<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Category;
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // database/seeders/CategorySeeder.php

$categories = [
    [
        'name' => 'Fabrics',
        'slug' => 'fabrics',
        'children' => [
            ['name' => 'Lawn', 'slug' => 'lawn'],
            ['name' => 'Chiffon', 'slug' => 'chiffon'],
            ['name' => 'Silk', 'slug' => 'silk'],
        ]
    ],
    [
        'name' => 'Jewellery',
        'slug' => 'jewellery',
        'children' => [
            ['name' => 'Earrings', 'slug' => 'earrings'],
            ['name' => 'Necklaces', 'slug' => 'necklaces'],
            ['name' => 'Rings', 'slug' => 'rings'],
        ]
    ],
    [
        'name' => 'Abayas',
        'slug' => 'abayas',
        'children' => [
            ['name' => 'Casual', 'slug' => 'casual'],
            ['name' => 'Premium', 'slug' => 'premium'],
        ]
    ],
    [
        'name' => 'Hijabs',
        'slug' => 'hijabs',
        'children' => [
            ['name' => 'Jersey', 'slug' => 'jersey'],
            ['name' => 'Chiffon', 'slug' => 'chiffon'],
        ]
    ],
    [
        'name' => 'Naas Home',
        'slug' => 'naas-home',
        'children' => []
    ],
    [
        'name' => 'Accessories',
        'slug' => 'accessories',
        'children' => []
    ],
];

foreach ($categories as $cat) {
    $parent = Category::create([
        'name' => $cat['name'],
        'slug' => $cat['slug'],
        'image' => 'categories/' . $cat['slug'] . '.jpg',
    ]);

    foreach ($cat['children'] as $child) {
        Category::create([
            'parent_id' => $parent->id,
            'name' => $child['name'],
            'slug' => $child['slug'],
            'image' => 'categories/' . $child['slug'] . '.jpg',
        ]);
    }
}
    }
}
