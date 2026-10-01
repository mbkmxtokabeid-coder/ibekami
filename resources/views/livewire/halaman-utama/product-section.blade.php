@php
    $version = Cache::rememberForever('homepage_products_version', fn() => time());
    // Ambil 12 produk pertama langsung dari cache
    $initialData = Cache::remember("homepage:ssr:products:v{$version}", now()->addMinutes(10), function() {
        return App\Models\Product::query()
            ->with(['type', 'category'])
            ->where('status', 'Aktif')
            ->orderBy('activated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(12)
            ->get()
            ->map(function ($product) {
                $img = $product->getFirstImageUrl();
                $parsed = parse_url($img);
                if (isset($parsed['host']) && in_array($parsed['host'], ['localhost', '127.0.0.1'])) {
                    $img = ($parsed['path'] ?? '') . (isset($parsed['query']) ? '?' . $parsed['query'] : '');
                }

                return [
                    'id' => $product->product_id,
                    'name' => $product->name,
                    'cat' => $product->type->name ?? $product->category->name ?? 'Produk',
                    'img' => $img,
                    'slug' => $product->getSlug(),
                ];
            })->toArray();
    });
@endphp

<section id="katalog" x-ignore class="py-10 md:py-14 px-4 bg-[#FFF2E0] relative overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute top-10 left-[-5%] w-72 h-72 bg-[#b35200]/10 rounded-full blur-[80px] pointer-events-none"></div>
    <div class="absolute bottom-10 right-[-5%] w-64 h-64 bg-white/40 rounded-full blur-[60px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto relative z-10">

        <!-- Header -->
        <div class="text-center max-w-xl mx-auto mb-10 sm:mb-12">
            <div class="flex items-center justify-center gap-3 text-xs sm:text-[13px] font-bold text-[#b35200] uppercase tracking-[0.2em] mb-2 sm:mb-3">
                <span class="w-10 sm:w-12 h-[1px] bg-[#b35200]"></span>
                {{ __('messages.our_collection') }}
                <span class="w-10 sm:w-12 h-[1px] bg-[#b35200]"></span>
            </div>
            <h2 class="font-['Poppins',sans-serif] text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#2C1A0E] tracking-tight leading-tight">
                {{ __('messages.available_products') }}
            </h2>
            <p class="text-[12px] md:text-sm text-[#886852] mt-2 leading-relaxed">
                {{ __('messages.made_with_love') }}
            </p>
        </div>

        <!-- Product Grid — Direct Blade SSR rendering for zero-latency card load -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
            @foreach($initialData as $index => $product)
                <a href="{{ route('katalog.detail', ['slug' => $product['slug']]) }}"
                   class="card-glow group bg-white/90 dark:bg-[#231811] rounded-3xl p-3 sm:p-3.5 border border-black/5 dark:border-white/10 
                          flex flex-col cursor-pointer
                          @if($index >= 9) hidden lg:flex @elseif($index >= 6) hidden md:flex @endif">

                    <!-- Image Container — dimensi eksplisit mencegah CLS -->
                    <div class="relative z-10 h-[130px] md:h-[180px] rounded-2xl overflow-hidden bg-gradient-to-br from-[#FFF2E0] to-[#FFE5C8] dark:from-[#2A1C14] dark:to-[#1E140D] mb-3">
                        <img 
                            src="{{ $product['img'] }}"
                            alt="{{ $product['name'] }}"
                            loading="lazy"
                            decoding="async"
                            width="400"
                            height="300"
                            class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.06]"
                            onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300?text=' + encodeURIComponent('{{ $product['name'] }}')"
                        >
                        <!-- Soft inner light ring on image hover -->
                        <div class="absolute inset-0 rounded-2xl ring-1 ring-inset ring-black/5 dark:ring-white/10 group-hover:ring-[#ff9100]/40 dark:group-hover:ring-[#b35200]/40 transition-all duration-300 pointer-events-none"></div>
                    </div>

                    <!-- Product Info -->
                    <div class="relative z-10 flex flex-col flex-1 justify-between">
                        <div>
                            <div class="text-[10px] font-bold text-[#ff9100] dark:text-[#b35200] uppercase tracking-wide mb-1 group-hover:text-[#e07d00] dark:group-hover:text-[#b35200] transition-colors duration-200">{{ $product['cat'] }}</div>
                            <h3 class="text-[13px] md:text-sm font-semibold text-[#2C1A0E] dark:text-[#FDF5EC] group-hover:text-[#ff9100] dark:group-hover:text-[#b35200] leading-snug line-clamp-2 transition-colors duration-200">{{ $product['name'] }}</h3>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
</section>

<style>
    /* Smooth image loading */
    img {
        transition: opacity 0.3s ease-in-out;
    }

    /* Line clamp for product names */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
