<div>

    {{-- ── Table Card ───────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Banner List</h2>
            <div class="flex items-center gap-3">
                @if ($maxReached)
                    <span class="text-xs font-medium text-orange-700 bg-orange-100 border border-orange-200 px-3 py-1.5 rounded-lg">
                        Max {{ \App\Livewire\Admin\Backend\BannerList::MAX_BANNERS }} banner reached — edit or delete to replace
                    </span>
                @else
                    <button wire:click="openCreate"
                            class="px-5 py-2 bg-cyan-500 hover:bg-cyan-600 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                        + Tambah Banner
                    </button>
                @endif
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
                </select>
                <span>entries</span>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <label>Search:</label>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Cari..."
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
                                ID <span class="text-gray-400">@if($sortField==='id'){{ $sortDir==='asc'?'↑':'↓' }}@else ↕ @endif</span>
                            </button>
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">
                            Media (Image/Video)
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">
                            Thumbnail
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider w-32">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($banners as $banner)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-700 font-medium">{{ $banner->id }}</td>
                            <td class="px-6 py-4">
                                @if ($banner->media_url)
                                    @if ($banner->isVideo())
                                        <div class="relative inline-block">
                                            <video
                                                src="{{ Storage::url($banner->media_url) }}"
                                                class="w-32 h-32 object-cover rounded-lg border border-gray-200"
                                                muted playsinline preload="metadata">
                                            </video>
                                            <span class="absolute top-1 left-1 bg-black/60 text-white text-[10px] px-1.5 py-0.5 rounded font-medium">
                                                VIDEO
                                            </span>
                                        </div>
                                    @else
                                        <img src="{{ Storage::url($banner->media_url) }}"
                                             alt="Banner"
                                             class="w-32 h-32 object-cover rounded-lg border border-gray-200"/>
                                    @endif
                                @else
                                    {{-- No media yet --}}
                                    <div class="flex items-center gap-3">
                                        <div class="w-32 h-32 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 flex flex-col items-center justify-center gap-1">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                            </svg>
                                            <span class="text-xs text-gray-400">No media</span>
                                        </div>
                                        <button wire:click="openEdit({{ $banner->id }})"
                                                class="px-3 py-1.5 bg-cyan-500 hover:bg-cyan-600 text-white text-xs font-semibold rounded-lg transition">
                                            + Tambahkan Media
                                        </button>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($banner->thumbnail_url)
                                    <img src="{{ Storage::url($banner->thumbnail_url) }}"
                                         alt="Thumbnail banner"
                                         class="w-32 h-32 object-cover rounded-lg border border-gray-200"/>
                                @else
                                    <div class="w-32 h-32 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center">
                                        <span class="text-[11px] text-gray-400">No thumbnail</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button wire:click="openEdit({{ $banner->id }})"
                                            class="w-8 h-8 flex items-center justify-center rounded border border-cyan-400 text-cyan-500 hover:bg-cyan-50 transition"
                                            title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="confirmDeleteBanner({{ $banner->id }})"
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
                                Belum ada banner.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 text-sm text-gray-600">
            <span>
                @if($banners->total() > 0)
                    Showing {{ $banners->firstItem() }} to {{ $banners->lastItem() }} of {{ $banners->total() }} entries
                @else
                    Showing 0 entries
                @endif
            </span>
            <div class="flex items-center gap-1">
                <button wire:click="previousPage" @disabled($banners->onFirstPage())
                        class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    Previous
                </button>
                @php $cur = $banners->currentPage(); $last = $banners->lastPage(); @endphp
                @for ($p = max(1,$cur-2); $p <= min($last,$cur+2); $p++)
                    <button wire:click="gotoPage({{ $p }})"
                            class="px-3 py-1.5 rounded border transition {{ $p===$cur ? 'bg-cyan-500 border-cyan-500 text-white font-semibold' : 'border-gray-300 hover:bg-gray-50' }}">
                        {{ $p }}
                    </button>
                @endfor
                <button wire:click="nextPage" @disabled(!$banners->hasMorePages())
                        class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    Next
                </button>
            </div>
        </div>
    </div>

    {{-- ── Modal Create / Edit ──────────────────────────────────── --}}
    <div x-data="{ show: @entangle('showModal') }"
         x-show="show" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div class="absolute inset-0 bg-black/50" @click="$wire.closeModal()"></div>

        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] z-10 flex flex-col my-8"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            {{-- Modal Header (Fixed) --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
                <h3 class="text-base font-semibold text-gray-800">
                    {{ $isEditing ? 'Edit Media (Video/Image)' : 'Tambah Banner' }}
                </h3>
                <button @click="$wire.closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body (Scrollable) --}}
            <div class="overflow-y-auto flex-1 px-6 py-5">
                <form wire:submit="save" class="space-y-4" id="banner-form">

                <div class="border border-gray-200 rounded-lg p-5 space-y-4">

                    {{-- Upload field --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            {{ $isEditing ? 'Update Video or Image' : 'Upload Video or Image' }}
                            @if(!$isEditing) <span class="text-red-500">*</span> @endif
                        </label>

                        <input type="file"
                               wire:model="media"
                               accept="image/jpeg,image/png,image/webp,video/mp4,video/quicktime,video/x-msvideo,video/x-matroska,video/webm"
                               x-data
                               x-on:change="
                                   const f = $event.target.files[0];
                                   if (!f) return;
                                   const isVideo = f.type.startsWith('video/');
                                   const maxImage = 2 * 1024 * 1024;   // 2MB untuk gambar
                                   const maxVideo = 100 * 1024 * 1024; // 100MB untuk video
                                   if (!isVideo && f.size > maxImage) {
                                       $event.target.value = '';
                                       alert('❌ ' + f.name + '\n\nUkuran gambar melebihi 2MB. Silakan kompres gambar terlebih dahulu.');
                                   } else if (isVideo && f.size > maxVideo) {
                                       $event.target.value = '';
                                       alert('❌ ' + f.name + '\n\nUkuran video melebihi 100MB.');
                                   }
                               "
                               class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg px-3 py-2
                                      file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0
                                      file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700
                                      hover:file:bg-gray-200 transition
                                      @error('media') border-red-400 bg-red-50 @enderror"/>

                        {{-- Hints --}}
                        <div class="mt-2 space-y-1">
                            <p class="text-xs text-gray-400">
                                📹 <strong>Video:</strong> Format mp4/mov/avi/mkv — maks <strong>100MB</strong>,
                                durasi disarankan <strong>≤ 30 detik</strong>, rasio <strong>1:1</strong>.
                                Video akan otomatis dikompresi ke <strong>WebM</strong>.
                            </p>
                            <p class="text-xs text-gray-400">
                                🖼️ <strong>Gambar:</strong> Format jpg/png/webp — maks <strong>2MB</strong>, rasio vertikal 9:16 (720×1280px).
                            </p>
                        </div>

                        {{-- Upload progress --}}
                        <div wire:loading wire:target="media" class="mt-2 text-xs text-cyan-600 flex items-center gap-1.5">
                            <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Mengupload file...
                        </div>

                        @error('media')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Thumbnail Upload (for video LCP optimization) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Thumbnail (Opsional - untuk optimasi LCP video)
                        </label>

                        <input type="file"
                               wire:model="thumbnail"
                               accept="image/jpeg,image/png,image/webp"
                               x-data
                               x-on:change="
                                   const f = $event.target.files[0];
                                   if (!f) return;
                                   const maxSize = 2 * 1024 * 1024; // 2MB
                                   if (f.size > maxSize) {
                                       $event.target.value = '';
                                       alert('❌ ' + f.name + '\n\nUkuran thumbnail melebihi 2MB.');
                                   }
                               "
                               class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg px-3 py-2
                                      file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0
                                      file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700
                                      hover:file:bg-gray-200 transition
                                      @error('thumbnail') border-red-400 bg-red-50 @enderror"/>

                        {{-- Hints --}}
                        <div class="mt-2 space-y-1">
                            <p class="text-xs text-gray-400">
                                🎯 <strong>Thumbnail:</strong> Gambar yang ditampilkan sebelum video dimuat.
                                Gunakan frame pertama video atau screenshot terbaik.
                            </p>
                            <p class="text-xs text-gray-400">
                                💡 <strong>Tips:</strong> Ekstrak dari video dengan FFmpeg: 
                                <code class="bg-gray-100 px-1 rounded">ffmpeg -i video.mp4 -vframes 1 thumbnail.webp</code>
                            </p>
                            <p class="text-xs text-gray-400">
                                📦 Target ukuran: <strong>&lt;100KB</strong> (akan otomatis dikompres ke WebP)
                            </p>
                        </div>

                        {{-- Upload progress --}}
                        <div wire:loading wire:target="thumbnail" class="mt-2 text-xs text-cyan-600 flex items-center gap-1.5">
                            <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Mengupload thumbnail...
                        </div>

                        @error('thumbnail')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Current media preview --}}
                    @if ($isEditing && $existingMedia)
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Current Media Preview:</p>
                            @if ($existingType === 'video')
                                <video src="{{ Storage::url($existingMedia) }}"
                                       controls
                                       class="w-full max-h-48 rounded-lg border border-gray-200 object-contain bg-black">
                                </video>
                            @else
                                <img src="{{ Storage::url($existingMedia) }}"
                                     alt="Current banner"
                                     class="w-full max-h-48 rounded-lg border border-gray-200 object-contain"/>
                            @endif
                        </div>
                    @endif

                    {{-- Current thumbnail preview --}}
                    @if ($isEditing && $existingThumbnail)
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Current Thumbnail:</p>
                            <img src="{{ Storage::url($existingThumbnail) }}"
                                 alt="Current thumbnail"
                                 class="w-full max-h-32 rounded-lg border border-gray-200 object-contain"/>
                        </div>
                    @endif

                    {{-- New media preview --}}
                    @if ($media)
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Preview Baru:</p>
                            @php $previewMime = $media->getMimeType(); @endphp
                            @if (str_starts_with($previewMime, 'video/'))
                                <video src="{{ $media->temporaryUrl() }}"
                                       controls
                                       class="w-full max-h-48 rounded-lg border border-cyan-200 object-contain bg-black">
                                </video>
                                <p class="mt-1 text-xs text-cyan-600 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Video akan dikompresi otomatis ke format WebM saat disimpan.
                                </p>
                            @else
                                <img src="{{ $media->temporaryUrl() }}"
                                     alt="Preview"
                                     class="w-full max-h-48 rounded-lg border border-cyan-200 object-contain"/>
                            @endif
                        </div>
                    @endif

                    {{-- New thumbnail preview --}}
                    @if ($thumbnail)
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Preview Thumbnail Baru:</p>
                            <img src="{{ $thumbnail->temporaryUrl() }}"
                                 alt="Thumbnail preview"
                                 class="w-full max-h-32 rounded-lg border border-cyan-200 object-contain"/>
                            <p class="mt-1 text-xs text-cyan-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Thumbnail akan dikompresi otomatis ke WebP (&lt;100KB) saat disimpan.
                            </p>
                        </div>
                    @endif

                </div>

                {{-- Processing indicator --}}
                @if ($isProcessing)
                    <div class="flex items-center gap-2 text-sm text-cyan-700 bg-cyan-50 border border-cyan-200 rounded-lg px-4 py-3">
                        <svg class="animate-spin w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Mengompresi video ke WebM... Mohon tunggu.
                    </div>
                @endif
                </form>
            </div>

            {{-- Modal Footer (Fixed) --}}
            <div class="flex items-center gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50 shrink-0">
                <button type="submit"
                        form="banner-form"
                        wire:loading.attr="disabled"
                        class="px-5 py-2 bg-green-500 hover:bg-green-600 disabled:opacity-60 text-white text-sm font-semibold rounded-lg transition">
                    <span wire:loading.remove wire:target="save">
                        {{ $isEditing ? 'Update Data' : 'Simpan Banner' }}
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Memproses...
                    </span>
                </button>
                <button type="button" @click="$wire.closeModal()"
                        class="px-5 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition">
                    Cancel
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
                timer:             p.type === 'warning' ? 4000 : 2500,
                timerProgressBar:  true,
                showConfirmButton: p.type === 'warning',
            });
        });
    });

    function confirmDeleteBanner(id) {
        Swal.fire({
            title:              'Hapus Banner?',
            text:               'Banner ini akan dihapus permanen.',
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
