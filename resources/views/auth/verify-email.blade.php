<x-guest-layout>

    {{-- Judul --}}
    <div class="mb-7 text-center">
        <div class="w-16 h-16 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-slate-800">Verifikasi Email Anda</h2>
        <p class="text-sm text-slate-500 mt-2 leading-relaxed">
            Terima kasih telah mendaftar! Kami telah mengirimkan tautan verifikasi ke email Anda.
            Silakan cek kotak masuk dan klik tautannya untuk mengaktifkan akun.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Tautan verifikasi baru telah dikirim ke email Anda.</span>
        </div>
    @endif

    <div class="space-y-3">
        {{-- Kirim ulang --}}
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-600 to-teal-600
                           hover:from-emerald-700 hover:to-teal-700 text-white font-semibold py-2.5 px-4 rounded-xl
                           transition-all duration-200 shadow-lg shadow-emerald-500/30
                           hover:-translate-y-0.5 active:translate-y-0 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 text-sm font-medium text-slate-500
                           hover:text-rose-600 py-2.5 px-4 rounded-xl border border-slate-200
                           hover:border-rose-200 hover:bg-rose-50 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar dari Akun
            </button>
        </form>
    </div>

</x-guest-layout>
