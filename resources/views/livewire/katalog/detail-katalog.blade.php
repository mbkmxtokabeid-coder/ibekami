<!-- <div class="bg-[#fff2e0] min-h-screen font-sans text-[#3d2b1f]" wire:poll.10s="checkVersion"> -->
<div class="bg-[#fff2e0] dark:bg-[#130D08] min-h-screen font-sans text-[#3d2b1f] dark:text-[#D8C6B6]" @production wire:poll.30s="checkVersion" @endproduction>
    
    {{-- Preload primary LCP image for high-speed delivery --}}
    @push('preload')
        <link rel="preload" as="image" href="{{ $productData['images'][0] ?? $productData['image'] }}" type="image/webp" fetchpriority="high">
    @endpush
    
    {{-- HERO SECTION --}}
    <section class="relative bg-[#fff2e0] dark:bg-[#130D08] overflow-hidden border-b border-[#b35200]/10 dark:border-[#b35200]/20">
        <div class="absolute top-[-10%] left-[-5%] w-72 h-72 bg-[#b35200] opacity-[0.08] rounded-full blur-[100px] pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-5 md:pt-28 md:pb-6 relative z-10 text-center">
            <div class="inline-flex items-center gap-3 text-[11px] font-black text-[#ff9100] dark:text-[#b35200] uppercase tracking-[0.2em] mb-3 bg-white dark:bg-[#231811] px-4 py-1.5 rounded-full shadow-sm border border-[#ff9100]/10 dark:border-[#b35200]/30">
                <span class="w-6 h-[2px] bg-[#ff9100] dark:bg-[#b35200] rounded-full"></span>
                {{ __('messages.product_detail') }}
                <span class="w-6 h-[2px] bg-[#ff9100] dark:bg-[#b35200] rounded-full"></span>
            </div>
            <h1 class="text-3xl md:text-5xl font-extrabold text-[#3d2b1f] dark:text-[#FDF5EC] leading-tight tracking-tight">
                {{ __('messages.best_quality') }} <em class="italic text-[#ff9100] dark:text-[#b35200] not-italic">{{ __('messages.for_you') }}</em> {{ __('messages.for_you_text') }}
            </h1>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-start pt-5 pb-10 md:pt-6 md:pb-12">
        <!-- Image Gallery -->
        <section x-data="{ activeImg: '{{ $productData['images'][0] ?? $productData['image'] }}' }">
            <div class="relative aspect-square rounded-[36px] overflow-hidden bg-white dark:bg-[#231811] border border-[#ff9100]/10 dark:border-[#b35200]/20 shadow-[0_20px_50px_rgba(166,78,47,0.15)] dark:shadow-none group">
                <img :src="activeImg" 
                     src="{{ $productData['images'][0] ?? $productData['image'] }}"
                     alt="{{ $productData['name'] }}" 
                     id="mainProductImage"
                     loading="eager"
                     fetchpriority="high"
                     decoding="sync"
                     width="600"
                     height="600"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                     onerror="this.src='https://via.placeholder.com/400x300?text=Image+Not+Found'">
                <span class="absolute top-5 left-5 bg-[#ff9100] dark:bg-[#b35200] text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest shadow-lg shadow-[#ff9100]/30 dark:shadow-[#b35200]/30">{{ $productData['category'] }}</span>
            </div>

            <!-- Thumbnails -->
            <div class="flex gap-3 sm:gap-4 mt-4 sm:mt-5">
                @foreach($productData['images'] as $index => $image)
                <button 
                    @click="activeImg = '{{ $image }}'" 
                    onclick="changeMainImage('{{ $image }}', this)"
                    class="thumbnail-btn w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden border-2 bg-white dark:bg-[#231811] transition-all shadow-sm {{ $index === 0 ? 'border-[#ff9100] dark:border-[#b35200] scale-105' : 'border-transparent opacity-60' }}" 
                    :class="activeImg === '{{ $image }}' ? 'border-[#ff9100] dark:border-[#b35200] scale-105' : 'border-transparent opacity-60'">
                    <img src="{{ $image }}" width="80" height="80" loading="lazy" class="w-full h-full object-cover" alt="Gambar {{ $index + 1 }}" onerror="this.src='https://via.placeholder.com/80x80?text={{ $index + 1 }}'">
                </button>
                @endforeach
            </div>
        </section>

        <!-- Product Info -->
        <section class="flex flex-col space-y-6">
            <div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-[#3d2b1f] dark:text-[#FDF5EC] leading-tight tracking-tight mb-4">
                    {{ $productData['name'] }}
                </h1>
                <p class="text-sm md:text-base text-[#7a6452] dark:text-[#D8C6B6] leading-relaxed font-medium italic">"{{ $productData['desc'] }}"</p>
                
                @if($productData['price'] > 0)
                <div class="mt-4 flex items-center gap-3" data-nosnippet>
                    @if($productData['discount'] > 0)
                        @php
                            $discountedPrice = $productData['price'] * (1 - $productData['discount'] / 100);
                        @endphp
                        <span class="text-2xl font-bold text-[#ff9100] dark:text-[#b35200]">Rp {{ number_format($discountedPrice, 0, ',', '.') }}</span>
                        <span class="text-lg text-gray-500 line-through">Rp {{ number_format($productData['price'], 0, ',', '.') }}</span>
                        <span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-sm font-semibold">-{{ $productData['discount'] }}%</span>
                    @else
                        <span class="text-2xl font-bold text-[#ff9100] dark:text-[#b35200]">Rp {{ number_format($productData['price'], 0, ',', '.') }}</span>
                    @endif
                </div>
                @endif
            </div>

            <!-- Specs Table -->
            <div class="bg-white dark:bg-[#231811] rounded-[28px] p-6 sm:p-7 shadow-[0_10px_30px_rgba(0,0,0,0.03)] dark:shadow-none border border-[#ff9100]/10 dark:border-[#b35200]/20">
                <h2 class="text-xl md:text-2xl font-bold text-[#3d2b1f] dark:text-[#FDF5EC] mb-5">{{ __('messages.product_details') }}</h2>
                
                <div class="space-y-3.5">
                    @if(!empty($productData['details']))
                        @foreach($productData['details'] as $key => $value)
                            <div class="grid grid-cols-[120px_20px_1fr] text-[14px] md:text-[15px] font-medium text-[#3d2b1f] dark:text-[#FDF5EC]">
                                <span class="text-[#886852] dark:text-[#9E8B7D]">{{ ucfirst($key) }}</span>
                                <span class="text-center text-[#886852] dark:text-[#9E8B7D]">:</span>
                                <span class="font-normal">{{ $value }}</span>
                            </div>
                        @endforeach
                    @else
                        <div class="grid grid-cols-[120px_20px_1fr] text-[14px] md:text-[15px] font-medium text-[#3d2b1f] dark:text-[#FDF5EC]">
                            <span class="text-[#886852] dark:text-[#9E8B7D]">{{ __('messages.category') }}</span>
                            <span class="text-center text-[#886852] dark:text-[#9E8B7D]">:</span>
                            <span class="font-normal">{{ $productData['category'] }}</span>
                        </div>
                        <div class="grid grid-cols-[120px_20px_1fr] text-[14px] md:text-[15px] font-medium text-[#3d2b1f] dark:text-[#FDF5EC]">
                            <span class="text-[#886852] dark:text-[#9E8B7D]">{{ __('messages.status') }}</span>
                            <span class="text-center text-[#886852] dark:text-[#9E8B7D]">:</span>
                            <span class="font-normal">{{ __('messages.available') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 w-full">
                <a href="{{ session('katalog_last_url', route('katalog')) }}" 
                   @click="if (window.history.length > 1 && document.referrer && document.referrer.includes('/katalog')) { $event.preventDefault(); window.history.back(); }"
                   class="flex-1 bg-white dark:bg-[#231811] border-2 border-[#ff9100] dark:border-[#b35200] text-[#2C1A0E] dark:text-[#FDF5EC] py-4 rounded-2xl flex items-center justify-center gap-2 font-bold text-base transition-all hover:bg-[#fff2e0] dark:hover:bg-[#b35200]/20 active:scale-[0.98] shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('messages.catalog') }}
                </a>

                <a href="https://wa.me/628170769999?text=Halo%20Admin%2C%20saya%20tertarik%20dengan%20produk%20dari%20website%20Ibekami.id.%20Bisa%20bantu%20untuk%20info%20lebih%20lanjut%3F" 
                   target="_blank"
                   rel="noopener noreferrer"
                   @click.throttle.2000ms
                   class="flex-[2] bg-[#ff9100] dark:bg-[#b35200] hover:bg-[#e68200] dark:hover:bg-[#994500] text-[#2C1A0E] dark:text-white py-4 rounded-2xl flex items-center justify-center gap-3 font-bold text-base transition-all shadow-[0_12px_24px_rgba(255,145,0,0.3)] dark:shadow-[0_12px_24px_rgba(179,82,0,0.3)] active:scale-[0.98]">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    {{ __('messages.order_via_whatsapp') }}
                </a>
            </div>
        </section>
    </main>

    {{-- RELATED PRODUCTS --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-14 md:pt-10 md:pb-16 border-t border-[#b35200]/10 dark:border-[#b35200]/20">
        <h2 class="text-2xl md:text-3xl font-extrabold text-[#3d2b1f] dark:text-[#FDF5EC] tracking-tight mb-6 md:mb-8">{{ __('messages.you_may_also_like') }}</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            @forelse($relatedProducts as $item)
                <a wire:key="related-{{ $item['slug'] }}"
                   href="{{ route('katalog.detail', ['slug' => $item['slug']]) }}"
                   class="card-glow group bg-white dark:bg-[#231811] rounded-2xl overflow-hidden border border-black/5 dark:border-white/10 flex flex-col cursor-pointer">

                    <div class="aspect-[4/3] bg-[#E8E3D8] dark:bg-[#1E140D] relative overflow-hidden shrink-0 z-10">
                        <img src="{{ $item['img'] }}"
                            alt="{{ $item['name'] }}"
                            loading="lazy"
                            decoding="async"
                            width="400"
                            height="300"
                            class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
                            onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300?text=' + encodeURIComponent('{{ $item['name'] }}')">
                        
                        <div class="absolute inset-0 bg-[#2C1A0E]/35 dark:bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="bg-white dark:bg-[#1A120B] text-[#b35200] font-bold px-4 py-2 rounded-lg text-xs translate-y-3 group-hover:translate-y-0 transition-transform duration-300 shadow-lg">
                                {{ __('messages.view_details') }}
                            </span>
                        </div>
                    </div>

                    <div class="p-4 flex-1 flex flex-col justify-start relative z-10">
                        <p class="text-[10px] font-bold text-[#b35200] uppercase tracking-wider mb-1 group-hover:text-[#994500] transition-colors duration-200">
                            {{ $item['cat'] }}
                        </p>
                        <h3 class="text-[13px] font-bold text-[#2C1A0E] dark:text-[#FDF5EC] group-hover:text-[#b35200] dark:group-hover:text-[#FFA834] leading-snug line-clamp-2 transition-colors duration-200">
                            {{ $item['name'] }}
                        </h3>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-[#886852] dark:text-[#9E8B7D] text-sm">{{ __('messages.no_related_products') }}</p>
                </div>
            @endforelse
        </div>
    </section>
</div>

<script>
function changeMainImage(imageUrl, clickedButton) {
    const mainImage = document.getElementById('mainProductImage');
    if (mainImage) {
        mainImage.src = imageUrl;
    }
    
    const allThumbnails = document.querySelectorAll('.thumbnail-btn');
    allThumbnails.forEach(btn => {
        btn.classList.remove('border-[#b35200]', 'scale-105');
        btn.classList.add('border-transparent', 'opacity-60');
    });
    
    if (clickedButton) {
        clickedButton.classList.remove('border-transparent', 'opacity-60');
        clickedButton.classList.add('border-[#b35200]', 'scale-105');
    }
}
</script>

{{-- Structured Data: Product + BreadcrumbList --}}
@if(config('app.env') === 'production')
@php
    $schemaImages = array_values($productData['images'] ?? [$productData['image']]);
    $schemaPrice = 0;
    $schemaPriceNote = '';
    if ($productData['price'] > 0) {
        if ($productData['discount'] > 0) {
            $schemaPrice = round($productData['price'] * (1 - $productData['discount'] / 100));
        } else {
            $schemaPrice = $productData['price'];
        }
    } else {
        $schemaPriceNote = 'Hubungi kami untuk informasi harga';
    }
    $schemaData = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Product',
                'name' => $productData['name'],
                'description' => $productData['desc'],
                'image' => $schemaImages,
                'category' => $productData['category'],
                'brand' => ['@type' => 'Brand', 'name' => 'IBEKAMI'],
                'offers' => array_filter([
                    '@type' => 'Offer',
                    'url' => url()->current(),
                    'priceCurrency' => 'IDR',
                    'price' => (string) $schemaPrice,
                    'description' => $schemaPriceNote ?: null,
                    'priceValidUntil' => ($productData['discount'] > 0) ? now()->addMonths(3)->toDateString() : null,
                    'availability' => 'https://schema.org/InStock',
                    'seller' => ['@type' => 'Organization', 'name' => 'IBEKAMI'],
                ]),
            ],
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => config('app.url')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Katalog', 'item' => route('katalog')],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => $productData['name'], 'item' => url()->current()],
                ],
            ],
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($schemaData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endif
