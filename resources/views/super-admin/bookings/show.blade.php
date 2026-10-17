<x-app-layout>
    <x-slot name="header">Detail Transaksi: #{{ $booking->booking_code }}</x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                    <a href="{{ route('super-admin.bookings.index') }}" class="hover:text-blue-600 transition-colors">Semua Pemesanan</a>
                    <span>/</span>
                    <span class="text-slate-800 font-medium">#{{ $booking->booking_code }}</span>
                </nav>
                <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                    Tiket #{{ $booking->booking_code }}
                    @if($booking->payment_status === 'paid')
                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Lunas
                        </span>
                    @elseif($booking->payment_status === 'pending')
                        <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span> Pending
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    @endif
                </h1>
            </div>
            <a href="{{ route('super-admin.bookings.index') }}" class="inline-flex items-center gap-1 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors self-start sm:self-auto">
                ← Kembali ke Daftar
            </a>
        </div>

        <!-- Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Data Tiket & Perjalanan -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-3.5 text-sm">
                <h2 class="font-bold text-slate-800 border-b border-slate-100 pb-2.5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    Informasi Tiket & Perjalanan
                </h2>
                <div class="flex justify-between py-1 border-b border-slate-100 text-xs">
                    <span class="text-slate-500">Operator Travel:</span>
                    <a href="{{ route('super-admin.operators.show', $booking->operator) }}" class="font-bold text-blue-600 hover:underline">
                        {{ $booking->operator->name ?? '-' }}
                    </a>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100 text-xs">
                    <span class="text-slate-500">Rute:</span>
                    <span class="font-semibold text-slate-800">
                        {{ $booking->schedule->route->originCity->name ?? '-' }} → {{ $booking->schedule->route->destinationCity->name ?? '-' }}
                    </span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100 text-xs">
                    <span class="text-slate-500">Waktu Berangkat:</span>
                    <span class="font-bold text-blue-600">
                        {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->isoFormat('dddd, D MMMM Y · HH:mm') }} WIB
                    </span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100 text-xs">
                    <span class="text-slate-500">Armada Mobil:</span>
                    <span class="font-semibold text-slate-800">
                        {{ $booking->schedule->vehicle->brand ?? 'Armada' }} ({{ $booking->schedule->vehicle->plate_number ?? '-' }})
                    </span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100 text-xs">
                    <span class="text-slate-500">Nomor Kursi:</span>
                    <span class="font-mono font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded">{{ $booking->seat_number ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-1 text-xs">
                    <span class="text-slate-500">Tarif Total:</span>
                    <span class="text-base font-extrabold text-blue-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Data Penumpang & Driver -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-3.5 text-sm">
                <h2 class="font-bold text-slate-800 border-b border-slate-100 pb-2.5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Penumpang & Penugasan Driver
                </h2>
                <div class="flex justify-between py-1 border-b border-slate-100 text-xs">
                    <span class="text-slate-500">Nama Penumpang:</span>
                    <span class="font-bold text-slate-900">{{ $booking->passenger_name }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100 text-xs">
                    <span class="text-slate-500">No. Telepon Penumpang:</span>
                    <span class="font-semibold text-slate-800">{{ $booking->passenger_phone }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100 text-xs">
                    <span class="text-slate-500">Driver Bertugas:</span>
                    @if($booking->driver)
                        <a href="{{ route('super-admin.users.show', $booking->driver) }}" class="font-bold text-indigo-600 hover:underline">
                            {{ $booking->driver->name }} ({{ $booking->driver->phone ?? '-' }})
                        </a>
                    @else
                        <span class="text-slate-400 italic">Belum Ditugaskan</span>
                    @endif
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100 text-xs">
                    <span class="text-slate-500">Status Penjemputan:</span>
                    <span class="font-bold text-slate-900 capitalize">{{ str_replace('_', ' ', $booking->pickup_status) }}</span>
                </div>
                <div class="flex justify-between py-1 text-xs">
                    <span class="text-slate-500">Status Pembayaran:</span>
                    <span class="font-bold uppercase {{ $booking->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $booking->payment_status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Detail Lokasi Penjemputan & Pengantaran -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Titik Jemput -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 text-xs">
                <h3 class="font-bold text-slate-800 text-sm mb-3 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Titik Penjemputan
                </h3>
                <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-100 space-y-1.5">
                    <p class="font-bold text-slate-900 text-xs">
                        {{ $booking->pickupPoint->name ?? ($booking->pickup_location_address ?? 'Titik jemput standar') }}
                    </p>
                    <p class="text-slate-600 leading-relaxed">
                        {{ $booking->pickupPoint->address ?? ($booking->pickup_location_address ?? 'Tidak ada alamat tertulis') }}
                    </p>
                    @if($booking->pickup_latitude && $booking->pickup_longitude)
                        <p class="text-slate-400 font-mono text-[11px] pt-1">
                            GPS: {{ $booking->pickup_latitude }}, {{ $booking->pickup_longitude }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Titik Pengantaran / Dropoff -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 text-xs">
                <h3 class="font-bold text-slate-800 text-sm mb-3 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Titik Tujuan / Dropoff
                </h3>
                <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-100 space-y-1.5">
                    <p class="font-bold text-slate-900 text-xs">
                        {{ $booking->destination->name ?? ($booking->destination_address ?? 'Pool Kota Tujuan') }}
                    </p>
                    <p class="text-slate-600 leading-relaxed">
                        {{ $booking->destination->address ?? ($booking->destination_address ?? ($booking->schedule->route->destinationCity->name ?? '-')) }}
                    </p>
                    @if($booking->dropoff_latitude && $booking->dropoff_longitude)
                        <p class="text-slate-400 font-mono text-[11px] pt-1">
                            GPS: {{ $booking->dropoff_latitude }}, {{ $booking->dropoff_longitude }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Log Riwayat Status Penjemputan -->
        @if($booking->statusLogs && $booking->statusLogs->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="font-bold text-slate-800 text-sm mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Kronologis / Log Riwayat Status Penjemputan
            </h3>
            <div class="space-y-4">
                @foreach($booking->statusLogs as $log)
                <div class="flex items-start gap-3 text-xs">
                    <div class="w-2 h-2 rounded-full bg-blue-600 mt-1.5 flex-shrink-0"></div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-800 capitalize">{{ str_replace('_', ' ', $log->status) }}</span>
                            <span class="text-slate-400 text-[11px]">{{ $log->created_at->isoFormat('D MMM Y · HH:mm') }} WIB</span>
                        </div>
                        @if($log->notes)
                            <p class="text-slate-600 mt-0.5">{{ $log->notes }}</p>
                        @endif
                        <p class="text-[10px] text-slate-400 mt-0.5">Oleh: {{ $log->user->name ?? 'Sistem' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
