<section class="py-16 px-4 bg-[#fff2e0] overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="text-center max-w-lg mx-auto mb-10">
            <div class="flex items-center justify-center gap-3 text-[11px] font-bold text-[#ff9100] uppercase tracking-widest mb-2">
                <span class="w-10 h-[1px] bg-[#ff9100]"></span>
                {{ __('messages.trusted_together') }}
                <span class="w-10 h-[1px] bg-[#ff9100]"></span>
            </div>
            <h2 class="font-['Playfair_Display'] text-3xl font-bold text-[#3d2b1f]">{{ __('messages.our_partners') }}</h2>
            <p class="text-[13px] text-[#7a6452] mt-2">{{ __('messages.trusted_by_institutions') }}</p>
        </div>

        <!-- Marquee Track with Alpine.js -->
        <div class="relative mt-8 overflow-hidden">
            <!-- Gradient Overlay -->
            <div class="absolute inset-y-0 left-0 w-16 md:w-24 bg-gradient-to-r from-[#fff2e0] to-transparent z-10 pointer-events-none"></div>
            <div class="absolute inset-y-0 right-0 w-16 md:w-24 bg-gradient-to-l from-[#fff2e0] to-transparent z-10 pointer-events-none"></div>
            
            <div class="flex flex-col gap-6">
                {{-- Baris 1: BUMN - Bergeser ke KANAN --}}
                @if(count($partnersBumn) > 0)
                <div class="space-y-2">
                    <div class="text-center">
                        <span class="inline-block px-4 py-1 bg-[#ff9100]/10 text-[#ff9100] text-xs font-bold uppercase tracking-wider rounded-full border border-[#ff9100]/20">
                            {{ __('messages.bumn_partners') }}
                        </span>
                    </div>
                    <div x-data="{
                        scrollPosition: 0,
                        isPaused: false,
                        totalWidth: 0,
                        animFrame: null,
                        lastTime: null,
                        
                        init() {
                            requestAnimationFrame(() => {
                                this.totalWidth = this.$refs.track.scrollWidth / 2;
                                this.startAutoScroll();
                            });
                        },
                        
                        startAutoScroll() {
                            const step = (timestamp) => {
                                if (!this.lastTime) this.lastTime = timestamp;
                                const delta = timestamp - this.lastTime;
                                this.lastTime = timestamp;

                                if (!this.isPaused) {
                                    this.scrollPosition += delta * 0.03;
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
                    class="relative">
                        <div x-ref="track" class="flex gap-4">
                            @php
                                // Duplikasi 2x untuk infinite loop
                                $bumnLoop = array_merge($partnersBumn, $partnersBumn);
                            @endphp
                            
                            @foreach($bumnLoop as $index => $partner)
                            <div wire:key="bumn-{{ $partner['id'] }}-{{ $index }}" 
                                 class="w-36 h-20 shrink-0 bg-white rounded-xl border border-[#ff9100]/10 flex items-center justify-center p-4 hover:border-[#ff9100] hover:shadow-lg hover:shadow-[#ff9100]/10 transition-all group">
                                <img src="{{ $partner['image'] }}" 
                                     loading="lazy"
                                     decoding="async"
                                     width="144"
                                     height="80"
                                     alt="Partner BUMN"
                                     class="max-h-full max-w-full object-contain transition-all"
                                     onerror="this.src='https://via.placeholder.com/150x80?text=BUMN'">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- Baris 2: Organization - Bergeser ke KIRI --}}
                @if(count($partnersOrganization) > 0)
                <div class="space-y-2">
                    <div class="text-center">
                        <span class="inline-block px-4 py-1 bg-[#ff9100]/10 text-[#ff9100] text-xs font-bold uppercase tracking-wider rounded-full border border-[#ff9100]/20">
                            {{ __('messages.organization_partners') }}
                        </span>
                    </div>
                    <div x-data="{
                        scrollPosition: 0,
                        isPaused: false,
                        totalWidth: 0,
                        animFrame: null,
                        lastTime: null,
                        
                        init() {
                            requestAnimationFrame(() => {
                                this.totalWidth = this.$refs.track.scrollWidth / 2;
                                this.scrollPosition = this.totalWidth;
                                this.startAutoScroll();
                            });
                        },
                        
                        startAutoScroll() {
                            const step = (timestamp) => {
                                if (!this.lastTime) this.lastTime = timestamp;
                                const delta = timestamp - this.lastTime;
                                this.lastTime = timestamp;

                                if (!this.isPaused) {
                                    this.scrollPosition -= delta * 0.03;
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
                    class="relative">
                        <div x-ref="track" class="flex gap-4">
                            @php
                                // Duplikasi 2x untuk infinite loop
                                $orgLoop = array_merge($partnersOrganization, $partnersOrganization);
                            @endphp
                            
                            @foreach($orgLoop as $index => $partner)
                            <div wire:key="org-{{ $partner['id'] }}-{{ $index }}" 
                                 class="w-36 h-20 shrink-0 bg-white rounded-xl border border-[#ff9100]/10 flex items-center justify-center p-4 hover:border-[#ff9100] hover:shadow-lg hover:shadow-[#ff9100]/10 transition-all group">
                                <img src="{{ $partner['image'] }}" 
                                     loading="lazy"
                                     decoding="async"
                                     width="144"
                                     height="80"
                                     alt="Partner Organization"
                                     class="max-h-full max-w-full object-contain transition-all"
                                     onerror="this.src='https://via.placeholder.com/150x80?text=Organization'">
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
        <div class="mt-16 bg-[#ff9100] rounded-[32px] p-8 md:p-12 flex flex-col md:flex-row justify-between items-center gap-8 shadow-2xl shadow-[#ff9100]/20">
            <div class="text-center md:text-left text-white">
                <div class="text-[11px] font-bold opacity-90 uppercase tracking-[2px] mb-2">{{ __('messages.join_us') }}</div>
                <h3 class="font-['Playfair_Display'] text-2xl md:text-3xl font-bold mb-2 text-white">{{ __('messages.become_next_partner') }}</h3>
                <p class="text-sm text-white/80">{{ __('messages.collaboration_best_solution') }}</p>
            </div>
            <a href="https://wa.me/628170769999?text=Halo%20Admin%2C%20saya%20tertarik%20dengan%20produk%20dari%20Ibekami.id.%20Bisa%20bantu%20untuk%20info%20lebih%20lanjut%3F" 
               target="_blank"
               @click.throttle.2000ms
               class="bg-white text-[#ff9100] px-8 py-4 rounded-xl font-bold hover:bg-[#fff2e0] transition-all whitespace-nowrap shadow-md">
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
