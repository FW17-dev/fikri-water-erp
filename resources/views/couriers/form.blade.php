@extends('layouts.app')
@section('title', isset($courier) ? 'Edit Kurir' : 'Tambah Kurir')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4">{{ isset($courier) ? 'Edit' : 'Tambah' }} Kurir</h1>
    <form action="{{ isset($courier) ? route('couriers.update', $courier) : route('couriers.store') }}" method="POST">
        @csrf
        @if(isset($courier)) @method('PUT') @endif

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Kurir</label>
            <input type="text" name="name" value="{{ old('name', $courier->name ?? '') }}" class="w-full px-4 py-2 border rounded-lg" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">No. HP</label>
            <input type="text" name="phone" value="{{ old('phone', $courier->phone ?? '') }}" class="w-full px-4 py-2 border rounded-lg" placeholder="08123456789">
        </div>

        <div class="mb-4 flex items-center">
            <input type="checkbox" name="is_active" value="1" {{ (old('is_active', $courier->is_active ?? true) == 1) ? 'checked' : '' }} class="mr-2">
            <label class="text-gray-700 text-sm font-bold">Aktif</label>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Simpan</button>
    </form>
</div>
@endsection