<div class="min-h-screen">
    @push('meta')
        <meta name="description" content="{{ $post->meta_description ?: $post->excerpt }}">
        <meta property="og:title" content="{{ $post->meta_title ?: $post->title }}">
        <meta property="og:description" content="{{ $post->meta_description ?: $post->excerpt }}">
        @if($post->featured_image)<meta property="og:image" content="{{ asset('storage/'.$post->featured_image) }}">@endif
    @endpush

    <article>
        <header class="max-w-4xl mx-auto px-4 pt-14 pb-10 text-center">
            <a href="{{ route('blog.index', ['category' => $post->category?->slug]) }}" class="text-xs tracking-[0.2em] text-naas-terracotta uppercase">{{ $post->category?->name ?? 'Journal' }}</a>
            <h1 class="font-serif text-4xl md:text-6xl leading-tight mt-5 mb-6">{{ $post->title }}</h1>
            <div class="text-xs tracking-wider text-naas-gray-warm uppercase">
                <span>{{ $post->author?->name ?? 'NAAS Shopping Editorial' }}</span><span class="mx-3">•</span><time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('F d, Y') }}</time>
            </div>
        </header>

        @if($post->featured_image)
            <div class="max-w-6xl mx-auto px-4 md:px-6"><img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $post->title }}" class="w-full max-h-[680px] object-cover"></div>
        @endif

        <div class="max-w-3xl mx-auto px-4 py-12">
            @if($post->excerpt)<p class="font-serif text-xl md:text-2xl leading-9 text-naas-green border-l-2 border-naas-terracotta pl-6 mb-10">{{ $post->excerpt }}</p>@endif
            <div class="prose prose-lg max-w-none prose-headings:font-serif prose-headings:text-naas-green prose-a:text-naas-terracotta prose-img:w-full">{!! $post->content !!}</div>
            <div class="mt-12 pt-6 border-t border-naas-cream-dark"><a href="{{ route('blog.index') }}" wire:navigate class="text-xs tracking-[0.15em] hover:text-naas-terracotta transition">← BACK TO JOURNAL</a></div>
        </div>
    </article>

    @if($relatedPosts->isNotEmpty())
        <section class="bg-naas-cream-dark py-16">
            <div class="max-w-6xl mx-auto px-4 md:px-6">
                <div class="text-center mb-10"><p class="text-xs tracking-[0.2em] text-naas-terracotta uppercase mb-2">Keep Reading</p><h2 class="font-serif text-3xl md:text-4xl">Related Stories</h2></div>
                <div class="grid md:grid-cols-3 gap-7">
                    @foreach($relatedPosts as $related)
                        <a href="{{ route('blog.show', $related->slug) }}" wire:navigate class="group bg-naas-cream">
                            <div class="aspect-[4/3] overflow-hidden">@if($related->featured_image)<img src="{{ asset('storage/'.$related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">@endif</div>
                            <div class="p-6"><p class="text-[10px] tracking-wider text-naas-terracotta uppercase mb-2">{{ $related->category?->name ?? 'Journal' }}</p><h3 class="font-serif text-xl group-hover:text-naas-terracotta transition">{{ $related->title }}</h3></div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
