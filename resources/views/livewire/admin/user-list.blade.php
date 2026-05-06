<div>

    {{-- ── Table Card ───────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Daftar User</h2>
            <button wire:click="openCreate"
                    class="px-5 py-2 bg-cyan-500 hover:bg-cyan-600 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                + Tambah Admin
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
                       placeholder="Cari username atau nama..."
                       class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-300 w-52"/>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider w-16">
                            No
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider w-44">
                            <button wire:click="sort('username')" class="flex items-center gap-1 hover:text-gray-900">
                                Username
                                <span class="text-gray-400">@if($sortField==='username'){{ $sortDir==='asc'?'↑':'↓' }}@else ↕ @endif</span>
                            </button>
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider w-44">
                            <button wire:click="sort('name')" class="flex items-center gap-1 hover:text-gray-900">
                                Name
                                <span class="text-gray-400">@if($sortField==='name'){{ $sortDir==='asc'?'↑':'↓' }}@else ↕ @endif</span>
                            </button>
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">
                            Password
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider w-28">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-700 font-medium">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 text-gray-800 font-medium">{{ $user->username }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->name }}</td>
                            <td class="px-6 py-4">
                                {{-- Show hashed password with toggle --}}
                                <div x-data="{ show: false }" class="flex items-center gap-2">
                                    <span class="font-mono text-xs text-gray-500 break-all"
                                          x-text="show ? '{{ addslashes($user->password) }}' : '••••••••••••••••'">
                                    </span>
                                    <button type="button"
                                            @click="show = !show"
                                            class="shrink-0 text-gray-400 hover:text-gray-600 transition"
                                            :title="show ? 'Sembunyikan' : 'Tampilkan hash'">
                                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button wire:click="openEdit({{ $user->id }})"
                                            class="w-8 h-8 flex items-center justify-center rounded border border-cyan-400 text-cyan-500 hover:bg-cyan-50 transition"
                                            title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->username) }}')"
                                            @if(Auth::id() === $user->id) disabled title="Tidak bisa hapus akun sendiri" @endif
                                            class="w-8 h-8 flex items-center justify-center rounded border border-red-400 text-red-500 hover:bg-red-50 transition
                                                   disabled:opacity-30 disabled:cursor-not-allowed"
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
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                Tidak ada data user.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 text-sm text-gray-600">
            <span>
                @if($users->total() > 0)
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                @else
                    Showing 0 entries
                @endif
            </span>
            <div class="flex items-center gap-1">
                <button wire:click="previousPage" @disabled($users->onFirstPage())
                        class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    Previous
                </button>
                @php
                    $cur  = $users->currentPage();
                    $last = $users->lastPage();
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
                <button wire:click="nextPage" @disabled(!$users->hasMorePages())
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
                    {{ $isEditing ? 'Edit Admin Account' : 'Create Admin Account' }}
                </h3>
                <button @click="$wire.closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <form wire:submit="save" class="px-6 py-5 space-y-4">

                {{-- Username --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           wire:model="username"
                           placeholder="Masukkan username"
                           autocomplete="off"
                           class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition
                                  @error('username') border-red-400 bg-red-50 focus:ring-2 focus:ring-red-200
                                  @else border-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 @enderror"/>
                    @error('username')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           wire:model="name"
                           placeholder="Masukkan nama lengkap"
                           class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition
                                  @error('name') border-red-400 bg-red-50 focus:ring-2 focus:ring-red-200
                                  @else border-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 @enderror"/>
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Password
                        @if(!$isEditing) <span class="text-red-500">*</span> @endif
                    </label>
                    <div class="relative" x-data="{ showPwd: false }">
                        <input :type="showPwd ? 'text' : 'password'"
                               wire:model="password"
                               placeholder="{{ $isEditing ? 'Kosongkan jika tidak ingin mengubah' : 'Masukkan password' }}"
                               autocomplete="new-password"
                               class="w-full pl-4 pr-10 py-2.5 text-sm border rounded-lg outline-none transition
                                      @error('password') border-red-400 bg-red-50 focus:ring-2 focus:ring-red-200
                                      @else border-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 @enderror"/>
                        {{-- Toggle show/hide --}}
                        <button type="button"
                                @click="showPwd = !showPwd"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                            <svg x-show="!showPwd" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPwd" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-400">
                        Your password must be between 8 and 30 characters.
                        @if($isEditing) Kosongkan jika tidak ingin mengubah password. @endif
                    </p>
                    @error('password')
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
                        Cancel
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
                timer:             p.type === 'error' ? 4000 : 2500,
                timerProgressBar:  true,
                showConfirmButton: p.type === 'error',
            });
        });
    });

    function confirmDeleteUser(id, username) {
        Swal.fire({
            title:              'Hapus User?',
            html:               `User <strong>${username}</strong> akan dihapus permanen.`,
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
