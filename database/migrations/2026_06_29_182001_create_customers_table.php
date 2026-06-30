<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->text('address')->nullable();
            $table->string('village')->nullable();
            $table->foreignId('area_id')->nullable()->constrained('areas')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->integer('points')->default(0);
            $table->decimal('debt', 15, 2)->default(0);
            $table->integer('galon_borrowed')->default(0);
            $table->timestamp('last_order_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};