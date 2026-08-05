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