<x-app-layout>
    <div class="max-w-4xl mx-auto w-full space-y-8 pb-16 animate-fade-up">
        
        <div class="bg-white p-8 md:p-10 rounded-[2rem] border border-slate-200 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-amber-400 to-amber-500"></div>

            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Edit Data Aset</h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Perbarui informasi infrastruktur operasional</p>
                </div>
                <i class="fas fa-edit text-slate-100 text-4xl"></i>
            </div>
            
            <form action="{{ route('admin.infrastructures.update', $infrastructure->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="bg-slate-50 p-6 rounded-2xl border-2 border-dashed border-slate-200 group hover:border-amber-400 transition-all">
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 text-center">Update Foto Infrastruktur</label>
                    
                    <div class="flex flex-col items-center">
                        <div id="image-preview-container" class="mb-4">
                            @if($infrastructure->image)
                                <img id="image-preview" src="{{ asset('storage/' . $infrastructure->image) }}" alt="Current Photo" class="max-h-48 rounded-xl shadow-md border-4 border-white">
                                <p id="preview-label" class="text-[9px] text-slate-400 font-bold uppercase text-center mt-2 italic">Foto Saat Ini</p>
                            @else
                                <div id="upload-placeholder" class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-slate-300 mb-2 group-hover:text-amber-400 transition-colors"></i>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">Belum ada foto. Klik untuk tambah.</p>
                                </div>
                                <img id="image-preview" src="#" alt="Preview" class="hidden max-h-48 rounded-xl shadow-md border-4 border-white">
                            @endif
                        </div>

                        <input type="file" name="image" id="image-input" class="hidden" accept="image/*" onchange="previewImage(this)">
                        
                        <button type="button" onclick="document.getElementById('image-input').click()" class="mt-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-[10px] font-black uppercase text-slate-600 hover:bg-slate-100 transition-all">
                            Ganti Foto
                        </button>
                    </div>
                    @error('image') <p class="text-red-500 text-[10px] mt-2 font-bold text-center uppercase">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Entitas Kepemilikan (Lokasi)</label>
                    <select name="entity_id" class="w-full border-slate-200 bg-slate-50 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-amber-50 focus:border-amber-500 transition-all" required>
                        @foreach($entities as $entity)
                            <option value="{{ $entity->id }}" {{ (old('entity_id') ?? $infrastructure->entity_id) == $entity->id ? 'selected' : '' }}>
                                {{ $entity->name }} ({{ $entity->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('entity_id') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Kategori Aset</label>
                        <select name="category" class="w-full border-slate-200 bg-slate-50 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-amber-50 focus:border-amber-500 transition-all uppercase" required>
                            <option value="equipment" {{ (old('category') ?? $infrastructure->category) == 'equipment' ? 'selected' : '' }}>Peralatan (Equipment)</option>
                            <option value="facility" {{ (old('category') ?? $infrastructure->category) == 'facility' ? 'selected' : '' }}>Fasilitas (Facility)</option>
                            <option value="utility" {{ (old('category') ?? $infrastructure->category) == 'utility' ? 'selected' : '' }}>Utilitas (Utility)</option>
                        </select>
                        @error('category') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Kode Identitas Aset</label>
                        <input type="text" name="code_name" value="{{ old('code_name') ?? $infrastructure->code_name }}" class="w-full border-slate-200 bg-slate-50 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-amber-50 focus:border-amber-500 transition-all uppercase" required>
                        @error('code_name') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Jenis Alat / Fasilitas</label>
                    <input type="text" name="type" value="{{ old('type') ?? $infrastructure->type }}" class="w-full border-slate-200 bg-slate-50 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-amber-50 focus:border-amber-500 transition-all uppercase" required>
                    @error('type') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Status Operasional Saat Ini</label>
                    <select name="status" class="w-full border-slate-200 bg-slate-50 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-amber-50 focus:border-amber-500 transition-all uppercase">
                        <option value="available" {{ (old('status') ?? $infrastructure->status) == 'available' ? 'selected' : '' }}>Available (Siap Pakai)</option>
                        <option value="breakdown" {{ (old('status') ?? $infrastructure->status) == 'breakdown' ? 'selected' : '' }}>Breakdown (Terkendala)</option>
                    </select>
                </div>

                <div class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white py-4 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Perbarui Data & Foto
                    </button>
                    <a href="{{ route('admin.infrastructures.index') }}" class="px-8 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-center">
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
                        previewLabel.classList.add('text-amber-600');
                    }
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
