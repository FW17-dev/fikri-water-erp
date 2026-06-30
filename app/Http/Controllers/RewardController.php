<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\Customer;
use App\Models\RewardClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RewardController extends Controller
{
    public function index(Request $request)
    {
        $query = Reward::query();
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        $rewards = $query->paginate(15);
        return view('rewards.index', compact('rewards'));
    }

    public function create()
    {
        return view('rewards.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'points_required' => 'required|integer|min:1',
            'stock'           => 'required|integer|min:0',
            'is_active'       => 'sometimes|boolean',
        ]);
        $validated['is_active'] = $request->has('is_active');
        Reward::create($validated);
        return redirect()->route('rewards.index')->with('success', 'Reward berhasil ditambahkan!');
    }

    public function edit(Reward $reward)
    {
        return view('rewards.form', compact('reward'));
    }

    public function update(Request $request, Reward $reward)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'points_required' => 'required|integer|min:1',
            'stock'           => 'required|integer|min:0',
            'is_active'       => 'sometimes|boolean',
        ]);
        $validated['is_active'] = $request->has('is_active');
        $reward->update($validated);
        return redirect()->route('rewards.index')->with('success', 'Reward diupdate!');
    }

    public function destroy(Reward $reward)
    {
        $reward->delete();
        return back()->with('success', 'Reward dihapus!');
    }

    // Form klaim reward
    public function claimForm()
    {
        $customers = Customer::orderBy('points', 'desc')->get();
        $rewards = Reward::where('is_active', true)->where('stock', '>', 0)->get();
        return view('rewards.claim', compact('customers', 'rewards'));
    }

    // Proses klaim
    public function claim(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'reward_id'   => 'required|exists:rewards,id',
        ]);

        $customer = Customer::find($validated['customer_id']);
        $reward = Reward::find($validated['reward_id']);

        if ($customer->points < $reward->points_required) {
            return back()->with('error', 'Poin customer tidak mencukupi!');
        }

        if ($reward->stock <= 0) {
            return back()->with('error', 'Stok reward habis!');
        }

        DB::transaction(function () use ($customer, $reward) {
            // Kurangi poin customer
            $customer->decrement('points', $reward->points_required);

            // Kurangi stok reward
            $reward->decrement('stock', 1);

            // Catat klaim
            RewardClaim::create([
                'customer_id' => $customer->id,
                'reward_id'   => $reward->id,
                'points_used' => $reward->points_required,
                'claimed_at'  => now(),
            ]);
        });

        return redirect()->route('rewards.claim')->with('success', 'Reward berhasil diklaim!');
    }
}