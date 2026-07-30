<div class="bg-[#fff2e0] min-h-screen font-sans text-[#3d2b1f]" wire:poll.10s="checkVersion">
    
    
    <?php $__env->startPush('preload'); ?>
        <link rel="preload" as="image" href="<?php echo e($productData['images'][0] ?? $productData['image']); ?>" type="image/webp" fetchpriority="high">
    <?php $__env->stopPush(); ?>
    
    
    <section class="relative bg-[#fff2e0] overflow-hidden border-b border-[#ff9100]/10">
        <div class="absolute top-[-10%] left-[-5%] w-72 h-72 bg-[#ff9100] opacity-[0.08] rounded-full blur-[100px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24 relative z-10 text-center">
            <div class="inline-flex items-center gap-3 text-[11px] font-black text-[#ff9100] uppercase tracking-[0.2em] mb-6 bg-white px-4 py-2 rounded-full shadow-sm border border-[#ff9100]/10">
                <span class="w-6 h-[2px] bg-[#ff9100] rounded-full"></span>
                <?php echo e(__('messages.product_detail')); ?>

                <span class="w-6 h-[2px] bg-[#ff9100] rounded-full"></span>
            </div>
            <h1 class="font-['Playfair_Display'] text-4xl md:text-6xl font-bold text-[#3d2b1f] leading-tight mb-4">
                <?php echo e(__('messages.best_quality')); ?> <em class="italic text-[#ff9100] not-italic"><?php echo e(__('messages.for_you')); ?></em> <?php echo e(__('messages.for_you_text')); ?>

            </h1>
        </div>
    </section>

    
    <main class="max-w-7xl mx-auto p-6 grid grid-cols-1 md:grid-cols-2 gap-16 items-start py-16">
        <!-- Image Gallery -->
        <section x-data="{ activeImg: '<?php echo e($productData['images'][0] ?? $productData['image']); ?>' }">
            <div class="relative aspect-square rounded-[40px] overflow-hidden bg-white border border-[#ff9100]/10 shadow-[0_20px_50px_rgba(166,78,47,0.15)] group">
                <img :src="activeImg" 
                     src="<?php echo e($productData['images'][0] ?? $productData['image']); ?>"
                     alt="<?php echo e($productData['name']); ?>" 
                     id="mainProductImage"
                     loading="eager"
                     fetchpriority="high"
                     decoding="sync"
                     width="600"
                     height="600"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                     onerror="this.src='https://via.placeholder.com/400x300?text=Image+Not+Found'">
                <span class="absolute top-6 left-6 bg-[#ff9100] text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest shadow-lg shadow-[#ff9100]/30"><?php echo e($productData['category']); ?></span>
            </div>

            <!-- Thumbnails -->
            <div class="flex gap-4 mt-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $productData['images']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <button 
                    @click="activeImg = '<?php echo e($image); ?>'" 
                    onclick="changeMainImage('<?php echo e($image); ?>', this)"
                    class="thumbnail-btn w-20 h-20 rounded-[20px] overflow-hidden border-2 transition-all shadow-sm <?php echo e($index === 0 ? 'border-[#ff9100] scale-105' : 'border-transparent opacity-60'); ?>" 
                    :class="activeImg === '<?php echo e($image); ?>' ? 'border-[#ff9100] scale-105' : 'border-transparent opacity-60'">
                    <img src="<?php echo e($image); ?>" width="80" height="80" loading="lazy" class="w-full h-full object-cover" alt="Gambar <?php echo e($index + 1); ?>" onerror="this.src='https://via.placeholder.com/80x80?text=<?php echo e($index + 1); ?>'">
                </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

            </div>
        </section>

        <!-- Product Info -->
        <section class="flex flex-col space-y-8">
            <div>
                <h1 class="text-4xl md:text-5xl font-['Playfair_Display'] font-bold text-[#3d2b1f] leading-tight mb-6">
                    <?php echo e($productData['name']); ?>

                </h1>
                <p class="text-base text-[#7a6452] leading-relaxed font-medium italic">"<?php echo e($productData['desc']); ?>"</p>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($productData['price'] > 0): ?>
                <div class="mt-4 flex items-center gap-3" data-nosnippet>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($productData['discount'] > 0): ?>
                        <?php
                            $discountedPrice = $productData['price'] * (1 - $productData['discount'] / 100);
                        ?>
                        <span class="text-2xl font-bold text-[#ff9100]">Rp <?php echo e(number_format($discountedPrice, 0, ',', '.')); ?></span>
                        <span class="text-lg text-gray-500 line-through">Rp <?php echo e(number_format($productData['price'], 0, ',', '.')); ?></span>
                        <span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-sm font-semibold">-<?php echo e($productData['discount']); ?>%</span>
                    <?php else: ?>
                        <span class="text-2xl font-bold text-[#ff9100]">Rp <?php echo e(number_format($productData['price'], 0, ',', '.')); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Specs Table -->
            <div class="bg-white rounded-[32px] p-8 shadow-[0_10px_30px_rgba(0,0,0,0.03)] border border-[#ff9100]/5">
                <h2 class="text-2xl font-bold text-[#3d2b1f] mb-6"><?php echo e(__('messages.product_details')); ?></h2>
                
                <div class="space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($productData['details'])): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $productData['details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="grid grid-cols-[120px_20px_1fr] text-[15px] md:text-lg font-medium text-[#3d2b1f]">
                                <span class="text-[#3d2b1f]"><?php echo e(ucfirst($key)); ?></span>
                                <span class="text-center">:</span>
                                <span class="font-normal"><?php echo e($value); ?></span>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <?php else: ?>
                        <div class="grid grid-cols-[120px_20px_1fr] text-[15px] md:text-lg font-medium text-[#3d2b1f]">
                            <span class="text-[#3d2b1f]"><?php echo e(__('messages.category')); ?></span>
                            <span class="text-center">:</span>
                            <span class="font-normal"><?php echo e($productData['category']); ?></span>
                        </div>
                        <div class="grid grid-cols-[120px_20px_1fr] text-[15px] md:text-lg font-medium text-[#3d2b1f]">
                            <span class="text-[#3d2b1f]"><?php echo e(__('messages.status')); ?></span>
                            <span class="text-center">:</span>
                            <span class="font-normal"><?php echo e(__('messages.available')); ?></span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 w-full">
                <a href="<?php echo e(route('katalog')); ?>" 
                   class="flex-1 bg-white border-2 border-[#ff9100] text-[#2C1A0E] py-5 rounded-[24px] flex items-center justify-center gap-2 font-black text-lg transition-all hover:bg-[#fff2e0] active:scale-[0.98] shadow-[0_10px_20px_rgba(255,145,0,0.05)]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <?php echo e(__('messages.catalog')); ?>

                </a>

                <a href="https://wa.me/6281707699?text=Halo%20Admin%2C%20saya%20tertarik%20dengan%20produk%20dari%20Ibekami.id.%20Bisa%20bantu%20untuk%20info%20lebih%20lanjut%3F" 
                   target="_blank"
                   rel="noopener noreferrer"
                   @click.throttle.2000ms
                   class="flex-[2] bg-[#ff9100] hover:bg-[#e68200] text-[#2C1A0E] py-5 rounded-[24px] flex items-center justify-center gap-4 font-black text-lg transition-all shadow-[0_15px_30px_rgba(255,145,0,0.3)] active:scale-[0.98]">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    <?php echo e(__('messages.order_via_whatsapp')); ?>

                </a>
            </div>
        </section>
    </main>

    
    <section class="max-w-7xl mx-auto px-6 py-24 border-t border-[#ff9100]/10">
        <h2 class="font-['Playfair_Display'] text-3xl font-bold text-[#3d2b1f] mb-12"><?php echo e(__('messages.you_may_also_like')); ?></h2>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <a <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'related-'.e($item['slug']).''; ?>wire:key="related-<?php echo e($item['slug']); ?>"
                   href="<?php echo e(route('katalog.detail', ['slug' => $item['slug']])); ?>"
                   class="bg-[#FDFAF7] rounded-2xl overflow-hidden border border-black/5 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_24px_rgba(44,26,14,0.1)] group cursor-pointer flex flex-col">

                    <div class="aspect-[4/3] bg-[#E8E3D8] relative overflow-hidden shrink-0">
                        <img src="<?php echo e($item['img']); ?>"
                            alt="<?php echo e($item['name']); ?>"
                            loading="lazy"
                            decoding="async"
                            width="400"
                            height="300"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        
                        <div class="absolute inset-0 bg-[#2C1A0E]/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="bg-white text-[#A64E2F] font-bold px-4 py-2 rounded-lg text-xs translate-y-3 group-hover:translate-y-0 transition-transform duration-300 shadow-lg">
                                <?php echo e(__('messages.view_details')); ?>

                            </span>
                        </div>
                    </div>

                    <div class="p-4 flex-1 flex flex-col justify-start">
                        <p class="text-[10px] font-bold text-[#A64E2F] uppercase tracking-wider mb-1">
                            <?php echo e($item['cat']); ?>

                        </p>
                        <h3 class="text-[13px] font-bold text-[#2C1A0E] leading-snug line-clamp-2">
                            <?php echo e($item['name']); ?>

                        </h3>
                    </div>
                </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="col-span-full text-center py-12">
                    <p class="text-[#886852] text-sm"><?php echo e(__('messages.no_related_products')); ?></p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
        btn.classList.remove('border-[#ff9100]', 'scale-105');
        btn.classList.add('border-transparent', 'opacity-60');
    });
    
    if (clickedButton) {
        clickedButton.classList.remove('border-transparent', 'opacity-60');
        clickedButton.classList.add('border-[#ff9100]', 'scale-105');
    }
}
</script>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('app.env') === 'production'): ?>
<?php
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
?>
<script type="application/ld+json"><?php echo json_encode($schemaData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?></script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH E:\3 MAGANG\IBEKAMI\ibekami_bckend\resources\views/livewire/katalog/detail-katalog.blade.php ENDPATH**/ ?>