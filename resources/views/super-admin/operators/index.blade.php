<x-app-layout>
    <x-slot name="header">Operator Travel</x-slot>

    <div class="space-y-6">

        <!-- Header Platform Level -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase tracking-wider">
                        Tata Kelola Ekosistem
                    </span>
                </div>
                <h1 class="text-2xl font-heading font-black text-slate-900">Mitra Operator Travel</h1>
                <p class="text-xs text-slate-500 mt-0.5">Kelola data mitra travel, status verifikasi akun, dan audit kesiapan operasional.</p>
            </div>
            <a href="{{ route('super-admin.operators.create') }}"
               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all shadow-md shadow-indigo-600/20 self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Operator
            </a>
        </div>

        <!-- Filter Status Tabs & Search -->
        <div class="bg-white rounded-2xl shadow-soft border border-slate-200/80 p-4">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                
                <!-- Filter Tabs -->
                <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                    <!-- Semua -->
                    <a href="{{ route('super-admin.operators.index', ['search' => request('search')]) }}"
                       class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ empty($status) ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Semua ({{ $counts['all'] ?? 0 }})
                    </a>

                    <!-- Menunggu Review -->
                    <a href="{{ route('super-admin.operators.index', ['status' => 'pending', 'search' => request('search')]) }}"
                       class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1.5 {{ $status === 'pending' ? 'bg-amber-500 text-slate-950 shadow-sm' : 'bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200/60' }}">
                        @if(($counts['pending'] ?? 0) > 0)
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                        @endif
                        Menunggu Verifikasi ({{ $counts['pending'] ?? 0 }})
                    </a>

                    <!-- Terverifikasi -->
                    <a href="{{ route('super-admin.operators.index', ['status' => 'verified', 'search' => request('search')]) }}"
                       class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'verified' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200/60' }}">
                        Terverifikasi ({{ $counts['verified'] ?? 0 }})
                    </a>

                    <!-- Ditolak -->
                    <a href="{{ route('super-admin.operators.index', ['status' => 'rejected', 'search' => request('search')]) }}"
                       class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'rejected' ? 'bg-rose-600 text-white shadow-sm' : 'bg-rose-50 text-rose-800 hover:bg-rose-100 border border-rose-200/60' }}">
                        Ditolak ({{ $counts['rejected'] ?? 0 }})
                    </a>
                </div>

                <!-- Search Input -->
                <form action="{{ route('super-admin.operators.index') }}" method="GET" class="w-full md:w-80">
                    @if($status)
                        <input type="hidden" name="status" value="{{ $status }}">
                    @endif
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Cari nama travel, pemilik, email..."
                               class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Operator -->
        <div class="bg-white rounded-2xl shadow-soft border border-slate-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/90 border-b border-slate-200/80 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-5">#</th>
                            <th class="py-3.5 px-5">Mitra Operator</th>
                            <th class="py-3.5 px-5">Pemilik / Kontak</th>
                            <th class="py-3.5 px-5">Status Verifikasi</th>
                            <th class="py-3.5 px-5">Kapasitas</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($operators as $operator)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-5 text-slate-400 font-mono">
                                {{ ($operators->currentPage() - 1) * $operators->perPage() + $loop->iteration }}
                            </td>
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    @if($operator->logo)
                                        <img src="{{ Storage::url($operator->logo) }}" alt="{{ $operator->name }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 shadow-2xs flex-shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-violet-600 text-white font-heading font-black text-xs flex items-center justify-center flex-shrink-0 shadow-2xs">
                                            {{ strtoupper(substr($operator->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <a href="{{ route('super-admin.operators.show', $operator) }}" class="font-bold text-slate-900 hover:text-indigo-600 transition-colors truncate block text-sm">
                                            {{ $operator->name }}
                                        </a>
                                        <span class="text-[11px] text-slate-400 font-mono">{{ $operator->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-5">
                                <p class="font-semibold text-slate-800">{{ $operator->owner_name ?? '-' }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $operator->phone ?? '-' }}</p>
                            </td>
                            <td class="py-4 px-5">
                                @if($operator->verification_status === 'verified')
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Terverifikasi
                                    </span>
                                @elseif($operator->verification_status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-ping"></span> Menunggu Review
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-800 border border-rose-200 text-xs font-bold px-2.5 py-1 rounded-full" title="{{ $operator->rejection_reason }}">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-slate-600">
                                <span class="font-bold text-slate-800">{{ $operator->vehicles_count }}</span> armada ·
                                <span class="font-bold text-slate-800">{{ $operator->routes_count }}</span> rute
                            </td>
                            <td class="py-4 px-5 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('super-admin.operators.show', $operator) }}"
                                       class="px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold text-xs transition-colors flex items-center gap-1" title="Tinjau & Verifikasi">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Review
                                    </a>
                                    <a href="{{ route('super-admin.operators.edit', $operator) }}"
                                       class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit Profil">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('super-admin.operators.destroy', $operator) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus operator {{ $operator->name }}? Seluruh relasi akun dan armada akan terhapus.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <p class="text-sm font-bold text-slate-700">Tidak ada data mitra travel</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Silakan ganti kata kunci pencarian atau filter status.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($operators->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $operators->links() }}
            </div>
            @endif
        </div>

    </div>
</x-app-layout>
