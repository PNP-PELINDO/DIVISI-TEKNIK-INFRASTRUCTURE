<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight leading-none mb-2">Lupa Sandi?</h2>
        <p class="text-slate-500 font-medium text-sm px-4">Jangan khawatir. Masukkan alamat email korporat Anda, dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6 font-bold text-sm text-emerald-600 bg-emerald-50 p-4 rounded-xl border border-emerald-200" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 font-bold text-xs" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center text-center">
                Kirim Tautan Reset Sandi
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
