<x-app-layout>
    <x-slot name="header">Pemesanan Tiket</x-slot>

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Pemesanan Tiket</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau transaksi tiket penumpang, status pembayaran, dan penugasan driver penjemputan.</p>
        </div>
    </div>

    <!-- Filter Bar & Search -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6 p-4">
        <form action="{{ route('admin-travel.bookings.index') }}" method="GET" class="flex flex-col lg:flex-row items-center justify-between gap-4">
            <!-- Filter Tabs -->
            <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                <a href="{{ route('admin-travel.bookings.index', array_filter(['search' => $search])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ empty($paymentStatus) && empty($pickupStatus) ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua ({{ $counts['all'] }})
                </a>
                <a href="{{ route('admin-travel.bookings.index', array_filter(['payment_status' => 'paid', 'search' => $search])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $paymentStatus === 'paid' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                    Lunas ({{ $counts['paid'] }})
                </a>
                <a href="{{ route('admin-travel.bookings.index', array_filter(['payment_status' => 'pending', 'search' => $search])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $paymentStatus === 'pending' ? 'bg-amber-500 text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                    Menunggu Bayar ({{ $counts['pending'] }})
                </a>
                <a href="{{ route('admin-travel.bookings.index', array_filter(['pickup_status' => 'completed', 'search' => $search])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $pickupStatus === 'completed' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                    Selesai ({{ $counts['completed'] }})
                </a>
            </div>

            <!-- Search Input -->
            <div class="w-full sm:w-72">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Cari kode booking, nama, HP..."
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
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Penumpang</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Rute & Jadwal</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Pembayaran</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Penjemputan</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Driver</th>
                        <th class="text-right text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bookings as $b)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-6 py-4">
                            <a href="{{ route('admin-travel.bookings.show', $b) }}"
                               class="font-mono font-bold text-blue-600 hover:text-blue-800 text-xs">
                                #{{ $b->booking_code }}
                            </a>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $b->created_at->diffForHumans() }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-900 text-sm">{{ $b->passenger_name }}</p>
                            <p class="text-xs text-slate-500">{{ $b->passenger_phone }}</p>
                            <span class="inline-block mt-0.5 text-[10px] font-mono font-bold bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded">
                                Kursi: {{ $b->seat_number ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5 text-xs font-bold text-slate-800">
                                <span>{{ $b->schedule->route->originCity->name ?? '-' }}</span>
                                <span class="text-slate-400">→</span>
                                <span>{{ $b->schedule->route->destinationCity->name ?? '-' }}</span>
                            </div>
                            <p class="text-xs text-blue-600 font-medium mt-0.5">
                                {{ \Carbon\Carbon::parse($b->schedule->departure_time)->isoFormat('D MMM Y · HH:mm') }} WIB
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900 text-xs">Rp {{ number_format($b->total_price, 0, ',', '.') }}</p>
                            @if($b->payment_status === 'paid')
                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-[11px] font-semibold px-2 py-0.5 rounded-full mt-1">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Lunas
                                </span>
                            @elseif($b->payment_status === 'pending')
                                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-[11px] font-semibold px-2 py-0.5 rounded-full mt-1">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span> Pending
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 text-[11px] font-semibold px-2 py-0.5 rounded-full mt-1">
                                    {{ ucfirst($b->payment_status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $pickupBadges = [
                                    'waiting'         => ['bg' => 'bg-amber-50 text-amber-700', 'text' => 'Menunggu'],
                                    'driver_assigned' => ['bg' => 'bg-blue-50 text-blue-700', 'text' => 'Driver Ditugaskan'],
                                    'on_the_way'      => ['bg' => 'bg-indigo-50 text-indigo-700', 'text' => 'Menuju Lokasi'],
                                    'arrived'         => ['bg' => 'bg-purple-50 text-purple-700', 'text' => 'Tiba di Lokasi'],
                                    'picked_up'       => ['bg' => 'bg-teal-50 text-teal-700', 'text' => 'Penumpang Naik'],
                                    'completed'       => ['bg' => 'bg-emerald-50 text-emerald-700', 'text' => 'Selesai'],
                                    'cancelled'       => ['bg' => 'bg-rose-50 text-rose-700', 'text' => 'Dibatalkan'],
                                ];
                                $badge = $pickupBadges[$b->pickup_status] ?? ['bg' => 'bg-slate-100 text-slate-700', 'text' => $b->pickup_status];
                            @endphp
                            <span class="inline-flex items-center text-[11px] font-semibold px-2.5 py-1 rounded-full {{ $badge['bg'] }}">
                                {{ $badge['text'] }}
                            </span>
                            <p class="text-[11px] text-slate-400 mt-0.5 capitalize">
                                {{ $b->pickup_type === 'door_to_door' ? 'Door to Door' : 'Pool to Pool' }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-xs">
                            @if($b->driver)
                                <p class="font-semibold text-slate-800">{{ $b->driver->name }}</p>
                                <p class="text-[11px] text-slate-400">{{ $b->driver->phone ?? '-' }}</p>
                            @else
                                <span class="text-slate-400 italic">Belum ditugaskan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin-travel.bookings.show', $b) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-semibold transition-colors">
                                Kelola
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="text-sm font-medium text-slate-600">Belum ada pemesanan tiket yang tercatat.</p>
                                <p class="text-xs text-slate-400 mt-1">Pemesanan dari aplikasi mobile customer akan muncul di sini secara otomatis.</p>
                            </div>
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
