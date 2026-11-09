<x-app-layout>
    <x-slot name="header">Edit Kota: {{ $city->name }}</x-slot>

    <div class="max-w-lg mx-auto">
        <div class="mb-6">
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                <a href="{{ route('admin-travel.cities.index') }}" class="hover:text-emerald-600 transition-colors">Kota Layanan</a>
                <span>/</span>
                <span class="text-slate-800 font-medium">Edit</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-800">Edit Kota Layanan</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui informasi kota layanan Anda.</p>
        </div>

        @if($errors->any())
        <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
            <ul class="list-disc list-inside space-y-1 text-xs">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin-travel.cities.update', $city) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5">

                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Kota <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $city->name) }}" required
                           class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $city->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                        <div>
                            <p class="text-xs font-semibold text-slate-800">Aktifkan Kota Ini</p>
                            <p class="text-[11px] text-slate-400">Kota aktif dapat dipilih saat membuat rute perjalanan.</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-5">
                <a href="{{ route('admin-travel.cities.index') }}"
                   class="px-5 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition-colors shadow-sm inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
