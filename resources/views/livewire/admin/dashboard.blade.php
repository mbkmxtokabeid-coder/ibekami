<div class="space-y-6">

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <div class="rounded-xl p-5 text-white"
             style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%)">
            <p class="text-sm font-medium opacity-80">Total Product</p>
            <p class="text-4xl font-bold mt-1">{{ number_format($totalProducts) }}</p>
        </div>

        <div class="rounded-xl p-5 text-white"
             style="background: linear-gradient(135deg, #0f9b8e 0%, #00d2d3 100%)">
            <p class="text-sm font-medium opacity-80">Total Partner</p>
            <p class="text-4xl font-bold mt-1">{{ number_format($totalPartners) }}</p>
        </div>

    </div>

    {{-- Chart Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Click Counts</h2>
        </div>
        <div class="p-6">
            <div id="clickCountChart" class="w-full" style="height: 320px;"></div>
        </div>
        <div class="px-6 pb-5 flex justify-center">
            <button class="px-6 py-2 bg-cyan-500 hover:bg-cyan-600 text-white text-sm font-medium rounded-lg transition">
                Lihat Selengkapnya
            </button>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Product Click Count Page Table</h2>
        </div>

        {{-- Table controls --}}
        <div class="px-6 py-3 flex flex-wrap items-center justify-between gap-3 border-b border-gray-100">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <span>Show</span>
                <select wire:model.live="perPage"
                        class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-300">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>entries</span>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <label>Search:</label>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Cari produk..."
                       class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-300 w-48"/>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">
                            <button wire:click="sort('name')" class="flex items-center gap-1 hover:text-gray-900">
                                Product Name
                                <span class="text-gray-400">
                                    @if ($sortField === 'name')
                                        @if ($sortDir === 'asc') ↑ @else ↓ @endif
                                    @else
                                        ↕
                                    @endif
                                </span>
                            </button>
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">
                            <button wire:click="sort('click_count')" class="flex items-center gap-1 hover:text-gray-900">
                                Product Click
                                <span class="text-gray-400">
                                    @if ($sortField === 'click_count')
                                        @if ($sortDir === 'asc') ↑ @else ↓ @endif
                                    @else
                                        ↕
                                    @endif
                                </span>
                            </button>
                        </th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">
                            <button wire:click="sort('order_click_count')" class="flex items-center gap-1 hover:text-gray-900">
                                Order Click
                                <span class="text-gray-400">
                                    @if ($sortField === 'order_click_count')
                                        @if ($sortDir === 'asc') ↑ @else ↓ @endif
                                    @else
                                        ↕
                                    @endif
                                </span>
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3 text-gray-800">{{ $product->name }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ number_format($product->click_count) }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ number_format($product->order_click_count) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-400">
                                Tidak ada data produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 text-sm text-gray-600">
            <span>
                Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }}
                of {{ $products->total() }} entries
            </span>
            <div class="flex items-center gap-1">
                {{-- Previous --}}
                <button wire:click="previousPage"
                        @disabled(!$products->onFirstPage())
                        class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    Previous
                </button>

                {{-- Page numbers --}}
                @php
                    $currentPage  = $products->currentPage();
                    $lastPage     = $products->lastPage();
                    $window       = 2;
                    $pages        = collect(range(
                        max(1, $currentPage - $window),
                        min($lastPage, $currentPage + $window)
                    ));
                @endphp

                @if ($pages->first() > 1)
                    <button wire:click="gotoPage(1)"
                            class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 transition">1</button>
                    @if ($pages->first() > 2)
                        <span class="px-2 text-gray-400">…</span>
                    @endif
                @endif

                @foreach ($pages as $page)
                    <button wire:click="gotoPage({{ $page }})"
                            class="px-3 py-1.5 rounded border transition
                                   {{ $page === $currentPage
                                        ? 'bg-cyan-500 border-cyan-500 text-white font-semibold'
                                        : 'border-gray-300 hover:bg-gray-50' }}">
                        {{ $page }}
                    </button>
                @endforeach

                @if ($pages->last() < $lastPage)
                    @if ($pages->last() < $lastPage - 1)
                        <span class="px-2 text-gray-400">…</span>
                    @endif
                    <button wire:click="gotoPage({{ $lastPage }})"
                            class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 transition">
                        {{ $lastPage }}
                    </button>
                @endif

                {{-- Next --}}
                <button wire:click="nextPage"
                        @disabled(!$products->hasMorePages())
                        class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    Next
                </button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('livewire:navigated', renderChart);
    document.addEventListener('DOMContentLoaded', renderChart);

    function renderChart() {
        const chartData = @json($chartData);

        const categories = chartData.map(t => t.name);
        const productClicks = chartData.map(t => parseInt(t.total_product_clicks) || 0);
        const orderClicks   = chartData.map(t => parseInt(t.total_order_clicks) || 0);

        Highcharts.chart('clickCountChart', {
            chart: { type: 'column', backgroundColor: 'transparent' },
            title: { text: 'Click Counts', style: { fontSize: '14px', color: '#374151' } },
            xAxis: {
                categories: categories,
                title: { text: 'Product Type', style: { color: '#6b7280' } },
                labels: { style: { color: '#6b7280' } },
            },
            yAxis: {
                min: 0,
                title: { text: 'Total Clicks', style: { color: '#6b7280' } },
                labels: {
                    style: { color: '#6b7280' },
                    formatter: function () {
                        return this.value >= 1000 ? (this.value / 1000) + 'k' : this.value;
                    }
                },
                gridLineColor: '#f3f4f6',
            },
            legend: {
                symbolRadius: 50,
                itemStyle: { color: '#6b7280', fontWeight: 'normal' },
            },
            series: [
                { name: 'Product Clicks', data: productClicks, color: '#38bdf8' },
                { name: 'Order Clicks',   data: orderClicks,   color: '#f87171' },
            ],
            credits: { enabled: true },
            plotOptions: {
                column: { borderRadius: 4, pointPadding: 0.1, groupPadding: 0.15 }
            },
        });
    }
</script>
@endpush
