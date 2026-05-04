<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        
        body { 
            font-family: 'Inter', sans-serif; 
        }

        /* Pelindo Blue Theme */
        .bg-pelindo { background-color: #0055a4; }
        .text-pelindo { color: #0055a4; }
        .dark .text-pelindo { color: #60a5fa; }
        
        .animate-fade-in { animation: fadeIn 0.8s ease-out forwards; opacity: 0; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

        /* Professional Cards */
        .card-stats {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .dark .card-stats {
            background: #1e293b;
            border-color: #334155;
        }
        .card-stats:hover { 
            transform: translateY(-5px); 
            border-color: #0055a4; 
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); 
        }
        .dark .card-stats:hover { 
            border-color: #3b82f6; 
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3); 
        }

        #hiddenExportTable { display: none; background: white; }
    </style>


    <div id="main-ui" class="max-w-[1600px] mx-auto w-full space-y-6 animate-fade-up">

        <!-- HEADER SECTION -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm space-y-8 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#003366] to-[#0055a4]"></div>

            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/30 text-[#0055a4] dark:text-blue-400 rounded-[1.5rem] flex items-center justify-center text-3xl border border-blue-100 dark:border-blue-800 shadow-inner">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-[#003366] dark:text-white uppercase tracking-tight">Pusat Analitik</h1>
                        <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 flex items-center gap-2">
                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span> 
                            {{ $user->role === 'superadmin' ? 'Monitoring Performa Seluruh Cabang' : 'Statistik Detail ' . $chartData['entity_name'] }}
                        </p>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="relative w-full lg:w-auto">
                    <button @click="open = !open" @click.away="open = false" 
                            class="w-full lg:w-auto justify-center bg-white dark:bg-slate-800 text-[#003366] dark:text-blue-400 px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all flex items-center gap-3 border border-slate-200 dark:border-slate-700 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700 active:scale-95">
                        <i class="fas fa-file-export text-xs"></i> Unduh Laporan <i class="fas fa-chevron-down text-[8px] opacity-60 ml-2"></i>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute right-0 mt-3 w-full lg:w-64 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-2xl z-[100] overflow-hidden">
                        <button onclick="exportToExcel()" class="w-full text-left px-6 py-4 text-[10px] font-black uppercase text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors flex items-center gap-4">
                            <i class="fas fa-file-excel text-emerald-500 text-sm"></i> Format Excel (.xlsx)
                        </button>
                        <button onclick="exportToPDF()" class="w-full text-left px-6 py-4 text-[10px] font-black uppercase text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors flex items-center gap-4 border-t border-slate-100 dark:border-slate-700">
                            <i class="fas fa-file-pdf text-red-500 text-sm"></i> Format PDF (.pdf)
                        </button>
                    </div>
                </div>

            </div>
        </div>


        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 " >
            
            <div class="lg:col-span-2 card-stats p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-slate-100 uppercase tracking-widest">Tren Insiden Kerusakan</h3>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase mt-1">Aktivitas pelaporan dalam 30 hari terakhir</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-pelindo dark:text-blue-400 border border-blue-100 dark:border-blue-800">
                        <i class="fas fa-wave-square"></i>
                    </div>
                </div>
                <div class="h-80 w-full">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <div class="card-stats p-8">
                <div class="mb-8">
                    <h3 class="text-sm font-black text-slate-800 dark:text-slate-100 uppercase tracking-widest text-center">Rasio Kesiapan</h3>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase mt-1 text-center">Persentase Kesiapan Aset</p>
                </div>
                <div class="h-64 w-full flex items-center justify-center relative">
                    <canvas id="pieChart"></canvas>
                </div>
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div class="bg-emerald-50 dark:bg-emerald-900/20 p-3 rounded-xl border border-emerald-100 dark:border-emerald-800 text-center">
                        <p class="text-[9px] font-black text-emerald-600 dark:text-emerald-400 uppercase">Tersedia</p>
                        <p class="text-lg font-black text-emerald-700 dark:text-emerald-500">{{ array_sum($chartData['ready']) }}</p>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900/20 p-3 rounded-xl border border-red-100 dark:border-red-800 text-center">
                        <p class="text-[9px] font-black text-red-600 dark:text-red-400 uppercase">Rusak</p>
                        <p class="text-lg font-black text-red-700 dark:text-red-500">{{ array_sum($chartData['breakdown']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-stats p-8 " >
            <div class="mb-8">
                <h3 class="text-sm font-black text-slate-800 dark:text-slate-100 uppercase tracking-widest">
                    {{ $user->role === 'superadmin' ? 'Perbandingan Performa Antar Terminal' : 'Statistik Kesiapan per Kategori' }}
                </h3>
            </div>
            <div class="h-96 w-full">
                <canvas id="barChart"></canvas>
            </div>
        </div>

        <div class="card-stats overflow-hidden shadow-sm" >
            <div class="px-8 py-6 bg-[#00152b] dark:bg-[#000d1a] border-b border-slate-800 flex items-center gap-4">
                <i class="fas fa-book-spells text-blue-400"></i>
                <h3 class="text-white font-black uppercase tracking-widest text-sm leading-none">Buku Log Pemeliharaan Alat</h3>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($infrastructures as $item)
                    <div x-data="{ open: false }" class="group">
                        <button @click="open = !open" class="w-full px-8 py-5 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                            <div class="flex items-center gap-6">
                                <div class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800 text-pelindo dark:text-blue-400 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-lg group-hover:bg-pelindo group-hover:text-white group-hover:border-pelindo dark:group-hover:bg-blue-600 transition-all">
                                    <i class="fas {{ $item->category == 'equipment' ? 'fa-truck' : 'fa-building-columns' }}"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-[10px] font-black text-pelindo dark:text-blue-400 uppercase tracking-widest">{{ $item->code_name }}</p>
                                    <h4 class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $item->type }}</h4>
                                </div>
                            </div>
                            <div class="flex items-center gap-6">
                                <div class="hidden md:block text-right">
                                    <p class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Total Laporan</p>
                                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $item->breakdownLogs->count() }} Kali</p>
                                </div>
                                <div class="w-8 h-8 rounded-full border border-slate-200 dark:border-slate-700 flex items-center justify-center transition-all" :class="open ? 'bg-pelindo text-white rotate-180' : 'text-slate-400 dark:text-slate-500'">
                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                </div>
                            </div>
                        </button>
                        
                        <div x-show="open" x-collapse>
                            <div class="px-8 pb-8 bg-slate-50/50 dark:bg-slate-900/30">
                                <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm">
                                    <table class="w-full text-left text-xs">
                                        <thead class="bg-slate-50 dark:bg-slate-900 border-b border-slate-100 dark:border-slate-700 text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                                            <tr>
                                                <th class="px-6 py-4">Tgl Kejadian</th>
                                                <th class="px-6 py-4">Rincian Kendala</th>
                                                <th class="px-6 py-4">Status Terakhir</th>
                                                <th class="px-6 py-4">Vendor / PIC</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                                            @forelse($item->breakdownLogs as $log)
                                            <tr>
                                                <td class="px-6 py-4 font-bold text-slate-600 dark:text-slate-300">{{ $log->created_at->format('d/m/Y') }}</td>
                                                <td class="px-6 py-4 text-slate-500 dark:text-slate-400">{{ $log->issue_detail }}</td>
                                                <td class="px-6 py-4">
                                                    @php
                                                        $statusConfig = [
                                                            'reported' => 'Dilaporkan',
                                                            'order_part' => 'Menunggu Suku Cadang',
                                                            'on_progress' => 'Sedang Diperbaiki',
                                                            'resolved' => 'Selesai'
                                                        ];
                                                        $label = $statusConfig[$log->repair_status] ?? $log->repair_status;
                                                    @endphp
                                                    <span class="px-2 py-1 rounded bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-[9px] font-black uppercase">{{ $label }}</span>
                                                </td>
                                                <td class="px-6 py-4 font-bold text-pelindo dark:text-blue-400 uppercase">{{ $log->vendor_pic }}</td>
                                            </tr>
                                            @empty
                                            <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400 dark:text-slate-500 italic">Belum ada riwayat perbaikan.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div id="hiddenExportTable">
        <div style="text-align: center; border-bottom: 2px solid #0055a4; padding-bottom: 20px; margin-bottom: 30px;">
            <h1 style="color: #0055a4; margin: 0; font-size: 24px;">PELINDO REGIONAL 2</h1>
            <h2 style="margin: 5px 0; font-size: 16px;">LAPORAN HISTORI PEMELIHARAAN INFRASTRUKTUR</h2>
            <p style="font-size: 10px; color: #666;">Generated on: {{ now()->format('d F Y H:i') }} | By: {{ Auth::user()->name }}</p>
        </div>

        <table id="excelDataTable" border="1" style="width: 100%; border-collapse: collapse; font-size: 10px;">
            <thead style="background-color: #0055a4; color: white;">
                <tr>
                    <th style="padding: 10px;">UNIT</th>
                    <th style="padding: 10px;">CABANG</th>
                    <th style="padding: 10px;">TGL RUSAK</th>
                    <th style="padding: 10px;">DETAIL KENDALA</th>
                    <th style="padding: 10px;">STATUS AKHIR</th>
                    <th style="padding: 10px;">PIC/VENDOR</th>
                </tr>
            </thead>
            <tbody>
                @foreach($infrastructures as $item)
                    @foreach($item->breakdownLogs as $log)
                    <tr>
                        <td style="padding: 8px;">{{ $item->code_name }} ({{ $item->type }})</td>
                        <td style="padding: 8px;">{{ $item->entity->name }}</td>
                        <td style="padding: 8px;">{{ $log->created_at->format('d/m/Y') }}</td>
                        <td style="padding: 8px;">{{ $log->issue_detail }}</td>
                        <td style="padding: 8px;">{{ strtoupper($log->repair_status) }}</td>
                        <td style="padding: 8px;">{{ $log->vendor_pic }}</td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const data = @json($chartData);
            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : '#f1f5f9';
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const primaryColor = isDark ? '#60a5fa' : '#0055a4';

            // 1. TREN CHART (LINE)
            new Chart(document.getElementById('trendChart'), {
                type: 'line',
                data: {
                    labels: data.trendLabels,
                    datasets: [{
                        label: 'Jumlah Laporan Kerusakan',
                        data: data.trendCounts,
                        borderColor: primaryColor,
                        backgroundColor: isDark ? 'rgba(96, 165, 250, 0.05)' : 'rgba(0, 85, 164, 0.05)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: isDark ? '#1e293b' : '#fff',
                        pointBorderColor: primaryColor,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark ? '#1e293b' : '#fff',
                            titleColor: isDark ? '#f1f5f9' : '#1e293b',
                            bodyColor: isDark ? '#94a3b8' : '#64748b',
                            borderColor: isDark ? '#334155' : '#e2e8f0',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: gridColor }, 
                            ticks: { stepSize: 1, color: textColor } 
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { color: textColor }
                        }
                    }
                }
            });

            // 2. STACKED BAR CHART
            new Chart(document.getElementById('barChart'), {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [
                        { label: 'Ready', data: data.ready, backgroundColor: '#10b981', barThickness: isDark ? 25 : 40, borderRadius: 4 },
                        { label: 'Down', data: data.breakdown, backgroundColor: '#ef4444', barThickness: isDark ? 25 : 40, borderRadius: 4 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: { color: textColor, font: { weight: 'bold', size: 10 } }
                        }
                    },
                    scales: {
                        y: { 
                            stacked: true, 
                            grid: { color: gridColor },
                            ticks: { color: textColor }
                        },
                        x: { 
                            stacked: true, 
                            grid: { display: false },
                            ticks: { color: textColor }
                        }
                    }
                }
            });

            // 3. PIE CHART
            new Chart(document.getElementById('pieChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Ready', 'Down'],
                    datasets: [{
                        data: [data.ready.reduce((a,b)=>a+b,0), data.breakdown.reduce((a,b)=>a+b,0)],
                        backgroundColor: ['#10b981', '#ef4444'],
                        borderWidth: isDark ? 2 : 0,
                        borderColor: isDark ? '#1e293b' : '#fff'
                    }]
                },
                options: {
                    cutout: '75%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { 
                            position: 'bottom',
                            labels: { color: textColor, font: { weight: 'bold', size: 10 } }
                        } 
                    }
                }
            });
        });

        // EXPORT FUNCTIONS
        function exportToExcel() {
            const table = document.getElementById('excelDataTable');
            const wb = XLSX.utils.table_to_book(table);
            XLSX.writeFile(wb, "Analytics_Report_Pelindo_{{ date('dMY') }}.xlsx");
        }

        function exportToPDF() {
            const element = document.getElementById('hiddenExportTable');
            element.style.display = 'block';
            
            const opt = {
                margin: 0.5,
                filename: 'Analytics_Report_Pelindo.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' }
            };

            html2pdf().set(opt).from(element).save().then(() => {
                element.style.display = 'none';
            });
        }
    </script>
</x-app-layout>
