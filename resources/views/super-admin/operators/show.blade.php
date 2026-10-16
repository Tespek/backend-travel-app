<x-app-layout>
    <x-slot name="header">Detail Operator: {{ $operator->name }}</x-slot>

    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Breadcrumb & Top Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                    <a href="{{ route('super-admin.operators.index') }}" class="hover:text-blue-600 transition-colors">Operator Travel</a>
                    <span>/</span>
                    <span class="text-slate-800 font-medium">{{ $operator->name }}</span>
                </nav>
                <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                    {{ $operator->name }}
                    @if($operator->verification_status === 'verified')
                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Terverifikasi
                        </span>
                    @elseif($operator->verification_status === 'pending')
                        <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span> Menunggu Verifikasi
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> Ditolak
                        </span>
                    @endif
                </h1>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('super-admin.operators.edit', $operator) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-semibold rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Operator
                </a>
                <a href="{{ route('super-admin.operators.index') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                    Kembali
                </a>
            </div>
        </div>

        <!-- Alasan Penolakan Alert jika ditolak -->
        @if($operator->verification_status === 'rejected' && $operator->rejection_reason)
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-start gap-3">
            <svg class="w-5 h-5 text-rose-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-bold text-xs uppercase tracking-wider text-rose-700">Pendaftaran Ditolak</p>
                <p class="text-xs text-rose-800 mt-1"><span class="font-semibold">Catatan / Alasan:</span> {{ $operator->rejection_reason }}</p>
            </div>
        </div>
        @endif

        <!-- Card Aksi Verifikasi Cepat (Khusus Super Admin) -->
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl p-6 text-white shadow-md" x-data="{ rejectModal: false }">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold">Verifikasi Legalitas & Izin Operasi</h2>
                    <p class="text-xs text-slate-300 mt-1 max-w-xl">
                        Tinjau kelengkapan dokumen KTP pemilik dan SIUP/NIB di bawah sebelum menyetujui aktivasi akun operator travel ini.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    @if($operator->verification_status !== 'verified')
                    <form action="{{ route('super-admin.operators.status', $operator) }}" method="POST"
                          onsubmit="return confirm('Verifikasi dan setujui operasional travel {{ $operator->name }}?')">
                        @csrf
                        <input type="hidden" name="verification_status" value="verified">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Setujui & Verifikasi
                        </button>
                    </form>
                    @endif

                    @if($operator->verification_status !== 'rejected')
                    <button type="button" @click="rejectModal = true"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Tolak Pendaftaran
                    </button>
                    @endif

                    @if($operator->verification_status !== 'pending')
                    <form action="{{ route('super-admin.operators.status', $operator) }}" method="POST">
                        @csrf
                        <input type="hidden" name="verification_status" value="pending">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs font-semibold transition-colors">
                            Kembalikan ke Pending
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Modal Alasan Penolakan -->
            <div x-show="rejectModal" x-cloak
                 class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/60">
                <div @click.away="rejectModal = false"
                     class="bg-white rounded-2xl max-w-md w-full p-6 text-slate-800 shadow-2xl relative">
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Tolak Pendaftaran Operator</h3>
                    <p class="text-xs text-slate-500 mb-4">
                        Berikan alasan penolakan agar admin travel dapat memperbaiki berkas atau data yang kurang sesuai.
                    </p>

                    <form action="{{ route('super-admin.operators.status', $operator) }}" method="POST">
                        @csrf
                        <input type="hidden" name="verification_status" value="rejected">
                        <div class="mb-4">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Alasan Penolakan <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="rejection_reason" rows="3" required
                                      placeholder="Contoh: Dokumen KTP buram dan tidak terbaca, mohon unggah scan asli..."
                                      class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500"></textarea>
                        </div>
                        <div class="flex items-center justify-end gap-2">
                            <button type="button" @click="rejectModal = false"
                                    class="px-4 py-2 rounded-lg border border-slate-300 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-xs font-bold text-white">
                                Konfirmasi Penolakan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Grid: Info Profil & Statistik -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profil Operator -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-start gap-5 pb-6 border-b border-slate-100">
                    @if($operator->logo)
                        <img src="{{ Storage::url($operator->logo) }}" alt="{{ $operator->name }}"
                             class="w-20 h-20 rounded-2xl object-cover border border-slate-200 shadow-sm flex-shrink-0">
                    @else
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white font-black text-2xl flex items-center justify-center shadow-sm flex-shrink-0">
                            {{ strtoupper(substr($operator->name, 0, 2)) }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <h2 class="text-xl font-bold text-slate-900">{{ $operator->name }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Terdaftar sejak {{ $operator->created_at->isoFormat('D MMMM Y') }}</p>
                        <div class="mt-3 flex flex-wrap gap-2 text-xs">
                            <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 px-3 py-1 rounded-lg">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                                {{ $operator->email }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 px-3 py-1 rounded-lg">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $operator->phone ?? 'Tidak ada nomor telepon' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-5 text-sm">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pemilik / Penanggung Jawab</p>
                        <p class="text-sm font-semibold text-slate-800 mt-1">{{ $operator->owner_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Terakhir Diperbarui</p>
                        <p class="text-sm font-semibold text-slate-800 mt-1">{{ $operator->updated_at->diffForHumans() }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Alamat Kantor</p>
                        <p class="text-sm text-slate-700 mt-1 leading-relaxed">{{ $operator->address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Statistik -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                    Statistik Operasional
                </h3>
                <div class="grid grid-cols-2 gap-3 flex-1">
                    <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100 flex flex-col items-center justify-center">
                        <span class="text-2xl font-black text-slate-900">{{ $operator->vehicles_count }}</span>
                        <span class="text-xs text-slate-500 mt-0.5 font-medium">Armada Mobil</span>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100 flex flex-col items-center justify-center">
                        <span class="text-2xl font-black text-slate-900">{{ $operator->routes_count }}</span>
                        <span class="text-xs text-slate-500 mt-0.5 font-medium">Rute Aktif</span>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100 flex flex-col items-center justify-center">
                        <span class="text-2xl font-black text-slate-900">{{ $operator->pickup_points_count }}</span>
                        <span class="text-xs text-slate-500 mt-0.5 font-medium">Titik Jemput</span>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100 flex flex-col items-center justify-center">
                        <span class="text-2xl font-black text-blue-600">{{ $operator->bookings_count }}</span>
                        <span class="text-xs text-slate-500 mt-0.5 font-medium">Total Booking</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Preview Dokumen Legalitas (KTP & SIUP) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Pratinjau Dokumen Legalitas (KTP & SIUP)
                </h2>
                <span class="text-xs text-slate-400">Klik tombol untuk membuka dokumen dalam ukuran penuh</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Preview KTP -->
                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/50 flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold">1</span>
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Dokumen KTP Pemilik</h3>
                        </div>
                        @if($operator->document_ktp)
                            <a href="{{ Storage::url($operator->document_ktp) }}" target="_blank"
                               class="text-xs font-semibold text-blue-600 hover:text-blue-800 inline-flex items-center gap-1 bg-white px-2.5 py-1 rounded-md border border-slate-200 shadow-2xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                Buka Fullscreen
                            </a>
                        @endif
                    </div>

                    <div class="flex-1 flex items-center justify-center min-h-[260px] bg-white rounded-lg border border-slate-200 overflow-hidden p-2">
                        @if($operator->document_ktp)
                            @php
                                $ktpExt = pathinfo($operator->document_ktp, PATHINFO_EXTENSION);
                            @endphp
                            @if(in_array(strtolower($ktpExt), ['jpg', 'jpeg', 'png', 'webp']))
                                <img src="{{ Storage::url($operator->document_ktp) }}" alt="KTP {{ $operator->owner_name }}"
                                     class="max-h-64 object-contain rounded">
                            @elseif(strtolower($ktpExt) === 'pdf')
                                <div class="text-center p-6">
                                    <svg class="w-16 h-16 text-rose-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-xs font-semibold text-slate-800">Dokumen Format PDF</p>
                                    <a href="{{ Storage::url($operator->document_ktp) }}" target="_blank"
                                       class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-lg transition-colors border border-rose-200">
                                        Lihat / Download File PDF
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center p-6 text-slate-400">
                                <svg class="w-12 h-12 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-xs font-medium text-slate-500">KTP belum diunggah</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Preview SIUP -->
                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/50 flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold">2</span>
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Dokumen SIUP / NIB Travel</h3>
                        </div>
                        @if($operator->document_siup)
                            <a href="{{ Storage::url($operator->document_siup) }}" target="_blank"
                               class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 inline-flex items-center gap-1 bg-white px-2.5 py-1 rounded-md border border-slate-200 shadow-2xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                Buka Fullscreen
                            </a>
                        @endif
                    </div>

                    <div class="flex-1 flex items-center justify-center min-h-[260px] bg-white rounded-lg border border-slate-200 overflow-hidden p-2">
                        @if($operator->document_siup)
                            @php
                                $siupExt = pathinfo($operator->document_siup, PATHINFO_EXTENSION);
                            @endphp
                            @if(in_array(strtolower($siupExt), ['jpg', 'jpeg', 'png', 'webp']))
                                <img src="{{ Storage::url($operator->document_siup) }}" alt="SIUP {{ $operator->name }}"
                                     class="max-h-64 object-contain rounded">
                            @elseif(strtolower($siupExt) === 'pdf')
                                <div class="text-center p-6">
                                    <svg class="w-16 h-16 text-rose-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-xs font-semibold text-slate-800">Dokumen Format PDF</p>
                                    <a href="{{ Storage::url($operator->document_siup) }}" target="_blank"
                                       class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-lg transition-colors border border-rose-200">
                                        Lihat / Download File PDF
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center p-6 text-slate-400">
                                <svg class="w-12 h-12 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-xs font-medium text-slate-500">SIUP belum diunggah</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Akun Admin & Driver Terdaftar -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Admin Travel -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-3 flex items-center justify-between">
                    <span>Admin Travel Terdaftar</span>
                    <span class="text-xs font-normal text-slate-400">{{ $adminUsers->count() }} orang</span>
                </h3>
                <div class="divide-y divide-slate-100 text-xs">
                    @forelse($adminUsers as $admin)
                    <div class="py-2.5 flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-slate-800">{{ $admin->name }}</p>
                            <p class="text-slate-400 text-[11px]">{{ $admin->email }}</p>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $admin->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                            {{ $admin->status === 'active' ? 'Aktif' : 'Banned' }}
                        </span>
                    </div>
                    @empty
                    <p class="text-slate-400 text-xs py-4 text-center">Belum ada akun Admin Travel untuk operator ini.</p>
                    @endforelse
                </div>
            </div>

            <!-- Driver Travel -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-3 flex items-center justify-between">
                    <span>Driver Terdaftar</span>
                    <span class="text-xs font-normal text-slate-400">{{ $drivers->count() }} driver</span>
                </h3>
                <div class="divide-y divide-slate-100 text-xs">
                    @forelse($drivers as $driver)
                    <div class="py-2.5 flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-slate-800">{{ $driver->name }}</p>
                            <p class="text-slate-400 text-[11px]">{{ $driver->phone ?? $driver->email }}</p>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $driver->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                            {{ $driver->status === 'active' ? 'Aktif' : 'Banned' }}
                        </span>
                    </div>
                    @empty
                    <p class="text-slate-400 text-xs py-4 text-center">Belum ada driver terdaftar untuk operator ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
