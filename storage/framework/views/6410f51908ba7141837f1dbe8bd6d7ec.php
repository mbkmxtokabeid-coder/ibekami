<div class="flex items-center gap-2">
    
    <div class="flex items-center gap-2 overflow-x-auto flex-1 pb-0.5"
         style="-webkit-overflow-scrolling: touch; scrollbar-width: none; -ms-overflow-style: none;">
        <style>.mobile-chips::-webkit-scrollbar { display: none; }</style>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $allTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <button
            wire:click="setCategory('<?php echo e($type['name']); ?>')"
            class="shrink-0 px-3 py-2 rounded-xl text-[12px] font-semibold border transition-all
                <?php echo e($activeCategory === $type['name']
                    ? 'bg-[#ff9100] text-white border-[#ff9100] shadow-md'
                    : 'bg-white text-[#7a5d48] border-[#e8d5c4]'); ?>">
            <?php echo e($type['name']); ?>

        </button>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>

    
    <button
        @click="$dispatch('open-filter-modal', {
            allTypes: <?php echo e(Js::from($allTypes)); ?>,
            allCategories: <?php echo e(Js::from($allCategories)); ?>,
            types: <?php echo e(Js::from($selectedTypes)); ?>,
            categories: <?php echo e(Js::from($selectedCategories)); ?>,
            wireId: $wire.$id
        })"
        class="relative shrink-0 flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl border transition-all
            <?php echo e(count($selectedTypes) > 0 || count($selectedCategories) > 0
                ? 'bg-[#ff9100] text-white border-[#ff9100] shadow-md'
                : 'bg-white text-[#ff9100] border-[#ff9100]/40'); ?>">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
        </svg>
        <span class="text-[10px] font-bold leading-none">Filter</span>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($selectedTypes) > 0 || count($selectedCategories) > 0): ?>
        <span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-[#3d2b1f] text-white text-[9px] font-black rounded-full flex items-center justify-center">
            <?php echo e(count($selectedTypes) + count($selectedCategories)); ?>

        </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </button>
</div>
<?php /**PATH E:\3 MAGANG\IBEKAMI\ibekami_bckend\resources\views/livewire/katalog/mobile-filter-bar.blade.php ENDPATH**/ ?>