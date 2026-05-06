<section class="bg-[#fdfaf7] min-h-screen pb-20 px-4 font-sans text-slate-800">
    <div class="max-w-7xl mx-auto">
        
        <!-- HEADER SECTION -->
        <div class="pt-16 pb-8 text-center md:text-left">
            <h2 class="text-4xl font-extrabold tracking-tight text-slate-900 mb-2">
                {{ __('messages.workshop_capabilities') }} <span class="text-[#ff9100]">{{ __('messages.capabilities') }}</span>
            </h2>
            <p class="text-slate-500 max-w-2xl text-lg">
                {{ __('messages.supported_by_latest_tech') }}
            </p>
        </div>

        <!-- GRID SYSTEM -->
        <div class="mt-12">
            @if(count($machines) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($machines as $machine)
                        <div wire:key="machine-{{ $machine['id'] }}" class="group bg-[#fdfaf7] rounded-[2.5rem] p-4 shadow-sm border border-slate-100 transition-all hover:shadow-xl">
                            <div class="aspect-square overflow-hidden rounded-[2rem] bg-slate-100 mb-6">
                                <img src="{{ $machine['image'] }}" 
                                     alt="{{ $machine['title'] }}"
                                     loading="lazy"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                     onerror="this.src='https://via.placeholder.com/400x400?text={{ urlencode($machine['title']) }}'">
                            </div>
                            <div class="px-2 pb-4">
                                <span class="text-[#8B5E3C] font-bold text-xs uppercase tracking-widest">{{ __('messages.production_machine') }}</span>
                                <h4 class="text-xl font-extrabold text-[#2D241E] mt-1 leading-tight">{{ $machine['title'] }}</h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-24">
                    <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-slate-400 font-semibold text-lg">{{ __('messages.no_machines_added') }}</p>
                </div>
            @endif
        </div>

        <!-- CONTACT STRIP -->
        <div class="mt-24 bg-white border border-slate-200 rounded-[2.5rem] p-4 flex flex-col md:flex-row items-center justify-between shadow-2xl shadow-slate-200/50">
            <div class="flex items-center gap-6 p-4">
                <div class="h-16 w-16 rounded-2xl bg-[#ff9100] flex items-center justify-center text-white shadow-lg shadow-[#ff9100]/30">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </div>
                <div>
                    <h5 class="text-xl font-bold text-slate-900 uppercase">{{ __('messages.start_your_project') }}</h5>
                    <p class="text-slate-400 text-sm italic">{{ __('messages.lets_build_together') }}</p>
                </div>
            </div>
            <a href="https://wa.me/628170769999?text=Halo%20Admin%2C%20saya%20tertarik%20dengan%20produk%20dari%20Ibekami.id.%20Bisa%20bantu%20untuk%20info%20lebih%20lanjut%3F" 
               target="_blank"
               @click.throttle.2000ms
               class="w-full md:w-auto bg-slate-900 text-white px-10 py-5 rounded-[1.8rem] font-bold hover:bg-[#ff9100] transition-all duration-500 text-center uppercase tracking-widest text-xs">
                {{ __('messages.contact_via_whatsapp') }}
            </a>
        </div>

    </div>
</section>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
    
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
</style>
