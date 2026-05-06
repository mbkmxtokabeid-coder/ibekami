<div>

    {{-- ── Page Header ─────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- Title + Add Button --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Daftar Jenis Produk yang Tersedia</h2>
            <button wire:click="openCreate"
                    class="px-5 py-2 bg-cyan-500 hover:bg-cyan-600 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                + Tambah Jenis
            </button>
        </div>

        {{-- Table Controls --}}
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
                       placeholder="Cari jenis produk..."
                       class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-300 w-52"/>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider w-20">
                            No
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">
                            <button wire:click="sort('name')" class="flex items-center gap-1 hover:text-gray-900">
                                Types Product
                                <span class="text-gray-400 text-xs">
                                    @if($sortField === 'name') {{ $sortDir === 'asc' ? '↑' : '↓' }} @else ↕ @endif
                                </span>
                            </button>
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">
                            Image
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider w-32">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($types as $type)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-700 font-medium">
                                {{ ($types->currentPage() - 1) * $types->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 text-gray-800">{{ $type->name }}</td>
                            <td class="px-6 py-4">
                                @if ($type->image_url)
                                    <img src="{{ Storage::url($type->image_url) }}"
                                         alt="{{ $type->name }}"
                                         class="w-20 h-20 object-cover rounded-full border border-gray-200 shadow-sm"/>
                                @else
                                    <div class="w-20 h-20 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    {{-- Edit --}}
                                    <button wire:click="openEdit({{ $type->id }})"
                                            class="w-8 h-8 flex items-center justify-center rounded border border-cyan-400 text-cyan-500
                                                   hover:bg-cyan-50 transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    {{-- Delete --}}
                                    <button onclick="confirmDelete({{ $type->id }}, '{{ addslashes($type->name) }}')"
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
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                Tidak ada data jenis produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 text-sm text-gray-600">
            <span>
                @if($types->total() > 0)
                    Showing {{ $types->firstItem() }} to {{ $types->lastItem() }} of {{ $types->total() }} entries
                @else
                    Showing 0 entries
                @endif
            </span>
            <div class="flex items-center gap-1">
                <button wire:click="previousPage" @disabled($types->onFirstPage())
                        class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    Previous
                </button>

                @php
                    $cur  = $types->currentPage();
                    $last = $types->lastPage();
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

                <button wire:click="nextPage" @disabled(!$types->hasMorePages())
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
                <h3 class="text-base font-semibold text-gray-800">
                    {{ $isEditing ? 'Edit Jenis Produk' : 'Tambah Jenis Produk' }}
                </h3>
                <button @click="$wire.closeModal()"
                        class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <form wire:submit="save" class="px-6 py-5 space-y-5">

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama Jenis Produk <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           wire:model="name"
                           placeholder="Nama Jenis Barang"
                           class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition
                                  @error('name') border-red-400 bg-red-50 focus:ring-2 focus:ring-red-200
                                  @else border-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 @enderror"/>
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Gambar --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Gambar Jenis Produk
                    </label>

                    {{-- Preview gambar existing (saat edit) --}}
                    @if ($isEditing && $existingImage && !$image)
                        <div class="mb-3 flex items-center gap-3">
                            <img src="{{ Storage::url($existingImage) }}"
                                 alt="Current image"
                                 class="w-16 h-16 object-cover rounded-lg border border-gray-200"/>
                            <span class="text-xs text-gray-500">Gambar saat ini. Upload baru untuk mengganti.</span>
                        </div>
                    @endif

                    {{-- Preview gambar baru --}}
                    @if ($image)
                        <div class="mb-3">
                            <img src="{{ $image->temporaryUrl() }}"
                                 alt="Preview"
                                 class="w-16 h-16 object-cover rounded-lg border border-gray-200"/>
                        </div>
                    @endif

                    <input type="file"
                           wire:model="image"
                           accept="image/jpg,image/jpeg,image/png,image/webp"
                           class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg px-3 py-2
                                  file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0
                                  file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700
                                  hover:file:bg-gray-200 transition
                                  @error('image') border-red-400 bg-red-50 @enderror"/>
                    <p class="mt-1 text-xs text-gray-400">Format: JPG, PNG, WEBP. Maks 2MB.</p>
                    @error('image')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    {{-- Upload progress --}}
                    <div wire:loading wire:target="image" class="mt-2 text-xs text-cyan-600 flex items-center gap-1">
                        <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Mengupload gambar...
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                    <button type="button" @click="$wire.closeModal()"
                            class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="px-5 py-2 bg-cyan-500 hover:bg-cyan-600 disabled:opacity-60 text-white text-sm font-semibold rounded-lg transition">
                        <span wire:loading.remove wire:target="save">Simpan Data</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
    // ── SweetAlert handler ────────────────────────────────────────
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
                toast:             false,
            });
        });
    });

    // ── Delete confirmation ───────────────────────────────────────
    function confirmDelete(id, name) {
        Swal.fire({
            title:              'Hapus Jenis Produk?',
            html:               `Jenis produk <strong>${name}</strong> akan dihapus permanen.`,
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
