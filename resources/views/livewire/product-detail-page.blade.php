@push('meta')
    @php
        $schemaImages = collect($product->images ?? [])
            ->filter()
            ->map(fn ($image) => asset('storage/'.$image))
            ->values()
            ->all();
        $productUrl = route('product-detail', $product->slug);
        $productTitle = $product->name.' - NAAS Shopping';
        $productDescription = \Illuminate\Support\Str::limit(
            trim(preg_replace('/\s+/', ' ', strip_tags(\Illuminate\Support\Str::markdown($product->description ?? '')))),
            160,
            ''
        );
        $productImage = $schemaImages[0] ?? asset('images/naas-logo.jpeg');

        $productSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => $schemaImages ?: [$productImage],
            'description' => $productDescription,
            'sku' => 'NAAS-'.$product->id,
            'brand' => [
                '@type' => 'Brand',
                'name' => $product->brand?->name ?? 'NAAS Shopping',
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => number_format((float) $product->price, 2, '.', ''),
                'priceCurrency' => 'PKR',
                'availability' => $product->in_stock
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock',
                'itemCondition' => 'https://schema.org/NewCondition',
                'url' => $productUrl,
                'seller' => [
                    '@type' => 'Organization',
                    'name' => 'NAAS Shopping',
                ],
            ],
        ];
        if ($reviewCount > 0) {
            $productSchema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => round($averageRating, 1),
                'reviewCount' => $reviewCount,
                'bestRating' => 5,
                'worstRating' => 1,
            ];
        }
        $productBreadcrumbs = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Products', 'item' => route('products')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $product->name, 'item' => $productUrl],
            ],
        ];
    @endphp
    <x-seo-meta
        :title="$productTitle"
        :description="$productDescription"
        :canonical="$productUrl"
        :image="$productImage"
        type="product"
    />
    <script type="application/ld+json">{!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode($productBreadcrumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
       @php
            $allImages = $product->images ?? [];
            $mainImage = $allImages[0] ?? null;
        @endphp

    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-xs tracking-widest text-gray-500 uppercase">
            <li><a href="/" class="hover:text-[#1a3c34] transition">Home</a></li>
            <li>/</li>
            <li><a href="/products" class="hover:text-[#1a3c34] transition">Shop</a></li>
            <li>/</li>
            <li class="text-[#1a3c34]">{{ $product->name }}</li>
        </ol>
    </nav>

    <section class="overflow-hidden bg-white py-11 font-poppins">
        <div class="max-w-6xl px-4 py-4 mx-auto lg:py-8 md:px-6">
            <div class="flex flex-wrap -mx-4">
                <!-- Product Images -->
                <div class="w-full mb-8 md:w-1/2 md:mb-0" x-data="{ mainImage: '{{ $mainImage ? asset('storage/' . $mainImage) : '' }}' }">
                    <div class="md:sticky md:top-32 z-0 overflow-hidden">
                        <div class="relative mb-6 lg:mb-10 bg-[#FAF9F6] aspect-[4/5] max-h-[75vh]">
                            <img x-bind:src="mainImage" 
                                 alt="{{ $product->name }}" 
                                 class="object-contain w-full h-full"
                                 onerror="this.style.display='none'">
                        </div>
                        <div class="flex-wrap hidden md:flex">
                            @if(count($allImages) > 0)
                            @foreach($allImages as $image)
                            @php $imageUrl = asset('storage/' . $image); @endphp
                            <div class="w-1/2 p-2 sm:w-1/4" 
                                 x-on:click="mainImage='{{ $imageUrl }}'"
                                 :class="mainImage === '{{ $imageUrl }}' ? 'ring-2 ring-[#1a3c34]' : ''">
                                <img src="{{ $imageUrl }}" 
                                     alt="{{ $product->name }}" 
                                     class="object-cover w-full lg:h-20 cursor-pointer hover:opacity-80 transition duration-300">
                            </div>
                            @endforeach
                            @endif
                        </div>

                        <!-- Free Shipping -->
                        <div class="px-6 pb-6 mt-6 border-t border-gray-200">
                            <div class="flex flex-wrap items-center mt-6">
                                <span class="mr-3 text-[#1a3c34]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-5 h-5 bi bi-truck" viewBox="0 0 16 16">
                                        <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456zM12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z">
                                        </path>
                                    </svg>
                                </span>
                                <div>
                                    <h2 class="text-sm font-medium text-[#1a3c34] tracking-wider uppercase">Free Shipping</h2>
                                    <p class="text-xs text-gray-500 mt-0.5">On orders over Rs. 10,000</p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <!-- Product Info -->
                <div class="w-full px-4 md:w-1/2">
                    <div class="lg:pl-20">
                        <div class="mb-8 [&ul]:list-disc [&ul]:ml-4">
                            <h2 class="font-serif text-3xl md:text-4xl text-[#1a3c34] mb-4">
                                {{ $product->name }}
                            </h2>

                            @if($reviewCount > 0)
                                <a href="#customer-reviews" class="mb-4 inline-flex items-center gap-2 text-sm text-gray-600 hover:text-[#1a3c34]">
                                    <span class="tracking-wider text-amber-500">{{ str_repeat('★', (int) round($averageRating)) }}{{ str_repeat('☆', 5 - (int) round($averageRating)) }}</span>
                                    <span>{{ number_format($averageRating, 1) }} ({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})</span>
                                </a>
                            @endif

                            <p class="mb-6">
                                <span class="font-serif text-3xl text-[#1a3c34]">{{ Number::currency($product->price,'PKR',true) }}</span>
                                @if($product->compare_price && $product->compare_price > $product->price)
                                <span class="text-base text-gray-400 line-through ml-3">{{ Number::currency($product->compare_price,'PKR',true) }}</span>
                                @endif
                            </p>

                            <div class="max-w-md text-gray-600 text-sm leading-relaxed">
                                {!! Str::markdown($product->description) !!}
                            </div>
                        </div>
                        <!-- Quantity Section -->
                        <div class="w-32 mb-8">
                            <label class="w-full pb-2 text-xs tracking-widest uppercase text-gray-500 border-b border-gray-200 block mb-4">
                                Quantity
                            </label>
                            <div class="relative flex flex-row w-full h-12 mt-2 bg-transparent">
                                <button wire:click="decreaseQuantity" 
                                        class="w-12 h-full text-[#1a3c34] bg-[#f5f0e8] border border-gray-200 outline-none cursor-pointer hover:bg-[#1a3c34] hover:text-white transition duration-300 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 12H4"/>
                                    </svg>
                                </button>
                                <input type="number" 
                                    value="{{ $quantity }}" 
                                    readonly 
                                    class="flex items-center w-full font-medium text-center text-[#1a3c34] bg-[#f5f0e8] outline-none border-y border-gray-200 focus:outline-none text-md" 
                                    placeholder="1">
                                <button wire:click="increaseQuantity" 
                                        class="w-12 h-full text-[#1a3c34] bg-[#f5f0e8] border border-gray-200 outline-none cursor-pointer hover:bg-[#1a3c34] hover:text-white transition duration-300 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Purchase Actions -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <button type="button" wire:click="addToCart({{ $product->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="addToCart({{ $product->id }}),buyNow({{ $product->id }})"
                                    @disabled(! $product->in_stock)
                                    class="w-full py-4 bg-[#1a3c34] text-white text-sm tracking-widest uppercase hover:bg-opacity-90 disabled:opacity-50 disabled:cursor-not-allowed transition duration-300">
                                <span wire:loading.remove wire:target="addToCart({{ $product->id }})">Add to Bag</span>
                                <span wire:loading wire:target="addToCart({{ $product->id }})">Adding...</span>
                            </button>

                            <button type="button" wire:click="buyNow({{ $product->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="addToCart({{ $product->id }}),buyNow({{ $product->id }})"
                                    @disabled(! $product->in_stock)
                                    class="w-full py-4 bg-[#C65D3B] text-white text-sm tracking-widest uppercase hover:bg-[#a94d30] disabled:opacity-50 disabled:cursor-not-allowed transition duration-300">
                                <span wire:loading.remove wire:target="buyNow({{ $product->id }})">Buy Now</span>
                                <span wire:loading wire:target="buyNow({{ $product->id }})">Redirecting...</span>
                            </button>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('products') }}"
                               class="text-xs tracking-widest uppercase text-gray-500 hover:text-[#1a3c34] transition border-b border-transparent hover:border-[#1a3c34] pb-1">
                                Continue Shopping
                            </a>
                        </div>

                        <!-- Trust Badges -->
                        <div class="flex items-center space-x-6 mt-8 pt-6 border-t border-gray-200">
                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                <svg class="w-4 h-4 text-[#1a3c34]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <span>7-day exchange</span>
                            </div>
                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                <svg class="w-4 h-4 text-[#1a3c34]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span>Crafted in PK</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <section id="customer-reviews" class="border-t border-gray-200 py-16">
        <style>
            .review-content-offset, .review-media-offset { margin-left: 0; }
            @media (min-width: 640px) {
                .review-content-offset { padding-left: 3.625rem; }
                .review-media-offset { margin-left: 3.625rem; }
            }
        </style>
        <div class="max-w-6xl mx-auto px-4 md:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <div>
                    <p class="text-xs tracking-[0.2em] text-[#C65D3B] uppercase mb-2">Customer Feedback</p>
                    <h2 class="font-serif text-3xl text-[#1B4332]">Product Reviews</h2>
                    @if($reviewCount > 0)
                        <div class="mt-5 rounded-2xl border bg-white p-5" style="border-color:#e7e2d9; box-shadow:0 8px 24px rgba(27,67,50,.06);">
                            <div style="display:flex;align-items:baseline;gap:.5rem;">
                                <strong style="font-family:serif;font-size:38px;line-height:1;color:#1B4332;">{{ number_format($averageRating, 1) }}</strong>
                                <span style="color:#6b7280;font-size:13px;">out of 5</span>
                            </div>
                            <div class="mt-2 text-amber-500 tracking-wider" style="color:#d97706;">{{ str_repeat('★', (int) round($averageRating)) }}{{ str_repeat('☆', 5 - (int) round($averageRating)) }}</div>
                            <p class="mt-1 text-sm text-gray-600">Based on {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</p>

                            <div style="display:flex;flex-direction:column;gap:.55rem;margin-top:1.25rem;">
                                @foreach($ratingBreakdown as $rating => $count)
                                    @php $percentage = $reviewCount > 0 ? ($count / $reviewCount) * 100 : 0; @endphp
                                    <button type="button" wire:click="filterReviewsByRating('{{ $rating }}')"
                                            style="display:grid;grid-template-columns:34px 1fr 24px;align-items:center;gap:.5rem;border:0;background:transparent;padding:0;cursor:pointer;color:#4b5563;font-size:12px;">
                                        <span>{{ $rating }} ★</span>
                                        <span style="display:block;height:6px;overflow:hidden;border-radius:9999px;background:#eee9e1;">
                                            <span style="display:block;width:{{ $percentage }}%;height:100%;border-radius:9999px;background:#C65D3B;"></span>
                                        </span>
                                        <span>{{ $count }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="mt-4 text-sm text-gray-500">No approved reviews yet.</p>
                    @endif
                </div>

                <div class="lg:col-span-2">
                    @if($reviewFeedback)
                        <div role="status" aria-live="polite" class="mb-6 rounded-lg p-4"
                             style="border: 1px solid {{ $reviewFeedbackType === 'success' ? '#16a34a' : '#dc2626' }}; background: {{ $reviewFeedbackType === 'success' ? '#dcfce7' : '#fee2e2' }}; color: {{ $reviewFeedbackType === 'success' ? '#166534' : '#991b1b' }}; font-size: 14px;">
                            <strong style="display: block; margin-bottom: 3px;">{{ $reviewFeedbackType === 'success' ? 'Review submitted' : 'Unable to submit review' }}</strong>
                            {{ $reviewFeedback }}
                        </div>
                    @endif

                    @auth
                        @if($canReview)
                            <form wire:submit.prevent="submitReview" class="mb-12 rounded-xl border border-gray-200 bg-[#FAF9F6] p-6 shadow-sm md:p-8">
                                <div class="mb-6">
                                    <h3 class="font-serif text-2xl text-[#1B4332]">Write a Review</h3>
                                    <p class="mt-1 text-sm text-gray-500">Share your experience with other customers.</p>
                                </div>

                                @if($errors->any())
                                    <div role="alert" class="mb-5 rounded-lg p-4"
                                         style="border: 1px solid #dc2626; background: #fee2e2; color: #991b1b; font-size: 14px;">
                                        <strong>Please correct the highlighted fields.</strong>
                                        <ul style="margin: 6px 0 0 18px; list-style: disc;">
                                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="mb-5">
                                    <label for="review-rating" class="block text-sm font-medium text-gray-700 mb-2">Your rating <span style="color:#dc2626">*</span></label>
                                    <select id="review-rating" wire:model="reviewRating" class="w-full rounded-lg border-gray-300 bg-white focus:border-[#1B4332] focus:ring-[#1B4332]" @error('reviewRating') style="border-color:#dc2626" @enderror>
                                        <option value="5">5 Stars</option><option value="4">4 Stars</option>
                                        <option value="3">3 Stars</option><option value="2">2 Stars</option><option value="1">1 Star</option>
                                    </select>
                                    @error('reviewRating') <p class="mt-1" style="color:#dc2626;font-size:13px">{{ $message }}</p> @enderror
                                </div>
                                <div class="mb-5">
                                    <label for="review-title" class="block text-sm font-medium text-gray-700 mb-2">Review title <span class="font-normal text-gray-400">(optional)</span></label>
                                    <input id="review-title" type="text" wire:model="reviewTitle" maxlength="120" placeholder="Summarize your experience" class="w-full rounded-lg border-gray-300 focus:border-[#1B4332] focus:ring-[#1B4332]" @error('reviewTitle') style="border-color:#dc2626" @enderror>
                                    @error('reviewTitle') <p class="mt-1" style="color:#dc2626;font-size:13px">{{ $message }}</p> @enderror
                                </div>
                                <div class="mb-5">
                                    <label for="review-comment" class="block text-sm font-medium text-gray-700 mb-2">Your review <span style="color:#dc2626">*</span></label>
                                    <textarea id="review-comment" wire:model="reviewComment" rows="5" maxlength="2000" placeholder="What did you like or dislike about this product?" class="w-full rounded-lg border-gray-300 focus:border-[#1B4332] focus:ring-[#1B4332]" @error('reviewComment') style="border-color:#dc2626" @enderror></textarea>
                                    <div class="mt-1 flex justify-between text-xs text-gray-500"><span>Minimum 10 characters</span><span>Maximum 2000</span></div>
                                    @error('reviewComment') <p class="mt-1" style="color:#dc2626;font-size:13px">{{ $message }}</p> @enderror
                                </div>
                                <div class="mb-5">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Add media <span class="font-normal text-gray-400">(optional)</span></label>
                                    <div class="mb-4 grid grid-cols-2 gap-3 text-sm" role="group" aria-label="Review media type">
                                        <button type="button" wire:click="selectReviewMedia('image')" aria-pressed="{{ $reviewMediaType === 'image' ? 'true' : 'false' }}"
                                                class="flex items-center justify-center gap-2 rounded-lg border p-3 font-medium"
                                                style="border-color: {{ $reviewMediaType === 'image' ? '#1B4332' : '#d1d5db' }}; background: {{ $reviewMediaType === 'image' ? '#1B4332' : '#ffffff' }}; color: {{ $reviewMediaType === 'image' ? '#ffffff' : '#374151' }};">
                                            <span aria-hidden="true">▧</span> Images
                                        </button>
                                        <button type="button" wire:click="selectReviewMedia('video')" aria-pressed="{{ $reviewMediaType === 'video' ? 'true' : 'false' }}"
                                                class="flex items-center justify-center gap-2 rounded-lg border p-3 font-medium"
                                                style="border-color: {{ $reviewMediaType === 'video' ? '#1B4332' : '#d1d5db' }}; background: {{ $reviewMediaType === 'video' ? '#1B4332' : '#ffffff' }}; color: {{ $reviewMediaType === 'video' ? '#ffffff' : '#374151' }};">
                                            <span aria-hidden="true">▶</span> Video
                                        </button>
                                    </div>
                                    @if($reviewMediaType === 'image')
                                        <input type="file" wire:model="reviewImages" accept="image/jpeg,image/png,image/webp" multiple class="block w-full rounded-lg border border-gray-300 bg-white p-3 text-sm text-gray-600">
                                        <p class="mt-1 text-xs text-gray-500">Maximum 3 images, 3MB each.</p>
                                        @if(count($reviewImages))<p class="mt-2 text-sm" style="color:#166534">{{ count($reviewImages) }} image(s) ready to submit.</p>@endif
                                        @error('reviewImages') <p class="mt-1" style="color:#dc2626;font-size:13px">{{ $message }}</p> @enderror
                                        @error('reviewImages.*') <p class="mt-1" style="color:#dc2626;font-size:13px">{{ $message }}</p> @enderror
                                    @else
                                        <input type="file" wire:model="reviewVideo" accept="video/mp4,video/webm,video/quicktime" class="block w-full rounded-lg border border-gray-300 bg-white p-3 text-sm text-gray-600">
                                        <p class="mt-1 text-xs text-gray-500">One MP4, WebM or MOV video, maximum 25MB.</p>
                                        @if($reviewVideo)<p class="mt-2 text-sm" style="color:#166534">Video is uploaded and ready to submit.</p>@endif
                                        @error('reviewVideo') <p class="mt-1" style="color:#dc2626;font-size:13px">{{ $message }}</p> @enderror
                                    @endif
                                    <p wire:loading wire:target="reviewImages,reviewVideo" class="mt-2 text-sm" style="color:#1B4332">Uploading media, please wait...</p>
                                </div>
                                <button type="submit" wire:loading.attr="disabled" wire:target="submitReview,reviewImages,reviewVideo"
                                        class="group inline-flex w-full items-center justify-center gap-3 px-8 py-4 text-sm font-semibold tracking-[0.14em] uppercase text-white disabled:opacity-50 sm:w-auto"
                                        style="background: linear-gradient(135deg, #1B4332 0%, #245b46 100%); color: #ffffff; min-height: 52px; border: 1px solid #1B4332; border-radius: 9999px; box-shadow: 0 8px 20px rgba(27,67,50,.22); cursor:pointer;">
                                    <span wire:loading.remove wire:target="submitReview">Submit My Review</span>
                                    <span wire:loading.remove wire:target="submitReview" aria-hidden="true" style="font-size:18px;line-height:1">→</span>
                                    <span wire:loading wire:target="submitReview,reviewImages,reviewVideo">Please wait...</span>
                                </button>
                                <p class="mt-3 text-xs text-gray-500">Your review will appear after admin approval.</p>
                            </form>
                        @elseif($product->reviews()->where('user_id', auth()->id())->exists())
                            <p class="mb-8 bg-[#FAF9F6] p-4 text-sm text-gray-600">You have already submitted a review for this product.</p>
                        @else
                            <p class="mb-8 bg-[#FAF9F6] p-4 text-sm text-gray-600">Reviews can be submitted after your order has been delivered.</p>
                        @endif
                    @else
                        <p class="mb-8 bg-[#FAF9F6] p-4 text-sm text-gray-600"><a href="{{ route('login') }}" class="font-medium text-[#1B4332] underline">Log in</a> to review a product you purchased.</p>
                    @endauth

                    @if($reviewCount > 0)
                        <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:1rem;border-color:#e7e2d9;">
                            <div style="display:flex;flex-wrap:wrap;align-items:center;gap:.5rem;">
                                <button type="button" wire:click="filterReviewsByRating('all')"
                                        style="padding:.5rem .85rem;border-radius:9999px;border:1px solid {{ $reviewRatingFilter === 'all' ? '#1B4332' : '#d1d5db' }};background:{{ $reviewRatingFilter === 'all' ? '#1B4332' : '#fff' }};color:{{ $reviewRatingFilter === 'all' ? '#fff' : '#4b5563' }};font-size:12px;cursor:pointer;">All</button>
                                @foreach(range(5, 1) as $rating)
                                    @if(($ratingBreakdown[$rating] ?? 0) > 0)
                                        <button type="button" wire:click="filterReviewsByRating('{{ $rating }}')"
                                                style="padding:.5rem .75rem;border-radius:9999px;border:1px solid {{ $reviewRatingFilter === (string) $rating ? '#1B4332' : '#d1d5db' }};background:{{ $reviewRatingFilter === (string) $rating ? '#1B4332' : '#fff' }};color:{{ $reviewRatingFilter === (string) $rating ? '#fff' : '#4b5563' }};font-size:12px;cursor:pointer;">{{ $rating }} ★</button>
                                    @endif
                                @endforeach
                                <span style="margin-left:.25rem;color:#6b7280;font-size:12px;">{{ $filteredReviewCount }} shown</span>
                            </div>
                            <label style="display:flex;align-items:center;gap:.5rem;color:#6b7280;font-size:12px;">
                                Sort
                                <select wire:model.live="reviewSort" style="border:1px solid #d1d5db;border-radius:8px;background:#fff;padding:.5rem 2rem .5rem .7rem;color:#374151;font-size:12px;">
                                    <option value="newest">Newest</option>
                                    <option value="highest">Highest rated</option>
                                    <option value="lowest">Lowest rated</option>
                                </select>
                            </label>
                        </div>
                    @endif

                    <div class="space-y-8" style="display: flex; flex-direction: column; gap: 2rem;">
                        @forelse($reviews as $review)
                            <article class="rounded-2xl border bg-white p-5 md:p-7" style="display: block; padding: 1.75rem; border: 1px solid #e7e2d9; border-radius: 16px; background: linear-gradient(180deg, #ffffff 0%, #fffdfa 100%); box-shadow: 0 10px 30px rgba(27,67,50,.07);" wire:key="review-{{ $review->id }}">
                                <div class="flex flex-wrap items-start justify-between gap-4" style="display: flex; flex-wrap: wrap; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: 1.25rem;">
                                    <div style="display: flex; align-items: center; gap: .875rem; min-width: 0;">
                                        <div aria-hidden="true" style="display: flex; flex: 0 0 auto; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 50%; background: #1B4332; color: #ffffff; font-family: serif; font-size: 18px; font-weight: 600;">
                                            {{ Str::upper(Str::substr($review->user->name, 0, 1)) }}
                                        </div>
                                        <div style="min-width: 0;">
                                            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: .5rem;">
                                                <span class="font-medium text-gray-900" style="font-weight: 600; color: #1f2937;">{{ $review->user->name }}</span>
                                                <span style="display: inline-flex; align-items: center; gap: .25rem; padding: .2rem .55rem; border-radius: 9999px; background: #dcfce7; color: #166534; font-size: 11px; font-weight: 600;">✓ Verified Purchase</span>
                                            </div>
                                            <div class="text-amber-500 tracking-wider" style="margin-top: .3rem; color: #d97706; font-size: 14px; letter-spacing: .12em;" aria-label="{{ $review->rating }} out of 5 stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</div>
                                        </div>
                                    </div>
                                    <time class="text-xs text-gray-500" style="display: block; padding-top: .25rem; color: #6b7280; font-size: 12px; white-space: nowrap;">{{ $review->approved_at?->format('M d, Y') }}</time>
                                </div>
                                <div class="review-content-offset">
                                    @if($review->title)<h3 class="font-serif text-lg text-gray-900" style="margin: 0; color: #1B4332; font-size: 18px; font-weight: 600;">{{ $review->title }}</h3>@endif
                                    <p class="text-sm leading-6 text-gray-600" style="margin-top: {{ $review->title ? '.5rem' : '0' }}; color: #4b5563; font-size: 14px; line-height: 1.75;">{{ $review->comment }}</p>
                                </div>
                                @php
                                    $reviewMedia = collect($review->images ?? [])->map(fn ($path) => ['type' => 'image', 'url' => asset('storage/'.$path)])
                                        ->concat(collect($review->videos ?? [])->map(fn ($path) => ['type' => 'video', 'url' => asset('storage/'.$path)]))
                                        ->values();
                                @endphp
                                @if($reviewMedia->isNotEmpty())
                                    <div class="review-media-slider review-media-offset relative mt-5 max-w-lg overflow-hidden bg-black" style="position: relative; overflow: hidden; background: #000; max-width: 32rem; margin-top: 1.25rem; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,.14);">
                                        <div id="review-media-track-{{ $review->id }}" class="flex w-full snap-x snap-mandatory overflow-x-auto scroll-smooth" style="display: flex; width: 100%; overflow-x: auto; scroll-snap-type: x mandatory; scroll-behavior: smooth; scrollbar-width: none; -ms-overflow-style: none;">
                                            @foreach($reviewMedia as $media)
                                                @php
                                                    $previousMediaIndex = ($loop->index - 1 + $reviewMedia->count()) % $reviewMedia->count();
                                                    $nextMediaIndex = ($loop->index + 1) % $reviewMedia->count();
                                                @endphp
                                                <div id="review-media-{{ $review->id }}-{{ $loop->index }}" class="relative aspect-video w-full flex-none snap-center" style="position: relative; width: 100%; min-width: 100%; aspect-ratio: 16 / 9; scroll-snap-align: center;" wire:key="review-media-{{ $review->id }}-{{ $loop->index }}">
                                                    @if($media['type'] === 'video')
                                                        <video src="{{ $media['url'] }}" controls preload="metadata" class="h-full w-full object-contain"></video>
                                                    @else
                                                        <a href="{{ $media['url'] }}" target="_blank" rel="noopener">
                                                            <img src="{{ $media['url'] }}" alt="Review photo {{ $loop->iteration }}" class="h-full w-full object-contain">
                                                        </a>
                                                    @endif
                                                    @if($reviewMedia->count() > 1)
                                                        <button type="button" data-review-go="{{ $previousMediaIndex }}" aria-label="Previous media"
                                                                style="position: absolute; z-index: 30; left: .5rem; top: 50%; transform: translateY(-50%); display: flex; align-items: center; justify-content: center; width: 2.5rem; height: 2.5rem; border-radius: 9999px; border: 0; background: rgba(0,0,0,.75); color: white; font-size: 1.5rem; cursor: pointer;">&#8249;</button>
                                                        <button type="button" data-review-go="{{ $nextMediaIndex }}" aria-label="Next media"
                                                                style="position: absolute; z-index: 30; right: .5rem; top: 50%; transform: translateY(-50%); display: flex; align-items: center; justify-content: center; width: 2.5rem; height: 2.5rem; border-radius: 9999px; border: 0; background: rgba(0,0,0,.75); color: white; font-size: 1.5rem; cursor: pointer;">&#8250;</button>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        @if($reviewMedia->count() > 1)
                                            <div class="absolute bottom-2 left-1/2 flex -translate-x-1/2 gap-1.5" style="position: absolute; z-index: 30; bottom: .5rem; left: 50%; transform: translateX(-50%); display: flex; gap: .375rem;">
                                                @foreach($reviewMedia as $media)
                                                    <button type="button" data-review-go="{{ $loop->index }}" aria-label="Show media {{ $loop->iteration }}"
                                                            class="h-2 w-2 rounded-full bg-white" style="display: block; width: .5rem; height: .5rem; padding: 0; border: 0; border-radius: 9999px; background: white; opacity: {{ $loop->first ? '1' : '.55' }}; cursor: pointer;"></button>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </article>
                        @empty
                            <div style="padding:2.5rem 1rem;text-align:center;border:1px dashed #d1d5db;border-radius:14px;background:#fff;color:#6b7280;">
                                No reviews match this rating filter.
                                <button type="button" wire:click="filterReviewsByRating('all')" style="display:block;margin:.75rem auto 0;border:0;background:transparent;color:#1B4332;text-decoration:underline;cursor:pointer;">View all reviews</button>
                            </div>
                        @endforelse
                    </div>
                    @if($reviews->hasPages())
                        <div class="mt-8 rounded-xl border border-gray-200 bg-white p-3">{{ $reviews->onEachSide(1)->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </section>

            <!-- Related Products -->
    @if(count($relatedProducts) > 0)
    <section class="py-16 bg-[#FAF9F6]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <p class="text-xs tracking-[0.2em] text-[#C65D3B] mb-2 uppercase">You May Also Like</p>
                <h2 class="font-serif text-3xl md:text-4xl text-[#1B4332]">Related Products</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                <a href="/products/{{ $related->slug }}" class="group" wire:key="related-{{ $related->id }}">
                    <div class="relative overflow-hidden bg-[#FAF9F6] mb-4">
                       @php
    $images = $related->images;
    if (is_string($images)) {
        $images = json_decode($images, true);
    }
    if (!is_array($images)) {
        $images = [$images];
    }
    $firstImage = $images[0] ?? null;
@endphp
                        <img src="{{ $firstImage ? asset('storage/' . $firstImage) : 'https://via.placeholder.com/400x500?text=No+Image' }}" 
                             alt="{{ $related->name }}" 
                             class="w-full h-[350px] object-cover group-hover:scale-105 transition duration-700"
                             onerror="this.style.display='none'">
                        
                        @if($related->on_sale || $related->compare_price > $related->price)
                        <span class="absolute top-4 left-4 bg-[#C65D3B] text-white text-xs tracking-wider px-3 py-1">SALE</span>
                        @endif
                    </div>
                    <h3 class="font-serif text-lg mb-1 group-hover:text-[#C65D3B] transition">{{ $related->name }}</h3>
                    <p class="text-gray-500 text-sm">
                        @if($related->compare_price && $related->compare_price > $related->price)
                            <span class="text-gray-400 line-through mr-2">{{ Number::currency($related->compare_price,'PKR',true) }}</span>
                        @endif
                        {{ Number::currency($related->price,'PKR',true) }}
                    </p>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    </section>
</div>
