<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight leading-none mb-2">Verifikasi Email</h2>
        <p class="text-slate-500 font-medium text-sm px-4">Terima kasih telah bergabung! Sebelum memulai, harap verifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirim. Jika Anda tidak menerima email, kami dapat mengirimkan tautan baru.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 font-bold text-sm text-emerald-600 bg-emerald-50 p-4 rounded-xl border border-emerald-200 text-center">
            Tautan verifikasi baru telah dikirim ke alamat email Anda.
        </div>
    @endif

    <div class="mt-4 flex flex-col gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full justify-center text-center">
                Kirim Ulang Email Verifikasi
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-center text-xs text-slate-500 hover:text-[#003366] font-bold uppercase tracking-widest underline decoration-slate-300 underline-offset-4 transition-colors">
                Keluar (Logout)
            </button>
        </form>
    </div>
</x-guest-layout>
