<x-guest-layout>

    {{-- Judul form --}}
    <div class="mb-7">
        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-slate-800">Lupa Kata Sandi?</h2>
        <p class="text-sm text-slate-500 mt-1">
            Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mereset kata sandi.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">
                Alamat Email
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       required autofocus
                       class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-300 rounded-xl
                              focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                              bg-slate-50 text-slate-800 placeholder-slate-400 transition"
                       placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Submit -->
        <button type="submit"
                class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-600 to-teal-600
                       hover:from-emerald-700 hover:to-teal-700 text-white font-semibold py-2.5 px-4 rounded-xl
                       transition-all duration-200 shadow-lg shadow-emerald-500/30
                       hover:-translate-y-0.5 active:translate-y-0 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Kirim Tautan Reset
        </button>

        <div class="text-center">
            <a href="{{ route('login') }}"
               class="text-sm text-slate-500 hover:text-emerald-600 transition inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke halaman masuk
            </a>
        </div>
    </form>

</x-guest-layout>
