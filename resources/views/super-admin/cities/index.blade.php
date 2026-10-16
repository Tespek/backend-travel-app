<x-app-layout>
    <x-slot name="header">Kota & Wilayah</x-slot>

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Kota & Wilayah</h1>
            <p class="text-sm text-slate-500 mt-1">Atur kota tujuan / asal yang dilayani oleh setiap mitra operator travel beserta data kecamatannya.</p>
        </div>
        <a href="{{ route('super-admin.cities.create') }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors shadow-sm self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kota Layanan
        </a>
    </div>

    <!-- Filter Bar & Search -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6 p-4">
        <form action="{{ route('super-admin.cities.index') }}" method="GET" class="flex flex-col lg:flex-row items-center justify-between gap-4">
            <!-- Filter Tabs -->
            <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                <a href="{{ route('super-admin.cities.index', array_filter(['operator_id' => $operatorId, 'search' => $search])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $status === null ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua ({{ $counts['all'] }})
                </a>
                <a href="{{ route('super-admin.cities.index', array_filter(['status' => '1', 'operator_id' => $operatorId, 'search' => $search])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $status === '1' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                    Aktif ({{ $counts['active'] }})
                </a>
                <a href="{{ route('super-admin.cities.index', array_filter(['status' => '0', 'operator_id' => $operatorId, 'search' => $search])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $status === '0' ? 'bg-slate-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Nonaktif ({{ $counts['inactive'] }})
                </a>
            </div>

            <!-- Filter Operator & Search Input -->
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                @if($status !== null)
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif

                <select name="operator_id" onchange="this.form.submit()"
                        class="w-full sm:w-48 px-3 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Operator</option>
                    @foreach($operators as $op)
                        <option value="{{ $op->id }}" {{ $operatorId == $op->id ? 'selected' : '' }}>
                            {{ $op->name }}
                        </option>
                    @endforeach
                </select>

                <div class="relative w-full sm:w-60">
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Cari nama kota..."
                           class="w-full pl-9 pr-4 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </form>
    </div>

    <!-- Table of Cities -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">#</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Nama Kota</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Mitra Operator</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Kecamatan</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Titik Jemput</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Status</th>
                        <th class="text-right text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($cities as $city)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-6 py-4 text-slate-400 text-xs font-mono">
                            {{ ($cities->currentPage() - 1) * $cities->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <span class="font-bold text-slate-900 text-sm">{{ $city->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded bg-slate-100 text-slate-700 font-medium text-xs">
                                {{ $city->operator->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600">
                            <span class="font-semibold text-slate-800">{{ $city->districts_count }}</span> kecamatan
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600">
                            <span class="font-semibold text-blue-600">{{ $city->pickup_points_count }}</span> titik pool
                        </td>
                        <td class="px-6 py-4">
                            @if($city->is_active)
                                <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-600 border border-slate-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('super-admin.cities.edit', $city) }}"
                                   class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('super-admin.cities.destroy', $city) }}" method="POST"
                                      onsubmit="return confirm('Hapus kota {{ $city->name }}?')">
                                    @csrf
                                    @method('DELETE')
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="text-sm font-medium text-slate-600">Belum ada kota layanan yang terdaftar.</p>
                                <p class="text-xs text-slate-400 mt-1">Tambahkan kota operasional agar rute dan titik penjemputan dapat dibuat.</p>
                                <a href="{{ route('super-admin.cities.create') }}" class="mt-4 text-xs font-semibold text-blue-600 hover:text-blue-700">
                                    + Tambah Kota Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($cities->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $cities->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
