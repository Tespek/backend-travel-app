<x-app-layout>
    <x-slot name="header">Detail Pemesanan: #{{ $booking->booking_code }}</x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                    <a href="{{ route('admin-travel.bookings.index') }}" class="hover:text-blue-600 transition-colors">Pemesanan</a>
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
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span> Menunggu Pembayaran
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    @endif
                </h1>
            </div>
            <a href="{{ route('admin-travel.bookings.index') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors self-start sm:self-auto">
                ← Kembali ke Daftar
            </a>
        </div>

        <!-- Grid: Penugasan Driver & Status Update -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Penugasan Driver -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-800 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Penugasan Driver
                    </h2>
                    <p class="text-xs text-slate-500 mb-4">
                        Tugaskan driver aktif untuk melakukan penjemputan penumpang ini.
                    </p>

                    @if($booking->driver)
                    <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-200 mb-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white font-bold text-sm flex items-center justify-center flex-shrink-0">
                            {{ strtoupper(substr($booking->driver->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-900">{{ $booking->driver->name }}</p>
                            <p class="text-xs text-slate-600">{{ $booking->driver->phone ?? $booking->driver->email }}</p>
                        </div>
                    </div>
                    @else
                    <div class="p-3.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-xs mb-4">
                        Driver belum ditugaskan untuk pemesanan ini.
                    </div>
                    @endif
                </div>

                <form action="{{ route('admin-travel.bookings.assign-driver', $booking) }}" method="POST" class="pt-2">
                    @csrf
                    <div class="flex items-center gap-2">
                        <select name="driver_id" required class="flex-1 px-3 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Driver Armada --</option>
                            @foreach($availableDrivers as $d)
                                <option value="{{ $d->id }}" {{ $booking->driver_id == $d->id ? 'selected' : '' }}>
                                    {{ $d->name }} ({{ $d->phone ?? 'Tanpa no HP' }})
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-colors whitespace-nowrap">
                            Tugaskan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Update Status Operasional & Pembayaran Manual -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-800 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pembaruan Status Operasional
                    </h2>
                    <p class="text-xs text-slate-500 mb-4">
                        Ubah status penjemputan penumpang atau tandai pembayaran lunas (jika bayar tunai di pool).
                    </p>
                </div>

                <form action="{{ route('admin-travel.bookings.update-status', $booking) }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Status Pembayaran</label>
                            <select name="payment_status" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="pending" {{ $booking->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $booking->payment_status === 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                                <option value="failed" {{ $booking->payment_status === 'failed' ? 'selected' : '' }}>Gagal</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Status Penjemputan</label>
                            <select name="pickup_status" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="waiting" {{ $booking->pickup_status === 'waiting' ? 'selected' : '' }}>Menunggu</option>
                                <option value="driver_assigned" {{ $booking->pickup_status === 'driver_assigned' ? 'selected' : '' }}>Driver Ditugaskan</option>
                                <option value="on_the_way" {{ $booking->pickup_status === 'on_the_way' ? 'selected' : '' }}>Menuju Lokasi</option>
                                <option value="arrived" {{ $booking->pickup_status === 'arrived' ? 'selected' : '' }}>Tiba di Lokasi</option>
                                <option value="picked_up" {{ $booking->pickup_status === 'picked_up' ? 'selected' : '' }}>Penumpang Naik</option>
                                <option value="completed" {{ $booking->pickup_status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $booking->pickup_status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-lg transition-colors">
                        Simpan Perubahan Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Detail Tiket & Rute -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Data Penumpang -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Informasi Penumpang</h3>
                <div class="space-y-2 text-xs">
                    <div>
                        <p class="text-slate-400">Nama Penumpang:</p>
                        <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $booking->passenger_name }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400">Nomor Telepon / WA:</p>
                        <p class="font-semibold text-slate-800 mt-0.5">{{ $booking->passenger_phone }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400">Email Pemesan:</p>
                        <p class="font-medium text-slate-700 mt-0.5">{{ $booking->passenger_email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400">Nomor Kursi:</p>
                        <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-800 font-mono font-bold text-xs rounded mt-0.5">
                            Kursi #{{ $booking->seat_number ?? '-' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-slate-400">Total Tarif Tiket:</p>
                        <p class="text-base font-extrabold text-blue-600 mt-0.5">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Detail Perjalanan & Mobil -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Rute & Mobil Armada</h3>
                <div class="space-y-2.5 text-xs">
                    <div>
                        <p class="text-slate-400">Rute Perjalanan:</p>
                        <p class="font-bold text-slate-900 text-sm mt-0.5">
                            {{ $booking->schedule->route->origin_name ?? '-' }} → {{ $booking->schedule->route->destination_name ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-400">Waktu Keberangkatan:</p>
                        <p class="font-bold text-blue-700 mt-0.5">
                            {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->isoFormat('dddd, D MMMM Y · HH:mm') }} WIB
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-400">Mobil Armada:</p>
                        <p class="font-semibold text-slate-800 mt-0.5">
                            {{ $booking->schedule->vehicle->brand ?? '-' }} ({{ $booking->schedule->vehicle->plate_number ?? '-' }})
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-400">Tipe Penjemputan:</p>
                        <span class="inline-block mt-0.5 px-2 py-0.5 bg-blue-50 text-blue-700 font-semibold rounded capitalize">
                            {{ $booking->pickup_type === 'door_to_door' ? 'Door to Door (Alamat Rumah)' : 'Pool to Pool' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Lokasi Penjemputan -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Lokasi Titik Jemput</h3>
                <div class="space-y-2 text-xs">
                    <div>
                        <p class="text-slate-400">Titik Jemput / Pool:</p>
                        <p class="font-bold text-slate-900 mt-0.5">
                            {{ $booking->pickupPoint->name ?? ($booking->pickup_address ? 'Alamat Penumpang' : '-') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-400">Alamat Lengkap:</p>
                        <p class="text-slate-700 leading-relaxed mt-0.5">
                            {{ $booking->pickup_address ?? $booking->pickupPoint->address ?? '-' }}
                        </p>
                    </div>
                    @if($booking->pickup_latitude && $booking->pickup_longitude)
                    <div class="pt-2">
                        <a href="https://www.google.com/maps?q={{ $booking->pickup_latitude }},{{ $booking->pickup_longitude }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 rounded-lg text-xs font-semibold transition-colors">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            Buka di Google Maps
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Log Riwayat Status Penjemputan -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-sm font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">
                Riwayat Log Penjemputan (Pickup Tracking Logs)
            </h3>
            <div class="divide-y divide-slate-100 text-xs">
                @forelse($booking->statusLogs ?? [] as $log)
                <div class="py-2.5 flex items-center justify-between">
                    <div>
                        <span class="font-semibold text-slate-800 capitalize">{{ str_replace('_', ' ', $log->status) }}</span>
                        <p class="text-slate-500 text-[11px] mt-0.5">{{ $log->note ?? '-' }}</p>
                    </div>
                    <span class="text-slate-400 text-[11px] font-mono">
                        {{ $log->created_at->isoFormat('D MMM Y · HH:mm:ss') }}
                    </span>
                </div>
                @empty
                <p class="text-slate-400 py-3 text-center">Belum ada riwayat perubahan status penjemputan.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
