<x-app-layout>
    <div class="max-w-4xl mx-auto w-full space-y-6 animate-fade-up">
        
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm relative flex items-center justify-between gap-6 overflow-hidden transition-colors duration-300">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-600 to-blue-400"></div>
            
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center text-2xl border border-blue-100 dark:border-blue-800 shadow-inner">
                    <i class="fas fa-calendar-edit"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-[#003366] dark:text-blue-400 uppercase tracking-tight">Edit Jadwal</h1>
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Sesuaikan Rencana Pemeliharaan</p>
                </div>
            </div>

            <a href="{{ route('admin.maintenance.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </a>
        </div>

        <div class="bg-white dark:bg-slate-900 p-8 md:p-12 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm transition-colors duration-300">
            <form action="{{ route('admin.maintenance.update', $maintenanceSchedule->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1">Pilih Alat / Aset</label>
                        <x-searchable-select 
                            name="infrastructure_id" 
                            :options="$infrastructures->map(fn($i) => ['value' => $i->id, 'label' => $i->code_name . ' - ' . $i->type . ' (' . $i->entity->name . ')'])"
                            placeholder="Cari Alat..."
                            :selected="$maintenanceSchedule->infrastructure_id"
                        />
                        @error('infrastructure_id') <p class="text-red-500 dark:text-red-400 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1">Tanggal Pemeliharaan</label>
                        <div class="relative">
                            <i class="fas fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="date" name="scheduled_date" required min="{{ date('Y-m-d') }}"
                                   value="{{ $maintenanceSchedule->scheduled_date }}"
                                   class="w-full pl-10 pr-4 py-3.5 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 transition-all">
                        </div>
                        @error('scheduled_date') <p class="text-red-500 dark:text-red-400 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1">Status Maintenance</label>
                        <div class="relative">
                            <i class="fas fa-tasks absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <select name="status" required class="w-full pl-10 pr-4 py-3.5 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 transition-all appearance-none cursor-pointer">
                                <option value="scheduled" {{ $maintenanceSchedule->status === 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                                <option value="completed" {{ $maintenanceSchedule->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $maintenanceSchedule->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        @error('status') <p class="text-red-500 dark:text-red-400 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1">Judul / Nama Kegiatan</label>
                    <div class="relative">
                        <i class="fas fa-heading absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="title" required value="{{ $maintenanceSchedule->title }}" placeholder="Contoh: Servis Berkala 1000 Jam"
                               class="w-full pl-10 pr-4 py-3.5 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 transition-all">
                    </div>
                    @error('title') <p class="text-red-500 dark:text-red-400 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1">Deskripsi Kegiatan</label>
                    <textarea name="description" rows="4" placeholder="Jelaskan detail pemeliharaan yang direncanakan..."
                              class="w-full px-4 py-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 transition-all">{{ $maintenanceSchedule->description }}</textarea>
                    @error('description') <p class="text-red-500 dark:text-red-400 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-[#003366] dark:bg-blue-600 hover:bg-[#002244] dark:hover:bg-blue-700 text-white py-4 rounded-xl text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-blue-900/20 transition-all flex items-center justify-center gap-3 group">
                        <i class="fas fa-save group-hover:rotate-12 transition-transform"></i> Perbarui Jadwal Maintenance
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
