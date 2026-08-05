<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        DB::table('pages')->updateOrInsert(
            ['slug' => 'shipping-returns'],
            [
                'title' => 'Shipping & Returns',
                'content' => '<h2>Shipping</h2><p>We deliver orders across Pakistan. Delivery times and applicable shipping charges are shown during checkout.</p><h2>Returns</h2><p>If you need to return an item, please contact NAAS Shopping with your order details. Items must be unused and returned in their original condition.</p>',
                'type' => 'custom',
                'meta_title' => 'Shipping & Returns - NAAS Shopping',
                'meta_description' => 'Shipping, delivery and return information for NAAS Shopping orders.',
                'is_active' => true,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        if (Schema::hasTable('pages')) {
            DB::table('pages')->where('slug', 'shipping-returns')->delete();
        }
    }
};
