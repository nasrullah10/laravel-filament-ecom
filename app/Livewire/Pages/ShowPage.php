<?php

namespace App\Livewire\Pages;

use App\Models\Page;
use App\Models\Category;
use Livewire\Component;

class ShowPage extends Component
{
    public $slug;
    public $page;

    public function mount($slug = null)
    {
        if ($slug) {
            $this->page = Page::active()->where('slug', $slug)->firstOrFail();
        }
    }

    public function render()
    {
        $menuCategories = Category::active()->ordered()->get();

        if (!$this->page && request()->routeIs('privacy-policy')) {
            $this->page = Page::active()->byType('privacy_policy')->firstOrFail();
        } elseif (!$this->page && request()->routeIs('terms-conditions')) {
            $this->page = Page::active()->byType('terms_conditions')->firstOrFail();
        } elseif (!$this->page && request()->routeIs('about-us')) {
            $this->page = Page::active()->byType('about_us')->firstOrFail();
        } elseif (!$this->page && request()->routeIs('contact-us')) {
            $this->page = Page::active()->byType('contact')->firstOrFail();
        }

        return view('livewire.pages.show-page', [
            'page' => $this->page,
            'menuCategories' => $menuCategories,
        ]);
    }
}