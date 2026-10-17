<x-app-layout>
    <x-slot name="header">Laporan & Statistik Platform</x-slot>

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Laporan & Statistik Platform</h1>
            <p class="text-sm text-slate-500 mt-1">Rekapitulasi total pendapatan transaksi tiket lunas dan performa masing-masing mitra travel.</p>
        </div>
        <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold px-4 py-2.5 rounded-lg transition-colors shadow-sm self-start sm:self-auto">
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
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-md flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-200">Total Transaksi Lunas</p>
                <p class="text-3xl font-black mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <p class="text-xs text-blue-100 mt-1">Dari seluruh mitra operator travel</p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-6 text-white shadow-md flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-200">Total Tiket Terjual</p>
                <p class="text-3xl font-black mt-1">{{ number_format($totalTickets, 0, ',', '.') }} Tiket</p>
                <p class="text-xs text-emerald-100 mt-1">Pemesanan penumpang berhasil</p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Tabel Rekapitulasi Per Operator -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
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
</x-app-layout>
