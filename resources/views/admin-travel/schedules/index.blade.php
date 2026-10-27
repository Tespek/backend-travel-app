<x-app-layout>
    <x-slot name="header">Manajemen Jadwal</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Jadwal Keberangkatan</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola jadwal perjalanan untuk semua rute Anda.</p>
        </div>
        <a href="{{ route('admin-travel.schedules.create') }}"
           class="inline-flex items-center gap-2 bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-md shadow-teal-600/20 transition-all hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Jadwal Baru
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">#</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">Rute</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">Tanggal & Jam</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">Kendaraan</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">Kursi Tersedia</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">Status</th>
                        <th class="text-right text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($schedules as $schedule)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-slate-400 text-xs font-mono">{{ ($schedules->currentPage() - 1) * $schedules->perPage() + $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5 font-medium text-slate-800">
                                {{ $schedule->route->origin_name }}
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                                {{ $schedule->route->destination_name }}
                            </div>
                            <div class="text-xs text-green-600 font-medium mt-0.5">
                                Rp {{ number_format($schedule->route->price, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-800">
                                {{ \Carbon\Carbon::parse($schedule->departure_date)->isoFormat('D MMM Y') }}
                            </div>
                            <div class="text-slate-500 text-xs mt-0.5">
                                {{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }} WIB
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-700">
                            <div>{{ $schedule->vehicle->brand ?? '-' }}</div>
                            <div class="text-xs text-slate-400">{{ $schedule->vehicle->plate_number ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-slate-800 font-semibold">{{ $schedule->available_seats }}</span>
                            <span class="text-slate-400 text-xs"> kursi</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = [
                                    'open'      => ['bg-green-100 text-green-700', 'Terbuka'],
                                    'full'      => ['bg-amber-100 text-amber-700', 'Penuh'],
                                    'cancelled' => ['bg-red-100 text-red-700', 'Dibatalkan'],
                                    'completed' => ['bg-slate-100 text-slate-600', 'Selesai'],
                                ];
                                [$cls, $label] = $statusConfig[$schedule->status] ?? ['bg-slate-100 text-slate-600', $schedule->status];
                            @endphp
                            <span class="inline-flex items-center text-xs font-medium px-2.5 py-1 rounded-full {{ $cls }}">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin-travel.schedules.edit', $schedule) }}"
                                   class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin-travel.schedules.destroy', $schedule) }}" method="POST"
                                      onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
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
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-slate-500 font-medium">Belum ada jadwal</p>
                                <p class="text-slate-400 text-sm">Buat jadwal keberangkatan pertama Anda.</p>
                                <a href="{{ route('admin-travel.schedules.create') }}" class="mt-2 text-blue-600 hover:underline text-sm font-medium">+ Tambah Jadwal</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($schedules->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $schedules->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
