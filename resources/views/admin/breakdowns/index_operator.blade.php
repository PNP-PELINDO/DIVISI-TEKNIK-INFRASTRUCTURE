<x-app-layout>
    <div class="max-w-[1600px] mx-auto w-full space-y-8 animate-fade-up"
         x-data="{
                 activeTab: '{{ request('history_page') ? 'history' : 'active' }}',
                 showReportModal: false,
                 showUpdateModal: false,
                 selectedAsset: null,
                 selectedLogId: null,
                 currentStatus: '',
                 logDates: {}
             }">

        <!-- HEADER SECTION -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-widest text-slate-400">
                        <li><a href="#" class="hover:text-[#0055a4] transition-colors">Operasional</a></li>
                        <li><i class="fas fa-chevron-right text-[8px] mx-1"></i></li>
                        <li class="text-[#0055a4] dark:text-blue-400">Log Kerusakan</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-black text-[#003366] dark:text-white uppercase tracking-tight flex items-center gap-3">
                    Log Kerusakan
                    <span class="bg-blue-100 dark:bg-blue-900/30 text-[#0055a4] dark:text-blue-400 px-3 py-1 rounded-full text-xs font-black">{{ auth()->user()->entity->name ?? 'Regional' }}</span>
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-2 font-medium">Kelola laporan kerusakan dan pantau progres perbaikan aset secara real-time.</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="flex p-1 bg-slate-100 dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-inner">
                    <button @click="activeTab = 'active'"
                            :class="activeTab === 'active' ? 'bg-white dark:bg-slate-700 text-[#003366] dark:text-blue-400 shadow-md scale-100' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 scale-95'"
                            class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i> Unit Rusak
                    </button>
                    <button @click="activeTab = 'ready'"
                            :class="activeTab === 'ready' ? 'bg-white dark:bg-slate-700 text-[#003366] dark:text-blue-400 shadow-md scale-100' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 scale-95'"
                            class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> Unit Siap
                    </button>
                    <button @click="activeTab = 'excel'"
                            :class="activeTab === 'excel' ? 'bg-white dark:bg-slate-700 text-[#003366] dark:text-blue-400 shadow-md scale-100' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 scale-95'"
                            class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                        <i class="fas fa-file-excel"></i> Live Excel
                    </button>
                    <button @click="activeTab = 'history'"
                            :class="activeTab === 'history' ? 'bg-white dark:bg-slate-700 text-[#003366] dark:text-blue-400 shadow-md scale-100' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 scale-95'"
                            class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                        <i class="fas fa-history"></i> Riwayat
                    </button>
                </div>
            </div>
        </div>

        <!-- STATS CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-5 transition-all hover:shadow-md group">
                <div class="w-14 h-14 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-2xl flex items-center justify-center text-2xl border border-red-100 dark:border-red-800 group-hover:scale-110 transition-transform">
                    <i class="fas fa-tools"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Sedang Rusak</p>
                    <p class="text-3xl font-black text-slate-900 dark:text-white">{{ $stats['breakdown'] ?? 0 }} <span class="text-xs text-slate-400 font-bold ml-1">UNIT</span></p>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-5 transition-all hover:shadow-md group">
                <div class="w-14 h-14 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center text-2xl border border-emerald-100 dark:border-emerald-800 group-hover:scale-110 transition-transform">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Tersedia (Ready)</p>
                    <p class="text-3xl font-black text-slate-900 dark:text-white">{{ $stats['available'] ?? 0 }} <span class="text-xs text-slate-400 font-bold ml-1">UNIT</span></p>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-5 transition-all hover:shadow-md group">
                <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/20 text-[#0055a4] dark:text-blue-400 rounded-2xl flex items-center justify-center text-2xl border border-blue-100 dark:border-blue-800 group-hover:scale-110 transition-transform">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Kesiapan Alat</p>
                    <p class="text-3xl font-black text-slate-900 dark:text-white">{{ $stats['readiness_rate'] ?? 0 }}<span class="text-xs text-slate-400 font-bold ml-1">%</span></p>
                </div>
            </div>
        </div>


        <!-- SEARCH & FILTER -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm">
            <form action="{{ route('admin.breakdowns.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="relative lg:col-span-2">
                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari berdasarkan kode alat atau detail kerusakan..."
                           class="w-full pl-12 pr-6 py-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl text-sm font-bold text-slate-700 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-[#0055a4] transition-all">
                </div>

                <button type="submit" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-widest text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-filter"></i> Filter
                </button>

                <div class="relative group">
                    <button type="button" class="w-full h-full bg-[#003366] dark:bg-blue-600 hover:bg-[#002244] dark:hover:bg-blue-700 text-white px-8 py-4 rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-900/20 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-file-export"></i> Export
                    </button>
                    <div class="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-800 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all transform origin-top-right scale-95 group-hover:scale-100 flex flex-col overflow-hidden z-50">
                        <button type="button" onclick="openExportModal('pdf')" class="flex items-center gap-3 px-6 py-4 text-left hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest transition-colors border-b border-slate-50 dark:border-slate-800">
                            <i class="fas fa-file-pdf text-red-500 text-base"></i> PDF Document
                        </button>
                        <button type="button" onclick="openExportModal('excel')" class="flex items-center gap-3 px-6 py-4 text-left hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest transition-colors">
                            <i class="fas fa-file-excel text-emerald-500 text-base"></i> Excel Sheet
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="space-y-6">

            <!-- TAB: UNIT RUSAK (ACTIVE BREAKDOWNS) -->
            <div x-show="activeTab === 'active'" x-transition class="space-y-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @forelse($infrastructures->where('status', 'breakdown') as $item)
                        @php $activeLog = $activeBreakdowns[$item->id] ?? null; @endphp
                        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-red-100 dark:border-red-900/30 overflow-hidden shadow-sm hover:shadow-xl transition-all border-l-8 border-l-red-500 relative">
                            <div class="p-8">
                                <div class="flex justify-between items-start mb-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-2xl flex items-center justify-center text-2xl border border-red-100 dark:border-red-800 shadow-inner shrink-0">
                                            <i class="fas fa-tools"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-black text-[#003366] dark:text-white uppercase leading-tight">{{ $item->code_name }}</h3>
                                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">{{ $item->type }} • {{ $item->category }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="bg-red-600 text-white px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-sm">Down</span>
                                        <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 mt-2 uppercase">Lapor: {{ $activeLog ? ($activeLog->breakdown_date ? \Carbon\Carbon::parse($activeLog->breakdown_date)->format('d M Y') : $activeLog->created_at->format('d M Y')) : '-' }}</p>
                                    </div>
                                </div>

                                <div class="bg-slate-50 dark:bg-slate-800/50 p-5 rounded-3xl border border-slate-100 dark:border-slate-700 mb-6">
                                    <p class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Detail Kendala</p>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200 leading-relaxed italic">"{{ $activeLog ? ($activeLog->issue_detail ?? 'Belum ada detail.') : 'Data log tidak ditemukan.' }}"</p>
                                </div>

                                <!-- STEP PROGRESS VISUAL -->
                                <div class="relative px-2 mb-8">
                                    <div class="absolute top-4 left-0 w-full h-1 bg-slate-100 dark:bg-slate-800 rounded-full"></div>
                                    @php
                                        $statuses = [
                                            'reported' => ['label' => 'Reported', 'icon' => 'fa-bullhorn'],
                                            'order_part' => ['label' => 'Order Part', 'icon' => 'fa-shopping-cart'],
                                            'on_progress' => ['label' => 'Work', 'icon' => 'fa-wrench'],
                                            'resolved' => ['label' => 'Ready', 'icon' => 'fa-check-circle'],
                                        ];
                                        $currentIdx = array_search($activeLog->repair_status ?? 'reported', array_keys($statuses));
                                    @endphp
                                    <div class="relative flex justify-between">
                                        @foreach($statuses as $key => $status)
                                            @php $isPast = array_search($key, array_keys($statuses)) <= $currentIdx; @endphp
                                            <div class="flex flex-col items-center">
                                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs transition-all duration-500 z-10 border-4 {{ $isPast ? 'bg-[#0055a4] text-white border-blue-100 dark:border-blue-900' : 'bg-white dark:bg-slate-800 text-slate-300 border-slate-100 dark:border-slate-700' }}">
                                                    <i class="fas {{ $status['icon'] }}"></i>
                                                </div>
                                                <span class="text-[8px] font-black uppercase mt-2 tracking-tighter {{ $isPast ? 'text-[#0055a4] dark:text-blue-400' : 'text-slate-300' }}">{{ $status['label'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- Dynamic Progress Bar -->
                                    <div class="absolute top-4 left-0 h-1 bg-[#0055a4] rounded-full transition-all duration-700" style="width: {{ ($currentIdx / (count($statuses)-1)) * 100 }}%"></div>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-[10px] font-black text-slate-500">
                                            {{ $activeLog ? substr($activeLog->vendor_pic ?? '?', 0, 1) : '?' }}
                                        </div>
                                        <p class="text-[10px] font-bold text-slate-500 uppercase">{{ $activeLog ? ($activeLog->vendor_pic ?? 'Belum ada PIC') : '-' }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        @if($activeLog && $activeLog->document_proof)
                                            <a href="{{ asset('storage/'.$activeLog->document_proof) }}" target="_blank" class="w-10 h-10 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl flex items-center justify-center text-slate-500 hover:text-emerald-500 transition-colors shadow-sm">
                                                <i class="fas fa-file-download"></i>
                                            </a>
                                        @endif
                                        <button @click="
                                                selectedAsset = {id: '{{ $item->id }}', code: '{{ $item->code_name }}'};
                                                selectedLogId = '{{ $activeLog->id ?? '' }}';
                                                currentStatus = '{{ $activeLog->repair_status ?? 'reported' }}';
                                                logDates = {
                                                    breakdown_date: '{{ $activeLog->breakdown_date ? \Carbon\Carbon::parse($activeLog->breakdown_date)->format('Y-m-d') : $activeLog->created_at->format('Y-m-d') }}',
                                                    troubleshoot_date: '{{ $activeLog->troubleshoot_date ? \Carbon\Carbon::parse($activeLog->troubleshoot_date)->format('Y-m-d') : '' }}',
                                                    ba_date: '{{ $activeLog->ba_date ? \Carbon\Carbon::parse($activeLog->ba_date)->format('Y-m-d') : '' }}',
                                                    work_order_date: '{{ $activeLog->work_order_date ? \Carbon\Carbon::parse($activeLog->work_order_date)->format('Y-m-d') : '' }}',
                                                    pr_po_date: '{{ $activeLog->pr_po_date ? \Carbon\Carbon::parse($activeLog->pr_po_date)->format('Y-m-d') : '' }}',
                                                    sparepart_date: '{{ $activeLog->sparepart_date ? \Carbon\Carbon::parse($activeLog->sparepart_date)->format('Y-m-d') : '' }}',
                                                    start_work_date: '{{ $activeLog->start_work_date ? \Carbon\Carbon::parse($activeLog->start_work_date)->format('Y-m-d') : '' }}',
                                                    com_test_date: '{{ $activeLog->com_test_date ? \Carbon\Carbon::parse($activeLog->com_test_date)->format('Y-m-d') : '' }}',
                                                    resolved_date: '{{ $activeLog->resolved_date ? \Carbon\Carbon::parse($activeLog->resolved_date)->format('Y-m-d') : '' }}',
                                                    vendor_pic: '{{ addslashes($activeLog->vendor_pic ?? '') }}'
                                                };
                                                showUpdateModal = true;
                                            "
                                            class="bg-[#003366] dark:bg-blue-600 hover:bg-[#001e3c] dark:hover:bg-blue-700 text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-900/10 transition-all flex items-center gap-2">
                                            Update Progres
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="lg:col-span-2 bg-emerald-50/50 dark:bg-emerald-900/10 border-2 border-dashed border-emerald-200 dark:border-emerald-800 p-16 rounded-[3rem] text-center">
                            <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center text-3xl mx-auto mb-6">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <h3 class="text-xl font-black text-emerald-800 dark:text-emerald-400 uppercase mb-2">Semua Unit Normal</h3>
                            <p class="text-emerald-600 dark:text-emerald-500 text-sm font-medium">Tidak ada laporan kerusakan aktif untuk wilayah ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- TAB: UNIT SIAP (READY ASSETS) -->
            <div x-show="activeTab === 'ready'" x-transition class="space-y-4">
                <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-200 dark:border-slate-800">
                                <th class="px-8 py-6 w-16 text-center">No</th>
                                <th class="px-8 py-6">Identitas Alat</th>
                                <th class="px-8 py-6">Tipe & Kategori</th>
                                <th class="px-8 py-6 text-center">Status</th>
                                <th class="px-8 py-6 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($infrastructures->where('status', 'available') as $index => $item)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                                    <td class="px-8 py-6 text-center text-slate-400 font-bold text-xs">{{ $index + 1 }}</td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span class="font-black text-[#003366] dark:text-blue-400 text-sm uppercase group-hover:text-blue-600 transition-colors">{{ $item->code_name }}</span>
                                            <span class="text-[9px] text-slate-400 dark:text-slate-500 font-black uppercase tracking-widest mt-1">{{ $item->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest">{{ $item->type }}</span>
                                        <span class="text-[9px] font-bold text-slate-400 ml-2">/ {{ $item->category }}</span>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <span class="bg-emerald-500 text-white px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm">Ready</span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <button @click="selectedAsset = {id: '{{ $item->id }}', code: '{{ addslashes($item->code_name) }}'}; showReportModal = true;"
                                                class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                            Lapor Rusak
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center text-slate-400 italic">Data tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB: LIVE EXCEL (GOOGLE SHEETS STYLE) -->
            <div x-show="activeTab === 'excel'" x-transition class="space-y-4">
                <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col">
                    <div class="bg-amber-50 dark:bg-amber-900/10 p-4 border-b border-amber-100 dark:border-amber-800/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-table text-amber-600 dark:text-amber-400"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest text-amber-800 dark:text-amber-300">Laporan Kesiapan Alat Pelabuhan (Format Excel)</span>
                        </div>
                        <span class="text-[8px] font-black text-amber-600/50 uppercase italic">Sinkronisasi Real-time</span>
                    </div>

                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-[10px] border-collapse min-w-[2000px]">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-black uppercase tracking-widest border-b border-slate-200 dark:border-slate-800">
                                    <th class="px-4 py-4 border-r border-slate-200 dark:border-slate-800 w-12 text-center">NO</th>
                                    <th class="px-6 py-4 border-r border-slate-200 dark:border-slate-800">NAMA ALAT</th>
                                    <th class="px-6 py-4 border-r border-slate-200 dark:border-slate-800">JENIS ALAT</th>
                                    <th class="px-6 py-4 border-r border-slate-200 dark:border-slate-800">ENTITAS</th>
                                    <th class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">STATUS</th>
                                    <th class="px-6 py-4 border-r border-slate-200 dark:border-slate-800">DETAIL KERUSAKAN</th>
                                    <th class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">TGL BREAK DOWN</th>
                                    <th class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">STATUS KESIAPAN</th>
                                    <th class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">TROUBLE SHOOT</th>
                                    <th class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">BERITA ACARA</th>
                                    <th class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">WORK ORDER</th>
                                    <th class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">PR / PO</th>
                                    <th class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">SPAREPART ON SITE</th>
                                    <th class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">MULAI KERJA</th>
                                    <th class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">COM TEST</th>
                                    <th class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">SELESAI KERJA</th>
                                    <th class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">PIC</th>
                                    <th class="px-6 py-4 text-center">OPSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach($infrastructures as $index => $item)
                                    @php $activeLog = $activeBreakdowns[$item->id] ?? null; @endphp
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors font-bold text-slate-700 dark:text-slate-300">
                                        <td class="px-4 py-4 border-r border-slate-200 dark:border-slate-800 text-center text-slate-400">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 uppercase text-blue-600 dark:text-blue-400 font-black">{{ $item->code_name }}</td>
                                        <td class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 uppercase">{{ $item->type }}</td>
                                        <td class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 uppercase italic">{{ $item->entity->name ?? '-' }}</td>
                                        <td class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">
                                            @if($item->status === 'available')
                                                <span class="bg-emerald-500 text-white px-3 py-1 rounded-full text-[8px] font-black uppercase">Ready</span>
                                            @else
                                                <span class="bg-red-600 text-white px-3 py-1 rounded-full text-[8px] font-black uppercase">Breakdown</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 max-w-xs truncate italic">
                                            {{ $activeLog ? ($activeLog->issue_detail ?? '-') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">
                                            {{ $activeLog ? ($activeLog->breakdown_date ? \Carbon\Carbon::parse($activeLog->breakdown_date)->format('d/m/Y') : $activeLog->created_at->format('d/m/Y')) : '-' }}
                                        </td>
                                        <td class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center uppercase">
                                            @if($activeLog)
                                                <span class="px-2 py-1 rounded border {{
                                                    $activeLog->repair_status === 'order_part' ? 'bg-amber-50 text-amber-600 border-amber-200' :
                                                    ($activeLog->repair_status === 'on_progress' ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-slate-100 text-slate-600')
                                                }}">
                                                    {{ str_replace('_', ' ', $activeLog->repair_status) }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">{{ $activeLog && $activeLog->troubleshoot_date ? \Carbon\Carbon::parse($activeLog->troubleshoot_date)->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">{{ $activeLog && $activeLog->ba_date ? \Carbon\Carbon::parse($activeLog->ba_date)->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">{{ $activeLog && $activeLog->work_order_date ? \Carbon\Carbon::parse($activeLog->work_order_date)->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">{{ $activeLog && $activeLog->pr_po_date ? \Carbon\Carbon::parse($activeLog->pr_po_date)->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">{{ $activeLog && $activeLog->sparepart_date ? \Carbon\Carbon::parse($activeLog->sparepart_date)->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">{{ $activeLog && $activeLog->start_work_date ? \Carbon\Carbon::parse($activeLog->start_work_date)->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">{{ $activeLog && $activeLog->com_test_date ? \Carbon\Carbon::parse($activeLog->com_test_date)->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">{{ $activeLog && $activeLog->resolved_date ? \Carbon\Carbon::parse($activeLog->resolved_date)->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4 border-r border-slate-200 dark:border-slate-800 text-center">
                                            @if($activeLog)
                                                <span class="bg-blue-900 text-white px-2 py-0.5 rounded text-[8px] font-black">{{ $activeLog->vendor_pic ?? 'N/A' }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($activeLog)
                                                <button @click="
                                                    selectedAsset = {id: '{{ $item->id }}', code: '{{ $item->code_name }}'};
                                                    selectedLogId = '{{ $activeLog->id }}';
                                                    currentStatus = '{{ $activeLog->repair_status }}';
                                                    logDates = {
                                                        breakdown_date: '{{ $activeLog->breakdown_date ? \Carbon\Carbon::parse($activeLog->breakdown_date)->format('Y-m-d') : $activeLog->created_at->format('Y-m-d') }}',
                                                        troubleshoot_date: '{{ $activeLog->troubleshoot_date ? \Carbon\Carbon::parse($activeLog->troubleshoot_date)->format('Y-m-d') : '' }}',
                                                        ba_date: '{{ $activeLog->ba_date ? \Carbon\Carbon::parse($activeLog->ba_date)->format('Y-m-d') : '' }}',
                                                        work_order_date: '{{ $activeLog->work_order_date ? \Carbon\Carbon::parse($activeLog->work_order_date)->format('Y-m-d') : '' }}',
                                                        pr_po_date: '{{ $activeLog->pr_po_date ? \Carbon\Carbon::parse($activeLog->pr_po_date)->format('Y-m-d') : '' }}',
                                                        sparepart_date: '{{ $activeLog->sparepart_date ? \Carbon\Carbon::parse($activeLog->sparepart_date)->format('Y-m-d') : '' }}',
                                                        start_work_date: '{{ $activeLog->start_work_date ? \Carbon\Carbon::parse($activeLog->start_work_date)->format('Y-m-d') : '' }}',
                                                        com_test_date: '{{ $activeLog->com_test_date ? \Carbon\Carbon::parse($activeLog->com_test_date)->format('Y-m-d') : '' }}',
                                                        resolved_date: '{{ $activeLog->resolved_date ? \Carbon\Carbon::parse($activeLog->resolved_date)->format('Y-m-d') : '' }}',
                                                        vendor_pic: '{{ $activeLog->vendor_pic ?? '' }}'
                                                    };
                                                    showUpdateModal = true;
                                                " class="text-blue-600 hover:text-blue-800 transition-colors">
                                                    <i class="fas fa-edit"></i> Update
                                                </button>
                                            @else
                                                <button @click="selectedAsset = {id: '{{ $item->id }}', code: '{{ addslashes($item->code_name) }}'}; showReportModal = true;" class="text-red-600 hover:text-red-800 transition-colors">
                                                    <i class="fas fa-plus-circle"></i> Lapor
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB: RIWAYAT (HISTORY) -->
            <div x-show="activeTab === 'history'" x-transition class="space-y-4">
                <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-200 dark:border-slate-800">
                                <th class="px-8 py-6 w-16 text-center">No</th>
                                <th class="px-8 py-6">Aset</th>
                                <th class="px-8 py-6">Detail Kendala</th>
                                <th class="px-8 py-6 text-center">Periode Down</th>
                                <th class="px-8 py-6 text-center">Status</th>
                                <th class="px-8 py-6 text-right">Dokumen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($historyLogs as $index => $log)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-8 py-6 text-center text-slate-400 font-bold text-xs">{{ $index + 1 }}</td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span class="font-black text-[#003366] dark:text-blue-400 text-sm uppercase">{{ $log->infrastructure->code_name ?? 'UNIT TERHAPUS' }}</span>
                                            <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-1">{{ $log->infrastructure->type ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <p class="text-[11px] font-bold text-slate-600 dark:text-slate-300 max-w-[300px] truncate italic" title="{{ $log->issue_detail }}">"{{ $log->issue_detail }}"</p>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="text-[10px] font-black text-[#0055a4] dark:text-blue-400">{{ $log->breakdown_date ? \Carbon\Carbon::parse($log->breakdown_date)->format('d/m/Y') : $log->created_at->format('d/m/Y') }}</span>
                                            @if($log->resolved_date)
                                                <i class="fas fa-arrow-down text-[8px] my-0.5 text-slate-300"></i>
                                                <span class="text-[10px] font-black text-emerald-600">{{ \Carbon\Carbon::parse($log->resolved_date)->format('d/m/Y') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <span class="bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest">Resolved</span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        @if($log->document_proof)
                                            <a href="{{ asset('storage/'.$log->document_proof) }}" target="_blank" class="inline-flex items-center gap-2 text-emerald-600 font-black text-[10px] uppercase hover:underline">
                                                <i class="fas fa-file-alt"></i> Lihat Bukti
                                            </a>
                                        @else
                                            <span class="text-slate-300 text-[10px] font-bold">Tidak ada file</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-8 py-10 text-center text-slate-400 italic font-medium">Belum ada riwayat perbaikan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $historyLogs->links() }}
                </div>
            </div>

        </div>

        <!-- MODAL: LAPOR KERUSAKAN -->
        <template x-teleport="body">
            <div x-show="showReportModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md" style="display: none;">

                <div @click.away="showReportModal = false" class="bg-white dark:bg-slate-900 rounded-[3rem] shadow-2xl max-w-md w-full overflow-hidden border border-slate-200 dark:border-slate-800">
                    <div class="bg-red-600 p-8 border-b border-red-700 flex items-center justify-between text-white">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-xl shadow-inner"><i class="fas fa-bolt"></i></div>
                            <div>
                                <h2 class="text-xl font-black uppercase tracking-tight leading-none">Lapor Unit Down</h2>
                                <p class="text-[10px] font-black text-red-100 uppercase tracking-[0.2em] mt-2" x-text="selectedAsset ? selectedAsset.code : ''"></p>
                            </div>
                        </div>
                        <button @click="showReportModal = false" class="text-white/60 hover:text-white transition-colors"><i class="fas fa-times text-2xl"></i></button>
                    </div>

                    <form action="{{ route('admin.breakdowns.store') }}" method="POST" class="p-8 space-y-6">
                        @csrf
                        <input type="hidden" name="infrastructure_id" :value="selectedAsset ? selectedAsset.id : ''">
                        <input type="hidden" name="repair_status" value="reported">

                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">Tgl Breakdown</label>
                            <input type="date" name="breakdown_date" value="{{ old('breakdown_date', now()->format('Y-m-d')) }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-[1.5rem] p-5 text-sm font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 transition-all" required>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">Detail Kendala / Kerusakan</label>
                            <textarea name="issue_detail" rows="4" placeholder="Jelaskan secara singkat apa yang terjadi pada unit ini..."
                                      class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-[1.5rem] p-5 text-sm font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 transition-all" required></textarea>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">PIC / Vendor Penanggung Jawab</label>
                            <div class="relative">
                                <i class="fas fa-user-gear absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="vendor_pic" placeholder="Nama teknisi atau vendor..."
                                       class="w-full pl-12 pr-6 py-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl text-sm font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 transition-all" required>
                            </div>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="showReportModal = false" class="flex-1 py-4 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">Batal</button>
                            <button type="submit" class="flex-2 px-10 py-4 bg-red-600 hover:bg-red-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-900/20 transition-all">Kirim Laporan</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        <!-- MODAL: UPDATE PROGRES -->
        <template x-teleport="body">
            <div x-show="showUpdateModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md" style="display: none;">

                <div @click.away="showUpdateModal = false" class="bg-white dark:bg-slate-900 rounded-[3rem] shadow-2xl max-w-2xl w-full overflow-hidden border border-slate-200 dark:border-slate-800 max-h-[90vh] flex flex-col">
                    <div class="bg-[#003366] dark:bg-slate-950 p-8 border-b border-white/5 flex items-center justify-between text-white shrink-0">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-xl shadow-inner"><i class="fas fa-wrench"></i></div>
                            <div>
                                <h2 class="text-xl font-black uppercase tracking-tight leading-none">Update Status Unit</h2>
                                <p class="text-[10px] font-black text-blue-300 uppercase tracking-[0.2em] mt-2" x-text="selectedAsset ? selectedAsset.code : ''"></p>
                            </div>
                        </div>
                        <button @click="showUpdateModal = false" class="text-white/60 hover:text-white transition-colors"><i class="fas fa-times text-2xl"></i></button>
                    </div>

                    <form :action="`/admin/breakdowns/${selectedLogId}`" method="POST" enctype="multipart/form-data" class="p-8 space-y-8 overflow-y-auto custom-scrollbar">
                        @csrf @method('PUT')

                        <!-- Status Picker -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-3xl border border-blue-100 dark:border-blue-800">
                            <label class="block text-[10px] font-black text-[#003366] dark:text-blue-400 uppercase tracking-widest mb-4">Ubah Tahap Pekerjaan</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                <template x-for="s in ['reported', 'order_part', 'on_progress', 'resolved']">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="repair_status" :value="s" x-model="currentStatus" class="sr-only">
                                        <div :class="currentStatus === s ? 'bg-[#003366] text-white shadow-lg scale-105' : 'bg-white dark:bg-slate-800 text-slate-400 dark:text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700'"
                                             class="px-3 py-4 rounded-xl text-[9px] font-black uppercase tracking-tighter text-center transition-all border border-transparent"
                                             x-text="s.replace('_', ' ')"></div>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <!-- Date Section (Dynamic based on logic) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2">Tgl Breakdown</label>
                                <input type="date" name="breakdown_date" x-model="logDates.breakdown_date" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-xs font-bold text-slate-700 dark:text-white transition-all">
                            </div>
                            <div x-show="['order_part', 'on_progress', 'resolved'].includes(currentStatus)">
                                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2">Tgl Troubleshoot</label>
                                <input type="date" name="troubleshoot_date" x-model="logDates.troubleshoot_date" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-xs font-bold text-slate-700 dark:text-white transition-all">
                            </div>
                            <div x-show="['order_part', 'on_progress', 'resolved'].includes(currentStatus)">
                                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2">Tgl Berita Acara (BA)</label>
                                <input type="date" name="ba_date" x-model="logDates.ba_date" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-xs font-bold text-slate-700 dark:text-white transition-all">
                            </div>
                            <div x-show="['order_part', 'on_progress', 'resolved'].includes(currentStatus)">
                                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2">Tgl Work Order (WO)</label>
                                <input type="date" name="work_order_date" x-model="logDates.work_order_date" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-xs font-bold text-slate-700 dark:text-white transition-all">
                            </div>
                            <div x-show="['order_part', 'on_progress', 'resolved'].includes(currentStatus)">
                                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2">Tgl PR / PO (Sparepart)</label>
                                <input type="date" name="pr_po_date" x-model="logDates.pr_po_date" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-xs font-bold text-slate-700 dark:text-white transition-all">
                            </div>
                            <div x-show="['order_part', 'on_progress', 'resolved'].includes(currentStatus)">
                                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2">Tgl Sparepart On Site</label>
                                <input type="date" name="sparepart_date" x-model="logDates.sparepart_date" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-xs font-bold text-slate-700 dark:text-white transition-all">
                            </div>
                            <div x-show="['on_progress', 'resolved'].includes(currentStatus)">
                                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2">Tgl Mulai Kerja</label>
                                <input type="date" name="start_work_date" x-model="logDates.start_work_date" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-xs font-bold text-slate-700 dark:text-white transition-all">
                            </div>
                            <div x-show="['on_progress', 'resolved'].includes(currentStatus)">
                                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2">Tgl Com Test</label>
                                <input type="date" name="com_test_date" x-model="logDates.com_test_date" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-xs font-bold text-slate-700 dark:text-white transition-all">
                            </div>
                            <div x-show="currentStatus === 'resolved'">
                                <label class="block text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-2">Tgl Unit Ready</label>
                                <input type="date" name="resolved_date" x-model="logDates.resolved_date" class="w-full bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800 rounded-xl py-3 px-4 text-xs font-bold text-emerald-700 dark:text-emerald-400 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Update PIC / Vendor Pekerja</label>
                            <input type="text" name="vendor_pic" x-model="logDates.vendor_pic" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-4 px-6 text-sm font-bold text-slate-900 dark:text-white transition-all">
                        </div>

                        <div class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4">Lampiran Bukti (Dokumen/Foto)</label>
                            <input type="file" name="document_proof" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-6 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-[#003366] file:text-white hover:file:bg-[#001e3c] transition-all cursor-pointer">
                        </div>

                        <div class="pt-4 flex gap-4">
                            <button type="submit" class="w-full py-5 bg-[#003366] dark:bg-blue-600 hover:bg-[#001e3c] dark:hover:bg-blue-700 text-white rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-blue-900/20 transition-all">Simpan Perubahan Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

    </div>

    <!-- Export Logic (Hidden Component) -->
    <x-export-report :infrastructures="$allInfrastructures ?? collect()" :recentBreakdowns="$recentBreakdowns ?? collect()" />
    <x-export-filter-modal />

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
    </style>
</x-app-layout>
