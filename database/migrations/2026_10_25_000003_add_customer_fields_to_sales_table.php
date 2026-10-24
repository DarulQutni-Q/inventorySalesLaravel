<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Penjualan manual memakai customer_id (walk-in boleh null),
            // penjualan dari konfirmasi pesanan memakai keduanya.
            $table->foreignId('customer_id')->nullable()->after('user_id')
                ->constrained()->restrictOnDelete();
            $table->foreignId('customer_order_id')->nullable()->after('customer_id')
                ->constrained()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropConstrainedForeignId('customer_order_id');
            $table->dropConstrainedForeignId('customer_id');
        });
    }
};
