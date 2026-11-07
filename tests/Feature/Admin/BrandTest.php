<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the brand creation form without category selection', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.brands.create'))
        ->assertOk()
        ->assertSee('name="name"', false)
        ->assertDontSee('category-picker')
        ->assertDontSee('category_id');
});

it('creates a brand with a generated code', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->post(route('admin.brands.store'), [
            'name' => 'Tiga Roda',
            'description' => 'Semen untuk konstruksi.',
        ])
        ->assertRedirect(route('admin.brands.index'))
        ->assertSessionHas('success', 'Brand Tiga Roda berhasil ditambahkan dengan kode TIR.');

    $this->assertDatabaseHas('brands', [
        'name' => 'Tiga Roda',
        'code' => 'TIR',
        'description' => 'Semen untuk konstruksi.',
    ]);
});

it('searches brands by name or code', function (string $search) {
    $admin = User::factory()->create(['role' => 'admin']);
    Brand::create(['name' => 'Tiga Roda', 'code' => 'TIR']);
    Brand::create(['name' => 'Nippon Paint', 'code' => 'NIP']);

    $this->actingAs($admin)
        ->get(route('admin.brands.index', ['search' => $search]))
        ->assertOk()
        ->assertSee('Tiga Roda')
        ->assertDontSee('Nippon Paint');
})->with([
    'brand name' => 'Tiga Roda',
    'brand code' => 'TIR',
]);

it('regenerates a brand code when its name changes', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $brand = Brand::create([
        'name' => 'Tiga Roda',
        'code' => 'TIR',
        'description' => 'Semen',
    ]);

    $this->actingAs($admin)
        ->put(route('admin.brands.update', $brand), [
            'name' => 'Nippon Paint',
            'description' => 'Semen untuk bangunan.',
        ])
        ->assertRedirect(route('admin.brands.index'))
        ->assertSessionHas('success', 'Brand berhasil diperbarui.');

    $this->assertDatabaseHas('brands', [
        'id' => $brand->id,
        'name' => 'Nippon Paint',
        'code' => 'NIP',
        'description' => 'Semen untuk bangunan.',
    ]);
});

it('keeps products when deleting their brand', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::create([
        'name' => 'Semen',
        'slug' => 'semen-001',
        'code' => 'SMN-001',
    ]);
    $brand = Brand::create([
        'name' => 'Tiga Roda',
        'code' => 'TIR',
    ]);
    $product = product::create([
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'sku' => 'SMN-0001',
        'name' => 'Semen Portland',
        'cost_price' => 50000,
        'selling_price' => 60000,
        'stock' => 10,
        'min_stock' => 2,
        'is_active' => true,
    ]);

    $this->actingAs($admin)
        ->delete(route('admin.brands.destroy', $brand))
        ->assertRedirect(route('admin.brands.index'))
        ->assertSessionHas('success', 'Brand berhasil dihapus.');

    $this->assertDatabaseMissing('brands', ['id' => $brand->id]);
    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'brand_id' => null,
    ]);
});
