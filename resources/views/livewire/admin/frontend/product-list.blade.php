<div>

    {{-- ── Table Card ───────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Product List</h2>
            <button wire:click="openCreate"
                    class="px-5 py-2 bg-cyan-500 hover:bg-cyan-600 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                + Add Product Data
            </button>
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
                       placeholder="Cari produk..."
                       class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-300 w-52"/>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider w-16">
                            No
                        </th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">
                            <button wire:click="sort('name')" class="flex items-center gap-1 hover:text-gray-900">
                                Product Name <span class="text-gray-400">@if($sortField==='name'){{ $sortDir==='asc'?'↑':'↓' }}@else ↕ @endif</span>
                            </button>
                        </th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">
                            Product Type
                        </th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">
                            Category
                        </th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">
                            Image
                        </th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">
                            <button wire:click="sort('status')" class="flex items-center gap-1 hover:text-gray-900">
                                Status <span class="text-gray-400">@if($sortField==='status'){{ $sortDir==='asc'?'↑':'↓' }}@else ↕ @endif</span>
                            </button>
                        </th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider w-28">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-gray-700 font-medium">
                                {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-3 text-gray-800 max-w-[140px]">
                                <span title="{{ $product->name }}">
                                    {{ Str::limit($product->name, 12) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 max-w-[110px]">
                                <span title="{{ $product->type?->name }}">
                                    {{ Str::limit($product->type?->name ?? '—', 10) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 max-w-[160px]">
                                {{ $product->category?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1">
                                    @forelse (array_slice($product->image_url ?? [], 0, 3) as $img)
                                        @php
                                            // Handle both full URL and filename
                                            if (filter_var($img, FILTER_VALIDATE_URL)) {
                                                $imageUrl = $img;
                                            } else {
                                                // Gunakan path yang benar sesuai dengan struktur folder
                                                $imageUrl = Storage::url('gambar_produk/' . $img);
                                            }
                                        @endphp
                                        <img src="{{ $imageUrl }}"
                                             alt="img"
                                             class="w-10 h-10 object-cover rounded border border-gray-200"
                                             onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'40\' height=\'40\'%3E%3Crect width=\'40\' height=\'40\' fill=\'%23f3f4f6\'/%3E%3C/svg%3E'"/>
                                    @empty
                                        <span class="text-gray-300 text-xs">—</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                             {{ $product->status === 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                    {{ $product->status ?? 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <button wire:click="openEdit('{{ $product->product_id }}')"
                                            class="w-8 h-8 flex items-center justify-center rounded border border-cyan-400 text-cyan-500 hover:bg-cyan-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="confirmDeleteProduct('{{ $product->product_id }}', '{{ addslashes($product->name) }}')"
                                            class="w-8 h-8 flex items-center justify-center rounded border border-red-400 text-red-500 hover:bg-red-50 transition">
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
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">Tidak ada data produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 text-sm text-gray-600">
            <span>
                @if($products->total() > 0)
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} entries
                @else
                    Showing 0 entries
                @endif
            </span>
            <div class="flex items-center gap-1">
                <button wire:click="previousPage" @disabled($products->onFirstPage())
                        class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    Previous
                </button>
                @php
                    $cur  = $products->currentPage();
                    $last = $products->lastPage();
                    $from = max(1, $cur - 2);
                    $to   = min($last, $cur + 2);
                @endphp
                @if ($from > 1)
                    <button wire:click="gotoPage(1)" class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 transition">1</button>
                    @if ($from > 2)<span class="px-1 text-gray-400">…</span>@endif
                @endif
                @for ($p = $from; $p <= $to; $p++)
                    <button wire:click="gotoPage({{ $p }})"
                            class="px-3 py-1.5 rounded border transition {{ $p===$cur ? 'bg-cyan-500 border-cyan-500 text-white font-semibold' : 'border-gray-300 hover:bg-gray-50' }}">
                        {{ $p }}
                    </button>
                @endfor
                @if ($to < $last)
                    @if ($to < $last - 1)<span class="px-1 text-gray-400">…</span>@endif
                    <button wire:click="gotoPage({{ $last }})" class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 transition">{{ $last }}</button>
                @endif
                <button wire:click="nextPage" @disabled(!$products->hasMorePages())
                        class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    Next
                </button>
            </div>
        </div>
    </div>

    {{-- ── Modal Create / Edit ──────────────────────────────────── --}}
    <div x-data="{ show: @entangle('showModal') }"
         x-show="show" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div class="absolute inset-0 bg-black/50" @click="$wire.closeModal()"></div>

        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl z-10 max-h-[90vh] flex flex-col"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
                <h3 class="text-base font-semibold text-gray-800">
                    {{ $isEditing ? 'Edit Product Data' : 'Add Product Data' }}
                </h3>
                <button @click="$wire.closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body (scrollable) --}}
            <div class="overflow-y-auto flex-1 px-6 py-5">
                <form wire:submit="save" id="productForm">
                    <div class="border border-gray-200 rounded-lg p-5 space-y-4">

                        {{-- Product Type --}}
                        <div class="grid grid-cols-3 items-start gap-4">
                            <label class="text-sm font-medium text-gray-700 pt-2.5">Product Type:</label>
                            <div class="col-span-2">
                                <select wire:model.live="product_type"
                                        class="w-full px-3 py-2.5 text-sm border rounded-lg outline-none transition
                                               @error('product_type') border-red-400 bg-red-50 @else border-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 @enderror">
                                    <option value="">— Pilih Jenis Produk —</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error('product_type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Category Product --}}
                        <div class="grid grid-cols-3 items-start gap-4">
                            <label class="text-sm font-medium text-gray-700 pt-2.5">Category Product:</label>
                            <div class="col-span-2">
                                <select wire:model="category_type"
                                        @disabled(!$product_type)
                                        class="w-full px-3 py-2.5 text-sm border rounded-lg outline-none transition
                                               disabled:bg-gray-100 disabled:cursor-not-allowed
                                               @error('category_type') border-red-400 bg-red-50 @else border-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 @enderror">
                                    <option value="">— Pilih Kategori Produk —</option>
                                    @foreach ($filteredCategories as $cat)
                                        <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('category_type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Product Name --}}
                        <div class="grid grid-cols-3 items-start gap-4">
                            <label class="text-sm font-medium text-gray-700 pt-2.5">Product Name:</label>
                            <div class="col-span-2">
                                <input type="text" wire:model="name" placeholder="Product Name"
                                       class="w-full px-3 py-2.5 text-sm border rounded-lg outline-none transition
                                              @error('name') border-red-400 bg-red-50 @else border-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 @enderror"/>
                                @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Product Description --}}
                        <div class="grid grid-cols-3 items-start gap-4">
                            <label class="text-sm font-medium text-gray-700 pt-2.5">Product Description:</label>
                            <div class="col-span-2">
                                <textarea wire:model="description" placeholder="Product Description" rows="3"
                                          class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-none transition
                                                 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 resize-none"></textarea>
                            </div>
                        </div>

                        {{-- Product Status --}}
                        <div class="grid grid-cols-3 items-start gap-4">
                            <label class="text-sm font-medium text-gray-700 pt-2.5">Product Status:</label>
                            <div class="col-span-2">
                                <select wire:model="status"
                                        class="w-full px-3 py-2.5 text-sm border rounded-lg outline-none transition
                                               @error('status') border-red-400 bg-red-50 @else border-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 @enderror">
                                    <option value="">— Select Product Status —</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                </select>
                                @error('status')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Product Details --}}
                        <div class="grid grid-cols-3 items-start gap-4">
                            <label class="text-sm font-medium text-gray-700 pt-2.5">Product Details:</label>
                            <div class="col-span-2 space-y-2">
                                @foreach ($details as $i => $detail)
                                    <div class="flex items-center gap-2">
                                        <input type="text" wire:model="details.{{ $i }}.key"
                                               placeholder="Nama detail (cth: Berat)"
                                               class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100"/>
                                        <input type="text" wire:model="details.{{ $i }}.value"
                                               placeholder="Nilai (cth: 200gr)"
                                               class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100"/>
                                        <button type="button" wire:click="removeDetail({{ $i }})"
                                                class="text-red-400 hover:text-red-600 transition shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                                <button type="button" wire:click="addDetail"
                                        class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add Detail
                                </button>
                            </div>
                        </div>

                        {{-- Product Picture --}}
                        <div class="grid grid-cols-3 items-start gap-4">
                            <label class="text-sm font-medium text-gray-700 pt-2.5">Product Picture:</label>
                            <div class="col-span-2 space-y-3">

                                {{-- Existing images (edit mode) --}}
                                @if (!empty($existingImages))
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($existingImages as $idx => $img)
                                            @php
                                                // Handle both full URL and filename
                                                if (filter_var($img, FILTER_VALIDATE_URL)) {
                                                    $imageUrl = $img;
                                                } else {
                                                    // Gunakan path yang benar sesuai dengan struktur folder
                                                    $imageUrl = Storage::url('gambar_produk/' . $img);
                                                }
                                            @endphp
                                            <div class="relative group">
                                                <img src="{{ $imageUrl }}"
                                                     alt="img"
                                                     class="w-16 h-16 object-cover rounded-lg border border-gray-200"
                                                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'64\' height=\'64\'%3E%3Crect width=\'64\' height=\'64\' fill=\'%23f3f4f6\'/%3E%3C/svg%3E'"/>
                                                <button type="button"
                                                        wire:click="removeExistingImage({{ $idx }})"
                                                        class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full
                                                               flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-xs">
                                                    ×
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- New image previews --}}
                                @if (!empty($images))
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($images as $img)
                                            <img src="{{ $img->temporaryUrl() }}"
                                                 alt="preview"
                                                 class="w-16 h-16 object-cover rounded-lg border border-cyan-200"/>
                                        @endforeach
                                    </div>
                                @endif

                                <input type="file" wire:model="images" multiple
                                       accept="image/jpg,image/jpeg,image/png,image/webp"
                                       class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg px-3 py-2
                                              file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0
                                              file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700
                                              hover:file:bg-gray-200 transition
                                              @error('images.*') border-red-400 @enderror"/>

                                <p class="text-xs text-red-500">
                                    Pastikan ukuran gambar sudah 1:1 dengan minimal 800px:800px
                                </p>

                                <div wire:loading wire:target="images" class="text-xs text-cyan-600 flex items-center gap-1">
                                    <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>
                                    Mengupload gambar...
                                </div>
                                @error('images.*')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center gap-3 mt-5">
                        <button type="submit" form="productForm"
                                wire:loading.attr="disabled"
                                class="px-5 py-2 bg-cyan-500 hover:bg-cyan-600 disabled:opacity-60 text-white text-sm font-semibold rounded-lg transition">
                            <span wire:loading.remove wire:target="save">Save Product Data</span>
                            <span wire:loading wire:target="save" class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>
                        <button type="button" @click="$wire.closeModal()"
                                class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold rounded-lg transition">
                            Cancel
                        </button>
                    </div>
                </form>
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

    function confirmDeleteProduct(id, name) {
        Swal.fire({
            title:              'Hapus Produk?',
            html:               `Produk <strong>${name}</strong> akan dihapus permanen.`,
            icon:               'warning',
            showCancelButton:   true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor:  '#6b7280',
            confirmButtonText:  'Ya, Hapus!',
            cancelButtonText:   'Batal',
            reverseButtons:     true,
        }).then((result) => {
            if (result.isConfirmed) {
                @this.delete(id);
            }
        });
    }
</script>
@endpush
