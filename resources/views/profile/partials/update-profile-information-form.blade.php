<section>
    <header class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-100">
        <div class="w-10 h-10 bg-blue-50 text-[#003366] rounded-xl flex items-center justify-center text-lg shrink-0 border border-blue-100">
            <i class="fas fa-id-card"></i>
        </div>
        <div>
            <h2 class="text-lg font-black text-[#003366] uppercase tracking-tight">
                Informasi Profil
            </h2>
            <p class="mt-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                Perbarui nama lengkap dan alamat email akun Anda.
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Nama Lengkap</label>
            <input id="name" name="name" type="text" class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm font-bold p-3.5 focus:ring-[#003366] focus:border-[#003366] transition-all" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Alamat Email</label>
            <input id="email" name="email" type="email" class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm font-bold p-3.5 focus:ring-[#003366] focus:border-[#003366] transition-all" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <p class="text-xs font-bold text-amber-800">
                        Alamat email Anda belum diverifikasi.
                        <button form="send-verification" class="text-[#0055a4] hover:text-[#003366] underline">
                            Klik di sini untuk mengirim ulang email verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-xs font-black text-emerald-600 uppercase">
                            <i class="fas fa-check-circle mr-1"></i> Tautan verifikasi baru telah dikirim.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="px-6 py-3.5 bg-[#003366] hover:bg-[#002244] text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-md shadow-blue-900/20">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-xs font-black text-emerald-600 uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> Berhasil Disimpan
                </p>
            @endif
        </div>
    </form>
</section>
