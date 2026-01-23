<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('detail_orders', function (Blueprint $table) {
            // Pastikan kolom ini juga 20, 2 agar sinkron dengan total_harga
            $table->decimal('subtotal_harga', 20, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('detail_orders', function (Blueprint $table) {
            $table->decimal('subtotal_harga', 10, 2)->change();
        });
    }
};
