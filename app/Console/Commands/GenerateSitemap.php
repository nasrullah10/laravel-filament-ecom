<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Product;
use App\Models\Category;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    public function handle()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/')->setPriority(1.0)->setChangeFrequency('daily'))
            ->add(Url::create('/shop')->setPriority(0.9)->setChangeFrequency('daily'))
            ->add(Url::create('/categories')->setPriority(0.8));

        // Dynamic Products
        Product::where('status', 'active')->chunk(100, function ($products) use ($sitemap) {
            foreach ($products as $product) {
                $sitemap->add(
                    Url::create(route('product.show', $product->slug))
                        ->setLastModificationDate($product->updated_at)
                        ->setPriority(0.8)
                        ->setChangeFrequency('weekly')
                );
            }
        });

        // Dynamic Categories
        Category::all()->each(function ($category) use ($sitemap) {
            $sitemap->add(
                Url::create(route('category.show', $category->slug))
                    ->setPriority(0.7)
                    ->setChangeFrequency('weekly')
            );
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));
        $this->info('Sitemap generated successfully!');
    }
}