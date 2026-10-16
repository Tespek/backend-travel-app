<x-app-layout>
    <x-slot name="header">Tambah Pengguna</x-slot>

    <div class="max-w-3xl mx-auto">
        <!-- Breadcrumb & Header -->
        <div class="mb-6">
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                <a href="{{ route('super-admin.users.index') }}" class="hover:text-blue-600 transition-colors">Manajemen User</a>
                <span>/</span>
                <span class="text-slate-800 font-medium">Tambah Baru</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-800">Tambah Pengguna Baru</h1>
            <p class="text-sm text-slate-500 mt-1">Daftarkan akun baru untuk Admin Mitra Travel, Driver, Customer, atau Super Admin.</p>
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

        <form action="{{ route('super-admin.users.store') }}" method="POST" class="space-y-6" x-data="{ selectedRole: '{{ old('role', 'customer') }}' }">
            @csrf

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nomor Telepon / WhatsApp
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Peran (Role) -->
                    <div>
                        <label for="role" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Peran Pengguna (Role) <span class="text-rose-500">*</span>
                        </label>
                        <select name="role" id="role" x-model="selectedRole" required
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="customer">Customer (Penumpang)</option>
                            <option value="admin_travel">Admin Travel (Pengelola Mitra)</option>
                            <option value="driver">Driver (Pengemudi)</option>
                            <option value="super_admin">Super Admin (Platform)</option>
                        </select>
                    </div>

                    <!-- Mitra Operator (Muncul jika role admin_travel atau driver) -->
                    <div class="md:col-span-2" x-show="selectedRole === 'admin_travel' || selectedRole === 'driver'" x-cloak>
                        <label for="operator_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Pilih Mitra Operator Travel <span class="text-rose-500">*</span>
                        </label>
                        <select name="operator_id" id="operator_id"
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Mitra Operator --</option>
                            @foreach($operators as $op)
                                <option value="{{ $op->id }}" {{ old('operator_id') == $op->id ? 'selected' : '' }}>
                                    {{ $op->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Password <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Konfirmasi Password <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Status Akun -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Status Akun <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-800">
                                <input type="radio" name="status" value="active" {{ old('status', 'active') === 'active' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                Aktif (Diizinkan login)
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-800">
                                <input type="radio" name="status" value="banned" {{ old('status') === 'banned' ? 'checked' : '' }} class="text-rose-600 focus:ring-rose-500">
                                Diblokir (Banned)
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('super-admin.users.index') }}"
                   class="px-5 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition-colors shadow-sm inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Pengguna Baru
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
