<?php

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('stores a supplier with its manually entered code', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->post(route('admin.suppliers.store'), [
            'name' => 'PT Contoh',
            'code' => 'SUP-001',
        ])
        ->assertRedirect(route('admin.suppliers.index'))
        ->assertSessionHas('success', 'Supplier PT Contoh berhasil ditambahkan.');

    $this->assertDatabaseHas('suppliers', [
        'name' => 'PT Contoh',
        'code' => 'SUP-001',
    ]);
});

it('requires a supplier code instead of generating one automatically', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->post(route('admin.suppliers.store'), [
            'name' => 'PT Tanpa Kode',
        ])
        ->assertSessionHasErrors('code');

    expect(Supplier::query()->where('name', 'PT Tanpa Kode')->exists())->toBeFalse();
});
