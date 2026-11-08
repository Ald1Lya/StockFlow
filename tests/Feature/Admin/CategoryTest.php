<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('does not expose category edit or update actions', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::create([
        'name' => 'Aksesoris',
        'slug' => 'aksesoris-001',
        'code' => 'CT-AKS',
        'description' => 'Deskripsi lama',
    ]);

    $this->actingAs($admin)
        ->get("/admin/categories/{$category->id}/edit")
        ->assertNotFound();

    $this->actingAs($admin)
        ->put("/admin/categories/{$category->id}", ['name' => 'Aksesoris Rumah'])
        ->assertMethodNotAllowed();
});

it('deletes categories from the index page', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::create([
        'name' => 'Aksesoris',
        'slug' => 'aksesoris-001',
        'code' => 'CT-AKS',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.categories.index'))
        ->assertOk()
        ->assertSee('action="'.route('admin.categories.destroy', $category).'"', false)
        ->assertSee('Hapus kategori Aksesoris');

    $this->actingAs($admin)
        ->delete(route('admin.categories.destroy', $category))
        ->assertRedirect(route('admin.categories.index'))
        ->assertSessionHas('success', 'Kategori Berhasil Dihapus.');

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

it('searches categories by name or code', function (string $search) {
    $admin = User::factory()->create(['role' => 'admin']);
    Category::create([
        'name' => 'Aksesoris',
        'slug' => 'aksesoris-001',
        'code' => 'CT-AKS',
    ]);
    Category::create([
        'name' => 'Minuman',
        'slug' => 'minuman-002',
        'code' => 'CT-MNM',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.categories.index', ['search' => $search]))
        ->assertOk()
        ->assertSee('Aksesoris')
        ->assertDontSee('Minuman');
})->with([
    'category name' => 'Aksesoris',
    'category code' => 'CT-AKS',
]);

it('shows all matching category results without pagination', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    foreach (range(1, 11) as $number) {
        Category::create([
            'name' => sprintf('Peralatan %02d', $number),
            'slug' => sprintf('peralatan-%02d', $number),
            'code' => sprintf('CT-PERALATAN-%02d', $number),
        ]);
    }

    $this->actingAs($admin)
        ->get(route('admin.categories.index', ['search' => 'Peralatan']))
        ->assertOk()
        ->assertSee('Peralatan 01')
        ->assertSee('Peralatan 11')
        ->assertDontSee('page=2')
        ->assertViewHas('categories', fn ($categories) => $categories->count() === 11);
});
