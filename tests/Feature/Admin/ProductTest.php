<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lists categories from the database in the product form', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Category::create([
        'name' => 'Besi',
        'slug' => 'besi-001',
        'code' => 'BSI-001',
    ]);
    $category = Category::create([
        'name' => 'Semen',
        'slug' => 'semen-002',
        'code' => 'SMN-002',
    ]);
    Brand::create([
        'category_id' => $category->id,
        'name' => 'Tiga Roda',
        'code' => 'SEM-TR',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.products.create'))
        ->assertOk()
        ->assertSee('<option value="">Pilih kategori</option>', false)
        ->assertSeeInOrder(['Besi', 'Semen'])
        ->assertSee('Tiga Roda');
});

it('creates a product and displays it in the product list', function () {
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

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Semen Portland 50 kg',
            'description' => 'Semen untuk konstruksi.',
            'cost_price' => 50000,
            'selling_price' => 75000,
            'stock' => 10,
            'min_stock' => 2,
        ])
        ->assertRedirect(route('admin.products.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('products', [
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'name' => 'Semen Portland 50 kg',
        'cost_price' => 50000,
        'selling_price' => 75000,
    ]);

    $this->get(route('admin.products.index'))
        ->assertOk()
        ->assertSee('Semen Portland 50 kg')
        ->assertSee('Semen')
        ->assertSee('Tiga Roda')
        ->assertSee('Produk berhasil ditambahkan');
});

it('shows validation errors when product details are missing', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->from(route('admin.products.create'))
        ->post(route('admin.products.store'), [])
        ->assertRedirect(route('admin.products.create'))
        ->assertSessionHasErrors(['category_id', 'name', 'cost_price', 'selling_price', 'stock', 'min_stock']);

    $this->get(route('admin.products.create'))
        ->assertOk()
        ->assertSee('Produk belum tersimpan');
});
