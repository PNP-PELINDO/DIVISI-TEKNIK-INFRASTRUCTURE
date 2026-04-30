<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        [x-cloak] { display: none !important; }

        /* Custom Scrollbar Korporat */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* List Card Hover */
        .ent-list-item {
            transition: all 0.2s ease-in-out;
            border-left: 3px solid transparent;
        }
        .ent-list-item:hover {
            background-color: #f8fafc;
            border-left-color: #0055a4;
        }
        .ent-list-item.status-down {
            border-left-color: #ef4444;
        }
        .ent-list-item.status-ready {
            border-left-color: #10b981;
        }
    </style>

    <div class="min-h-screen py-8"
         x-data="{
            search: '',
            filterEntity: 'all',
            filterCategory: 'all',
            filterStatus: 'all',
            showDeleteModal: false,
            deleteUrl: '',
            assetCode: '',
            showDetailModal: false,
            selectedItem: {},
            @php
                $jsData = $infrastructures->map(function($item) {
                    return [
                        'id' => $item->id,
                        'code' => strtoupper($item->code_name ?? 'TANPA-KODE'),
                        'type' => $item->type ?? 'Tidak Diketahui',
                        'category' => $item->category ?? '',
                        'entity' => optional($item->entity)->name ?? 'Lokasi Tidak Valid',
                        'status' => $item->status ?? 'available',
                        'quantity' => $item->quantity ?? 1,
                        'image' => $item->image ? asset('storage/'.$item->image) : null,
                        'edit_url' => route('admin.infrastructures.edit', $item->id),
                        'delete_url' => route('admin.infrastructures.destroy', $item->id),
                        'created_by' => optional($item->createdBy)->name ?? 'System',
                        'updated_by' => optional($item->updatedBy)->name ?? 'System',
                        'created_at' => $item->created_at ? $item->created_at->format('d M Y H:i') : '-',
                        'updated_at' => $item->updated_at ? $item->updated_at->format('d M Y H:i') : '-'
                    ];
                });

                $uniqueEntities = $infrastructures->map(fn($i) => optional($i->entity)->name ?? 'Lokasi Tidak Valid')->unique()->values();
            @endphp
            items: {{ json_encode($jsData) }},

            get filteredItems() {
                return this.items.filter(i => {
                    const matchSearch = i.code.toLowerCase().includes(this.search.toLowerCase()) ||
                                      i.type.toLowerCase().includes(this.search.toLowerCase());
                    const matchEntity = this.filterEntity === 'all' || i.entity === this.filterEntity;
                    const matchCat = this.filterCategory === 'all' || i.category === this.filterCategory;
                    const matchStatus = this.filterStatus === 'all' || i.status === this.filterStatus;
                    return matchSearch && matchEntity && matchCat && matchStatus;
                })
            },

            get groupedItems() {
                return this.filteredItems.reduce((groups, item) => {
                    if (!groups[item.entity]) {
                        groups[item.entity] = [];
                    }
                    groups[item.entity].push(item);
                    return groups;
                }, {});
            }
         }">

        <div class="max-w-[1600px] mx-auto w-full px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- ALERTS -->
            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-4 py-3 rounded-md text-sm font-medium shadow-sm flex items-center gap-3 mb-4">
                    <i class="fas fa-check-circle text-emerald-500"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 text-red-700 border border-red-200 px-4 py-3 rounded-md text-sm font-medium shadow-sm flex items-center gap-3 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-500"></i> {{ session('error') }}
                </div>
            @endif

            <!-- MAIN CONTAINER -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm flex flex-col">

                <!-- HEADER SECTIONS -->
                <div class="border-b border-slate-200 px-6 py-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-lg font-bold text-[#003366] flex items-center gap-2">
                            <i class="fas fa-boxes-stacked text-[#0055a4]"></i> Manajemen Infrastruktur
                        </h1>
                        <p class="text-xs font-medium text-slate-500 mt-1">Katalog dan status operasional aset terpadu Pelindo</p>
                    </div>
                    <div class="shrink-0">
                        <a href="{{ route('admin.infrastructures.create') }}" class="inline-flex items-center justify-center gap-2 bg-[#0055a4] hover:bg-[#003366] text-white px-4 py-2.5 rounded text-xs font-semibold transition-colors shadow-sm">
                            <i class="fas fa-plus"></i> Register Aset Baru
                        </a>
                    </div>
                </div>

                <!-- FILTER SECTIONS -->
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-4 flex flex-col lg:flex-row gap-3">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" x-model="search" placeholder="Cari Kode atau Tipe Alat..."
                               class="w-full pl-9 pr-3 py-2 bg-white border border-slate-300 rounded text-xs font-medium text-slate-700 focus:ring-[#0055a4] focus:border-[#0055a4] transition-colors">
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 shrink-0">
                        <select x-model="filterEntity" class="w-full sm:w-48 px-3 py-2 bg-white border border-slate-300 rounded text-xs font-medium text-slate-700 focus:ring-[#0055a4] transition-colors">
                            <option value="all">Semua Entitas</option>
                            @foreach($uniqueEntities as $entityName)
                                <option value="{{ $entityName }}">{{ $entityName }}</option>
                            @endforeach
                        </select>

                        <select x-model="filterCategory" class="w-full sm:w-40 px-3 py-2 bg-white border border-slate-300 rounded text-xs font-medium text-slate-700 focus:ring-[#0055a4] transition-colors">
                            <option value="all">Semua Kategori</option>
                            <option value="equipment">Peralatan</option>
                            <option value="facility">Fasilitas</option>
                            <option value="utility">Utilitas</option>
                        </select>

                        <select x-model="filterStatus" class="w-full sm:w-40 px-3 py-2 bg-white border border-slate-300 rounded text-xs font-medium text-slate-700 focus:ring-[#0055a4] transition-colors">
                            <option value="all">Semua Status</option>
                            <option value="available">Ready</option>
                            <option value="breakdown">Breakdown</option>
                        </select>
                    </div>
                </div>

                <!-- DATA LIST -->
                <div class="flex-1 divide-y divide-slate-100">
                    <template x-for="(items, entity) in groupedItems" :key="entity">
                        <div class="pb-2">
                            <!-- Group Header -->
                            <div class="bg-slate-100/50 px-6 py-2.5 flex items-center justify-between sticky top-0 z-10 border-y border-slate-200">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-[#0055a4] text-xs"></i>
                                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wide" x-text="entity"></h3>
                                </div>
                                <span class="text-[10px] font-semibold text-slate-500 bg-white border border-slate-200 px-2 py-0.5 rounded shadow-sm" x-text="items.length + ' Aset'"></span>
                            </div>

                            <!-- Items -->
                            <div class="divide-y divide-slate-100">
                                <template x-for="item in items" :key="item.id">
                                    <div class="ent-list-item flex flex-col md:flex-row md:items-center px-6 py-4 gap-4"
                                         :class="item.status === 'available' ? 'status-ready' : 'status-down'">

                                        <!-- Image & Core Info -->
                                        <div class="flex items-center gap-4 flex-1 min-w-0">
                                            <div class="w-12 h-12 rounded border border-slate-200 bg-slate-50 shrink-0 overflow-hidden flex items-center justify-center">
                                                <template x-if="item.image">
                                                    <img :src="item.image" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!item.image">
                                                    <i class="fas text-lg text-slate-300" :class="{'fa-truck': item.category === 'equipment', 'fa-building': item.category === 'facility', 'fa-bolt': item.category === 'utility', 'fa-image': !item.category}"></i>
                                                </template>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-2 mb-0.5">
                                                    <span class="font-bold text-[#003366] text-sm truncate" x-text="item.code"></span>
                                                    <span class="px-2 py-0.5 rounded text-[9px] font-semibold uppercase border tracking-wide"
                                                          :class="{'bg-blue-50 text-blue-600 border-blue-200': item.category === 'equipment', 'bg-emerald-50 text-emerald-600 border-emerald-200': item.category === 'facility', 'bg-amber-50 text-amber-600 border-amber-200': item.category === 'utility'}"
                                                          x-text="item.category"></span>
                                                </div>
                                                <p class="text-xs font-medium text-slate-600 truncate" x-text="item.type"></p>
                                            </div>
                                        </div>

                                        <!-- Status & Action (Desktop & Mobile adapting) -->
                                        <div class="flex items-center justify-between md:justify-end gap-6 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-slate-100">

                                            <!-- Kuantitas -->
                                            <div class="text-center w-16 hidden sm:block">
                                                <p class="text-[10px] font-medium text-slate-400">Qty</p>
                                                <p class="text-xs font-semibold text-slate-700" x-text="item.quantity"></p>
                                            </div>

                                            <!-- Status Label -->
                                            <div class="w-24 text-right md:text-center">
                                                <template x-if="item.status === 'available'">
                                                    <span class="inline-flex items-center justify-center gap-1.5 px-2 py-1 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-semibold w-full">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Ready
                                                    </span>
                                                </template>
                                                <template x-if="item.status !== 'available'">
                                                    <span class="inline-flex items-center justify-center gap-1.5 px-2 py-1 rounded bg-red-50 text-red-700 border border-red-200 text-[10px] font-semibold w-full">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Down
                                                    </span>
                                                </template>
                                            </div>

                                            <!-- Actions -->
                                            <div class="flex items-center gap-1.5">
                                                <button type="button" @click="selectedItem = item; showDetailModal = true;" title="Detail"
                                                        class="w-8 h-8 flex items-center justify-center rounded bg-white border border-slate-300 text-slate-500 hover:text-[#0055a4] hover:bg-blue-50 hover:border-blue-300 transition-colors">
                                                    <i class="fas fa-eye text-xs"></i>
                                                </button>
                                                <a :href="item.edit_url" title="Edit"
                                                   class="w-8 h-8 flex items-center justify-center rounded bg-white border border-slate-300 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 hover:border-emerald-300 transition-colors">
                                                    <i class="fas fa-pen text-xs"></i>
                                                </a>
                                                <button type="button" @click="deleteUrl = item.delete_url; assetCode = item.code; showDeleteModal = true;" title="Hapus"
                                                        class="w-8 h-8 flex items-center justify-center rounded bg-white border border-slate-300 text-slate-500 hover:text-red-600 hover:bg-red-50 hover:border-red-300 transition-colors">
                                                    <i class="fas fa-trash-alt text-xs"></i>
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </template>
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
                <div x-show="showDetailModal" x-cloak
                     class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
                    <div @click.away="showDetailModal = false"
                         x-show="showDetailModal"
                         x-transition.scale.origin.bottom.duration.200ms
                         class="bg-white rounded-lg shadow-xl max-w-3xl w-full border border-slate-200 flex flex-col max-h-[90vh] overflow-hidden">

                        <div class="bg-white px-6 py-4 border-b border-slate-200 flex justify-between items-center shrink-0">
                            <h2 class="text-sm font-bold text-[#003366] flex items-center gap-2">
                                <i class="fas fa-info-circle text-[#0055a4]"></i> Informasi Aset
                            </h2>
                            <button @click="showDetailModal = false" class="text-slate-400 hover:text-red-500 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="p-6 overflow-y-auto bg-slate-50 flex-1">
                            <div class="flex flex-col md:flex-row gap-6">

                                <!-- Foto / Image -->
                                <div class="w-full md:w-1/3 shrink-0">
                                    <div class="aspect-square bg-white rounded border border-slate-200 overflow-hidden flex items-center justify-center relative shadow-sm">
                                        <template x-if="selectedItem.image">
                                            <img :src="selectedItem.image" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!selectedItem.image">
                                            <i class="fas text-4xl text-slate-300" :class="{'fa-truck': selectedItem.category === 'equipment', 'fa-building': selectedItem.category === 'facility', 'fa-bolt': selectedItem.category === 'utility', 'fa-image': !selectedItem.category}"></i>
                                        </template>
                                    </div>
                                    <div class="mt-4 p-3 bg-white rounded border border-slate-200 text-center shadow-sm">
                                        <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wide mb-1">Status Operasional</p>
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded text-xs font-bold border"
                                             :class="selectedItem.status === 'available' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200'">
                                            <i class="fas" :class="selectedItem.status === 'available' ? 'fa-check-circle' : 'fa-engine-warning'"></i>
                                            <span x-text="selectedItem.status === 'available' ? 'Ready' : 'Breakdown'"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detail Info -->
                                <div class="w-full md:w-2/3 space-y-4">
                                    <div class="bg-white p-5 rounded border border-slate-200 shadow-sm">
                                        <h3 class="text-lg font-bold text-slate-800" x-text="selectedItem.code"></h3>
                                        <p class="text-sm font-medium text-slate-600 mt-1" x-text="selectedItem.type"></p>

                                        <div class="grid grid-cols-2 gap-4 mt-5 pt-5 border-t border-slate-100">
                                            <div>
                                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Lokasi / Entitas</p>
                                                <p class="text-xs font-medium text-slate-800 mt-1" x-text="selectedItem.entity"></p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Kategori</p>
                                                <p class="text-xs font-medium text-slate-800 mt-1 capitalize" x-text="selectedItem.category"></p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Jumlah / Qty</p>
                                                <p class="text-xs font-medium text-slate-800 mt-1" x-text="selectedItem.quantity + ' Unit'"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-white rounded border border-slate-200 shadow-sm overflow-hidden">
                                        <div class="bg-slate-50 px-4 py-2 border-b border-slate-200">
                                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Jejak Sistem</p>
                                        </div>
                                        <div class="p-4 space-y-2">
                                            <div class="flex justify-between items-center text-xs">
                                                <span class="text-slate-500">Dibuat Oleh</span>
                                                <span class="font-medium text-slate-800" x-text="selectedItem.created_by + ' (' + selectedItem.created_at + ')'"></span>
                                            </div>
                                            <div class="flex justify-between items-center text-xs">
                                                <span class="text-slate-500">Terakhir Update</span>
                                                <span class="font-medium text-slate-800" x-text="selectedItem.updated_by + ' (' + selectedItem.updated_at + ')'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</x-app-layout>
