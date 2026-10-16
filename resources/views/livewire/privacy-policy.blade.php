
<div
    x-data="{
        activeSection: 'section-0',

        scrollToSection(id) {
            const element = document.getElementById(id);

            if (element) {
                window.scrollTo({
                    top: element.getBoundingClientRect().top
                        + window.scrollY - 100,
                    behavior: 'smooth'
                });

                this.activeSection = id;
            }
        }
    }"
    @scroll.window.throttle.150ms="
        const sections = [
            'section-1',
            'section-2',
            'section-3',
            'section-4',
            'section-5',
            'section-6'
        ];

        const scrollPos =
            window.pageYOffset ||
            document.documentElement.scrollTop;

        sections.forEach(id => {
            const el = document.getElementById(id);

            if (
                el &&
                scrollPos >=
                el.getBoundingClientRect().top +
                window.scrollY - 150
            ) {
                activeSection = id;
            }
        });
    "
    class="min-h-screen overflow-x-hidden bg-[#F4F1EA]
           pt-24 pb-10 font-sans text-[#222222]
           antialiased transition-colors duration-200
           dark:bg-[#130D08] dark:text-[#D8C6B6]
           sm:pt-28 sm:pb-12 lg:pt-28 lg:pb-20"
>
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <header class="mb-6 w-full min-w-0 text-center sm:mb-8">
            <h1
                class="mx-auto mb-3 max-w-full break-words
                       text-3xl font-extrabold leading-tight
                       tracking-tight text-[#2C1A0E]
                       dark:text-[#FDF5EC]
                       sm:mb-4 sm:text-4xl lg:text-5xl"
            >
                {{ __('messages.privacy_policy_title') }}
            </h1>

            <p
                class="text-sm italic leading-relaxed
                       text-gray-500 dark:text-[#9E8B7D]
                       sm:text-base"
            >
                {{ __('messages.last_updated') }}:
                {{ \Carbon\Carbon::now()->locale(app()->getLocale())->translatedFormat('d F Y') }}
            </p>
        </header>

        <div class="flex min-w-0 flex-col gap-6 lg:flex-row lg:gap-8">

            <!-- Daftar Isi Desktop -->
            <aside class="hidden w-1/4 lg:block">
                <div
                    class="sticky top-24 rounded-2xl border
                           border-[#e0ddd5] bg-white p-6 shadow-sm
                           dark:border-white/10 dark:bg-[#231811]"
                >
                    <h5
                        class="mb-5 text-xs font-black uppercase
                               tracking-widest text-[#A65D3B]
                               dark:text-[#b35200]"
                    >
                        {{ __('messages.table_of_contents') }}
                    </h5>

                    <ul class="space-y-1">
                        @foreach (range(1, 6) as $number)
                            <li>
                                <a
                                    href="#section-{{ $number }}"
                                    @click.prevent="scrollToSection('section-{{ $number }}')"
                                    :class="activeSection === 'section-{{ $number }}'
                                        ? 'text-[#A65D3B] dark:text-[#b35200] font-bold pl-2'
                                        : 'text-gray-700 dark:text-[#D8C6B6] hover:text-[#A65D3B] dark:hover:text-[#b35200] hover:pl-2'"
                                    class="block cursor-pointer
                                           border-b border-gray-100
                                           py-2 text-sm transition-all
                                           dark:border-white/5"
                                >
                                    {{ $number }}.
                                    {{ __('messages.section_' . $number . '_title') }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>

            <!-- Konten Utama -->
            <main class="w-full min-w-0 lg:w-3/4">
                <div
                    class="min-w-0 rounded-2xl border
                           border-[#e0ddd5] bg-white p-5 shadow-sm
                           dark:border-white/10 dark:bg-[#1A120B]
                           sm:p-8 lg:p-12"
                >

                    <!-- Pendahuluan -->
                    <div
                        id="section-0"
                        class="mb-8 min-w-0 scroll-mt-28 sm:mb-10"
                    >
                        <p
                            class="text-base font-medium leading-relaxed
                                   text-gray-700 dark:text-[#E8DDD3]
                                   sm:text-lg"
                        >
                            {{ __('messages.privacy_intro') }}
                        </p>
                    </div>

                    <div class="min-w-0 space-y-10 sm:space-y-12">

                        <!-- Bagian 1 -->
                        <section
                            id="section-1"
                            class="min-w-0 scroll-mt-28"
                        >
                            <h3
                                class="mb-5 border-l-4 border-[#A65D3B]
                                       pl-3 text-lg font-bold uppercase
                                       leading-snug tracking-wide
                                       text-[#2C1A0E]
                                       dark:border-[#b35200]
                                       dark:text-[#FDF5EC]
                                       sm:mb-6 sm:border-l-8
                                       sm:pl-5 sm:text-xl"
                            >
                                1. {{ __('messages.section_1_title') }}
                            </h3>

                            <p
                                class="mb-4 leading-relaxed text-gray-600
                                       dark:text-[#D8C6B6]"
                            >
                                {{ __('messages.section_1_intro') }}
                            </p>

                            <ul class="space-y-3 text-gray-600 dark:text-[#D8C6B6]">
                                @foreach ([1, 2, 3] as $item)
                                    <li class="flex min-w-0 items-start gap-2">
                                        <span
                                            class="shrink-0 font-bold
                                                   text-[#A65D3B]
                                                   dark:text-[#b35200]"
                                        >→</span>

                                        <span class="min-w-0 leading-relaxed">
                                            <strong class="text-gray-900 dark:text-[#FDF5EC]">
                                                {{ __('messages.section_1_item_' . $item) }}
                                            </strong>
                                            {{ __('messages.section_1_item_' . $item . '_desc') }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </section>

                        <!-- Bagian 2 -->
                        <section
                            id="section-2"
                            class="min-w-0 scroll-mt-28"
                        >
                            <h3
                                class="mb-5 border-l-4 border-[#A65D3B]
                                       pl-3 text-lg font-bold uppercase
                                       leading-snug tracking-wide
                                       text-[#2C1A0E]
                                       dark:border-[#b35200]
                                       dark:text-[#FDF5EC]
                                       sm:mb-6 sm:border-l-8
                                       sm:pl-5 sm:text-xl"
                            >
                                2. {{ __('messages.section_2_title') }}
                            </h3>

                            <p class="mb-4 leading-relaxed text-gray-600 dark:text-[#D8C6B6]">
                                {{ __('messages.section_2_intro') }}
                            </p>

                            <ul class="space-y-3 text-gray-600 dark:text-[#D8C6B6]">
                                @foreach ([1, 2, 3, 4] as $item)
                                    <li class="flex min-w-0 items-start gap-2">
                                        <span class="shrink-0 font-bold text-[#A65D3B] dark:text-[#b35200]">→</span>
                                        <span class="min-w-0 leading-relaxed">
                                            {{ __('messages.section_2_item_' . $item) }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </section>

                        <!-- Bagian 3 -->
                        <section
                            id="section-3"
                            class="min-w-0 scroll-mt-28"
                        >
                            <h3
                                class="mb-5 border-l-4 border-[#A65D3B]
                                       pl-3 text-lg font-bold uppercase
                                       leading-snug tracking-wide
                                       text-[#2C1A0E]
                                       dark:border-[#b35200]
                                       dark:text-[#FDF5EC]
                                       sm:mb-6 sm:border-l-8
                                       sm:pl-5 sm:text-xl"
                            >
                                3. {{ __('messages.section_3_title') }}
                            </h3>

                            <p class="leading-relaxed text-gray-600 dark:text-[#D8C6B6]">
                                {{ __('messages.section_3_content') }}
                            </p>
                        </section>

                        <!-- Bagian 4 -->
                        <section
                            id="section-4"
                            class="min-w-0 scroll-mt-28"
                        >
                            <h3
                                class="mb-5 border-l-4 border-[#A65D3B]
                                       pl-3 text-lg font-bold uppercase
                                       leading-snug tracking-wide
                                       text-[#2C1A0E]
                                       dark:border-[#b35200]
                                       dark:text-[#FDF5EC]
                                       sm:mb-6 sm:border-l-8
                                       sm:pl-5 sm:text-xl"
                            >
                                4. {{ __('messages.section_4_title') }}
                            </h3>

                            <p class="leading-relaxed text-gray-600 dark:text-[#D8C6B6]">
                                {{ __('messages.section_4_content') }}
                            </p>
                        </section>

                        <!-- Bagian 5 -->
                        <section
                            id="section-5"
                            class="min-w-0 scroll-mt-28"
                        >
                            <h3
                                class="mb-5 border-l-4 border-[#A65D3B]
                                       pl-3 text-lg font-bold uppercase
                                       leading-snug tracking-wide
                                       text-[#2C1A0E]
                                       dark:border-[#b35200]
                                       dark:text-[#FDF5EC]
                                       sm:mb-6 sm:border-l-8
                                       sm:pl-5 sm:text-xl"
                            >
                                5. {{ __('messages.section_5_title') }}
                            </h3>

                            <p class="mb-4 leading-relaxed text-gray-600 dark:text-[#D8C6B6]">
                                {{ __('messages.section_5_intro') }}
                            </p>

                            <ul class="space-y-3 text-gray-600 dark:text-[#D8C6B6]">
                                @foreach ([1, 2, 3] as $item)
                                    <li class="flex min-w-0 items-start gap-2">
                                        <span class="shrink-0 font-bold text-[#A65D3B] dark:text-[#b35200]">→</span>
                                        <span class="min-w-0 leading-relaxed">
                                            {{ __('messages.section_5_item_' . $item) }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </section>

                        <!-- Bagian 6: Kontak -->
                        <section
                            id="section-6"
                            class="min-w-0 scroll-mt-28"
                        >
                            <h3
                                class="mb-5 border-l-4 border-[#A65D3B]
                                       pl-3 text-lg font-bold uppercase
                                       leading-snug tracking-wide
                                       text-[#2C1A0E]
                                       dark:border-[#b35200]
                                       dark:text-[#FDF5EC]
                                       sm:mb-6 sm:border-l-8
                                       sm:pl-5 sm:text-xl"
                            >
                                6. {{ __('messages.section_6_title') }}
                            </h3>

                            <p class="mb-6 leading-relaxed text-gray-600 dark:text-[#D8C6B6] sm:mb-8">
                                {{ __('messages.section_6_intro') }}
                            </p>

                            <!-- Kartu Kontak -->
                            <div
                                class="w-full min-w-0 rounded-xl border
                                       border-[#e0ddd5] bg-[#fdfaf5]
                                       p-4 shadow-inner
                                       dark:border-white/10
                                       dark:bg-[#231811]
                                       sm:p-6 lg:p-8"
                            >
                                <ul class="w-full min-w-0 space-y-5">

                                    <!-- Email -->
                                    <li class="min-w-0">
                                        <div class="mb-1">
                                            <strong class="text-sm font-semibold text-gray-800 dark:text-[#FDF5EC] sm:text-base">
                                                {{ __('messages.email') }}:
                                            </strong>
                                        </div>

                                        <a
                                            href="https://mail.google.com/mail/?to=ikhtiarberkah1010@gmail.com&tf=cm"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            aria-label="Kirim email ke ikhtiarberkah1010@gmail.com via Gmail"
                                            title="Kirim email ke ikhtiarberkah1010@gmail.com via Gmail"
                                            class="inline-flex max-w-full items-center gap-1
                                                   whitespace-nowrap text-xs font-semibold
                                                   leading-normal text-[#A65D3B]
                                                   hover:underline dark:text-[#b35200]
                                                   sm:text-sm"
                                        >
                                            <span class="whitespace-nowrap">
                                                ikhtiarberkah1010@gmail.com
                                            </span>

                                            <svg
                                                class="h-3 w-3 shrink-0 opacity-75"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                viewBox="0 0 24 24"
                                                aria-hidden="true"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                                />
                                            </svg>
                                        </a>
                                    </li>

                                    <!-- WhatsApp -->
                                    <li class="min-w-0">
                                        <div class="mb-1">
                                            <strong class="text-sm font-semibold text-gray-800 dark:text-[#FDF5EC] sm:text-base">
                                                WhatsApp:
                                            </strong>
                                        </div>

                                        <a
                                            href="https://wa.me/628170769999"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="text-sm font-semibold leading-relaxed
                                                   text-[#A65D3B] hover:underline
                                                   dark:text-[#b35200] sm:text-base"
                                        >
                                            +62 817-0769-9999
                                        </a>
                                    </li>

                                    <!-- Instagram -->
                                    <li class="min-w-0">
                                        <div class="mb-1">
                                            <strong class="text-sm font-semibold text-gray-800 dark:text-[#FDF5EC] sm:text-base">
                                                {{ __('messages.instagram') }}:
                                            </strong>
                                        </div>

                                        <a
                                            href="https://www.instagram.com/ibekami.id/"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="text-sm font-semibold leading-relaxed
                                                   text-[#A65D3B] hover:underline
                                                   dark:text-[#b35200] sm:text-base"
                                        >
                                            @ibekami.id
                                        </a>
                                    </li>

                                    <!-- Lokasi -->
                                    <li class="min-w-0">
                                        <div class="mb-1">
                                            <strong class="text-sm font-semibold text-gray-800 dark:text-[#FDF5EC] sm:text-base">
                                                {{ __('messages.location') }}:
                                            </strong>
                                        </div>

                                        <a
                                            href="https://maps.app.goo.gl/o7soqw1UAc4AzDsH6"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            title="Buka lokasi di Google Maps"
                                            class="group block min-w-0 max-w-full"
                                        >
                                            <span
                                                class="inline-flex max-w-full items-start gap-1.5
                                                       text-sm font-semibold leading-relaxed
                                                       text-[#A65D3B] group-hover:underline
                                                       dark:text-[#b35200] sm:text-base"
                                            >
                                                <span class="min-w-0 break-words">
                                                    {{ __('messages.address_line1') }}
                                                </span>

                                                <svg
                                                    class="mt-1 h-3.5 w-3.5 shrink-0 opacity-75"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    viewBox="0 0 24 24"
                                                    aria-hidden="true"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                                    />
                                                </svg>
                                            </span>

                                            <span
                                                class="mt-1 block break-words
                                                       text-sm font-medium leading-relaxed
                                                       text-[#5C3D28]
                                                       dark:text-[#D8C6B6]"
                                            >
                                                {{ __('messages.address_line2') }}
                                            </span>
                                        </a>
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
