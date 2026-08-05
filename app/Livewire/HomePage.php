<?php

namespace App\Livewire;

use App\Models\Slider;
use App\Models\Product;
use App\Models\Testimonial;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Home - NaasShopping')]
#[Layout('components.layouts.app')]
class HomePage extends Component
{
    public function render()
    {
        // ===== DYNAMIC SLIDERS =====
        $sliders = Slider::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // ===== TESTIMONIALS / CLIENT REVIEWS (Latest 4 Active) =====
        $testimonials = Testimonial::where('is_active', true)
            ->latest() // Latest pehle
            ->take(4)  // Sirf 4
            ->get();

        // ===== Best Selling Products =====
        $bestSellingProducts = Product::where('is_active', 1)
            ->where(function ($query) {
                $query->where('is_featured', 1)
                      ->orWhere('on_sale', 1);
            })
            ->with(['category', 'brand'])
            ->take(8)
            ->get();

        if ($bestSellingProducts->isEmpty()) {
            $bestSellingProducts = Product::where('is_active', 1)
                ->latest()
                ->take(5)
                ->get();
        }

        // ===== Featured Products =====
        $featuredProducts = Product::where('is_active', 1)
            ->where('is_featured', 1)
            ->latest()
            ->take(4)
            ->get();

        return view('livewire.home-page', compact(
            'sliders',
            'testimonials',
            'bestSellingProducts',
            'featuredProducts'
        ));
    }
}