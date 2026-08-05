<?php
// app/Livewire/HtmlSitemap.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Page;
use App\Models\BlogPost;

class HtmlSitemap extends Component
{
    public function render()
    {
        return view('livewire.html-sitemap', [
            'categories' => Category::where('is_active', 1)->get(),
            'products' => Product::where('is_active',1)->select('name', 'slug')->get(),
            'pages' => Page::where('is_active', 1)->get(),
            'blogPosts' => BlogPost::published()->select('title', 'slug')->latest('published_at')->get(),
        ])->layout('components/layouts.app');
    }
}
