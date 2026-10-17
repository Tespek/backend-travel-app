<x-app-layout>
    <x-slot name="header">Detail Pengguna: {{ $user->name }}</x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                    <a href="{{ route('super-admin.users.index') }}" class="hover:text-blue-600 transition-colors">Manajemen User</a>
                    <span>/</span>
                    <span class="text-slate-800 font-medium">{{ $user->name }}</span>
                </nav>
                <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                    {{ $user->name }}
                    @if($user->status === 'active')
                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> Diblokir
                        </span>
                    @endif
                </h1>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                @if($user->id !== auth()->id())
                <form action="{{ route('super-admin.users.toggle-status', $user) }}" method="POST"
                      onsubmit="return confirm('{{ $user->status === 'active' ? 'Blokir akun pengguna ini?' : 'Aktifkan kembali akun pengguna ini?' }}')">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-lg transition-colors border {{ $user->status === 'active' ? 'bg-amber-50 hover:bg-amber-100 text-amber-800 border-amber-200' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border-emerald-200' }}">
                        @if($user->status === 'active')
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            Blokir Akun
                        @else
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Aktifkan Akun
                        @endif
                    </button>
                </form>
                @endif

                <a href="{{ route('super-admin.users.edit', $user) }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Profil
                </a>
                <a href="{{ route('super-admin.users.index') }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                    Kembali
                </a>
            </div>
        </div>

        <!-- Profil & Detail Pengguna Card -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Kartu Identitas Profil -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-full bg-slate-100 border-2 border-slate-200 text-slate-700 font-black text-2xl flex items-center justify-center mb-4 shadow-inner">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <h2 class="text-lg font-bold text-slate-900">{{ $user->name }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">{{ $user->email }}</p>

                <div class="mt-4">
                    @php
                        $roleBadges = [
                            'super_admin'  => 'bg-purple-50 text-purple-700 border-purple-200',
                            'admin_travel' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'driver'       => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            'customer'     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        ];
                        $badgeStyle = $roleBadges[$user->role] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badgeStyle }} capitalize">
                        Peran: {{ str_replace('_', ' ', $user->role) }}
                    </span>
                </div>

                @if($user->operator)
                <div class="w-full mt-6 pt-5 border-t border-slate-100 text-left">
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Mitra Operator</p>
                    <a href="{{ route('super-admin.operators.show', $user->operator) }}"
                       class="flex items-center gap-2 p-2.5 rounded-lg bg-slate-50 hover:bg-blue-50/60 border border-slate-200 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">
                            {{ strtoupper(substr($user->operator->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-800 group-hover:text-blue-600 truncate">{{ $user->operator->name }}</p>
                            <p class="text-[11px] text-slate-400 truncate">{{ $user->operator->phone ?? $user->operator->email }}</p>
                        </div>
                    </a>
                </div>
                @endif
            </div>

            <!-- Detail Data Akun & Info Kontak -->
            <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                        Informasi Lengkap Akun
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-3 rounded-lg bg-slate-50 border border-slate-100">
                            <p class="text-slate-400 font-semibold uppercase tracking-wider text-[10px]">Nomor Telepon / WhatsApp</p>
                            <p class="text-sm font-bold text-slate-800 mt-1">{{ $user->phone ?? 'Belum diisi' }}</p>
                        </div>

                        <div class="p-3 rounded-lg bg-slate-50 border border-slate-100">
                            <p class="text-slate-400 font-semibold uppercase tracking-wider text-[10px]">Status Akun</p>
                            <p class="text-sm font-bold mt-1 {{ $user->status === 'active' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $user->status === 'active' ? 'Aktif (Dapat Login)' : 'Diblokir (Akses Ditutup)' }}
                            </p>
                        </div>

                        <div class="p-3 rounded-lg bg-slate-50 border border-slate-100">
                            <p class="text-slate-400 font-semibold uppercase tracking-wider text-[10px]">Tanggal Registrasi</p>
                            <p class="text-sm font-bold text-slate-800 mt-1">{{ $user->created_at->isoFormat('dddd, D MMMM Y · HH:mm') }} WIB</p>
                        </div>

                        <div class="p-3 rounded-lg bg-slate-50 border border-slate-100">
                            <p class="text-slate-400 font-semibold uppercase tracking-wider text-[10px]">Verifikasi Email</p>
                            <p class="text-sm font-bold mt-1 {{ $user->email_verified_at ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $user->email_verified_at ? 'Terverifikasi (' . $user->email_verified_at->format('d/m/Y') . ')' : 'Belum Terverifikasi' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                    <span>ID Pengguna: #{{ $user->id }}</span>
                    <span>Terakhir diperbarui: {{ $user->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        <!-- Riwayat Transaksi / Penugasan Terkait -->
        @if(in_array($user->role, ['customer', 'driver']))
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">
                        {{ $user->role === 'customer' ? 'Riwayat Pemesanan Tiket Penumpang' : 'Riwayat Penugasan Driver Perjalanan' }}
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Maksimal 10 aktivitas transaksi terbaru.</p>
                </div>
                <a href="{{ route('super-admin.bookings.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                    Lihat Semua Transaksi →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">Kode Booking</th>
                            <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">Operator</th>
                            <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">Rute Perjalanan</th>
                            <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">Jadwal Berangkat</th>
                            <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">Total Tarif</th>
                            <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">Status</th>
                            <th class="text-right text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($bookings as $b)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-xs text-slate-900">
                                #{{ $b->booking_code }}
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-slate-700">
                                {{ $b->operator->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-800">
                                {{ $b->schedule->route->originCity->name ?? '-' }} → {{ $b->schedule->route->destinationCity->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600">
                                {{ \Carbon\Carbon::parse($b->schedule->departure_time)->isoFormat('D MMM Y · HH:mm') }}
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-slate-900">
                                Rp {{ number_format($b->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($b->payment_status === 'paid')
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2 py-0.5 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full">
                                        {{ ucfirst($b->payment_status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('super-admin.bookings.show', $b) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                                    Detail →
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-slate-400 text-xs">
                                Belum ada riwayat pemesanan yang tercatat untuk pengguna ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
