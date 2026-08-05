<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Product;
use App\Models\Category;
use App\Models\BlogPost;
use App\Models\Page;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    public function handle(): int
    {
        $sitemap = Sitemap::create()
            ->add(Url::create(route('home'))->setPriority(1.0)->setChangeFrequency('daily'))
            ->add(Url::create(route('products'))->setPriority(0.9)->setChangeFrequency('daily'))
            ->add(Url::create(route('categories'))->setPriority(0.8)->setChangeFrequency('weekly'))
            ->add(Url::create(route('blog.index'))->setPriority(0.8)->setChangeFrequency('weekly'));

        Product::where('is_active', true)->chunkById(100, function ($products) use ($sitemap) {
            foreach ($products as $product) {
                $sitemap->add(
                    Url::create(route('product-detail', $product->slug))
                        ->setLastModificationDate($product->updated_at)
                        ->setPriority(0.8)
                        ->setChangeFrequency('weekly')
                );
            }
        });

        Category::where('is_active', true)->each(function ($category) use ($sitemap) {
            $sitemap->add(
                Url::create(route('products', ['category' => $category->slug]))
                    ->setPriority(0.7)
                    ->setChangeFrequency('weekly')
            );
        });

        BlogPost::published()->each(function ($post) use ($sitemap) {
            $sitemap->add(
                Url::create(route('blog.show', $post->slug))
                    ->setLastModificationDate($post->updated_at)
                    ->setPriority(0.7)
                    ->setChangeFrequency('monthly')
            );
        });

        Page::active()->each(function ($page) use ($sitemap) {
            $sitemap->add(
                Url::create(route('page.show', $page->slug))
                    ->setLastModificationDate($page->updated_at)
                    ->setPriority(0.6)
                    ->setChangeFrequency('monthly')
            );
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));
        $this->info('Sitemap generated successfully!');

        return self::SUCCESS;
    }
}
