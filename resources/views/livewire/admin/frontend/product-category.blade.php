<div>

    {{-- ── Table Card ───────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- Header --}}
        
            
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Kategori Produk</h2>
            
            <div class="flex items-center gap-2">
                <button wire:click="openDeleteType"
                        class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-sm font-semibold rounded-lg transition shadow-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus Jenis
                </button>
                <button wire:click="openCreate"
                        class="px-5 py-2 bg-cyan-500 hover:bg-cyan-600 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                    + Isi Kategori
                </button>
            </div>
        </div>
        {{-- Controls --}}
        <div class="px-6 py-3 flex flex-wrap items-center justify-between gap-3 border-b border-gray-100">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <span>Show</span>
                <select wire:model.live="perPage"
                        class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-300">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <span>entries</span>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <label>Search:</label>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Cari kategori atau jenis..."
                       class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-300 w-52"/>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider w-20">
                            <button wire:click="sort('id')" class="flex items-center gap-1 hover:text-gray-900">
                                No
                                <span class="text-gray-400 text-xs">
                                    @if($sortField === 'id') {{ $sortDir === 'asc' ? '↑' : '↓' }} @else ↕ @endif
                                </span>
                            </button>
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">
                            <button wire:click="sort('type_name')" class="flex items-center gap-1 hover:text-gray-900">
                                Jenis Produk
                                <span class="text-gray-400 text-xs">
                                    @if($sortField === 'type_name') {{ $sortDir === 'asc' ? '↑' : '↓' }} @else ↕ @endif
                                </span>
                            </button>
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">
                            <button wire:click="sort('name_id')" class="flex items-center gap-1 hover:text-gray-900">
                                Kategori (ID)
                                <span class="text-gray-400 text-xs">
                                    @if($sortField === 'name_id') {{ $sortDir === 'asc' ? '↑' : '↓' }} @else ↕ @endif
                                </span>
                            </button>
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">
                            <button wire:click="sort('name_en')" class="flex items-center gap-1 hover:text-gray-900">
                                Kategori (EN)
                                <span class="text-gray-400 text-xs">
                                    @if($sortField === 'name_en') {{ $sortDir === 'asc' ? '↑' : '↓' }} @else ↕ @endif
                                </span>
                            </button>
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider w-32">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($categories as $i => $cat)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-700 font-medium">
                                {{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 text-gray-800">
                                {{ $cat->type?->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-gray-800">{{ $cat->name_id }}</td>
                            <td class="px-6 py-4 text-gray-800">{{ $cat->name_en }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    {{-- Edit --}}
                                    <button wire:click="openEdit({{ $cat->id }})"
                                            class="w-8 h-8 flex items-center justify-center rounded border border-cyan-400 text-cyan-500
                                                   hover:bg-cyan-50 transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    {{-- Delete --}}
                                    <button wire:click="openDelete({{ $cat->id }})"
                                            class="w-8 h-8 flex items-center justify-center rounded border border-red-400 text-red-500
                                                   hover:bg-red-50 transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                Tidak ada data kategori produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 text-sm text-gray-600">
            <span>
                @if($categories->total() > 0)
                    Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} entries
                @else
                    Showing 0 entries
                @endif
            </span>
            <div class="flex items-center gap-1">
                <button wire:click="previousPage" @disabled($categories->onFirstPage())
                        class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    Previous
                </button>

                @php
                    $cur  = $categories->currentPage();
                    $last = $categories->lastPage();
                    $from = max(1, $cur - 2);
                    $to   = min($last, $cur + 2);
                @endphp

                @if ($from > 1)
                    <button wire:click="gotoPage(1)" class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 transition">1</button>
                    @if ($from > 2) <span class="px-1 text-gray-400">…</span> @endif
                @endif

                @for ($p = $from; $p <= $to; $p++)
                    <button wire:click="gotoPage({{ $p }})"
                            class="px-3 py-1.5 rounded border transition
                                   {{ $p === $cur ? 'bg-cyan-500 border-cyan-500 text-white font-semibold' : 'border-gray-300 hover:bg-gray-50' }}">
                        {{ $p }}
                    </button>
                @endfor

                @if ($to < $last)
                    @if ($to < $last - 1) <span class="px-1 text-gray-400">…</span> @endif
                    <button wire:click="gotoPage({{ $last }})" class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 transition">{{ $last }}</button>
                @endif

                <button wire:click="nextPage" @disabled(!$categories->hasMorePages())
                        class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    Next
                </button>
            </div>
        </div>
    </div>

    {{-- ── Modal Create / Edit ──────────────────────────────────── --}}
    <div x-data="{ show: @entangle('showModal') }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50" @click="$wire.closeModal()"></div>

        {{-- Modal Box --}}
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg z-10"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-800">Kategori Produk</h3>
                <button @click="$wire.closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <form wire:submit="save" class="px-6 py-5 space-y-5">

                {{-- Jenis Produk --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Jenis Produk: <span class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="type_id"
                            class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition
                                   @error('type_id') border-red-400 bg-red-50 focus:ring-2 focus:ring-red-200
                                   @else border-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 @enderror">
                        {{-- <option value="">— Pilih Jenis Produk —</option> --}}
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                        <option value="new" >Jenis Produk Baru</option>
                    </select>
                    @error('type_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @if ($type_id === 'new')
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Jenis Produk Baru (Bahasa Indonesia)<span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                            wire:model="new_type_name_id"
                            placeholder="Contoh: Merchandise"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg outline-none transition focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100"/>

                        @error('new_type_name_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Jenis Produk Baru (English) <span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                            wire:model="new_type_name_en"
                            placeholder="Example: Merchandise"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg outline-none transition focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100"/>

                        @error('new_type_name_en')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
                {{-- Nama Kategori (Bahasa Indonesia) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Kategori Produk (Bahasa Indonesia) <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           wire:model="name_id"
                           placeholder="Contoh: Tumbler"
                           class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition
                                  @error('name_id') border-red-400 bg-red-50 focus:ring-2 focus:ring-red-200
                                  @else border-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 @enderror"/>
                    @error('name_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Kategori (English) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Kategori Produk (English) <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           wire:model="name_en"
                           placeholder="Example: Tumbler"
                           class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition
                                  @error('name_en') border-red-400 bg-red-50 focus:ring-2 focus:ring-red-200
                                  @else border-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 @enderror"/>
                    @error('name_en')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Footer --}}
                <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="px-5 py-2 bg-green-500 hover:bg-green-600 disabled:opacity-60 text-white text-sm font-semibold rounded-lg transition">
                        <span wire:loading.remove wire:target="save">Save Changes</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                    <button type="button" @click="$wire.closeModal()"
                            class="px-5 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition">
                        Close
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- ── Modal Delete ─────────────────────────────────────────────── --}}
    <div x-data="{ show: @entangle('showDeleteModal') }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="absolute inset-0 bg-black/50" @click="$wire.closeDeleteModal()"></div>

        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg z-10">
            {{-- Header --}}
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-red-100 text-red-500 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-semibold text-gray-800">Hapus Kategori?</h3>
                    <p class="text-sm text-gray-500">Kategori <strong class="text-gray-700">{{ $deletingName }}</strong> akan dihapus permanen.</p>
                </div>
                <button @click="$wire.closeDeleteModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5 space-y-4">
                @if ($deletingProductCount > 0)
                    <div class="text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3">
                        Terdapat <strong>{{ $deletingProductCount }} produk</strong> yang menggunakan kategori ini.
                        Pilih tindakan untuk produk tersebut:
                    </div>

                    {{-- Opsi 1: Pindahkan --}}
                    <label class="flex items-start gap-3 p-4 border rounded-lg cursor-pointer transition
                                  {{ $deleteMode === 'move' ? 'border-cyan-400 bg-cyan-50' : 'border-gray-200 hover:bg-gray-50' }}">
                        <input type="radio" wire:model.live="deleteMode" value="move" class="mt-1 text-cyan-500 focus:ring-cyan-300">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-800">Hapus kategori &amp; pindahkan produk</p>
                            <p class="text-xs text-gray-500 mb-2">Produk akan dipindahkan ke kategori lain (jenis produk ikut menyesuaikan).</p>

                            @if ($deleteMode === 'move')
                                <select wire:model="targetCategoryId"
                                        class="w-full px-3 py-2 text-sm border rounded-lg outline-none transition
                                               @error('targetCategoryId') border-red-400 bg-red-50 @else border-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 @enderror">
                                    <option value="">— Pilih Kategori Pengganti —</option>
                                    @foreach ($replacementCategories as $rc)
                                        <option value="{{ $rc->id }}">
                                            {{ $rc->name_id }}{{ $rc->type ? ' ('.$rc->type->name.')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('targetCategoryId')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            @endif
                        </div>
                    </label>

                    {{-- Opsi 2: Hapus semua --}}
                    <label class="flex items-start gap-3 p-4 border rounded-lg cursor-pointer transition
                                  {{ $deleteMode === 'cascade' ? 'border-red-400 bg-red-50' : 'border-gray-200 hover:bg-gray-50' }}">
                        <input type="radio" wire:model.live="deleteMode" value="cascade" class="mt-1 text-red-500 focus:ring-red-300">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-800">Hapus kategori beserta produknya</p>
                            <p class="text-xs text-gray-500">
                                {{ $deletingProductCount }} produk (termasuk gambarnya) akan ikut dihapus permanen.
                            </p>
                        </div>
                    </label>
                @else
                    <p class="text-sm text-gray-600">Tidak ada produk yang menggunakan kategori ini.</p>
                @endif
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                <button type="button" @click="$wire.closeDeleteModal()"
                        class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-semibold rounded-lg transition">
                    Batal
                </button>
                <button type="button" wire:click="confirmDelete" wire:loading.attr="disabled" wire:target="confirmDelete"
                        class="px-5 py-2 bg-red-500 hover:bg-red-600 disabled:opacity-60 text-white text-sm font-semibold rounded-lg transition">
                    <span wire:loading.remove wire:target="confirmDelete">Ya, Hapus!</span>
                    <span wire:loading wire:target="confirmDelete">Menghapus...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Modal Delete Jenis ────────────────────────────────────────── --}}
    <div x-data="{ show: @entangle('showDeleteTypeModal') }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="absolute inset-0 bg-black/50" @click="$wire.closeDeleteTypeModal()"></div>

        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg z-10"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            {{-- Header --}}
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-red-100 text-red-500 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-semibold text-gray-800">Hapus Jenis Produk</h3>
                    <p class="text-xs text-gray-500">Pilih jenis produk yang ingin dihapus dari sistem.</p>
                </div>
                <button @click="$wire.closeDeleteTypeModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5 space-y-4">
                {{-- Dropdown Pilih Jenis Produk yang Mau Dihapus --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Pilih Jenis Produk yang Ingin Dihapus: <span class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="selectedDeleteTypeId"
                            class="w-full px-3 py-2 text-sm border rounded-lg outline-none transition
                                   @error('selectedDeleteTypeId') border-red-400 bg-red-50 @else border-gray-300 focus:border-red-400 focus:ring-2 focus:ring-red-100 @enderror">
                        <option value="">— Pilih Jenis Produk —</option>
                        @foreach ($types as $t)
                            <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->name_en }})</option>
                        @endforeach
                    </select>
                    @error('selectedDeleteTypeId')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @if ($selectedDeleteTypeId)
                    @if ($deletingTypeCategoryCount > 0 || $deletingTypeProductCount > 0)
                        <div class="text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3">
                            Terdapat <strong>{{ $deletingTypeCategoryCount }} kategori</strong> dan <strong>{{ $deletingTypeProductCount }} produk</strong> pada jenis produk ini.
                            Pilih tindakan:
                        </div>

                        {{-- Opsi 1: Pindahkan kategori dan produk --}}
                        <label class="flex items-start gap-3 p-4 border rounded-lg cursor-pointer transition
                                      {{ $deleteTypeMode === 'move' ? 'border-cyan-400 bg-cyan-50' : 'border-gray-200 hover:bg-gray-50' }}">
                            <input type="radio" wire:model.live="deleteTypeMode" value="move" class="mt-1 text-cyan-500 focus:ring-cyan-300">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-800">Hapus jenis &amp; pindahkan kategori dan produk</p>
                                <p class="text-xs text-gray-500 mb-2">Kategori dan produk akan dialihkan ke jenis produk lain.</p>

                                @if ($deleteTypeMode === 'move')
                                    <select wire:model="targetTypeId"
                                            class="w-full px-3 py-2 text-sm border rounded-lg outline-none transition
                                                   @error('targetTypeId') border-red-400 bg-red-50 @else border-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 @enderror">
                                        <option value="">— Pilih Jenis Produk Pengganti —</option>
                                        @foreach ($replacementTypes as $rt)
                                            <option value="{{ $rt->id }}">{{ $rt->name }} ({{ $rt->name_en }})</option>
                                        @endforeach
                                    </select>
                                    @error('targetTypeId')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                @endif
                            </div>
                        </label>

                        {{-- Opsi 2: Hapus semua --}}
                        <label class="flex items-start gap-3 p-4 border rounded-lg cursor-pointer transition
                                      {{ $deleteTypeMode === 'cascade' ? 'border-red-400 bg-red-50' : 'border-gray-200 hover:bg-gray-50' }}">
                            <input type="radio" wire:model.live="deleteTypeMode" value="cascade" class="mt-1 text-red-500 focus:ring-red-300">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-800">Hapus jenis beserta seluruh kategori &amp; produknya</p>
                                <p class="text-xs text-gray-500">
                                    {{ $deletingTypeCategoryCount }} kategori dan {{ $deletingTypeProductCount }} produk (termasuk gambarnya) akan ikut dihapus permanen.
                                </p>
                            </div>
                        </label>
                    @else
                        <div class="text-sm text-gray-600 bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                            Tidak ada kategori atau produk yang menggunakan jenis ini. Jenis produk dapat langsung dihapus.
                        </div>
                    @endif
                @endif
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                <button type="button" @click="$wire.closeDeleteTypeModal()"
                        class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-semibold rounded-lg transition">
                    Batal
                </button>
                <button type="button"
                        wire:click="confirmDeleteType"
                        wire:loading.attr="disabled"
                        wire:target="confirmDeleteType"
                        @disabled(!$selectedDeleteTypeId)
                        class="px-5 py-2 bg-red-500 hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-lg transition">
                    <span wire:loading.remove wire:target="confirmDeleteType">Ya, Hapus!</span>
                    <span wire:loading wire:target="confirmDeleteType">Menghapus...</span>
                </button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('swal', (params) => {
            const p = Array.isArray(params) ? params[0] : params;
            Swal.fire({
                icon:              p.type  || 'info',
                title:             p.title || '',
                text:              p.text  || '',
                timer:             2500,
                timerProgressBar:  true,
                showConfirmButton: false,
            });
        });
    });
</script>
@endpush
