@push('meta')
    @php
        $organizationSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'NAAS Shopping',
            'url' => 'https://naasshopping.com/',
            'logo' => 'https://naasshopping.com/images/naas-logo.jpeg',
            'sameAs' => [
                'https://www.facebook.com/NaasShopping',
                'https://www.instagram.com/naasshopping.pk/',
            ],
        ];

        $websiteSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'NAAS Shopping',
            'url' => 'https://naasshopping.com/',
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => 'https://naasshopping.com/products?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

<div>
    {{-- ==========================================
         HERO SLIDER - Dynamic from DB + Product Fallback
         ========================================== --}}

    @if($sliders->count() > 0)
        {{-- ===== DATABASE SLIDERS (Khaadi Style) ===== --}}
        <div x-data="heroSlider()" 
             x-init="init()"
             class="relative w-full overflow-hidden"
             style="height: calc(100vh - 80px); max-height: 900px;"
             @mouseenter="stopAutoPlay()"
             @mouseleave="startAutoPlay()">

            @foreach($sliders as $index => $slider)
            <div x-show="currentSlide === {{ $index }}"
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 scale-105"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-700"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute inset-0"
                 x-cloak>

                {{-- Desktop Image --}}
                <img src="{{ asset('storage/' . $slider->image) }}" 
                     class="hidden md:block w-full h-full object-cover object-center"
                     alt="{{ $slider->title }}"
                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}">

                {{-- Mobile Image --}}
                <img src="{{ asset('storage/' . ($slider->mobile_image ?? $slider->image)) }}" 
                     class="md:hidden w-full h-full object-cover object-center"
                     alt="{{ $slider->title }}"
                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}">

                {{-- Overlay --}}
                <div class="absolute inset-0" 
                     style="background-color: {{ $slider->overlay_color }}; opacity: {{ $slider->overlay_opacity / 100 }}">
                </div>

                {{-- Content --}}
                <div class="absolute inset-0 flex items-center
                     {{ $slider->text_position === 'center' ? 'justify-center' : ($slider->text_position === 'right' ? 'justify-end' : 'justify-start') }}">

                    <div class="max-w-2xl px-8 md:px-16 lg:px-24
                         {{ $slider->text_position === 'center' ? 'text-center' : ($slider->text_position === 'right' ? 'text-right' : 'text-left') }}"
                         style="color: {{ $slider->text_color }}">

                        @if($slider->subtitle)
                        <p class="text-sm md:text-base tracking-[0.3em] uppercase mb-4 font-light animate-fade-up"
                           style="animation-delay: 0.3s">
                            {{ $slider->subtitle }}
                        </p>
                        @endif

                        <h1 class="font-serif text-4xl md:text-6xl lg:text-7xl leading-tight mb-6 animate-fade-up"
                            style="animation-delay: 0.5s">
                            {{ $slider->title }}
                        </h1>

                        @if($slider->description)
                        <p class="text-base md:text-lg font-light mb-8 opacity-90 max-w-md animate-fade-up
                             {{ $slider->text_position === 'center' ? 'mx-auto' : ($slider->text_position === 'right' ? 'ml-auto' : '') }}"
                           style="animation-delay: 0.7s">
                            {{ $slider->description }}
                        </p>
                        @endif

                        <div class="animate-fade-up" style="animation-delay: 0.9s">
                            <a href="{{ $slider->button_link }}"
                               class="inline-block bg-white text-gray-900 px-10 py-4 text-xs tracking-[0.2em] uppercase hover:bg-gray-900 hover:text-white transition-all duration-300">
                                {{ $slider->button_text }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Navigation Arrows --}}
            @if($sliders->count() > 1)
            <button @click="prev()" 
                    class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center text-white/60 hover:text-white transition-all duration-300 z-10 group">
                <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <button @click="next()" 
                    class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center text-white/60 hover:text-white transition-all duration-300 z-10 group">
                <svg class="w-6 h-6 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            {{-- Dots --}}
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex items-center space-x-3 z-10">
                @foreach($sliders as $index => $slider)
                <button @click="goToSlide({{ $index }})"
                        class="h-2 rounded-full transition-all duration-500"
                        :class="currentSlide === {{ $index }} ? 'w-8 bg-white' : 'w-2 bg-white/50 hover:bg-white/70'"
                        aria-label="Slide {{ $index + 1 }}">
                </button>
                @endforeach
            </div>

            {{-- Progress Bar --}}
            <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-white/20 z-10">
                <div class="h-full bg-white transition-all ease-linear"
                     :style="'width: ' + progress + '%'"></div>
            </div>
            @endif
        </div>

        <script>
            function heroSlider() {
                return {
                    currentSlide: 0,
                    totalSlides: {{ $sliders->count() }},
                    progress: 0,
                    interval: null,
                    progressInterval: null,
                    slideDuration: 6000,

                    init() {
                        if (this.totalSlides > 1) this.startAutoPlay();
                    },

                    startAutoPlay() {
                        this.resetProgress();
                        this.interval = setInterval(() => this.next(), this.slideDuration);
                        this.progressInterval = setInterval(() => {
                            this.progress += 1.67;
                        }, 100);
                    },

                    resetProgress() {
                        this.progress = 0;
                        clearInterval(this.progressInterval);
                    },

                    stopAutoPlay() {
                        clearInterval(this.interval);
                        clearInterval(this.progressInterval);
                    },

                    next() {
                        this.stopAutoPlay();
                        this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                        this.startAutoPlay();
                    },

                    prev() {
                        this.stopAutoPlay();
                        this.currentSlide = this.currentSlide === 0 ? this.totalSlides - 1 : this.currentSlide - 1;
                        this.startAutoPlay();
                    },

                    goToSlide(index) {
                        this.stopAutoPlay();
                        this.currentSlide = index;
                        this.startAutoPlay();
                    }
                }
            }
        </script>

    @else
        {{-- ===== PRODUCT FALLBACK SLIDER ===== --}}
        <div x-data="{ 
            currentSlide: 0, 
            products: {{ $bestSellingProducts->map(function($product) {
                $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
                if (!is_array($images)) $images = [$product->images ?? $product->image];
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'description' => $product->description,
                    'image_url' => asset('storage/' . ($images[0] ?? $product->image)),
                ];
            })->toJson() }},
            totalSlides: {{ $bestSellingProducts->count() }},
            autoSlide() {
                setInterval(() => {
                    this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                }, 5000);
            }
        }" x-init="autoSlide()" class="relative h-[600px] md:h-[700px] lg:h-[calc(100vh-80px)] overflow-hidden">

            <template x-for="(product, index) in products" :key="index">
                <div x-show="currentSlide === index" 
                     x-transition:enter="transition ease-out duration-1000"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-700"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute inset-0">

                    <img :src="product.image_url" 
                         class="w-full h-full object-cover" 
                         :alt="product.name">

                    <div class="absolute inset-0 bg-black/30"></div>

                    <div class="relative z-10 flex items-center h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="max-w-xl">
                            <p class="text-white/80 text-sm tracking-[0.3em] uppercase mb-3 font-light">Best Selling</p>
                            <h1 class="font-serif text-5xl md:text-6xl text-white mb-4 leading-tight" x-text="product.name"></h1>
                            <p class="text-white/90 text-lg mb-2 font-light" x-text="product.description ? product.description.substring(0, 100) + '...' : ''"></p>
                            <p class="text-white text-2xl font-serif mb-8" x-text="'Rs. ' + product.price"></p>
                            <a :href="'/products/' + product.slug" class="inline-block bg-white text-gray-900 px-8 py-3 text-xs tracking-[0.2em] uppercase hover:bg-gray-900 hover:text-white transition-all duration-300">SHOP NOW</a>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Arrows --}}
            <button @click="currentSlide = currentSlide > 0 ? currentSlide - 1 : totalSlides - 1" 
                    class="absolute left-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-white/40 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition z-20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button @click="currentSlide = currentSlide < totalSlides - 1 ? currentSlide + 1 : 0" 
                    class="absolute right-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-white/40 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition z-20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>

            {{-- Dots --}}
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex space-x-3 z-20">
                <template x-for="(product, index) in products" :key="index">
                    <button @click="currentSlide = index" 
                            :class="currentSlide === index ? 'w-8 bg-white' : 'w-2 bg-white/50'"
                            class="h-2 rounded-full transition-all duration-500"></button>
                </template>
            </div>
        </div>
    @endif

    {{-- ==========================================
         FEATURES BAR
         ========================================== --}}
    <div class="bg-[#F5F3EF] py-8 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="flex items-center justify-center space-x-3">
                    <svg class="w-6 h-6 text-[#1B4332]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <div class="text-left">
                        <p class="text-sm font-medium text-gray-900">Free Shipping in Pakistan</p>
                        <p class="text-xs text-gray-500">On orders over Rs. 10,000</p>
                    </div>
                </div>
                <div class="flex items-center justify-center space-x-3">
                    <svg class="w-6 h-6 text-[#1B4332]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <div class="text-left">
                        <p class="text-sm font-medium text-gray-900">Cash on Delivery</p>
                        <p class="text-xs text-gray-500">Pay when your order arrives</p>
                    </div>
                </div>
                <div class="flex items-center justify-center space-x-3">
                    <svg class="w-6 h-6 text-[#1B4332]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <div class="text-left">
                        <p class="text-sm font-medium text-gray-900">Crafted in Pakistan</p>
                        <p class="text-xs text-gray-500">By artisans, since 2019</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
 {{-- ==========================================
         NEW ARRIVALS
         ========================================== --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <p class="text-xs tracking-[0.2em] text-[#C65D3B] mb-2 uppercase">Just Landed</p>
                    <h2 class="font-serif text-4xl text-[#1B4332]">New arrivals.</h2>
                </div>
                <a href="{{ route('products') }}" class="text-sm tracking-wider hover:text-[#C65D3B] transition flex items-center group">
                    VIEW ALL 
                    <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                <a href="{{ route('product-detail', $product->slug) }}" class="group" wire:key="feat-{{ $product->id }}">
                    <div class="relative overflow-hidden bg-[#FAF9F6] mb-4">
                        @php
                            $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
                            if (!is_array($images)) $images = [$product->images ?? $product->image];
                            $firstImage = $images[0] ?? $product->image;
                        @endphp
                        <img src="{{ asset('storage/' . $firstImage) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-[350px] object-cover group-hover:scale-105 transition duration-700">
                        <span class="absolute top-4 left-4 bg-white text-xs tracking-wider px-3 py-1">NEW</span>

                        <div class="absolute bottom-0 left-0 right-0 p-4 translate-y-full group-hover:translate-y-0 transition duration-300">
                            <button class="w-full bg-white text-gray-900 py-3 text-xs tracking-wider uppercase hover:bg-gray-900 hover:text-white transition">
                                Quick View
                            </button>
                        </div>
                    </div>
                    <h3 class="font-serif text-lg mb-1 group-hover:text-[#C65D3B] transition">{{ $product->name }}</h3>
                   <p class="text-gray-500 text-sm">
                        @if($product->compare_price && $product->compare_price > $product->price)
                            <span class="text-gray-400 line-through mr-2">Rs. {{ number_format($product->compare_price) }}</span>
                        @endif
                        Rs. {{ number_format($product->price) }}
                    </p>
                </a>
                @endforeach
            </div>
        </div>
    </section>
  {{-- ==========================================
     BEST SELLING PRODUCTS
     ========================================== --}}
<section class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-end mb-12">
        <div>
            <p class="text-xs tracking-[0.2em] text-[#C65D3B] mb-2 uppercase">Best Selling</p>
            <h2 class="font-serif text-4xl text-[#1B4332]">Best Selling.</h2>
        </div>
        <a href="{{ route('products') }}" class="text-sm tracking-wider hover:text-[#C65D3B] transition flex items-center group">
            VIEW ALL 
            <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($bestSellingProducts as $product)
        <a href="{{ route('product-detail', $product->slug) }}" class="group" wire:key="best-{{ $product->id }}">
            <div class="relative overflow-hidden bg-[#FAF9F6] mb-4">
                @php
                    $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
                    if (!is_array($images)) $images = [$product->images ?? $product->image];
                    $firstImage = $images[0] ?? $product->image;
                @endphp
                <img src="{{ asset('storage/' . $firstImage) }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-[350px] object-cover group-hover:scale-105 transition duration-700">
                <span class="absolute top-4 left-4 bg-white text-xs tracking-wider px-3 py-1">BEST</span>

                <div class="absolute bottom-0 left-0 right-0 p-4 translate-y-full group-hover:translate-y-0 transition duration-300">
                    <button class="w-full bg-white text-gray-900 py-3 text-xs tracking-wider uppercase hover:bg-gray-900 hover:text-white transition">
                        Quick View
                    </button>
                </div>
            </div>
            <h3 class="font-serif text-lg mb-1 group-hover:text-[#C65D3B] transition">{{ $product->name }}</h3>
            <p class="text-gray-500 text-sm">
                @if($product->compare_price && $product->compare_price > $product->price)
                    <span class="text-gray-400 line-through mr-2">Rs. {{ number_format($product->compare_price) }}</span>
                @endif
                Rs. {{ number_format($product->price) }}
            </p>
        </a>
        @endforeach
    </div>
</section>
   
    {{-- ==========================================
         CLIENT TESTIMONIALS / REVIEWS
         ========================================== --}}
    @if($testimonials->count() > 0)
    <section class="py-20 bg-[#FAF9F6]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="text-center mb-16">
                <p class="text-xs tracking-[0.2em] text-[#C65D3B] mb-2 uppercase">What They Say</p>
                <h2 class="font-serif text-4xl md:text-5xl text-[#1B4332] mb-4">Client Stories.</h2>
                <p class="text-gray-500 text-sm max-w-md mx-auto">Real feedback from our valued customers across Pakistan.</p>
            </div>

            {{-- Testimonials Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($testimonials as $testimonial)
                <div class="bg-white p-6 shadow-sm hover:shadow-lg transition-shadow duration-300 group" wire:key="testimonial-{{ $testimonial->id }}">

                    {{-- Video Review --}}
                    @if($testimonial->type === 'video')
                    <div class="relative mb-4 overflow-hidden rounded-lg bg-gray-900">
                        {{-- Video Player --}}
                        <video class="w-full aspect-video object-cover"
                               poster="{{ $testimonial->thumbnail_url ?? '' }}"
                               preload="metadata"
                               playsinline>
                            <source src="{{ $testimonial->video_url }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>

                        {{-- Custom Play Button Overlay --}}
                        <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/40 transition cursor-pointer video-play-btn">
                            <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-lg transform group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-[#1B4332] ml-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Text Review --}}
                    @else
                    <div class="mb-4">
                        <svg class="w-8 h-8 text-[#C65D3B]/30 mb-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                        </svg>
                        <p class="text-gray-600 text-sm leading-relaxed line-clamp-4">
                            "{{ $testimonial->content }}"
                        </p>
                    </div>
                    @endif

                    {{-- Client Info --}}
                    <div class="flex items-center pt-4 border-t border-gray-100">
                        <img src="{{ $testimonial->client_image ? asset('storage/' . $testimonial->client_image) : 'https://ui-avatars.com/api/?name=' . urlencode($testimonial->client_name) . '&background=1B4332&color=fff' }}" 
                             alt="{{ $testimonial->client_name }}"
                             class="w-10 h-10 rounded-full object-cover mr-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $testimonial->client_name }}</p>
                            @if($testimonial->client_location)
                            <p class="text-xs text-gray-500 truncate">{{ $testimonial->client_location }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Rating & Product --}}
                    <div class="mt-3 flex items-center justify-between">
                        <div class="flex text-yellow-400 text-xs">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $testimonial->rating)
                                    ★
                                @else
                                    <span class="text-gray-300">★</span>
                                @endif
                            @endfor
                        </div>
                        @if($testimonial->product_name)
                        <span class="text-xs text-gray-400 truncate ml-2">{{ $testimonial->product_name }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>
