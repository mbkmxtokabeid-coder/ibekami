<div>

    {{-- ── Table Card ───────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Machine List</h2>
            <button wire:click="openCreate"
                    class="px-5 py-2 bg-cyan-500 hover:bg-cyan-600 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                + Add New Machine
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
                       placeholder="Cari mesin..."
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
                                ID
                                <span class="text-gray-400 text-xs">
                                    @if($sortField==='id') {{ $sortDir==='asc' ? '↑' : '↓' }} @else ↕ @endif
                                </span>
                            </button>
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">
                            <button wire:click="sort('title')" class="flex items-center gap-1 hover:text-gray-900">
                                Title
                                <span class="text-gray-400 text-xs">
                                    @if($sortField==='title') {{ $sortDir==='asc' ? '↑' : '↓' }} @else ↕ @endif
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
                    @forelse ($machines as $machine)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-700 font-medium">{{ $machine->id }}</td>
                            <td class="px-6 py-4 text-gray-800 font-medium uppercase">{{ $machine->title }}</td>
                            <td class="px-6 py-4">
                                @if ($machine->image_url)
                                    @php
                                        $imageUrl = $machine->image_url;
                                        // Ekstrak nama file saja
                                        $filename = basename($imageUrl);
                                        
                                        // Cek apakah URL lengkap
                                        if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                                            $imageSrc = $imageUrl;
                                        } else {
                                            // Cek file lokal di machine_picture
                                            $localPath = public_path('storage/machine_picture/' . $filename);
                                            if (file_exists($localPath)) {
                                                $imageSrc = asset('storage/machine_picture/' . $filename);
                                            } else {
                                                // Fallback ke ibekami.id
                                                $imageSrc = 'https://ibekami.id/storage/machine_picture/' . $filename;
                                            }
                                        }
                                    @endphp
                                    <img src="{{ $imageSrc }}"
                                         alt="{{ $machine->title }}"
                                         class="w-32 h-20 object-contain rounded-lg border border-gray-200 bg-white p-1"
                                         onerror="this.onerror=null; this.src='https://via.placeholder.com/128x80?text=No+Image'"/>
                                @else
                                    <div class="w-24 h-20 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button wire:click="openEdit({{ $machine->id }})"
                                            class="w-8 h-8 flex items-center justify-center rounded border border-cyan-400 text-cyan-500 hover:bg-cyan-50 transition"
                                            title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="confirmDeleteMachine({{ $machine->id }}, '{{ addslashes($machine->title) }}')"
                                            class="w-8 h-8 flex items-center justify-center rounded border border-red-400 text-red-500 hover:bg-red-50 transition"
                                            title="Hapus">
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
                                Tidak ada data mesin.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 text-sm text-gray-600">
            <span>
                @if($machines->total() > 0)
                    Showing {{ $machines->firstItem() }} to {{ $machines->lastItem() }} of {{ $machines->total() }} entries
                @else
                    Showing 0 entries
                @endif
            </span>
            <div class="flex items-center gap-1">
                <button wire:click="previousPage" @disabled($machines->onFirstPage())
                        class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    Previous
                </button>
                @php
                    $cur  = $machines->currentPage();
                    $last = $machines->lastPage();
                    $from = max(1, $cur - 2);
                    $to   = min($last, $cur + 2);
                @endphp
                @if ($from > 1)
                    <button wire:click="gotoPage(1)" class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 transition">1</button>
                    @if ($from > 2)<span class="px-1 text-gray-400">…</span>@endif
                @endif
                @for ($p = $from; $p <= $to; $p++)
                    <button wire:click="gotoPage({{ $p }})"
                            class="px-3 py-1.5 rounded border transition
                                   {{ $p === $cur ? 'bg-cyan-500 border-cyan-500 text-white font-semibold' : 'border-gray-300 hover:bg-gray-50' }}">
                        {{ $p }}
                    </button>
                @endfor
                @if ($to < $last)
                    @if ($to < $last - 1)<span class="px-1 text-gray-400">…</span>@endif
                    <button wire:click="gotoPage({{ $last }})" class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 transition">{{ $last }}</button>
                @endif
                <button wire:click="nextPage" @disabled(!$machines->hasMorePages())
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

        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg z-10"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-800">
                    {{ $isEditing ? 'Edit Machine' : 'Add New Machine' }}
                </h3>
                <button @click="$wire.closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <form wire:submit="save" class="px-6 py-5 space-y-5">

                <div class="border border-gray-200 rounded-lg p-5 space-y-4">

                    {{-- Machine Title --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Machine Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="title"
                               placeholder="Fill in the banner title here"
                               class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition
                                      @error('title') border-red-400 bg-red-50 focus:ring-2 focus:ring-red-200
                                      @else border-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 @enderror"/>
                        @error('title')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Machine Picture --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Machine Picture
                        </label>

                        {{-- Preview existing (edit mode) --}}
                        @if ($isEditing && $existingImage && !$image)
                            <div class="mb-3 flex items-center gap-3">
                                @php
                                    $filename = basename($existingImage);
                                    // Cek apakah URL lengkap
                                    if (filter_var($existingImage, FILTER_VALIDATE_URL)) {
                                        $previewSrc = $existingImage;
                                    } else {
                                        // Cek file lokal
                                        $localPath = public_path('storage/machine_picture/' . $filename);
                                        if (file_exists($localPath)) {
                                            $previewSrc = asset('storage/machine_picture/' . $filename);
                                        } else {
                                            // Fallback ke ibekami.id
                                            $previewSrc = 'https://ibekami.id/storage/machine_picture/' . $filename;
                                        }
                                    }
                                @endphp
                                <img src="{{ $previewSrc }}"
                                     alt="Current"
                                     class="w-24 h-20 object-contain rounded-lg border border-gray-200 bg-white p-1"
                                     onerror="this.src='https://via.placeholder.com/96x80?text=No+Image'"/>
                                <span class="text-xs text-gray-500">Gambar saat ini. Upload baru untuk mengganti.</span>
                            </div>
                        @endif

                        {{-- Preview new --}}
                        @if ($image)
                            <div class="mb-3">
                                <img src="{{ $image->temporaryUrl() }}"
                                     alt="Preview"
                                     class="w-20 h-16 object-contain rounded-lg border border-gray-200"/>
                            </div>
                        @endif

                        <input type="file"
                               wire:model="image"
                               accept="image/jpg,image/jpeg,image/png,image/webp"
                               x-data
                               x-on:change="
                                   const f = $event.target.files[0];
                                   if (f && f.size > 2 * 1024 * 1024) {
                                       $event.target.value = '';
                                       alert('❌ ' + f.name + '\n\nUkuran gambar melebihi 2MB. Silakan kompres gambar terlebih dahulu.');
                                   }
                               "
                               class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg px-3 py-2
                                      file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0
                                      file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700
                                      hover:file:bg-gray-200 transition
                                      @error('image') border-red-400 bg-red-50 @enderror"/>

                        <p class="mt-1.5 text-xs text-gray-400">
                            Format: JPG, PNG, WEBP · Maks <strong>2MB</strong> · Resolusi 1280×720px
                        </p>

                        <div wire:loading wire:target="image" class="mt-1 text-xs text-cyan-600 flex items-center gap-1">
                            <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Mengupload...
                        </div>
                        @error('image')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Footer --}}
                <div class="flex items-center gap-3">
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="px-5 py-2 bg-cyan-500 hover:bg-cyan-600 disabled:opacity-60 text-white text-sm font-semibold rounded-lg transition">
                        <span wire:loading.remove wire:target="save">Save Data</span>
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
                        Batal
                    </button>
                </div>

            </form>
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

    function confirmDeleteMachine(id, title) {
        Swal.fire({
            title:              'Hapus Mesin?',
            html:               `Mesin <strong>${title}</strong> akan dihapus permanen.`,
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
