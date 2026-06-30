<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('backups', function (Blueprint $table) {
            if (!Schema::hasColumn('backups', 'file_name')) {
                $table->string('file_name')->after('id');
            }
            if (!Schema::hasColumn('backups', 'file_path')) {
                $table->string('file_path')->after('file_name');
            }
            if (!Schema::hasColumn('backups', 'size')) {
                $table->decimal('size', 10, 2)->after('file_path');
            }
            if (!Schema::hasColumn('backups', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    public function down(): void
    {
        Schema::table('backups', function (Blueprint $table) {
            $table->dropColumn(['file_name', 'file_path', 'size']);
        });
    }
};