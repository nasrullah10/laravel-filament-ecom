<div class="min-h-screen">
    <section class="bg-naas-green text-white py-20">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <p class="text-xs tracking-[0.3em] text-naas-gold uppercase mb-4">Stories & Inspiration</p>
            <h1 class="font-serif text-4xl md:text-6xl mb-5">The NAAS Shopping Journal</h1>
            <p class="text-white/70 max-w-2xl mx-auto">Discover style guides, thoughtful stories and the latest from our collection.</p>
        </div>
    </section>

    <section class="max-w-[85rem] mx-auto px-4 md:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-5 justify-between mb-10">
            <div class="flex gap-2 overflow-x-auto pb-2">
                <button wire:click="$set('category', '')" class="whitespace-nowrap px-5 py-2 text-xs tracking-wider border transition {{ $category === '' ? 'bg-naas-green text-white border-naas-green' : 'border-naas-cream-dark hover:border-naas-green' }}">ALL</button>
                @foreach($categories as $blogCategory)
                    <button wire:click="$set('category', '{{ $blogCategory->slug }}')" wire:key="category-{{ $blogCategory->id }}" class="whitespace-nowrap px-5 py-2 text-xs tracking-wider border transition {{ $category === $blogCategory->slug ? 'bg-naas-green text-white border-naas-green' : 'border-naas-cream-dark hover:border-naas-green' }}">
                        {{ strtoupper($blogCategory->name) }} ({{ $blogCategory->posts_count }})
                    </button>
                @endforeach
            </div>
            <div class="relative w-full lg:w-80">
                <input wire:model.live.debounce.400ms="search" type="search" placeholder="Search stories..." class="w-full bg-transparent border border-naas-cream-dark px-4 py-3 pr-10 text-sm focus:border-naas-green focus:ring-naas-green">
                <svg class="absolute right-3 top-3.5 w-5 h-5 text-naas-gray-warm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"/></svg>
            </div>
        </div>

        <div wire:loading.class="opacity-50" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-x-7 gap-y-12 transition-opacity">
            @forelse($posts as $post)
                <article wire:key="post-{{ $post->id }}" class="group">
                    <a href="{{ route('blog.show', $post->slug) }}" wire:navigate class="block overflow-hidden bg-naas-cream-dark aspect-[4/3] mb-5">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-naas-gray-warm font-serif text-2xl">NAAS Shopping Journal</div>
                        @endif
                    </a>
                    <div class="flex items-center gap-3 text-[11px] tracking-wider uppercase text-naas-terracotta mb-3">
                        <span>{{ $post->category?->name ?? 'Journal' }}</span><span class="w-5 h-px bg-naas-terracotta"></span><time>{{ $post->published_at->format('M d, Y') }}</time>
                    </div>
                    <h2 class="font-serif text-2xl leading-tight mb-3 group-hover:text-naas-terracotta transition"><a href="{{ route('blog.show', $post->slug) }}" wire:navigate>{{ $post->title }}</a></h2>
                    <p class="text-sm leading-7 text-naas-gray-warm mb-4">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 150) }}</p>
                    <a href="{{ route('blog.show', $post->slug) }}" wire:navigate class="inline-flex items-center text-xs tracking-[0.15em] border-b border-naas-dark pb-1 hover:text-naas-terracotta hover:border-naas-terracotta transition">READ STORY</a>
                </article>
            @empty
                <div class="sm:col-span-2 lg:col-span-3 py-20 text-center">
                    <h2 class="font-serif text-3xl mb-3">No stories found</h2>
                    <p class="text-naas-gray-warm">Try another search or category.</p>
                </div>
            @endforelse
        </div>

        @if($posts->hasPages())
            <div class="mt-14">{{ $posts->links() }}</div>
        @endif
    </section>
</div>
