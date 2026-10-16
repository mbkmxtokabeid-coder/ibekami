<section id="mitra" class="cv-auto py-12 md:py-16 px-4 bg-[#fff2e0] dark:bg-[#130D08] overflow-hidden transition-colors duration-200 scroll-mt-24 md:scroll-mt-28">
    <div class="max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-10">
            <div class="flex items-center justify-center gap-3 text-xs sm:text-[13px] font-bold text-[#b35200] dark:text-[#ff9100] uppercase tracking-[0.2em] mb-2 sm:mb-3">
                <span class="w-10 sm:w-12 h-[1px] bg-[#b35200] dark:bg-[#ff9100]"></span>
                {{ __('messages.trusted_together') }}
                <span class="w-10 sm:w-12 h-[1px] bg-[#b35200] dark:bg-[#ff9100]"></span>
            </div>
            <h2 class="font-['Poppins',sans-serif] text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#2C1A0E] dark:text-[#FDF5EC] tracking-tight leading-tight">{{ __('messages.our_partners') }}</h2>
            <p class="text-sm sm:text-base text-[#866b59] dark:text-[#9E8B7D] mt-2 sm:mt-3 font-medium leading-relaxed md:whitespace-nowrap">{{ __('messages.trusted_by_institutions') }}</p>
        </div>

        @php
            $bumnLoop = array_merge($partnersBumn, $partnersBumn, $partnersBumn);
            $orgLoop = array_merge($partnersOrganization, $partnersOrganization, $partnersOrganization);
        @endphp

        <!-- Marquee Track with Alpine.js -->
        <div class="relative mt-5 overflow-hidden">
            <!-- Gradient Overlay (Fade left & right matching background) -->
            <div class="absolute inset-y-0 left-0 w-16 md:w-24 bg-gradient-to-r from-[#fff2e0] dark:from-[#130D08] to-transparent z-10 pointer-events-none"></div>
            <div class="absolute inset-y-0 right-0 w-16 md:w-24 bg-gradient-to-l from-[#fff2e0] dark:from-[#130D08] to-transparent z-10 pointer-events-none"></div>
            
            <div class="flex flex-col gap-6">
                {{-- Baris 1: BUMN - Bergeser ke KIRI --}}
                @if(count($partnersBumn) > 0)
                <div class="space-y-2">
                    <div class="text-center">
                        <span class="inline-block px-4 py-1 bg-[#ff9100]/10 dark:bg-[#b35200]/25 text-[#ff9100] dark:text-[#b35200] text-xs font-bold uppercase tracking-wider rounded-full border border-[#ff9100]/20 dark:border-[#b35200]/40">
                            {{ __('messages.bumn_partners') }}
                        </span>
                    </div>
                    <div x-data="{
                        scrollPosition: 0,
                        isPaused: false,
                        isVisible: false,
                        totalWidth: 0,
                        animFrame: null,
                        lastTime: null,
                        
                        init() {
                            requestAnimationFrame(() => {
                                if (!this.$refs.track) return;
                                this.totalWidth = this.$refs.track.scrollWidth / 3;
                                this.scrollPosition = 0;
                                this.$refs.track.style.transform = `translateX(0px)`;
                                const observer = new IntersectionObserver(([entry]) => {
                                    this.isVisible = entry.isIntersecting;
                                    if (this.isVisible && !this.animFrame) {
                                        this.lastTime = null;
                                        this.startAutoScroll();
                                    }
                                }, { threshold: 0.05 });
                                observer.observe(this.$el);
                            });
                        },

                        updateWidth() {
                            if (this.$refs.track) {
                                this.totalWidth = this.$refs.track.scrollWidth / 3;
                            }
                        },
                        
                        startAutoScroll() {
                            const step = (timestamp) => {
                                if (!this.isVisible) {
                                    this.animFrame = null;
                                    return;
                                }
                                if (!this.lastTime) this.lastTime = timestamp;
                                const delta = Math.min(timestamp - this.lastTime, 100);
                                this.lastTime = timestamp;

                                if (!this.isPaused && this.totalWidth > 0) {
                                    this.scrollPosition += delta * 0.035;
                                    if (this.scrollPosition >= this.totalWidth) {
                                        this.scrollPosition = 0;
                                    }
                                    this.$refs.track.style.transform = `translateX(-${this.scrollPosition}px)`;
                                }
                                this.animFrame = requestAnimationFrame(step);
                            };
                            this.animFrame = requestAnimationFrame(step);
                        }
                    }"
                    @mouseenter="isPaused = true"
                    @mouseleave="isPaused = false"
                    @resize.window.debounce.150ms="updateWidth()"
                    class="relative overflow-hidden">
                        <div x-ref="track" class="flex gap-4 w-max shrink-0">
                            @foreach($bumnLoop as $index => $partner)
                            <div wire:key="bumn-{{ $partner['id'] }}-{{ $index }}" 
                                 class="w-36 h-20 shrink-0 bg-white dark:bg-[#231811] rounded-xl border border-[#b35200]/10 dark:border-[#b35200]/20 flex items-center justify-center p-4 hover:border-[#b35200] hover:shadow-lg hover:shadow-[#b35200]/10 transition-all group">
                                <img src="{{ $partner['image'] }}" 
                                     loading="lazy"
                                     decoding="async"
                                     width="144"
                                     height="80"
                                     alt="Partner BUMN"
                                     class="max-h-full max-w-full object-contain transition-all"
                                     onerror="this.onerror=null; this.style.opacity='0';">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- Baris 2: Organization - Bergeser ke KANAN --}}
                @if(count($partnersOrganization) > 0)
                <div class="space-y-2">
                    <div class="text-center">
                        <span class="inline-block px-4 py-1 bg-[#ff9100]/10 dark:bg-[#b35200]/25 text-[#ff9100] dark:text-[#b35200] text-xs font-bold uppercase tracking-wider rounded-full border border-[#ff9100]/20 dark:border-[#b35200]/40">
                            {{ __('messages.organization_partners') }}
                        </span>
                    </div>
                    <div x-data="{
                        scrollPosition: 0,
                        isPaused: false,
                        isVisible: false,
                        totalWidth: 0,
                        animFrame: null,
                        lastTime: null,
                        
                        init() {
                            requestAnimationFrame(() => {
                                if (!this.$refs.track) return;
                                this.totalWidth = this.$refs.track.scrollWidth / 3;
                                this.scrollPosition = this.totalWidth;
                                this.$refs.track.style.transform = `translateX(-${this.scrollPosition}px)`;
                                const observer = new IntersectionObserver(([entry]) => {
                                    this.isVisible = entry.isIntersecting;
                                    if (this.isVisible && !this.animFrame) {
                                        this.lastTime = null;
                                        this.startAutoScroll();
                                    }
                                }, { threshold: 0.05 });
                                observer.observe(this.$el);
                            });
                        },

                        updateWidth() {
                            if (this.$refs.track) {
                                this.totalWidth = this.$refs.track.scrollWidth / 3;
                            }
                        },
                        
                        startAutoScroll() {
                            const step = (timestamp) => {
                                if (!this.isVisible) {
                                    this.animFrame = null;
                                    return;
                                }
                                if (!this.lastTime) this.lastTime = timestamp;
                                const delta = Math.min(timestamp - this.lastTime, 100);
                                this.lastTime = timestamp;

                                if (!this.isPaused && this.totalWidth > 0) {
                                    this.scrollPosition -= delta * 0.035;
                                    if (this.scrollPosition <= 0) {
                                        this.scrollPosition = this.totalWidth;
                                    }
                                    this.$refs.track.style.transform = `translateX(-${this.scrollPosition}px)`;
                                }
                                this.animFrame = requestAnimationFrame(step);
                            };
                            this.animFrame = requestAnimationFrame(step);
                        }
                    }"
                    @mouseenter="isPaused = true"
                    @mouseleave="isPaused = false"
                    @resize.window.debounce.150ms="updateWidth()"
                    class="relative overflow-hidden">
                        <div x-ref="track" class="flex gap-4 w-max shrink-0">
                            @foreach($orgLoop as $index => $partner)
                            <div wire:key="org-{{ $partner['id'] }}-{{ $index }}" 
                                 class="w-36 h-20 shrink-0 bg-white dark:bg-[#231811] rounded-xl border border-[#b35200]/10 dark:border-[#b35200]/20 flex items-center justify-center p-4 hover:border-[#b35200] hover:shadow-lg hover:shadow-[#b35200]/10 transition-all group">
                                <img src="{{ $partner['image'] }}" 
                                     loading="lazy"
                                     decoding="async"
                                     width="144"
                                     height="80"
                                     alt="Partner Organization"
                                     class="max-h-full max-w-full object-contain transition-all"
                                     onerror="this.onerror=null; this.style.opacity='0';">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- Jika tidak ada partner sama sekali --}}
                @if(count($partnersBumn) === 0 && count($partnersOrganization) === 0)
                <div class="text-center py-12">
                    <p class="text-gray-400 text-sm">{{ __('messages.no_partner_data') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- CTA Banner -->
        <div x-ignore class="mt-16 bg-[#ff9100] dark:bg-[#b35200] rounded-[32px] p-8 md:p-12 flex flex-col md:flex-row justify-between items-center gap-8 shadow-2xl shadow-[#ff9100]/25 dark:shadow-[#b35200]/20 border border-white/10 transition-colors duration-200">
            <div class="text-center md:text-left text-white">
                <div class="text-[11px] font-bold opacity-95 uppercase tracking-[2px] mb-2">{{ __('messages.join_us') }}</div>
                <h3 class="text-2xl md:text-3xl font-extrabold mb-2 text-white tracking-tight">{{ __('messages.become_next_partner') }}</h3>
                <p class="text-sm text-white/95">{{ __('messages.collaboration_best_solution') }}</p>
            </div>
            <a href="https://wa.me/628170769999?text={{ rawurlencode(__('messages.wa_default_message')) }}" 
               target="_blank"
               rel="noopener noreferrer"
               @click.throttle.2000ms
               class="bg-white dark:bg-[#231811] text-[#2C1A0E] dark:text-[#FDF5EC] px-8 py-4 rounded-xl font-bold hover:bg-[#fff2e0] dark:hover:bg-[#2c1d15] transition-all whitespace-nowrap shadow-md">
                {{ __('messages.contact_us') }} →
            </a>
        </div>
    </div>

    <style>
        /* Prevent text selection during scroll */
        .shrink-0 {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
        }
    </style>
</section>
