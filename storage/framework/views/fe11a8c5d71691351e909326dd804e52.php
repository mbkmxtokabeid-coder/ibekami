<div class="flex-1 min-w-0 w-full"
    x-data="{
        currentLimit: window.innerWidth < 768 ? 8 : 9,
        resizeDebounceTimer: null,
        
        init() {
            $wire.setPerPage(this.currentLimit);
        },
        
        handleResize() {
            // Debounce 200ms: hanya kalkulasi ulang setelah user berhenti resize
            clearTimeout(this.resizeDebounceTimer);
            this.resizeDebounceTimer = setTimeout(() => {
                let newLimit = window.innerWidth < 768 ? 8 : 9;
                if (this.currentLimit !== newLimit) {
                    this.currentLimit = newLimit;
                    $wire.setPerPage(newLimit);
                }
            }, 200);
        }
    }"
    @resize.window="handleResize()">

    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <p class="text-[14px] text-[#5C3D28]">
            <?php echo e(__('messages.showing')); ?> <strong class="text-[#2C1A0E]"><?php echo e($this->paginatedData['total']); ?> <?php echo e(__('messages.products')); ?></strong>
        </p>

        
        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-[#A64E2F]/30 text-[#A64E2F] text-[12px] font-semibold rounded-full shadow-sm">
                <?php echo e($activeCategory); ?>

                <button wire:click="resetFilters" class="hover:text-[#8C4126] focus:outline-none">&times;</button>
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-[#A64E2F]/30 text-[#A64E2F] text-[12px] font-semibold rounded-full shadow-sm">
                <?php echo e($sortBy); ?>

                <button wire:click="resetFilters" class="hover:text-[#8C4126] focus:outline-none">&times;</button>
            </span>
            <button wire:click="resetFilters" class="text-[12px] font-semibold text-[#886852] hover:text-[#A64E2F] transition-colors px-1 outline-none">
                <?php echo e(__('messages.reset_filters')); ?>

            </button>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->paginatedData['total'] > 0): ?>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6 relative">
            
            
            <div wire:loading wire:target="nextPage, previousPage, setPage, setPerPage, resetFilters" class="absolute inset-0 bg-[#D6CFBF]/60 backdrop-blur-sm z-20 rounded-2xl flex items-center justify-center">
                <span class="w-8 h-8 rounded-full border-4 border-[#A64E2F]/30 border-t-[#A64E2F] animate-spin"></span>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->paginatedData['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <a href="<?php echo e(route('katalog.detail', ['slug' => $product['slug']])); ?>"
                   <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'product-'.e($product['id']).''; ?>wire:key="product-<?php echo e($product['id']); ?>"
                   class="bg-[#FDFAF7] rounded-2xl overflow-hidden border border-black/5 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_24px_rgba(44,26,14,0.1)] group cursor-pointer flex flex-col">

                    <div class="aspect-[4/3] bg-[#E8E3D8] relative overflow-hidden shrink-0">
                        <img src="<?php echo e($product['img']); ?>"
                             alt="<?php echo e($product['name']); ?>"
                             <?php if($loop->index < 4): ?>
                                 loading="eager"
                                 fetchpriority="high"
                                 decoding="sync"
                             <?php else: ?>
                                 loading="lazy"
                                 decoding="async"
                             <?php endif; ?>
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
                            <?php echo e($product['cat']); ?>

                        </p>
                        <h3 class="text-[13px] font-bold text-[#2C1A0E] leading-snug line-clamp-2">
                            <?php echo e($product['name']); ?>

                        </h3>
                    </div>
                </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->paginatedData['totalPages'] > 1): ?>
            <div class="mt-12 flex flex-wrap justify-center items-center gap-2">
                
                <button wire:click="previousPage" <?php if($this->paginatedData['currentPage'] <= 1): ?> disabled <?php endif; ?>
                    class="w-10 h-10 flex items-center justify-center rounded-xl border border-[#A64E2F]/40 text-[#A64E2F] disabled:opacity-30 disabled:cursor-not-allowed hover:bg-[#F5EDE8] transition-colors outline-none font-bold">
                    &larr;
                </button>

                <div class="hidden sm:flex items-center gap-1">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = 1; $i <= $this->paginatedData['totalPages']; $i++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i == 1 || $i == $this->paginatedData['totalPages'] || abs($i - $this->paginatedData['currentPage']) <= 1): ?>
                            <button wire:click="setPage(<?php echo e($i); ?>)"
                                    class="w-10 h-10 flex items-center justify-center rounded-xl font-bold text-[13px] transition-colors outline-none
                                    <?php echo e($this->paginatedData['currentPage'] == $i ? 'bg-[#A64E2F] text-white shadow-md shadow-[#A64E2F]/20' : 'border border-[#A64E2F]/20 text-[#A64E2F] hover:bg-[#F5EDE8]'); ?>">
                                <?php echo e($i); ?>

                            </button>
                        <?php elseif(abs($i - $this->paginatedData['currentPage']) == 2): ?>
                            <span class="w-6 text-center text-[#886852] font-bold">...</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>

                <div class="sm:hidden text-[#5C3D28] text-[12px] font-bold px-3">
                    <?php echo e(__('messages.page')); ?> <?php echo e($this->paginatedData['currentPage']); ?> / <?php echo e($this->paginatedData['totalPages']); ?>

                </div>

                <button wire:click="nextPage" <?php if($this->paginatedData['currentPage'] >= $this->paginatedData['totalPages']): ?> disabled <?php endif; ?>
                    class="w-10 h-10 flex items-center justify-center rounded-xl border border-[#A64E2F]/40 text-[#A64E2F] disabled:opacity-30 disabled:cursor-not-allowed hover:bg-[#F5EDE8] transition-colors outline-none font-bold">
                    &rarr;
                </button>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php else: ?>
        
        <div class="flex flex-col items-center justify-center py-24 text-center bg-[#FDFAF7] rounded-3xl border border-dashed border-[#A64E2F]/20">
            <svg class="w-12 h-12 text-[#C4B9A8] mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-[#886852] font-semibold text-[14px]"><?php echo e(__('messages.no_products_found')); ?></p>
            <button wire:click="resetFilters" class="mt-3 text-[#A64E2F] text-[13px] font-bold hover:underline outline-none">
                <?php echo e(__('messages.reset_all_filters')); ?>

            </button>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


</div>
<?php /**PATH E:\3 MAGANG\IBEKAMI\ibekami_bckend\resources\views/livewire/katalog/katalog-section.blade.php ENDPATH**/ ?>