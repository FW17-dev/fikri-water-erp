@extends('layouts.app')
@section('title', 'Daftar Customer')
@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Daftar Customer</h1>
    <a href="{{ route('customers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">+ Tambah Customer</a>
</div>

<form method="GET" class="mb-4">
    <input type="text" name="search" placeholder="Cari nama atau no HP..." class="w-full md:w-1/3 px-4 py-2 border rounded-lg" value="{{ request('search') }}">
    <button type="submit" class="ml-2 bg-gray-600 text-white px-4 py-2 rounded text-sm">Cari</button>
</form>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Nama</th>
                <th class="p-2 text-left">No. HP</th>
                <th class="p-2 text-left">Area</th>
                <th class="p-2 text-left">Poin</th>
                <th class="p-2 text-left">Piutang</th>
                <th class="p-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
            <tr class="border-b">
                <td class="p-2">{{ $customer->name }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ $customer->area->name ?? '-' }}</td>
                <td>{{ $customer->points }}</td>
                <td>Rp {{ number_format($customer->debt, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('customers.edit', $customer) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus customer ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline ml-2">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-4 text-center text-gray-500">Belum ada customer. Yuk tambahkan!</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $customers->links() }}</div>
@endsection