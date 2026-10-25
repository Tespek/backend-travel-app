<x-app-layout>
    <x-slot name="header">
        {{ auth()->user()->role === 'super_admin' ? 'Dashboard Super Admin' : 'Dashboard Operasional Travel' }}
    </x-slot>

    @if(auth()->user()->role === 'super_admin')
    {{-- ========================================================================= --}}
    {{-- ========================= DASHBOARD SUPER ADMIN ========================= --}}
    {{-- ========================================================================= --}}
    <div class="space-y-6">
        <!-- Hero Banner Super Admin -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-950 p-6 sm:p-8 text-white shadow-xl">
            <div class="relative z-10 max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 text-xs font-semibold mb-3 border border-blue-500/30 backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                    <span>Pusat Kendali Ekosistem Multi-Operator Travel</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold tracking-tight">
                    Selamat Datang, {{ auth()->user()->name }}!
                </h1>
                <p class="text-sm text-slate-300 mt-2 leading-relaxed">
                    Sistem memantau seluruh aktivitas reservasi tiket antar kota, penjemputan penumpang real-time, dan status verifikasi legalitas mitra operator travel.
                </p>
            </div>
            <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-primary-600/10 blur-3xl pointer-events-none"></div>
        </div>

        <!-- Metric Cards Super Admin -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Operator -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-soft hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Mitra Operator</p>
                        <h3 class="text-2xl font-heading font-black text-slate-800 mt-1">{{ $stats['total_operators'] ?? 0 }}</h3>
                        <a href="{{ route('super-admin.operators.index') }}" class="text-[11px] font-semibold text-primary-600 hover:text-primary-700 hover:underline mt-2 inline-flex items-center gap-1">
                            Kelola Operator
                            <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Menunggu Verifikasi -->
            <div class="bg-white rounded-2xl p-5 border border-amber-200/80 shadow-soft hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Verifikasi Tertunda</p>
                        <h3 class="text-2xl font-heading font-black text-amber-600 mt-1">{{ $stats['pending_operators'] ?? 0 }}</h3>
                        <a href="{{ route('super-admin.operators.index', ['status' => 'pending']) }}" class="text-[11px] font-semibold text-amber-700 hover:underline mt-2 inline-flex items-center gap-1">
                            Tinjau Dokumen KTP/SIUP
                            <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Pengguna -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-soft hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Pengguna</p>
                        <h3 class="text-2xl font-heading font-black text-slate-800 mt-1">{{ $stats['total_users'] ?? 0 }}</h3>
                        <a href="{{ route('super-admin.users.index') }}" class="text-[11px] font-semibold text-indigo-600 hover:underline mt-2 inline-flex items-center gap-1">
                            Kelola Akun
                            <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Reservasi -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-soft hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Reservasi</p>
                        <h3 class="text-2xl font-heading font-black text-slate-800 mt-1">{{ $stats['total_bookings'] ?? 0 }}</h3>
                        <a href="{{ route('admin-travel.bookings.index') }}" class="text-[11px] font-semibold text-emerald-600 hover:underline mt-2 inline-flex items-center gap-1">
                            Lihat Transaksi
                            <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Perlu Verifikasi Segera -->
        @if(isset($pendingOperators) && $pendingOperators->count() > 0)
        <div class="bg-white rounded-2xl border border-amber-200/90 shadow-soft overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-amber-50 to-orange-50/40 border-b border-amber-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                    </span>
                    <div>
                        <h2 class="text-sm font-heading font-bold text-amber-950">
                            Perlu Verifikasi Segera ({{ $pendingOperators->count() }} Operator Menunggu)
                        </h2>
                        <p class="text-[11px] text-amber-700">Tinjau kelengkapan berkas SIUP, KTP pemilik, dan rekening bank mitra travel.</p>
                    </div>
                </div>
                <a href="{{ route('super-admin.operators.index', ['status' => 'pending']) }}" class="text-xs font-bold text-amber-800 hover:text-amber-950 hover:underline">
                    Lihat Semua →
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($pendingOperators as $pOp)
                <div class="p-4 sm:px-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50/80 transition-colors">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 font-heading font-bold text-sm flex items-center justify-center flex-shrink-0">
                            {{ strtoupper(substr($pOp->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 text-sm">{{ $pOp->name }}</p>
                            <p class="text-xs text-slate-500">Pemilik: <span class="font-medium text-slate-700">{{ $pOp->owner_name }}</span> · {{ $pOp->phone ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('super-admin.operators.show', $pOp) }}"
                           class="px-4 py-2 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold transition-colors shadow-xs">
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
    {{-- ========================================================================= --}}
    {{-- ========================= DASHBOARD ADMIN TRAVEL ======================== --}}
    {{-- ========================================================================= --}}
    <div class="space-y-7">

        <!-- Banner Status Verifikasi Akun -->
        @if(isset($operator))
            @if($operator->verification_status === 'pending')
            <div class="p-5 rounded-2xl bg-amber-50/90 border border-amber-200 text-amber-900 shadow-sm flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-200 text-amber-800 uppercase tracking-wider">Menunggu Verifikasi</span>
                    </div>
                    <p class="text-xs text-amber-800 mt-1 leading-relaxed">
                        Akun operator travel Anda sedang dalam proses peninjauan oleh Super Admin. Anda tetap dapat menginput armada mobil, rute, pool, dan jadwal perjalanan sembari menunggu proses persetujuan.
                    </p>
                </div>
                <a href="{{ route('admin-travel.operator-profile.show') }}" class="px-3.5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold transition-colors flex-shrink-0">
                    Lihat Profil Travel
                </a>
            </div>
            @elseif($operator->verification_status === 'rejected')
            <div class="p-5 rounded-2xl bg-rose-50/90 border border-rose-200 text-rose-900 shadow-sm flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-xs uppercase tracking-wider text-rose-800">Pendaftaran Akun Belum Disetujui</p>
                    <p class="text-xs text-rose-700 mt-1 leading-relaxed">{{ $operator->rejection_reason ?? 'Mohon perbarui informasi profil travel Anda atau hubungi Super Admin untuk aktivasi akun operator.' }}</p>
                </div>
                <a href="{{ route('admin-travel.operator-profile.show') }}" class="px-3.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold transition-colors flex-shrink-0">
                    Perbarui Profil
                </a>
            </div>
            @endif
        @endif

        <!-- Welcome Hero Banner Travel -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-700 via-primary-600 to-indigo-800 p-6 sm:p-8 text-white shadow-xl shadow-primary-600/15">
            <div class="relative z-10 max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 text-white text-xs font-semibold mb-3 border border-white/20 backdrop-blur-xs">
                    <svg class="w-3.5 h-3.5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Pusat Operasional Mitra Travel Resmi</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold tracking-tight">
                    {{ $operator->name ?? 'Portal Admin Travel' }}
                </h1>
                <p class="text-xs sm:text-sm text-blue-100 mt-2 leading-relaxed">
                    Kelola armada mobil, buka jadwal tiket antar kota, koordinasikan penjemputan penumpang door-to-door, dan pantau performa omset tiket Anda secara real-time.
                </p>
                
                <div class="mt-5 flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin-travel.schedules.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white text-primary-600 hover:bg-blue-50 font-bold text-xs shadow-md transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Buka Jadwal Baru
                    </a>
                    <a href="{{ route('admin-travel.bookings.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs border border-white/20 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Semua Reservasi
                    </a>
                </div>
            </div>
            <!-- Decorative Vector Elements -->
            <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-radial from-white/10 to-transparent pointer-events-none"></div>
            <div class="absolute -right-8 -bottom-8 w-60 h-60 rounded-full bg-indigo-500/20 blur-3xl pointer-events-none"></div>
        </div>

        <!-- ==================== 1. VISUAL STAT CARDS ==================== -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-5">
            
            <!-- Card 1: Total Reservasi Tiket -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-soft hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Reservasi</span>
                    <span class="p-2 rounded-xl bg-blue-50 text-primary-600 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    </span>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-heading font-black text-slate-800">{{ number_format($stats['total_bookings'] ?? 0) }}</h3>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            Tiket Terbit
                        </span>
                        <a href="{{ route('admin-travel.bookings.index') }}" class="text-[11px] font-semibold text-primary-600 hover:underline">Detail →</a>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Pendapatan Tiket -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-soft hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Omset Lunas</span>
                    <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </div>
                <div class="mt-3">
                    <h3 class="text-xl font-heading font-black text-slate-800 truncate" title="Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}">
                        Rp {{ number_format(($stats['total_revenue'] ?? 0) / 1000, 0, ',', '.') }}k
                    </h3>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                            Status Paid
                        </span>
                        <span class="text-[10px] text-slate-400">Total Bersih</span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Armada Mobil -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-soft hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Armada Mobil</span>
                    <span class="p-2 rounded-xl bg-indigo-50 text-indigo-600 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8m-8 4h8m-4 4h4m-8 0H8m0 0H5.2A2.2 2.2 0 013 12.8V6.2A2.2 2.2 0 015.2 4h13.6A2.2 2.2 0 0121 6.2v6.6a2.2 2.2 0 01-2.2 2.2H16"/></svg>
                    </span>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-heading font-black text-slate-800">{{ $stats['total_vehicles'] ?? 0 }}</h3>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full">Unit Siap</span>
                        <a href="{{ route('admin-travel.vehicles.index') }}" class="text-[11px] font-semibold text-indigo-600 hover:underline">Kelola →</a>
                    </div>
                </div>
            </div>

            <!-- Card 4: Jadwal Aktif -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-soft hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Jadwal Jalan</span>
                    <span class="p-2 rounded-xl bg-amber-50 text-amber-600 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-heading font-black text-slate-800">{{ $stats['total_schedules'] ?? 0 }}</h3>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">Terbuka</span>
                        <a href="{{ route('admin-travel.schedules.index') }}" class="text-[11px] font-semibold text-amber-600 hover:underline">Lihat →</a>
                    </div>
                </div>
            </div>

            <!-- Card 5: Driver Travel -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-soft hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Driver</span>
                    <span class="p-2 rounded-xl bg-teal-50 text-teal-600 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </span>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-heading font-black text-slate-800">{{ $stats['total_drivers'] ?? 0 }}</h3>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-full">Driver Aktif</span>
                        <a href="{{ route('admin-travel.drivers.index') }}" class="text-[11px] font-semibold text-teal-600 hover:underline">Atur →</a>
                    </div>
                </div>
            </div>

        </div>

        <!-- ==================== 2. CHART.JS VISUAL GRAPHS ==================== -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Chart 1: Line Chart (Tren Pemesanan 6 Bulan Terakhir) -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-soft">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-heading font-bold text-slate-800">Tren Pemesanan Tiket</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Grafik dinamika reservasi tiket penumpang selama 6 bulan terakhir.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-primary-50 text-primary-600 border border-primary-200/60">
                            <span class="w-2 h-2 rounded-full bg-primary-600"></span>
                            Tiket Terjual
                        </span>
                    </div>
                </div>
                <div class="mt-4 relative h-64 sm:h-72">
                    <canvas id="monthlyBookingsChart"></canvas>
                </div>
            </div>

            <!-- Chart 2: Doughnut Chart (Status Pembayaran) -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-soft flex flex-col justify-between">
                <div>
                    <div class="pb-4 border-b border-slate-100">
                        <h2 class="text-base font-heading font-bold text-slate-800">Status Pembayaran</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Komposisi status pembayaran tiket masuk sistem.</p>
                    </div>
                    <div class="mt-4 relative h-48 sm:h-52 flex items-center justify-center">
                        <canvas id="paymentStatusChart"></canvas>
                    </div>
                </div>

                <!-- Custom Legend / Summary -->
                <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-3 gap-2 text-center">
                    <div class="p-2 rounded-xl bg-emerald-50 border border-emerald-100">
                        <p class="text-[10px] font-bold text-emerald-800 uppercase">Lunas</p>
                        <p class="text-sm font-black text-emerald-600 mt-0.5">{{ $paymentStats['paid'] ?? 0 }}</p>
                    </div>
                    <div class="p-2 rounded-xl bg-amber-50 border border-amber-100">
                        <p class="text-[10px] font-bold text-amber-800 uppercase">Pending</p>
                        <p class="text-sm font-black text-amber-600 mt-0.5">{{ $paymentStats['pending'] ?? 0 }}</p>
                    </div>
                    <div class="p-2 rounded-xl bg-rose-50 border border-rose-100">
                        <p class="text-[10px] font-bold text-rose-800 uppercase">Batal</p>
                        <p class="text-sm font-black text-rose-600 mt-0.5">{{ $paymentStats['cancelled'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- ==================== 3. DATA TABLES GRID ==================== -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Tabel 1: Pemesanan Terbaru (2 Kolom di Desktop) -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-soft overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-heading font-bold text-slate-800">Reservasi Tiket Terbaru</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Pantau penumpang yang baru saja memesan perjalanan.</p>
                    </div>
                    <a href="{{ route('admin-travel.bookings.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary-600 hover:text-primary-700 hover:underline">
                        Lihat Semua
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 font-bold uppercase tracking-wider">
                                <th class="py-3 px-4">Kode & Penumpang</th>
                                <th class="py-3 px-4">Rute Perjalanan</th>
                                <th class="py-3 px-4">Biaya</th>
                                <th class="py-3 px-4">Status Bayar</th>
                                <th class="py-3 px-4">Penjemputan</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentBookings ?? [] as $bk)
                            <tr class="hover:bg-slate-50/90 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="font-mono font-bold text-primary-600">{{ $bk->booking_code }}</span>
                                    <p class="font-semibold text-slate-800 mt-0.5">{{ $bk->passenger_name }}</p>
                                    <p class="text-[11px] text-slate-400 font-normal">{{ $bk->passenger_phone }}</p>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-1 font-semibold text-slate-800">
                                        <span>{{ $bk->schedule->route->origin_name ?? '-' }}</span>
                                        <span class="text-slate-400">→</span>
                                        <span>{{ $bk->schedule->route->destination_name ?? '-' }}</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        Kursi: <span class="font-bold text-slate-700">{{ $bk->seat_number ?? '-' }}</span>
                                    </p>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    Rp {{ number_format($bk->total_price ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($bk->payment_status === 'paid')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Lunas
                                        </span>
                                    @elseif($bk->payment_status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            {{ ucfirst($bk->payment_status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700">
                                        {{ str_replace('_', ' ', ucfirst($bk->pickup_status ?? 'waiting')) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <a href="{{ route('admin-travel.bookings.show', $bk) }}" class="p-1.5 rounded-lg text-primary-600 hover:bg-primary-50 transition-colors inline-block" title="Lihat Rincian Tiket">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        <p class="font-medium text-xs">Belum ada data reservasi tiket</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Jadwal Keberangkatan Terdekat (1 Kolom) -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-heading font-bold text-slate-800">Keberangkatan Terdekat</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Mobil dan rute yang akan segera jalan.</p>
                        </div>
                        <a href="{{ route('admin-travel.schedules.create') }}" class="p-1.5 rounded-lg bg-primary-50 text-primary-600 hover:bg-primary-100 transition-colors" title="Tambah Jadwal">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100 text-xs">
                        @forelse($upcomingSchedules ?? [] as $sch)
                        <div class="p-4 hover:bg-slate-50/80 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5 font-bold text-slate-900">
                                    <span>{{ $sch->route->origin_name ?? '-' }}</span>
                                    <span class="text-slate-400">→</span>
                                    <span>{{ $sch->route->destination_name ?? '-' }}</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $sch->status === 'open' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                    {{ ucfirst($sch->status) }}
                                </span>
                            </div>

                            <div class="mt-2 flex items-center justify-between text-slate-500">
                                <span class="font-mono text-slate-700 font-semibold bg-slate-100 px-2 py-0.5 rounded">
                                    {{ $sch->vehicle->plate_number ?? '-' }}
                                </span>
                                <span class="font-bold text-primary-600">
                                    {{ \Carbon\Carbon::parse($sch->departure_date)->isoFormat('D MMM') }} · {{ substr($sch->departure_time, 0, 5) }} WIB
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="p-8 text-center text-slate-400">
                            <p class="text-xs font-medium">Belum ada jadwal perjalanan aktif.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    <a href="{{ route('admin-travel.schedules.index') }}" class="w-full block text-center py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 shadow-2xs transition-colors">
                        Lihat Seluruh Jadwal Perjalanan
                    </a>
                </div>
            </div>

        </div>

    </div>

    <!-- ==================== CHART.JS INITIALIZATION SCRIPT ==================== -->
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Data dari Backend Controller
            const months = {!! json_encode($chartMonths ?? ['Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt']) !!};
            const bookingsData = {!! json_encode($chartBookings ?? [0, 0, 0, 0, 0, 0]) !!};
            const paymentStats = {!! json_encode($paymentStats ?? ['paid' => 0, 'pending' => 0, 'cancelled' => 0]) !!};

            // 1. Line Chart: Tren Pemesanan Tiket (6 Bulan)
            const ctxMonthly = document.getElementById('monthlyBookingsChart');
            if (ctxMonthly) {
                const gradient = ctxMonthly.getContext('2d').createLinearGradient(0, 0, 0, 280);
                gradient.addColorStop(0, 'rgba(37, 99, 235, 0.28)');
                gradient.addColorStop(1, 'rgba(37, 99, 235, 0.00)');

                new Chart(ctxMonthly, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Pemesanan Tiket',
                            data: bookingsData,
                            borderColor: '#2563EB',
                            borderWidth: 3,
                            fill: true,
                            backgroundColor: gradient,
                            tension: 0.38,
                            pointBackgroundColor: '#FFFFFF',
                            pointBorderColor: '#2563EB',
                            pointBorderWidth: 2.5,
                            pointRadius: 4.5,
                            pointHoverRadius: 7,
                            pointHoverBackgroundColor: '#2563EB',
                            pointHoverBorderColor: '#FFFFFF',
                            pointHoverBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: 'bold' },
                                bodyFont: { family: 'Inter', size: 12 },
                                padding: 12,
                                cornerRadius: 10,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.y + ' Tiket Dipesan';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: {
                                    font: { family: 'Inter', size: 11 },
                                    color: '#64748b'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                border: { dash: [5, 5] },
                                grid: { color: '#f1f5f9' },
                                ticks: {
                                    font: { family: 'Inter', size: 11 },
                                    color: '#64748b',
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }

            // 2. Doughnut Chart: Komposisi Status Pembayaran
            const ctxPayment = document.getElementById('paymentStatusChart');
            if (ctxPayment) {
                const totalPayment = (paymentStats.paid || 0) + (paymentStats.pending || 0) + (paymentStats.cancelled || 0);

                new Chart(ctxPayment, {
                    type: 'doughnut',
                    data: {
                        labels: ['Lunas (Paid)', 'Menunggu (Pending)', 'Batal/Gagal'],
                        datasets: [{
                            data: [
                                paymentStats.paid || 0,
                                paymentStats.pending || 0,
                                paymentStats.cancelled || 0
                            ],
                            backgroundColor: [
                                '#10B981', // Emerald
                                '#F59E0B', // Amber
                                '#EF4444'  // Rose/Red
                            ],
                            borderWidth: 3,
                            borderColor: '#FFFFFF',
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                titleFont: { family: 'Plus Jakarta Sans', size: 12 },
                                bodyFont: { family: 'Inter', size: 12 },
                                padding: 10,
                                cornerRadius: 10,
                                callbacks: {
                                    label: function(context) {
                                        const count = context.parsed;
                                        const percentage = totalPayment > 0 ? Math.round((count / totalPayment) * 100) : 0;
                                        return ` ${context.label}: ${count} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush

    @endif
</x-app-layout>
