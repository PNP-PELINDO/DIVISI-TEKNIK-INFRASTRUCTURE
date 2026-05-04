<x-app-layout>
    <div class="max-w-3xl mx-auto w-full space-y-6 animate-fade-up">
        
        <div class="bg-white dark:bg-slate-900 p-8 md:p-10 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#003366] to-[#0055a4]"></div>

            <div class="mb-8">
                <h2 class="text-2xl font-black text-[#003366] dark:text-blue-400 uppercase tracking-tight">Edit Data Entitas</h2>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Perbarui informasi cabang / lokasi</p>
            </div>
            
            <form action="{{ route('admin.entities.update', $entity->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')                <div>
                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Nama Entitas Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $entity->name) }}" class="w-full border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-blue-50 dark:focus:ring-blue-900/20 focus:border-[#003366] dark:focus:border-blue-400 transition-all uppercase text-slate-900 dark:text-slate-100" required>
                    @error('name') <p class="text-red-500 dark:text-red-400 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Kode Internal (Singkatan)</label>
                    <input type="text" name="code" value="{{ old('code', $entity->code) }}" class="w-full border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-blue-50 dark:focus:ring-blue-900/20 focus:border-[#003366] dark:focus:border-blue-400 transition-all uppercase text-slate-900 dark:text-slate-100" required>
                    @error('code') <p class="text-red-500 dark:text-red-400 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-8 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-[#003366] dark:bg-blue-600 hover:bg-[#001e3c] dark:hover:bg-blue-700 text-white py-4 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-900/20 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-save text-white dark:text-blue-200"></i> Perbarui Data
                    </button>
                    <a href="{{ route('admin.entities.index') }}" class="px-8 py-4 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
