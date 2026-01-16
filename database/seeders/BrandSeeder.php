<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Jaan-e-Zeeb Signature',
            'Jaan-e-Zeeb Heritage',
            'Jaan-e-Zeeb Luxury',
        ];

        foreach ($brands as $brand) {
            DB::table('brands')->insert([
                'name'       => $brand,
                'slug'       => Str::slug($brand),
                'image'      => null,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
