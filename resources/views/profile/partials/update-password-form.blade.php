<section>
    <header class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-100">
        <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-lg shrink-0 border border-amber-100">
            <i class="fas fa-key"></i>
        </div>
        <div>
            <h2 class="text-lg font-black text-[#003366] uppercase tracking-tight">
                Keamanan Sandi
            </h2>
            <p class="mt-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                Pastikan akun Anda menggunakan kata sandi yang panjang dan acak.
            </p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Kata Sandi Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm font-bold p-3.5 focus:ring-[#003366] focus:border-[#003366] transition-all" autocomplete="current-password" />
            @error('current_password', 'updatePassword') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Kata Sandi Baru</label>
            <input id="update_password_password" name="password" type="password" class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm font-bold p-3.5 focus:ring-[#003366] focus:border-[#003366] transition-all" autocomplete="new-password" />
            @error('password', 'updatePassword') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Konfirmasi Sandi Baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm font-bold p-3.5 focus:ring-[#003366] focus:border-[#003366] transition-all" autocomplete="new-password" />
            @error('password_confirmation', 'updatePassword') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="px-6 py-3.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-md shadow-slate-800/20">
                Perbarui Sandi
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-xs font-black text-emerald-600 uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> Sandi Diperbarui
                </p>
            @endif
        </div>
    </form>
</section>
