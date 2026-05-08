<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            transition: background-color 0.3s ease;
        }

        .dark body {
            background-color: #0f172a;
            color: #f1f5f9;
        }

        [x-cloak] { display: none !important; }

        .animate-fade-up {
            animation: fadeUp 0.6s ease-out forwards;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Enterprise Card System */
        .ent-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.5rem;
            padding: 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .dark .ent-card {
            background-color: #1e293b;
            border-color: #334155;
        }

        .ent-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            border-color: #0064a7;
        }

        .dark .ent-card:hover {
            border-color: #60a5fa;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
        }

        .stat-badge {
            font-[900] text-[10px] uppercase tracking-widest px-3 py-1.5 rounded-lg border shadow-sm;
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .dark ::-webkit-scrollbar-thumb { background: #334155; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>


    @php
        // LOGIKA & RUMUS BARU UNTUK ENTERPRISE DASHBOARD
        $equipment = isset($allInfrastructures) ? $allInfrastructures->where('category', 'equipment') : collect();
        $facility = isset($allInfrastructures) ? $allInfrastructures->where('category', 'facility') : collect();
        $utility = isset($allInfrastructures) ? $allInfrastructures->where('category', 'utility') : collect();

        // 1. Rumus Availability Rate (SLA KPI)
        $totalAssets = $stats['total'] ?? 0;
        $availableAssets = $stats['available'] ?? 0;
        $availabilityRate = $totalAssets > 0 ? round(($availableAssets / $totalAssets) * 100, 1) : 0;

        // Penentuan Warna SLA
        $slaColor = $availabilityRate >= 90 ? 'bg-emerald-500' : ($availabilityRate >= 75 ? 'bg-amber-500' : 'bg-red-500');

        // 2. Data Top 5 Entitas dengan Kerusakan Terbanyak (Untuk Horizontal Bar Chart)
        $entityBreakdowns = isset($allInfrastructures) ? $allInfrastructures->where('status', 'breakdown')->groupBy(fn($i) => $i->entity->name ?? 'Unknown') : collect();
        $topBrokenEntities = $entityBreakdowns->map->count()->sortByDesc(fn($c) => $c)->take(5);
    @endphp

    <div id="main-ui" class="max-w-[1600px] mx-auto w-full space-y-8 animate-fade-up"
        x-data="{ 
            showDetailModal: false, 
            infraData: null, 
            activeLog: null,
            openDetailModal(infra, log = null) { 
                this.infraData = infra; 
                this.activeLog = log;
                this.showDetailModal = true; 
            } 
        }">

        <!-- HEADER & FILTER SECTION -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm space-y-10 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-pelindo-blue to-pelindo-cyan"></div>

            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-8">
                <div class="flex items-center gap-6">
                    <div class="flex items-center justify-center gap-4 bg-slate-50 dark:bg-slate-800 p-4 rounded-[1.5rem] border border-slate-100 dark:border-slate-700 shadow-inner">
                        <img src="{{ asset('danantara.png') }}" alt="Danantara" class="h-8 md:h-10 object-contain transition-all duration-300">
                        <div class="w-px h-8 bg-slate-300 dark:bg-slate-600"></div>
                        <img src="{{ asset('pelindo.png') }}" alt="Pelindo" class="h-8 md:h-10 object-contain transition-all duration-300">
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-pelindo-blue dark:text-white uppercase tracking-tight">Command Center</h1>
                        <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 flex items-center gap-2">
                            <span class="w-2 h-2 bg-pelindo-cyan rounded-full animate-pulse"></span> 
                            Monitoring Dashboard Operasional Regional 2
                        </p>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
                    <div x-data="{ open: false }" class="relative flex-1 sm:flex-none">
                        <button @click="open = !open" @click.away="open = false" 
                                class="w-full justify-center bg-white dark:bg-slate-800 text-pelindo-blue dark:text-pelindo-cyan px-5 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all flex items-center gap-3 border border-slate-200 dark:border-slate-700 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700">
                            <i class="fas fa-file-export text-xs"></i> <span class="hidden xs:inline">Export Laporan</span> <i class="fas fa-chevron-down text-[8px] opacity-60 ml-1"></i>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute right-0 mt-3 w-56 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-2xl z-[100] overflow-hidden">
                            <button onclick="openExportModal('pdf')" class="w-full text-left px-6 py-4 text-[10px] font-black uppercase text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors flex items-center gap-4">
                                <i class="fas fa-file-pdf text-red-500 text-sm"></i> Format PDF (.pdf)
                            </button>
                            <button onclick="openExportModal('excel')" class="w-full text-left px-6 py-4 text-[10px] font-black uppercase text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors flex items-center gap-4 border-t border-slate-100 dark:border-slate-700">
                                <i class="fas fa-file-excel text-emerald-500 text-sm"></i> Format Excel (.xlsx)
                            </button>
                        </div>
                    </div>

                    <a href="{{ url('/') }}" target="_blank" class="flex-1 sm:flex-none justify-center bg-slate-50 dark:bg-slate-800/50 text-pelindo-blue dark:text-pelindo-cyan px-5 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all flex items-center gap-3 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700">
                        <i class="fas fa-external-link-alt text-xs"></i> <span class="hidden xs:inline">Portal Publik</span>
                    </a>
                    
                    <a href="{{ route('admin.infrastructures.create') }}" 
                       class="flex-1 sm:flex-none justify-center bg-pelindo-blue hover:bg-pelindo-navy dark:bg-pelindo-blue dark:hover:bg-pelindo-navy text-white px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all flex items-center gap-3 shadow-lg shadow-blue-900/20 active:scale-95">
                        <i class="fas fa-plus text-xs"></i> <span class="hidden xs:inline">Registrasi Aset</span>
                    </a>
                </div>
            </div>

            <!-- Dashboard Filters -->
            <form action="{{ route('admin.dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 pt-8 border-t border-slate-100 dark:border-slate-800/50">
                @if(auth()->user()->role === 'superadmin')
                <div class="relative lg:col-span-1">
                    <i class="fas fa-map-marker-alt absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs"></i>
                    <select name="entity_id" onchange="this.form.submit()" class="w-full pl-12 pr-6 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs font-black text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-pelindo-blue uppercase transition-all appearance-none cursor-pointer">
                        <option value="">Semua Area (Pusat)</option>
                        @foreach($allEntities ?? [] as $entity)
                            <option value="{{ $entity->id }}" {{ ($filterEntity ?? '') == $entity->id ? 'selected' : '' }}>{{ $entity->name }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                </div>
                @endif
                
                <div class="relative {{ auth()->user()->role === 'superadmin' ? 'lg:col-span-1' : 'lg:col-span-2' }}">
                    <i class="fas fa-layer-group absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs"></i>
                    <select name="category" onchange="this.form.submit()" class="w-full pl-12 pr-6 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs font-black text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-pelindo-blue uppercase transition-all appearance-none cursor-pointer">
                        <option value="">Semua Kategori Aset</option>
                        <option value="equipment" {{ ($filterCategory ?? '') == 'equipment' ? 'selected' : '' }}>Peralatan (Equipment)</option>
                        <option value="facility" {{ ($filterCategory ?? '') == 'facility' ? 'selected' : '' }}>Fasilitas (Facility)</option>
                        <option value="utility" {{ ($filterCategory ?? '') == 'utility' ? 'selected' : '' }}>Utilitas (Utility)</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                </div>

                <div class="flex gap-2 {{ auth()->user()->role === 'superadmin' ? 'lg:col-span-2' : 'lg:col-span-2' }}">
                    <button type="submit" class="flex-1 bg-pelindo-blue hover:bg-pelindo-navy dark:bg-slate-800 dark:hover:bg-slate-700 text-white px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-3 border border-transparent dark:border-slate-700 shadow-lg active:scale-95">
                        <i class="fas fa-filter"></i> Terapkan Filter
                    </button>
                    @if(($filterEntity ?? false) || ($filterCategory ?? false))
                        <a href="{{ route('admin.dashboard') }}" class="px-6 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-2xl flex items-center justify-center transition-all border border-transparent dark:border-slate-700">
                            <i class="fas fa-undo text-xs"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>


        <!-- KPI STATS -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 " >
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl flex flex-col justify-between shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 dark:bg-blue-900/20 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-black text-pelindo-blue dark:text-pelindo-cyan uppercase tracking-widest mb-1">Total Aset & Kesiapan</p>
                        <div class="flex items-end gap-2">
                            <p class="text-3xl font-black text-slate-800 dark:text-slate-100">{{ $stats['total'] ?? 0 }}</p>
                            <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-1">Unit</p>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-500 text-xl shadow-inner border border-blue-100 dark:border-blue-800">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
                <div class="mt-4 relative z-10">
                    <div class="flex items-center justify-between text-xs font-bold mb-1">
                        <span class="text-slate-500 dark:text-slate-400">Readiness Rate</span>
                        <span class="text-blue-600 dark:text-blue-400">{{ $stats['readiness_rate'] }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 mt-1.5">
                        <div class="bg-pelindo-blue h-1.5 rounded-full" style="width: {{ $stats['readiness_rate'] }}%"></div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Avg. Repair Time (MTTR)</span>
                        <span class="text-[10px] font-black text-slate-700 dark:text-slate-300">{{ $stats['mttr'] ?? 0 }} <span class="text-[8px] text-slate-400">Hari</span></span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl flex flex-col justify-between shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 dark:bg-red-900/20 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-black text-pelindo-cyan dark:text-pelindo-cyan uppercase tracking-widest mb-1">Laporan Masuk (Baru)</p>
                        <div class="flex items-end gap-2">
                            <p class="text-3xl font-black text-slate-800 dark:text-slate-100">{{ $stats['reported'] ?? 0 }}</p>
                            <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-1">Tiket</p>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-sky-50 dark:bg-sky-900/30 rounded-full flex items-center justify-center text-pelindo-cyan text-xl shadow-inner border border-sky-100 dark:border-sky-800">
                        <i class="fas fa-bell"></i>
                    </div>
                </div>
                <div class="mt-4 relative z-10 flex items-center gap-2">
                    <span class="text-[10px] font-bold text-pelindo-blue dark:text-pelindo-cyan bg-sky-50 dark:bg-sky-900/30 px-2 py-1 rounded border border-sky-100 dark:border-sky-800">URGENT ACTION</span>
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500">Belum direspons</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl flex flex-col justify-between shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 dark:bg-amber-900/20 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Sedang Dikerjakan</p>
                        <div class="flex items-end gap-2">
                            <p class="text-3xl font-black text-slate-800 dark:text-slate-100">{{ $stats['on_progress'] ?? 0 }}</p>
                            <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-1">Tiket</p>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-slate-50 dark:bg-slate-800/30 rounded-full flex items-center justify-center text-slate-500 text-xl shadow-inner border border-slate-100 dark:border-slate-800">
                        <i class="fas fa-tools"></i>
                    </div>
                </div>
                <div class="mt-4 relative z-10 flex items-center gap-2">
                    <span class="text-[10px] font-bold text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-900/30 px-2 py-1 rounded border border-slate-100 dark:border-slate-800">ON PROGRESS</span>
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500">Ditangani teknisi</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl flex flex-col justify-between shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-50 dark:bg-purple-900/20 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Menunggu Suku Cadang</p>
                        <div class="flex items-end gap-2">
                            <p class="text-3xl font-black text-slate-800 dark:text-slate-100">{{ $stats['order_part'] ?? 0 }}</p>
                            <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-1">Tiket</p>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-slate-50 dark:bg-slate-800/30 rounded-full flex items-center justify-center text-slate-500 text-xl shadow-inner border border-slate-100 dark:border-slate-800">
                        <i class="fas fa-box-open"></i>
                    </div>
                </div>
                <div class="mt-4 relative z-10 flex items-center gap-2">
                    <span class="text-[10px] font-bold text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-900/30 px-2 py-1 rounded border border-slate-100 dark:border-slate-800">ORDER PART</span>
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500">Menunggu vendor/logistik</span>
                </div>
            </div>
        </div>

        <!-- ANALYTICS CHARTS -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Tren Chart (Expanded) -->
            <div class="lg:col-span-9 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm flex flex-col">
                <h3 class="text-xs font-black text-slate-700 dark:text-slate-200 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-line text-pelindo-blue"></i> Tren Insiden (30 Hari Terakhir)
                </h3>
                <div class="relative h-64 w-full mt-auto">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <!-- Operational KPI Card (Replacement for Donut) -->
            <div class="lg:col-span-3 bg-pelindo-navy dark:bg-slate-900 text-white p-6 rounded-2xl shadow-xl flex flex-col justify-between relative overflow-hidden group border border-white/5 dark:border-white/10 transition-all">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl opacity-50 group-hover:opacity-80 transition-opacity"></div>
                
                <div class="relative z-10">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-pelindo-cyan mb-6 flex items-center gap-2">
                        <i class="fas fa-microchip"></i> Performance KPI
                    </h3>
                    
                    <div class="space-y-8">
                        <div class="group/kpi">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 group-hover/kpi:text-blue-300 transition-colors">Mean Time to Resolve</p>
                            <div class="flex items-end gap-2">
                                <span class="text-4xl font-black leading-none">{{ $stats['mttr'] }}</span>
                                <span class="text-xs font-bold text-slate-400 mb-1">Hari</span>
                            </div>
                            <div class="w-full bg-white/10 h-1.5 mt-4 rounded-full overflow-hidden border border-white/5">
                                <div class="bg-gradient-to-r from-blue-600 to-blue-400 h-full transition-all duration-1000" style="width: {{ min(100, (1 / max(1, $stats['mttr'])) * 300) }}%"></div>
                            </div>
                        </div>

                        <div class="group/kpi">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 group-hover/kpi:text-emerald-300 transition-colors">Target SLA (Sembuh)</p>
                            <div class="flex items-center gap-3">
                                <span class="text-2xl font-black text-pelindo-cyan">92%</span>
                                <span class="text-[9px] font-black bg-white/10 text-pelindo-cyan px-2 py-1 rounded border border-white/10 uppercase tracking-tighter">On Schedule</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-white/5 relative z-10 text-center">
                    <p class="text-[8px] font-bold text-slate-500 uppercase tracking-[0.3em] group-hover:text-slate-400 transition-colors">Efisiensi Perbaikan Pusat</p>
                </div>
            </div>

            <!-- Top 5 Entitas Bermasalah -->
            <div class="lg:col-span-12 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm flex flex-col">
                <h3 class="text-xs font-black text-slate-700 dark:text-slate-200 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="fas fa-city text-pelindo-blue"></i> Sebaran Kerusakan per Cabang (Top 5)
                </h3>
                <div class="relative h-40 w-full mt-auto">
                    <canvas id="topEntityChart"></canvas>
                </div>
            </div>

        </div>

        <!-- ACTIONABLE LISTS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Top Infrastruktur Rusak -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm flex flex-col">
                <div class="px-6 py-5 bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <i class="fas fa-tools text-slate-600 dark:text-slate-400 text-xl"></i>
                        <div>
                            <h3 class="text-slate-800 dark:text-slate-100 font-black uppercase tracking-widest text-sm leading-none">Top 5 Kerusakan Terbanyak</h3>
                            <p class="text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest mt-1">Evaluasi Aset Kritis</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 flex-1">
                    <div class="space-y-3">
                        @forelse($frequentInfrastructures as $index => $infra)
                        <div @click='openDetailModal({{ json_encode($infra) }})' class="cursor-pointer group flex items-center justify-between p-3.5 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 hover:border-blue-300 dark:hover:border-blue-700 hover:shadow-md transition-all relative">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center font-black text-[10px] group-hover:bg-pelindo-blue group-hover:text-white transition-colors">
                                    #{{ $index + 1 }}
                                </div>
                                <div>
                                    <h4 class="text-[11px] font-black text-slate-800 dark:text-slate-200 uppercase group-hover:text-pelindo-blue dark:group-hover:text-pelindo-cyan transition-colors">{{ $infra->code_name }}</h4>
                                    <p class="text-[9px] text-slate-400 dark:text-slate-500 uppercase font-bold">{{ $infra->entity->name ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-black text-pelindo-blue dark:text-pelindo-cyan">{{ $infra->breakdown_logs_count }}</span>
                                <p class="text-[8px] text-slate-400 uppercase font-bold">Kali</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-[10px] text-slate-400 uppercase font-bold text-center py-8">Belum ada riwayat.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Pemeliharaan Mendatang (Maintenance) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm flex flex-col">
                <div class="px-6 py-5 bg-blue-50/50 dark:bg-blue-900/10 border-b border-blue-100 dark:border-blue-800 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <i class="fas fa-calendar-alt text-pelindo-blue dark:text-pelindo-cyan text-xl"></i>
                        <div>
                            <h3 class="text-slate-800 dark:text-slate-100 font-black uppercase tracking-widest text-sm leading-none">Pemeliharaan Mendatang</h3>
                            <p class="text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest mt-1">Jadwal 7 Hari Ke Depan</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 flex-1">
                    <div class="space-y-3">
                        @forelse($upcomingMaintenance as $sched)
                        <div @click='openDetailModal({{ json_encode($sched->infrastructure) }})' class="cursor-pointer flex items-center justify-between p-3.5 rounded-xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-800/50 shadow-sm relative group overflow-hidden">
                            <div class="absolute left-0 top-0 w-1 h-full bg-pelindo-blue opacity-20 group-hover:opacity-100 transition-opacity"></div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-900 flex flex-col items-center justify-center border border-slate-100 dark:border-slate-700">
                                    <span class="text-[9px] font-black text-pelindo-blue dark:text-pelindo-cyan leading-none">{{ \Carbon\Carbon::parse($sched->scheduled_date)->translatedFormat('d') }}</span>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase leading-none mt-0.5">{{ \Carbon\Carbon::parse($sched->scheduled_date)->translatedFormat('M') }}</span>
                                </div>
                                <div>
                                    <h4 class="text-[11px] font-black text-slate-800 dark:text-slate-200 uppercase truncate max-w-[120px]">{{ $sched->title }}</h4>
                                    <p class="text-[9px] text-slate-400 uppercase font-bold">{{ $sched->infrastructure->code_name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[9px] font-black text-slate-600 dark:text-slate-300 uppercase tracking-tighter">{{ \Carbon\Carbon::parse($sched->scheduled_date)->diffForHumans() }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10 opacity-30">
                            <i class="fas fa-calendar-check text-4xl mb-3"></i>
                            <p class="text-[10px] font-black uppercase tracking-widest">Tidak ada jadwal terdekat</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Laporan Mendesak (Urgent) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm flex flex-col">
                <div class="px-6 py-5 bg-red-50 dark:bg-red-900/20 border-b border-red-100 dark:border-red-900/30 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <i class="fas fa-siren-on text-pelindo-blue dark:text-pelindo-cyan text-xl"></i>
                        <div>
                            <h3 class="text-slate-800 dark:text-slate-100 font-black uppercase tracking-widest text-sm leading-none">Laporan Mendesak</h3>
                            <p class="text-[9px] text-pelindo-blue dark:text-pelindo-cyan font-bold uppercase tracking-widest mt-1">Tiket Status 'Reported'</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 flex-1">
                    <div class="space-y-3">
                        @forelse($urgentBreakdowns as $log)
                        <div @click='openDetailModal({{ json_encode($log->infrastructure) }}, {{ json_encode($log->only(["issue_detail", "repair_status", "vendor_pic", "created_at"])) }})' class="cursor-pointer group flex items-start gap-3 p-3.5 border border-red-100 dark:border-red-900/30 rounded-xl bg-white dark:bg-slate-800 shadow-sm hover:border-red-400 dark:hover:border-red-700 transition-all">
                            <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center shrink-0 mt-0.5 group-hover:bg-pelindo-blue group-hover:text-white transition-colors">
                                <i class="fas fa-exclamation text-[10px]"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-0.5">
                                    <h4 class="text-[11px] font-black text-slate-800 dark:text-slate-200 uppercase truncate max-w-[100px]">{{ $log->infrastructure->code_name ?? 'Asset' }}</h4>
                                    <span class="text-[8px] font-bold text-slate-500">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-[10px] text-slate-600 dark:text-slate-400 line-clamp-1 italic">"{{ $log->issue_detail }}"</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10 opacity-30">
                            <i class="fas fa-shield-check text-4xl mb-3 text-pelindo-cyan"></i>
                            <p class="text-[10px] font-black uppercase tracking-widest">Semua Laporan Teratasi</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm " >
            <div class="px-6 py-5 bg-pelindo-navy dark:bg-[#000d1a] flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <i class="fas fa-clipboard-list text-pelindo-cyan text-xl"></i>
                    <div>
                        <h3 class="text-white font-black uppercase tracking-widest text-sm leading-none">Log Insiden Aktif</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">Status laporan yang belum terselesaikan</p>
                    </div>
                </div>
                <a href="{{ route('admin.breakdowns.index') }}" class="hidden sm:block text-[10px] font-black text-blue-400 hover:text-white uppercase tracking-widest transition-colors border border-blue-900/50 hover:border-blue-400 px-4 py-2 rounded-lg">
                    Kelola Laporan
                </a>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse ent-table min-w-[1000px]">
                    <thead>
                        <tr class="bg-pelindo-blue dark:bg-slate-800 text-white/80 dark:text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                            <th class="px-8 py-5 w-16 text-center border-r border-white/5">NO</th>
                            <th class="px-8 py-5">Entitas Pelabuhan</th>
                            <th class="px-8 py-5">Identitas Alat</th>
                            <th class="px-8 py-5">Ringkasan Kendala</th>
                            <th class="px-8 py-5 text-center">Status Tahapan</th>
                            <th class="px-8 py-5">PIC / Vendor</th>
                            <th class="px-8 py-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs font-medium">
                        @forelse($recentLogs ?? [] as $index => $log)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all group">
                            <td class="px-8 py-5 text-center text-slate-400 font-black border-r border-slate-50 dark:border-slate-800">{{ $index + 1 }}</td>
                            <td class="px-8 py-5">
                                <div class="flex flex-col">
                                    <span class="text-slate-800 dark:text-slate-200 uppercase font-black tracking-tight">{{ $log->infrastructure->entity->name ?? '-' }}</span>
                                    <span class="text-[9px] text-slate-400 uppercase font-bold mt-0.5">Terminal Area</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-pelindo-blue dark:text-pelindo-cyan font-black uppercase bg-sky-50 dark:bg-sky-900/30 px-3 py-1.5 rounded-lg border border-sky-100 dark:border-sky-800 shadow-sm group-hover:bg-sky-100 transition-colors">{{ $log->infrastructure->code_name ?? '-' }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-slate-600 dark:text-slate-400 max-w-[250px] truncate leading-relaxed italic" title="{{ $log->issue_detail }}">"{{ $log->issue_detail }}"</p>
                            </td>
                            <td class="px-8 py-5 text-center">
                                @php
                                    $statusConfig = \App\Models\BreakdownLog::getStatusConfig();
                                    $conf = $statusConfig[$log->repair_status] ?? $statusConfig['reported'];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded {{ $conf['bg'] }} {{ $conf['text'] }} border {{ $conf['border'] }} text-[8px] font-black uppercase tracking-widest shadow-sm">
                                    <i class="fas {{ $conf['icon'] }}"></i>
                                    {{ $conf['label'] }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] text-slate-500"><i class="fas fa-user"></i></div>
                                    <span class="text-slate-600 dark:text-slate-300 font-bold uppercase">{{ $log->vendor_pic ?? 'Internal' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <button @click='openDetailModal({{ json_encode($log->infrastructure) }}, {{ json_encode($log->only(["issue_detail", "repair_status", "vendor_pic", "created_at"])) }})' class="w-8 h-8 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-200 dark:hover:border-blue-800 transition-all shadow-sm">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center opacity-40">
                                    <i class="fas fa-search text-4xl mb-4"></i>
                                    <p class="text-xs font-black uppercase tracking-widest">Tidak ada data yang ditemukan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Super Detail Modal (Interactive UI) -->
        <div x-show="showDetailModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Background Backdrop -->
            <div x-show="showDetailModal" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                 @click="showDetailModal = false"></div>

            <!-- Modal Panel -->
            <div x-show="showDetailModal" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all sm:my-8 w-full max-w-4xl flex flex-col max-h-[90vh]">
                
                <!-- Modal Header -->
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-800/50 shrink-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center text-2xl shadow-inner border border-blue-200 dark:border-blue-800">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 dark:text-slate-100 uppercase tracking-wide leading-tight" x-text="infraData?.code_name"></h3>
                            <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mt-0.5">
                                <span x-text="infraData?.entity?.name"></span> &bull; 
                                <span x-text="infraData?.category"></span> &bull; 
                                <span x-text="infraData?.type"></span>
                            </p>
                        </div>
                    </div>
                    <button @click="showDetailModal = false" class="text-slate-400 hover:text-red-500 transition-colors p-2 bg-white dark:bg-slate-800 rounded-full hover:bg-red-50 dark:hover:bg-red-900/30 border border-slate-200 dark:border-slate-700 hover:border-red-200 dark:hover:border-red-800 w-10 h-10 flex items-center justify-center shadow-sm">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="p-6 overflow-y-auto flex-1 bg-white dark:bg-slate-900 custom-scrollbar">

                    <!-- Detail Insiden Aktif (Jika dibuka dari Log) -->
                    <template x-if="activeLog">
                        <div class="mb-8 p-6 rounded-2xl bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30">
                            <h4 class="text-[10px] font-black text-red-600 dark:text-red-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <i class="fas fa-exclamation-triangle"></i> Detail Insiden Terkini
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Deskripsi Kendala</p>
                                    <p class="text-sm font-black text-slate-800 dark:text-slate-200 italic" x-text="'&quot;' + activeLog.issue_detail + '&quot;'"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Status Perbaikan</p>
                                    <span class="inline-flex px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest border"
                                          :class="{
                                              'bg-red-100 text-red-700 border-red-200': activeLog.repair_status === 'reported',
                                              'bg-amber-100 text-amber-700 border-amber-200': activeLog.repair_status === 'on_progress',
                                              'bg-blue-100 text-blue-700 border-blue-200': activeLog.repair_status === 'work_order',
                                              'bg-purple-100 text-purple-700 border-purple-200': activeLog.repair_status === 'order_part',
                                              'bg-emerald-100 text-emerald-700 border-emerald-200': activeLog.repair_status === 'resolved'
                                          }"
                                          x-text="activeLog.repair_status"></span>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">PIC / Vendor</p>
                                    <p class="text-xs font-black text-slate-700 dark:text-slate-300" x-text="activeLog.vendor_pic || 'Internal'"></p>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Asset Photo Section -->
                    <div class="mb-8" x-show="infraData?.image">
                        <div class="relative rounded-2xl overflow-hidden border-4 border-white dark:border-slate-800 shadow-xl group">
                            <img :src="infraData?.image?.startsWith('http') ? infraData.image : '/storage/' + infraData?.image" 
                                 class="w-full h-48 md:h-64 object-cover transition-transform duration-700 group-hover:scale-110" 
                                 alt="Foto Aset">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                                <p class="text-white text-[10px] font-black uppercase tracking-widest"><i class="fas fa-camera mr-2"></i> Foto Dokumentasi Aset</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Quick Stats -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl p-5 flex items-center gap-5 shadow-sm">
                            <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-700 shadow-sm"><i class="fas fa-history text-xl"></i></div>
                            <div>
                                <p class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest">Total Riwayat Kerusakan</p>
                                <p class="text-2xl font-black text-slate-800 dark:text-slate-100" x-text="infraData?.breakdown_logs?.length + ' Kali'"></p>
                            </div>
                        </div>
                        
                        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 rounded-xl p-5 flex items-center gap-5 shadow-sm">
                            <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-700 shadow-sm"><i class="fas fa-battery-half text-xl"></i></div>
                            <div>
                                <p class="text-[10px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-widest">Kondisi Aset Saat Ini</p>
                                <p class="text-2xl font-black text-slate-800 dark:text-slate-100 uppercase mt-1" x-text="infraData?.status"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Kerusakan -->
                    <div>
                        <h4 class="text-xs font-black text-slate-800 dark:text-slate-100 uppercase tracking-widest mb-6 flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-2">
                            <i class="fas fa-stream text-blue-500"></i> Histori Perbaikan (Timeline)
                        </h4>
                        
                        <div class="relative border-l-2 border-slate-200 dark:border-slate-800 ml-3 space-y-8 pb-4">
                            <template x-for="(log, index) in infraData?.breakdown_logs" :key="index">
                                <div class="relative pl-6">
                                    <!-- Timeline Dot -->
                                    <div class="absolute w-4 h-4 rounded-full border-2 border-white -left-[9px] top-1"
                                         :class="{
                                            'bg-red-500': log.repair_status === 'reported',
                                            'bg-amber-500': log.repair_status === 'on_progress',
                                            'bg-purple-500': log.repair_status === 'order_part',
                                            'bg-emerald-500': log.repair_status === 'resolved'
                                         }">
                                    </div>
                                    
                                    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow relative">
                                        <!-- Status Badge -->
                                        <div class="absolute right-4 top-4">
                                            <span class="text-[9px] font-black px-2 py-1 rounded-md uppercase tracking-widest border"
                                                  :class="{
                                                      'bg-red-50 text-red-600 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800': log.repair_status === 'reported',
                                                      'bg-amber-50 text-amber-600 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800': log.repair_status === 'on_progress',
                                                      'bg-purple-50 text-purple-600 border-purple-200 dark:bg-purple-900/30 dark:text-purple-400 dark:border-purple-800': log.repair_status === 'order_part',
                                                      'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800': log.repair_status === 'resolved'
                                                  }"
                                                  x-text="log.repair_status.replace('_', ' ')">
                                            </span>
                                        </div>

                                        <p class="text-[10px] font-bold text-slate-500 mb-2">
                                            <i class="far fa-calendar-alt mr-1"></i> <span x-text="new Date(log.created_at).toLocaleDateString('id-ID', {weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'})"></span>
                                        </p>
                                        
                                        <p class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-3" x-text="log.issue_detail"></p>
                                        
                                        <div class="flex items-center gap-4 text-[10px] text-slate-500 dark:text-slate-400 font-bold bg-slate-50 dark:bg-slate-900 p-2 rounded-lg border border-slate-100 dark:border-slate-800">
                                            <div class="flex items-center gap-1.5">
                                                <i class="fas fa-user-hard-hat text-slate-400 dark:text-slate-500"></i> Pelapor: <span class="text-slate-700 dark:text-slate-300" x-text="log.created_by?.name || 'Sistem'"></span>
                                            </div>
                                            <div class="w-px h-3 bg-slate-300 dark:bg-slate-700"></div>
                                            <div class="flex items-center gap-1.5">
                                                <i class="fas fa-toolbox text-slate-400 dark:text-slate-500"></i> Vendor/PIC: <span class="text-slate-700 dark:text-slate-300 uppercase" x-text="log.vendor_pic || '-'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            
                            <!-- Placeholder if no logs -->
                            <div x-show="!infraData?.breakdown_logs || infraData.breakdown_logs.length === 0" class="pl-6">
                                <div class="absolute w-4 h-4 rounded-full border-2 border-white bg-slate-300 -left-[9px] top-1"></div>
                                <div class="bg-slate-50 dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-700 rounded-xl p-6 text-center">
                                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Belum ada riwayat kerusakan.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between shrink-0">
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase">Dashboard Infrastructure Availability (DIA)</p>
                    <a :href="'/admin/infrastructures/' + infraData?.id" class="bg-pelindo-blue dark:bg-pelindo-blue hover:bg-pelindo-navy dark:hover:bg-pelindo-navy text-white px-5 py-2.5 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-md transition-colors flex items-center gap-2">
                        <i class="fas fa-tools"></i> Kelola Perbaikan (Update Status)
                    </a>
                </div>
            </div>
        </div>

    </div>




    <!-- Export Filter Modal -->
    <x-export-filter-modal />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Helper to get colors based on dark mode
            const isDark = () => document.documentElement.classList.contains('dark');
            const getGridColor = () => isDark() ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
            const getTextColor = () => isDark() ? '#94a3b8' : '#64748b';

            // 1. Trend Line Chart (DYNAMIC GRADIENT)
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            
            const createGradient = (ctx) => {
                const gradient = ctx.createLinearGradient(0, 0, 0, 250);
                gradient.addColorStop(0, 'rgba(0, 85, 164, 0.4)');    // Pelindo Blue
                gradient.addColorStop(1, 'rgba(0, 85, 164, 0)');
                return gradient;
            };

            const trendChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData['trendLabels'] ?? []) !!},
                    datasets: [{
                        label: 'Insiden Tercatat',
                        data: {!! json_encode($chartData['trendCounts'] ?? []) !!},
                        borderColor: '#0064a7',
                        backgroundColor: createGradient(trendCtx),
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#0064a7',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark() ? '#1e293b' : '#0f172a',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 12,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: getGridColor(), drawBorder: false },
                            ticks: { stepSize: 1, font: {size: 10, weight: '600'}, color: getTextColor() }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: {size: 10, weight: '600'}, color: getTextColor() }
                        }
                    }
                }
            });

            // 3. CHART: Horizontal Bar untuk Top 5 Entitas Bermasalah
            const topEntityChart = new Chart(document.getElementById('topEntityChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: {!! $topBrokenEntities->keys()->toJson() !!},
                    datasets: [{
                        label: 'Total Aset Rusak',
                        data: {!! $topBrokenEntities->values()->toJson() !!},
                        backgroundColor: '#58b9e4',
                        borderRadius: 4,
                        barThickness: 18
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true, maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark() ? '#1e293b' : '#0f172a',
                            padding: 12,
                            displayColors: false
                        }
                    },
                    scales: {
                        x: { 
                            beginAtZero: true, 
                            grid: { color: getGridColor(), drawBorder: false }, 
                            ticks: { stepSize: 1, font: {size: 10, weight: '600'}, color: getTextColor() } 
                        },
                        y: { 
                            grid: { display: false }, 
                            ticks: { font: { size: 10, weight: '700' }, color: getTextColor() } 
                        }
                    }
                }
            });

            // Function to update charts on dark mode toggle
            window.addEventListener('storage', (e) => {
                if (e.key === 'dark-mode') {
                    setTimeout(() => {
                        trendChart.options.scales.y.grid.color = getGridColor();
                        trendChart.options.scales.y.ticks.color = getTextColor();
                        trendChart.options.scales.x.ticks.color = getTextColor();
                        trendChart.options.plugins.tooltip.backgroundColor = isDark() ? '#1e293b' : '#0f172a';
                        trendChart.update();

                        topEntityChart.options.scales.x.grid.color = getGridColor();
                        topEntityChart.options.scales.x.ticks.color = getTextColor();
                        topEntityChart.options.scales.y.ticks.color = getTextColor();
                        topEntityChart.options.plugins.tooltip.backgroundColor = isDark() ? '#1e293b' : '#0f172a';
                        topEntityChart.update();
                    }, 100);
                }
            });

            // Handle manual toggle from button (since it doesn't trigger storage event on same tab)
            const originalToggle = window.toggleDarkMode;
            window.toggleDarkMode = function() {
                originalToggle();
                setTimeout(() => {
                    const gridColor = getGridColor();
                    const textColor = getTextColor();
                    const tooltipBg = isDark() ? '#1e293b' : '#0f172a';

                    trendChart.options.scales.y.grid.color = gridColor;
                    trendChart.options.scales.y.ticks.color = textColor;
                    trendChart.options.scales.x.ticks.color = textColor;
                    trendChart.options.plugins.tooltip.backgroundColor = tooltipBg;
                    trendChart.update();

                    topEntityChart.options.scales.x.grid.color = gridColor;
                    topEntityChart.options.scales.x.ticks.color = textColor;
                    topEntityChart.options.scales.y.ticks.color = textColor;
                    topEntityChart.options.plugins.tooltip.backgroundColor = tooltipBg;
                    topEntityChart.update();
                }, 100);
            };
        });
    </script>
</x-app-layout>
