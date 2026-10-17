<x-app-layout>
    <x-slot name="header">Verifikasi Operator: {{ $operator->name }}</x-slot>

    <div class="space-y-6" x-data="{ rejectModal: false, previewModal: false, activeDocTitle: '', activeDocUrl: '' }">

        <!-- Top Breadcrumbs & Back Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                    <a href="{{ route('super-admin.operators.index') }}" class="hover:text-indigo-600 transition-colors font-semibold">Operator Travel</a>
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-800 font-bold">{{ $operator->name }}</span>
                </nav>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-heading font-black text-slate-900">{{ $operator->name }}</h1>
                    @if($operator->verification_status === 'verified')
                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold px-3 py-1 rounded-full">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Terverifikasi Resmi
                        </span>
                    @elseif($operator->verification_status === 'pending')
                        <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold px-3 py-1 rounded-full">
                            <span class="w-2 h-2 bg-amber-500 rounded-full animate-ping"></span> Menunggu Verifikasi Dokumen
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-800 border border-rose-200 text-xs font-bold px-3 py-1 rounded-full">
                            <span class="w-2 h-2 bg-rose-500 rounded-full"></span> Pendaftaran Ditolak
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2 self-start sm:self-auto">
                <a href="{{ route('super-admin.operators.edit', $operator) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold rounded-xl transition-all shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Profil
                </a>
                <a href="{{ route('super-admin.operators.index') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                    ← Kembali
                </a>
            </div>
        </div>

        <!-- Alert Alasan Penolakan (Jika Ada) -->
        @if($operator->verification_status === 'rejected' && $operator->rejection_reason)
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs shadow-2xs flex items-start gap-3.5">
            <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="flex-1">
                <p class="font-bold uppercase tracking-wider text-rose-800">Pendaftaran Operator Ditolak</p>
                <p class="text-rose-700 mt-1 leading-relaxed"><span class="font-semibold">Catatan Super Admin:</span> {{ $operator->rejection_reason }}</p>
            </div>
        </div>
        @endif

        <!-- ==================== 2-COLUMN LAYOUT: INFO KIRI & DOKUMEN KANAN ==================== -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- ==================== KOLOM KIRI (INFO PERUSAHAAN & PEMILIK) ==================== -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Card Profil Perusahaan -->
                <div class="bg-white rounded-2xl shadow-soft border border-slate-200/80 p-6 space-y-5">
                    <div class="flex items-center gap-4 pb-5 border-b border-slate-100">
                        @if($operator->logo)
                            <img src="{{ Storage::url($operator->logo) }}" alt="{{ $operator->name }}"
                                 class="w-16 h-16 rounded-2xl object-cover border border-slate-200 shadow-sm flex-shrink-0">
                        @else
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white font-heading font-black text-xl flex items-center justify-center shadow-md shadow-indigo-600/20 flex-shrink-0">
                                {{ strtoupper(substr($operator->name, 0, 2)) }}
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">Mitra Travel</span>
                            <h2 class="text-lg font-heading font-black text-slate-900 mt-1 truncate">{{ $operator->name }}</h2>
                            <p class="text-xs text-slate-400 font-mono">{{ $operator->email }}</p>
                        </div>
                    </div>

                    <!-- Detail Info List -->
                    <div class="space-y-3.5 text-xs">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Pemilik / Penanggung Jawab</span>
                            <p class="text-sm font-bold text-slate-800 mt-0.5 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                {{ $operator->owner_name ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Kontak Telepon / WhatsApp</span>
                            <p class="text-sm font-semibold text-slate-800 mt-0.5 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $operator->phone ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Alamat Kantor Pusat Travel</span>
                            <p class="text-xs text-slate-700 mt-0.5 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100">
                                {{ $operator->address ?? 'Alamat belum dilengkapi.' }}
                            </p>
                        </div>

                        <div class="pt-2 flex items-center justify-between text-slate-400 border-t border-slate-100">
                            <span>Tanggal Registrasi:</span>
                            <span class="font-bold text-slate-700">{{ $operator->created_at->isoFormat('D MMMM Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Statistik Ringkas Operasional -->
                <div class="bg-white rounded-2xl shadow-soft border border-slate-200/80 p-5">
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Kapasitas Mitra Travel</h3>
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <div class="p-3 bg-indigo-50/60 rounded-xl border border-indigo-100">
                            <p class="text-xl font-heading font-black text-indigo-700">{{ $operator->vehicles_count }}</p>
                            <p class="text-[10px] font-bold text-indigo-900 mt-0.5">Armada Mobil</p>
                        </div>
                        <div class="p-3 bg-violet-50/60 rounded-xl border border-violet-100">
                            <p class="text-xl font-heading font-black text-violet-700">{{ $operator->routes_count }}</p>
                            <p class="text-[10px] font-bold text-violet-900 mt-0.5">Rute Perjalanan</p>
                        </div>
                        <div class="p-3 bg-blue-50/60 rounded-xl border border-blue-100">
                            <p class="text-xl font-heading font-black text-blue-700">{{ $operator->pickup_points_count }}</p>
                            <p class="text-[10px] font-bold text-blue-900 mt-0.5">Pool / Titik Jemput</p>
                        </div>
                        <div class="p-3 bg-emerald-50/60 rounded-xl border border-emerald-100">
                            <p class="text-xl font-heading font-black text-emerald-700">{{ $operator->bookings_count }}</p>
                            <p class="text-[10px] font-bold text-emerald-900 mt-0.5">Total Reservasi</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ==================== KOLOM KANAN (PREVIEW DOKUMEN KTP & SIUP) ==================== -->
            <div class="lg:col-span-7 space-y-6">
                
                <div class="bg-white rounded-2xl shadow-soft border border-slate-200/80 p-6 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h2 class="text-base font-heading font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Berkas Dokumen Legalitas
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">Periksa keabsahan scan KTP pemilik dan izin usaha SIUP / NIB operator.</p>
                        </div>
                    </div>

                    <!-- 1. DOKUMEN KTP PEMILIK -->
                    <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50/60">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shadow-xs">1</span>
                                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">KTP Pemilik ({{ $operator->owner_name ?? 'Mitra' }})</h3>
                            </div>
                            @if($operator->document_ktp)
                                <a href="{{ Storage::url($operator->document_ktp) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white border border-slate-200 text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:bg-slate-50 shadow-2xs transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    Buka Ukuran Asli
                                </a>
                            @endif
                        </div>

                        <!-- Preview Container -->
                        <div class="min-h-[220px] bg-white rounded-xl border border-slate-200/90 overflow-hidden flex items-center justify-center p-3">
                            @if($operator->document_ktp)
                                @php
                                    $ktpExt = pathinfo($operator->document_ktp, PATHINFO_EXTENSION);
                                    $isKtpImage = in_array(strtolower($ktpExt), ['jpg', 'jpeg', 'png', 'webp']);
                                @endphp

                                @if($isKtpImage)
                                    <img src="{{ Storage::url($operator->document_ktp) }}" 
                                         alt="Scan KTP {{ $operator->owner_name }}"
                                         class="max-h-64 max-w-full rounded-lg border border-slate-200 shadow-sm object-contain">
                                @else
                                    <div class="text-center p-6">
                                        <svg class="w-14 h-14 text-rose-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        <p class="text-xs font-bold text-slate-800">Dokumen Format PDF</p>
                                        <a href="{{ Storage::url($operator->document_ktp) }}" target="_blank"
                                           class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs transition-colors">
                                            Lihat / Unduh Dokumen PDF
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center p-8 text-slate-400">
                                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="text-xs font-semibold text-slate-500">Berkas KTP Belum Diunggah</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Mitra travel belum menyertakan scan identitas pemilik.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- 2. DOKUMEN SIUP / NIB -->
                    <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50/60">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-violet-600 text-white font-bold text-xs flex items-center justify-center shadow-xs">2</span>
                                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Izin Usaha SIUP / NIB Travel</h3>
                            </div>
                            @if($operator->document_siup)
                                <a href="{{ Storage::url($operator->document_siup) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white border border-slate-200 text-xs font-bold text-violet-600 hover:text-violet-800 hover:bg-slate-50 shadow-2xs transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    Buka Ukuran Asli
                                </a>
                            @endif
                        </div>

                        <!-- Preview Container -->
                        <div class="min-h-[220px] bg-white rounded-xl border border-slate-200/90 overflow-hidden flex items-center justify-center p-3">
                            @if($operator->document_siup)
                                @php
                                    $siupExt = pathinfo($operator->document_siup, PATHINFO_EXTENSION);
                                    $isSiupImage = in_array(strtolower($siupExt), ['jpg', 'jpeg', 'png', 'webp']);
                                @endphp

                                @if($isSiupImage)
                                    <img src="{{ Storage::url($operator->document_siup) }}" 
                                         alt="Scan SIUP {{ $operator->name }}"
                                         class="max-h-64 max-w-full rounded-lg border border-slate-200 shadow-sm object-contain">
                                @else
                                    <div class="text-center p-6">
                                        <svg class="w-14 h-14 text-rose-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        <p class="text-xs font-bold text-slate-800">Dokumen Format PDF</p>
                                        <a href="{{ Storage::url($operator->document_siup) }}" target="_blank"
                                           class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold rounded-xl shadow-xs transition-colors">
                                            Lihat / Unduh Dokumen PDF
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center p-8 text-slate-400">
                                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="text-xs font-semibold text-slate-500">Berkas SIUP/NIB Belum Diunggah</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Mitra travel belum melampirkan izin legalitas usaha.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- ==================== ACTION CARD VERIFIKASI (SANGAT PENTING) ==================== -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-2xl p-6 sm:p-7 text-white shadow-xl border border-indigo-900/40">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="max-w-xl">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-bold border border-indigo-500/30 mb-2">
                        Keputusan Legalitas & Izin Buka Rute
                    </span>
                    <h3 class="text-xl font-heading font-black text-white">Keputusan Verifikasi Operator</h3>
                    <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                        Pastikan seluruh data penanggung jawab dan scan dokumen izin usaha telah valid. Menyetujui operator ini akan mengizinkan pembukaan jadwal tiket di aplikasi mobile pelanggan.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <!-- Tombol Setujui & Verifikasi (Hijau Besar) -->
                    @if($operator->verification_status !== 'verified')
                    <form action="{{ route('super-admin.operators.status', $operator) }}" method="POST"
                          onsubmit="return confirm('Apakah Anda yakin dokumen sah dan ingin menyetujui akun operator {{ $operator->name }}?')">
                        @csrf
                        <input type="hidden" name="verification_status" value="verified">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-heading font-black text-xs shadow-lg shadow-emerald-600/30 transition-all hover:scale-105">
                            <svg class="w-5 h-5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Verifikasi & Setujui Akun
                        </button>
                    </form>
                    @endif

                    <!-- Tombol Tolak Pendaftaran (Merah Outline) -->
                    @if($operator->verification_status !== 'rejected')
                    <button type="button" @click="rejectModal = true"
                            class="inline-flex items-center gap-2 px-5 py-3.5 rounded-xl bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white border border-rose-500/40 text-xs font-bold transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Tolak Pendaftaran
                    </button>
                    @endif

                    <!-- Reset ke Pending jika sudah verified/rejected -->
                    @if($operator->verification_status !== 'pending')
                    <form action="{{ route('super-admin.operators.status', $operator) }}" method="POST">
                        @csrf
                        <input type="hidden" name="verification_status" value="pending">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold border border-slate-700 transition-colors">
                            Kembalikan ke Status Pending
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- ==================== MODAL FORM TOLAK PENDAFTARAN ==================== -->
        <div x-show="rejectModal" x-cloak
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-xs">
            <div @click.away="rejectModal = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white rounded-2xl max-w-lg w-full p-6 text-slate-800 shadow-2xl relative border border-slate-200">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-heading font-black text-base text-slate-900">Tolak Berkas Pendaftaran Travel</h3>
                        <p class="text-xs text-slate-500">Berikan catatan alasan yang jelas agar mitra travel dapat memperbaiki dokumennya.</p>
                    </div>
                </div>

                <form action="{{ route('super-admin.operators.status', $operator) }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="verification_status" value="rejected">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Alasan Penolakan <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="rejection_reason" rows="4" required
                                  placeholder="Contoh: Dokumen KTP pemilik buram / scan SIUP tidak mencantumkan izin trayek angkutan orang antar kota..."
                                  class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:outline-hidden focus:ring-2 focus:ring-rose-500 focus:border-rose-500"></textarea>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="rejectModal = false"
                                class="px-4 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-600 hover:bg-slate-50">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-xs font-black text-white shadow-xs">
                            Kirim Penolakan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ==================== AKUN TERDAFTAR (ADMIN & DRIVER) ==================== -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Admin Travel -->
            <div class="bg-white rounded-2xl shadow-soft border border-slate-200/80 p-5">
                <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                    <div>
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Admin Mitra Terdaftar</h3>
                        <p class="text-[11px] text-slate-400">{{ $adminUsers->count() }} akun terhubung</p>
                    </div>
                    <a href="{{ route('super-admin.users.create', ['operator_id' => $operator->id, 'role' => 'admin_travel']) }}"
                       class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-lg transition-colors">
                        + Tambah Admin
                    </a>
                </div>
                <div class="divide-y divide-slate-100 text-xs">
                    @forelse($adminUsers as $admin)
                    <div class="py-2.5 flex items-center justify-between">
                        <div>
                            <a href="{{ route('super-admin.users.show', $admin) }}" class="font-bold text-slate-800 hover:text-indigo-600 transition-colors">
                                {{ $admin->name }}
                            </a>
                            <p class="text-slate-400 text-[11px] font-mono">{{ $admin->email }}</p>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $admin->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700' }}">
                            {{ ucfirst($admin->status) }}
                        </span>
                    </div>
                    @empty
                    <p class="py-6 text-center text-slate-400 text-xs">Belum ada akun Admin Travel untuk operator ini.</p>
                    @endforelse
                </div>
            </div>

            <!-- Driver Travel -->
            <div class="bg-white rounded-2xl shadow-soft border border-slate-200/80 p-5">
                <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                    <div>
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Driver Travel Terdaftar</h3>
                        <p class="text-[11px] text-slate-400">{{ $drivers->count() }} driver terdaftar</p>
                    </div>
                    <a href="{{ route('super-admin.users.create', ['operator_id' => $operator->id, 'role' => 'driver']) }}"
                       class="inline-flex items-center gap-1 px-3 py-1 bg-violet-50 hover:bg-violet-100 text-violet-700 text-xs font-bold rounded-lg transition-colors">
                        + Tambah Driver
                    </a>
                </div>
                <div class="divide-y divide-slate-100 text-xs">
                    @forelse($drivers as $driver)
                    <div class="py-2.5 flex items-center justify-between">
                        <div>
                            <a href="{{ route('super-admin.users.show', $driver) }}" class="font-bold text-slate-800 hover:text-violet-600 transition-colors">
                                {{ $driver->name }}
                            </a>
                            <p class="text-slate-400 text-[11px]">{{ $driver->phone ?? $driver->email }}</p>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $driver->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700' }}">
                            {{ ucfirst($driver->status) }}
                        </span>
                    </div>
                    @empty
                    <p class="py-6 text-center text-slate-400 text-xs">Belum ada driver yang didaftarkan.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
