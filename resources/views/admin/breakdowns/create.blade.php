<x-app-layout>
    <div class="max-w-4xl mx-auto w-full space-y-6 animate-fade-up">
        
        <div class="bg-white dark:bg-slate-900 p-8 md:p-10 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-red-600 to-red-800"></div>

            <div class="mb-8 flex items-center gap-4">
                <div class="w-12 h-12 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center rounded-xl border border-red-100 dark:border-red-800">
                    <i class="fas fa-triangle-exclamation text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-[#003366] dark:text-blue-400 uppercase tracking-tight">Pelaporan Kerusakan Baru</h2>
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Status aset akan otomatis berubah menjadi "Down"</p>
                </div>
            </div>
            
            <form action="{{ route('admin.breakdowns.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- NOTIFICATION ALERTS -->
                @if(session('error') || $errors->any())
                    <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-600 p-6 rounded-2xl shadow-sm flex items-start gap-5 mb-6">
                        <div class="w-12 h-12 bg-red-600 text-white rounded-xl flex items-center justify-center text-xl shrink-0 shadow-lg shadow-red-600/20">
                            <i class="fas fa-triangle-exclamation"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-black text-red-800 dark:text-red-400 uppercase tracking-widest">Terjadi Kesalahan!</h4>
                            @if(session('error'))
                                <p class="text-xs font-bold text-red-600 dark:text-red-500 mt-1">{{ session('error') }}</p>
                            @endif
                            @if($errors->any())
                                <ul class="mt-2 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li class="text-[10px] font-bold text-red-600/80 dark:text-red-500/80 flex items-center gap-2 uppercase tracking-tight">
                                            <span class="w-1.5 h-1.5 bg-red-600 rounded-full"></span> {{ $error }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- PILIH ASET (WITH SEARCH FILTER) -->
                @php
                    $infraOptions = $infrastructures->map(function($infra) {
                        return [
                            'id' => $infra->id,
                            'code' => $infra->code_name,
                            'text' => $infra->code_name . ' | ' . $infra->type . ' (Lokasi: ' . ($infra->entity->name ?? 'Pusat') . ')'
                        ];
                    })->values();
                @endphp

                <div class="space-y-2"
                     x-data="{
                        open: false,
                        search: '',
                        selectedId: '{{ old('infrastructure_id') }}',
                        selectedText: '-- Ketik atau Pilih Aset yang Bermasalah --',
                        options: {{ json_encode($infraOptions) }},
                        get filteredOptions() {
                            if (this.search === '') return this.options;
                            return this.options.filter(i => i.text.toLowerCase().includes(this.search.toLowerCase()));
                        },
                        init() {
                            if(this.selectedId) {
                                let selected = this.options.find(i => i.id == this.selectedId);
                                if(selected) this.selectedText = selected.text;
                            }
                        }
                     }">

                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Pilih Aset Bermasalah <span class="text-red-500">*</span></label>

                    <!-- Hidden Input untuk dikirim ke Controller -->
                    <input type="hidden" name="infrastructure_id" :value="selectedId">

                    <div class="relative">
                        <!-- Trigger Button -->
                        <button type="button" @click="open = !open"
                                class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm rounded-xl p-4 text-left flex justify-between items-center transition-all focus:ring-4 focus:ring-red-50 dark:focus:ring-red-900/20 focus:border-red-600 dark:focus:border-red-400 {{ $errors->has('infrastructure_id') ? 'border-red-500 bg-red-50 dark:bg-red-900/10' : '' }}">
                            <span x-text="selectedText" :class="selectedId ? 'text-slate-900 dark:text-slate-100 font-bold' : 'text-slate-400 dark:text-slate-500'"></span>
                            <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute z-50 w-full mt-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl overflow-hidden">

                            <!-- Search Box -->
                            <div class="p-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 sticky top-0">
                                <div class="relative">
                                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="text" x-model="search" placeholder="Ketik Kode Aset atau Tipe Alat..."
                                           class="w-full pl-10 pr-4 py-2.5 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-red-500 focus:border-red-500 dark:text-slate-200">
                                </div>
                            </div>

                            <!-- Options List -->
                            <ul class="max-h-64 overflow-y-auto custom-scrollbar">
                                <template x-for="option in filteredOptions" :key="option.id">
                                    <li @click="selectedId = option.id; selectedText = option.text; open = false; search = ''"
                                        class="px-5 py-3.5 text-xs cursor-pointer border-b border-slate-50 dark:border-slate-800/50 last:border-0 transition-colors"
                                        :class="selectedId == option.id ? 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 font-medium'">
                                        <span x-text="option.text"></span>
                                    </li>
                                </template>
                                <li x-show="filteredOptions.length === 0" class="px-5 py-6 text-xs text-slate-500 text-center bg-slate-50 dark:bg-slate-800/50">
                                    Aset tidak ditemukan. Coba kata kunci lain.
                                </li>
                            </ul>
                        </div>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 mt-1 ml-1 uppercase tracking-widest">Hanya menampilkan aset berstatus "Ready"</p>
                </div>

                <!-- DESKRIPSI KENDALA -->
                <div>
                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Deskripsi Kendala Teknis <span class="text-red-500">*</span></label>
                    <textarea name="issue_detail" rows="4" class="w-full border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-medium p-4 focus:ring-4 focus:ring-red-50 dark:focus:ring-red-900/20 focus:border-red-600 dark:focus:border-red-400 transition-all text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500" placeholder="Jelaskan secara detail kerusakan atau anomali yang terjadi pada alat..." required>{{ old('issue_detail') }}</textarea>
                    @error('issue_detail') <p class="text-red-500 dark:text-red-400 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- TANGGAL BREAKDOWN -->
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Tgl Breakdown <span class="text-red-500">*</span></label>
                        <input type="date" name="breakdown_date" value="{{ old('breakdown_date', now()->format('Y-m-d')) }}" class="w-full border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-red-50 dark:focus:ring-red-900/20 focus:border-red-600 dark:focus:border-red-400 transition-all text-slate-900 dark:text-slate-100" required>
                        @error('breakdown_date') <p class="text-red-500 dark:text-red-400 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- PIC / VENDOR -->
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Pelapor / Vendor</label>
                        <input type="text" name="vendor_pic" value="{{ old('vendor_pic') }}" class="w-full border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-red-50 dark:focus:ring-red-900/20 focus:border-red-600 dark:focus:border-red-400 transition-all uppercase text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500" placeholder="Contoh: PT. BIMA">
                        @error('vendor_pic') <p class="text-red-500 dark:text-red-400 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- STATUS LAPORAN -->
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Status Laporan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="repair_status" class="w-full border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-red-50 dark:focus:ring-red-900/20 focus:border-red-600 dark:focus:border-red-400 transition-all uppercase text-slate-900 dark:text-slate-100 appearance-none cursor-pointer" required>
                                <option value="reported" {{ old('repair_status') == 'reported' ? 'selected' : '' }}>Reported</option>
                                <option value="order_part" {{ old('repair_status') == 'order_part' ? 'selected' : '' }}>Order Part</option>
                                <option value="on_progress" {{ old('repair_status') == 'on_progress' ? 'selected' : '' }}>On Progress</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                        @error('repair_status') <p class="text-red-500 dark:text-red-400 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-red-600 dark:bg-red-700 hover:bg-red-700 dark:hover:bg-red-800 text-white py-4 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-red-900/20 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Simpan Laporan & Update Aset
                    </button>
                    <a href="{{ route('admin.breakdowns.index') }}" class="px-8 py-4 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
    </style>
</x-app-layout>

