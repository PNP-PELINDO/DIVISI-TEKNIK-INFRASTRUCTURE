<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fa;
        }

        [x-cloak] { display: none !important; }

        .animate-fade {
            animation: fadeIn 0.6s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Penyempurnaan Card Asset agar teks tidak bentrok */
        .asset-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .asset-card:hover {
            box-shadow: 0 12px 20px -5px rgba(0, 51, 102, 0.1);
            border-color: #0055a4;
            transform: translateY(-4px);
        }

        .asset-img-box {
            width: 100%;
            aspect-ratio: 4/3;
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        /* Mengunci tinggi judul agar sejajar dan tidak merusak layout */
        .asset-title-wrapper {
            min-height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 10px;
            border-radius: 6px;
            margin-top: 6px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-available { background-color: #ecfdf5; border: 1px solid #d1fae5; color: #065f46; }
        .stat-available .badge { background-color: #10b981; color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px; }

        .stat-breakdown { background-color: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; }
        .stat-breakdown .badge { background-color: #ef4444; color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px; }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>

    @php
        // Mengelompokkan data berdasarkan Entitas -> Lalu berdasarkan Tipe Alat
        $groupedEquipment = isset($equipment) ? $equipment->groupBy(fn($i) => $i->entity->name ?? 'Unknown Entity') : collect();
        $groupedFacility = isset($facility) ? $facility->groupBy(fn($i) => $i->entity->name ?? 'Unknown Entity') : collect();
        $groupedUtility = isset($utility) ? $utility->groupBy(fn($i) => $i->entity->name ?? 'Unknown Entity') : collect();

        // Data untuk Chart
        $countEq = isset($equipment) ? $equipment->count() : 0;
        $countFac = isset($facility) ? $facility->count() : 0;
        $countUtil = isset($utility) ? $utility->count() : 0;
    @endphp

    <div class="max-w-[1600px] mx-auto w-full space-y-8 pb-16">

        <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm flex flex-col xl:flex-row items-center justify-between gap-8 animate-fade">
            
            <div class="flex flex-col sm:flex-row items-center text-center sm:text-left gap-6 w-full xl:w-auto">
                <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <img src="{{ asset('danantara.png') }}" alt="Danantara" class="h-10 md:h-12 object-contain">
                    <div class="w-px h-10 bg-slate-300"></div>
                    <img src="{{ asset('pelindo.png') }}" alt="Pelindo" class="h-10 md:h-12 object-contain">
                </div>
            </div>

            <div class="flex flex-wrap justify-center gap-3 w-full xl:w-auto">
                <a href="{{ route('admin.infrastructures.create') }}" class="bg-[#003366] hover:bg-[#001e3c] text-white px-5 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-900/20 transition-all flex items-center gap-2">
                    <i class="fas fa-plus"></i> Aset Baru
                </a>
                <a href="{{ route('admin.breakdowns.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-900/20 transition-all flex items-center gap-2">
                    <i class="fas fa-triangle-exclamation"></i> Lapor Kerusakan
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade" style="animation-delay: 100ms;">
            
            <div class="space-y-6">
                <div class="bg-white border border-slate-200 p-6 rounded-2xl flex items-center justify-between shadow-sm">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Inventaris</p>
                        <p class="text-3xl font-black text-[#003366] mt-1">{{ $stats['total'] ?? 0 }} <span class="text-sm text-slate-500 font-bold">Unit</span></p>
                    </div>
                    <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 text-xl border border-slate-100"><i class="fas fa-boxes-stacked"></i></div>
                </div>
                
                <div class="bg-white border border-slate-200 p-6 rounded-2xl flex items-center justify-between shadow-sm border-l-4 border-l-emerald-500">
                    <div>
                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Aset Beroperasi</p>
                        <p class="text-3xl font-black text-slate-800 mt-1">{{ $stats['available'] ?? 0 }} <span class="text-sm text-slate-500 font-bold">Unit</span></p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500 text-xl"><i class="fas fa-check-circle"></i></div>
                </div>

                <div class="bg-white border border-slate-200 p-6 rounded-2xl flex items-center justify-between shadow-sm border-l-4 border-l-red-500">
                    <div>
                        <p class="text-[10px] font-black text-red-600 uppercase tracking-widest">Breakdown / Rusak</p>
                        <p class="text-3xl font-black text-slate-800 mt-1">{{ $stats['breakdown'] ?? 0 }} <span class="text-sm text-slate-500 font-bold">Unit</span></p>
                    </div>
                    <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-red-500 text-xl"><i class="fas fa-engine-warning"></i></div>
                </div>
            </div>

            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm flex flex-col justify-center">
                    <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest text-center mb-4">Rasio Kesiapan Alat (Readiness)</h3>
                    <div class="relative h-48 w-full flex items-center justify-center">
                        <canvas id="healthChart"></canvas>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm flex flex-col justify-center">
                    <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest text-center mb-4">Distribusi Kategori Aset</h3>
                    <div class="relative h-48 w-full">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div x-data="{ tab: 'equipment' }" class="space-y-6 animate-fade" style="animation-delay: 200ms;">
            
            <div class="flex items-center gap-2 border-b border-slate-300 pb-px overflow-x-auto hide-scroll">
                <button @click="tab = 'equipment'" :class="tab === 'equipment' ? 'border-[#003366] text-[#003366] bg-white shadow-[0_-4px_6px_-4px_rgba(0,0,0,0.1)]' : 'border-transparent text-slate-500 hover:text-slate-700 bg-slate-50'" class="px-6 py-3.5 text-xs font-black uppercase tracking-widest border-t-2 border-x-2 rounded-t-xl transition-all whitespace-nowrap">
                    <i class="fas fa-truck-loading mr-2"></i> Peralatan ({{ $countEq }})
                </button>
                <button @click="tab = 'facility'" :class="tab === 'facility' ? 'border-[#003366] text-[#003366] bg-white shadow-[0_-4px_6px_-4px_rgba(0,0,0,0.1)]' : 'border-transparent text-slate-500 hover:text-slate-700 bg-slate-50'" class="px-6 py-3.5 text-xs font-black uppercase tracking-widest border-t-2 border-x-2 rounded-t-xl transition-all whitespace-nowrap">
                    <i class="fas fa-building mr-2"></i> Fasilitas ({{ $countFac }})
                </button>
                <button @click="tab = 'utility'" :class="tab === 'utility' ? 'border-[#003366] text-[#003366] bg-white shadow-[0_-4px_6px_-4px_rgba(0,0,0,0.1)]' : 'border-transparent text-slate-500 hover:text-slate-700 bg-slate-50'" class="px-6 py-3.5 text-xs font-black uppercase tracking-widest border-t-2 border-x-2 rounded-t-xl transition-all whitespace-nowrap">
                    <i class="fas fa-bolt mr-2"></i> Utilitas ({{ $countUtil }})
                </button>
            </div>

            <div x-show="tab === 'equipment'" x-cloak class="space-y-8">
                @forelse($groupedEquipment as $entityName => $items)
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="bg-[#003366] px-6 py-4 text-white flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                            <h3 class="font-black text-sm uppercase tracking-widest">{{ $entityName }}</h3>
                        </div>
                        
                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                            @foreach($items->groupBy('type') as $type => $typeItems)
                                @php
                                    $available = $typeItems->where('status', 'available')->count();
                                    $breakdown = $typeItems->where('status', 'breakdown')->count();
                                @endphp
                                <div class="asset-card">
                                    <div class="asset-img-box">
                                        <i class="fas fa-truck-monster text-4xl text-slate-300"></i>
                                    </div>
                                    <div class="asset-title-wrapper">
                                        <h4 class="text-[10px] font-black text-slate-800 uppercase text-center line-clamp-2 leading-snug" title="{{ $type }}">
                                            {{ $type }}
                                        </h4>
                                    </div>
                                    <div class="mt-auto border-t border-slate-100 pt-3">
                                        <div class="stat-row stat-available">
                                            <span>Available</span>
                                            <span class="badge">{{ $available }}</span>
                                        </div>
                                        <div class="stat-row stat-breakdown">
                                            <span>Breakdown</span>
                                            <span class="badge">{{ $breakdown }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="p-16 text-center bg-white border border-slate-200 rounded-2xl">
                        <i class="fas fa-box-open text-4xl text-slate-300 mb-3"></i>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Tidak ada data peralatan yang terdaftar</p>
                    </div>
                @endforelse
            </div>

            <div x-show="tab === 'facility'" x-cloak class="space-y-8">
                @forelse($groupedFacility as $entityName => $items)
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="bg-[#003366] px-6 py-4 text-white flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                            <h3 class="font-black text-sm uppercase tracking-widest">{{ $entityName }}</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                            @foreach($items->groupBy('type') as $type => $typeItems)
                                @php
                                    $available = $typeItems->where('status', 'available')->count();
                                    $breakdown = $typeItems->where('status', 'breakdown')->count();
                                @endphp
                                <div class="asset-card">
                                    <div class="asset-img-box">
                                        <i class="fas fa-warehouse text-4xl text-slate-300"></i>
                                    </div>
                                    <div class="asset-title-wrapper">
                                        <h4 class="text-[10px] font-black text-slate-800 uppercase text-center line-clamp-2 leading-snug">{{ $type }}</h4>
                                    </div>
                                    <div class="mt-auto border-t border-slate-100 pt-3">
                                        <div class="stat-row stat-available">
                                            <span>Ready</span>
                                            <span class="badge">{{ $available }}</span>
                                        </div>
                                        <div class="stat-row stat-breakdown">
                                            <span>Issue</span>
                                            <span class="badge">{{ $breakdown }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="p-16 text-center bg-white border border-slate-200 rounded-2xl">
                        <i class="fas fa-building text-4xl text-slate-300 mb-3"></i>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Tidak ada data fasilitas</p>
                    </div>
                @endforelse
            </div>

            <div x-show="tab === 'utility'" x-cloak class="space-y-8">
                @forelse($groupedUtility as $entityName => $items)
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="bg-[#003366] px-6 py-4 text-white flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                            <h3 class="font-black text-sm uppercase tracking-widest">{{ $entityName }}</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                            @foreach($items->groupBy('type') as $type => $typeItems)
                                @php
                                    $available = $typeItems->where('status', 'available')->count();
                                    $breakdown = $typeItems->where('status', 'breakdown')->count();
                                @endphp
                                <div class="asset-card">
                                    <div class="asset-img-box">
                                        <i class="fas fa-bolt text-4xl text-slate-300"></i>
                                    </div>
                                    <div class="asset-title-wrapper">
                                        <h4 class="text-[10px] font-black text-slate-800 uppercase text-center line-clamp-2 leading-snug">{{ $type }}</h4>
                                    </div>
                                    <div class="mt-auto border-t border-slate-100 pt-3">
                                        <div class="stat-row stat-available">
                                            <span>Active</span>
                                            <span class="badge">{{ $available }}</span>
                                        </div>
                                        <div class="stat-row stat-breakdown">
                                            <span>Down</span>
                                            <span class="badge">{{ $breakdown }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="p-16 text-center bg-white border border-slate-200 rounded-2xl">
                        <i class="fas fa-plug text-4xl text-slate-300 mb-3"></i>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Tidak ada data utilitas</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm animate-fade" style="animation-delay: 300ms;">
            <div class="px-6 py-5 bg-[#00152b] flex items-center justify-between">
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
                        <tr class="bg-[#002244] text-slate-300 text-[10px] font-black uppercase tracking-[0.2em]">
                            <th class="px-8 py-5 w-16 text-center">NO</th>
                            <th class="px-8 py-5">Entitas Pelabuhan</th>
                            <th class="px-8 py-5">Identitas Alat</th>
                            <th class="px-8 py-5">Ringkasan Kendala</th>
                            <th class="px-8 py-5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium">
                        @forelse($recent_logs ?? [] as $index => $log)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-8 py-5 text-center text-slate-400 font-bold">{{ $index + 1 }}</td>
                            <td class="px-8 py-5 text-slate-600 uppercase font-bold">{{ $log->infrastructure->entity->name ?? '-' }}</td>
                            <td class="px-8 py-5">
                                <span class="text-[#003366] font-black uppercase bg-blue-50 px-3 py-1.5 rounded border border-blue-100">{{ $log->infrastructure->code_name ?? '-' }}</span>
                            </td>
                            <td class="px-8 py-5 text-slate-600 max-w-[200px] truncate" title="{{ $log->issue_detail }}">{{ $log->issue_detail }}</td>
                            <td class="px-8 py-5 text-center">
                                @if($log->repair_status == 'order_part')
                                    <span class="bg-[#ef4444] text-white text-[9px] font-black px-3 py-1.5 rounded-md uppercase tracking-widest shadow-sm">Order Part</span>
                                @elseif($log->repair_status == 'on_progress')
                                    <span class="bg-[#f59e0b] text-white text-[9px] font-black px-3 py-1.5 rounded-md uppercase tracking-widest shadow-sm">On Progress</span>
                                @else
                                    <span class="bg-slate-200 text-slate-600 text-[9px] font-black px-3 py-1.5 rounded-md uppercase tracking-widest">{{ str_replace('_', ' ', $log->repair_status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center text-slate-400">
                                <span class="text-[10px] font-black uppercase tracking-widest"><i class="fas fa-check-circle mr-2 text-emerald-400"></i> Tidak ada laporan kerusakan alat saat ini.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data untuk Chart Kesiapan (Doughnut)
            const healthCtx = document.getElementById('healthChart').getContext('2d');
            new Chart(healthCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Beroperasi (Ready)', 'Breakdown (Down)'],
                    datasets: [{
                        data: [{{ $stats['available'] ?? 0 }}, {{ $stats['breakdown'] ?? 0 }}],
                        backgroundColor: ['#10b981', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { family: "'Plus Jakarta Sans', sans-serif", size: 10, weight: 'bold' } } }
                    }
                }
            });

            // Data untuk Chart Distribusi Kategori (Bar)
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'bar',
                data: {
                    labels: ['Peralatan', 'Fasilitas', 'Utilitas'],
                    datasets: [{
                        label: 'Total Unit',
                        data: [{{ $countEq }}, {{ $countFac }}, {{ $countUtil }}],
                        backgroundColor: '#0055a4',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
</x-app-layout>
