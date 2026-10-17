<x-app-layout>
    <x-slot name="header">Manajemen Pengguna Global</x-slot>

    <div class="space-y-6">

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase tracking-wider">
                        Akses & Keamanan
                    </span>
                </div>
                <h1 class="text-2xl font-heading font-black text-slate-900">Manajemen Pengguna Global</h1>
                <p class="text-xs text-slate-500 mt-0.5">Kelola seluruh akun platform mulai dari Customer, Driver, Admin Mitra Travel, hingga Super Admin.</p>
            </div>
            <a href="{{ route('super-admin.users.create') }}"
               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all shadow-md shadow-indigo-600/20 self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                + Tambah Pengguna Baru
            </a>
        </div>

        <!-- Filter Bar & Search -->
        <div class="bg-white rounded-2xl shadow-soft border border-slate-200/80 p-4">
            <form action="{{ route('super-admin.users.index') }}" method="GET" class="flex flex-col lg:flex-row items-center justify-between gap-4">
                
                <!-- Filter Tabs (Role) -->
                <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                    <a href="{{ route('super-admin.users.index', array_filter(['status' => $status, 'operator_id' => $operatorId, 'search' => $search])) }}"
                       class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ empty($role) ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Semua ({{ $counts['all'] ?? 0 }})
                    </a>
                    <a href="{{ route('super-admin.users.index', array_filter(['role' => 'admin_travel', 'status' => $status, 'operator_id' => $operatorId, 'search' => $search])) }}"
                       class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ $role === 'admin_travel' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-100' }}">
                        Admin Travel ({{ $counts['admin_travel'] ?? 0 }})
                    </a>
                    <a href="{{ route('super-admin.users.index', array_filter(['role' => 'driver', 'status' => $status, 'operator_id' => $operatorId, 'search' => $search])) }}"
                       class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ $role === 'driver' ? 'bg-violet-600 text-white shadow-sm' : 'bg-violet-50 text-violet-700 hover:bg-violet-100 border border-violet-100' }}">
                        Driver ({{ $counts['driver'] ?? 0 }})
                    </a>
                    <a href="{{ route('super-admin.users.index', array_filter(['role' => 'customer', 'status' => $status, 'operator_id' => $operatorId, 'search' => $search])) }}"
                       class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ $role === 'customer' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-100' }}">
                        Customer ({{ $counts['customer'] ?? 0 }})
                    </a>
                    <a href="{{ route('super-admin.users.index', array_filter(['role' => 'super_admin', 'status' => $status, 'operator_id' => $operatorId, 'search' => $search])) }}"
                       class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ $role === 'super_admin' ? 'bg-slate-800 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Super Admin ({{ $counts['super_admin'] ?? 0 }})
                    </a>
                </div>

                <!-- Dropdown Status, Operator & Search -->
                <div class="flex flex-col sm:flex-row items-center gap-2.5 w-full lg:w-auto">
                    @if($role)
                        <input type="hidden" name="role" value="{{ $role }}">
                    @endif

                    <!-- Status Filter -->
                    <select name="status" onchange="this.form.submit()"
                            class="w-full sm:w-36 px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="banned" {{ $status === 'banned' ? 'selected' : '' }}>Diblokir (Banned)</option>
                    </select>

                    <!-- Operator Filter -->
                    <select name="operator_id" onchange="this.form.submit()"
                            class="w-full sm:w-44 px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Mitra Travel</option>
                        @foreach($operators as $op)
                            <option value="{{ $op->id }}" {{ $operatorId == $op->id ? 'selected' : '' }}>
                                {{ $op->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Search Input -->
                    <div class="relative w-full sm:w-60">
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Cari nama, email, HP..."
                               class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500 transition-colors">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel Pengguna -->
        <div class="bg-white rounded-2xl shadow-soft border border-slate-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/90 border-b border-slate-200/80 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-5">#</th>
                            <th class="py-3.5 px-5">Pengguna</th>
                            <th class="py-3.5 px-5">Kontak</th>
                            <th class="py-3.5 px-5">Peran (Role)</th>
                            <th class="py-3.5 px-5">Mitra Travel Terkait</th>
                            <th class="py-3.5 px-5">Status Akun</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $u)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-5 text-slate-400 font-mono">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                            </td>
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-xs flex-shrink-0 shadow-2xs
                                                @if($u->role === 'super_admin') bg-slate-900
                                                @elseif($u->role === 'admin_travel') bg-indigo-600
                                                @elseif($u->role === 'driver') bg-violet-600
                                                @else bg-emerald-600 @endif">
                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('super-admin.users.show', $u) }}" class="font-bold text-slate-900 hover:text-indigo-600 transition-colors truncate block text-sm">
                                            {{ $u->name }}
                                        </a>
                                        <p class="text-[11px] text-slate-400 font-mono">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-5">
                                <p class="font-semibold text-slate-800">{{ $u->phone ?? '-' }}</p>
                                <p class="text-[10px] text-slate-400">Terdaftar {{ $u->created_at ? $u->created_at->diffForHumans() : '-' }}</p>
                            </td>
                            <td class="py-4 px-5">
                                @if($u->role === 'super_admin')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-900 text-white">
                                        Super Admin
                                    </span>
                                @elseif($u->role === 'admin_travel')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        Admin Travel
                                    </span>
                                @elseif($u->role === 'driver')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-violet-50 text-violet-700 border border-violet-200">
                                        Driver Travel
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Customer
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-5">
                                @if($u->operator)
                                    <a href="{{ route('super-admin.operators.show', $u->operator) }}" class="font-bold text-indigo-600 hover:underline inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        {{ $u->operator->name }}
                                    </a>
                                @else
                                    <span class="text-slate-400 italic">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-5">
                                @if($u->status === 'active')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-800 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Diblokir
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Tombol Detail -->
                                    <a href="{{ route('super-admin.users.show', $u) }}" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Lihat Aktivitas">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>

                                    <!-- Tombol Edit -->
                                    <a href="{{ route('super-admin.users.edit', $u) }}" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit Akun">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    <!-- Tombol Ban / Unban (Icon Gembok) -->
                                    @if($u->id !== auth()->id())
                                    <form action="{{ route('super-admin.users.toggle-status', $u) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin {{ $u->status === 'active' ? 'memblokir (ban)' : 'mengaktifkan kembali' }} akun {{ $u->name }}?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" 
                                                class="p-1.5 rounded-lg transition-colors {{ $u->status === 'active' ? 'text-slate-400 hover:text-rose-600 hover:bg-rose-50' : 'text-amber-600 bg-amber-50 hover:bg-amber-100' }}"
                                                title="{{ $u->status === 'active' ? 'Blokir Akun (Ban)' : 'Buka Blokir (Unban)' }}">
                                            @if($u->status === 'active')
                                                <!-- Gembok Terbuka (Siap di-lock) -->
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                                </svg>
                                            @else
                                                <!-- Gembok Terkunci (Siap di-unlock) -->
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('super-admin.users.destroy', $u) }}" method="POST"
                                          onsubmit="return confirm('Hapus permanen akun {{ $u->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Permanen">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    </div>
                                    <p class="text-sm font-bold text-slate-700">Tidak ada data pengguna</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Silakan sesuaikan kata kunci pencarian atau filter peran.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $users->links() }}
            </div>
            @endif
        </div>

    </div>
</x-app-layout>
