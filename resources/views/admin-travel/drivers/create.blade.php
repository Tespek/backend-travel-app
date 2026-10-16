<x-app-layout>
    <x-slot name="header">Tambah Driver</x-slot>

    <div class="max-w-2xl">
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
                <a href="{{ route('admin-travel.drivers.index') }}" class="hover:text-blue-600">Driver</a>
                <span>/</span>
                <span class="text-slate-800 font-medium">Tambah Driver</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Daftarkan Driver Baru</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <form action="{{ route('admin-travel.drivers.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                           placeholder="Masukkan nama lengkap driver"
                           class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           placeholder="email@example.com"
                           class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Nomor Telepon
                    </label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                           placeholder="cth: 08123456789"
                           class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-400 @enderror">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="password"
                               placeholder="Min. 8 karakter"
                               class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-400 @enderror">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               placeholder="Ulangi password"
                               class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-lg px-4 py-3 text-sm text-blue-700">
                    <strong>Info:</strong> Driver akan login menggunakan email dan password ini melalui aplikasi mobile.
                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-3 pt-2 border-t border-slate-100">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
                        Daftarkan Driver
                    </button>
                    <a href="{{ route('admin-travel.drivers.index') }}"
                       class="text-slate-600 hover:text-slate-800 text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-slate-100 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
