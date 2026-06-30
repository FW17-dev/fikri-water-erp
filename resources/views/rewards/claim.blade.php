@extends('layouts.app')
@section('title', 'Klaim Reward')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4">Klaim Reward</h1>
    <form action="{{ route('rewards.processClaim') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Customer</label>
            <select name="customer_id" class="w-full px-4 py-2 border rounded-lg" required>
                <option value="">-- Pilih --</option>
                @foreach($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }} (Poin: {{ $customer->points }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Hadiah</label>
            <select name="reward_id" class="w-full px-4 py-2 border rounded-lg" required>
                <option value="">-- Pilih --</option>
                @foreach($rewards as $reward)
                <option value="{{ $reward->id }}">{{ $reward->name }} ({{ $reward->points_required }} poin) - Stok: {{ $reward->stock }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">Klaim Sekarang</button>
    </form>
</div>
@endsection