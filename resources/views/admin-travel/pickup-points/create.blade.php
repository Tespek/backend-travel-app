<x-app-layout>
    <x-slot name="header">Tambah Titik Penjemputan</x-slot>

    <div class="max-w-3xl mx-auto">
        <!-- Breadcrumb & Header -->
        <div class="mb-6">
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                <a href="{{ route('admin-travel.pickup-points.index') }}" class="hover:text-blue-600 transition-colors">Titik Penjemputan</a>
                <span>/</span>
                <span class="text-slate-800 font-medium">Tambah Baru</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-800">Tambah Titik Penjemputan</h1>
            <p class="text-sm text-slate-500 mt-1">Daftarkan lokasi pool atau titik jemput resmi travel Anda agar penumpang dapat memilih lokasi keberangkatan.</p>
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

        <form action="{{ route('admin-travel.pickup-points.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Pilihan Kota -->
                    <div>
                        <label for="city_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Kota Lokasi Pool <span class="text-rose-500">*</span>
                        </label>
                        <select name="city_id" id="city_id" required
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Kota --</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nama Titik Jemput -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Titik Jemput / Pool <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               placeholder="Contoh: Pool Dipatiukur, SPBU Pasteur, Shelter Bandara"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Alamat Lengkap -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Alamat Lengkap Lokasi <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="address" id="address" rows="3" required
                                  placeholder="Alamat jalan lengkap, patokan gedung, atau nomor bangunan..."
                                  class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address') }}</textarea>
                    </div>

                    <!-- Koordinat GPS -->
                    <div>
                        <label for="latitude" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Latitude (Garis Lintang) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude', -6.917464) }}" required
                               placeholder="Contoh: -6.917464"
                               class="w-full px-3.5 py-2.5 text-sm font-mono border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-[11px] text-slate-400 mt-1">Dapat disalin dari Google Maps (angka pertama).</p>
                    </div>

                    <div>
                        <label for="longitude" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Longitude (Garis Bujur) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude', 107.619122) }}" required
                               placeholder="Contoh: 107.619122"
                               class="w-full px-3.5 py-2.5 text-sm font-mono border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-[11px] text-slate-400 mt-1">Dapat disalin dari Google Maps (angka kedua).</p>
                    </div>

                    <!-- Status Aktif -->
                    <div class="md:col-span-2 pt-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                            <div>
                                <p class="text-xs font-semibold text-slate-800">Aktifkan Titik Penjemputan Ini</p>
                                <p class="text-[11px] text-slate-400">Titik yang aktif akan tampil sebagai pilihan penjemputan penumpang di aplikasi mobile.</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin-travel.pickup-points.index') }}"
                   class="px-5 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition-colors shadow-sm inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Titik Penjemputan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
