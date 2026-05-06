<aside
    x-data="{ sidebarOpen: true }"
    :class="sidebarOpen ? 'w-64' : 'w-16'"
    class="flex flex-col h-full bg-[#1a2535] transition-all duration-300 ease-in-out shrink-0 overflow-hidden">

    {{-- Brand --}}
    <div class="flex items-center justify-between h-14 px-4 border-b border-white/10 shrink-0">
        <div class="flex items-center gap-2 overflow-hidden">
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-orange-400 to-pink-500 shrink-0 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <span x-show="sidebarOpen" x-cloak class="text-white font-bold text-base tracking-wide">
                Catalog Panel
            </span>
        </div>
        <button @click="sidebarOpen = !sidebarOpen"
                class="text-gray-400 hover:text-white transition shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto py-4 space-y-1 px-2">

        {{-- Navigation label --}}
        <div x-show="sidebarOpen" x-cloak class="px-2 mb-1">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-500">Navigation</p>
        </div>

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                  {{ request()->routeIs('admin.dashboard')
                        ? 'bg-white/10 text-white border-l-2 border-cyan-400'
                        : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span x-show="sidebarOpen" x-cloak>Dashboard</span>
        </a>

        {{-- Frontend label --}}
        <div x-show="sidebarOpen" x-cloak class="px-2 mt-4 mb-1">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-500">Frontend</p>
        </div>
        <div x-show="!sidebarOpen" x-cloak class="border-t border-white/5 my-2"></div>

        {{-- Frontend Menu (collapsible) --}}
        <div x-data="{ menuOpen: {{ request()->routeIs('admin.frontend.*') ? 'true' : 'false' }} }">
            <button @click="menuOpen = !menuOpen"
                    class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                           {{ request()->routeIs('admin.frontend.*')
                                ? 'bg-white/10 text-white border-l-2 border-cyan-400'
                                : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span x-show="sidebarOpen" x-cloak>Frontend Menu</span>
                </div>
                <svg x-show="sidebarOpen" x-cloak
                     :class="menuOpen ? 'rotate-180' : ''"
                     class="w-4 h-4 transition-transform duration-200 shrink-0 text-gray-500"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="menuOpen && sidebarOpen" x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="mt-1 ml-4 space-y-0.5 border-l border-white/10 pl-3">
                @php
                    $frontendLinks = [
                        ['route' => 'admin.frontend.product-type',     'label' => 'Product Type'],
                        ['route' => 'admin.frontend.product-category', 'label' => 'Product Category'],
                        ['route' => 'admin.frontend.product-list',     'label' => 'Product List'],
                        ['route' => 'admin.frontend.machine-list',     'label' => 'Machine List'],
                    ];
                @endphp
                @foreach ($frontendLinks as $link)
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition
                              {{ request()->routeIs($link['route'])
                                    ? 'text-white bg-white/10'
                                    : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Backend label --}}
        <div x-show="sidebarOpen" x-cloak class="px-2 mt-4 mb-1">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-500">Backend</p>
        </div>
        <div x-show="!sidebarOpen" x-cloak class="border-t border-white/5 my-2"></div>

        {{-- Backend Menu (collapsible) --}}
        <div x-data="{ menuOpen: {{ request()->routeIs('admin.backend.*') ? 'true' : 'false' }} }">
            <button @click="menuOpen = !menuOpen"
                    class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                           {{ request()->routeIs('admin.backend.*')
                                ? 'bg-white/10 text-white border-l-2 border-cyan-400'
                                : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span x-show="sidebarOpen" x-cloak>Backend Menu</span>
                </div>
                <svg x-show="sidebarOpen" x-cloak
                     :class="menuOpen ? 'rotate-180' : ''"
                     class="w-4 h-4 transition-transform duration-200 shrink-0 text-gray-500"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="menuOpen && sidebarOpen" x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="mt-1 ml-4 space-y-0.5 border-l border-white/10 pl-3">
                @php
                    $backendLinks = [
                        ['route' => 'admin.backend.partner-list', 'label' => 'Partner List'],
                        ['route' => 'admin.backend.review-list',  'label' => 'Review List'],
                        ['route' => 'admin.backend.banner-list',  'label' => 'Banner List'],
                    ];
                @endphp
                @foreach ($backendLinks as $link)
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition
                              {{ request()->routeIs($link['route'])
                                    ? 'text-white bg-white/10'
                                    : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- User label --}}
        <div x-show="sidebarOpen" x-cloak class="px-2 mt-4 mb-1">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-500">User</p>
        </div>
        <div x-show="!sidebarOpen" x-cloak class="border-t border-white/5 my-2"></div>

        {{-- User List --}}
        <a href="{{ route('admin.user-list') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                  {{ request()->routeIs('admin.user-list')
                        ? 'bg-white/10 text-white border-l-2 border-cyan-400'
                        : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span x-show="sidebarOpen" x-cloak>User List</span>
        </a>

    </nav>
</aside>
