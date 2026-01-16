<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        $products = [

            // Balochi Women Wear (5)
            [
                'category_id' => 1,
                'brand_id' => 1,
                'name' => 'Balochi Traditional Dress',
                'slug' => Str::slug('Balochi Traditional Dress'),
                'images' => json_encode([
                    'https://www.pakistantradeportal.gov.pk/storage/product_images/62c2b4ca283c81656927434.jpg'
                ]),
                'description' => 'Authentic Balochi traditional dress with hand embroidery.',
                'price' => 12000.00,
                'is_active' => true,
                'is_featured' => true,
                'in_stock' => true,
                'on_sale' => false,
            ],
            [
                'category_id' => 2,
                'brand_id' => 2,
                'name' => 'Balochi Hand Embroidered Dress',
                'slug' => Str::slug('Balochi Hand Embroidered Dress'),
                'images' => json_encode([
                    'https://www.pakistantradeportal.gov.pk/storage/product_images/62c2b4ca283c81656927434.jpg'
                ]),
                'description' => 'Elegant hand embroidered Balochi dress with mirror work.',
                'price' => 14000.00,
                'is_featured' => false,
            ],
            [
                'category_id' => 3,
                'brand_id' => 3,
                'name' => 'Balochi Party Wear',
                'slug' => Str::slug('Balochi Party Wear'),
                'images' => json_encode([
                    'https://www.pakistantradeportal.gov.pk/storage/product_images/62c2b4ca283c81656927434.jpg'
                ]),
                'description' => 'Modern Balochi party wear dress with embroidery and mirror work.',
                'price' => 16000.00,
                'is_featured' => true,
            ],
            [
                'category_id' => 4,
                'brand_id' => 1,
                'name' => 'Balochi Bridal Dress',
                'slug' => Str::slug('Balochi Bridal Dress'),
                'images' => json_encode([
                    'https://www.pakistantradeportal.gov.pk/storage/product_images/62c2b4ca283c81656927434.jpg'
                ]),
                'description' => 'Luxurious Balochi bridal gown with rich embroidery.',
                'price' => 22000.00,
                'is_featured' => true,
            ],

            // Sindhi Women Wear (3)
            [
                'category_id' => 5,
                'brand_id' => 1,
                'name' => 'Sindhi Ajrak Dress',
                'slug' => Str::slug('Sindhi Ajrak Dress'),
                'images' => json_encode([
                    'https://www.pakistantradeportal.gov.pk/storage/product_images/62c2b4ca283c81656927434.jpg'
                ]),
                'description' => 'Traditional Sindhi Ajrak printed dress with bright colors.',
                'price' => 9500.00,
                'is_featured' => true,
            ],
            [
                'category_id' => 6,
                'brand_id' => 2,
                'name' => 'Sindhi Embroidered Suit',
                'slug' => Str::slug('Sindhi Embroidered Suit'),
                'images' => json_encode([
                    'https://www.pakistantradeportal.gov.pk/storage/product_images/62c2b4ca283c81656927434.jpg'
                ]),
                'description' => 'Beautiful Sindhi embroidered suit with mirror work.',
                'price' => 10500.00,
            ],
            [
                'category_id' => 7,
                'brand_id' => 3,
                'name' => 'Sindhi Traditional Dress',
                'slug' => Str::slug('Sindhi Traditional Dress'),
                'images' => json_encode([
                    'https://www.pakistantradeportal.gov.pk/storage/product_images/62c2b4ca283c81656927434.jpg'
                ]),
                'description' => 'Traditional Sindhi dress featuring classic Ajrak patterns.',
                'price' => 9900.00,
            ],

        ];

        foreach ($products as $product) {
            DB::table('products')->insert(array_merge($product, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
