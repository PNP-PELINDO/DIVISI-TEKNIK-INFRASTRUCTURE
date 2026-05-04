<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fa;
        }

        .dark body {
            background-color: #0f172a;
        }

        [x-cloak] { display: none !important; }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .asset-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .dark .asset-card {
            background-color: #1e293b;
            border-color: #334155;
        }

        .asset-card:hover {
            box-shadow: 0 12px 20px -5px rgba(0, 51, 102, 0.1);
            border-color: #0055a4;
            transform: translateY(-4px);
        }

        .asset-img-box {
            width: 100%;
            aspect-ratio: 16/9;
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            overflow: hidden;
        }

        .dark .asset-img-box {
            background-color: #0f172a;
            border-color: #1e293b;
        }

        .asset-title-wrapper {
            min-height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 8px;
            border-radius: 6px;
            margin-top: 6px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-available { background-color: #ecfdf5; border: 1px solid #d1fae5; color: #065f46; }
        .dark .stat-available { background-color: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); color: #34d399; }
        .stat-available .badge { background-color: #10b981; color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px; }

        .stat-breakdown { background-color: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; }
        .dark .stat-breakdown { background-color: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.2); color: #f87171; }
        .stat-breakdown .badge { background-color: #ef4444; color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px; }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
    </style>
    @php
        $equipment = isset($allInfrastructures) ? $allInfrastructures->where('category', 'equipment') : collect();
        $facility = isset($allInfrastructures) ? $allInfrastructures->where('category', 'facility') : collect();
        $utility = isset($allInfrastructures) ? $allInfrastructures->where('category', 'utility') : collect();

        $groupedEquipment = $equipment->groupBy(fn($i) => $i->entity->name ?? 'Unknown Entity');
        $groupedFacility = $facility->groupBy(fn($i) => $i->entity->name ?? 'Unknown Entity');
        $groupedUtility = $utility->groupBy(fn($i) => $i->entity->name ?? 'Unknown Entity');

        $countEq = $equipment->count();
        $countFac = $facility->count();
        $countUtil = $utility->count();
    @endphp

    <div id="main-ui" class="max-w-[1600px] mx-auto w-full space-y-6 animate-fade-up" x-data="{ showDetailModal: false, infraData: null, openDetailModal(data) { this.infraData = data; this.showDetailModal = true; } }">

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 sm:p-8 shadow-sm flex flex-col xl:flex-row items-center justify-between gap-6 relative z-[60]">
            <div class="flex flex-col sm:flex-row items-center text-center sm:text-left gap-6 w-full xl:w-auto">
                <div class="flex items-center justify-center gap-3 sm:gap-4 bg-slate-50 dark:bg-slate-800 p-3 sm:p-4 rounded-2xl border border-slate-100 dark:border-slate-700 w-full sm:w-auto">
                    <img src="{{ asset('danantara.png') }}" alt="Danantara" class="h-7 sm:h-10 md:h-12 object-contain">
                    <div class="w-px h-8 sm:h-10 bg-slate-300"></div>
                    <img src="{{ asset('pelindo.png') }}" alt="Pelindo" class="h-7 sm:h-10 md:h-12 object-contain">
                </div>
            </div>

            <div class="grid grid-cols-1 min-[450px]:grid-cols-2 sm:flex sm:flex-row sm:flex-wrap justify-center gap-3 w-full xl:w-auto">
                <div class="relative group z-50 w-full sm:w-auto">
                    <button class="w-full sm:w-auto justify-center bg-[#003366] hover:bg-[#002244] text-white px-5 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-900/20 transition-all flex items-center gap-2">
                        <i class="fas fa-file-export"></i> Export Laporan <i class="fas fa-chevron-down ml-1"></i>
                    </button>
                    <div class="absolute left-0 right-0 sm:right-auto xl:right-0 xl:left-auto top-full mt-2 w-full sm:w-48 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all transform origin-top xl:origin-top-right scale-95 group-hover:scale-100 flex flex-col overflow-hidden">
                        <button onclick="openExportModal('pdf')" class="flex items-center gap-3 px-4 py-3 text-left hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-[10px] font-black uppercase tracking-widest transition-colors border-b border-slate-50 dark:border-slate-700">
                            <i class="fas fa-file-pdf text-red-500 text-sm"></i> Format PDF
                        </button>
                        <button onclick="openExportModal('excel')" class="flex items-center gap-3 px-4 py-3 text-left hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-[10px] font-black uppercase tracking-widest transition-colors">
                            <i class="fas fa-file-excel text-emerald-500 text-sm"></i> Format Excel
                        </button>
                    </div>
                </div>

                <a href="{{ url('/') }}" class="w-full sm:w-auto justify-center bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-[#003366] dark:text-blue-400 px-5 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm transition-all flex items-center gap-2 border border-slate-200 dark:border-slate-700">
                    <i class="fas fa-globe"></i> Portal Publik
                </a>
                <a href="{{ route('admin.infrastructures.create') }}" class="w-full sm:w-auto justify-center bg-[#003366] hover:bg-[#001e3c] text-white px-5 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-900/20 transition-all flex items-center gap-2">
                    <i class="fas fa-plus"></i> Aset Baru
                </a>
                <a href="{{ route('admin.breakdowns.create') }}" class="w-full sm:w-auto justify-center bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-amber-900/20 transition-all flex items-center gap-2">
                    <i class="fas fa-triangle-exclamation"></i> Lapor Kerusakan
                </a>
            </div>
        </div>

        <!-- FILTER PANEL -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 sm:p-6 shadow-sm relative z-[50]" >
            <form action="{{ route('dashboard') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
                @if(auth()->user()->role === 'superadmin')
                <div class="w-full md:w-1/3">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Pilih Terminal / Bagian</label>
                    <div class="relative">
                        <select name="entity_id" class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-[#003366] focus:border-[#003366] transition-all appearance-none cursor-pointer" onchange="this.form.submit()">
                            <option value="">-- Semua Area (Pusat) --</option>
                            @foreach($allEntities ?? [] as $entity)
                                <option value="{{ $entity->id }}" {{ ($filterEntity ?? '') == $entity->id ? 'selected' : '' }}>{{ $entity->name }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-map-marker-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                </div>
                @endif
                
                <div class="w-full md:w-1/3">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Kategori Aset</label>
                    <div class="relative">
                        <select name="category" class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-[#003366] focus:border-[#003366] transition-all appearance-none cursor-pointer" onchange="this.form.submit()">
                            <option value="">-- Semua Kategori --</option>
                            <option value="equipment" {{ ($filterCategory ?? '') == 'equipment' ? 'selected' : '' }}>Peralatan (Equipment)</option>
                            <option value="facility" {{ ($filterCategory ?? '') == 'facility' ? 'selected' : '' }}>Fasilitas (Facility)</option>
                            <option value="utility" {{ ($filterCategory ?? '') == 'utility' ? 'selected' : '' }}>Utilitas (Utility)</option>
                        </select>
                        <i class="fas fa-layer-group absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                </div>

                <div class="w-full md:w-auto flex items-center gap-3 h-[46px]">
                    <button type="submit" class="h-full px-6 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 hover:text-blue-700 rounded-xl text-[10px] font-black uppercase tracking-widest border border-blue-200 dark:border-blue-800 transition-colors flex items-center justify-center">
                        <i class="fas fa-filter mr-2"></i> Terapkan
                    </button>
                    @if(($filterEntity ?? false) || ($filterCategory ?? false))
                        <a href="{{ route('dashboard') }}" class="h-full px-6 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-800 dark:hover:text-slate-200 rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-200 dark:border-slate-700 transition-colors flex items-center justify-center">
                            Reset
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
                        <p class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-1">Total Aset & Kesiapan</p>
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
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5">
                        <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $stats['readiness_rate'] }}%"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl flex flex-col justify-between shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 dark:bg-red-900/20 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-black text-red-600 dark:text-red-400 uppercase tracking-widest mb-1">Laporan Masuk (Baru)</p>
                        <div class="flex items-end gap-2">
                            <p class="text-3xl font-black text-slate-800 dark:text-slate-100">{{ $stats['reported'] ?? 0 }}</p>
                            <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-1">Tiket</p>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-red-50 dark:bg-red-900/30 rounded-full flex items-center justify-center text-red-500 text-xl shadow-inner border border-red-100 dark:border-red-800">
                        <i class="fas fa-bell"></i>
                    </div>
                </div>
                <div class="mt-4 relative z-10 flex items-center gap-2">
                    <span class="text-[10px] font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 px-2 py-1 rounded border border-red-100 dark:border-red-800">URGENT ACTION</span>
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500">Belum direspons</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl flex flex-col justify-between shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 dark:bg-amber-900/20 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-widest mb-1">Sedang Dikerjakan</p>
                        <div class="flex items-end gap-2">
                            <p class="text-3xl font-black text-slate-800 dark:text-slate-100">{{ $stats['on_progress'] ?? 0 }}</p>
                            <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-1">Tiket</p>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/30 rounded-full flex items-center justify-center text-amber-500 text-xl shadow-inner border border-amber-100 dark:border-amber-800">
                        <i class="fas fa-tools"></i>
                    </div>
                </div>
                <div class="mt-4 relative z-10 flex items-center gap-2">
                    <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 px-2 py-1 rounded border border-amber-100 dark:border-amber-800">ON PROGRESS</span>
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500">Ditangani teknisi</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl flex flex-col justify-between shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-50 dark:bg-purple-900/20 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest mb-1">Menunggu Suku Cadang</p>
                        <div class="flex items-end gap-2">
                            <p class="text-3xl font-black text-slate-800 dark:text-slate-100">{{ $stats['order_part'] ?? 0 }}</p>
                            <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-1">Tiket</p>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-purple-50 dark:bg-purple-900/30 rounded-full flex items-center justify-center text-purple-500 text-xl shadow-inner border border-purple-100 dark:border-purple-800">
                        <i class="fas fa-box-open"></i>
                    </div>
                </div>
                <div class="mt-4 relative z-10 flex items-center gap-2">
                    <span class="text-[10px] font-bold text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/30 px-2 py-1 rounded border border-purple-100 dark:border-purple-800">ORDER PART</span>
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500">Menunggu vendor/logistik</span>
                </div>
            </div>
        </div>

        <!-- ANALYTICS CHARTS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 " >
            
            <!-- Tren Chart (Lebar 2 Kolom) -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm flex flex-col">
                <h3 class="text-xs font-black text-slate-700 dark:text-slate-200 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-line text-[#0055a4]"></i> Tren Laporan Kerusakan (30 Hari Terakhir)
                </h3>
                <div class="relative h-64 w-full mt-auto">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <!-- Distribusi Kerusakan (1 Kolom) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm flex flex-col justify-between">
                <h3 class="text-xs font-black text-slate-700 dark:text-slate-200 uppercase tracking-widest text-center mb-4 flex items-center justify-center gap-2">
                    <i class="fas fa-chart-pie text-amber-500"></i> Kerusakan per Kategori
                </h3>
                <div class="relative h-48 w-full flex items-center justify-center mt-auto">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
            
        </div>

        <!-- ACTIONABLE LISTS -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 " >
            
            <!-- Top Infrastruktur Rusak -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm flex flex-col">
                <div class="px-6 py-5 bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <i class="fas fa-tools text-slate-600 dark:text-slate-400 text-xl"></i>
                        <div>
                            <h3 class="text-slate-800 dark:text-slate-100 font-black uppercase tracking-widest text-sm leading-none">Top 5 Infrastruktur Sering Rusak</h3>
                            <p class="text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest mt-1">Evaluasi Aset Kritis</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 flex-1">
                    <div class="space-y-4">
                        @forelse($frequentInfrastructures as $index => $infra)
                        <div @click="openDetailModal({{ json_encode($infra) }})" class="cursor-pointer group flex items-center justify-between p-4 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 hover:border-blue-300 dark:hover:border-blue-700 hover:shadow-md transition-all relative">
                            <div class="absolute right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fas fa-chevron-right text-blue-500"></i>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-black text-sm group-hover:bg-red-500 group-hover:text-white transition-colors">
                                    #{{ $index + 1 }}
                                </div>
                                <div>
                                    <h4 class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">{{ $infra->code_name }}</h4>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-bold mt-1">{{ $infra->entity->name ?? '-' }} &bull; {{ ucfirst($infra->category) }}</p>
                                </div>
                            </div>
                            <div class="text-right mr-6">
                                <span class="text-lg font-black text-[#003366] dark:text-blue-400">{{ $infra->breakdown_logs_count }}</span>
                                <p class="text-[9px] text-slate-400 dark:text-slate-500 uppercase font-bold">Kali Rusak</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <p class="text-xs text-slate-400 uppercase font-bold tracking-widest">Belum ada data kerusakan historis.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Top Terminal / Urgent / Info Tambahan -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm flex flex-col">
                <div class="px-6 py-5 bg-red-50 dark:bg-red-900/20 border-b border-red-100 dark:border-red-900/30 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <i class="fas fa-siren-on text-red-600 dark:text-red-400 text-xl"></i>
                        <div>
                            <h3 class="text-red-800 dark:text-red-200 font-black uppercase tracking-widest text-sm leading-none">Laporan Mendesak (Urgent)</h3>
                            <p class="text-[9px] text-red-500 dark:text-red-400 font-bold uppercase tracking-widest mt-1">Laporan Belum Ditindaklanjuti</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 flex-1">
                    <div class="space-y-4">
                        @forelse($urgentBreakdowns as $log)
                        <div @click="openDetailModal({{ json_encode($log->infrastructure) }})" class="cursor-pointer group flex items-start gap-4 p-4 border border-red-100 dark:border-red-900/30 rounded-xl bg-white dark:bg-slate-800 shadow-sm hover:border-red-400 dark:hover:border-red-700 hover:shadow-md transition-all relative">
                            <div class="absolute right-4 top-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fas fa-chevron-right text-red-500"></i>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0 mt-1 group-hover:bg-red-500 group-hover:text-white transition-colors">
                                <i class="fas fa-exclamation"></i>
                            </div>
                            <div class="flex-1 pr-6">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="text-[11px] font-black text-slate-800 dark:text-slate-200 uppercase group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors">{{ $log->infrastructure->code_name ?? 'Unknown Asset' }}</h4>
                                    <span class="text-[9px] font-bold text-red-500 bg-red-50 dark:bg-red-900/30 px-2 py-0.5 rounded" title="{{ $log->created_at->format('d M Y H:i') }}">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed">{{ $log->issue_detail }}</p>
                                <div class="mt-3 flex items-center justify-between text-[9px] uppercase font-bold text-slate-400 dark:text-slate-500 border-t border-slate-50 dark:border-slate-800 pt-2">
                                    <span>Lokasi: {{ $log->infrastructure->entity->name ?? '-' }}</span>
                                    <span>Pelapor: {{ $log->createdBy->name ?? 'Sistem' }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12 flex flex-col items-center justify-center h-full">
                            <div class="w-16 h-16 bg-emerald-50 text-emerald-500 border border-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl shadow-sm"><i class="fas fa-shield-check"></i></div>
                            <p class="text-xs text-slate-500 uppercase font-black tracking-widest">Semua Aman!</p>
                            <p class="text-[10px] text-slate-400 uppercase font-bold mt-1">Tidak ada laporan mendesak saat ini.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm " >
            <div class="px-6 py-5 bg-[#00152b] dark:bg-[#000d1a] flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <i class="fas fa-clipboard-list text-red-500 text-xl"></i>
                    <div>
                        <h3 class="text-white font-black uppercase tracking-widest text-sm leading-none">Log Insiden Aktif</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">Status laporan yang belum terselesaikan</p>
                    </div>
                </div>
                <a href="{{ route('admin.breakdowns.index') }}" class="hidden sm:block text-[10px] font-black text-blue-400 hover:text-white uppercase tracking-widest transition-colors border border-blue-900/50 hover:border-blue-400 px-4 py-2 rounded-lg">
                    Kelola Laporan
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-[#002244] dark:bg-slate-800 text-slate-300 dark:text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                            <th class="px-8 py-5 w-16 text-center">NO</th>
                            <th class="px-8 py-5">Entitas Pelabuhan</th>
                            <th class="px-8 py-5">Identitas Alat</th>
                            <th class="px-8 py-5">Ringkasan Kendala</th>
                            <th class="px-8 py-5 text-center">Status</th>
                            <th class="px-8 py-5">PIC</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs font-medium">
                        @forelse($recentLogs ?? [] as $index => $log)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-8 py-5 text-center text-slate-400 font-bold">{{ $index + 1 }}</td>
                            <td class="px-8 py-5 text-slate-600 dark:text-slate-400 uppercase font-bold">{{ $log->infrastructure->entity->name ?? '-' }}</td>
                            <td class="px-8 py-5">
                                <span class="text-[#003366] dark:text-blue-400 font-black uppercase bg-blue-50 dark:bg-blue-900/30 px-3 py-1.5 rounded border border-blue-100 dark:border-blue-800">{{ $log->infrastructure->code_name ?? '-' }}</span>
                            </td>
                            <td class="px-8 py-5 text-slate-600 dark:text-slate-400 max-w-[200px] truncate" title="{{ $log->issue_detail }}">{{ $log->issue_detail }}</td>
                            <td class="px-8 py-5 text-center">
                                @if($log->repair_status == 'order_part')
                                    <span class="bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-800 text-[9px] font-black px-3 py-1.5 rounded-md uppercase tracking-widest shadow-sm">Menunggu Suku Cadang</span>
                                @elseif($log->repair_status == 'on_progress')
                                    <span class="bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800 text-[9px] font-black px-3 py-1.5 rounded-md uppercase tracking-widest shadow-sm">Sedang Diperbaiki</span>
                                @elseif($log->repair_status == 'reported')
                                    <span class="bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 text-[9px] font-black px-3 py-1.5 rounded-md uppercase tracking-widest shadow-sm">Dilaporkan</span>
                                @else
                                    <span class="bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 text-[9px] font-black px-3 py-1.5 rounded-md uppercase tracking-widest shadow-sm">Selesai</span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-slate-500 uppercase">{{ $log->vendor_pic ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-8 py-16 text-center text-slate-400 dark:text-slate-500">Tidak ada laporan kerusakan alat saat ini.</td></tr>
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
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- Quick Stats -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl p-4 flex items-center gap-4 shadow-sm">
                            <div class="w-10 h-10 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-700"><i class="fas fa-history text-lg"></i></div>
                            <div>
                                <p class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest">Total Kerusakan</p>
                                <p class="text-xl font-black text-slate-800 dark:text-slate-100" x-text="infraData?.breakdown_logs?.length + ' Kali'"></p>
                            </div>
                        </div>
                        
                        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 rounded-xl p-4 flex items-center gap-4 shadow-sm">
                            <div class="w-10 h-10 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-700"><i class="fas fa-battery-half text-lg"></i></div>
                            <div>
                                <p class="text-[10px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-widest">Status Saat Ini</p>
                                <p class="text-xl font-black text-slate-800 dark:text-slate-100 uppercase text-sm mt-1" x-text="infraData?.status"></p>
                            </div>
                        </div>

                        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 rounded-xl p-4 flex items-center gap-4 shadow-sm">
                            <div class="w-10 h-10 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-700"><i class="fas fa-boxes text-lg"></i></div>
                            <div>
                                <p class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">Kuantitas</p>
                                <p class="text-xl font-black text-slate-800 dark:text-slate-100" x-text="infraData?.quantity + ' Unit'"></p>
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
                                            <i class="far fa-calendar-alt mr-1"></i> <span x-text="new Date(log.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})"></span>
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
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase">Pelindo Infrastructure Reporting System</p>
                    <a :href="'/admin/infrastructures/' + infraData?.id" class="bg-[#003366] dark:bg-blue-600 hover:bg-[#001e3c] dark:hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-md transition-colors flex items-center gap-2">
                        <i class="fas fa-tools"></i> Kelola Perbaikan (Update Status)
                    </a>
                </div>
            </div>
        </div>

    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    <!-- Hidden Report Component (for fallback) -->
    <x-export-report :infrastructures="$allInfrastructures" :recentBreakdowns="$allActiveBreakdowns" />
    
    <!-- Export Filter Modal -->
    <x-export-filter-modal />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Peralatan', 'Fasilitas', 'Utilitas'],
                    datasets: [{
                        data: [
                            {{ $chartData['breakdownsByCategory']['equipment'] ?? 0 }}, 
                            {{ $chartData['breakdownsByCategory']['facility'] ?? 0 }},
                            {{ $chartData['breakdownsByCategory']['utility'] ?? 0 }}
                        ],
                        backgroundColor: ['#3b82f6', '#f59e0b', '#8b5cf6'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false, 
                    cutout: '75%', 
                    plugins: { 
                        legend: { 
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                usePointStyle: true,
                                font: { size: 10, weight: 'bold' },
                                padding: 20
                            }
                        } 
                    } 
                }
            });

            const trendCtx = document.getElementById('trendChart').getContext('2d');
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData['trendLabels']) !!},
                    datasets: [{
                        label: 'Laporan Kerusakan',
                        data: {!! json_encode($chartData['trendCounts']) !!},
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#ef4444',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
</x-app-layout>
