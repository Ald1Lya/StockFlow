<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // READ: Menampilkan seluruh user
    public function index(Request $request)
    {
        $role = $request->query('role');
        $search = mb_substr(trim((string) $request->query('search', '')), 0, 100);

        $users = User::query()
            ->when(
                in_array($role, ['admin', 'warehouse', 'cashier'], true),
                fn (Builder $query) => $query->where('role', $role),
            )
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'selectedRole' => in_array($role, ['admin', 'warehouse', 'cashier'], true) ? $role : null,
            'search' => $search,
        ]);
    }

    // CREATE: Menampilkan form tambah user
    public function create()
    {
        return view('admin.users.create');
    }

    // CREATE: Menyimpan user baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'role' => ['required', 'in:admin,warehouse,cashier'],
        ]);

        User::create($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    // UPDATE: Menampilkan form edit
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function show(User $user): RedirectResponse
    {
        return redirect()->route('admin.users.edit', $user);
    }

    // UPDATE: Memperbarui user
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'unique:users,email,'.$user->id,
            ],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'role' => ['required', 'in:admin,warehouse,cashier'],
        ]);

        // Password kosong berarti tidak diubah
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    // DELETE: Menghapus user
    public function destroy(Request $request, User $user)
    {
        // Mencegah admin menghapus akun sendiri
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
