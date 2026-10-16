<x-app-layout>
    <x-slot name="header">Operator Travel</x-slot>

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Operator Travel</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola data mitra travel, verifikasi identitas (KTP & SIUP), dan pantau operasional.</p>
        </div>
        <a href="{{ route('super-admin.operators.create') }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors shadow-sm self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Operator
        </a>
    </div>

    <!-- Filter Status Tabs & Search -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6 p-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Filter Tabs -->
            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                <a href="{{ route('super-admin.operators.index', ['search' => request('search')]) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ empty($status) ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua ({{ $counts['all'] }})
                </a>
                <a href="{{ route('super-admin.operators.index', ['status' => 'pending', 'search' => request('search')]) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $status === 'pending' ? 'bg-amber-500 text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                    Menunggu ({{ $counts['pending'] }})
                </a>
                <a href="{{ route('super-admin.operators.index', ['status' => 'verified', 'search' => request('search')]) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $status === 'verified' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                    Terverifikasi ({{ $counts['verified'] }})
                </a>
                <a href="{{ route('super-admin.operators.index', ['status' => 'rejected', 'search' => request('search')]) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $status === 'rejected' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                    Ditolak ({{ $counts['rejected'] }})
                </a>
            </div>

            <!-- Search Bar -->
            <form action="{{ route('super-admin.operators.index') }}" method="GET" class="w-full md:w-72">
                @if($status)
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Cari nama, email, pemilik..."
                           class="w-full pl-9 pr-4 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </form>
        </div>
    </div>

    <!-- Table of Operators -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">#</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Operator</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Pemilik / Kontak</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Dokumen</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Status</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Statistik</th>
                        <th class="text-right text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($operators as $operator)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-6 py-4 text-slate-400 text-xs font-mono">
                            {{ ($operators->currentPage() - 1) * $operators->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($operator->logo)
                                    <img src="{{ Storage::url($operator->logo) }}" alt="{{ $operator->name }}" class="w-10 h-10 rounded-lg object-cover border border-slate-200 flex-shrink-0">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-700 font-bold flex items-center justify-center flex-shrink-0 text-sm">
                                        {{ strtoupper(substr($operator->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <a href="{{ route('super-admin.operators.show', $operator) }}" class="font-semibold text-slate-900 hover:text-blue-600 transition-colors truncate block">
                                        {{ $operator->name }}
                                    </a>
                                    <span class="text-xs text-slate-400">{{ $operator->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-800 text-xs">{{ $operator->owner_name }}</p>
                            <p class="text-slate-400 text-xs mt-0.5">{{ $operator->phone ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5 text-xs">
                                <span class="px-2 py-0.5 rounded font-mono text-[11px] {{ $operator->document_ktp ? 'bg-slate-100 text-slate-700' : 'bg-slate-100 text-slate-400' }}">
                                    KTP: {{ $operator->document_ktp ? '✓' : '-' }}
                                </span>
                                <span class="px-2 py-0.5 rounded font-mono text-[11px] {{ $operator->document_siup ? 'bg-slate-100 text-slate-700' : 'bg-slate-100 text-slate-400' }}">
                                    SIUP: {{ $operator->document_siup ? '✓' : '-' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($operator->verification_status === 'verified')
                                <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Terverifikasi
                                </span>
                            @elseif($operator->verification_status === 'pending')
                                <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span> Menunggu
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold px-2.5 py-1 rounded-full" title="{{ $operator->rejection_reason }}">
                                    <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500">
                            <span class="font-medium text-slate-700">{{ $operator->vehicles_count }}</span> armada ·
                            <span class="font-medium text-slate-700">{{ $operator->routes_count }}</span> rute
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('super-admin.operators.show', $operator) }}"
                                   class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail & Dokumen">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('super-admin.operators.edit', $operator) }}"
                                   class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('super-admin.operators.destroy', $operator) }}" method="POST"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus operator {{ $operator->name }}? File terkait juga akan dihapus.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <p class="text-sm font-medium text-slate-600">Belum ada operator travel yang terdaftar.</p>
                                <p class="text-xs text-slate-400 mt-1">Tambahkan operator mitra travel pertama Anda.</p>
                                <a href="{{ route('super-admin.operators.create') }}" class="mt-4 text-xs font-semibold text-blue-600 hover:text-blue-700">
                                    + Tambah Operator Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($operators->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $operators->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
