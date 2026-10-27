<x-app-layout>
    <x-slot name="header">Titik Penjemputan</x-slot>

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Titik Penjemputan Penumpang</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola lokasi pool, shelter, atau titik kumpul resmi travel Anda beserta titik koordinat GPS.</p>
        </div>
    </div>

    <!-- Filter Bar & Search -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6 p-4">
        <form action="{{ route('admin-travel.pickup-points.index') }}" method="GET" class="flex flex-col lg:flex-row items-center justify-between gap-4">
            <!-- Filter Tabs -->
            <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                <a href="{{ route('admin-travel.pickup-points.index', array_filter(['city_id' => $cityId, 'search' => $search])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $status === null ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua ({{ $counts['all'] }})
                </a>
                <a href="{{ route('admin-travel.pickup-points.index', array_filter(['status' => '1', 'city_id' => $cityId, 'search' => $search])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $status === '1' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                    Aktif ({{ $counts['active'] }})
                </a>
                <a href="{{ route('admin-travel.pickup-points.index', array_filter(['status' => '0', 'city_id' => $cityId, 'search' => $search])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $status === '0' ? 'bg-slate-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Nonaktif ({{ $counts['inactive'] }})
                </a>
            </div>

            <!-- Filter Kota & Search Input -->
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                @if($status !== null)
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif

                <select name="city_id" onchange="this.form.submit()"
                        class="w-full sm:w-44 px-3 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kota</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ $cityId == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>

                <div class="relative w-full sm:w-64">
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Cari nama pool / alamat..."
                           class="w-full pl-9 pr-4 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </form>
    </div>

    <!-- Table of Pickup Points -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">#</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Titik Jemput / Pool</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Kota</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Alamat Lokasi</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Koordinat GPS</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Status</th>
                        <th class="text-right text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3.5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pickupPoints as $point)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-6 py-4 text-slate-400 text-xs font-mono">
                            {{ ($pickupPoints->currentPage() - 1) * $pickupPoints->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900 text-sm">{{ $point->name }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $point->bookings_count }} pesanan tercatat</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 text-xs font-semibold">
                                {{ $point->city->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600 max-w-xs truncate" title="{{ $point->address }}">
                            {{ $point->address }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="https://www.google.com/maps?q={{ $point->latitude }},{{ $point->longitude }}" target="_blank"
                               class="inline-flex items-center gap-1.5 text-xs text-blue-600 hover:text-blue-800 bg-slate-50 hover:bg-blue-50 px-2 py-1 rounded border border-slate-200 transition-colors font-mono">
                                <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <span>{{ number_format($point->latitude, 4) }}, {{ number_format($point->longitude, 4) }}</span>
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            @if($point->is_active)
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
                                <a href="{{ route('admin-travel.pickup-points.edit', $point) }}"
                                   class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin-travel.pickup-points.destroy', $point) }}" method="POST"
                                      onsubmit="return confirm('Hapus titik penjemputan {{ $point->name }}?')">
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
                                <p class="text-sm font-medium text-slate-600">Belum ada titik penjemputan yang terdaftar.</p>
                                <p class="text-xs text-slate-400 mt-1">Daftarkan pool atau shelter penjemputan agar penumpang dapat memilih lokasi naik mobil.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pickupPoints->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $pickupPoints->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
