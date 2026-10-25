<x-app-layout>
    <x-slot name="header">Tambah Operator Travel</x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb & Header -->
        <div class="mb-6">
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                <a href="{{ route('super-admin.operators.index') }}" class="hover:text-blue-600 transition-colors">Operator Travel</a>
                <span>/</span>
                <span class="text-slate-800 font-medium">Tambah Baru</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-800">Tambah Operator Travel</h1>
            <p class="text-sm text-slate-500 mt-1">Daftarkan operator mitra travel baru ke dalam sistem platform.</p>
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

        <form action="{{ route('super-admin.operators.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Card 1: Informasi Umum Perusahaan -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-base font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Informasi Profil Perusahaan / Operator
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Operator Travel <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               placeholder="Contoh: DayTrans, Baraya Travel, Cipaganti"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Email Resmi Travel <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               placeholder="kontak@travel.com"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="owner_name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Pemilik / Penanggung Jawab <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="owner_name" id="owner_name" value="{{ old('owner_name') }}" required
                               placeholder="Nama lengkap penanggung jawab"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nomor Telepon / WhatsApp
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                               placeholder="081234567890"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Alamat Kantor Pusat / Operasional
                        </label>
                        <textarea name="address" id="address" rows="3"
                                  placeholder="Alamat lengkap kantor pusat travel..."
                                  class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address') }}</textarea>
                    </div>

                    <!-- Upload Logo Perusahaan -->
                    <div class="md:col-span-2" x-data="{ logoPreview: null }">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Logo Operator Travel
                        </label>
                        <div class="flex items-center gap-4">
                            <template x-if="logoPreview">
                                <img :src="logoPreview" class="w-16 h-16 rounded-xl object-cover border border-slate-200">
                            </template>
                            <template x-if="!logoPreview">
                                <div class="w-16 h-16 rounded-xl bg-slate-100 border border-dashed border-slate-300 flex items-center justify-center text-slate-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </template>
                            <div class="flex-1">
                                <input type="file" name="logo" id="logo" accept="image/*"
                                       @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => logoPreview = e.target.result; reader.readAsDataURL(file); }"
                                       class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, WEBP (Maks. 2MB). Rekomendasi rasio 1:1.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Status Verifikasi Awal -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6" x-data="{ currentStatus: '{{ old('verification_status', 'pending') }}' }">
                <h2 class="text-base font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Status Verifikasi Awal
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <label class="cursor-pointer border rounded-xl p-3 flex items-start gap-3 transition-colors"
                           :class="currentStatus === 'pending' ? 'border-amber-500 bg-amber-50/50 ring-1 ring-amber-500' : 'border-slate-200 hover:bg-slate-50'">
                        <input type="radio" name="verification_status" value="pending" x-model="currentStatus" class="mt-0.5 text-amber-500 focus:ring-amber-400">
                        <div>
                            <p class="text-xs font-bold text-amber-800">Menunggu (Pending)</p>
                            <p class="text-[11px] text-amber-600 mt-0.5">Akun perlu ditinjau sebelum aktivasi rute & booking.</p>
                        </div>
                    </label>

                    <label class="cursor-pointer border rounded-xl p-3 flex items-start gap-3 transition-colors"
                           :class="currentStatus === 'verified' ? 'border-emerald-500 bg-emerald-50/50 ring-1 ring-emerald-500' : 'border-slate-200 hover:bg-slate-50'">
                        <input type="radio" name="verification_status" value="verified" x-model="currentStatus" class="mt-0.5 text-emerald-600 focus:ring-emerald-500">
                        <div>
                            <p class="text-xs font-bold text-emerald-800">Terverifikasi (Verified)</p>
                            <p class="text-[11px] text-emerald-600 mt-0.5">Operator langsung aktif dan siap beroperasi.</p>
                        </div>
                    </label>

                    <label class="cursor-pointer border rounded-xl p-3 flex items-start gap-3 transition-colors"
                           :class="currentStatus === 'rejected' ? 'border-rose-500 bg-rose-50/50 ring-1 ring-rose-500' : 'border-slate-200 hover:bg-slate-50'">
                        <input type="radio" name="verification_status" value="rejected" x-model="currentStatus" class="mt-0.5 text-rose-600 focus:ring-rose-500">
                        <div>
                            <p class="text-xs font-bold text-rose-800">Ditolak (Rejected)</p>
                            <p class="text-[11px] text-rose-600 mt-0.5">Tidak memenuhi syarat operasional platform.</p>
                        </div>
                    </label>
                </div>

                <!-- Input Alasan Penolakan jika rejected -->
                <div x-show="currentStatus === 'rejected'" x-cloak class="mt-4 pt-4 border-t border-slate-100">
                    <label for="rejection_reason" class="block text-xs font-semibold text-rose-700 uppercase tracking-wider mb-1.5">
                        Alasan Penolakan <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="2"
                              placeholder="Tuliskan catatan mengapa pendaftaran operator ini ditolak..."
                              class="w-full px-3.5 py-2 text-sm border border-rose-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500">{{ old('rejection_reason') }}</textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('super-admin.operators.index') }}"
                   class="px-5 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition-colors shadow-sm inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Operator Baru
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
