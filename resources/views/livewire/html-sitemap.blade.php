<div class="max-w-7xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-8">HTML Sitemap</h1>
    
    <div class="grid md:grid-cols-3 gap-8">
        {{-- Pages --}}
        <div>
            <h2 class="text-xl font-semibold mb-4 text-primary-600">Pages</h2>
            <ul class="space-y-2">
                <li><a href="/" class="text-blue-600 hover:underline">Home</a></li>
                <li><a href="/shop" class="text-blue-600 hover:underline">Shop</a></li>
                <li><a href="/about" class="text-blue-600 hover:underline">About Us</a></li>
                <li><a href="/contact" class="text-blue-600 hover:underline">Contact</a></li>
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
                        <a href="{{ route('categories', $category->slug) }}" class="text-blue-600 hover:underline">
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
                        <a href="{{ route('products', $product->slug) }}" class="text-blue-600 hover:underline">
                            {{ $product->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>