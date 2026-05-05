<x-app-layout>
    <div class="max-w-4xl mx-auto w-full space-y-6 animate-fade-up" x-data="{ showConfirmModal: false }">
        
        <template x-teleport="body">
            <div x-show="showConfirmModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 backdrop-blur-none"
                 x-transition:enter-end="opacity-100 backdrop-blur-sm"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 backdrop-blur-sm"
                 x-transition:leave-end="opacity-0 backdrop-blur-none"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
                 style="display: none;">
                
                <div @click.away="showConfirmModal = false" 
                     class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-200 dark:border-slate-800 transform transition-all">
                    
                    <div class="p-6 sm:p-8">
                        <div class="flex items-start gap-5">
                            <div class="flex-shrink-0 w-12 h-12 bg-blue-50 dark:bg-blue-900/30 text-[#0055a4] dark:text-blue-400 rounded-full flex items-center justify-center text-xl">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-[#003366] dark:text-blue-400 mb-1">Konfirmasi Pembaruan</h2>
                                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                    Apakah Anda yakin ingin menyimpan perubahan data aset ini? Pastikan informasi operasional dan foto infrastruktur sudah sesuai.
                                </p>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col-reverse sm:flex-row gap-3 sm:justify-end">
                            <button type="button" @click="showConfirmModal = false" 
                                    class="w-full sm:w-auto px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-semibold transition-colors">
                                Periksa Kembali
                            </button>
                            
                            <button type="button" @click="document.getElementById('editAssetForm').submit()" 
                                    class="w-full sm:w-auto px-5 py-2.5 bg-[#003366] hover:bg-[#002244] text-white rounded-lg text-sm font-semibold transition-colors shadow-sm shadow-blue-900/20">
                                Ya, Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="bg-white dark:bg-slate-900 p-8 md:p-10 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#003366] to-[#0055a4]"></div>

            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black text-[#003366] dark:text-blue-400 uppercase tracking-tight">Edit Data Aset</h2>
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Perbarui informasi infrastruktur operasional terminal</p>
                </div>
                <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center border border-blue-100 dark:border-blue-800">
                    <i class="fas fa-edit text-[#0055a4] dark:text-blue-400 text-2xl"></i>
                </div>
            </div>
            
            <form id="editAssetForm" action="{{ route('admin.infrastructures.update', $infrastructure->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 group hover:border-[#0055a4] dark:hover:border-blue-400 transition-all">
                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-4 text-center">Update Foto Infrastruktur</label>
                    
                    <div class="flex flex-col items-center">
                        <div id="image-preview-container" class="mb-4">
                            @if($infrastructure->image)
                                <img id="image-preview" src="{{ asset('storage/' . $infrastructure->image) }}" alt="Current Photo" class="max-h-48 rounded-xl shadow-md border-4 border-white dark:border-slate-800">
                                <p id="preview-label" class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase text-center mt-2 italic">Foto Saat Ini</p>
@else
                                <div id="upload-placeholder" class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-slate-300 dark:text-slate-600 mb-2 group-hover:text-[#0055a4] dark:group-hover:text-blue-400 transition-colors"></i>
                                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase">Belum ada foto. Klik untuk tambah.</p>
                                </div>
                                <img id="image-preview" src="#" alt="Preview" class="hidden max-h-48 rounded-xl shadow-md border-4 border-white dark:border-slate-800">
                            @endif
                        </div>

                        <input type="file" name="image" id="image-input" class="hidden" accept="image/*" onchange="previewImage(this)">
                        
                        <button type="button" onclick="document.getElementById('image-input').click()" class="mt-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-[10px] font-black uppercase text-slate-600 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-[#0055a4] dark:hover:text-blue-400 hover:border-[#0055a4] dark:hover:border-blue-800 transition-all">
                            Ganti Foto
                        </button>
                    </div>
                    @error('image') <p class="text-red-500 text-[10px] mt-2 font-bold text-center uppercase">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Entitas Kepemilikan (Lokasi)</label>
                    
                    @if(auth()->user()->role === 'superadmin')
                        <x-searchable-select 
                            name="entity_id" 
                            :value="old('entity_id') ?? $infrastructure->entity_id"
                            placeholder="-- Pilih Cabang / Entitas --"
                            :options="$entities->map(fn($e) => ['value' => $e->id, 'label' => $e->name . ' (' . $e->code . ')'])->toArray()"
                            required
                        />
                    @else
                        <div class="w-full border border-slate-200 dark:border-slate-700 bg-slate-100/70 dark:bg-slate-800/70 rounded-2xl text-sm font-black p-5 text-slate-500 dark:text-slate-400 uppercase cursor-not-allowed flex items-center justify-between">
                            <span>{{ $infrastructure->entity->name ?? 'Entitas Tidak Diketahui' }}</span>
                            <i class="fas fa-lock text-slate-300 dark:text-slate-600"></i>
                        </div>
                        <input type="hidden" name="entity_id" value="{{ $infrastructure->entity_id }}">
                    @endif
                    @error('entity_id') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Kategori Aset</label>
                        <select name="category" class="w-full border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-blue-50 dark:focus:ring-blue-900/20 focus:border-[#0055a4] dark:focus:border-blue-400 transition-all uppercase text-slate-900 dark:text-slate-100" required>
                            <option value="equipment" {{ (old('category') ?? $infrastructure->category) == 'equipment' ? 'selected' : '' }}>Peralatan (Equipment)</option>
                            <option value="facility" {{ (old('category') ?? $infrastructure->category) == 'facility' ? 'selected' : '' }}>Fasilitas (Facility)</option>
                            <option value="utility" {{ (old('category') ?? $infrastructure->category) == 'utility' ? 'selected' : '' }}>Utilitas (Utility)</option>
                        </select>
                        @error('category') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Kode Identitas Aset</label>
                        <input type="text" name="code_name" value="{{ old('code_name') ?? $infrastructure->code_name }}" class="w-full border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-blue-50 dark:focus:ring-blue-900/20 focus:border-[#0055a4] dark:focus:border-blue-400 transition-all uppercase text-slate-900 dark:text-slate-100" required>
                        @error('code_name') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Jenis Alat / Fasilitas</label>
                    <input type="text" name="type" value="{{ old('type') ?? $infrastructure->type }}" class="w-full border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-blue-50 dark:focus:ring-blue-900/20 focus:border-[#0055a4] dark:focus:border-blue-400 transition-all uppercase text-slate-900 dark:text-slate-100" required>
                    @error('type') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div x-data="{ assetStatus: '{{ old('status', $infrastructure->status) }}' }">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Status Operasional Saat Ini</label>
                            <select name="status" x-model="assetStatus" class="w-full border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-blue-50 dark:focus:ring-blue-900/20 focus:border-[#0055a4] dark:focus:border-blue-400 transition-all uppercase text-slate-900 dark:text-slate-100">
                                <option value="available" {{ (old('status') ?? $infrastructure->status) == 'available' ? 'selected' : '' }}>Available (Siap Pakai)</option>
                                <option value="breakdown" {{ (old('status') ?? $infrastructure->status) == 'breakdown' ? 'selected' : '' }}>Breakdown (Terkendala)</option>
                            </select>
                            @error('status') <p class="text-red-500 text-[10px] mt-2 font-bold ml-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div x-show="assetStatus === 'breakdown'" x-cloak x-transition class="space-y-3">
                            <label class="block text-[11px] font-black text-red-600 dark:text-red-400 uppercase tracking-[0.2em] mb-2 ml-1">Detail Kerusakan / Kendala</label>
                            <div class="relative">
                                <i class="fas fa-tools absolute left-5 top-5 text-red-400"></i>
                                <textarea name="issue_detail" placeholder="Jelaskan detail kerusakan alat saat ini..." 
                                          class="w-full border border-red-200 dark:border-red-900/30 bg-red-50/30 dark:bg-red-900/10 rounded-2xl text-sm font-bold p-5 pl-12 focus:ring-4 focus:ring-red-50 focus:border-red-500 transition-all text-slate-700 dark:text-slate-200">{{ old('issue_detail') }}</textarea>
                            </div>
                            @error('issue_detail') <p class="text-red-500 text-[10px] font-bold ml-1 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row gap-4">
                    <button type="button" @click="showConfirmModal = true" class="flex-1 bg-[#003366] dark:bg-blue-600 hover:bg-[#002244] dark:hover:bg-blue-700 text-white py-4 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-900/20 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Perbarui Data & Foto
                    </button>
                    
                    <a href="{{ route('admin.infrastructures.index') }}" class="px-8 py-4 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>

    </div>

    <script>
        function previewImage(input) {
            const previewImage = document.getElementById('image-preview');
            const previewLabel = document.getElementById('preview-label');
            const placeholder = document.getElementById('upload-placeholder');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                    if (previewLabel) {
                        previewLabel.innerText = 'Pratinjau Foto Baru';
                        previewLabel.classList.remove('italic');
                        previewLabel.classList.remove('text-slate-400', 'dark:text-slate-500');
                        previewLabel.classList.add('text-[#0055a4]', 'dark:text-blue-400', 'font-black');
                    }
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
