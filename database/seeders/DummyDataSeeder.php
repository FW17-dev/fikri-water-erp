<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Area;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Courier;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Production;
use App\Models\Financial;
use App\Models\Reward;
use App\Models\RewardClaim;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
            // Nonaktifkan audit log sementara
    \Illuminate\Support\Facades\DB::table('audit_logs')->truncate();
    \App\Models\AuditLog::unsetEventDispatcher();
        // 1. User (sudah ada dari UserSeeder, tapi kita tambahkan beberapa)
        User::firstOrCreate(
    ['email' => 'admin@fikri.com'],
    [
        'name' => 'Admin Fikri',
        'password' => Hash::make('password123'),
        'role' => 'owner',
    ]
);

        User::firstOrCreate(
    ['email' => 'staff@fikri.com'],
    [
        'name' => 'Staff Depot',
        'password' => Hash::make('password123'),
        'role' => 'admin',
    ]
);

        User::firstOrCreate(
    ['email' => 'kurir@fikri.com'],
    [
        'name' => 'Kurir Andi',
        'password' => Hash::make('password123'),
        'role' => 'courier',
    ]
);

        // 2. Area
        $areas = ['Kampung Melayu', 'Kampung Baru', 'Kampung Sawah', 'Kampung Pulo', 'Kampung Tengah', 'Perumahan Permata', 'Perumahan Mutiara'];
        foreach ($areas as $area) {
            Area::create(['name' => $area]);
        }

        // 3. Supplier
        $suppliers = [
            ['name' => 'PDAM Tirta', 'phone' => '021-1234567', 'address' => 'Jl. Raya No. 1, Jakarta'],
            ['name' => 'Aqua Galon', 'phone' => '021-7890123', 'address' => 'Jl. Industri No. 5, Bekasi'],
            ['name' => 'Air Murni Jaya', 'phone' => '021-4567890', 'address' => 'Jl. Kemang No. 10, Depok'],
        ];
        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        // 4. Produk
        $products = [
            ['name' => 'Galon 19L', 'category' => 'Air Minum', 'price' => 18000, 'reward_points' => 1, 'supplier_id' => 1],
            ['name' => 'Galon 15L', 'category' => 'Air Minum', 'price' => 15000, 'reward_points' => 1, 'supplier_id' => 1],
            ['name' => 'Botol 600ml', 'category' => 'Air Minum Kemasan', 'price' => 5000, 'reward_points' => 1, 'supplier_id' => 2],
            ['name' => 'Botol 1.5L', 'category' => 'Air Minum Kemasan', 'price' => 8000, 'reward_points' => 1, 'supplier_id' => 2],
            ['name' => 'Galon 19L Premium', 'category' => 'Air Minum', 'price' => 22000, 'reward_points' => 2, 'supplier_id' => 3],
        ];
        foreach ($products as $product) {
            Product::create($product);
        }

        // 5. Kurir
        $couriers = [
            ['name' => 'Andi', 'phone' => '08123456789', 'is_active' => true],
            ['name' => 'Budi', 'phone' => '08129876543', 'is_active' => true],
            ['name' => 'Cici', 'phone' => '08125555555', 'is_active' => true],
            ['name' => 'Dedi', 'phone' => '08127777777', 'is_active' => false],
        ];
        foreach ($couriers as $courier) {
            Courier::create($courier);
        }

        // 6. Customer (20 customer)
        $customers = [
            ['name' => 'Ahmad Fauzi', 'phone' => '0811111111', 'address' => 'Jl. Melayu No. 5', 'area_id' => 1, 'points' => 0, 'debt' => 0],
            ['name' => 'Siti Rahayu', 'phone' => '0812222222', 'address' => 'Jl. Baru No. 10', 'area_id' => 2, 'points' => 0, 'debt' => 0],
            ['name' => 'Budi Santoso', 'phone' => '0813333333', 'address' => 'Jl. Sawah No. 3', 'area_id' => 3, 'points' => 0, 'debt' => 0],
            ['name' => 'Dewi Lestari', 'phone' => '0814444444', 'address' => 'Jl. Pulo No. 7', 'area_id' => 4, 'points' => 0, 'debt' => 0],
            ['name' => 'Rudi Hartono', 'phone' => '0815555555', 'address' => 'Jl. Tengah No. 12', 'area_id' => 5, 'points' => 0, 'debt' => 0],
            ['name' => 'Sari Indah', 'phone' => '0816666666', 'address' => 'Perumahan Permata Blok A1', 'area_id' => 6, 'points' => 0, 'debt' => 0],
            ['name' => 'Joko Widodo', 'phone' => '0817777777', 'address' => 'Perumahan Mutiara Blok B2', 'area_id' => 7, 'points' => 0, 'debt' => 0],
            ['name' => 'Ratna Sari', 'phone' => '0818888888', 'address' => 'Jl. Melayu No. 15', 'area_id' => 1, 'points' => 0, 'debt' => 0],
            ['name' => 'Agus Salim', 'phone' => '0819999999', 'address' => 'Jl. Baru No. 22', 'area_id' => 2, 'points' => 0, 'debt' => 0],
            ['name' => 'Dian Pratiwi', 'phone' => '0810000000', 'address' => 'Jl. Sawah No. 8', 'area_id' => 3, 'points' => 0, 'debt' => 0],
            ['name' => 'Firman', 'phone' => '0811010101', 'address' => 'Jl. Pulo No. 3', 'area_id' => 4, 'points' => 0, 'debt' => 0],
            ['name' => 'Lina Marlina', 'phone' => '0812020202', 'address' => 'Jl. Tengah No. 6', 'area_id' => 5, 'points' => 0, 'debt' => 0],
            ['name' => 'Tono', 'phone' => '0813030303', 'address' => 'Perumahan Permata Blok C5', 'area_id' => 6, 'points' => 0, 'debt' => 0],
            ['name' => 'Wati', 'phone' => '0814040404', 'address' => 'Perumahan Mutiara Blok D3', 'area_id' => 7, 'points' => 0, 'debt' => 0],
            ['name' => 'Hendra', 'phone' => '0815050505', 'address' => 'Jl. Melayu No. 20', 'area_id' => 1, 'points' => 0, 'debt' => 0],
            ['name' => 'Yuni', 'phone' => '0816060606', 'address' => 'Jl. Baru No. 30', 'area_id' => 2, 'points' => 0, 'debt' => 0],
            ['name' => 'Anwar', 'phone' => '0817070707', 'address' => 'Jl. Sawah No. 15', 'area_id' => 3, 'points' => 0, 'debt' => 0],
            ['name' => 'Rina', 'phone' => '0818080808', 'address' => 'Jl. Pulo No. 9', 'area_id' => 4, 'points' => 0, 'debt' => 0],
            ['name' => 'Taufik', 'phone' => '0819090909', 'address' => 'Jl. Tengah No. 18', 'area_id' => 5, 'points' => 0, 'debt' => 0],
            ['name' => 'Mega', 'phone' => '0810101010', 'address' => 'Perumahan Permata Blok E7', 'area_id' => 6, 'points' => 0, 'debt' => 0],
        ];
        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        // 7. Produksi Air (pembelian dari supplier)
        $productions = [
            ['supplier_id' => 1, 'quantity' => 400, 'price_per_unit' => 12000, 'total_price' => 4800000, 'purchase_date' => Carbon::now()->subDays(10)],
            ['supplier_id' => 2, 'quantity' => 200, 'price_per_unit' => 13000, 'total_price' => 2600000, 'purchase_date' => Carbon::now()->subDays(5)],
            ['supplier_id' => 3, 'quantity' => 300, 'price_per_unit' => 14000, 'total_price' => 4200000, 'purchase_date' => Carbon::now()->subDays(2)],
        ];
        foreach ($productions as $prod) {
            Production::create($prod);
        }

        // 8. Order (50 order selama 30 hari terakhir)
        $statuses = ['pending', 'processing', 'delivered', 'completed', 'cancelled'];
        $paymentMethods = ['cash', 'transfer', 'debt'];
        
        for ($i = 0; $i < 50; $i++) {
            $customer = Customer::inRandomOrder()->first();
            $product = Product::inRandomOrder()->first();
            $courier = Courier::where('is_active', true)->inRandomOrder()->first();
            $quantity = rand(1, 5);
            $totalPrice = $product->price * $quantity;
            $status = $statuses[array_rand($statuses)];
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
            
            // Buat tanggal acak dalam 30 hari terakhir
            $createdAt = Carbon::now()->subDays(rand(0, 29));
            
            $order = Order::create([
                'customer_id' => $customer->id,
                'product_id' => $product->id,
                'courier_id' => $courier ? $courier->id : null,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'payment_method' => $paymentMethod,
                'status' => $status,
                'notes' => rand(0, 1) ? 'Catatan order #' . ($i+1) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Jika status delivered atau completed, set delivered_at
            if (in_array($status, ['delivered', 'completed'])) {
                $order->update([
                    'delivered_at' => $createdAt->addHours(rand(1, 12)),
                ]);
            }

            // Tambah poin customer
            $pointsEarned = $product->reward_points * $quantity;
            $customer->increment('points', $pointsEarned);
            $customer->update(['last_order_at' => $createdAt]);

            // Jika payment method debt, tambahkan hutang
            if ($paymentMethod == 'debt') {
                $customer->increment('debt', $totalPrice);
            }
        }

        // 9. Transaksi Keuangan (30 transaksi)
        $categories = ['operasional', 'transfer', 'keluarga', 'pribadi'];
        $subCategories = [
            'operasional' => ['Pembelian air', 'Tutup galon', 'Tissue', 'Filter', 'BBM', 'Service motor', 'Listrik', 'Kuota'],
            'transfer' => ['Tabungan Rp75.000/hari'],
            'keluarga' => ['Makan pegawai', 'Gaji adik'],
            'pribadi' => ['Kuliah', 'Bensin', 'Jajan'],
        ];
        $types = ['income', 'expense'];

        for ($i = 0; $i < 30; $i++) {
            $category = $categories[array_rand($categories)];
            $subCategoryArray = $subCategories[$category];
            $subCategory = $subCategoryArray[array_rand($subCategoryArray)];
            $type = $types[array_rand($types)];
            $amount = rand(10000, 1000000);
            
            // Jika income, amount biasanya lebih kecil (kecuali transfer)
            if ($type == 'income' && $category != 'transfer') {
                $amount = rand(5000, 500000);
            }
            // Jika expense, amount bisa besar
            if ($type == 'expense' && in_array($category, ['operasional', 'keluarga'])) {
                $amount = rand(50000, 2000000);
            }

            Financial::create([
                'category' => $category,
                'sub_category' => $subCategory,
                'description' => 'Transaksi ' . ($i+1) . ' - ' . $subCategory,
                'type' => $type,
                'amount' => $amount,
                'transaction_date' => Carbon::now()->subDays(rand(0, 29)),
                'order_id' => null,
            ]);
        }

        // 10. Reward
        $rewards = [
            ['name' => 'Gelas Cantik', 'points_required' => 10, 'stock' => 20, 'is_active' => true],
            ['name' => 'Tumbler', 'points_required' => 25, 'stock' => 10, 'is_active' => true],
            ['name' => 'Payung', 'points_required' => 30, 'stock' => 5, 'is_active' => true],
            ['name' => 'Mug Custom', 'points_required' => 15, 'stock' => 15, 'is_active' => true],
            ['name' => 'Voucher 50K', 'points_required' => 40, 'stock' => 3, 'is_active' => true],
        ];
        foreach ($rewards as $reward) {
            Reward::create($reward);
        }

        // 11. Reward Claim (beberapa customer sudah klaim)
        for ($i = 0; $i < 5; $i++) {
            $customer = Customer::where('points', '>', 15)->inRandomOrder()->first();
            if (!$customer) break;
            
            $reward = Reward::where('is_active', true)->where('stock', '>', 0)->inRandomOrder()->first();
            if (!$reward) break;
            
            if ($customer->points >= $reward->points_required) {
                $customer->decrement('points', $reward->points_required);
                $reward->decrement('stock', 1);
                
                RewardClaim::create([
                    'customer_id' => $customer->id,
                    'reward_id' => $reward->id,
                    'points_used' => $reward->points_required,
                    'claimed_at' => Carbon::now()->subDays(rand(0, 10)),
                ]);
            }
        }

        $this->command->info('✅ Data dummy berhasil dibuat!');
    }
}