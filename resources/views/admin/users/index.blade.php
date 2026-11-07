@extends('layouts.admin-layout')

@section('page-title', 'Pengguna')

@section('content')
<x-modal
    :show="session()->has('success')"
    title="Berhasil"
    :message="session('success', '')"
    action-text="Oke, mengerti"
/>

<section>
    <div class="mb-5 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">Master Data</div>
                        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Manajemen Pengguna</h1>
                        <p class="mt-1 text-[13px] text-[#7a7264]">{{ $users->total() }} pengguna terdaftar</p>
                    </div>
                    <div class="flex w-full flex-wrap items-center justify-between gap-2 lg:w-auto lg:justify-end">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="flex min-w-0 flex-1 items-center gap-2 sm:flex-none">
                            @if ($selectedRole)
                                <input type="hidden" name="role" value="{{ $selectedRole }}">
                            @endif
                            <label for="user-search" class="sr-only">Cari nama atau email</label>
                            <div class="relative min-w-0 flex-1 sm:w-64 sm:flex-none">
                                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#8a8073]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.2-5.2m0 0A7.5 7.5 0 1 0 5.2 5.2a7.5 7.5 0 0 0 10.6 10.6Z"/>
                                </svg>
                                <input id="user-search" type="search" name="search" value="{{ $search }}" placeholder="Cari berdasarkan nama atau email..." class="w-full rounded-md border border-[#e2dac8] bg-white py-2 pl-9 pr-3 text-[12.5px] text-[#3a352d] placeholder:text-[#a89f8e] focus:border-[#c9934a] focus:outline-none focus:ring-2 focus:ring-[#c9934a]/15">
                            </div>
                            <button type="submit" class="rounded-md border border-[#e2dac8] bg-white px-3 py-2 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]">Cari</button>
                        </form>
                        <a href="{{ route('admin.users.create') }}" class="inline-flex shrink-0 items-center gap-1.5 rounded-md bg-[#161410] px-3 py-2 text-[12.5px] font-semibold text-[#ece7dd] hover:bg-[#2a2520]">
                        <svg class="h-3.5 w-3.5 text-[#c9934a]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Pengguna
                        </a>
                    </div>
    </div>

    <div class="mb-4 flex flex-wrap items-center gap-2">
        @foreach ([
            'all' => 'Semua',
            'admin' => 'Admin',
            'warehouse' => 'Staff Gudang',
            'cashier' => 'Kasir',
        ] as $roleValue => $roleLabel)
            @php($isSelectedRole = $roleValue === 'all' ? $selectedRole === null : $selectedRole === $roleValue)
            <a href="{{ route('admin.users.index', array_filter([
                'role' => $roleValue === 'all' ? null : $roleValue,
                'search' => $search !== '' ? $search : null,
            ])) }}"
               @if ($isSelectedRole) aria-current="page" @endif
               class="rounded-md px-3 py-1.5 text-[12px] font-semibold {{ $isSelectedRole ? 'bg-[#161410] text-[#ece7dd]' : 'border border-[#e2dac8] bg-white text-[#3a352d] hover:bg-[#f7f4ee]' }}">
                {{ $roleLabel }}
            </a>
        @endforeach
    </div>

                <div class="overflow-hidden rounded-lg border border-[#e2dac8] bg-white">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-[12.5px]">
                            <thead>
                                <tr class="border-b border-[#e2dac8] bg-[#f7f4ee]/60 text-[10.5px] uppercase tracking-wider text-[#8a8073]">
                                    <th class="px-5 py-3 font-semibold">No.</th>
                                    <th class="px-5 py-3 font-semibold">Nama</th>
                                    <th class="px-3 py-3 font-semibold">Email</th>
                                    <th class="px-3 py-3 font-semibold">Peran</th>
                                    <th class="px-3 py-3 font-semibold">Terdaftar</th>
                                    <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#e2dac8]/70 text-[#3a352d]">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-[#f7f4ee]/50">
                                        <td class="px-5 py-3 font-mono text-[11.5px] text-[#8a8073]">{{ ($users->firstItem() ?? 0) + $loop->index }}</td>
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-2.5">
                                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#161410] text-[10px] font-semibold text-[#c9934a]">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                                <span class="font-medium text-[#161410]">{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 text-[11.5px] text-[#7a7264]">{{ $user->email }}</td>
                                        <td class="px-3 py-3">
                                            <span class="rounded bg-[#161410] px-1.5 py-0.5 text-[10.5px] font-semibold text-[#c9934a]">
                                                {{ ['admin' => 'Admin', 'warehouse' => 'Staff Gudang', 'cashier' => 'Kasir'][$user->role] ?? $user->role }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-3 font-mono text-[11px] text-[#8a8073]">{{ $user->created_at->format('Y-m-d') }}</td>
                                        <td class="px-5 py-3 text-right text-[11.5px]">
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                            class="font-medium text-[#a67b3f] hover:underline">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.users.destroy', $user) }}"
                                                method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="ml-3 font-medium text-red-500 hover:underline">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-5 py-8 text-center text-[13px] text-[#7a7264]">Belum ada pengguna.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-3 border-t border-[#e2dac8] pt-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-[11.5px] text-[#7a7264]">
                        Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna
                    </p>
                    {{ $users->links() }}
                </div>
</section>
@endsection
