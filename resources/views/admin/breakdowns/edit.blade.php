<x-app-layout>
    <div class="max-w-4xl mx-auto w-full space-y-6 animate-fade-up">
        
        <div class="bg-white dark:bg-slate-900 p-8 md:p-10 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#003366] to-[#0055a4]"></div>

            <div class="mb-8">
                <h2 class="text-2xl font-black text-[#003366] dark:text-blue-400 uppercase tracking-tight">Update Progres Perbaikan</h2>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Laporan untuk Aset: <span class="text-[#0055a4] dark:text-blue-400">{{ $breakdown->infrastructure->code_name }}</span></p>
            </div>
            
            <form action="{{ route('admin.breakdowns.update', $breakdown->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Deskripsi Kendala Laporan Awal</label>
                    <textarea name="issue_detail" rows="3" class="w-full border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-medium p-4 focus:ring-4 focus:ring-blue-50 dark:focus:ring-blue-900/20 focus:border-[#003366] dark:focus:border-blue-400 transition-all text-slate-900 dark:text-slate-100" required>{{ old('issue_detail') ?? $breakdown->issue_detail }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Penanggung Jawab (PIC)</label>
                        <input type="text" name="vendor_pic" value="{{ old('vendor_pic') ?? $breakdown->vendor_pic }}" class="w-full border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-blue-50 dark:focus:ring-blue-900/20 focus:border-[#003366] dark:focus:border-blue-400 transition-all uppercase text-slate-900 dark:text-slate-100" required>
                    </div>
                    
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Update Status Kerja</label>
                        <select name="repair_status" class="w-full border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-blue-50 dark:focus:ring-blue-900/20 focus:border-[#003366] dark:focus:border-blue-400 transition-all uppercase text-slate-900 dark:text-slate-100" required>
                            <option value="reported" {{ (old('repair_status') ?? $breakdown->repair_status) == 'reported' ? 'selected' : '' }} class="dark:bg-slate-900">Dilaporkan</option>
                            <option value="order_part" {{ (old('repair_status') ?? $breakdown->repair_status) == 'order_part' ? 'selected' : '' }} class="dark:bg-slate-900">Menunggu Suku Cadang</option>
                            <option value="on_progress" {{ (old('repair_status') ?? $breakdown->repair_status) == 'on_progress' ? 'selected' : '' }} class="dark:bg-slate-900">Sedang Diperbaiki</option>
                            <option value="resolved" {{ (old('repair_status') ?? $breakdown->repair_status) == 'resolved' ? 'selected' : '' }} class="dark:bg-slate-900">Selesai Perbaikan</option>
                        </select>
                        <p class="text-[9px] text-[#0055a4] dark:text-blue-400 font-bold mt-2 ml-1">*Pilih "Selesai Perbaikan" untuk mengembalikan status alat ke Tersedia.</p>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-[#003366] dark:bg-blue-600 hover:bg-[#002244] dark:hover:bg-blue-700 text-white py-4 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-900/20 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Perbarui Laporan
                    </button>
                    <a href="{{ route('admin.breakdowns.index') }}" class="px-8 py-4 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
