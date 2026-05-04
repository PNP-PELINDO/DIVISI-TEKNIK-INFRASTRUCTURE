<x-app-layout>
    <div class="max-w-[1600px] mx-auto w-full space-y-8 animate-fade-up" x-data="{ 
        showDeleteModal: false, 
        deleteUrl: '', 
        assetCode: '', 
        isLoading: false, 
        showHistoryModal: false, 
        selectedHistory: [],
        showUpdateModal: false,
        selectedAsset: null,
        selectedLogId: null,
        currentStatus: '',
        logDates: {}
    }">
        
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-widest text-slate-400">
                        <li><a href="#" class="hover:text-[#0055a4] transition-colors">Pusat</a></li>
                        <li><i class="fas fa-chevron-right text-[8px] mx-1"></i></li>
                        <li class="text-[#0055a4] dark:text-blue-400">Monitoring Log Kerusakan</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-black text-[#003366] dark:text-white uppercase tracking-tight flex items-center gap-3">
                    Monitoring Global
                    <span class="bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 px-3 py-1 rounded-full text-xs font-black">Super Admin</span>
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-2 font-medium">Pemantauan aktivitas kerusakan dan perbaikan seluruh cabang Pelindo.</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="bg-white dark:bg-slate-900 px-6 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Waktu Server</p>
                        <p class="text-xs font-black text-[#003366] dark:text-blue-400">{{ now()->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="w-px h-8 bg-slate-100 dark:bg-slate-800"></div>
                    <div class="w-10 h-10 bg-red-50 dark:bg-red-900/20 text-red-600 rounded-xl flex items-center justify-center text-lg">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTER & ACTION BAR -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <form action="{{ route('admin.breakdowns.index') }}" method="GET" @submit="isLoading = true" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-5 relative">
                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari kode alat, nama aset, atau detail kendala..." 
                           class="w-full pl-12 pr-6 py-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl text-sm font-bold placeholder-slate-400 focus:ring-2 focus:ring-red-500 transition-all">
                </div>

                <div class="md:col-span-3">
                    <select name="entity_id" onchange="this.form.submit()" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl text-sm font-bold text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-red-500 uppercase transition-all">
                        <option value="all">Semua Terminal</option>
                        @foreach($allEntities as $entity)
                            <option value="{{ $entity->id }}" {{ request('entity_id') == $entity->id ? 'selected' : '' }}>{{ $entity->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <select name="repair_status" onchange="this.form.submit()" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl text-sm font-bold text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-red-500 uppercase transition-all">
                        <option value="all">Status</option>
                        <option value="reported" {{ request('repair_status') == 'reported' ? 'selected' : '' }}>Reported</option>
                        <option value="order_part" {{ request('repair_status') == 'order_part' ? 'selected' : '' }}>Order Part</option>
                        <option value="on_progress" {{ request('repair_status') == 'on_progress' ? 'selected' : '' }}>On Progress</option>
                        <option value="resolved" {{ request('repair_status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>

                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white rounded-2xl transition-all flex items-center justify-center">
                        <i class="fas fa-filter"></i>
                    </button>
                    @if(request()->anyFilled(['search', 'entity_id', 'repair_status']))
                        <a href="{{ route('admin.breakdowns.index') }}" class="w-14 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 rounded-2xl flex items-center justify-center transition-all">
                            <i class="fas fa-undo"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- MAIN DATA TABLE -->
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
            <div class="overflow-x-auto hide-scrollbar">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-200 dark:border-slate-800">
                            <th class="px-8 py-6 w-16 text-center">No</th>
                            <th class="px-8 py-6">Unit & Lokasi</th>
                            <th class="px-8 py-6">Detail Kendala</th>
                            <th class="px-8 py-6 text-center">Status</th>
                            <th class="px-8 py-6">Timeline</th>
                            <th class="px-8 py-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($logs as $index => $log)
                        <tr class="hover:bg-red-50/20 dark:hover:bg-red-900/5 transition-colors group">
                            <td class="px-8 py-6 text-center text-slate-400 font-bold text-xs">{{ $index + 1 }}</td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-slate-50 dark:bg-slate-800 rounded-xl flex items-center justify-center text-[#003366] dark:text-blue-400 text-sm border border-slate-100 dark:border-slate-700">
                                        <i class="fas fa-cube"></i>
                                    </div>
                                    <div>
                                        <p class="font-black text-[#003366] dark:text-blue-400 text-sm uppercase leading-none">{{ $log->infrastructure->code_name ?? 'UNIT TERHAPUS' }}</p>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-2">
                                            <i class="fas fa-map-marker-alt mr-1"></i> {{ $log->infrastructure->entity->name ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-[11px] font-bold text-slate-600 dark:text-slate-300 max-w-[250px] truncate italic">"{{ $log->issue_detail }}"</p>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-tighter mt-1">PIC: {{ $log->vendor_pic ?? 'Internal' }}</p>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @php
                                    $statusConfig = [
                                        'reported' => ['bg' => 'bg-red-500', 'label' => 'Reported'],
                                        'order_part' => ['bg' => 'bg-purple-600', 'label' => 'Order Part'],
                                        'on_progress' => ['bg' => 'bg-amber-500', 'label' => 'Working'],
                                        'resolved' => ['bg' => 'bg-emerald-500', 'label' => 'Ready']
                                    ];
                                    $conf = $statusConfig[$log->repair_status] ?? ['bg' => 'bg-slate-500', 'label' => 'Unknown'];
                                @endphp
                                <span class="{{ $conf['bg'] }} text-white px-3 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest shadow-sm">
                                    {{ $conf['label'] }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center justify-between gap-4">
                                        <span class="text-[8px] font-black text-slate-400 uppercase">Lapor</span>
                                        <span class="text-[10px] font-bold text-[#0055a4]">{{ $log->created_at->format('d/m/y') }}</span>
                                    </div>
                                    @if($log->resolved_date)
                                    <div class="flex items-center justify-between gap-4">
                                        <span class="text-[8px] font-black text-emerald-600 uppercase">Selesai</span>
                                        <span class="text-[10px] font-black text-emerald-600">{{ \Carbon\Carbon::parse($log->resolved_date)->format('d/m/y') }}</span>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" 
                                            @click="
                                                selectedAsset = {id: '{{ $log->infrastructure->id ?? '' }}', code: '{{ addslashes($log->infrastructure->code_name ?? '') }}'}; 
                                                selectedLogId = '{{ $log->id }}'; 
                                                currentStatus = '{{ $log->repair_status }}'; 
                                                logDates = {
                                                    troubleshoot_date: '{{ $log->troubleshoot_date }}',
                                                    ba_date: '{{ $log->ba_date }}',
                                                    work_order_date: '{{ $log->work_order_date }}',
                                                    pr_po_date: '{{ $log->pr_po_date }}',
                                                    sparepart_date: '{{ $log->sparepart_date }}',
                                                    start_work_date: '{{ $log->start_work_date }}',
                                                    com_test_date: '{{ $log->com_test_date }}',
                                                    resolved_date: '{{ $log->resolved_date }}',
                                                    vendor_pic: '{{ addslashes($log->vendor_pic ?? '') }}'
                                                };
                                                showUpdateModal = true;
                                            "
                                            class="w-9 h-9 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl flex items-center justify-center transition-all shadow-sm" title="Koreksi Data">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>

                                    <button type="button" 
                                            @click="selectedHistory = {{ $log->statusHistories->map(fn($h) => [
                                                'id' => $h->id,
                                                'new_status' => strtoupper($h->new_status),
                                                'note' => $h->note,
                                                'created_at' => $h->created_at->format('d M Y, H:i'),
                                                'user' => ['name' => $h->user->name]
                                            ])->toJson() }}; showHistoryModal = true;"
                                            class="w-9 h-9 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-xl flex items-center justify-center transition-all shadow-sm" title="Audit Trail">
                                        <i class="fas fa-history text-xs"></i>
                                    </button>

                                    <button type="button" 
                                            @click="deleteUrl = '{{ route('admin.breakdowns.destroy', $log->id) }}'; assetCode = '{{ addslashes($log->infrastructure->code_name ?? '') }}'; showDeleteModal = true;"
                                            class="w-9 h-9 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-400 hover:text-red-600 rounded-xl flex items-center justify-center transition-all shadow-sm">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-8 py-20 text-center text-slate-400 italic">Tidak ada data log yang ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-8 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20">
                {{ $logs->links() }}
            </div>

        </div>

        <!-- MODALS (SAMA SEPERTI OPERATOR TAPI TEMA RED) -->
        <template x-teleport="body">
            <div x-show="showUpdateModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md" style="display: none;">
                <div @click.away="showUpdateModal = false" class="bg-white dark:bg-slate-900 rounded-[3rem] shadow-2xl max-w-2xl w-full overflow-hidden border border-slate-200 dark:border-slate-800 max-h-[90vh] flex flex-col">
                    <div class="bg-red-600 p-8 border-b border-red-700 flex items-center justify-between text-white shrink-0">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-xl shadow-inner"><i class="fas fa-shield-alt"></i></div>
                            <div>
                                <h2 class="text-xl font-black uppercase tracking-tight leading-none">Koreksi Data Laporan</h2>
                                <p class="text-[10px] font-black text-red-100 uppercase tracking-[0.2em] mt-2" x-text="selectedAsset ? selectedAsset.code : ''"></p>
                            </div>
                        </div>
                        <button @click="showUpdateModal = false" class="text-white/60 hover:text-white transition-colors"><i class="fas fa-times text-2xl"></i></button>
                    </div>
                    
                    <form :action="`/admin/breakdowns/${selectedLogId}`" method="POST" enctype="multipart/form-data" class="p-8 space-y-8 overflow-y-auto custom-scrollbar">
                        @csrf @method('PUT')
                        <div class="bg-red-50 dark:bg-red-900/20 p-6 rounded-3xl border border-red-100 dark:border-red-800">
                            <label class="block text-[10px] font-black text-red-700 dark:text-red-400 uppercase tracking-widest mb-4">Ubah Status (Override)</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                <template x-for="s in ['reported', 'order_part', 'on_progress', 'resolved']">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="repair_status" :value="s" x-model="currentStatus" class="sr-only">
                                        <div :class="currentStatus === s ? 'bg-red-600 text-white shadow-lg scale-105' : 'bg-white dark:bg-slate-800 text-slate-400 dark:text-slate-500'"
                                             class="px-3 py-4 rounded-xl text-[9px] font-black uppercase tracking-tighter text-center transition-all border border-transparent" 
                                             x-text="s.replace('_', ' ')"></div>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2">Tgl Troubleshoot</label>
                                <input type="date" name="troubleshoot_date" x-model="logDates.troubleshoot_date" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-xs font-bold text-slate-700 dark:text-white transition-all">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2">Tgl PR / PO (Sparepart)</label>
                                <input type="date" name="pr_po_date" x-model="logDates.pr_po_date" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-xs font-bold text-slate-700 dark:text-white transition-all">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2">Tgl Mulai Kerja</label>
                                <input type="date" name="start_work_date" x-model="logDates.start_work_date" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-xs font-bold text-slate-700 dark:text-white transition-all">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-2">Tgl Unit Ready</label>
                                <input type="date" name="resolved_date" x-model="logDates.resolved_date" class="w-full bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800 rounded-xl py-3 px-4 text-xs font-bold text-emerald-700 dark:text-emerald-400 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">PIC / Vendor Pekerja</label>
                            <input type="text" name="vendor_pic" x-model="logDates.vendor_pic" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-4 px-6 text-sm font-bold text-slate-900 dark:text-white transition-all">
                        </div>

                        <div class="pt-4 flex gap-4">
                            <button type="submit" class="w-full py-5 bg-red-600 hover:bg-red-700 text-white rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-red-900/20 transition-all">Simpan Koreksi Admin</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        <template x-teleport="body">
            <div x-show="showDeleteModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md" style="display: none;">
                <div @click.away="showDeleteModal = false" class="bg-white dark:bg-slate-900 rounded-[3rem] shadow-2xl max-w-sm w-full overflow-hidden border border-slate-200 dark:border-slate-800">
                    <div class="p-10 text-center">
                        <div class="w-20 h-20 bg-red-50 dark:bg-red-900/30 text-red-600 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                        <h2 class="text-2xl font-black text-[#003366] dark:text-white uppercase leading-tight mb-4">Hapus Log Laporan?</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium leading-relaxed mb-8">Data yang dihapus tidak dapat dikembalikan. Lanjutkan?</p>
                        <div class="flex gap-4">
                            <button @click="showDeleteModal = false" class="flex-1 py-4 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-2xl text-[10px] font-black uppercase tracking-widest">Batal</button>
                            <form :action="deleteUrl" method="POST" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Audit Trail Modal -->
        <template x-teleport="body">
            <div x-show="showHistoryModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md" style="display: none;">
                <div @click.away="showHistoryModal = false" class="bg-white dark:bg-slate-900 rounded-[3rem] shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 dark:border-slate-800 animate-in zoom-in-95 duration-300">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-2xl font-black text-[#003366] dark:text-white uppercase tracking-tight">Audit Trail</h2>
                            <button @click="showHistoryModal = false" class="text-slate-400 hover:text-slate-600 transition-colors"><i class="fas fa-times text-2xl"></i></button>
                        </div>
                        <div class="space-y-4 max-h-[400px] overflow-y-auto pr-4 custom-scrollbar">
                            <template x-for="log in selectedHistory" :key="log.id">
                                <div class="relative pl-8 pb-6 border-l-2 border-slate-100 dark:border-slate-800 last:border-0">
                                    <div class="absolute -left-[9px] top-0 w-4 h-4 bg-blue-500 rounded-full border-4 border-white dark:border-slate-900 shadow-sm"></div>
                                    <div class="bg-slate-50 dark:bg-slate-800/50 p-5 rounded-2xl border border-slate-100 dark:border-slate-700">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest" x-text="log.new_status"></span>
                                            <span class="text-[9px] font-bold text-slate-400" x-text="log.created_at"></span>
                                        </div>
                                        <p class="text-xs font-bold text-slate-700 dark:text-slate-200" x-text="log.note"></p>
                                        <div class="mt-3 flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-lg bg-blue-100 text-[#0055a4] flex items-center justify-center text-[10px] font-black" x-text="log.user.name.substring(0,1)"></div>
                                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-tighter" x-text="log.user.name"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </template>

    </div>

    <x-export-report :infrastructures="$allInfrastructures" :recentBreakdowns="$recentBreakdowns" />
    <x-export-filter-modal />

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
    </style>
</x-app-layout>
