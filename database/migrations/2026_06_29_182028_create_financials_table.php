<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financials', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // operasional, transfer, keluarga, pribadi
            $table->string('sub_category')->nullable();
            $table->string('description');
            $table->enum('type', ['income', 'expense'])->default('expense');
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financials');
    }
};