{{-- PUSAT ALERT/PESAN APLIKASI
     AUTH: error validasi/login dan info proses register.
     CRUD ADMIN: sukses/gagal saat tambah, edit, atau hapus data.
     CRUD GUDANG/KASIR & USER: gunakan komponen yang sama untuk pesan tiap role.
     Pilih type (error, info, success, warning); kalau desain perlu dibedakan nanti,
     tambahkan variannya di sini agar markup alert tidak tersebar di halaman. --}}
@props(['type' => 'info'])

@php
    $styles = [
        'error' => 'border-red-200 bg-red-50 text-red-700',
        'info' => 'border-[#e2dac8] bg-white text-[#7a7264]',
        'success' => 'border-green-200 bg-green-50 text-green-700',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
    ];

    $role = $type === 'error' ? 'alert' : 'status';
@endphp

<div {{ $attributes->class(['rounded-lg border px-4 py-3 text-sm', $styles[$type] ?? $styles['info']]) }}
     role="{{ $role }}">
    {{ $slot }}
</div>