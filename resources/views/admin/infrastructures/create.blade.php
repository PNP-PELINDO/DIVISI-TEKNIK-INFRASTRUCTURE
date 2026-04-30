<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        [x-cloak] { display: none !important; }

        /* Focus state minimalis korporat */
        input:focus, select:focus, textarea:focus {
            box-shadow: 0 0 0 1px #0055a4 !important;
            border-color: #0055a4 !important;
        }
    </style>

    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto w-full px-4 sm:px-6 lg:px-8 space-y-6 animate-fade">

            <!-- HEADER -->
            <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden">
                <div class="absolute left-0 top-0 h-full w-1.5 bg-[#0055a4]"></div>

                <div>
                    <h1 class="text-lg font-bold text-[#003366] flex items-center gap-2">
                        <i class="fas fa-boxes-stacked text-[#0055a4]"></i> Registrasi Aset Baru
                    </h1>
                    <p class="text-xs font-medium text-slate-500 mt-1">Sistem Pendataan Terpusat: 1 Entri Data untuk 1 Unit Fisik Alat</p>
                </div>

                <div class="shrink-0">
                    <a href="{{ route('admin.infrastructures.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-[#003366] text-xs font-semibold transition-colors">
                        <i class="fas fa-arrow-left"></i> Kembali ke Katalog
                    </a>
                </div>
            </div>

            <!-- ERROR ALERTS -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 p-4 rounded-lg flex items-start gap-3 shadow-sm">
                    <i class="fas fa-exclamation-triangle text-red-600 mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-red-800">Registrasi Gagal</h3>
                        <ul class="mt-1 space-y-1 text-xs text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- MAIN FORM CARD -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="bg-[#00152b] px-6 py-4 border-b border-slate-700 flex items-center gap-3">
                    <i class="fas fa-clipboard-list text-blue-400"></i>
                    <h2 class="text-xs font-bold text-white uppercase tracking-widest">Formulir Pendataan Spesifikasi Aset</h2>
                </div>

                <form action="{{ route('admin.infrastructures.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6"
                      x-data="{
                          inputMode: '{{ old('type_select') == 'new' ? 'text' : 'select' }}',
                          selectedType: '{{ old('type_select') }}',
                          selectedCategory: '{{ old('category') }}',
                          typeMap: {{ json_encode($typeCategoryMap ?? []) }}
                      }">
                    @csrf

                    <!-- AREA UPLOAD FOTO -->
                    <div onclick="document.getElementById('image-input').click()" class="bg-slate-50 p-6 rounded-lg border-2 border-dashed border-slate-300 hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer group text-center relative overflow-hidden">

                        <div id="image-preview-container" class="hidden mb-0">
                            <img id="image-preview" src="#" alt="Preview" class="max-h-56 mx-auto rounded border border-slate-200 object-cover shadow-sm">
                            <div class="mt-3 text-xs font-semibold text-blue-600">Klik untuk mengganti foto</div>
                        </div>

                        <div id="upload-placeholder" class="py-6">
                            <div class="w-16 h-16 bg-white border border-slate-200 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform shadow-sm">
                                <i class="fas fa-camera text-2xl text-slate-400 group-hover:text-blue-500 transition-colors"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-700">Foto Fisik Aset (Opsional)</h3>
                            <p class="text-xs text-slate-500 mt-1">Upload gambar dalam format JPG/PNG untuk identifikasi visual.</p>
                        </div>

                        <input type="file" name="image" id="image-input" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </div>

                    <!-- LOKASI / ENTITAS -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Lokasi / Entitas Kepemilikan <span class="text-red-500">*</span></label>

                        @if(auth()->user()->role === 'superadmin')
                            <select name="entity_id" class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-md block p-2.5 transition-colors" required>
                                <option value="" disabled selected>-- Pilih Cabang / Anak Perusahaan --</option>
                                @foreach($entities as $entity)
                                    <option value="{{ $entity->id }}" {{ old('entity_id') == $entity->id ? 'selected' : '' }}>
                                        {{ $entity->code }} - {{ $entity->name }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="w-full bg-slate-100 border border-slate-200 text-slate-500 text-sm rounded-md block p-2.5 flex items-center justify-between cursor-not-allowed">
                                <span class="font-semibold">{{ auth()->user()->entity->name ?? 'Entitas Tidak Diketahui' }}</span>
                                <i class="fas fa-lock opacity-50"></i>
                            </div>
                            <p class="text-[10px] font-semibold text-slate-500 mt-1"><i class="fas fa-info-circle text-blue-500 mr-1"></i> Area terkunci otomatis sesuai hak akses wilayah tugas Anda.</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- KATEGORI -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Kategori Infrastruktur <span class="text-red-500">*</span></label>
                            <select name="category" x-model="selectedCategory" class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-md block p-2.5 transition-colors" required>
                                <option value="" disabled>-- Pilih Kategori Utama --</option>
                                <option value="equipment">Peralatan (Equipment)</option>
                                <option value="facility">Fasilitas (Facility)</option>
                                <option value="utility">Utilitas (Utility)</option>
                            </select>
                            <p x-show="selectedType !== '' && selectedType !== 'new' && typeMap[selectedType]" x-cloak class="text-[10px] font-semibold text-emerald-600 mt-1">
                                <i class="fas fa-magic mr-1"></i> Kategori diatur otomatis berdasarkan tipe.
                            </p>
                        </div>

                        <!-- KODE ASET -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                                Kode Identitas (Unik) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="code_name" value="{{ old('code_name') }}" placeholder="Contoh: TT-01, RTG-05, GUDANG-A"
                                   class="w-full bg-white text-sm rounded-md block p-2.5 transition-colors font-mono font-bold uppercase {{ $errors->has('code_name') ? 'border-red-500 text-red-700 bg-red-50' : 'border-slate-300 text-slate-900' }}" required>
                            <p class="text-[10px] font-medium text-slate-500">Gunakan kode lambung atau nomor registrasi unik pada bodi alat.</p>
                        </div>
                    </div>

                    <!-- JENIS ALAT / TIPE -->
                    <div class="bg-slate-50 p-5 rounded-lg border border-slate-200 space-y-3">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Spesifikasi Jenis Alat / Fasilitas <span class="text-red-500">*</span></label>

                        <div x-show="inputMode === 'select'">
                            <select name="type_select" x-model="selectedType"
                                    @change="
                                        if(selectedType === 'new') {
                                            inputMode = 'text';
                                            selectedType = 'new';
                                        } else if(selectedType !== '') {
                                            selectedCategory = typeMap[selectedType];
                                        }
                                    "
                                    class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-md block p-2.5 transition-colors cursor-pointer">
                                <option value="" disabled>-- Pilih Tipe yang Tersedia --</option>
                                @foreach($typeCategoryMap ?? [] as $type => $cat)
                                    <option value="{{ $type }}">{{ $type }} (Kategori: {{ ucfirst($cat) }})</option>
                                @endforeach
                                <option value="new" class="font-bold text-[#0055a4] bg-blue-50">+ BUAT JENIS SPESIFIKASI BARU</option>
                            </select>
                        </div>

                        <div x-show="inputMode === 'text'" x-cloak class="flex flex-col sm:flex-row gap-3">
                            <input type="text" name="type_new" value="{{ old('type_new') }}" placeholder="Ketik jenis alat baru (Contoh: Rubber Tyred Gantry)..."
                                   class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-md block p-2.5 transition-colors">
                            <button type="button" @click="inputMode = 'select'; selectedType = ''; selectedCategory = '';"
                                    class="w-full sm:w-auto px-5 py-2.5 bg-white text-slate-600 border border-slate-300 rounded-md font-semibold text-xs transition-colors hover:bg-slate-100 whitespace-nowrap">
                                Batal Input Baru
                            </button>
                        </div>
                        @error('type_new')
                            <p class="text-[10px] font-bold text-red-500 mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hidden Status (Always Available when created) -->
                    <input type="hidden" name="status" value="available">

                    <!-- ACTION BUTTONS -->
                    <div class="pt-6 border-t border-slate-200 flex flex-col-reverse sm:flex-row justify-end gap-3">
                        <a href="{{ route('admin.infrastructures.index') }}" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-md text-sm font-semibold transition-colors hover:bg-slate-50 text-center">
                            Batal
                        </a>
                        <button type="submit" class="w-full sm:w-auto bg-[#0055a4] hover:bg-[#003366] text-white px-6 py-2.5 rounded-md text-sm font-semibold transition-colors shadow-sm flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i> Simpan Data Aset
                        </button>
                    </div>
                </form>
            </div>

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
