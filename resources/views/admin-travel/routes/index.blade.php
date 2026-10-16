<x-app-layout>
    <x-slot name="header">Manajemen Rute</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Rute Perjalanan</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola rute perjalanan untuk operator Anda.</p>
        </div>
        <a href="{{ route('admin-travel.routes.create') }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Rute
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">#</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">Rute</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">Harga</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">Estimasi</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">Status</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">Jadwal</th>
                        <th class="text-right text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($routes as $route)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-slate-400 text-xs">{{ $loop->start + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-slate-800">{{ $route->originCity->name }}</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                                <span class="font-semibold text-slate-800">{{ $route->destinationCity->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-semibold text-green-600">
                            Rp {{ number_format($route->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $route->estimated_duration ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($route->is_active)
                                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-medium px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin-travel.schedules.index', ['route_id' => $route->id]) }}"
                               class="text-blue-600 hover:underline text-xs font-medium">
                                {{ $route->schedules_count ?? $route->schedules()->count() }} jadwal
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin-travel.routes.edit', $route) }}"
                                   class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin-travel.routes.destroy', $route) }}" method="POST"
                                      onsubmit="return confirm('Hapus rute ini? Semua jadwal terkait juga akan dihapus.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                    </svg>
                                </div>
                                <p class="text-slate-500 font-medium">Belum ada rute</p>
                                <p class="text-slate-400 text-sm">Mulai tambah rute perjalanan pertama Anda.</p>
                                <a href="{{ route('admin-travel.routes.create') }}" class="mt-2 text-blue-600 hover:underline text-sm font-medium">+ Tambah Rute</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($routes->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $routes->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
