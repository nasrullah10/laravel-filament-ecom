<div class="max-w-7xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-8">HTML Sitemap</h1>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        {{-- Pages --}}
        <div>
            <h2 class="text-xl font-semibold mb-4 text-primary-600">Pages</h2>
            <ul class="space-y-2">
                <li><a href="/" class="text-blue-600 hover:underline">Home</a></li>
                <li><a href="{{ route('products') }}" class="text-blue-600 hover:underline">Shop</a></li>
                <li><a href="{{ route('blog.index') }}" class="text-blue-600 hover:underline">Blog</a></li>
                @foreach($pages as $page)
                    <li>
                        <a href="{{ route('page.show', $page->slug) }}" class="text-blue-600 hover:underline">
                            {{ $page->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Categories --}}
        <div>
            <h2 class="text-xl font-semibold mb-4 text-primary-600">Categories</h2>
            <ul class="space-y-2">
                @foreach($categories as $category)
                    <li>
                        <a href="{{ route('products', ['category' => $category->slug]) }}" class="text-blue-600 hover:underline">
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Products --}}
        <div>
            <h2 class="text-xl font-semibold mb-4 text-primary-600">Products</h2>
            <ul class="space-y-2 max-h-96 overflow-y-auto">
                @foreach($products as $product)
                    <li>
                        <a href="{{ route('product-detail', $product->slug) }}" class="text-blue-600 hover:underline">
                            {{ $product->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-4 text-primary-600">Blog</h2>
            <ul class="space-y-2 max-h-96 overflow-y-auto">
                @foreach($blogPosts as $post)
                    <li><a href="{{ route('blog.show', $post->slug) }}" class="text-blue-600 hover:underline">{{ $post->title }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
