<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price_sar', 10, 2)->nullable();
            $table->decimal('compare_price_sar', 10, 2)->nullable();
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('unit_amount_sar', 12, 2)->nullable();
            $table->decimal('total_amount_sar', 12, 2)->nullable();
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('grand_total_sar', 12, 2)->nullable();
            $table->decimal('shipping_amount_sar', 12, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', fn (Blueprint $table) => $table->dropColumn(['price_sar', 'compare_price_sar']));
        Schema::table('order_items', fn (Blueprint $table) => $table->dropColumn(['unit_amount_sar', 'total_amount_sar']));
        Schema::table('orders', fn (Blueprint $table) => $table->dropColumn(['grand_total_sar', 'shipping_amount_sar']));
    }
};