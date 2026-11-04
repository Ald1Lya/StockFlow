<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';

        $brands = Brand::query()
            ->with('category')
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

        return view('admin.brands.index', [
            'brands' => $brands,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.brands.create', [
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:brands,name'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
        ]);

        $category = Category::findOrFail($data['category_id']);
        $code = $this->makeCode($category, $data['name']);

        if (Brand::query()->where('code', $code)->exists()) {
            return back()
                ->withErrors(['name' => "Kode {$code} sudah digunakan. Pilih nama brand yang menghasilkan inisial berbeda."])
                ->withInput();
        }

        $data['code'] = $code;
        Brand::create($data);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Brand berhasil ditambahkan.');
    }

    public function edit(Brand $brand): View
    {
        return view('admin.brands.edit', [
            'brand' => $brand,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:brands,name,'.$brand->id],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
        ]);

        $category = Category::findOrFail($data['category_id']);
        $code = $this->makeCode($category, $data['name']);

        if (Brand::query()->where('code', $code)->where('id', '!=', $brand->id)->exists()) {
            return back()
                ->withErrors(['name' => "Kode {$code} sudah digunakan. Pilih nama brand yang menghasilkan inisial berbeda."])
                ->withInput();
        }

        $data['code'] = $code;
        $brand->update($data);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Brand berhasil diperbarui.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $brand->delete();

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Brand berhasil dihapus.');
    }

    private function makeCode(Category $category, string $brandName): string
    {
        $categoryPrefix = Str::upper($category->code);
        $brandInitials = collect(preg_split('/[\s-]+/', trim($brandName)))
            ->filter()
            ->map(fn (string $word) => Str::upper(Str::substr($word, 0, 1)))
            ->implode('');

        return $categoryPrefix.'-'.$brandInitials;
    }
}
