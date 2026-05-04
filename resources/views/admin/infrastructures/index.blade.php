<x-app-layout>
    <div class="max-w-[1600px] mx-auto w-full space-y-6 animate-fade-up" 
         x-data="{ 
                showDeleteModal: false,
                deleteUrl: '',
                assetCode: '',
                showDetailModal: false,
                selectedItem: {},
             }">

        <!-- MODAL DELETE (Clean Enterprise Style) -->
        <template x-teleport="body">
            <div x-show="showDeleteModal" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div @click.away="showDeleteModal = false"
                     x-show="showDeleteModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl max-w-sm w-full border border-slate-200 dark:border-slate-800 overflow-hidden">
                    <div class="p-10 text-center">
                        <div class="w-20 h-20 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 border border-red-100 dark:border-red-800">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                        <h3 class="text-2xl font-black text-[#003366] dark:text-white uppercase tracking-tight mb-2">Hapus Aset?</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed mb-8">
                            Anda yakin ingin menghapus data <strong class="text-red-600 dark:text-red-400" x-text="assetCode"></strong>? <br>
                            Data ini akan dihapus secara permanen.
                        </p>
                        <div class="flex gap-4">
                            <button @click="showDeleteModal = false" class="flex-1 py-4 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">Batal</button>
                            <form :action="deleteUrl" method="POST" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-900/20 hover:bg-red-700 transition-all">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- MODAL DETAIL (Full Audit Style) -->
        <template x-teleport="body">
            <div x-show="showDetailModal" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                
                <div @click.away="showDetailModal = false" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="bg-white dark:bg-slate-900 rounded-[3rem] shadow-2xl max-w-4xl w-full overflow-hidden border border-slate-200 dark:border-slate-800 flex flex-col max-h-[90vh]">
                    
                    <div class="bg-[#003366] dark:bg-slate-800 p-8 flex justify-between items-center shrink-0 border-b border-white/10 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-12 opacity-5 pointer-events-none">
                            <i class="fas fa-boxes-stacked text-8xl text-white"></i>
                        </div>
                        <div class="flex items-center gap-4 relative z-10">
                            <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-2xl text-white shadow-inner border border-white/10"><i class="fas fa-info-circle"></i></div>
                            <div>
                                <h2 class="text-2xl font-black text-white uppercase tracking-tight leading-none" x-text="selectedItem.code"></h2>
                                <p class="text-[10px] font-black text-blue-300 uppercase tracking-[0.2em] mt-2 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></span>
                                    Inventory Specification & Health History
                                </p>
                            </div>
                        </div>
                        <button @click="showDetailModal = false" class="text-white/60 hover:text-white transition-colors p-2 relative z-10"><i class="fas fa-times text-2xl"></i></button>
                    </div>

                    <div class="p-8 overflow-y-auto custom-scrollbar flex-1 bg-white dark:bg-slate-900">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                            
                            <!-- LEFT COLUMN: PHOTO & BASIC INFO -->
                            <div class="lg:col-span-5 space-y-6">
                                <div class="aspect-video lg:aspect-square bg-slate-50 dark:bg-slate-800 rounded-[2rem] border border-slate-200 dark:border-slate-700 overflow-hidden flex items-center justify-center relative group shadow-inner">
                                    <template x-if="selectedItem.image">
                                        <img :src="selectedItem.image" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!selectedItem.image">
                                        <div class="flex flex-col items-center gap-3 text-slate-300 dark:text-slate-600">
                                            <i class="fas text-6xl" :class="{'fa-truck': selectedItem.category === 'equipment', 'fa-building': selectedItem.category === 'facility', 'fa-bolt': selectedItem.category === 'utility', 'fa-image': !selectedItem.category}"></i>
                                            <span class="text-[9px] font-black uppercase tracking-widest">No Physical Asset Image</span>
                                        </div>
                                    </template>
                                    
                                    <div class="absolute bottom-4 left-4 px-4 py-2 bg-white/90 dark:bg-slate-800/90 backdrop-blur-md text-[9px] font-black rounded-xl uppercase tracking-[0.2em] border border-white/50 dark:border-slate-700/50 shadow-sm"
                                         :class="{'text-blue-600 dark:text-blue-400': selectedItem.category === 'equipment', 'text-emerald-600 dark:text-emerald-400': selectedItem.category === 'facility', 'text-amber-600 dark:text-amber-400': selectedItem.category === 'utility'}">
                                        <span x-text="selectedItem.category === 'equipment' ? 'Peralatan' : (selectedItem.category === 'facility' ? 'Fasilitas' : 'Utilitas')"></span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-slate-50 dark:bg-slate-800/50 p-5 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
                                        <p class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Status Saat Ini</p>
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border"
                                              :class="selectedItem.status === 'available' ? 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800' : 'bg-red-50 text-red-600 border-red-100 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800'">
                                            <span class="w-1.5 h-1.5 rounded-full" :class="selectedItem.status === 'available' ? 'bg-emerald-500' : 'bg-red-500 animate-pulse'"></span>
                                            <span x-text="selectedItem.status === 'available' ? 'Ready' : 'Down'"></span>
                                        </span>
                                    </div>
                                    <div class="bg-slate-50 dark:bg-slate-800/50 p-5 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
                                        <p class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Area Pelabuhan</p>
                                        <p class="text-xs font-black text-slate-700 dark:text-slate-200 uppercase truncate" x-text="selectedItem.entity"></p>
                                    </div>
                                </div>

                                <div class="bg-white dark:bg-slate-800/30 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 space-y-4 shadow-sm">
                                    <div class="flex justify-between items-center">
                                        <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase">Jenis Aset</span>
                                        <span class="text-[10px] font-black text-slate-800 dark:text-slate-100 uppercase" x-text="selectedItem.type"></span>
                                    </div>
                                    <div class="w-full h-px bg-slate-100 dark:bg-slate-800"></div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase">Input Oleh</span>
                                        <span class="text-[10px] font-black text-slate-800 dark:text-slate-100 uppercase" x-text="selectedItem.created_by"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase">Terakhir Update</span>
                                        <span class="text-[10px] font-black text-slate-800 dark:text-slate-100 uppercase" x-text="selectedItem.updated_by"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase">Waktu Registrasi</span>
                                        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase" x-text="selectedItem.created_at"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- RIGHT COLUMN: MAINTENANCE TIMELINE -->
                            <div class="lg:col-span-7 flex flex-col">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-xs font-black text-[#003366] dark:text-blue-400 uppercase tracking-widest flex items-center gap-3">
                                        <i class="fas fa-history text-slate-400"></i> Histori Kerusakan & Perbaikan
                                    </h3>
                                    <span class="text-[9px] font-black bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 px-3 py-1 rounded-full border border-slate-200 dark:border-slate-700" x-text="selectedItem.logs?.length + ' Catatan'"></span>
                                </div>

                                <div class="flex-1 overflow-y-auto custom-scrollbar pr-2 min-h-[300px]">
                                    <div class="relative border-l-2 border-slate-100 dark:border-slate-800 ml-3 space-y-8 pb-4">
                                        <template x-for="(log, index) in selectedItem.logs" :key="index">
                                            <div class="relative pl-8">
                                                <!-- Timeline Dot -->
                                                <div class="absolute w-4 h-4 rounded-full border-2 border-white dark:border-slate-900 -left-[9px] top-1 shadow-sm"
                                                     :class="{
                                                        'bg-red-500': log.repair_status === 'reported',
                                                        'bg-amber-500': log.repair_status === 'on_progress',
                                                        'bg-purple-500': log.repair_status === 'order_part',
                                                        'bg-emerald-500': log.repair_status === 'resolved'
                                                     }">
                                                </div>
                                                
                                                <div class="bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all">
                                                    <div class="flex justify-between items-start mb-2">
                                                        <span class="text-[9px] font-black px-2 py-1 rounded-md uppercase tracking-widest border"
                                                              :class="{
                                                                  'bg-red-50 text-red-600 border-red-100': log.repair_status === 'reported',
                                                                  'bg-amber-50 text-amber-600 border-amber-100': log.repair_status === 'on_progress',
                                                                  'bg-purple-50 text-purple-600 border-purple-100': log.repair_status === 'order_part',
                                                                  'bg-emerald-50 text-emerald-600 border-emerald-100': log.repair_status === 'resolved'
                                                              }"
                                                              x-text="log.repair_status.replace('_', ' ')">
                                                        </span>
                                                        <span class="text-[9px] font-bold text-slate-400 uppercase" x-text="new Date(log.created_at).toLocaleDateString('id-ID', {day:'numeric', month:'short', year:'numeric'})"></span>
                                                    </div>
                                                    <p class="text-xs font-black text-slate-700 dark:text-slate-200 leading-relaxed italic" x-text="'&quot;' + log.issue_detail + '&quot;'"></p>
                                                    
                                                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 dark:border-slate-700/50 pt-3">
                                                        <div class="flex items-center gap-2">
                                                            <i class="fas fa-user-circle text-slate-300 dark:text-slate-600"></i>
                                                            <span class="text-[9px] font-black text-slate-500 dark:text-slate-400 uppercase" x-text="log.created_by?.name || 'Sistem'"></span>
                                                        </div>
                                                        <span class="text-[9px] font-bold text-slate-400 uppercase" x-text="log.vendor_pic || 'Internal'"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- EMPTY LOGS -->
                                        <template x-if="!selectedItem.logs || selectedItem.logs.length === 0">
                                            <div class="pl-8 flex flex-col items-center justify-center py-10 opacity-30">
                                                <i class="fas fa-shield-check text-5xl mb-4"></i>
                                                <p class="text-[10px] font-black uppercase tracking-widest">Aset Bersih (No History)</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>


        <!-- ALERTS -->
        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 p-5 rounded-3xl flex items-start gap-4 shadow-sm animate-fade-in mb-8">
                <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-emerald-800 dark:text-emerald-400 uppercase tracking-tight">Transaksi Berhasil</h3>
                    <p class="text-xs text-emerald-700 dark:text-emerald-300 mt-1 font-medium leading-relaxed">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-5 rounded-3xl flex items-start gap-4 shadow-sm animate-fade-in mb-8">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-2xl flex items-center justify-center text-red-600 dark:text-red-400 shrink-0">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-red-800 dark:text-red-400 uppercase tracking-tight">Terjadi Kesalahan</h3>
                    <p class="text-xs text-red-700 dark:text-red-300 mt-1 font-medium leading-relaxed">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- HEADER & FILTER SECTION -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm space-y-10 relative overflow-hidden mb-8">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#003366] to-[#0055a4]"></div>

            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/30 text-[#0055a4] dark:text-blue-400 rounded-[1.5rem] flex items-center justify-center text-3xl border border-blue-100 dark:border-blue-800 shadow-inner">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-[#003366] dark:text-white uppercase tracking-tight">Katalog Infrastruktur</h1>
                        <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 flex items-center gap-2">
                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span> 
                            Sistem Manajemen Aset Terpadu
                        </p>
                    </div>
                </div>
                
                <a href="{{ route('admin.infrastructures.create') }}" 
                   class="bg-[#003366] hover:bg-[#001e3c] dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-3 shadow-lg shadow-blue-900/20 active:scale-95">
                    <i class="fas fa-plus text-xs"></i> Register Aset Baru
                </a>
            </div>

            <!-- Server-side Filter Form -->
            <form action="{{ route('admin.infrastructures.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 pt-8 border-t border-slate-100 dark:border-slate-800/50">
                <div class="relative lg:col-span-2">
                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode atau Nama Aset..." 
                           class="w-full pl-12 pr-6 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs font-bold text-slate-700 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-[#0055a4] transition-all">
                </div>
                
                @if(auth()->user()->role === 'superadmin')
                <div class="relative">
                    <select name="entity_id" onchange="this.form.submit()" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs font-black text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-[#0055a4] uppercase transition-all appearance-none cursor-pointer">
                        <option value="all">Semua Terminal</option>
                        @foreach($allEntities as $entity)
                            <option value="{{ $entity->id }}" {{ request('entity_id') == $entity->id ? 'selected' : '' }}>{{ $entity->name }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                </div>
                @endif

                <div class="relative">
                    <select name="category" onchange="this.form.submit()" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs font-black text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-[#0055a4] uppercase transition-all appearance-none cursor-pointer">
                        <option value="all">Kategori</option>
                        <option value="equipment" {{ request('category') == 'equipment' ? 'selected' : '' }}>EQUIPMENT</option>
                        <option value="facility" {{ request('category') == 'facility' ? 'selected' : '' }}>FACILITY</option>
                        <option value="utility" {{ request('category') == 'utility' ? 'selected' : '' }}>UTILITY</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                </div>

                <div class="relative">
                    <select name="status" onchange="this.form.submit()" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs font-black text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-[#0055a4] uppercase transition-all appearance-none cursor-pointer">
                        <option value="all">Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Ready</option>
                        <option value="breakdown" {{ request('status') == 'breakdown' ? 'selected' : '' }}>Down</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                </div>
            </form>
        </div>


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
                                                            'updated_at' => $item->updated_at->format('d M Y H:i'),
                                                            'logs' => $item->breakdownLogs->map(fn($l) => [
                                                                'issue_detail' => $l->issue_detail,
                                                                'repair_status' => $l->repair_status,
                                                                'created_at' => $l->created_at->toIso8601String(),
                                                                'vendor_pic' => $l->vendor_pic,
                                                                'created_by' => ['name' => optional($l->createdBy)->name]
                                                            ])
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
                @if($infrastructures->hasPages())
                    <div class="px-8 py-6 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800">
                        {{ $infrastructures->links() }}
                    </div>
                @endif
            </div>
    </div>
</x-app-layout>
