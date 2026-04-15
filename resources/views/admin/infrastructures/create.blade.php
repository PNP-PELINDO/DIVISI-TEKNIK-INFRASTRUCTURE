<x-app-layout>
    <div class="max-w-4xl mx-auto w-full space-y-8 pb-16 animate-fade-up">
        
        <div class="bg-white p-8 md:p-10 rounded-[2rem] border border-slate-200 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#003366] to-[#0055a4]"></div>

            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black text-[#003366] uppercase tracking-tight">Tambah Inventaris Aset</h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Registrasi peralatan, fasilitas, atau utilitas baru</p>
                </div>
                <i class="fas fa-camera-retro text-slate-100 text-4xl"></i>
            </div>
            
            <form action="{{ route('admin.infrastructures.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div onclick="document.getElementById('image-input').click()" class="bg-slate-50 p-6 rounded-2xl border-2 border-dashed border-slate-300 group hover:border-blue-500 hover:bg-blue-50/30 transition-all cursor-pointer">
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 text-center cursor-pointer">Foto Infrastruktur</label>
                    
                    <div class="flex flex-col items-center">
                        <div id="image-preview-container" class="hidden mb-4">
                            <img id="image-preview" src="#" alt="Preview" class="max-h-48 rounded-xl shadow-md border-4 border-white object-cover">
                        </div>
                        
                        <div id="upload-placeholder" class="text-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-slate-300 mb-2 group-hover:text-blue-500 transition-colors"></i>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Klik di mana saja untuk pilih gambar aset</p>
                            <p class="text-[9px] font-bold text-slate-300 uppercase mt-1">Format: JPG, PNG. Maks: 2MB</p>
                        </div>

                        <input type="file" name="image" id="image-input" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </div>
                    @error('image') <p class="text-red-500 text-[10px] mt-2 font-bold text-center uppercase">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Entitas Kepemilikan (Lokasi)</label>
                    <select name="entity_id" class="w-full border-slate-200 bg-slate-50 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-blue-50 focus:border-[#003366] transition-all" required>
                        <option value="">-- Pilih Cabang / Entitas --</option>
                        @foreach($entities as $entity)
                            <option value="{{ $entity->id }}" {{ old('entity_id') == $entity->id ? 'selected' : '' }}>
                                {{ $entity->name }} ({{ $entity->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('entity_id') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Kategori Aset</label>
                        <select name="category" class="w-full border-slate-200 bg-slate-50 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-blue-50 focus:border-[#003366] transition-all uppercase" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="equipment" {{ old('category') == 'equipment' ? 'selected' : '' }}>Peralatan (Equipment)</option>
                            <option value="facility" {{ old('category') == 'facility' ? 'selected' : '' }}>Fasilitas (Facility)</option>
                            <option value="utility" {{ old('category') == 'utility' ? 'selected' : '' }}>Utilitas (Utility)</option>
                        </select>
                        @error('category') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Kode Identitas Aset</label>
                        <input type="text" name="code_name" value="{{ old('code_name') }}" placeholder="Contoh: GLC-01" class="w-full border-slate-200 bg-slate-50 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-blue-50 focus:border-[#003366] transition-all uppercase" required>
                        @error('code_name') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1">Jenis Alat / Fasilitas</label>
                    <input type="text" name="type" value="{{ old('type') }}" placeholder="Contoh: Gantry Luffing Crane" class="w-full border-slate-200 bg-slate-50 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-blue-50 focus:border-[#003366] transition-all uppercase" required>
                    @error('type') <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <input type="hidden" name="status" value="available">

                <div class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-[#003366] hover:bg-[#001e3c] text-white py-4 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-900/20 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-save text-blue-400"></i> Simpan Aset & Gambar
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
