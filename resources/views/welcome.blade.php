<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Infrastruktur | Pelindo Regional Group</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Tipografi Standar Korporat */
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --pelindo-primary: #003366;
            --pelindo-secondary: #0055a4;
            --pelindo-dark: #001a33;
            --bg-body: #eff3f8;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: #1e293b;
            /* Subtle corporate pattern */
            background-image: radial-gradient(#d1d5db 1px, transparent 1px);
            background-size: 32px 32px;
        }

        /* --- SISTEM ANIMASI KORPORAT --- */
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .animate-fade-up {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }

        .animate-fade {
            animation: fadeIn 0.8s ease-out forwards;
            opacity: 0;
        }

        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }

        /* --- SIDEBAR --- */
        .sidebar-transition {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @media (max-width: 1023px) {
            #main-sidebar { transform: translateX(-100%); }
            #main-sidebar.show { transform: translateX(0); }
        }
        
        .sidebar-hidden { margin-left: -16rem; }

        /* --- KARTU ASET (STYLE SESUAI GAMBAR) --- */
        .asset-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .asset-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.1);
            border-color: #cbd5e1;
        }

        .asset-image-placeholder {
            width: 100%;
            aspect-ratio: 4/3;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden; /* Tambahan wajib agar gambar tidak keluar batas lengkungan */
        }

        .asset-title {
            color: var(--pelindo-primary);
            font-size: 11px;
            font-weight: 900;
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 16px;
            min-height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1.3;
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            border-radius: 6px;
            margin-bottom: 8px;
            border: 1px solid transparent;
        }

        .stat-row:last-child { margin-bottom: 0; }

        .stat-available {
            background-color: #f0fdf4;
            border-color: #dcfce7;
        }
        
        .stat-available .stat-label { color: #475569; }
        .stat-available .stat-value {
            background-color: #10b981;
            color: white;
        }

        .stat-breakdown {
            background-color: #fef2f2;
            border-color: #fee2e2;
        }

        .stat-breakdown .stat-label { color: #475569; }
        .stat-breakdown .stat-value {
            background-color: #ef4444;
            color: white;
        }

        .stat-label {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-value {
            font-size: 11px;
            font-weight: 900;
            padding: 2px 8px;
            border-radius: 4px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--pelindo-primary); }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <div id="mobile-overlay" class="fixed inset-0 bg-slate-900/60 z-40 hidden lg:hidden transition-opacity duration-300 opacity-0" onclick="toggleSidebar()"></div>

    <aside id="main-sidebar" class="sidebar-transition w-64 bg-slate-900 text-slate-300 flex flex-col fixed lg:relative inset-y-0 left-0 z-50 shadow-2xl shrink-0">
        
        <div class="h-20 border-b border-white/10 flex flex-col justify-center px-6">
            <span class="text-[10px] font-black tracking-widest text-slate-400 uppercase leading-none">Main Menu</span>
            <span class="text-[8px] font-bold text-slate-500 uppercase mt-1">Sistem Navigasi</span>
        </div>

        <div class="flex-1 overflow-y-auto py-6 space-y-8">
            <nav class="space-y-1">
                <a href="#" class="flex items-center gap-4 px-6 py-3 bg-white/10 text-white border-l-4 border-blue-500 font-bold transition-all">
                    <i class="fas fa-chart-pie text-sm w-4 text-center text-blue-400"></i>
                    <span class="text-xs uppercase tracking-wider">Dashboard</span>
                </a>
                <a href="{{ route('admin.infrastructures.index') }}" class="flex items-center gap-4 px-6 py-3 hover:bg-white/5 hover:text-white transition-all font-semibold">
                    <i class="fas fa-boxes text-sm w-4 text-center text-slate-500"></i>
                    <span class="text-xs uppercase tracking-wider">Inventaris</span>
                </a>
                <a href="{{ route('admin.breakdowns.index') }}" class="flex items-center gap-4 px-6 py-3 hover:bg-white/5 hover:text-white transition-all font-semibold">
                    <i class="fas fa-clipboard-check text-sm w-4 text-center text-slate-500"></i>
                    <span class="text-xs uppercase tracking-wider">Log Laporan</span>
                </a>
            </nav>
        </div>

        <div class="p-6 border-t border-white/10">
            @auth
                <div class="mb-4">
                    <p class="text-[10px] font-bold text-slate-500 uppercase mb-1">User Aktif</p>
                    <p class="text-xs font-black text-white uppercase truncate">{{ Auth::user()->name }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full py-2.5 bg-slate-800 hover:bg-red-900/50 border border-slate-700 hover:border-red-500/50 text-slate-300 hover:text-red-400 text-[10px] font-black rounded-lg transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 w-full py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-black rounded-lg transition-all uppercase tracking-widest shadow-lg">
                    <i class="fas fa-lock"></i> Autentikasi Admin
                </a>
            @endauth
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto relative z-10 sidebar-transition">
        
        <header class="bg-white/90 backdrop-blur-sm sticky top-0 z-40 px-6 py-4 flex items-center justify-between border-b border-slate-200 shadow-sm">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="w-9 h-9 flex items-center justify-center rounded bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors border border-slate-200">
                    <i id="toggle-icon" class="fas fa-bars"></i>
                </button>
                <div class="hidden sm:block">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Akses Global</span>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500">
                    <i class="fas fa-user text-xs"></i>
                </div>
            </div>
        </header>

        <div class="p-4 md:p-8 lg:p-10 max-w-[1600px] mx-auto w-full space-y-10">

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 md:p-8 animate-fade-up">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6 md:gap-4">
                    
                    <div class="w-full md:w-1/4 flex justify-center md:justify-start">
                        <img src="{{ asset('danantara.png') }}" alt="Danantara Indonesia" class="h-10 md:h-12 lg:h-14 object-contain">
                    </div>
                    
                    <div class="w-full md:w-2/4 text-center flex flex-col items-center">
                        <h1 class="text-xl md:text-2xl lg:text-3xl font-black text-[#003366] uppercase tracking-wide leading-tight">
                            Dashboard Infrastructure<br class="hidden md:block"> Availability
                        </h1>
                        <h2 class="text-xs md:text-sm font-extrabold text-[#0055a4] uppercase mt-2 tracking-widest">
                            Pelindo Regional Group
                        </h2>
                        
                        <div class="mt-4 bg-[#0055a4] text-white px-5 py-1.5 rounded-full text-[10px] md:text-xs font-bold tracking-widest shadow-md flex items-center gap-2">
                            <span>Last Update :</span>
                            <span>{{ now()->format('d-m-Y H:i') }}</span>
                        </div>
                    </div>

                    <div class="w-full md:w-1/4 flex justify-center md:justify-end">
                        <img src="{{ asset('pelindo.png') }}" alt="Pelindo" class="h-10 md:h-12 lg:h-14 object-contain">
                    </div>

                </div>
            </div>

            @forelse ($entities ?? [] as $index => $entity)
            <section class="animate-fade-up delay-{{ ($index % 3 + 1) * 100 }}">
                
                <div class="bg-[#003366] text-white px-6 py-4 rounded-t-xl border-b-4 border-[#0055a4] shadow-sm flex items-center justify-between">
                    <h3 class="font-black text-sm md:text-base uppercase tracking-widest">{{ $entity->name }}</h3>
                    <span class="text-[10px] font-bold text-blue-200 uppercase bg-black/20 px-3 py-1 rounded-full border border-white/10 hidden sm:inline-block">
                        Total Unit: {{ $entity->infrastructures->count() }}
                    </span>
                </div>

                <div class="bg-white border-x border-b border-slate-200 rounded-b-xl p-6 shadow-sm">
                    
                    @if($entity->infrastructures->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5 md:gap-6">
                            
                            @foreach ($entity->infrastructures->groupBy('type') as $type => $items)
                                @php
                                    $availableCount = $items->where('status', 'available')->count();
                                    $breakdownCount = $items->where('status', 'breakdown')->count();
                                    
                                    // Mencari item pertama di grup ini yang memiliki gambar (tidak null)
                                    $representativeItem = $items->whereNotNull('image')->first();
                                @endphp
                                
                                <div class="asset-card">
                                    <div class="asset-image-placeholder">
                                        {{-- LOGIKA PENAMPIL GAMBAR --}}
                                        @if($representativeItem && $representativeItem->image)
                                            <img src="{{ asset('storage/' . $representativeItem->image) }}" alt="{{ $type }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-truck-loading text-4xl text-slate-300"></i>
                                        @endif
                                    </div>

                                    <h4 class="asset-title" title="{{ $type }}">{{ $type }}</h4>
                                    
                                    <div class="mt-auto">
                                        <div class="stat-row stat-available">
                                            <span class="stat-label">Available</span>
                                            <span class="stat-value">{{ $availableCount }}</span>
                                        </div>
                                        <div class="stat-row stat-breakdown">
                                            <span class="stat-label">Breakdown</span>
                                            <span class="stat-value">{{ $breakdownCount }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    @else
                        <div class="py-12 flex flex-col items-center justify-center text-slate-400">
                            <i class="fas fa-boxes text-4xl mb-4 text-slate-200"></i>
                            <p class="font-bold text-xs uppercase tracking-widest">Belum ada data infrastruktur</p>
                        </div>
                    @endif

                </div>
            </section>
            @empty
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-20 flex flex-col items-center justify-center text-center animate-fade">
                <i class="fas fa-database text-6xl text-slate-200 mb-6"></i>
                <h3 class="text-xl font-black text-[#003366] uppercase tracking-tight">Koneksi Database Berhasil</h3>
                <p class="text-slate-500 text-sm font-medium mt-2">Sistem belum mendeteksi entitas maupun infrastruktur.<br>Silakan sinkronisasi data operasional.</p>
            </div>
            @endforelse


            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden animate-fade-up delay-400">
                
                <div class="bg-[#003366] px-6 py-4 flex items-center border-b-2 border-slate-200">
                    <div class="flex items-center gap-3 text-white">
                        <i class="fas fa-clipboard-list text-yellow-400"></i>
                        <h3 class="font-black text-sm uppercase tracking-widest leading-none">Breakdown Detail</h3>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap sm:whitespace-normal">
                        <thead>
                            <tr class="bg-[#0055a4] text-white text-[10px] font-black uppercase tracking-widest">
                                <th class="px-6 py-4 w-16">No</th>
                                <th class="px-6 py-4">Pelindo Group Entity</th>
                                <th class="px-6 py-4">Infrastructure</th>
                                <th class="px-6 py-4">Breakdown Detail</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">PIC</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-sm">
                            @forelse ($breakdowns ?? [] as $index => $log)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-slate-500 font-bold text-xs">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-slate-600 text-xs uppercase">{{ $log->infrastructure->entity->name ?? '-' }}</td>
                                <td class="px-6 py-4 font-black text-[#003366] text-xs uppercase">{{ $log->infrastructure->code_name ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-600 font-medium text-xs">{{ $log->issue_detail }}</td>
                                <td class="px-6 py-4">
                                    @if($log->repair_status == 'order_part')
                                        <span class="bg-[#ef4444] text-white text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-sm">Order Part</span>
                                    @elseif($log->repair_status == 'on_progress')
                                        <span class="bg-[#f59e0b] text-white text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-sm">Reparation</span>
                                    @else
                                        <span class="bg-slate-200 text-slate-600 text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest">{{ $log->repair_status }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-bold text-xs uppercase">{{ $log->vendor_pic ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tidak ada laporan kerusakan alat (Breakdown) saat ini.</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <footer class="p-8 text-center bg-white border-t border-slate-200 mt-auto">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">
                &copy; {{ date('Y') }} Danantara Indonesia. All Rights Reserved.
            </p>
        </footer>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const overlay = document.getElementById('mobile-overlay');
            const icon = document.getElementById('toggle-icon');
            const isMobile = window.innerWidth < 1024;

            if (isMobile) {
                sidebar.classList.toggle('show');
                if (overlay.classList.contains('hidden')) {
                    overlay.classList.remove('hidden');
                    setTimeout(() => overlay.classList.remove('opacity-0'), 10);
                } else {
                    overlay.classList.add('opacity-0');
                    setTimeout(() => overlay.classList.add('hidden'), 300);
                }
            } else {
                sidebar.classList.toggle('sidebar-hidden');
            }
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                document.getElementById('mobile-overlay').classList.add('hidden');
                document.getElementById('mobile-overlay').classList.add('opacity-0');
                document.getElementById('main-sidebar').classList.remove('show');
            }
        });
    </script>
</body>
</html>
