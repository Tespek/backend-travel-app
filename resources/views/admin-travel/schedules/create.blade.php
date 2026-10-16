<x-app-layout>
    <x-slot name="header">Tambah Jadwal Baru</x-slot>

    <div class="max-w-2xl">
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
                <a href="{{ route('admin-travel.schedules.index') }}" class="hover:text-blue-600">Jadwal</a>
                <span>/</span>
                <span class="text-slate-800 font-medium">Tambah Jadwal</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Tambah Jadwal Baru</h1>
        </div>

        @if($routes->isEmpty())
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <p class="font-semibold text-amber-800 text-sm">Belum ada rute aktif</p>
                <p class="text-amber-700 text-sm mt-1">Anda harus membuat rute terlebih dahulu sebelum menambahkan jadwal.</p>
                <a href="{{ route('admin-travel.routes.create') }}" class="inline-block mt-2 text-sm font-medium text-amber-700 underline">Buat Rute Sekarang →</a>
            </div>
        </div>
        @else

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <form action="{{ route('admin-travel.schedules.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Rute -->
                <div>
                    <label for="route_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Rute Perjalanan <span class="text-red-500">*</span>
                    </label>
                    <select name="route_id" id="route_id"
                            class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('route_id') border-red-400 @enderror">
                        <option value="">-- Pilih Rute --</option>
                        @foreach($routes as $route)
                            <option value="{{ $route->id }}" {{ old('route_id') == $route->id ? 'selected' : '' }}>
                                {{ $route->originCity->name }} → {{ $route->destinationCity->name }}
                                (Rp {{ number_format($route->price, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                    @error('route_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kendaraan -->
                <div>
                    <label for="vehicle_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Armada Kendaraan <span class="text-red-500">*</span>
                    </label>
                    @if($vehicles->isEmpty())
                        <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-sm text-red-700">
                            Belum ada kendaraan dengan status "Tersedia". Silakan tambahkan kendaraan terlebih dahulu.
                        </div>
                    @else
                    <select name="vehicle_id" id="vehicle_id"
                            class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vehicle_id') border-red-400 @enderror">
                        <option value="">-- Pilih Kendaraan --</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->brand }} — {{ $vehicle->plate_number }} ({{ $vehicle->capacity }} kursi)
                            </option>
                        @endforeach
                    </select>
                    @endif
                    @error('vehicle_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal & Jam -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="departure_date" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Tanggal Berangkat <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="departure_date" id="departure_date"
                               value="{{ old('departure_date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('departure_date') border-red-400 @enderror">
                        @error('departure_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="departure_time" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Jam Berangkat <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="departure_time" id="departure_time"
                               value="{{ old('departure_time') }}"
                               class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('departure_time') border-red-400 @enderror">
                        @error('departure_time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Kursi & Status -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="available_seats" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Kursi Tersedia <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="available_seats" id="available_seats" min="1" max="100"
                               value="{{ old('available_seats') }}"
                               placeholder="0"
                               class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('available_seats') border-red-400 @enderror">
                        @error('available_seats')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status"
                                class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-400 @enderror">
                            <option value="open" {{ old('status', 'open') == 'open' ? 'selected' : '' }}>Terbuka</option>
                            <option value="full" {{ old('status') == 'full' ? 'selected' : '' }}>Penuh</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-3 pt-2 border-t border-slate-100">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
                        Simpan Jadwal
                    </button>
                    <a href="{{ route('admin-travel.schedules.index') }}"
                       class="text-slate-600 hover:text-slate-800 text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-slate-100 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
        @endif
    </div>
</x-app-layout>
