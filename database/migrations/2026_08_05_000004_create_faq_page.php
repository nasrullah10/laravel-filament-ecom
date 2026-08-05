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
            ['slug' => 'faq'],
            [
                'title' => 'Frequently Asked Questions',
                'content' => '<h2>How can I place an order?</h2><p>Browse our products, add your preferred items to the cart and proceed to checkout.</p><h2>How long does delivery take?</h2><p>Delivery time depends on your location. Your order details will be shared after confirmation.</p><h2>Which payment methods are available?</h2><p>Available payment methods are displayed securely during checkout.</p><h2>How can I contact NAAS Shopping?</h2><p>You can contact us through the details provided on our Contact Us page or via WhatsApp.</p>',
                'type' => 'custom',
                'meta_title' => 'Frequently Asked Questions - NAAS Shopping',
                'meta_description' => 'Answers to frequently asked questions about NAAS Shopping orders, delivery and payments.',
                'is_active' => true,
                'sort_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        if (Schema::hasTable('pages')) {
            DB::table('pages')->where('slug', 'faq')->delete();
        }
    }
};
