<x-app-layout>
    <div class="max-w-4xl mx-auto w-full space-y-8 pb-16 animate-fade-up">
        
        <div class="bg-white p-8 md:p-10 rounded-[2rem] border border-slate-200 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-amber-400 to-amber-600"></div>

            <div class="mb-8">
                <h2 class="text-2xl font-black text-[#003366] uppercase tracking-tight">Update Progres Perbaikan</h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Laporan untuk Aset: <span class="text-[#0055a4]">{{ $breakdown->infrastructure->code_name }}</span></p>
            </div>
            
            <form action="{{ route('admin.breakdowns.update', $breakdown->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Deskripsi Kendala Laporan Awal</label>
                    <textarea name="issue_detail" rows="3" class="w-full border-slate-200 bg-slate-50 rounded-xl text-sm font-medium p-4 focus:ring-4 focus:ring-amber-50 focus:border-amber-500 transition-all" required>{{ old('issue_detail') ?? $breakdown->issue_detail }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Penanggung Jawab (PIC)</label>
                        <input type="text" name="vendor_pic" value="{{ old('vendor_pic') ?? $breakdown->vendor_pic }}" class="w-full border-slate-200 bg-slate-50 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-amber-50 focus:border-amber-500 transition-all uppercase" required>
                    </div>
                    
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Update Status Kerja</label>
                        <select name="repair_status" class="w-full border-slate-200 bg-slate-50 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-amber-50 focus:border-amber-500 transition-all uppercase" required>
                            <option value="reported" {{ (old('repair_status') ?? $breakdown->repair_status) == 'reported' ? 'selected' : '' }}>Reported</option>
                            <option value="order_part" {{ (old('repair_status') ?? $breakdown->repair_status) == 'order_part' ? 'selected' : '' }}>Order Part</option>
                            <option value="on_progress" {{ (old('repair_status') ?? $breakdown->repair_status) == 'on_progress' ? 'selected' : '' }}>On Progress (Sedang Diperbaiki)</option>
                            <option value="resolved" {{ (old('repair_status') ?? $breakdown->repair_status) == 'resolved' ? 'selected' : '' }}>Resolved (Perbaikan Selesai)</option>
                        </select>
                        <p class="text-[9px] text-amber-600 font-bold mt-2 ml-1">*Pilih "Resolved" untuk mengembalikan status alat ke Ready.</p>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white py-4 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Perbarui Laporan
                    </button>
                    <a href="{{ route('admin.breakdowns.index') }}" class="px-8 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
