<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('shows the login form', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertViewIs('auth.login');
});

it('redirects the application home page to login', function () {
    $this->get('/')
        ->assertRedirect(route('login'));
});

it('shows first-admin registration only while there are no users', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee(route('register'));

    $this->get(route('register'))
        ->assertOk()
        ->assertViewIs('auth.register');
});

it('creates the first account as admin and shows a confirmation modal without signing in', function () {
    $this->from(route('register'))
        ->post(route('register.store'), [
            'name' => 'Admin Pertama',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
            'role' => 'cashier',
        ])
        ->assertRedirect(route('login'));

    $user = User::query()->where('email', 'admin@example.com')->firstOrFail();

    expect($user->role)->toBe('admin')
        ->and(Hash::check('password123', $user->password))->toBeTrue();

    $this->assertGuest();

    $this->get(route('login'))
        ->assertOk()
        ->assertSee('role="dialog"', false)
        ->assertSee('Akun admin berhasil dibuat');
});

it('closes first-admin registration after an account exists', function () {
    User::factory()->create(['role' => 'cashier']);

    $this->get(route('login'))
        ->assertOk()
        ->assertDontSee(route('register'));

    $this->get(route('register'))
        ->assertRedirect(route('login'))
        ->assertSessionHas('status', 'Pendaftaran admin pertama sudah ditutup.');

    $this->post(route('register.store'), [
        'name' => 'Admin Tambahan',
        'email' => 'other@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'terms' => '1',
    ])->assertRedirect(route('login'))
        ->assertSessionHas('status', 'Pendaftaran admin pertama sudah ditutup.');

    $this->assertDatabaseCount('users', 1);
    $this->assertGuest();
});

it('does not create an account when registration data is invalid', function () {
    $this->from(route('register'))
        ->post(route('register.store'), [
            'name' => 'Admin Pertama',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
            'terms' => '1',
        ])
        ->assertRedirect(route('register'))
        ->assertSessionHasErrors('password');

    $this->assertDatabaseCount('users', 0);
    $this->assertGuest();
});

it('signs in and redirects to the dashboard for the selected role', function (string $role, string $dashboardRoute) {
    $user = User::factory()->create(['role' => $role]);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
        'role' => $role,
    ])->assertRedirect(route($dashboardRoute));

    $this->assertAuthenticatedAs($user);
})->with([
    'admin' => ['admin', 'admin.dashboard'],
    'warehouse staff' => ['warehouse', 'gudang.dashboard'],
    'cashier' => ['cashier', 'kasir.dashboard'],
]);

it('rejects credentials when the selected role does not match the account', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $this->followingRedirects()
        ->from(route('login'))
        ->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
            'role' => 'warehouse',
        ])->assertOk()
        ->assertSee('role="alert"', false)
        ->assertSee('Email, password, atau peran yang dipilih tidak sesuai.');

    $this->assertGuest();
});

it('rejects roles that are not configured for the application', function () {
    $this->from(route('login'))
        ->post(route('login.store'), [
            'email' => 'user@example.com',
            'password' => 'password',
            'role' => 'super-admin',
        ])->assertRedirect(route('login'))
        ->assertSessionHasErrors('role');

    $this->assertGuest();
});

it('allows a user to open only the dashboard for their role', function (string $role, string $dashboardRoute, string $view) {
    $user = User::factory()->create(['role' => $role]);

    $this->actingAs($user)
        ->get(route($dashboardRoute))
        ->assertOk()
        ->assertViewIs($view);
})->with([
    'admin dashboard' => ['admin', 'admin.dashboard', 'admin.dashboard'],
    'warehouse dashboard' => ['warehouse', 'gudang.dashboard', 'gudang.dashboard'],
    'cashier dashboard' => ['cashier', 'kasir.dashboard', 'kasir.dashboard'],
]);

it('forbids a logged-in user from another role dashboard', function () {
    $user = User::factory()->create(['role' => 'cashier']);

    $this->actingAs($user)
        ->get(route('gudang.dashboard'))
        ->assertForbidden();
});

it('redirects guests to login before they open a role dashboard', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('login'));
});

it('logs out and redirects to the login form', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $this->actingAs($user)
        ->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});
