<x-app-layout>
    <div class="max-w-4xl mx-auto w-full space-y-6 animate-fade-up">
        
        <div class="bg-white p-8 md:p-10 rounded-[2rem] border border-slate-200 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-red-600 to-red-800"></div>

            <div class="mb-8 flex items-center gap-4">
                <div class="w-12 h-12 bg-red-50 text-red-600 flex items-center justify-center rounded-xl border border-red-100">
                    <i class="fas fa-triangle-exclamation text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-[#003366] uppercase tracking-tight">Pelaporan Kerusakan Baru</h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Status aset akan otomatis berubah menjadi "Down"</p>
                </div>
            </div>
            
            <form action="{{ route('admin.breakdowns.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Pilih Aset Bermasalah</label>
                    <select name="infrastructure_id" class="w-full border-slate-200 bg-slate-50 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-red-50 focus:border-red-600 transition-all" required>
                        <option value="">-- Pilih Alat yang Saat Ini Beroperasi (Available) --</option>
                        @foreach($infrastructures as $infra)
                            <option value="{{ $infra->id }}" {{ old('infrastructure_id') == $infra->id ? 'selected' : '' }}>
                                {{ $infra->code_name }} - {{ $infra->type }} ({{ $infra->entity->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('infrastructure_id') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Deskripsi Kendala Teknis</label>
                    <textarea name="issue_detail" rows="4" class="w-full border-slate-200 bg-slate-50 rounded-xl text-sm font-medium p-4 focus:ring-4 focus:ring-red-50 focus:border-red-600 transition-all" placeholder="Jelaskan secara detail kerusakan atau anomali yang terjadi pada alat..." required>{{ old('issue_detail') }}</textarea>
                    @error('issue_detail') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">PIC / Vendor Perbaikan</label>
                        <input type="text" name="vendor_pic" value="{{ old('vendor_pic') }}" class="w-full border-slate-200 bg-slate-50 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-red-50 focus:border-red-600 transition-all uppercase" placeholder="Contoh: PT. BIMA / TIM INTERNAL">
                        @error('vendor_pic') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Status Laporan Awal</label>
                        <select name="repair_status" class="w-full border-slate-200 bg-slate-50 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-red-50 focus:border-red-600 transition-all uppercase" required>
                            <option value="reported" {{ old('repair_status') == 'reported' ? 'selected' : '' }}>Reported (Baru Dilaporkan)</option>
                            <option value="order_part" {{ old('repair_status') == 'order_part' ? 'selected' : '' }}>Order Part (Menunggu Suku Cadang)</option>
                        </select>
                        @error('repair_status') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-4 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-red-900/20 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Simpan Laporan & Update Aset
                    </button>
                    <a href="{{ route('admin.breakdowns.index') }}" class="px-8 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
