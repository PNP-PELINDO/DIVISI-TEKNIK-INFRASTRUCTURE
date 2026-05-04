<section class="space-y-6" x-data="{ showConfirmModal: false }">
    <header class="flex items-center gap-4 mb-8 pb-6 border-b border-red-200">
        <div class="w-10 h-10 bg-red-100 text-red-600 rounded-xl flex items-center justify-center text-lg shrink-0 border border-red-200">
            <i class="fas fa-user-times"></i>
        </div>
        <div>
            <h2 class="text-lg font-black text-red-700 uppercase tracking-tight">
                Hapus Akun
            </h2>
            <p class="mt-1 text-[10px] font-bold text-red-500 uppercase tracking-widest">
                Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>
    </header>

    <div class="bg-white/80 p-5 rounded-xl border border-red-200">
        <p class="text-sm font-bold text-slate-600 leading-relaxed">
            Setelah akun Anda dihapus, semua sumber daya dan data yang terkait akan dihapus secara permanen. Harap pastikan untuk mencadangkan data penting sebelum melanjutkan penghapusan.
        </p>
    </div>

    <button type="button" @click="showConfirmModal = true" class="px-6 py-3.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-md shadow-red-600/20">
        Hapus Akun Saya
    </button>

    <template x-teleport="body">
        <div x-show="showConfirmModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm transition-opacity" x-cloak style="display: none;">
            <div @click.away="showConfirmModal = false" x-show="showConfirmModal" x-transition.scale.origin.bottom class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl overflow-hidden border border-slate-200">
                <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
                    @csrf
                    @method('delete')

                    <div class="w-16 h-16 bg-red-50 text-red-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 border border-red-100">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>

                    <h2 class="text-xl font-black text-center text-slate-800 uppercase tracking-tight mb-2">
                        Konfirmasi Penghapusan
                    </h2>

                    <p class="mt-1 text-xs text-center font-bold text-slate-500 leading-relaxed mb-6">
                        Masukkan kata sandi Anda untuk mengonfirmasi penghapusan permanen akun ini.
                    </p>

                    <div class="mb-6">
                        <label for="password" class="sr-only">Kata Sandi</label>
                        <input id="password" name="password" type="password" class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm font-bold focus:ring-red-500 focus:border-red-500 transition-all text-center p-3.5" placeholder="Ketik kata sandi Anda..." />
                        @error('password', 'userDeletion') <p class="text-red-500 text-xs mt-2 font-bold text-center">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="button" @click="showConfirmModal = false" class="flex-1 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                            Batal
                        </button>
                        
                        <button type="submit" class="flex-1 py-3.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-md shadow-red-600/20">
                            Hapus Permanen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</section>
