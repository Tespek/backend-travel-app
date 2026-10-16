<x-app-layout>
    <x-slot name="header">Manajemen User</x-slot>

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Pengguna</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola seluruh akun platform mulai dari Customer, Driver, Admin Mitra Travel, hingga Super Admin.</p>
        </div>
        <a href="{{ route('super-admin.users.create') }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors shadow-sm self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pengguna
        </a>
    </div>

    <!-- Filter Bar & Search -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6 p-4">
        <form action="{{ route('super-admin.users.index') }}" method="GET" class="flex flex-col lg:flex-row items-center justify-between gap-4">
            <!-- Filter Tabs -->
            <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                <a href="{{ route('super-admin.users.index', array_filter(['status' => $status, 'search' => $search])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ empty($role) ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua ({{ $counts['all'] }})
                </a>
                <a href="{{ route('super-admin.users.index', array_filter(['role' => 'admin_travel', 'status' => $status, 'search' => $search])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $role === 'admin_travel' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                    Admin Travel ({{ $counts['admin_travel'] }})
                </a>
                <a href="{{ route('super-admin.users.index', array_filter(['role' => 'driver', 'status' => $status, 'search' => $search])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $role === 'driver' ? 'bg-indigo-600 text-white' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }}">
                    Driver ({{ $counts['driver'] }})
                </a>
                <a href="{{ route('super-admin.users.index', array_filter(['role' => 'customer', 'status' => $status, 'search' => $search])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $role === 'customer' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                    Customer ({{ $counts['customer'] }})
                </a>
                <a href="{{ route('super-admin.users.index', array_filter(['role' => 'super_admin', 'status' => $status, 'search' => $search])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $role === 'super_admin' ? 'bg-purple-600 text-white' : 'bg-purple-50 text-purple-700 hover:bg-purple-100' }}">
                    Super Admin ({{ $counts['super_admin'] }})
                </a>
            </div>

            <!-- Search Input -->
            <div class="w-full sm:w-64">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Cari nama, email, nomor HP..."
                           class="w-full pl-9 pr-4 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </form>
    </div>

    <!-- Table of Users -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">#</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Pengguna</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Kontak</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Peran (Role)</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Mitra Travel</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Status</th>
                        <th class="text-right text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $u)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-6 py-4 text-slate-400 text-xs font-mono">
                            {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 text-sm">{{ $u->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600">
                            {{ $u->phone ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $roleBadges = [
                                    'super_admin'  => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'admin_travel' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'driver'       => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'customer'     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                ];
                                $badgeStyle = $roleBadges[$u->role] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $badgeStyle }} capitalize">
                                {{ str_replace('_', ' ', $u->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-700">
                            {{ $u->operator->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($u->status === 'active')
                                <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> Diblokir
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('super-admin.users.edit', $u) }}"
                                   class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @if($u->id !== auth()->id())
                                <form action="{{ route('super-admin.users.destroy', $u) }}" method="POST"
                                      onsubmit="return confirm('Hapus akun {{ $u->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-xs">
                            Tidak ada data pengguna yang sesuai dengan filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
