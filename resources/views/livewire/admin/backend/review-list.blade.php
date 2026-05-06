<div>

    {{-- ── Table Card ───────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Review List</h2>
            <button wire:click="openCreate"
                    class="px-5 py-2 bg-cyan-500 hover:bg-cyan-600 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                + Add New Review
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
                       placeholder="Cari nama atau review..."
                       class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-300 w-52"/>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider w-16">
                            <button wire:click="sort('id')" class="flex items-center gap-1 hover:text-gray-900">
                                ID <span class="text-gray-400">@if($sortField==='id'){{ $sortDir==='asc'?'↑':'↓' }}@else ↕ @endif</span>
                            </button>
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider w-44">
                            <button wire:click="sort('name')" class="flex items-center gap-1 hover:text-gray-900">
                                Reviewer Name <span class="text-gray-400">@if($sortField==='name'){{ $sortDir==='asc'?'↑':'↓' }}@else ↕ @endif</span>
                            </button>
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">
                            The Review
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider w-28">
                            <button wire:click="sort('star')" class="flex items-center gap-1 hover:text-gray-900">
                                Star <span class="text-gray-400">@if($sortField==='star'){{ $sortDir==='asc'?'↑':'↓' }}@else ↕ @endif</span>
                            </button>
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider w-32">
                            <button wire:click="sort('review_date')" class="flex items-center gap-1 hover:text-gray-900">
                                Review Date <span class="text-gray-400">@if($sortField==='review_date'){{ $sortDir==='asc'?'↑':'↓' }}@else ↕ @endif</span>
                            </button>
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider w-28">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($reviews as $review)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-700 font-medium">{{ $review->id }}</td>
                            <td class="px-6 py-4 text-gray-800 font-medium">{{ $review->name }}</td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs">
                                <p class="line-clamp-2 leading-relaxed">{{ $review->review }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-0.5">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->star ? 'text-yellow-400' : 'text-gray-200' }}"
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                    <span class="ml-1 text-xs text-gray-500">({{ $review->star }})</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-xs">
                                {{ $review->review_date?->format('d M Y') ?? '—' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button wire:click="openEdit({{ $review->id }})"
                                            class="w-8 h-8 flex items-center justify-center rounded border border-cyan-400 text-cyan-500 hover:bg-cyan-50 transition"
                                            title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="confirmDeleteReview({{ $review->id }}, '{{ addslashes($review->name) }}')"
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
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                Tidak ada data review.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 text-sm text-gray-600">
            <span>
                @if($reviews->total() > 0)
                    Showing {{ $reviews->firstItem() }} to {{ $reviews->lastItem() }} of {{ $reviews->total() }} entries
                @else
                    Showing 0 entries
                @endif
            </span>
            <div class="flex items-center gap-1">
                <button wire:click="previousPage" @disabled($reviews->onFirstPage())
                        class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    Previous
                </button>
                @php
                    $cur  = $reviews->currentPage();
                    $last = $reviews->lastPage();
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
                <button wire:click="nextPage" @disabled(!$reviews->hasMorePages())
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

        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-xl z-10"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-800">
                    {{ $isEditing ? 'Edit Review' : 'Add New Review' }}
                </h3>
                <button @click="$wire.closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <form wire:submit="save" class="px-6 py-5 space-y-4">

                {{-- Reviewer Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Reviewer Name: <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           wire:model="name"
                           placeholder="Enter Reviewer Name"
                           class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition
                                  @error('name') border-red-400 bg-red-50 focus:ring-2 focus:ring-red-200
                                  @else border-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 @enderror"/>
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Text Review --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Text Review: <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="review"
                              placeholder="Enter Text Review"
                              rows="4"
                              class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition resize-none
                                     @error('review') border-red-400 bg-red-50 focus:ring-2 focus:ring-red-200
                                     @else border-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 @enderror"></textarea>
                    @error('review')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Rating --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Rating Review: <span class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="star"
                            class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition
                                   @error('star') border-red-400 bg-red-50 focus:ring-2 focus:ring-red-200
                                   @else border-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 @enderror">
                        <option value="">Choose Rating Review</option>
                        <option value="1">⭐ Bintang 1</option>
                        <option value="2">⭐⭐ Bintang 2</option>
                        <option value="3">⭐⭐⭐ Bintang 3</option>
                        <option value="4">⭐⭐⭐⭐ Bintang 4</option>
                        <option value="5">⭐⭐⭐⭐⭐ Bintang 5</option>
                    </select>

                    {{-- Star preview --}}
                    @if ($star)
                        <div class="flex items-center gap-0.5 mt-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= (int)$star ? 'text-yellow-400' : 'text-gray-200' }}"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    @endif

                    @error('star')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Date Review --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Date Review:
                    </label>
                    <input type="date"
                           wire:model="review_date"
                           class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition
                                  @error('review_date') border-red-400 bg-red-50 focus:ring-2 focus:ring-red-200
                                  @else border-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 @enderror"/>
                    @error('review_date')
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

    function confirmDeleteReview(id, name) {
        Swal.fire({
            title:              'Hapus Review?',
            html:               `Review dari <strong>${name}</strong> akan dihapus permanen.`,
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
