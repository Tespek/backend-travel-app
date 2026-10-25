<x-app-layout>
    <x-slot name="header">Dashboard Super Admin</x-slot>

    <div class="space-y-7">

        <!-- ==================== HERO BANNER PLATFORM OWNER ==================== -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 sm:p-8 text-white shadow-xl border border-indigo-900/30">
            <div class="relative z-10 max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-semibold mb-3 border border-indigo-500/30 backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-violet-400 animate-pulse"></span>
                    <span>Pusat Kendali Ekosistem Multi-Operator Travel</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold tracking-tight">
                    Portal Tata Kelola & Analitik Platform
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 mt-2 leading-relaxed">
                    Pantau kinerja seluruh mitra travel, arus pendapatan komisi sistem, persetujuan pendaftaran mitra, serta kepatuhan armada dan operasional antar kota.
                </p>

                <!-- Quick Action Buttons -->
                <div class="mt-5 flex flex-wrap items-center gap-3">
                    @if(($stats['pending_operators'] ?? 0) > 0)
                    <a href="{{ route('super-admin.operators.index', ['status' => 'pending']) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-md shadow-amber-500/20 transition-all">
                        <span class="w-2 h-2 rounded-full bg-slate-950 animate-ping"></span>
                        Verifikasi {{ $stats['pending_operators'] }} Operator Tertunda
                    </a>
                    @endif
                    <a href="{{ route('super-admin.reports.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-md shadow-indigo-600/30 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Laporan Keuangan Platform
                    </a>
                    <a href="{{ route('super-admin.operators.create') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs border border-white/20 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Mitra Manual
                    </a>
                </div>
            </div>

            <!-- Background Ambient Glow -->
            <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-radial from-indigo-500/15 to-transparent pointer-events-none"></div>
            <div class="absolute -right-8 -bottom-8 w-64 h-64 rounded-full bg-violet-600/15 blur-3xl pointer-events-none"></div>
        </div>

        <!-- ==================== 1. PLATFORM LEVEL STAT CARDS ==================== -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Card 1: Total Pendapatan Platform (Fee) -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-soft hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Fee Platform</span>
                    <span class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-heading font-black text-slate-900 truncate" title="Rp {{ number_format($stats['total_platform_fee'] ?? 0, 0, ',', '.') }}">
                        Rp {{ number_format(($stats['total_platform_fee'] ?? 0) / 1000, 0, ',', '.') }}k
                    </h3>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                            Komisi Sistem
                        </span>
                        <a href="{{ route('super-admin.reports.index') }}" class="text-[11px] font-semibold text-indigo-600 hover:underline">
                            Audit →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Operator Terverifikasi -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-soft hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Mitra Travel Aktif</span>
                    <span class="w-11 h-11 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-heading font-black text-slate-900">
                        {{ $stats['verified_operators'] ?? 0 }} <span class="text-xs font-semibold text-slate-500">/ {{ $stats['total_operators'] ?? 0 }}</span>
                    </h3>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                            Terverifikasi
                        </span>
                        <a href="{{ route('super-admin.operators.index') }}" class="text-[11px] font-semibold text-violet-600 hover:underline">
                            Kelola →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 3: Operator Pending Verifikasi -->
            <div class="bg-white rounded-2xl p-5 border border-amber-200/80 shadow-soft hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-amber-800 uppercase tracking-wider">Menunggu Verifikasi</span>
                    <span class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-heading font-black text-amber-600">
                        {{ $stats['pending_operators'] ?? 0 }}
                    </h3>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-amber-800 bg-amber-100/70 px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                            @if(($stats['pending_operators'] ?? 0) > 0)
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-ping"></span>
                            @endif
                            Perlu Review
                        </span>
                        <a href="{{ route('super-admin.operators.index', ['status' => 'pending']) }}" class="text-[11px] font-semibold text-amber-700 hover:underline">
                            Periksa →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 4: Total Transaksi Global -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-soft hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Transaksi Global</span>
                    <span class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-heading font-black text-slate-900">
                        {{ number_format($stats['total_bookings'] ?? 0) }}
                    </h3>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                            {{ $stats['paid_bookings'] ?? 0 }} Tiket Lunas
                        </span>
                        <a href="{{ route('super-admin.reports.index') }}" class="text-[11px] font-semibold text-emerald-600 hover:underline">
                            Laporan Fee →
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- ==================== 2. CHART.JS ANALYTIC GRAPHS ==================== -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Grafik 1: Line Chart (Pendapatan Platform vs Operator) -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-soft">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-heading font-bold text-slate-900">Pendapatan Platform vs Pendapatan Operator</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Komparasi pendapatan fee platform dan pendapatan bersih operator selama 6 bulan terakhir.</p>
                    </div>
                    <!-- Chart Legend Indicators -->
                    <div class="flex items-center gap-3 text-xs font-semibold">
                        <span class="inline-flex items-center gap-1.5 text-indigo-600">
                            <span class="w-3 h-3 rounded-md bg-indigo-600"></span>
                            Fee Platform
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-violet-500">
                            <span class="w-3 h-3 rounded-md bg-violet-400"></span>
                            Net Operator
                        </span>
                    </div>
                </div>
                <div class="mt-4 relative h-64 sm:h-72">
                    <canvas id="platformRevenueChart"></canvas>
                </div>
            </div>

            <!-- Grafik 2: Pie / Doughnut Chart (Status Verifikasi Operator) -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-soft flex flex-col justify-between">
                <div>
                    <div class="pb-4 border-b border-slate-100">
                        <h2 class="text-base font-heading font-bold text-slate-900">Status Verifikasi Operator</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Proporsi status mitra travel terdaftar dalam sistem.</p>
                    </div>
                    <div class="mt-4 relative h-48 sm:h-52 flex items-center justify-center">
                        <canvas id="operatorStatusChart"></canvas>
                    </div>
                </div>

                <!-- Custom Summary Pills -->
                <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-3 gap-2 text-center">
                    <div class="p-2 rounded-xl bg-emerald-50 border border-emerald-100">
                        <p class="text-[10px] font-bold text-emerald-800 uppercase">Verified</p>
                        <p class="text-sm font-black text-emerald-600 mt-0.5">{{ $operatorStatusData['verified'] ?? 0 }}</p>
                    </div>
                    <div class="p-2 rounded-xl bg-amber-50 border border-amber-100">
                        <p class="text-[10px] font-bold text-amber-800 uppercase">Pending</p>
                        <p class="text-sm font-black text-amber-600 mt-0.5">{{ $operatorStatusData['pending'] ?? 0 }}</p>
                    </div>
                    <div class="p-2 rounded-xl bg-rose-50 border border-rose-100">
                        <p class="text-[10px] font-bold text-rose-800 uppercase">Rejected</p>
                        <p class="text-sm font-black text-rose-600 mt-0.5">{{ $operatorStatusData['rejected'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- ==================== 3. TABEL OPERATOR MENUNGGU PERSETUJUAN ==================== -->
        <div class="bg-white rounded-2xl border border-amber-200/80 shadow-soft overflow-hidden">
            <div class="p-5 bg-gradient-to-r from-amber-50 to-orange-50/40 border-b border-amber-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-heading font-bold text-amber-950">
                            Antrean Persetujuan Operator ({{ count($pendingOperators ?? []) }})
                        </h2>
                        <p class="text-xs text-amber-800">Operator yang menunggu peninjauan kesiapan dan persetujuan akun.</p>
                    </div>
                </div>
                <a href="{{ route('super-admin.operators.index', ['status' => 'pending']) }}" class="text-xs font-bold text-amber-900 hover:text-amber-950 hover:underline inline-flex items-center gap-1">
                    Lihat Semua Antrean
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="py-3 px-5">Nama Travel</th>
                            <th class="py-3 px-5">Pemilik & Kontak</th>
                            <th class="py-3 px-5">Tanggal Daftar</th>
                            <th class="py-3 px-5">Status Verifikasi</th>
                            <th class="py-3 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pendingOperators ?? [] as $op)
                        <tr class="hover:bg-slate-50/90 transition-colors">
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-700 font-heading font-bold text-xs flex items-center justify-center flex-shrink-0">
                                        {{ strtoupper(substr($op->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 text-sm">{{ $op->name }}</p>
                                        <p class="text-[11px] text-slate-400 font-mono">{{ $op->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-5">
                                <p class="font-semibold text-slate-800">{{ $op->owner_name ?? '-' }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $op->phone ?? '-' }}</p>
                            </td>
                            <td class="py-4 px-5 text-slate-600">
                                <span class="font-medium">{{ $op->created_at ? $op->created_at->isoFormat('D MMM Y') : '-' }}</span>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $op->created_at ? $op->created_at->diffForHumans() : '' }}</p>
                            </td>
                            <td class="py-4 px-5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                                    Menunggu Review
                                </span>
                            </td>
                            <td class="py-4 px-5 text-right">
                                <a href="{{ route('super-admin.operators.show', $op) }}" 
                                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Review Operator
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="font-bold text-slate-700 text-sm">Semua Operator Terverifikasi</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Tidak ada antrean pendaftaran mitra travel yang menunggu persetujuan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- ==================== CHART.JS SCRIPT ==================== -->
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Data dari Controller
            const months = {!! json_encode($chartMonths ?? ['Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt']) !!};
            const platformFeeData = {!! json_encode($platformRevenue ?? [0, 0, 0, 0, 0, 0]) !!};
            const operatorNetData = {!! json_encode($operatorRevenue ?? [0, 0, 0, 0, 0, 0]) !!};
            const operatorStatus = {!! json_encode($operatorStatusData ?? ['verified' => 0, 'pending' => 0, 'rejected' => 0]) !!};

            // Format Currency IDR
            const formatRupiah = (val) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);

            // 1. Dual Line Chart: Pendapatan Platform vs Operator
            const ctxRevenue = document.getElementById('platformRevenueChart');
            if (ctxRevenue) {
                const gradientIndigo = ctxRevenue.getContext('2d').createLinearGradient(0, 0, 0, 260);
                gradientIndigo.addColorStop(0, 'rgba(79, 70, 229, 0.25)');
                gradientIndigo.addColorStop(1, 'rgba(79, 70, 229, 0.00)');

                const gradientViolet = ctxRevenue.getContext('2d').createLinearGradient(0, 0, 0, 260);
                gradientViolet.addColorStop(0, 'rgba(139, 92, 246, 0.20)');
                gradientViolet.addColorStop(1, 'rgba(139, 92, 246, 0.00)');

                new Chart(ctxRevenue, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [
                            {
                                label: 'Fee Platform',
                                data: platformFeeData,
                                borderColor: '#4F46E5', // Indigo
                                borderWidth: 3,
                                fill: true,
                                backgroundColor: gradientIndigo,
                                tension: 0.35,
                                pointBackgroundColor: '#FFFFFF',
                                pointBorderColor: '#4F46E5',
                                pointBorderWidth: 2.5,
                                pointRadius: 4.5,
                                pointHoverRadius: 7
                            },
                            {
                                label: 'Pendapatan Operator',
                                data: operatorNetData,
                                borderColor: '#8B5CF6', // Violet
                                borderWidth: 2.5,
                                fill: true,
                                backgroundColor: gradientViolet,
                                tension: 0.35,
                                borderDash: [4, 4],
                                pointBackgroundColor: '#FFFFFF',
                                pointBorderColor: '#8B5CF6',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }
                        ]
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
                                callbacks: {
                                    label: function(context) {
                                        return `${context.dataset.label}: ${formatRupiah(context.parsed.y)}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { family: 'Inter', size: 11 }, color: '#64748b' }
                            },
                            y: {
                                beginAtZero: true,
                                border: { dash: [5, 5] },
                                grid: { color: '#f1f5f9' },
                                ticks: {
                                    font: { family: 'Inter', size: 11 },
                                    color: '#64748b',
                                    callback: function(value) {
                                        return 'Rp ' + (value >= 1000 ? (value / 1000) + 'k' : value);
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // 2. Doughnut / Pie Chart: Status Verifikasi Operator
            const ctxStatus = document.getElementById('operatorStatusChart');
            if (ctxStatus) {
                const totalOps = (operatorStatus.verified || 0) + (operatorStatus.pending || 0) + (operatorStatus.rejected || 0);

                new Chart(ctxStatus, {
                    type: 'doughnut',
                    data: {
                        labels: ['Terverifikasi', 'Menunggu Review', 'Ditolak'],
                        datasets: [{
                            data: [
                                operatorStatus.verified || 0,
                                operatorStatus.pending || 0,
                                operatorStatus.rejected || 0
                            ],
                            backgroundColor: [
                                '#10B981', // Emerald
                                '#F59E0B', // Amber
                                '#EF4444'  // Rose
                            ],
                            borderWidth: 3,
                            borderColor: '#FFFFFF',
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
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
                                        const pct = totalOps > 0 ? Math.round((count / totalOps) * 100) : 0;
                                        return ` ${context.label}: ${count} Operator (${pct}%)`;
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
</x-app-layout>
