<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a brand with a generated code', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::create([
        'name' => 'Semen',
        'slug' => 'semen-001',
        'code' => 'SMN-001',
    ]);

    $this->actingAs($admin)
        ->post(route('admin.brands.store'), [
            'name' => 'Tiga Roda',
            'category_id' => $category->id,
            'description' => 'Semen untuk konstruksi.',
        ])
        ->assertRedirect(route('admin.brands.index'))
        ->assertSessionHas('success', 'Brand berhasil ditambahkan.');

    $this->assertDatabaseHas('brands', [
        'category_id' => $category->id,
        'name' => 'Tiga Roda',
        'code' => 'SMN-001-TR',
        'description' => 'Semen untuk konstruksi.',
    ]);
});

it('searches brands by name or code', function (string $search) {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::create([
        'name' => 'Semen',
        'slug' => 'semen-001',
        'code' => 'SMN-001',
    ]);
    Brand::create(['category_id' => $category->id, 'name' => 'Tiga Roda', 'code' => 'SEM-TR']);
    Brand::create(['category_id' => $category->id, 'name' => 'Nippon Paint', 'code' => 'SEM-NP']);

    $this->actingAs($admin)
        ->get(route('admin.brands.index', ['search' => $search]))
        ->assertOk()
        ->assertSee('Tiga Roda')
        ->assertDontSee('Nippon Paint');
})->with([
    'brand name' => 'Tiga Roda',
    'brand code' => 'SEM-TR',
]);

it('regenerates a brand code when its category changes', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::create([
        'name' => 'Semen',
        'slug' => 'semen-001',
        'code' => 'SMN-001',
    ]);
    $paintCategory = Category::create([
        'name' => 'Cat',
        'slug' => 'cat-002',
        'code' => 'CAT-002',
    ]);
    $brand = Brand::create([
        'category_id' => $category->id,
        'name' => 'Tiga Roda',
        'code' => 'SEM-TR',
        'description' => 'Semen',
    ]);

    $this->actingAs($admin)
        ->put(route('admin.brands.update', $brand), [
            'name' => 'Tiga Roda',
            'category_id' => $paintCategory->id,
            'description' => 'Semen untuk bangunan.',
        ])
        ->assertRedirect(route('admin.brands.index'))
        ->assertSessionHas('success', 'Brand berhasil diperbarui.');

    $this->assertDatabaseHas('brands', [
        'id' => $brand->id,
        'category_id' => $paintCategory->id,
        'name' => 'Tiga Roda',
        'code' => 'CAT-002-TR',
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
        'category_id' => $category->id,
        'name' => 'Tiga Roda',
        'code' => 'SEM-TR',
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
