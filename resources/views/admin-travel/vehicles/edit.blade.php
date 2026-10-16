<x-app-layout>
    <x-slot name="header">Edit Armada: {{ $vehicle->plate_number }}</x-slot>

    <div class="max-w-3xl mx-auto">
        <!-- Breadcrumb & Header -->
        <div class="mb-6">
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                <a href="{{ route('admin-travel.vehicles.index') }}" class="hover:text-blue-600 transition-colors">Armada Kendaraan</a>
                <span>/</span>
                <span class="text-slate-800 font-medium">{{ $vehicle->plate_number }}</span>
                <span>/</span>
                <span class="text-slate-800 font-medium">Edit</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-800">Edit Armada Kendaraan</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui data plat nomor, kapasitas penumpang, atau status perawatan armada.</p>
        </div>

        @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
            <div class="flex items-center gap-2 font-semibold mb-1">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Terdapat beberapa kesalahan input:
            </div>
            <ul class="list-disc list-inside text-xs space-y-1 ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin-travel.vehicles.update', $vehicle) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Plat Nomor -->
                    <div>
                        <label for="plate_number" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Plat Nomor Kendaraan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="plate_number" id="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" required
                               class="w-full px-3.5 py-2.5 text-sm uppercase font-mono tracking-wider border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Merk / Tipe Kendaraan -->
                    <div>
                        <label for="brand" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Merk / Tipe Mobil <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="brand" id="brand" value="{{ old('brand', $vehicle->brand) }}" required
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Kapasitas Kursi -->
                    <div>
                        <label for="capacity" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Kapasitas Kursi Penumpang <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $vehicle->capacity) }}" min="1" max="100" required
                                   class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-16">
                            <span class="absolute right-3.5 top-2.5 text-xs text-slate-400 font-medium">Kursi</span>
                        </div>
                    </div>

                    <!-- Status Armada -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Status Armada Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3" x-data="{ selectedStatus: '{{ old('status', $vehicle->status) }}' }">
                            <label class="cursor-pointer border rounded-xl p-3 flex items-start gap-3 transition-colors"
                                   :class="selectedStatus === 'available' ? 'border-emerald-500 bg-emerald-50/50 ring-1 ring-emerald-500' : 'border-slate-200 hover:bg-slate-50'">
                                <input type="radio" name="status" value="available" x-model="selectedStatus" class="mt-0.5 text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <p class="text-xs font-bold text-emerald-800">Siap Operasi</p>
                                    <p class="text-[11px] text-emerald-600 mt-0.5">Mobil siap jalan dan dapat dijadwalkan.</p>
                                </div>
                            </label>

                            <label class="cursor-pointer border rounded-xl p-3 flex items-start gap-3 transition-colors"
                                   :class="selectedStatus === 'maintenance' ? 'border-amber-500 bg-amber-50/50 ring-1 ring-amber-500' : 'border-slate-200 hover:bg-slate-50'">
                                <input type="radio" name="status" value="maintenance" x-model="selectedStatus" class="mt-0.5 text-amber-500 focus:ring-amber-400">
                                <div>
                                    <p class="text-xs font-bold text-amber-800">Perawatan / Servis</p>
                                    <p class="text-[11px] text-amber-600 mt-0.5">Sedang dalam perbaikan rutin di bengkel.</p>
                                </div>
                            </label>

                            <label class="cursor-pointer border rounded-xl p-3 flex items-start gap-3 transition-colors"
                                   :class="selectedStatus === 'inactive' ? 'border-slate-400 bg-slate-50 ring-1 ring-slate-400' : 'border-slate-200 hover:bg-slate-50'">
                                <input type="radio" name="status" value="inactive" x-model="selectedStatus" class="mt-0.5 text-slate-600 focus:ring-slate-500">
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Nonaktif</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Mobil sementara tidak dioperasikan.</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Fasilitas & Catatan -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Fasilitas Kendaraan / Catatan Tambahan
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $vehicle->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin-travel.vehicles.index') }}"
                   class="px-5 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition-colors shadow-sm inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
