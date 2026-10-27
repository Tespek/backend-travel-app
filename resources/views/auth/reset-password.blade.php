<x-guest-layout>

    {{-- Judul form --}}
    <div class="mb-7">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-slate-800">Buat Kata Sandi Baru</h2>
        <p class="text-sm text-slate-500 mt-1">Pastikan kata sandi baru Anda cukup kuat dan mudah diingat.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                <input id="email" type="email" name="email"
                       value="{{ old('email', $request->email) }}"
                       required autofocus autocomplete="username"
                       class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-300 rounded-xl
                              focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                              bg-slate-50 text-slate-800 placeholder-slate-400 transition"
                       placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- New Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">
                Kata Sandi Baru
            </label>
            <div class="relative" x-data="{ show: false }">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input id="password" :type="show ? 'text' : 'password'" name="password"
                       required autocomplete="new-password"
                       class="w-full pl-10 pr-10 py-2.5 text-sm border border-slate-300 rounded-xl
                              focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                              bg-slate-50 text-slate-800 placeholder-slate-400 transition"
                       placeholder="••••••••">
                <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">
                Konfirmasi Kata Sandi
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       required autocomplete="new-password"
                       class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-300 rounded-xl
                              focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                              bg-slate-50 text-slate-800 placeholder-slate-400 transition"
                       placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <!-- Submit -->
        <button type="submit"
                class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-600 to-teal-600
                       hover:from-emerald-700 hover:to-teal-700 text-white font-semibold py-2.5 px-4 rounded-xl
                       transition-all duration-200 shadow-lg shadow-emerald-500/30
                       hover:-translate-y-0.5 active:translate-y-0 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 13l4 4L19 7"/>
            </svg>
            Simpan Kata Sandi Baru
        </button>
    </form>

</x-guest-layout>
