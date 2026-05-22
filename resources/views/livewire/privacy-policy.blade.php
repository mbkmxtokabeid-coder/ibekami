<div x-data="{ 
    activeSection: 'section-0',
    scrollToSection(id) {
        const element = document.getElementById(id);
        if (element) {
            window.scrollTo({
                top: element.offsetTop - 100,
                behavior: 'smooth'
            });
            this.activeSection = id;
        }
    }
}" 
@scroll.window.throttle.150ms="
    const sections = ['section-1', 'section-2', 'section-3', 'section-4', 'section-5', 'section-6'];
    const scrollPos = window.pageYOffset || document.documentElement.scrollTop;
    
    sections.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            if (scrollPos >= el.offsetTop - 150) {
                activeSection = id;
            }
        }
    });
"
class="bg-[#F4F1EA] text-[#222222] font-sans antialiased min-h-screen py-12 lg:py-20">

    <div class="container mx-auto px-4 max-w-7xl">
        
        <!-- Header Section -->
        <header class="text-center mb-12">
            
            <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight mb-4">
                {{ __('messages.privacy_policy_title') }}
            </h1>
            <p class="text-gray-500 text-sm italic">
                {{ __('messages.last_updated') }}: {{ date('d F Y') }}
            </p>
        </header>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Nav (Desktop) -->
            <aside class="hidden lg:block w-1/4">
                <div class="sticky top-24 bg-white border border-[#e0ddd5] rounded-lg p-6 shadow-sm">
                    <h5 class="text-xs font-black uppercase tracking-widest text-[#A65D3B] mb-5">
                        {{ __('messages.table_of_contents') }}
                    </h5>
                    <ul class="space-y-1">
                        <li><a @click.prevent="scrollToSection('section-1')" :class="activeSection === 'section-1' ? 'text-[#A65D3B] font-bold pl-2' : 'text-gray-700 hover:text-[#A65D3B] hover:pl-2'" class="block py-2 text-sm transition-all cursor-pointer border-b border-gray-50">1. {{ __('messages.section_1_title') }}</a></li>
                        <li><a @click.prevent="scrollToSection('section-2')" :class="activeSection === 'section-2' ? 'text-[#A65D3B] font-bold pl-2' : 'text-gray-700 hover:text-[#A65D3B] hover:pl-2'" class="block py-2 text-sm transition-all cursor-pointer border-b border-gray-50">2. {{ __('messages.section_2_title') }}</a></li>
                        <li><a @click.prevent="scrollToSection('section-3')" :class="activeSection === 'section-3' ? 'text-[#A65D3B] font-bold pl-2' : 'text-gray-700 hover:text-[#A65D3B] hover:pl-2'" class="block py-2 text-sm transition-all cursor-pointer border-b border-gray-50">3. {{ __('messages.section_3_title') }}</a></li>
                        <li><a @click.prevent="scrollToSection('section-4')" :class="activeSection === 'section-4' ? 'text-[#A65D3B] font-bold pl-2' : 'text-gray-700 hover:text-[#A65D3B] hover:pl-2'" class="block py-2 text-sm transition-all cursor-pointer border-b border-gray-50">4. {{ __('messages.section_4_title') }}</a></li>
                        <li><a @click.prevent="scrollToSection('section-5')" :class="activeSection === 'section-5' ? 'text-[#A65D3B] font-bold pl-2' : 'text-gray-700 hover:text-[#A65D3B] hover:pl-2'" class="block py-2 text-sm transition-all cursor-pointer border-b border-gray-50">5. {{ __('messages.section_5_title') }}</a></li>
                        <li><a @click.prevent="scrollToSection('section-6')" :class="activeSection === 'section-6' ? 'text-[#A65D3B] font-bold pl-2' : 'text-gray-700 hover:text-[#A65D3B] hover:pl-2'" class="block py-2 text-sm transition-all cursor-pointer">6. {{ __('messages.section_6_title') }}</a></li>
                    </ul>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="w-full lg:w-3/4">
                <div class="bg-white rounded-lg shadow-sm p-8 lg:p-12 border border-white">
                    
                    <!-- Section Intro -->
                    <div id="section-0" class="mb-12">
                        <p class="text-lg font-medium text-gray-700 leading-relaxed">
                            {{ __('messages.privacy_intro') }}
                        </p>
                    </div>

                    <div class="space-y-12">
                        <!-- Section 1 -->
                        <section id="section-1" class="scroll-mt-28">
                            <h3 class="text-xl font-bold uppercase tracking-wide border-l-8 border-[#A65D3B] pl-5 mb-6">
                                1. {{ __('messages.section_1_title') }}
                            </h3>
                            <p class="text-gray-600 leading-relaxed mb-4">{{ __('messages.section_1_intro') }}</p>
                            <ul class="space-y-3 text-gray-600">
                                <li class="flex items-start"><span class="text-[#A65D3B] font-bold mr-2">→</span> <span><strong>{{ __('messages.section_1_item_1') }}</strong> {{ __('messages.section_1_item_1_desc') }}</span></li>
                                <li class="flex items-start"><span class="text-[#A65D3B] font-bold mr-2">→</span> <span><strong>{{ __('messages.section_1_item_2') }}</strong> {{ __('messages.section_1_item_2_desc') }}</span></li>
                                <li class="flex items-start"><span class="text-[#A65D3B] font-bold mr-2">→</span> <span><strong>{{ __('messages.section_1_item_3') }}</strong> {{ __('messages.section_1_item_3_desc') }}</span></li>
                            </ul>
                        </section>

                        <!-- Section 2 -->
                        <section id="section-2" class="scroll-mt-28">
                            <h3 class="text-xl font-bold uppercase tracking-wide border-l-8 border-[#A65D3B] pl-5 mb-6">
                                2. {{ __('messages.section_2_title') }}
                            </h3>
                            <p class="text-gray-600 leading-relaxed mb-4">{{ __('messages.section_2_intro') }}</p>
                            <ul class="space-y-3 text-gray-600">
                                <li class="flex items-start"><span class="text-[#A65D3B] font-bold mr-2">→</span> {{ __('messages.section_2_item_1') }}</li>
                                <li class="flex items-start"><span class="text-[#A65D3B] font-bold mr-2">→</span> {{ __('messages.section_2_item_2') }}</li>
                                <li class="flex items-start"><span class="text-[#A65D3B] font-bold mr-2">→</span> {{ __('messages.section_2_item_3') }}</li>
                                <li class="flex items-start"><span class="text-[#A65D3B] font-bold mr-2">→</span> {{ __('messages.section_2_item_4') }}</li>
                            </ul>
                        </section>

                        <!-- Section 3 -->
                        <section id="section-3" class="scroll-mt-28">
                            <h3 class="text-xl font-bold uppercase tracking-wide border-l-8 border-[#A65D3B] pl-5 mb-6">
                                3. {{ __('messages.section_3_title') }}
                            </h3>
                            <p class="text-gray-600 leading-relaxed">{{ __('messages.section_3_content') }}</p>
                        </section>

                        <!-- Section 4 -->
                        <section id="section-4" class="scroll-mt-28">
                            <h3 class="text-xl font-bold uppercase tracking-wide border-l-8 border-[#A65D3B] pl-5 mb-6">
                                4. {{ __('messages.section_4_title') }}
                            </h3>
                            <p class="text-gray-600 leading-relaxed">{{ __('messages.section_4_content') }}</p>
                        </section>

                        <!-- Section 5 -->
                        <section id="section-5" class="scroll-mt-28">
                            <h3 class="text-xl font-bold uppercase tracking-wide border-l-8 border-[#A65D3B] pl-5 mb-6">
                                5. {{ __('messages.section_5_title') }}
                            </h3>
                            <p class="text-gray-600 leading-relaxed mb-4">{{ __('messages.section_5_intro') }}</p>
                            <ul class="space-y-3 text-gray-600">
                                <li class="flex items-start"><span class="text-[#A65D3B] font-bold mr-2">→</span> {{ __('messages.section_5_item_1') }}</li>
                                <li class="flex items-start"><span class="text-[#A65D3B] font-bold mr-2">→</span> {{ __('messages.section_5_item_2') }}</li>
                                <li class="flex items-start"><span class="text-[#A65D3B] font-bold mr-2">→</span> {{ __('messages.section_5_item_3') }}</li>
                            </ul>
                        </section>

                        <!-- Section 6: Contact -->
                        <section id="section-6" class="scroll-mt-28">
                            <h3 class="text-xl font-bold uppercase tracking-wide border-l-8 border-[#A65D3B] pl-5 mb-6">
                                6. {{ __('messages.section_6_title') }}
                            </h3>
                            <p class="text-gray-600 leading-relaxed mb-8">{{ __('messages.section_6_intro') }}</p>
                            
                            <div class="bg-[#fdfaf5] border border-[#e0ddd5] rounded-lg p-8 shadow-inner">
                                <ul class="space-y-4">
                                    <li class="flex flex-col sm:flex-row items-baseline">
                                        <strong class="w-32 text-gray-800 shrink-0">{{ __('messages.email') }}:</strong>
                                        <a href="mailto:ikhtiarberkah1010@gmail.com" class="text-[#A65D3B] font-bold hover:underline">ikhtiarberkah1010@gmail.com</a>
                                    </li>
                                    <li class="flex flex-col sm:flex-row items-baseline">
                                        <strong class="w-32 text-gray-800 shrink-0">{{ __('messages.instagram') }}:</strong>
                                        <a href="https://www.instagram.com/ibekami.id/" target="_blank" class="text-[#A65D3B] font-bold hover:underline">@ibekami.id</a>
                                    </li>
                                    <li class="flex flex-col sm:flex-row items-baseline">
                                        <strong class="w-32 text-gray-800 shrink-0">{{ __('messages.location') }}:</strong>
                                        <span class="text-gray-600">{{ __('messages.medan_indonesia') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </section>

                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
