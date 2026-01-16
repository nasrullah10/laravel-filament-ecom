<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            
            // Balochi Women Wear (5)
            'Balochi Traditional Dress',
            'Balochi Hand Embroidered Dress',
            'Balochi Party Wear',
            'Balochi Bridal Dress',
            // Sindhi Women Wear (3)
            'Sindhi Ajrak Dress',
            'Sindhi Embroidered Suit',
            'Sindhi Traditional Dress',
            // Punjabi Women Wear (5)
            'Punjabi Traditional Dress',
            'Punjabi Embroidered Suit',
            'Punjabi Party Wear',
            'Punjabi Bridal Wear',
            // Pashtun Women Wear (5)
            'Pashtun Traditional Dress',
            'Pashtun Embroidered Suit',
            'Pashtun Party Wear',
            'Pashtun Bridal Dress',

           

            // Kashmiri Women Wear (3)
            'Kashmiri Embroidered Dress',
            'Kashmiri Wool Dress',
            'Kashmiri Traditional Dress',
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name'       => $category,
                'slug'       => Str::slug($category),
                'image'      => null,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
