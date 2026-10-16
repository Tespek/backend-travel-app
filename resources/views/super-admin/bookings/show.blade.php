<x-app-layout>
    <x-slot name="header">Detail Transaksi: #{{ $booking->booking_code }}</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                    <a href="{{ route('super-admin.bookings.index') }}" class="hover:text-blue-600">Semua Pemesanan</a>
                    <span>/</span>
                    <span class="text-slate-800 font-medium">#{{ $booking->booking_code }}</span>
                </nav>
                <h1 class="text-2xl font-bold text-slate-800">Tiket #{{ $booking->booking_code }}</h1>
            </div>
            <a href="{{ route('super-admin.bookings.index') }}" class="text-xs font-semibold px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg">
                ← Kembali
            </a>
        </div>

        <!-- Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Data Tiket -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-3 text-sm">
                <h2 class="font-bold text-slate-800 border-b pb-2">Informasi Tiket</h2>
                <div class="flex justify-between py-1 border-b border-slate-100 text-xs">
                    <span class="text-slate-500">Operator Travel:</span>
                    <span class="font-bold text-slate-900">{{ $booking->operator->name ?? '-' }}</span>
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
                    <span class="text-slate-500">Nomor Kursi:</span>
                    <span class="font-mono font-bold text-slate-800">{{ $booking->seat_number ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-1 text-xs">
                    <span class="text-slate-500">Tarif Tiket:</span>
                    <span class="text-base font-extrabold text-blue-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Data Penumpang & Penjemputan -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-3 text-sm">
                <h2 class="font-bold text-slate-800 border-b pb-2">Penumpang & Penjemputan</h2>
                <div class="flex justify-between py-1 border-b border-slate-100 text-xs">
                    <span class="text-slate-500">Nama Penumpang:</span>
                    <span class="font-bold text-slate-900">{{ $booking->passenger_name }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100 text-xs">
                    <span class="text-slate-500">No. Telepon:</span>
                    <span class="font-semibold text-slate-800">{{ $booking->passenger_phone }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100 text-xs">
                    <span class="text-slate-500">Driver Bertugas:</span>
                    <span class="font-semibold text-slate-800">{{ $booking->driver->name ?? 'Belum Ditugaskan' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100 text-xs">
                    <span class="text-slate-500">Status Penjemputan:</span>
                    <span class="font-bold text-slate-900 capitalize">{{ str_replace('_', ' ', $booking->pickup_status) }}</span>
                </div>
                <div class="flex justify-between py-1 text-xs">
                    <span class="text-slate-500">Status Bayar:</span>
                    <span class="font-bold uppercase {{ $booking->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $booking->payment_status }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
