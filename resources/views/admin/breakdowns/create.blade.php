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
                
                <div>
                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Pilih Aset Bermasalah</label>
                    <x-searchable-select 
                        name="infrastructure_id" 
                        :value="old('infrastructure_id')"
                        placeholder="-- Pilih Alat yang Saat Ini Beroperasi (Available) --"
                        :options="$infrastructures->map(fn($i) => ['value' => $i->id, 'label' => $i->code_name . ' - ' . $i->type . ' (' . ($i->entity->name ?? 'N/A') . ')'])->toArray()"
                        required
                    />
                    @error('infrastructure_id') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Deskripsi Kendala Teknis</label>
                    <textarea name="issue_detail" rows="4" class="w-full border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-medium p-4 focus:ring-4 focus:ring-red-50 dark:focus:ring-red-900/20 focus:border-red-600 dark:focus:border-red-400 transition-all text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500" placeholder="Jelaskan secara detail kerusakan atau anomali yang terjadi pada alat..." required>{{ old('issue_detail') }}</textarea>
                    @error('issue_detail') <p class="text-red-500 dark:text-red-400 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- ERROR ALERTS -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 p-4 rounded-lg flex items-start gap-3 shadow-sm">
                    <i class="fas fa-exclamation-triangle text-red-600 mt-0.5"></i>
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">PIC / Vendor Perbaikan</label>
                        <input type="text" name="vendor_pic" value="{{ old('vendor_pic') }}" class="w-full border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-red-50 dark:focus:ring-red-900/20 focus:border-red-600 dark:focus:border-red-400 transition-all uppercase text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500" placeholder="Contoh: PT. BIMA / TIM INTERNAL">
                        @error('vendor_pic') <p class="text-red-500 dark:text-red-400 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Status Laporan Awal</label>
                        <select name="repair_status" class="w-full border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-red-50 dark:focus:ring-red-900/20 focus:border-red-600 dark:focus:border-red-400 transition-all uppercase text-slate-900 dark:text-slate-100" required>
                            <option value="reported" {{ old('repair_status') == 'reported' ? 'selected' : '' }}>Reported (Baru Dilaporkan)</option>
                            <option value="order_part" {{ old('repair_status') == 'order_part' ? 'selected' : '' }}>Order Part (Menunggu Suku Cadang)</option>
                        </select>
                        @error('repair_status') <p class="text-red-500 dark:text-red-400 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif

                <div class="pt-8 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-red-600 dark:bg-red-700 hover:bg-red-700 dark:hover:bg-red-800 text-white py-4 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-red-900/20 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Simpan Laporan & Update Aset
                    </button>
                    <a href="{{ route('admin.breakdowns.index') }}" class="px-8 py-4 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-center">
                        Batal
                    </a>
                </div>

                <form action="{{ route('admin.breakdowns.store') }}" method="POST" class="p-6 md:p-8 space-y-6">
                    @csrf

                    <!-- PILIH ASET (WITH SEARCH FILTER) -->
                    @php
                        // Siapkan data aset untuk Alpine.js
                        $infraOptions = $infrastructures->map(function($infra) {
                            return [
                                'id' => $infra->id,
                                'code' => $infra->code_name,
                                'text' => $infra->code_name . ' | ' . $infra->type . ' (Lokasi: ' . ($infra->entity->name ?? 'Pusat') . ')'
                            ];
                        })->values();
                    @endphp

                    <div class="space-y-1.5"
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

                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Pilih Infrastruktur / Aset Bermasalah <span class="text-red-500">*</span></label>

                        <!-- Hidden Input untuk dikirim ke Controller -->
                        <input type="hidden" name="infrastructure_id" :value="selectedId">

                        <div class="relative">
                            <!-- Trigger Button -->
                            <button type="button" @click="open = !open"
                                    class="w-full bg-white border text-sm rounded-md p-2.5 text-left flex justify-between items-center transition-colors focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 {{ $errors->has('infrastructure_id') ? 'border-red-500 bg-red-50' : 'border-slate-300' }}">
                                <span x-text="selectedText" :class="selectedId ? 'text-slate-900 font-semibold' : 'text-slate-500'"></span>
                                <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" x-cloak
                                 x-transition.opacity.duration.200ms
                                 class="absolute z-50 w-full mt-1 bg-white border border-slate-300 rounded-md shadow-xl overflow-hidden">

                                <!-- Search Box -->
                                <div class="p-2 border-b border-slate-100 bg-slate-50 sticky top-0">
                                    <div class="relative">
                                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                        <input type="text" x-model="search" placeholder="Ketik Kode Aset atau Tipe Alat..."
                                               class="w-full pl-8 pr-3 py-2 text-xs border border-slate-300 rounded focus:ring-red-500 focus:border-red-500">
                                    </div>
                                </div>

                                <!-- Options List -->
                                <ul class="max-h-56 overflow-y-auto dropdown-scroll">
                                    <template x-for="option in filteredOptions" :key="option.id">
                                        <li @click="selectedId = option.id; selectedText = option.text; open = false; search = ''"
                                            class="px-4 py-2.5 text-xs cursor-pointer border-b border-slate-50 last:border-0 transition-colors"
                                            :class="selectedId == option.id ? 'bg-red-50 text-red-700 font-bold' : 'text-slate-700 hover:bg-slate-50 font-medium'">
                                            <span x-text="option.text"></span>
                                        </li>
                                    </template>
                                    <li x-show="filteredOptions.length === 0" class="px-4 py-3 text-xs text-slate-500 text-center bg-slate-50">
                                        Aset tidak ditemukan. Coba kata kunci lain.
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <p class="text-[10px] font-medium text-slate-500 mt-1">Hanya aset dengan status beroperasi normal (Ready) yang dapat dilaporkan.</p>

                        @error('infrastructure_id')
                            <p class="text-[10px] font-bold text-red-500 mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- DESKRIPSI KENDALA -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Deskripsi Rincian Kendala Teknis <span class="text-red-500">*</span></label>
                        <textarea name="issue_detail" rows="4" placeholder="Jelaskan secara spesifik masalah, error, atau anomali yang terjadi pada alat..."
                                  class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-md block p-2.5 transition-colors resize-none {{ $errors->has('issue_detail') ? 'border-red-500 bg-red-50' : '' }}" required>{{ old('issue_detail') }}</textarea>
                        @error('issue_detail')
                            <p class="text-[10px] font-bold text-red-500 mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                        <!-- PIC / VENDOR -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Pelapor / Vendor Perbaikan (Opsional)</label>
                            <input type="text" name="vendor_pic" value="{{ old('vendor_pic') }}" placeholder="Contoh: PT. BIMA / TIM TEKNIK INTERNAL"
                                   class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-md block p-2.5 transition-colors uppercase font-semibold">
                            <p class="text-[10px] font-medium text-slate-500 mt-1">Kosongkan jika belum ada PIC yang ditunjuk.</p>
                            @error('vendor_pic')
                                <p class="text-[10px] font-bold text-red-500 mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- STATUS LAPORAN -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Status Laporan Awal <span class="text-red-500">*</span></label>
                            <select name="repair_status" class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-md block p-2.5 transition-colors uppercase font-semibold cursor-pointer" required>
                                <option value="reported" {{ old('repair_status') == 'reported' ? 'selected' : '' }}>Reported (Baru Dilaporkan)</option>
                                <option value="order_part" {{ old('repair_status') == 'order_part' ? 'selected' : '' }}>Order Part (Butuh Suku Cadang)</option>
                                <option value="on_progress" {{ old('repair_status') == 'on_progress' ? 'selected' : '' }}>On Progress (Langsung Dikerjakan)</option>
                            </select>
                            @error('repair_status')
                                <p class="text-[10px] font-bold text-red-500 mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="pt-6 border-t border-slate-200 flex flex-col-reverse sm:flex-row justify-end gap-3">
                        <a href="{{ route('admin.breakdowns.index') }}" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-md text-sm font-semibold transition-colors hover:bg-slate-50 text-center">
                            Batal
                        </a>
                        <button type="submit" class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-md text-sm font-semibold transition-colors shadow-sm flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i> Submit Laporan & Update Aset
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
