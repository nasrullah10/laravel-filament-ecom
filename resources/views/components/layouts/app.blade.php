<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-88BS9Z6DB1"></script>
  
   <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Naasshopping",
  "url": "https://naasshopping.com/",
  "logo": "https://naasshopping.com/images/naas-logo.jpeg"
}
</script>

    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-88BS9Z6DB1');
    </script>
    <!-- Meta Pixel Code -->
    <!--  <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1568461734864615');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=1568461734864615&ev=PageView&noscript=1"
    /></noscript>-->
    <!-- End Meta Pixel Code -->
    <!-- Meta Pixel Code -->
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '2259771218109187');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=2259771218109187&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
<!-- End Meta Pixel Code -->
    <!-- Google Tag Manager -->
    <script>
    (function(w,d,s,l,i){
        w[l]=w[l]||[];
        w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});
        var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),
        dl=l!='dataLayer'?'&l='+l:'';
        j.async=true;
        j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
        f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-W3CKK5C2');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $title ?? 'NAAS Shopping - Premium Fashion')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    
    @php
        $pageMeta = trim($__env->yieldPushContent('meta'));
        $privateRouteNames = [
            'cart', 'checkout', 'checkout.success', 'checkout.cancel',
            'login', 'register', 'password.request', 'password.reset',
            'my-orders', 'my-order-detail', 'logout',
        ];
    @endphp
    @if($pageMeta !== '')
        {!! $pageMeta !!}
    @else
        <x-seo-meta
            :title="$title ?? 'NAAS Shopping - Premium Fashion'"
            description="Shop premium modest fashion, abayas, hijabs, bedsheets and accessories at NAAS Shopping with delivery across Pakistan."
            :canonical="url()->current()"
            :robots="request()->routeIs(...$privateRouteNames) ? 'noindex,nofollow' : 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1'"
        />
    @endif
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles
</head>
<!-- Floating WhatsApp Button with Animation -->
<a href="https://wa.me/923162131295" 
   target="_blank" 
   rel="noopener noreferrer"
   class="fixed bottom-6 right-6 z-50 bg-[#25D366] hover:bg-[#128C7E] text-white rounded-full p-4 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group animate-bounce-slow"
   aria-label="Chat on WhatsApp">
   
    <!-- White Pulse Ring Effect -->
    <span class="absolute inset-0 rounded-full bg-white animate-ping opacity-60"></span>
    
    <svg class="w-7 h-7 relative z-10" fill="currentColor" viewBox="0 0 24 24">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
    </svg>
    
    <!-- Tooltip -->
    <span class="absolute right-full mr-3 top-1/2 -translate-y-1/2 bg-gray-900 text-white text-xs px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap pointer-events-none">
        Chat with us
        <span class="absolute right-[-4px] top-1/2 -translate-y-1/2 border-4 border-transparent border-l-gray-900"></span>
    </span>
</a>

<style>
@keyframes bounce-slow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
.animate-bounce-slow {
    animation: bounce-slow 2s infinite ease-in-out;
}
</style>
<body class="font-sans bg-naas-cream text-naas-dark antialiased">

     <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe
            src="https://www.googletagmanager.com/ns.html?id=GTM-W3CKK5C2"
            height="0"
            width="0"
            style="display:none;visibility:hidden">
        </iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
    
    <!-- LIVEWIRE NAVBAR COMPONENT -->
    <livewire:partials.navbar />

    <!-- Main Content -->
    <main>
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot }}
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-naas-green text-white mt-20">
        <div class="max-w-[85rem] mx-auto px-4 md:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">

                <!-- Column 1: Information (Privacy, Terms, About Us, Contact Us) -->
                <div>
                    <h4 class="text-xs tracking-widest mb-6 text-gray-400">INFORMATION</h4>
                    <ul class="space-y-3 text-sm">
                        @php
                            $infoPages = \App\Models\Page::active()
                                ->where(function ($query) {
                                    $query->whereIn('type', ['privacy_policy', 'terms_conditions', 'about_us', 'contact'])
                                        ->orWhereIn('slug', ['shipping-returns', 'faq']);
                                })
                                ->ordered()
                                ->get();
                        @endphp

                        @foreach($infoPages as $infoPage)
                            <li>
                                @php
                                    $routeName = match($infoPage->type) {
                                        'privacy_policy' => 'privacy-policy',
                                        'terms_conditions' => 'terms-conditions',
                                        'about_us' => 'about-us',
                                        'contact' => 'contact-us',
                                        default => 'page.show',
                                    };
                                @endphp
                                <a href="{{ $infoPage->type === 'custom' ? route('page.show', $infoPage->slug) : route($routeName) }}"
                                   class="hover:text-naas-terracotta transition">
                                    {{ $infoPage->title }}
                                </a>
                            </li>
                        @endforeach

                    </ul>
                </div>

                <!-- Column 2: Shop -->
                <div>
                    <h4 class="text-xs tracking-widest mb-6 text-gray-400">SHOP</h4>
                    <ul class="space-y-3 text-sm">
                        @foreach($menuCategories ?? [] as $category)
                        <li><a href="/products?category={{ $category->slug }}" class="hover:text-naas-terracotta transition">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Column 3: Maison -->
                <div>
                    <h4 class="text-xs tracking-widest mb-6 text-gray-400">MAISON</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="hover:text-naas-terracotta transition">Our Story</a></li>
                        <li><a href="#" class="hover:text-naas-terracotta transition">Careers</a></li>
                        <li><a href="#" class="hover:text-naas-terracotta transition">Press</a></li>
                    </ul>
                    <div class="flex space-x-4 mt-6">
                        <a href="https://www.facebook.com/NaasShopping" target="_blank" rel="noopener noreferrer" class="hover:text-naas-terracotta transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/naasshopping.pk/" target="_blank" rel="noopener noreferrer" aria-label="NAAS Shopping on Instagram" class="hover:text-naas-terracotta transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-12 pt-8 text-center text-xs text-gray-400">
                © 2026 NAAS Shopping — naasshopping.com. All rights reserved.
            </div>
        </div>
    </footer>

    @livewireScripts
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
