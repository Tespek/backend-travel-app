<x-app-layout>
    <x-slot name="header">Laporan & Statistik Platform</x-slot>

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Laporan & Statistik Platform</h1>
            <p class="text-sm text-slate-500 mt-1">Rekapitulasi total pendapatan transaksi tiket lunas, bagi hasil komisi platform, dan performa mitra travel.</p>
        </div>
        <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold px-4 py-2.5 rounded-lg transition-colors shadow-sm self-start sm:self-auto print:hidden">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak Laporan
        </button>
    </div>

    <!-- Filter Bar (Operator & Tanggal) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6 p-4 print:hidden">
        <form action="{{ route('super-admin.reports.index') }}" method="GET" class="flex flex-col lg:flex-row items-end gap-3 justify-between">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 w-full lg:w-auto flex-1">
                <!-- Filter Mitra -->
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Mitra Operator</label>
                    <select name="operator_id" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Operator Mitra</option>
                        @foreach($operators as $op)
                            <option value="{{ $op->id }}" {{ $operatorId == $op->id ? 'selected' : '' }}>
                                {{ $op->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dari Tanggal -->
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           class="w-full px-3 py-1.5 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Sampai Tanggal -->
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           class="w-full px-3 py-1.5 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Submit & Reset Buttons -->
            <div class="flex items-center gap-2 self-end w-full sm:w-auto">
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors flex items-center justify-center gap-1.5 flex-1 sm:flex-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Terapkan Filter
                </button>
                @if($operatorId || $startDate || $endDate)
                <a href="{{ route('super-admin.reports.index') }}"
                   class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-lg transition-colors">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Ringkasan Kartu Metrik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <!-- Total Omset Transaksi Lunas -->
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-md flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-200">Total Transaksi Lunas</p>
                <p class="text-2xl lg:text-3xl font-black mt-1">Rp {{ number_format($totalRevenue ?? $totalGross ?? 0, 0, ',', '.') }}</p>
                <p class="text-xs text-blue-100 mt-1">Gross omset tiket lunas</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <!-- Total Tiket Terjual -->
        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-6 text-white shadow-md flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-200">Total Tiket Terjual</p>
                <p class="text-2xl lg:text-3xl font-black mt-1">{{ number_format($totalTickets ?? 0, 0, ',', '.') }} Tiket</p>
                <p class="text-xs text-emerald-100 mt-1">Pemesanan penumpang berhasil</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
        </div>

        <!-- Fee Platform (5%) -->
        <div class="bg-gradient-to-br from-violet-600 to-purple-700 rounded-2xl p-6 text-white shadow-md flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-purple-200">Komisi Platform (5%)</p>
                <p class="text-2xl lg:text-3xl font-black mt-1">Rp {{ number_format($totalPlatformFee ?? 0, 0, ',', '.') }}</p>
                <p class="text-xs text-purple-100 mt-1">Pendapatan fee sistem platform</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>

        <!-- Hak Bersih Mitra Operator -->
        <div class="bg-gradient-to-br from-amber-600 to-orange-700 rounded-2xl p-6 text-white shadow-md flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-amber-200">Hak Bersih Mitra</p>
                <p class="text-2xl lg:text-3xl font-black mt-1">Rp {{ number_format($totalNetOperators ?? 0, 0, ',', '.') }}</p>
                <p class="text-xs text-amber-100 mt-1">Setelah pemotongan komisi 5%</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Tabel Rekapitulasi Per Operator -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="p-5 border-b border-slate-100">
            <h2 class="text-base font-bold text-slate-800">Rekapitulasi Kinerja Mitra Operator Travel</h2>
            <p class="text-xs text-slate-500 mt-0.5">Statistik jumlah armada, rute, tiket terjual, dan akumulasi omset masing-masing operator.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">#</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Operator Travel</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Armada Mobil</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Rute Perjalanan</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Tiket Terjual</th>
                        <th class="text-right text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($operatorReports as $rep)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-6 py-4 text-slate-400 text-xs font-mono">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-900 text-sm">{{ $rep->name }}</span>
                            <p class="text-[11px] text-slate-400">{{ $rep->email }}</p>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-700">
                            {{ $rep->vehicles_count }} unit
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-700">
                            {{ $rep->routes_count }} rute
                        </td>
                        <td class="px-6 py-4 text-xs font-bold text-slate-800">
                            {{ $rep->paid_bookings_count }} tiket
                        </td>
                        <td class="px-6 py-4 text-right text-xs font-black text-emerald-600">
                            Rp {{ number_format($rep->total_revenue ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-400 text-xs">
                            Belum ada data operator travel.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Riwayat Transaksi Tiket Terkini (Filtered) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-base font-bold text-slate-800">Rincian Transaksi Tiket Terkini</h2>
                <p class="text-xs text-slate-500 mt-0.5">Daftar transaksi pemesanan tiket lunas sesuai filter yang diterapkan.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Kode Booking</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Operator</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Penumpang</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Rute Perjalanan</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Total Bayar</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Status</th>
                        <th class="text-right text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5 print:hidden">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($globalTransactions as $tx)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-mono font-bold text-slate-900 text-xs">#{{ $tx->booking_code }}</span>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $tx->created_at ? $tx->created_at->format('d/m/Y H:i') : '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-800 text-xs font-medium">
                                {{ $tx->operator->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-900 text-xs">{{ $tx->passenger_name }}</p>
                            <p class="text-slate-400 text-[11px]">{{ $tx->passenger_phone }}</p>
                        </td>
                        <td class="px-6 py-4 text-xs">
                            <p class="font-semibold text-slate-800">
                                {{ $tx->schedule->route->origin_name ?? '-' }} &rarr; {{ $tx->schedule->route->destination_name ?? '-' }}
                            </p>
                            <p class="text-[11px] text-blue-600">
                                Kursi No. {{ $tx->seat_number ?? '-' }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-xs font-bold text-slate-900">
                            Rp {{ number_format($tx->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Lunas
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right print:hidden">
                            <a href="{{ route('super-admin.bookings.show', $tx) }}"
                               class="inline-flex items-center text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-slate-400 text-xs">
                            Tidak ada data transaksi lunas pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($globalTransactions->hasPages())
        <div class="p-4 border-t border-slate-100 print:hidden">
            {{ $globalTransactions->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
