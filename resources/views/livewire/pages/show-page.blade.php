@push('meta')
    @php
        $pageTitle = $page->meta_title ?: $page->title.' - NAAS Shopping';
        $pageDescription = $page->meta_description
            ?: \Illuminate\Support\Str::limit(strip_tags($page->content), 160, '');
        $pageCanonical = match ($page->type) {
            'privacy_policy' => route('privacy-policy'),
            'terms_conditions' => route('terms-conditions'),
            'about_us' => route('about-us'),
            'contact' => route('contact-us'),
            default => route('page.show', $page->slug),
        };
        $pageBreadcrumbs = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $page->title, 'item' => $pageCanonical],
            ],
        ];
    @endphp
    <x-seo-meta :title="$pageTitle" :description="$pageDescription" :canonical="$pageCanonical" />
    <script type="application/ld+json">{!! json_encode($pageBreadcrumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

<div>
    <!-- Page Header -->
    <section class="bg-naas-green text-white py-16">
        <div class="max-w-[85rem] mx-auto px-4 md:px-6 lg:px-8">
            <h1 class="font-serif text-4xl md:text-5xl">{{ $page->title }}</h1>
            <p class="text-gray-300 mt-2 text-sm">Last updated: {{ $page->updated_at->format('F d, Y') }}</p>
        </div>
    </section>

    <!-- Page Content -->
    <section class="max-w-[85rem] mx-auto px-4 md:px-6 lg:px-8 py-16">
        <div class="max-w-3xl mx-auto prose prose-lg prose-headings:font-serif prose-a:text-naas-terracotta hover:prose-a:text-naas-terracotta-dark">
            {!! $page->content !!}
        </div>
    </section>
</div>
