<x-app-layout>
    <x-slot name="header">Laporan Keuangan</x-slot>

    {{-- =====================================================================
         PAGE HEADER
    ====================================================================== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Laporan Keuangan & Pendapatan</h1>
            <p class="text-sm text-slate-500 mt-1">
                Pantau pendapatan penjualan tiket secara mingguan, bulanan, dan tahunan.
            </p>
        </div>

        {{-- Export hint --}}
        <div class="flex items-center gap-2 text-xs text-slate-400">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Hanya tiket dengan status <span class="font-semibold text-emerald-600 mx-1">Lunas</span> yang dihitung.
        </div>
    </div>

    {{-- =====================================================================
         PERIOD SELECTOR
    ====================================================================== --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admin-travel.reports.index') }}"
              class="flex flex-wrap items-end gap-4">

            {{-- Tab: Pilih periode --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                    Periode
                </label>
                <div class="flex items-center gap-1 bg-slate-100 rounded-lg p-1">
                    @foreach(['weekly' => 'Minggu Ini', 'monthly' => 'Bulanan', 'yearly' => 'Tahunan'] as $key => $label)
                        <a href="{{ route('admin-travel.reports.index', array_merge(request()->query(), ['period' => $key])) }}"
                           class="px-4 py-1.5 rounded-md text-xs font-semibold transition-colors
                                  {{ $period === $key
                                      ? 'bg-emerald-600 text-white shadow-sm'
                                      : 'text-slate-600 hover:text-slate-900' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Month selector (only for monthly) --}}
            @if($period === 'monthly')
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                        Bulan
                    </label>
                    <select name="month"
                            onchange="this.form.submit()"
                            class="text-sm border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m)->isoFormat('MMMM') }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Year selector (monthly & yearly) --}}
            @if($period !== 'weekly')
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                        Tahun
                    </label>
                    <select name="year"
                            onchange="this.form.submit()"
                            class="text-sm border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @foreach(array_reverse($availableYears) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Active period label --}}
            <div class="flex-1 min-w-0">
                <p class="text-xs text-slate-400">Menampilkan data</p>
                <p class="text-sm font-semibold text-slate-700">
                    @if($period === 'weekly')
                        {{ $periodStart->isoFormat('D MMM') }} – {{ $periodEnd->isoFormat('D MMM YYYY') }}
                    @elseif($period === 'monthly')
                        {{ $periodStart->isoFormat('MMMM YYYY') }}
                    @else
                        Tahun {{ $year }}
                    @endif
                </p>
            </div>
        </form>
    </div>

    {{-- =====================================================================
         KPI CARDS
    ====================================================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

        {{-- Total Pendapatan (periode) --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                @if($revenueGrowth !== null)
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full
                                 {{ $revenueGrowth >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                        @if($revenueGrowth >= 0)
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                        {{ abs($revenueGrowth) }}%
                    </span>
                @endif
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Pendapatan</p>
            <p class="text-2xl font-bold text-slate-800 mt-0.5">
                Rp {{ number_format($periodRevenue, 0, ',', '.') }}
            </p>
            <p class="text-xs text-slate-400 mt-1">
                @if($period === 'weekly') Minggu ini
                @elseif($period === 'monthly') {{ $periodStart->isoFormat('MMMM YYYY') }}
                @else Tahun {{ $year }}
                @endif
            </p>
        </div>

        {{-- Jumlah Tiket Terjual --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Tiket Terjual</p>
            <p class="text-2xl font-bold text-slate-800 mt-0.5">
                {{ number_format($periodTickets, 0, ',', '.') }}
            </p>
            <p class="text-xs text-slate-400 mt-1">Tiket lunas terkonfirmasi</p>
        </div>

        {{-- Rata-rata Harga Tiket --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-violet-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2h2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Rata-rata Harga Tiket</p>
            <p class="text-2xl font-bold text-slate-800 mt-0.5">
                Rp {{ number_format($periodAvgTicket, 0, ',', '.') }}
            </p>
            <p class="text-xs text-slate-400 mt-1">Per tiket periode ini</p>
        </div>

        {{-- All-Time Revenue --}}
        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-xl shadow-sm p-5 text-white">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-white/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs font-medium text-emerald-100 uppercase tracking-wider">Total Pendapatan (Semua Waktu)</p>
            <p class="text-2xl font-bold mt-0.5">
                Rp {{ number_format($totalRevenueAllTime, 0, ',', '.') }}
            </p>
            <p class="text-xs text-emerald-100 mt-1">
                {{ number_format($totalTicketsAllTime, 0, ',', '.') }} tiket terjual secara kumulatif
            </p>
        </div>
    </div>

    {{-- =====================================================================
         REVENUE CHART + TOP ROUTES
    ====================================================================== --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

        {{-- Chart (2/3 width) --}}
        <div class="xl:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Grafik Pendapatan</h3>
                    <p class="text-xs text-slate-400 mt-0.5">
                        @if($period === 'weekly') Per hari, minggu ini
                        @elseif($period === 'monthly') Per hari — {{ $periodStart->isoFormat('MMMM YYYY') }}
                        @else Per bulan — Tahun {{ $year }}
                        @endif
                    </p>
                </div>
                <div class="flex items-center gap-3 text-xs text-slate-500">
                    <span class="flex items-center gap-1">
                        <span class="inline-block w-3 h-3 rounded-sm bg-emerald-500"></span> Pendapatan
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="inline-block w-3 h-3 rounded-sm bg-emerald-400"></span> Tiket
                    </span>
                </div>
            </div>
            <div class="relative" style="height: 280px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Top Routes (1/3 width) --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <h3 class="text-sm font-bold text-slate-800 mb-4">Rute Teratas</h3>

            @if($topRoutes->isEmpty())
                <div class="flex flex-col items-center justify-center py-10 text-slate-400">
                    <svg class="w-10 h-10 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    <p class="text-xs">Belum ada data rute</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($topRoutes as $idx => $item)
                        @php
                            $routeLabel = optional(optional(optional($item['route']->schedule)->route)->originCity)->name
                                . ' → '
                                . optional(optional(optional($item['route']->schedule)->route)->destinationCity)->name;
                            $pct = $periodRevenue > 0 ? round(($item['revenue'] / $periodRevenue) * 100) : 0;
                            $colors = ['bg-emerald-500','bg-teal-500','bg-green-500','bg-emerald-600','bg-teal-600'];
                            $color = $colors[$idx] ?? 'bg-slate-400';
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="text-[10px] font-bold text-white w-5 h-5 rounded-full {{ $color }} flex items-center justify-center flex-shrink-0">
                                        {{ $idx + 1 }}
                                    </span>
                                    <span class="text-xs font-medium text-slate-700 truncate">
                                        {{ $routeLabel ?: '—' }}
                                    </span>
                                </div>
                                <span class="text-xs font-semibold text-slate-500 ml-2 flex-shrink-0">{{ $pct }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5">
                                <div class="{{ $color }} h-1.5 rounded-full transition-all duration-500"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                            <div class="flex justify-between mt-0.5 text-[10px] text-slate-400">
                                <span>{{ $item['tickets'] }} tiket</span>
                                <span>Rp {{ number_format($item['revenue'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- =====================================================================
         TRANSACTION TABLE
    ====================================================================== --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Rincian Transaksi</h3>
                <p class="text-xs text-slate-400 mt-0.5">
                    Daftar tiket lunas pada periode ini — {{ $transactions->total() }} data
                </p>
            </div>
            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200">
                {{ $transactions->total() }} Transaksi
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-5 py-3">
                            Kode Booking
                        </th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-5 py-3">
                            Penumpang
                        </th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-5 py-3">
                            Rute
                        </th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-5 py-3">
                            Tanggal Bayar
                        </th>
                        <th class="text-right text-xs font-semibold text-slate-500 uppercase tracking-wider px-5 py-3">
                            Pendapatan
                        </th>
                        <th class="text-center text-xs font-semibold text-slate-500 uppercase tracking-wider px-5 py-3">
                            Metode
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $booking)
                        @php
                            $origin = optional(optional(optional($booking->schedule)->route)->originCity)->name ?? '—';
                            $destination = optional(optional(optional($booking->schedule)->route)->destinationCity)->name ?? '—';
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-3.5">
                                <a href="{{ route('admin-travel.bookings.show', $booking) }}"
                                   class="font-mono text-xs font-semibold text-emerald-600 hover:text-emerald-800 hover:underline">
                                    {{ $booking->booking_code }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="font-medium text-slate-700 text-xs">{{ $booking->passenger_name }}</p>
                                <p class="text-slate-400 text-[11px]">{{ $booking->passenger_phone }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-1.5 text-xs text-slate-700">
                                    <span>{{ $origin }}</span>
                                    <svg class="w-3 h-3 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    <span>{{ $destination }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="text-xs text-slate-700">
                                    {{ $booking->paid_at ? $booking->paid_at->isoFormat('D MMM YYYY') : '—' }}
                                </p>
                                <p class="text-[11px] text-slate-400">
                                    {{ $booking->paid_at ? $booking->paid_at->isoFormat('HH:mm') : '' }}
                                </p>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <span class="text-sm font-bold text-emerald-700">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($booking->payment_method)
                                    <span class="inline-block px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px] font-semibold uppercase">
                                        {{ $booking->payment_method }}
                                    </span>
                                @else
                                    <span class="text-slate-300 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-slate-400">
                                    <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium text-slate-500">Belum ada transaksi pada periode ini</p>
                                    <p class="text-xs">Coba pilih periode lain untuk melihat data.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @if($transactions->isNotEmpty())
                    <tfoot>
                        <tr class="bg-slate-50 border-t border-slate-200">
                            <td colspan="4" class="px-5 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                Total Halaman Ini
                            </td>
                            <td class="px-5 py-3 text-right">
                                <span class="text-sm font-bold text-slate-800">
                                    Rp {{ number_format($transactions->sum('total_price'), 0, ',', '.') }}
                                </span>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    {{-- =====================================================================
         CHART.JS SCRIPT
    ====================================================================== --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const labels   = @json($chartLabels);
        const revenues = @json($chartRevenues);
        const tickets  = @json($chartTickets);
        const period   = @json($period);

        const ctx = document.getElementById('revenueChart').getContext('2d');

        // Gradient fill for revenue bars
        const gradient = ctx.createLinearGradient(0, 0, 0, 280);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.85)');
        gradient.addColorStop(1, 'rgba(5, 150, 105, 0.35)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pendapatan (Rp)',
                        data: revenues,
                        backgroundColor: gradient,
                        borderColor: 'rgba(16, 185, 129, 0.9)',
                        borderWidth: 1.5,
                        borderRadius: 5,
                        yAxisID: 'yRevenue',
                        order: 2,
                    },
                    {
                        label: 'Jumlah Tiket',
                        data: tickets,
                        type: 'line',
                        borderColor: 'rgba(52, 211, 153, 0.9)',
                        backgroundColor: 'rgba(52, 211, 153, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(52, 211, 153, 1)',
                        pointRadius: period === 'monthly' ? 2 : 4,
                        tension: 0.4,
                        fill: false,
                        yAxisID: 'yTickets',
                        order: 1,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.92)',
                        titleColor: '#e2e8f0',
                        bodyColor: '#cbd5e1',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                if (context.datasetIndex === 0) {
                                    return ' Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                                return ' ' + context.parsed.y + ' tiket';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 10 },
                            maxRotation: period === 'monthly' ? 45 : 0,
                        }
                    },
                    yRevenue: {
                        type: 'linear',
                        position: 'left',
                        grid: { color: 'rgba(148, 163, 184, 0.12)' },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 10 },
                            callback: function (val) {
                                if (val >= 1_000_000) return 'Rp ' + (val / 1_000_000).toFixed(1) + ' Jt';
                                if (val >= 1_000)     return 'Rp ' + (val / 1_000).toFixed(0) + ' Rb';
                                return 'Rp ' + val;
                            }
                        },
                        beginAtZero: true,
                    },
                    yTickets: {
                        type: 'linear',
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: {
                            color: '#34d399',
                            font: { size: 10 },
                            precision: 0,
                        },
                        beginAtZero: true,
                    }
                }
            }
        });
    });
    </script>
</x-app-layout>
