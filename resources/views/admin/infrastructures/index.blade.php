<x-app-layout>
    <div class="max-w-[1600px] mx-auto w-full space-y-6 animate-fade-up" 
         x-data="{ 
                showDeleteModal: false,
                deleteUrl: '',
                assetCode: '',
                showDetailModal: false,
                selectedItem: {},
             }">

            <template x-teleport="body">
                <div x-show="showDeleteModal" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
                     style="display: none;">
                    
                    <div @click.away="showDeleteModal = false" 
                         class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-xl max-w-sm w-full overflow-hidden border border-slate-200 dark:border-slate-800 animate-in zoom-in-95 duration-300">
                        
                        <div class="p-8 text-center">
                            <div class="w-20 h-20 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 border border-red-100 dark:border-red-800">
                                <i class="fas fa-trash-alt"></i>
                            </div>
                            
                            <h2 class="text-xl font-black text-[#003366] dark:text-blue-400 uppercase tracking-tight mb-2">Hapus Aset?</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium leading-relaxed mb-6">
                                Anda yakin ingin menghapus infrastruktur <br>
                                <strong class="text-red-600 dark:text-red-400 text-base" x-text="assetCode"></strong>? <br>
                                <span class="text-xs">Tindakan ini tidak dapat dibatalkan.</span>
                            </p>

                            <div class="flex gap-3">
                                <button type="button" @click="showDeleteModal = false" 
                                        class="flex-1 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                                    Batal
                                </button>
                                
                                <form :action="deleteUrl" method="POST" class="flex-1">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="w-full py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-md shadow-red-600/20">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </template>

                    <!-- State Kosong -->
                    <div x-show="filteredItems.length === 0" style="display: none;" class="py-24 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center border border-slate-200 mx-auto mb-4">
                            <i class="fas fa-folder-open text-2xl text-slate-300"></i>
                        </div>
                        <p class="font-semibold text-sm text-slate-700">Data Tidak Ditemukan</p>
                        <p class="text-xs mt-1 font-medium text-slate-500">Coba sesuaikan filter atau kata kunci pencarian Anda.</p>
                    </div>
                </div>
            </div>

            <!-- MODAL DELETE (Clean Enterprise Style) -->
            <template x-teleport="body">
                <div x-show="showDeleteModal" x-cloak
                     class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
                    <div @click.away="showDeleteModal = false"
                         x-show="showDeleteModal"
                         x-transition.scale.origin.bottom.duration.200ms
                         class="bg-white rounded-lg shadow-xl max-w-sm w-full border border-slate-200 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-red-100 text-red-600 rounded-full flex items-center justify-center shrink-0">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">Konfirmasi Penghapusan</h3>
                                    <p class="text-xs text-slate-500 mt-1">Anda yakin ingin menghapus data <strong class="text-slate-800" x-text="assetCode"></strong>? Data ini akan dihapus secara permanen.</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 flex justify-end gap-2">
                            <button @click="showDeleteModal = false" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded text-xs font-semibold hover:bg-slate-50 transition-colors">Batal</button>
                            <form :action="deleteUrl" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded text-xs font-semibold hover:bg-red-700 transition-colors shadow-sm">Hapus Data</button>
                            </form>
                        </div>
                    </div>
                </div>
            </template>

            <!-- MODAL DETAIL (Clean Enterprise Style) -->
            <template x-teleport="body">
                <div x-show="showDetailModal" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
                     style="display: none;">
                    
                    <div @click.away="showDetailModal = false" 
                         class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-xl max-w-2xl w-full overflow-hidden border border-slate-200 dark:border-slate-800 animate-in zoom-in-95 duration-300 flex flex-col max-h-[90vh]">
                        
                        <div class="bg-[#003366] dark:bg-slate-800 p-6 flex justify-between items-center shrink-0">
                            <h2 class="text-lg font-black text-white uppercase tracking-widest flex items-center gap-3">
                                <i class="fas fa-info-circle text-blue-300"></i> Detail Infrastruktur
                            </h2>
                            <button @click="showDetailModal = false" class="text-slate-400 hover:text-red-500 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="p-6 overflow-y-auto bg-slate-50 flex-1">
                            <div class="flex flex-col md:flex-row gap-6">

                                <!-- Foto / Image -->
                                <div class="w-full md:w-1/3 shrink-0">
                                    <div class="aspect-square bg-slate-50 dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden flex items-center justify-center relative group">
                                        <template x-if="selectedItem.image">
                                            <img :src="selectedItem.image" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!selectedItem.image">
                                            <i class="fas text-4xl text-slate-300" :class="{'fa-truck': selectedItem.category === 'equipment', 'fa-building': selectedItem.category === 'facility', 'fa-bolt': selectedItem.category === 'utility', 'fa-image': !selectedItem.category}"></i>
                                        </template>
                                        
                                        <div class="absolute top-3 left-3 px-3 py-1 bg-white/90 dark:bg-slate-800/90 backdrop-blur text-[10px] font-black rounded-lg uppercase tracking-widest border border-white/50 dark:border-slate-700/50 shadow-sm"
                                             :class="{'text-blue-600 dark:text-blue-400': selectedItem.category === 'equipment', 'text-emerald-600 dark:text-emerald-400': selectedItem.category === 'facility', 'text-amber-600 dark:text-amber-400': selectedItem.category === 'utility'}">
                                            <span x-text="selectedItem.category === 'equipment' ? 'Peralatan' : (selectedItem.category === 'facility' ? 'Fasilitas' : 'Utilitas')"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 p-4 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 text-center">
                                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Status Kesiapan</p>
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-widest"
                                              :class="selectedItem.status === 'available' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400'">
                                            <i class="fas" :class="selectedItem.status === 'available' ? 'fa-check-circle' : 'fa-engine-warning'"></i>
                                            <span x-text="selectedItem.status === 'available' ? 'Ready' : 'Breakdown'"></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="w-full md:w-2/3 space-y-6">
                                    <div>
                                        <h3 class="text-2xl font-black text-[#003366] dark:text-blue-400 uppercase" x-text="selectedItem.code"></h3>
                                        <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mt-1" x-text="selectedItem.type"></p>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-slate-50 dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700">
                                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Entitas / Cabang</p>
                                            <p class="text-xs font-bold text-slate-700 dark:text-slate-200 uppercase" x-text="selectedItem.entity"></p>
                                        </div>
                                        <div class="bg-slate-50 dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700">
                                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Kuantitas</p>
                                            <p class="text-xs font-bold text-slate-700 dark:text-slate-200 uppercase" x-text="selectedItem.quantity + ' Unit'"></p>
                                        </div>
                                    </div>
                                    
                                    <div class="border-t border-slate-200 dark:border-slate-800 pt-4 space-y-3">
                                        <div class="flex justify-between items-center text-xs">
                                            <span class="font-bold text-slate-400 dark:text-slate-500">Dibuat Oleh</span>
                                            <span class="font-bold text-slate-700 dark:text-slate-300" x-text="selectedItem.created_by"></span>
                                        </div>
                                        <div class="flex justify-between items-center text-xs">
                                            <span class="font-bold text-slate-400 dark:text-slate-500">Terakhir Diperbarui Oleh</span>
                                            <span class="font-bold text-slate-700 dark:text-slate-300" x-text="selectedItem.updated_by"></span>
                                        </div>
                                        <div class="flex justify-between items-center text-xs">
                                            <span class="font-bold text-slate-400 dark:text-slate-500">Tanggal Dibuat</span>
                                            <span class="font-bold text-slate-700 dark:text-slate-300" x-text="selectedItem.created_at"></span>
                                        </div>
                                        <div class="flex justify-between items-center text-xs">
                                            <span class="font-bold text-slate-400 dark:text-slate-500">Tanggal Diperbarui</span>
                                            <span class="font-bold text-slate-700 dark:text-slate-300" x-text="selectedItem.updated_at"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-6 py-4 rounded-xl text-sm font-bold shadow-sm flex items-center gap-3 animate-fade-in mb-6">
                    <i class="fas fa-check-circle text-emerald-500 text-lg"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 text-red-700 border border-red-200 px-6 py-4 rounded-xl text-sm font-bold shadow-sm flex items-center gap-3 animate-fade-in mb-6">
                    <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i> {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm space-y-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#003366] to-[#0055a4]"></div>

                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/30 text-[#0055a4] dark:text-blue-400 rounded-2xl flex items-center justify-center text-2xl border border-blue-100 dark:border-blue-800">
                            <i class="fas fa-boxes-stacked"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-black text-[#003366] dark:text-blue-400 uppercase tracking-tight">Katalog Infrastruktur</h1>
                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Sistem Manajemen Aset Terpadu</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.infrastructures.create') }}" class="bg-[#003366] hover:bg-[#001e3c] text-white px-8 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all flex items-center gap-2 shadow-md shadow-blue-900/10">
                            <i class="fas fa-plus"></i> Tambah Aset
                        </a>
                    </div>
                </div>

                <!-- Server-side Filter Form -->
                <form action="{{ route('admin.infrastructures.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 pt-6 border-t border-slate-100 dark:border-slate-800">
                    <div class="relative lg:col-span-2">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode atau Nama Aset..." 
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-[#0055a4] focus:border-[#0055a4] transition-all">
                    </div>
                    
                    @if(auth()->user()->role === 'superadmin')
                    <select name="entity_id" onchange="this.form.submit()" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-[#0055a4] uppercase transition-all">
                        <option value="all">Semua Terminal</option>
                        @foreach($allEntities as $entity)
                            <option value="{{ $entity->id }}" {{ request('entity_id') == $entity->id ? 'selected' : '' }}>{{ $entity->name }}</option>
                        @endforeach
                    </select>
                    @endif

                    <select name="category" onchange="this.form.submit()" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-[#0055a4] uppercase transition-all">
                        <option value="all">Semua Kategori</option>
                        <option value="equipment" {{ request('category') == 'equipment' ? 'selected' : '' }}>EQUIPMENT</option>
                        <option value="facility" {{ request('category') == 'facility' ? 'selected' : '' }}>FACILITY</option>
                        <option value="utility" {{ request('category') == 'utility' ? 'selected' : '' }}>UTILITY</option>
                    </select>

                    <select name="status" onchange="this.form.submit()" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-[#0055a4] uppercase transition-all">
                        <option value="all">Semua Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Ready (Tersedia)</option>
                        <option value="breakdown" {{ request('status') == 'breakdown' ? 'selected' : '' }}>Down (Rusak)</option>
                    </select>

                    <noscript>
                        <button type="submit" class="bg-slate-800 text-white p-3 rounded-xl">Filter</button>
                    </noscript>
                </form>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-6 py-4 rounded-xl text-sm font-bold shadow-sm flex items-center gap-3 animate-fade-in">
                    <i class="fas fa-check-circle text-emerald-500"></i> {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-200 dark:border-slate-800">
                                <th class="px-8 py-5 w-16 text-center">NO</th>
                                <th class="px-8 py-5">Identitas Unit</th>
                                <th class="px-8 py-5">Spesifikasi Kategori</th>
                                <th class="px-8 py-5 text-center">Status Operasional</th>
                                <th class="px-8 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        
                        @php
                            $groupedInfras = $infrastructures->groupBy(fn($i) => optional($i->entity)->name ?? 'Lokasi Tidak Valid');
                        @endphp

                        @forelse($groupedInfras as $entityName => $items)
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <tr class="bg-blue-50/50 dark:bg-blue-900/10 border-y border-blue-100 dark:border-blue-900/30">
                                    <td colspan="5" class="px-8 py-4">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-black text-[#003366] dark:text-blue-400 uppercase tracking-widest text-[11px] flex items-center gap-2">
                                                <i class="fas fa-map-marker-alt text-[#0055a4] dark:text-blue-400 text-sm"></i> {{ $entityName }}
                                            </h3>
                                            <span class="text-[9px] font-black text-[#0055a4] dark:text-blue-400 uppercase bg-white dark:bg-slate-800 px-3 py-1.5 rounded-md border border-blue-100 dark:border-blue-900/50 shadow-sm">{{ $items->count() }} Unit Aset</span>
                                        </div>
                                    </td>
                                </tr>

                                @foreach($items as $index => $item)
                                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors group">
                                        <td class="px-8 py-5 text-center text-slate-400 dark:text-slate-500 font-bold text-xs">{{ $index + 1 }}</td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shrink-0 shadow-sm">
                                                    @if($item->image)
                                                        <img src="{{ asset('storage/'.$item->image) }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
                                                            <i class="fas text-xs {{ $item->category === 'equipment' ? 'fa-truck' : ($item->category === 'facility' ? 'fa-building' : ($item->category === 'utility' ? 'fa-bolt' : 'fa-image')) }}"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <span class="font-black text-[#003366] dark:text-blue-400 text-xs tracking-wider uppercase bg-white dark:bg-slate-800 px-2.5 py-1 rounded border border-slate-200 dark:border-slate-700 shadow-sm">{{ $item->code_name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <p class="text-xs font-black text-slate-700 dark:text-slate-200 uppercase">{{ $item->type }}</p>
                                            <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mt-0.5">{{ $item->category }}</p>
                                            <div class="flex items-center gap-1.5 mt-2 opacity-60">
                                                <i class="fas fa-user-edit text-[8px] text-slate-400 dark:text-slate-500"></i>
                                                <p class="text-[8px] font-black tracking-widest text-slate-400 dark:text-slate-500 uppercase">{{ $item->updatedBy->name ?? 'System' }}</p>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            @if($item->status === 'available')
                                                <span class="bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 px-3 py-1.5 rounded text-[9px] font-black uppercase tracking-widest border border-emerald-200 dark:border-emerald-800 inline-flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Ready
                                                </span>
                                            @else
                                                <span class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-3 py-1.5 rounded text-[9px] font-black uppercase tracking-widest border border-red-200 dark:border-red-800 inline-flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Down
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                <button type="button" 
                                                        @click="selectedItem = {{ json_encode([
                                                            'id' => $item->id,
                                                            'code' => strtoupper($item->code_name),
                                                            'type' => strtoupper($item->type),
                                                            'category' => $item->category,
                                                            'entity' => optional($item->entity)->name,
                                                            'status' => $item->status,
                                                            'quantity' => $item->quantity,
                                                            'image' => $item->image ? asset('storage/'.$item->image) : null,
                                                            'created_by' => optional($item->createdBy)->name ?? 'System',
                                                            'updated_by' => optional($item->updatedBy)->name ?? 'System',
                                                            'created_at' => $item->created_at->format('d M Y H:i'),
                                                            'updated_at' => $item->updated_at->format('d M Y H:i')
                                                        ]) }}; showDetailModal = true;"
                                                        class="w-8 h-8 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 hover:border-emerald-200 dark:hover:border-emerald-800 rounded-lg flex items-center justify-center transition-all shadow-sm">
                                                    <i class="fas fa-eye text-[10px]"></i>
                                                </button>
                                                <a href="{{ route('admin.infrastructures.edit', $item->id) }}" class="w-8 h-8 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:text-[#0055a4] dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:border-blue-200 dark:hover:border-blue-800 rounded-lg flex items-center justify-center transition-all shadow-sm">
                                                    <i class="fas fa-pen text-[10px]"></i>
                                                </a>
                                                <button type="button" 
                                                        @click="deleteUrl = '{{ route('admin.infrastructures.destroy', $item->id) }}'; assetCode = '{{ $item->code_name }}'; showDeleteModal = true;"
                                                        class="w-8 h-8 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 hover:border-red-200 dark:hover:border-red-800 rounded-lg flex items-center justify-center transition-all shadow-sm">
                                                    <i class="fas fa-trash-alt text-[10px]"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @empty
                            <tbody>
                                <tr>
                                    <td colspan="5" class="px-8 py-24 text-center bg-slate-50/50 dark:bg-slate-800/20">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center border border-slate-200 dark:border-slate-700 shadow-sm mb-4">
                                                <i class="fas fa-folder-open text-2xl text-slate-300 dark:text-slate-600"></i>
                                            </div>
                                            <p class="font-black uppercase tracking-[0.2em] text-sm text-slate-600 dark:text-slate-400">Data Tidak Ditemukan</p>
                                            <p class="text-[10px] mt-1 font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Sesuaikan filter atau kata kunci pencarian</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        @endforelse
                    </table>
                </div>
            </div>
    </div>
</x-app-layout>
