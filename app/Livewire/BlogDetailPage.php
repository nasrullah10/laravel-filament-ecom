<?php

namespace App\Livewire;

use App\Models\BlogPost;
use Livewire\Component;

class BlogDetailPage extends Component
{
    public string $slug;

    public function mount(string $slug): void
    {
        $this->slug = $slug;
    }

    public function render()
    {
        $post = BlogPost::query()
            ->published()
            ->with(['category', 'author'])
            ->where('slug', $this->slug)
            ->firstOrFail();

        $relatedPosts = BlogPost::query()
            ->published()
            ->with('category')
            ->whereKeyNot($post->id)
            ->when($post->blog_category_id, fn ($query) => $query->where('blog_category_id', $post->blog_category_id))
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('livewire.blog-detail-page', compact('post', 'relatedPosts'))
            ->title($post->meta_title ?: $post->title);
    }
}
