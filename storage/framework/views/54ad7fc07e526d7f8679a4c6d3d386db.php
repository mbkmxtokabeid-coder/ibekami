

<?php $__env->startSection('title', 'Cetak Souvenir Custom Terdekat di Medan | Cepat & Terjangkau'); ?>
<?php $__env->startSection('meta_description', 'Katalog produk percetakan express & souvenir custom terdekat di Medan. Melayani pesanan satuan, grosiran, partai besar & kecil cepat & harga terjangkau.'); ?>
<?php $__env->startSection('og_image', asset('storage/banners/e840d922-8a26-4f10-98a3-80f53fb62364.webp')); ?>

<?php $__env->startSection('content'); ?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('app.env') === 'production'): ?>
<?php
    $katalogSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => config('app.url')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Katalog Produk', 'item' => route('katalog')],
        ],
    ];
?>
<script type="application/ld+json"><?php echo json_encode($katalogSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?></script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div class="min-h-screen bg-[#fff2e0] pt-24 lg:pt-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        
        <div class="hidden lg:block mb-8">
            <h1 class="font-['Playfair_Display'] text-3xl md:text-4xl font-bold text-[#2C1A0E] leading-tight">
                <?php echo e(__('messages.product_catalog')); ?>

            </h1>
            <p class="text-[13px] text-[#886852] mt-1">
                <?php echo e(__('messages.catalog_subtitle')); ?>

            </p>
        </div>

        
        <div class="flex flex-col lg:flex-row gap-8 items-start">

            
            <div class="hidden lg:block lg:w-auto">
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('katalog.sidebar-katalog', []);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1894423793-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
            </div>

            
            <div class="w-full order-1 lg:order-none min-w-0">

                
                <div class="lg:hidden mb-4">
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('katalog.mobile-filter-bar', []);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1894423793-1', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
                </div>

                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('katalog.katalog-section', []);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1894423793-2', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
            </div>

        </div>
    </div>
</div>


<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('footer', ['lazy' => true]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1894423793-3', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\3 MAGANG\IBEKAMI\ibekami_bckend\resources\views/katalog.blade.php ENDPATH**/ ?>