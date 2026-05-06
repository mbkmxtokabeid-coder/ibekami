<section class="py-16 px-4 bg-[#fdfaf7] overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10 text-center md:text-left">
            <div class="flex items-center justify-center md:justify-start gap-2 text-[11px] font-bold text-[#ff9100] uppercase tracking-widest mb-2">
                {{ __('messages.customer_reviews') }}
                <span class="w-10 h-[1px] bg-[#ff9100]"></span>
            </div>
            <h2 class="font-['Playfair_Display'] text-3xl font-bold text-[#2C1A0E]">
                {{ __('messages.what_they_say') }}
            </h2>
        </div>

        <!-- Auto-scroll Carousel with Alpine.js -->
        <div class="relative w-full overflow-hidden"
             x-data="{
                scrollPosition: 0,
                isPaused: false,
                cardWidth: 0,
                totalWidth: 0,
                
                init() {
                    this.cardWidth = this.$refs.track.children[0].offsetWidth + 24; // width + gap
                    this.totalWidth = this.$refs.track.scrollWidth / 3; // dibagi 3 karena duplikat 3x
                    this.startAutoScroll();
                },
                
                startAutoScroll() {
                    setInterval(() => {
                        if (!this.isPaused) {
                            this.scrollPosition -= 1;
                            
                            // Reset position untuk infinite loop
                            if (Math.abs(this.scrollPosition) >= this.totalWidth) {
                                this.scrollPosition = 0;
                            }
                            
                            this.$refs.track.style.transform = `translateX(${this.scrollPosition}px)`;
                        }
                    }, 30); // Update setiap 30ms untuk smooth animation
                }
             }"
             @mouseenter="isPaused = true"
             @mouseleave="isPaused = false">
            
            <!-- Slider Track -->
            <div x-ref="track" class="flex gap-6 transition-transform duration-100 ease-linear">
                @php
                    // Duplikasi 3x untuk infinite loop
                    $loop = array_merge($reviews, $reviews, $reviews);
                @endphp

                @foreach($loop as $index => $review)
                <div wire:key="review-{{ $review['id'] }}-{{ $index }}" 
                     class="w-[280px] md:w-[350px] flex-shrink-0 bg-white p-6 rounded-2xl border border-[#ff9100]/10 flex flex-col justify-between shadow-sm hover:shadow-lg hover:border-[#ff9100]/30 transition-all duration-300">
                    
                    <div>
                        <!-- Rating Stars -->
                        <div class="flex gap-1 mb-4">
                            @for($i = 0; $i < ($review['rating'] ?? 5); $i++)
                                <div class="w-3 h-3 bg-[#ff9100]"
                                     style="clip-path:polygon(50% 0%,61% 35%,98% 35%,68% 57%,79% 91%,50% 70%,21% 91%,32% 57%,2% 35%,39% 35%)">
                                </div>
                            @endfor
                        </div>

                        <!-- Review Text -->
                        <p class="text-[12px] md:text-[13px] italic text-[#5C3D28] leading-relaxed mb-6">
                            "{{ $review['text'] }}"
                        </p>
                    </div>

                    <!-- User Info -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#ff9100] text-white font-bold rounded-full flex items-center justify-center text-xs shadow-md">
                            {{ $review['initials'] }}
                        </div>
                        <div>
                            <div class="text-[12px] font-bold text-[#2C1A0E]">
                                {{ $review['name'] }}
                            </div>
                            <div class="text-[11px] text-[#8A6A54]">
                                {{ $review['date'] }}
                            </div>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>

            <!-- Gradient Overlays -->
            <div class="absolute left-0 top-0 bottom-0 w-20 bg-gradient-to-r from-[#fdfaf7] to-transparent pointer-events-none z-10"></div>
            <div class="absolute right-0 top-0 bottom-0 w-20 bg-gradient-to-l from-[#fdfaf7] to-transparent pointer-events-none z-10"></div>
        </div>

        <!-- Indicator -->
        <div class="flex justify-center gap-2 mt-8">
            <div class="w-2 h-2 rounded-full bg-[#ff9100] animate-pulse"></div>
            <div class="w-2 h-2 rounded-full bg-[#ff9100]/30"></div>
            <div class="w-2 h-2 rounded-full bg-[#ff9100]/30"></div>
        </div>
    </div>
</section>

<style>
/* Prevent text selection during scroll */
.flex-shrink-0 {
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
}
</style>
