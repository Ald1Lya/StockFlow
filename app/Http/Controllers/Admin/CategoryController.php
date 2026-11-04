<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // READ: Menampilkan seluruh kategori fitur search
    public function index(Request $request)
    {
        $search = $request->query('search');
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';

        $categories = Category::query()
            ->withCount('products')
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.categories.index', [
            'categories' => $categories,
            'search' => $search,
        ]);
    }

    // menampilkan form tambah kategori dan untuk edit si create dan edit itu beda view, jadi create itu buat tambah kategori baru, sedangkan edit itu buat edit kategori yang sudah ada
    public function create()
    {
        return view('admin.categories.create');
    }

    // untuk menambah
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
        ]);

        // LOGIKA SMART PREFIX 3 HURUF
        $words = explode(' ', strtoupper(trim($data['name'])));
        $code = '';

        if (count($words) == 1) {
            $code = substr($words[0], 0, 3);
        } elseif (count($words) == 2) {
            $code = substr($words[0], 0, 2).substr($words[1], 0, 1);
        } else {
            $code = substr($words[0], 0, 1).substr($words[1], 0, 1).substr($words[2], 0, 1);
        }

        // LOGIKA ANTI-BENTROK (Contoh: BEB -> BEB1 -> BEB2)
        $originalCode = $code;
        $counter = 1;
        while (Category::where('code', $code)->exists()) {
            $code = $originalCode.$counter;
            $counter++;
        }

        $data['code'] = $code;

        // Logika Slug
        $baseSlug = Str::slug($data['name']);
        $lastCategory = Category::latest('id')->first();
        $number = $lastCategory ? $lastCategory->id + 1 : 1;
        $data['slug'] = $baseSlug.'-'.str_pad($number, 3, '0', STR_PAD_LEFT);

        // Simpan ke Database
        Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "Kategori {$data['name']} berhasil ditambahkan dengan kode {$code}.");
    }

    // untuk edit
    public function edit(Category $category)
    {
        return view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:categories,code,'.$category->id],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori Berhasil Dihapus.');
    }
}
