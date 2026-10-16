<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    @if(auth()->user()->role === 'super_admin')
    {{-- ==================== DASHBOARD SUPER ADMIN ==================== --}}
    <div class="space-y-6">
        <!-- Welcome Hero -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-2xl p-6 sm:p-8 text-white shadow-md relative overflow-hidden">
            <div class="relative z-10 max-w-2xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 text-xs font-semibold mb-3 border border-blue-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span> Panel Super Admin Platform
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="text-sm text-slate-300 mt-2 leading-relaxed">
                    Pantau pertumbuhan mitra operator travel, verifikasi berkas legalitas pendaftaran, dan kelola operasional seluruh wilayah.
                </p>
            </div>
            <!-- Decorative Background Element -->
            <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-radial from-blue-600/10 to-transparent pointer-events-none"></div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <!-- Total Operator -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Mitra Operator</p>
                    <p class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total_operators'] ?? 0 }}</p>
                    <a href="{{ route('super-admin.operators.index') }}" class="text-[11px] text-blue-600 font-semibold hover:underline mt-1 inline-block">
                        Lihat Semua Mitra →
                    </a>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>

            <!-- Menunggu Verifikasi -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Menunggu Verifikasi</p>
                    <p class="text-2xl font-black text-amber-600 mt-1">{{ $stats['pending_operators'] ?? 0 }}</p>
                    <a href="{{ route('super-admin.operators.index', ['status' => 'pending']) }}" class="text-[11px] text-amber-700 font-semibold hover:underline mt-1 inline-block">
                        Tinjau Dokumen KTP/SIUP →
                    </a>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>

            <!-- Total Pengguna -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Pengguna</p>
                    <p class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total_users'] ?? 0 }}</p>
                    <span class="text-[11px] text-slate-400 mt-1 inline-block">Admin, Driver, Customer</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Total Pemesanan Tiket -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Reservasi</p>
                    <p class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total_bookings'] ?? 0 }}</p>
                    <span class="text-[11px] text-slate-400 mt-1 inline-block">Pemesanan seluruh operator</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Permohonan Operator Menunggu Verifikasi -->
        @if(isset($pendingOperators) && $pendingOperators->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
            <div class="p-4 sm:p-5 bg-amber-50/70 border-b border-amber-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span>
                    <h2 class="text-sm font-bold text-amber-900">Perlu Verifikasi Segera: {{ $pendingOperators->count() }} Operator Menunggu</h2>
                </div>
                <a href="{{ route('super-admin.operators.index', ['status' => 'pending']) }}" class="text-xs font-semibold text-amber-700 hover:text-amber-800">
                    Lihat Semua →
                </a>
            </div>
            <div class="divide-y divide-slate-100 text-sm">
                @foreach($pendingOperators as $pOp)
                <div class="p-4 sm:px-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50 transition-colors">
                    <div>
                        <p class="font-bold text-slate-900">{{ $pOp->name }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Pemilik: <span class="font-medium text-slate-700">{{ $pOp->owner_name }}</span> · {{ $pOp->email }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('super-admin.operators.show', $pOp) }}"
                           class="px-3.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition-colors">
                            Periksa Dokumen
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    @elseif(auth()->user()->role === 'admin_travel')
    {{-- ==================== DASHBOARD ADMIN TRAVEL ==================== --}}
    <div class="space-y-6">
        <!-- Status Verifikasi Banner jika bukan verified -->
        @if(isset($operator))
            @if($operator->verification_status === 'pending')
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-sm flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="flex-1">
                    <p class="font-bold text-xs uppercase tracking-wider text-amber-800">Status Akun: Menunggu Verifikasi</p>
                    <p class="text-xs text-amber-700 mt-0.5">
                        Dokumen legalitas operator Anda sedang diverifikasi oleh Super Admin. Anda dapat melengkapi armada, rute, dan jadwal sembari menunggu.
                    </p>
                </div>
                <a href="{{ route('admin-travel.operator-profile.show') }}" class="text-xs font-semibold text-amber-800 underline">
                    Cek Dokumen
                </a>
            </div>
            @elseif($operator->verification_status === 'rejected')
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-sm flex items-start gap-3">
                <svg class="w-5 h-5 text-rose-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <p class="font-bold text-xs uppercase tracking-wider text-rose-700">Perhatian: Pendaftaran Perlu Perbaikan Dokumen</p>
                    <p class="text-xs text-rose-800 mt-0.5">{{ $operator->rejection_reason ?? 'Mohon perbarui dokumen KTP/SIUP Anda.' }}</p>
                </div>
                <a href="{{ route('admin-travel.operator-profile.show') }}" class="px-3 py-1.5 rounded-lg bg-rose-600 text-white text-xs font-semibold hover:bg-rose-700">
                    Unggah Ulang
                </a>
            </div>
            @endif
        @endif

        <!-- Welcome Hero -->
        <div class="bg-gradient-to-r from-blue-700 via-blue-800 to-indigo-900 rounded-2xl p-6 sm:p-8 text-white shadow-md">
            <div class="max-w-2xl">
                <p class="text-xs font-semibold text-blue-200 uppercase tracking-wider">Panel Operasional Mitra</p>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight mt-1">{{ $operator->name ?? 'Admin Travel' }}</h1>
                <p class="text-xs sm:text-sm text-blue-100 mt-2">
                    Kelola kesiapan armada kendaraan, keberangkatan rute perjalanan, penugasan driver, dan reservasi tiket penumpang.
                </p>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-5">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Armada Mobil</p>
                <p class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total_vehicles'] ?? 0 }}</p>
                <a href="{{ route('admin-travel.vehicles.index') }}" class="text-[11px] text-blue-600 font-semibold hover:underline mt-1 inline-block">
                    Kelola Armada →
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-5">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Rute Aktif</p>
                <p class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total_routes'] ?? 0 }}</p>
                <a href="{{ route('admin-travel.routes.index') }}" class="text-[11px] text-blue-600 font-semibold hover:underline mt-1 inline-block">
                    Kelola Rute →
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-5">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Driver</p>
                <p class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total_drivers'] ?? 0 }}</p>
                <a href="{{ route('admin-travel.drivers.index') }}" class="text-[11px] text-blue-600 font-semibold hover:underline mt-1 inline-block">
                    Kelola Driver →
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-5">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Jadwal Perjalanan</p>
                <p class="text-2xl font-black text-blue-600 mt-1">{{ $stats['total_schedules'] ?? 0 }}</p>
                <a href="{{ route('admin-travel.schedules.index') }}" class="text-[11px] text-blue-600 font-semibold hover:underline mt-1 inline-block">
                    Jadwal Berangkat →
                </a>
            </div>
        </div>

        <!-- Jadwal Mendatang -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-800">Jadwal Keberangkatan Terdekat</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Pantau mobil dan rute yang akan segera diberangkatkan.</p>
                </div>
                <a href="{{ route('admin-travel.schedules.create') }}" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors">
                    + Tambah Jadwal
                </a>
            </div>
            <div class="divide-y divide-slate-100 text-sm">
                @forelse($upcomingSchedules ?? [] as $sch)
                <div class="p-4 sm:px-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50 transition-colors">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-800">{{ $sch->route->originCity->name ?? '-' }}</span>
                            <span class="text-slate-400">→</span>
                            <span class="font-bold text-slate-800">{{ $sch->route->destinationCity->name ?? '-' }}</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            Armada: <span class="font-mono font-semibold text-slate-700">{{ $sch->vehicle->plate_number ?? '-' }}</span> ({{ $sch->vehicle->brand ?? '-' }})
                        </p>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-xs font-bold text-blue-600">
                            {{ \Carbon\Carbon::parse($sch->departure_date)->isoFormat('dddd, D MMM Y') }} · {{ substr($sch->departure_time, 0, 5) }} WIB
                        </p>
                        <span class="inline-block mt-1 text-[11px] px-2 py-0.5 rounded-full {{ $sch->status === 'open' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($sch->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    Belum ada jadwal keberangkatan aktif. Klik tombol "+ Tambah Jadwal" untuk membuka jadwal tiket baru.
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif
</x-app-layout>
