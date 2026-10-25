<x-app-layout>
    <x-slot name="header">Edit Jadwal</x-slot>

    <div class="max-w-2xl">
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
                <a href="{{ route('admin-travel.schedules.index') }}" class="hover:text-blue-600">Jadwal</a>
                <span>/</span>
                <span class="text-slate-800 font-medium">Edit Jadwal</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Jadwal Keberangkatan</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <form action="{{ route('admin-travel.schedules.update', $schedule) }}" method="POST" class="space-y-5">
                @csrf @method('PUT')

                <!-- Rute -->
                <div>
                    <label for="route_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Rute Perjalanan <span class="text-red-500">*</span>
                    </label>
                    <select name="route_id" id="route_id"
                            class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('route_id') border-red-400 @enderror">
                        <option value="">-- Pilih Rute --</option>
                        @foreach($routes as $route)
                            <option value="{{ $route->id }}"
                                {{ old('route_id', $schedule->route_id) == $route->id ? 'selected' : '' }}>
                                {{ $route->origin_name }} → {{ $route->destination_name }}
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
                    <select name="vehicle_id" id="vehicle_id"
                            class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vehicle_id') border-red-400 @enderror">
                        <option value="">-- Pilih Kendaraan --</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}"
                                {{ old('vehicle_id', $schedule->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->brand }} — {{ $vehicle->plate_number }} ({{ $vehicle->capacity }} kursi)
                            </option>
                        @endforeach
                    </select>
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
                               value="{{ old('departure_date', $schedule->departure_date) }}"
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
                               value="{{ old('departure_time', \Carbon\Carbon::parse($schedule->departure_time)->format('H:i')) }}"
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
                        <input type="number" name="available_seats" id="available_seats" min="0" max="100"
                               value="{{ old('available_seats', $schedule->available_seats) }}"
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
                            @foreach(['open' => 'Terbuka', 'full' => 'Penuh', 'cancelled' => 'Dibatalkan', 'completed' => 'Selesai'] as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $schedule->status) == $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
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
                        Perbarui Jadwal
                    </button>
                    <a href="{{ route('admin-travel.schedules.index') }}"
                       class="text-slate-600 hover:text-slate-800 text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-slate-100 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
