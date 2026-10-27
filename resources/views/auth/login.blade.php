<x-guest-layout>

    {{-- Judul form --}}
    <div class="mb-7">
        <h2 class="text-xl font-bold text-slate-800">Masuk ke Akun Anda</h2>
        <p class="text-sm text-slate-500 mt-1">Silakan masukkan email dan kata sandi untuk melanjutkan.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
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
                       required autofocus autocomplete="username"
                       class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-300 rounded-xl
                              focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                              bg-slate-50 text-slate-800 placeholder-slate-400 transition"
                       placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <div class="mb-1.5">
                <label for="password" class="block text-sm font-semibold text-slate-700">
                    Kata Sandi
                </label>
            </div>
            <div class="relative" x-data="{ show: false }">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input id="password" :type="show ? 'text' : 'password'" name="password"
                       required autocomplete="current-password"
                       class="w-full pl-10 pr-10 py-2.5 text-sm border border-slate-300 rounded-xl
                              focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                              bg-slate-50 text-slate-800 placeholder-slate-400 transition"
                       placeholder="••••••••">
                <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember"
                   class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
            <label for="remember_me" class="ml-2 text-sm text-slate-600 cursor-pointer select-none">
                Ingat saya
            </label>
        </div>

        <!-- Submit -->
        <button type="submit"
                class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-600 to-teal-600
                       hover:from-emerald-700 hover:to-teal-700 text-white font-semibold py-2.5 px-4 rounded-xl
                       transition-all duration-200 shadow-lg shadow-emerald-500/30 hover:shadow-emerald-600/40
                       hover:-translate-y-0.5 active:translate-y-0 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Masuk Sekarang
        </button>
    </form>

</x-guest-layout>
