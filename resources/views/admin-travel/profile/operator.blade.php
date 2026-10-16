<x-app-layout>
    <x-slot name="header">Profil & Legalitas Travel</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Profil & Dokumen Legalitas Travel</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola informasi publik travel dan unggah dokumen KTP/SIUP untuk proses verifikasi platform.</p>
            </div>
            <div class="self-start sm:self-auto">
                @if($operator->verification_status === 'verified')
                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold px-3 py-1.5 rounded-full">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Terverifikasi Resmi
                    </span>
                @elseif($operator->verification_status === 'pending')
                    <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold px-3 py-1.5 rounded-full">
                        <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span> Menunggu Peninjauan Admin
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold px-3 py-1.5 rounded-full">
                        <span class="w-2 h-2 bg-rose-500 rounded-full"></span> Perlu Perbaikan Dokumen
                    </span>
                @endif
            </div>
        </div>

        <!-- Alert Status Verifikasi -->
        @if($operator->verification_status === 'rejected')
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-sm flex items-start gap-3">
            <svg class="w-5 h-5 text-rose-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <p class="font-bold text-xs uppercase tracking-wider text-rose-700">Pendaftaran Travel Belum Disetujui</p>
                <p class="text-xs text-rose-800 mt-1">
                    <span class="font-semibold">Catatan dari Super Admin:</span> {{ $operator->rejection_reason ?? 'Dokumen tidak valid atau tidak terbaca.' }}
                </p>
                <p class="text-xs text-rose-700 mt-2 font-medium">
                    Silakan unggah kembali dokumen KTP atau SIUP yang baru dan jelas melalui formulir di bawah ini. Status akan otomatis diajukan kembali untuk peninjauan ulang.
                </p>
            </div>
        </div>
        @elseif($operator->verification_status === 'pending')
        <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-sm flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-bold text-xs uppercase tracking-wider text-amber-800">Menunggu Verifikasi Legalitas</p>
                <p class="text-xs text-amber-700 mt-1">
                    Dokumen legalitas operator travel Anda sedang dalam proses peninjauan oleh tim Super Admin platform. Anda tetap dapat melengkapi data armada, rute, dan driver sembari menunggu persetujuan.
                </p>
            </div>
        </div>
        @endif

        <form action="{{ route('admin-travel.operator-profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Informasi Tetap (Read-Only) & Kontak -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-base font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Informasi Bisnis & Operasional Travel
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                            Nama Operator Travel
                        </label>
                        <input type="text" value="{{ $operator->name }}" disabled
                               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-lg text-slate-600 cursor-not-allowed">
                        <p class="text-[11px] text-slate-400 mt-1">Hubungi Super Admin jika ingin mengubah nama badan travel.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                            Email Terdaftar
                        </label>
                        <input type="email" value="{{ $operator->email }}" disabled
                               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-lg text-slate-600 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                            Nama Pemilik / Penanggung Jawab
                        </label>
                        <input type="text" value="{{ $operator->owner_name }}" disabled
                               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-lg text-slate-600 cursor-not-allowed">
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nomor Telepon / WhatsApp CS Travel
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $operator->phone) }}"
                               placeholder="081234567890"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Alamat Kantor Pusat / Pool Utama
                        </label>
                        <textarea name="address" id="address" rows="3"
                                  placeholder="Alamat lengkap pool travel..."
                                  class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address', $operator->address) }}</textarea>
                    </div>

                    <!-- Upload Logo -->
                    <div class="md:col-span-2" x-data="{ logoPreview: '{{ $operator->logo ? Storage::url($operator->logo) : '' }}' }">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Logo Travel
                        </label>
                        <div class="flex items-center gap-4">
                            <template x-if="logoPreview">
                                <img :src="logoPreview" class="w-16 h-16 rounded-xl object-cover border border-slate-200 shadow-2xs">
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
                                <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, WEBP (Maks. 2MB). Rekomendasi rasio 1:1 untuk aplikasi mobile.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload / Update Dokumen KTP & SIUP -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-base font-bold text-slate-800 mb-2 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Unggah Dokumen Legalitas (KTP & SIUP)
                </h2>
                <p class="text-xs text-slate-500 mb-5">Dokumen ini digunakan oleh Super Admin untuk memeriksa keaslian izin operasi travel Anda.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Dokumen KTP -->
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold">1</span>
                                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">KTP Pemilik Travel</h3>
                                </div>
                                @if($operator->document_ktp)
                                    <a href="{{ Storage::url($operator->document_ktp) }}" target="_blank"
                                       class="text-[11px] font-semibold text-blue-600 hover:text-blue-800 inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        Lihat File
                                    </a>
                                @else
                                    <span class="text-[11px] text-rose-600 font-semibold">Wajib Diunggah</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 mb-3">Foto/scan KTP asli penanggung jawab sesuai nama pemilik.</p>
                        </div>
                        <div>
                            <input type="file" name="document_ktp" id="document_ktp" accept=".jpg,.jpeg,.png,.pdf"
                                   class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                            <p class="text-[11px] text-slate-400 mt-2">Maks. 5MB (JPG, PNG, PDF).</p>
                        </div>
                    </div>

                    <!-- Dokumen SIUP -->
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold">2</span>
                                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">SIUP / NIB / Izin Usaha</h3>
                                </div>
                                @if($operator->document_siup)
                                    <a href="{{ Storage::url($operator->document_siup) }}" target="_blank"
                                       class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-800 inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        Lihat File
                                    </a>
                                @else
                                    <span class="text-[11px] text-rose-600 font-semibold">Wajib Diunggah</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 mb-3">Surat Izin Usaha Perdagangan atau Nomor Induk Berusaha (NIB) travel.</p>
                        </div>
                        <div>
                            <input type="file" name="document_siup" id="document_siup" accept=".jpg,.jpeg,.png,.pdf"
                                   class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
                            <p class="text-[11px] text-slate-400 mt-2">Maks. 5MB (JPG, PNG, PDF).</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition-colors shadow-sm inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan & Ajukan Pembaruan Dokumen
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
