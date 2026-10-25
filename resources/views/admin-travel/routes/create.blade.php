<x-app-layout>
    <x-slot name="header">Tambah Rute Baru</x-slot>

    <div class="max-w-2xl">
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
                <a href="{{ route('admin-travel.routes.index') }}" class="hover:text-blue-600">Rute</a>
                <span>/</span>
                <span class="text-slate-800 font-medium">Tambah Rute</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Tambah Rute Baru</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <form action="{{ route('admin-travel.routes.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Kota Asal -->
                <div>
                    <label for="origin" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Kota Asal <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="origin" id="origin"
                           value="{{ old('origin') }}"
                           placeholder="cth: Jakarta, Medan, Lhokseumawe"
                           class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('origin') border-red-400 @enderror">
                    @error('origin')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kota Tujuan -->
                <div>
                    <label for="destination" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Kota Tujuan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="destination" id="destination"
                           value="{{ old('destination') }}"
                           placeholder="cth: Bandung, Banda Aceh, Takengon"
                           class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('destination') border-red-400 @enderror">
                    @error('destination')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga & Estimasi (2 kolom) -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Harga (Rp) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">Rp</span>
                            <input type="number" name="price" id="price" min="0" step="1000"
                                   value="{{ old('price') }}"
                                   placeholder="0"
                                   class="w-full pl-9 pr-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-400 @enderror">
                        </div>
                        @error('price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="estimated_duration" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Estimasi Waktu
                        </label>
                        <input type="text" name="estimated_duration" id="estimated_duration"
                               value="{{ old('estimated_duration') }}"
                               placeholder="cth: 3 jam 30 menit"
                               class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('estimated_duration') border-red-400 @enderror">
                        @error('estimated_duration')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status Aktif -->
                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                    <label for="is_active" class="text-sm font-medium text-slate-700">
                        Aktifkan rute ini
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-3 pt-2 border-t border-slate-100">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
                        Simpan Rute
                    </button>
                    <a href="{{ route('admin-travel.routes.index') }}"
                       class="text-slate-600 hover:text-slate-800 text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-slate-100 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
