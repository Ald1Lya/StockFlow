@extends('layouts.admin-layout')



@section('page-title', 'Supplier edit')

@section('content')
<section class="mx-auto max-w-3xl">
    <div class="mb-5">
        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">Master Data / Supplier</div>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Edit Supplier</h1>
       
    </div>
    <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST">
        @csrf
        @method('PUT')
    <div class="rounded-lg border border-[#e2dac8] bg-white p-5 sm:p-6">
        <div class="grid gap-4 sm:grid-cols-2">
            <label class="text-[12px] font-medium text-[#3a352d]">Nama supplier
                <input name="name" type="text" value="{{ old('name', $supplier->name) }}" placeholder="Nama perusahaan" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
            </label>
    
            <label class="text-[12px] font-medium text-[#3a352d]">Nomor telepon
                <input name="phone" type="tel" value="{{ old('phone', $supplier->phone) }}" placeholder="Nomor kontak" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
            </label>
            <label class="text-[12px] font-medium text-[#3a352d]">Email
                <input name="email" type="email" value="{{ old('email', $supplier->email) }}" placeholder="supplier@email.com" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
            </label>
            <label class="text-[12px] font-medium text-[#3a352d] sm:col-span-2">Alamat
                <textarea name="address" rows="3" placeholder="Alamat supplier" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">{{ old('address', $supplier->address) }}</textarea>
            </label>
        </div>
        <div class="mt-6 flex justify-end gap-2 border-t border-[#e2dac8] pt-4">
            <a href="{{ route('admin.suppliers.index') }}" class="rounded-md border border-[#e2dac8] px-4 py-2 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]">Batal</a>
            <button type="submit" class="rounded-md bg-[#161410] px-4 py-2 text-[12.5px] font-semibold text-[#ece7dd]">Simpan</button>
        </div>
        </form>
    </div>
</section>
@endsection