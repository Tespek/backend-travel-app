<x-app-layout>
    <x-slot name="header">Semua Pemesanan</x-slot>

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Semua Pemesanan Tiket</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau seluruh riwayat transaksi tiket dan status penjemputan dari seluruh mitra operator travel.</p>
        </div>
    </div>

    <!-- Filter Bar & Search -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6 p-4">
        <form action="{{ route('super-admin.bookings.index') }}" method="GET" class="flex flex-col lg:flex-row items-center justify-between gap-4">
            <!-- Filter Tabs -->
            <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                <a href="{{ route('super-admin.bookings.index', array_filter(['operator_id' => $operatorId, 'search' => $search])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ empty($paymentStatus) ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua ({{ $counts['all'] }})
                </a>
                <a href="{{ route('super-admin.bookings.index', array_filter(['payment_status' => 'paid', 'operator_id' => $operatorId, 'search' => $search])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $paymentStatus === 'paid' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                    Lunas ({{ $counts['paid'] }})
                </a>
                <a href="{{ route('super-admin.bookings.index', array_filter(['payment_status' => 'pending', 'operator_id' => $operatorId, 'search' => $search])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $paymentStatus === 'pending' ? 'bg-amber-500 text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                    Pending ({{ $counts['pending'] }})
                </a>
            </div>

            <!-- Operator Filter & Search -->
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <select name="operator_id" onchange="this.form.submit()"
                        class="w-full sm:w-48 px-3 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Operator</option>
                    @foreach($operators as $op)
                        <option value="{{ $op->id }}" {{ $operatorId == $op->id ? 'selected' : '' }}>
                            {{ $op->name }}
                        </option>
                    @endforeach
                </select>

                <div class="relative w-full sm:w-60">
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Cari kode booking / nama..."
                           class="w-full pl-9 pr-4 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </form>
    </div>

    <!-- Table of Bookings -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Kode Booking</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Operator</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Penumpang</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Rute & Waktu</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Tarif</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Status Bayar</th>
                        <th class="text-right text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bookings as $b)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-mono font-bold text-slate-900 text-xs">#{{ $b->booking_code }}</span>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $b->created_at->diffForHumans() }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-800 text-xs font-semibold">
                                {{ $b->operator->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-900 text-xs">{{ $b->passenger_name }}</p>
                            <p class="text-slate-400 text-[11px]">{{ $b->passenger_phone }}</p>
                        </td>
                        <td class="px-6 py-4 text-xs">
                            <p class="font-semibold text-slate-800">
                                {{ $b->schedule->route->originCity->name ?? '-' }} → {{ $b->schedule->route->destinationCity->name ?? '-' }}
                            </p>
                            <p class="text-[11px] text-blue-600">
                                {{ \Carbon\Carbon::parse($b->schedule->departure_time)->isoFormat('D MMM Y · HH:mm') }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-xs font-bold text-slate-900">
                            Rp {{ number_format($b->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($b->payment_status === 'paid')
                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Lunas
                                </span>
                            @elseif($b->payment_status === 'pending')
                                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span> Pending
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    {{ ucfirst($b->payment_status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('super-admin.bookings.show', $b) }}"
                               class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                                Detail →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-xs">
                            Belum ada pemesanan tiket yang tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
