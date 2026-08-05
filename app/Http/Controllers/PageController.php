<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Category;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::active()->where('slug', $slug)->firstOrFail();
        $menuCategories = Category::active()->ordered()->get();

        return view('pages.show', compact('page', 'menuCategories'));
    }

    public function privacyPolicy()
    {
        $page = Page::active()->byType('privacy_policy')->first();
        
        if (!$page) {
            abort(404, 'Privacy Policy page not found');
        }

        $menuCategories = Category::active()->ordered()->get();

        return view('pages.show', compact('page', 'menuCategories'));
    }

    public function termsConditions()
    {
        $page = Page::active()->byType('terms_conditions')->first();
        
        if (!$page) {
            abort(404, 'Terms & Conditions page not found');
        }

        $menuCategories = Category::active()->ordered()->get();

        return view('pages.show', compact('page', 'menuCategories'));
    }

    public function aboutUs()
    {
        $page = Page::active()->byType('about_us')->first();
        
        if (!$page) {
            abort(404, 'About Us page not found');
        }

        $menuCategories = Category::active()->ordered()->get();

        return view('pages.show', compact('page', 'menuCategories'));
    }

    public function contactUs()
    {
        $page = Page::active()->byType('contact')->first();
        
        if (!$page) {
            abort(404, 'Contact Us page not found');
        }

        $menuCategories = Category::active()->ordered()->get();

        return view('pages.show', compact('page', 'menuCategories'));
    }
}