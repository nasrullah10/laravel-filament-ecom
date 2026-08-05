<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share menu categories with all views
        View::composer('components.layouts.app', function ($view) {
            $menuCategories = Category::whereNull('parent_id')
                ->with('children')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
            
            $view->with('menuCategories', $menuCategories);
        });
    }

}
