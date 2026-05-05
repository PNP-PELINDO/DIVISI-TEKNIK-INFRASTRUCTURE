<x-app-layout>
    <div class="max-w-4xl mx-auto w-full space-y-6 animate-fade-up">
        
        <div class="bg-white dark:bg-slate-900 p-8 md:p-10 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-pelindo-blue to-pelindo-cyan"></div>

            <div class="mb-10 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black text-pelindo-blue dark:text-pelindo-cyan uppercase tracking-tight">Registrasi Unit Aset</h2>
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Sistem Digitalisasi Inventaris Infrastruktur Pelindo</p>
                </div>
                <div class="w-16 h-16 bg-sky-50 dark:bg-blue-900/30 text-pelindo-blue dark:text-pelindo-cyan rounded-2xl flex items-center justify-center text-3xl border border-sky-100 dark:border-blue-800 shadow-inner">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
            </div>

            <!-- ERROR ALERTS -->
            @if ($errors->any())
                <div class="mb-8 p-5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl flex items-start gap-4 animate-fade-in">
                    <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-red-800 dark:text-red-400 uppercase tracking-widest mb-1">Gagal Menyimpan Data!</h3>
                        <ul class="list-disc list-inside text-[10px] font-bold text-red-600 dark:text-red-400/80 uppercase tracking-wide">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            
            <form action="{{ route('admin.infrastructures.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" 
                  x-data="{ 
                      inputMode: '{{ old('type_select') == 'new' ? 'text' : 'select' }}', 
                      selectedType: '{{ old('type_select') }}',
                      selectedCategory: '{{ old('category') }}',
                      typeMap: {{ json_encode($typeCategoryMap ?? []) }} 
                  }">
                @csrf
                
                <!-- UPLOAD FOTO -->
                <div onclick="document.getElementById('image-input').click()" class="bg-slate-50 dark:bg-slate-800/50 p-8 rounded-3xl border-2 border-dashed border-slate-300 dark:border-slate-700 group hover:border-pelindo-cyan dark:hover:border-pelindo-cyan hover:bg-sky-50/30 dark:hover:bg-sky-900/20 transition-all cursor-pointer">
                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-6 text-center cursor-pointer">Foto Infrastruktur (Opsional)</label>
                    
                    <div class="flex flex-col items-center">
                        <div id="image-preview-container" class="hidden mb-4">
                            <img id="image-preview" src="#" alt="Preview" class="max-h-56 rounded-2xl shadow-2xl border-4 border-white dark:border-slate-700 object-cover transition-transform hover:scale-105">
                            <p class="text-[9px] text-pelindo-blue dark:text-pelindo-cyan font-black uppercase text-center mt-4 tracking-widest">Pratinjau Foto Terpilih</p>
                        </div>
                        
                        <div id="upload-placeholder" class="text-center py-4">
                            <div class="w-20 h-20 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-200 dark:border-slate-700 shadow-sm group-hover:scale-110 group-hover:text-pelindo-blue transition-all">
                                <i class="fas fa-cloud-upload-alt text-3xl text-slate-300 dark:text-slate-600 group-hover:text-pelindo-blue transition-colors"></i>
                            </div>
                            <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Klik atau Seret Gambar ke Sini</p>
                            <p class="text-[9px] text-slate-300 dark:text-slate-600 mt-1 uppercase font-bold">Format: JPG, PNG, WEBP (Max 2MB)</p>
                        </div>

                        <input type="file" name="image" id="image-input" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </div>
                </div>

                <!-- LOKASI / ENTITAS -->
                <div>
                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Entitas Kepemilikan (Area Tugas)</label>
                    
                    @if(auth()->user()->role === 'superadmin')
                        <x-searchable-select 
                            name="entity_id" 
                            :value="old('entity_id')"
                            placeholder="-- Pilih Cabang / Entitas --"
                            :options="$entities->map(fn($e) => ['value' => $e->id, 'label' => $e->name . ' (' . $e->code . ')'])->toArray()"
                            required
                        />
                    @else
                        <div class="w-full border border-slate-200 dark:border-slate-700 bg-slate-100/70 dark:bg-slate-800/70 rounded-2xl text-sm font-black p-5 text-slate-500 dark:text-slate-400 uppercase cursor-not-allowed flex items-center justify-between">
                            <span>{{ auth()->user()->entity->name ?? 'Entitas Tidak Diketahui' }}</span>
                            <i class="fas fa-lock text-slate-300 dark:text-slate-600"></i>
                        </div>
                        <input type="hidden" name="entity_id" value="{{ auth()->user()->entity_id }}">
                        <p class="text-[9px] font-bold text-pelindo-blue dark:text-pelindo-cyan mt-2 ml-1 uppercase tracking-widest"><i class="fas fa-shield-alt mr-1"></i> Data akan otomatis terhubung ke area {{ auth()->user()->entity->name ?? 'Anda' }}</p>
                    @endif
                </div>

                <!-- KATEGORI & KODE -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Kategori Infrastruktur</label>
                        <div class="relative">
                            <select name="category" x-model="selectedCategory" class="w-full border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-2xl text-sm font-black p-5 focus:ring-4 focus:ring-sky-50 dark:focus:ring-sky-900/20 focus:border-pelindo-blue dark:focus:border-pelindo-cyan transition-all uppercase appearance-none cursor-pointer text-slate-700 dark:text-slate-200" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="equipment">Peralatan (Equipment)</option>
                                <option value="facility">Fasilitas (Facility)</option>
                                <option value="utility">Utilitas (Utility)</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black {{ $errors->has('code_name') ? 'text-red-600 dark:text-red-400' : 'text-pelindo-blue dark:text-pelindo-cyan' }} uppercase tracking-[0.2em] mb-3 ml-1">
                            Kode Identitas Unit (Unique ID)
                        </label>
                        <div class="relative">
                            <input type="text" name="code_name" value="{{ old('code_name') }}" placeholder="Contoh: GLC-01, HMC-05, TR-12" 
                                   class="w-full rounded-2xl text-sm font-black p-5 transition-all uppercase {{ $errors->has('code_name') ? 'border-red-400 dark:border-red-700 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 focus:ring-red-200 dark:focus:ring-red-900/20 focus:border-red-500 dark:focus:border-red-400' : 'border-blue-200 dark:border-blue-800 bg-blue-50/30 dark:bg-blue-900/20 text-slate-900 dark:text-slate-100 focus:ring-sky-50 dark:focus:ring-sky-900/20 focus:border-pelindo-blue dark:focus:border-pelindo-cyan' }}" required>
                            
                            @error('code_name')
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 text-red-500">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- JENIS ALAT -->
                <div class="bg-slate-50 dark:bg-slate-800/30 p-8 rounded-3xl border border-slate-100 dark:border-slate-800">
                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-4 ml-1">Spesifikasi Jenis Alat / Fasilitas</label>
                    
                    <div x-show="inputMode === 'select'" class="space-y-4">
                        <div class="relative">
                            <select name="type_select" x-model="selectedType" 
                                    @change="
                                        if(selectedType === 'new') { 
                                             inputMode = 'text'; 
                                        } else if(selectedType !== '') {
                                             selectedCategory = typeMap[selectedType];
                                        }
                                    " 
                                    class="w-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-2xl text-sm font-black p-5 focus:ring-4 focus:ring-sky-50 dark:focus:ring-sky-900/20 focus:border-pelindo-blue dark:focus:border-pelindo-cyan transition-all uppercase appearance-none cursor-pointer text-slate-700 dark:text-slate-200">
                                <option value="">-- Pilih dari Basis Data --</option>
                                @foreach($typeCategoryMap ?? [] as $type => $cat)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                                <option value="new" class="text-pelindo-blue dark:text-pelindo-cyan">➕ INPUT JENIS BARU (CUSTOM)</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                        </div>
                        <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 ml-1 uppercase tracking-widest"><i class="fas fa-info-circle mr-1"></i> Pilih jenis yang sudah ada untuk mempercepat pengisian data</p>
                    </div>

                    <div x-show="inputMode === 'text'" x-cloak class="space-y-4">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <input type="text" name="type_new" value="{{ old('type_new') }}" placeholder="Ketik jenis alat baru (Contoh: Rubber Tyred Gantry)..." 
                                   class="flex-1 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-2xl text-sm font-black p-5 focus:ring-4 focus:ring-sky-50 dark:focus:ring-sky-900/20 focus:border-pelindo-blue dark:focus:border-pelindo-cyan transition-all uppercase text-slate-900 dark:text-slate-100">
                            <button type="button" @click="inputMode = 'select'; selectedType = '';" class="px-8 py-5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-red-100 dark:hover:bg-red-900/40 transition-all border border-red-100 dark:border-red-900/30">
                                Batal
                            </button>
                        </div>
                        <p class="text-[9px] font-bold text-pelindo-blue dark:text-pelindo-cyan ml-1 uppercase tracking-widest"><i class="fas fa-plus-circle mr-1"></i> Anda sedang menambahkan jenis alat baru ke sistem</p>
                    </div>
                </div>

                <!-- STATUS OPERASIONAL -->
                <div x-data="{ assetStatus: '{{ old('status', 'available') }}' }">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Status Operasional Awal</label>
                            <div class="relative">
                                <select name="status" x-model="assetStatus" class="w-full border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-2xl text-sm font-black p-5 focus:ring-4 focus:ring-sky-50 dark:focus:ring-sky-900/20 focus:border-pelindo-blue dark:focus:border-pelindo-cyan transition-all uppercase appearance-none cursor-pointer text-slate-700 dark:text-slate-200" required>
                                    <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Available (Siap Pakai)</option>
                                    <option value="breakdown" {{ old('status') == 'breakdown' ? 'selected' : '' }}>Breakdown (Terkendala)</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                            </div>
                            @error('status') <p class="text-red-500 text-[10px] mt-2 font-bold ml-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div x-show="assetStatus === 'breakdown'" x-cloak x-transition class="space-y-3">
                            <label class="block text-[11px] font-black text-red-600 dark:text-red-400 uppercase tracking-[0.2em] mb-3 ml-1">Detail Kerusakan / Kendala</label>
                            <div class="relative">
                                <i class="fas fa-tools absolute left-5 top-5 text-red-400"></i>
                                <textarea name="issue_detail" placeholder="Jelaskan detail kerusakan alat saat ini..." 
                                          class="w-full border border-red-200 dark:border-red-900/30 bg-red-50/30 dark:bg-red-900/10 rounded-2xl text-sm font-bold p-5 pl-12 focus:ring-4 focus:ring-red-50 focus:border-red-500 transition-all text-slate-700 dark:text-slate-200">{{ old('issue_detail') }}</textarea>
                            </div>
                            @error('issue_detail') <p class="text-red-500 text-[10px] font-bold ml-1 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="pt-10 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-pelindo-blue dark:bg-pelindo-blue hover:bg-pelindo-navy dark:hover:bg-pelindo-navy text-white py-5 rounded-2xl text-xs font-black uppercase tracking-[0.2em] shadow-2xl shadow-blue-900/20 transition-all flex items-center justify-center gap-3 active:scale-[0.98]">
                        <i class="fas fa-save text-pelindo-cyan"></i> Simpan Inventaris Unit
                    </button>
                    <a href="{{ route('admin.infrastructures.index') }}" class="px-10 py-5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-2xl text-xs font-black uppercase tracking-widest transition-all text-center border border-slate-200 dark:border-slate-700">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const previewContainer = document.getElementById('image-preview-container');
            const previewImage = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
