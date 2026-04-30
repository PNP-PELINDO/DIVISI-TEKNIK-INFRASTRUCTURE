<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight leading-none mb-2">Area Terbatas</h2>
        <p class="text-slate-500 font-medium text-sm px-4">Ini adalah area aman aplikasi. Harap konfirmasi kata sandi Anda sebelum melanjutkan.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Kata Sandi" />

            <x-text-input id="password" class="block w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 font-bold text-xs" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center text-center">
                Konfirmasi Identitas
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
