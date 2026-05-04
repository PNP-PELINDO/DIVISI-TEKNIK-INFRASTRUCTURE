<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight leading-none mb-2">Buat Sandi Baru</h2>
        <p class="text-slate-500 font-medium text-sm px-4">Silakan atur ulang kata sandi baru untuk akun Anda.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 font-bold text-xs" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Kata Sandi Baru" />
            <x-text-input id="password" class="block w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 font-bold text-xs" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Sandi Baru" />
            <x-text-input id="password_confirmation" class="block w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 font-bold text-xs" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center text-center">
                Simpan Kata Sandi
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
