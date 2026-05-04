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
            </div>
        </template>

        <div class="max-w-3xl mx-auto w-full px-4 sm:px-6 lg:px-8 space-y-6 animate-fade">

            <!-- HEADER KORPORAT -->
            <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden">
                <div class="absolute left-0 top-0 h-full w-1.5 bg-[#0055a4]"></div>

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
            </div>

            <!-- ERROR ALERTS -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 p-4 rounded-lg flex items-start gap-3 shadow-sm">
                    <i class="fas fa-exclamation-triangle text-red-600 mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-red-800">Pembaruan Gagal</h3>
                        <ul class="mt-1 space-y-1 text-xs text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- MAIN FORM CARD -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="bg-[#00152b] px-6 py-4 border-b border-slate-700 flex items-center gap-3">
                    <i class="fas fa-map-location-dot text-blue-400"></i>
                    <h2 class="text-xs font-bold text-white uppercase tracking-widest">Informasi Utama Entitas</h2>
                </div>

                <!-- Perhatikan penambahan x-ref="entityForm" agar bisa di-submit dari modal -->
                <form x-ref="entityForm" action="{{ route('admin.entities.update', $entity->id) }}" method="POST" class="p-6 md:p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- NAMA ENTITAS -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Nama Entitas Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $entity->name) }}" placeholder="Contoh: PT Pelindo Terminal Petikemas"
                               class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-md block p-2.5 transition-colors uppercase font-semibold {{ $errors->has('name') ? 'border-red-500 bg-red-50' : '' }}" required>
                        @error('name')
                            <p class="text-[10px] font-bold text-red-500 mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- KODE INTERNAL -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Kode Internal (Singkatan) <span class="text-red-500">*</span></label>
                        <input type="text" name="code" value="{{ old('code', $entity->code) }}" placeholder="Contoh: TPK, SPJM, REG2"
                               class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-md block p-2.5 transition-colors uppercase font-mono font-bold {{ $errors->has('code') ? 'border-red-500 bg-red-50' : '' }}" required>
                        <p class="text-[10px] font-medium text-slate-500">Digunakan sebagai prefix pelaporan dan penandaan area aset.</p>
                        @error('code')
                            <p class="text-[10px] font-bold text-red-500 mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="pt-6 border-t border-slate-200 flex flex-col-reverse sm:flex-row justify-end gap-3">
                        <a href="{{ route('admin.entities.index') }}" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-md text-sm font-semibold transition-colors hover:bg-slate-50 text-center">
                            Batal
                        </a>
                        <!-- Tipe button diubah jadi button biasa agar tidak langsung submit -->
                        <button type="button" @click="showConfirmModal = true" class="w-full sm:w-auto bg-[#0055a4] hover:bg-[#003366] text-white px-6 py-2.5 rounded-md text-sm font-semibold transition-colors shadow-sm flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i> Perbarui Data
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
