<?php
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
?>

<section id="katalog" x-ignore class="py-14 md:py-20 px-4 bg-[#FFF2E0] relative overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute top-10 left-[-5%] w-72 h-72 bg-[#FF9100]/10 rounded-full blur-[80px] pointer-events-none"></div>
    <div class="absolute bottom-10 right-[-5%] w-64 h-64 bg-white/40 rounded-full blur-[60px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto relative z-10">

        <!-- Header -->
        <div class="mb-10 flex flex-col sm:flex-row sm:justify-between sm:items-end gap-6">
            <div>
                <div class="inline-flex items-center gap-2 text-[10px] font-semibold text-[#b35200] uppercase tracking-[0.2em] mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#b35200] animate-pulse"></span>
                    <?php echo e(__('messages.our_collection')); ?>

                </div>
                <h2 class="text-2xl md:text-4xl font-extrabold text-[#2C1A0E] leading-tight">
                    <?php echo e(__('messages.available_products')); ?>

                </h2>
                <p class="text-[12px] md:text-sm text-[#886852] mt-2 max-w-md leading-relaxed">
                    <?php echo e(__('messages.made_with_love')); ?>

                </p>
            </div>
        </div>

        <!-- Product Grid — Direct Blade SSR rendering for zero-latency card load -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $initialData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <a href="<?php echo e(route('katalog.detail', ['slug' => $product['slug']])); ?>"
                   class="group bg-white/90 rounded-3xl p-3 border border-black/5 
                          shadow-sm hover:shadow-md hover:shadow-[#b35200]/10 
                          transition-all duration-300 ease-out 
                          hover:-translate-y-1 flex flex-col cursor-pointer
                          <?php if($index >= 9): ?> hidden lg:flex <?php elseif($index >= 6): ?> hidden md:flex <?php endif; ?>">

                    <!-- Image Container — dimensi eksplisit mencegah CLS -->
                    <div class="h-[130px] md:h-[180px] rounded-2xl overflow-hidden bg-gradient-to-br from-[#FFF2E0] to-[#FFE5C8] mb-3">
                        <img 
                            src="<?php echo e($product['img']); ?>"
                            alt="<?php echo e($product['name']); ?>"
                            loading="lazy"
                            decoding="async"
                            width="400"
                            height="300"
                            class="w-full h-full object-cover transition-all duration-500 group-hover:scale-[1.04]"
                            onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300?text=' + encodeURIComponent('<?php echo e($product['name']); ?>')"
                        >
                    </div>

                    <!-- Product Info -->
                    <div>
                        <div class="text-[10px] font-semibold text-[#b35200] uppercase tracking-wide mb-1"><?php echo e($product['cat']); ?></div>
                        <h3 class="text-[13px] md:text-sm font-semibold text-[#2C1A0E] leading-snug line-clamp-2"><?php echo e($product['name']); ?></h3>
                    </div>
                </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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
<?php /**PATH D:\Ibekami\ibekami\resources\views/livewire/halaman-utama/product-section.blade.php ENDPATH**/ ?>