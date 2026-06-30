@extends('layouts.app')
@section('title', isset($customer) ? 'Edit Customer' : 'Tambah Customer')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4">{{ isset($customer) ? 'Edit' : 'Tambah' }} Customer</h1>
    <form action="{{ isset($customer) ? route('customers.update', $customer) : route('customers.store') }}" method="POST">
        @csrf
        @if(isset($customer)) @method('PUT') @endif

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama</label>
            <input type="text" name="name" value="{{ old('name', $customer->name ?? '') }}" class="w-full px-4 py-2 border rounded-lg" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">No. HP</label>
            <input type="text" name="phone" value="{{ old('phone', $customer->phone ?? '') }}" class="w-full px-4 py-2 border rounded-lg" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Alamat</label>
            <textarea name="address" class="w-full px-4 py-2 border rounded-lg">{{ old('address', $customer->address ?? '') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Area</label>
            <select name="area_id" class="w-full px-4 py-2 border rounded-lg">
                <option value="">Pilih Area</option>
                @foreach($areas as $area)
                <option value="{{ $area->id }}" {{ (old('area_id', $customer->area_id ?? '') == $area->id) ? 'selected' : '' }}>
                    {{ $area->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Catatan</label>
            <textarea name="notes" class="w-full px-4 py-2 border rounded-lg">{{ old('notes', $customer->notes ?? '') }}</textarea>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Simpan</button>
    </form>
</div>
@endsection