@extends('layouts.app')
@section('title', 'Daftar Produk')
@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Daftar Produk</h1>
    <a href="{{ route('products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">+ Tambah Produk</a>
</div>

<form method="GET" class="mb-4">
    <input type="text" name="search" placeholder="Cari nama produk..." class="w-full md:w-1/3 px-4 py-2 border rounded-lg" value="{{ request('search') }}">
    <button type="submit" class="ml-2 bg-gray-600 text-white px-4 py-2 rounded text-sm">Cari</button>
</form>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Nama</th>
                <th class="p-2 text-left">Kategori</th>
                <th class="p-2 text-left">Harga</th>
                <th class="p-2 text-left">Poin</th>
                <th class="p-2 text-left">Supplier</th>
                <th class="p-2 text-left">Status</th>
                <th class="p-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr class="border-b">
                <td class="p-2">{{ $product->name }}</td>
                <td>{{ $product->category ?? '-' }}</td>
                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td>{{ $product->reward_points }}</td>
                <td>{{ $product->supplier->name ?? '-' }}</td>
                <td>
                    <span class="px-2 py-1 rounded text-xs {{ $product->is_active ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('products.edit', $product) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus produk ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline ml-2">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="p-4 text-center text-gray-500">Belum ada produk. Yuk tambahkan!</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $products->links() }}</div>
@endsection