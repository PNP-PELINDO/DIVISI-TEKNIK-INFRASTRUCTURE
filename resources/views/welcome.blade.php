<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Portal Infrastruktur | Pelindo Regional Group</title>
    <link rel="icon" href="{{ asset('logo_pelindo.png') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        pelindo: {
                            blue: '#0064a7',
                            cyan: '#58b9e4',
                            navy: '#002d5d',
                        }
                    }
                }
            }
        }
    </script>
    
    <script>
        // Force light mode by default if no preference is saved
        if (localStorage.getItem('dark-mode') === 'true') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --pelindo-primary: #003366;
            --pelindo-secondary: #0055a4;
            --bg-body: #f4f7fb;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: #1e293b;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 30px 30px;
        }

        [x-cloak] { display: none !important; }

        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .{
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }

        .asset-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 12px; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        @media (min-width: 768px) {
            .asset-card { padding: 12px; }
        }

        .asset-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -5px rgba(0, 51, 102, 0.1);
            border-color: var(--pelindo-secondary);
        }

        .asset-image-placeholder {
            width: 100%;
            aspect-ratio: 16/9;
            background: #f8fafc;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            overflow: hidden; 
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 8px;
            border-radius: 8px;
            margin-bottom: 6px;
        }

        @media (min-width: 768px) {
            .stat-row { padding: 8px 12px; margin-bottom: 8px; }
        }

        .stat-available { background-color: #f0fdf4; border: 1px solid #dcfce7; }
        .stat-available .stat-label { color: #166534; }
        .stat-available .stat-value { background-color: #10b981; color: white; }

        .stat-breakdown { background-color: #fef2f2; border: 1px solid #fee2e2; }
        .stat-breakdown .stat-label { color: #991b1b; }
        .stat-breakdown .stat-value { background-color: #ef4444; color: white; }

        .stat-label { font-size: 9px; font-weight: 800; text-transform: uppercase; }
        .stat-value { font-size: 10px; font-weight: 900; padding: 2px 6px; border-radius: 6px; }

        @media (min-width: 768px) {
            .stat-label { font-size: 10px; }
            .stat-value { font-size: 11px; padding: 2px 8px; }
        }

        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Dark Mode Overrides for Custom Classes */
        .dark body {
            background-color: #020617;
            background-image: radial-gradient(#1e293b 1px, transparent 1px);
            color: #f1f5f9;
        }

        .dark .asset-card {
            background-color: #0f172a;
            border-color: #1e293b;
        }

        .dark .asset-card:hover {
            border-color: #38bdf8;
            box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.5);
        }

        .dark .asset-image-placeholder {
            background-color: #1e293b;
        }

        .dark .stat-available { background-color: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); }
        .dark .stat-available .stat-label { color: #34d399; }

        .dark .stat-breakdown { background-color: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.2); }
        .dark .stat-breakdown .stat-label { color: #f87171; }
    </style>
</head>
<body class="min-h-screen flex flex-col animate-fade-up" 
      x-data="{ 
        search: '',
        filter: 'all', 
        filterEntity: 'all',
        showTypeModal: false, 
        selectedTypeTitle: '', 
        selectedTypeEntity: '', 
        selectedTypeItems: [],
        showIncidentModal: false,
        selectedIncident: null,
        openIncidentModal(incident) {
            this.selectedIncident = incident;
            this.showIncidentModal = true;
        }
      }">

    <nav class="bg-white/90 dark:bg-[#001e3c]/90 backdrop-blur-md sticky top-0 z-[70] border-b border-slate-200 dark:border-slate-800 shadow-sm transition-colors duration-300">
        <div class="max-w-[1600px] mx-auto px-4 md:px-10 h-16 md:h-20 flex items-center justify-between">
            <div class="flex items-center gap-3 md:gap-6">
                <!-- Logo Danantara: Dark in Light Mode, White in Dark Mode -->
                <img src="{{ asset('danantara.png') }}" alt="Danantara" class="h-6 md:h-10 object-contain dark:grayscale dark:invert dark:brightness-200 transition-all duration-300">
                
                <div class="w-px h-6 md:h-10 bg-slate-200 dark:bg-slate-700"></div>
                
                <!-- Logo Pelindo: Dark in Light Mode, White in Dark Mode -->
                <img src="{{ asset('pelindo.png') }}" alt="Pelindo" class="h-6 md:h-10 object-contain dark:grayscale dark:invert dark:brightness-200 transition-all duration-300">
            </div>
            <div class="flex items-center gap-3 md:gap-5">
                <!-- Dark Mode Toggle -->
                <button onclick="toggleDarkMode()" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-amber-400 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all border border-slate-200 dark:border-slate-700">
                    <i id="dark-icon" class="fas fa-moon dark:fa-sun"></i>
                </button>

                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 md:px-6 md:py-2.5 bg-[#003366] dark:bg-sky-600 text-white rounded-full text-[10px] md:text-xs font-black uppercase tracking-widest shadow-md transition-all whitespace-nowrap">
                        <i class="fas fa-desktop md:mr-2"></i> <span class="hidden md:inline">Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 md:px-6 md:py-2.5 bg-white dark:bg-slate-800 text-[#003366] dark:text-sky-400 border-2 border-[#003366] dark:border-slate-700 rounded-full text-[10px] md:text-xs font-black uppercase tracking-widest shadow-sm transition-all hover:bg-slate-50 dark:hover:bg-slate-700 whitespace-nowrap">
                        <i class="fas fa-lock md:mr-2"></i> <span class="hidden md:inline">Login</span>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="flex-1 w-full max-w-[1600px] mx-auto px-4 md:px-10 py-8 md:py-10 space-y-10 md:space-y-12">

        <div class="text-center py-4 md:py-8 ">
            <h1 class="text-3xl md:text-5xl font-black text-[#003366] dark:text-white uppercase tracking-tight leading-tight">
                Dasbor Kesiapan <br>
                <span class="text-[#0055a4] dark:text-sky-400">Infrastruktur</span>
            </h1>
            <p class="mt-3 md:mt-4 text-xs md:text-sm font-bold text-slate-500 dark:text-slate-400 tracking-widest uppercase">Pelindo Regional Group</p>
            
            <div class="inline-flex items-center gap-2 md:gap-3 mt-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 px-4 md:px-6 py-2 md:py-3 rounded-full shadow-sm text-[10px] md:text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-widest">
                <span class="relative flex h-2.5 w-2.5 md:h-3 md:w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 md:h-3 md:w-3 bg-emerald-500"></span>
                </span>
                Diperbarui : {{ now()->translatedFormat('l, d F Y - H:i') }}
            </div>

            <!-- SEARCH & ENHANCED FILTERS -->
            <div class="max-w-4xl mx-auto mt-10 space-y-4 px-4">
                <!-- Search Bar -->
                <div class="relative group">
                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#0055a4] dark:group-focus-within:text-sky-400 transition-colors"></i>
                    <input type="text" x-model="search" placeholder="Cari Kode Alat, Nama, atau Jenis..." 
                           class="w-full pl-12 pr-12 py-4 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm focus:ring-2 focus:ring-[#0055a4] dark:focus:ring-sky-500 focus:border-transparent outline-none transition-all text-sm font-bold placeholder:text-slate-400 dark:text-white">
                    <button x-show="search.length > 0" @click="search = ''" class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 transition-colors">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>

                <!-- Filter Pills -->
                <div class="flex flex-wrap items-center justify-center gap-2">
                    <div class="bg-white/80 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-full p-1.5 shadow-sm flex flex-wrap gap-1">
                        <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-[#003366] dark:bg-sky-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'" class="px-4 py-2 rounded-full text-[9px] md:text-[10px] font-black uppercase tracking-widest transition-all">Semua Kategori</button>
                        <button @click="filter = 'equipment'" :class="filter === 'equipment' ? 'bg-[#003366] dark:bg-sky-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'" class="px-4 py-2 rounded-full text-[9px] md:text-[10px] font-black uppercase tracking-widest transition-all">Peralatan</button>
                        <button @click="filter = 'facility'" :class="filter === 'facility' ? 'bg-[#003366] dark:bg-sky-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'" class="px-4 py-2 rounded-full text-[9px] md:text-[10px] font-black uppercase tracking-widest transition-all">Fasilitas</button>
                        <button @click="filter = 'utility'" :class="filter === 'utility' ? 'bg-[#003366] dark:bg-sky-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'" class="px-4 py-2 rounded-full text-[9px] md:text-[10px] font-black uppercase tracking-widest transition-all">Utilitas</button>
                    </div>

                    <div class="bg-white/80 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-full p-1.5 shadow-sm flex items-center gap-2">
                        <i class="fas fa-filter text-[#0055a4] dark:text-sky-400 ml-3 text-xs"></i>
                        <select x-model="filterEntity" class="bg-transparent text-[9px] md:text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 outline-none pr-4 py-1 cursor-pointer">
                            <option value="all">Seluruh Wilayah</option>
                            @foreach($entities as $e)
                                <option value="{{ $e->name }}">{{ $e->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-8 md:space-y-10">
            @forelse ($entities as $index => $entity)
            
            @php
    $availableCategories = $entity->infrastructures->pluck('category')->unique()->values()->toJson();
            @endphp
            
            <section 
                data-entity-id="{{ $entity->id }}"
                data-entity-name="{{ $entity->name }}"
                x-show="((filter === 'all' || {{ $availableCategories }}.includes(filter)) && (filterEntity === 'all' || filterEntity === '{{ $entity->name }}')) && (search === '' || visibleUnits > 0)" 
                x-data="{ 
                    showModal: false,
                    infrastructures: {{ $entity->infrastructures->map(fn($i) => [
                        'code' => strtolower($i->code_name), 
                        'type' => strtolower($i->type), 
                        'category' => $i->category
                    ])->toJson() }},
                    get visibleUnits() {
                        if (search === '' && filter === 'all') return this.infrastructures.length;
                        return this.infrastructures.filter(i => {
                            const matchFilter = filter === 'all' || i.category === filter;
                            const matchSearch = search === '' || i.code.includes(search.toLowerCase()) || i.type.includes(search.toLowerCase());
                            return matchFilter && matchSearch;
                        }).length;
                    }
                }">
                
                <div class="bg-gradient-to-r from-[#003366] to-[#0055a4] dark:from-slate-800 dark:to-slate-900 text-white px-5 py-4 md:px-8 md:py-5 rounded-t-2xl shadow-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <h3 class="font-black text-sm md:text-lg uppercase tracking-widest flex items-center gap-2 md:gap-3">
                        <i class="fas fa-building text-blue-300 dark:text-sky-400"></i> {{ $entity->name }}
                    </h3>
                    
                    <span class="text-[9px] md:text-[10px] font-bold text-blue-100 dark:text-slate-300 uppercase bg-black/20 px-3 py-1 md:px-4 md:py-1.5 rounded-full border border-white/20">
                        Unit Terfilter: <span x-text="visibleUnits"></span> / {{ $entity->infrastructures->count() }}
                    </span>
                </div>

                <div class="bg-white/80 dark:bg-slate-900/50 border-x border-b border-slate-200 dark:border-slate-800 rounded-b-2xl p-4 md:p-8 shadow-sm">
                    @if($entity->infrastructures->count() > 0)
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-8 gap-3 md:gap-4">
                            @foreach ($entity->infrastructures->groupBy('type') as $type => $items)
                                @php
            $availableQty = $items->where('status', 'available')->count();
            $breakdownQty = $items->where('status', 'breakdown')->count();
            $representativeItem = $items->whereNotNull('image')->first();
            $itemCategory = $items->first()->category ?? 'equipment';
                                @endphp
                                
                                <div class="asset-card group cursor-pointer relative" 
                                     data-type="{{ htmlspecialchars($type, ENT_QUOTES) }}"
                                     data-entity="{{ htmlspecialchars($entity->name, ENT_QUOTES) }}"
                                     data-items="{{ json_encode($items->map(function ($i) {
                return ['code' => $i->code_name, 'status' => $i->status]; })->values()) }}"
                                     @click="selectedTypeTitle = $el.dataset.type; selectedTypeEntity = $el.dataset.entity; selectedTypeItems = JSON.parse($el.dataset.items); showTypeModal = true;"
                                     x-show="(filter === 'all' || filter === '{{ $itemCategory }}') && (search === '' || '{{ strtolower($type) }}'.includes(search.toLowerCase()) || '{{ strtolower($entity->name) }}'.includes(search.toLowerCase()) || JSON.parse($el.dataset.items).some(item => item.code.toLowerCase().includes(search.toLowerCase())))">
                                     
                                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                                        <div class="w-6 h-6 bg-white/90 dark:bg-slate-800/90 backdrop-blur rounded-full flex items-center justify-center text-[#0055a4] dark:text-sky-400 shadow-sm border border-slate-200 dark:border-slate-700">
                                            <i class="fas fa-expand-alt text-[10px]"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="asset-image-placeholder relative group overflow-hidden">
                                        @if($representativeItem && $representativeItem->image)
                                            <img src="{{ str_starts_with($representativeItem->image, 'http') ? $representativeItem->image : asset('storage/' . ltrim($representativeItem->image, '/')) }}" 
                                                 onerror="this.onerror=null; this.src='{{ str_starts_with($representativeItem->image, 'http') ? $representativeItem->image : asset(ltrim($representativeItem->image, '/')) }}';"
                                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                                 alt="{{ $type }}">
                                        @else
                                            @if($itemCategory === 'equipment')
                                                <i class="fas fa-truck text-4xl md:text-5xl text-slate-300 transition-transform duration-500 group-hover:scale-110 group-hover:text-blue-400"></i>
                                            @elseif($itemCategory === 'facility')
                                                <i class="fas fa-building text-4xl md:text-5xl text-slate-300 transition-transform duration-500 group-hover:scale-110 group-hover:text-blue-400"></i>
                                            @else
                                                <i class="fas fa-bolt text-4xl md:text-5xl text-slate-300 transition-transform duration-500 group-hover:scale-110 group-hover:text-blue-400"></i>
                                            @endif
                                        @endif
                                    </div>

                                    <h4 class="text-[9px] font-black text-[#003366] dark:text-slate-200 text-center uppercase mb-2 h-6 flex items-center justify-center leading-tight">{{ $type }}</h4>
                                    
                                    <div class="mt-auto border-t border-slate-100 dark:border-slate-800 pt-2 md:pt-3">
                                        <div class="stat-row stat-available">
                                            <span class="stat-label">Tersedia (Ready)</span>
                                            <span class="stat-value">{{ $availableQty }}</span>
                                        </div>
                                        <div class="stat-row stat-breakdown">
                                            <span class="stat-label">Rusak (Down)</span>
                                            <span class="stat-value">{{ $breakdownQty }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 md:mt-8 pt-4 md:pt-6 border-t border-slate-200 dark:border-slate-800 border-dashed text-center">
                            <button @click="showModal = true" class="inline-flex items-center justify-center px-6 py-2.5 bg-slate-50 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-slate-700 text-[#0055a4] dark:text-sky-400 border border-slate-200 dark:border-slate-700 hover:border-blue-200 rounded-full text-[10px] md:text-xs font-black uppercase tracking-widest transition-all">
                                <i class="fas fa-list-ul mr-2"></i> Lihat Semua Alat
                            </button>
                        </div>

                        <div x-show="showModal" class="fixed inset-0 z-[100] flex items-center justify-center px-4 bg-slate-900/60 backdrop-blur-sm transition-opacity" x-cloak>
                            <div @click.away="showModal = false" x-show="showModal" x-transition.scale.origin.bottom class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-4xl max-h-[85vh] flex flex-col shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-800">
                                
                                <div class="bg-[#00152b] dark:bg-slate-950 px-6 py-4 flex items-center justify-between shrink-0">
                                    <div>
                                        <h3 class="text-white font-black uppercase tracking-widest text-sm">Daftar Aset</h3>
                                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mt-1">{{ $entity->name }}</p>
                                    </div>
                                    <button @click="showModal = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-red-500 text-white flex items-center justify-center transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                <div class="flex-1 overflow-y-auto p-6 bg-slate-50 dark:bg-slate-950">
                                    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden overflow-x-auto hide-scrollbar">
                                        <table class="w-full text-left text-xs whitespace-nowrap min-w-[500px]">
                                            <thead class="bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[9px] font-black uppercase tracking-widest border-b border-slate-200 dark:border-slate-700">
                                                <tr>
                                                    <th class="px-5 py-3 w-12 text-center">No</th>
                                                    <th class="px-5 py-3">Kode Alat</th>
                                                    <th class="px-5 py-3">Tipe / Jenis</th>
                                                    <th class="px-5 py-3">Kategori</th>
                                                    <th class="px-5 py-3 text-center">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                                @foreach($entity->infrastructures as $idx => $asset)
                                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                                    <td class="px-5 py-3 text-center font-bold text-slate-400 dark:text-slate-600">{{ $idx + 1 }}</td>
                                                    <td class="px-5 py-3 font-black text-[#003366] dark:text-sky-400 uppercase">{{ $asset->code_name }}</td>
                                                    <td class="px-5 py-3 font-bold text-slate-600 dark:text-slate-300">{{ $asset->type }}</td>
                                                    <td class="px-5 py-3 text-slate-500 dark:text-slate-500 uppercase text-[10px] tracking-widest">{{ $asset->category }}</td>
                                                    <td class="px-5 py-3 text-center">
                                                        @if($asset->status == 'available')
                                                            <span class="px-2 py-1 rounded bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/20 text-[9px] font-black uppercase">Tersedia</span>
                                                        @else
                                                            <span class="px-2 py-1 rounded bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-500/20 text-[9px] font-black uppercase">Rusak</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <p class="text-center text-slate-400 font-bold py-8 md:py-10 uppercase text-[10px] md:text-xs">Belum ada infrastruktur didaftarkan</p>
                    @endif
                </div>
            </section>
            @empty
            <div class="text-center py-16 md:py-20 text-slate-300">
                <i class="fas fa-database text-5xl md:text-6xl mb-4"></i>
                <p class="font-black uppercase text-sm md:text-base">Data Tidak Tersedia</p>
            </div>
            @endforelse
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden mt-10 md:mt-16">
            <div class="bg-[#00152b] dark:bg-slate-950 px-5 py-4 md:px-8 md:py-5 flex items-center justify-between">
                <div class="flex items-center gap-3 md:gap-4 text-white">
                    <i class="fas fa-clipboard-list text-red-500 dark:text-rose-500 text-lg md:text-xl"></i>
                    <h3 class="font-black text-xs md:text-sm uppercase tracking-widest">Log Insiden Aktif</h3>
                </div>
            </div>

            <div class="w-full overflow-x-auto hide-scrollbar">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-[#002244] dark:bg-slate-900 text-slate-300 dark:text-slate-400 text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] whitespace-nowrap">
                            <th class="px-5 py-4 md:px-8 md:py-5 w-16 text-center">NO</th>
                            <th class="px-5 py-4 md:px-8 md:py-5">Entitas Pelabuhan</th>
                            <th class="px-5 py-4 md:px-8 md:py-5 text-center">Identitas Alat</th>
                            <th class="px-5 py-4 md:px-8 md:py-5">Kendala</th>
                            <th class="px-5 py-4 md:px-8 md:py-5 text-center">Status</th>
                            <th class="px-5 py-4 md:px-8 md:py-5">PIC</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 whitespace-nowrap">
                        @forelse ($breakdowns as $index => $log)
                        <tr x-show="(filter === 'all' || filter === '{{ $log->infrastructure->category }}') && (filterEntity === 'all' || filterEntity === '{{ $log->infrastructure->entity->name }}') && (search === '' || '{{ strtolower($log->infrastructure->code_name) }}'.includes(search.toLowerCase()) || '{{ strtolower($log->infrastructure->entity->name ?? '') }}'.includes(search.toLowerCase()) || '{{ strtolower($log->issue_detail) }}'.includes(search.toLowerCase()))"
                            @click='openIncidentModal({{ json_encode($log->only(["id", "issue_detail", "repair_status", "vendor_pic", "created_at"]) + ["infrastructure" => $log->infrastructure->only(["code_name", "type", "category"]) + ["entity" => $log->infrastructure->entity->only(["name"])]]) }})'
                            class="cursor-pointer text-[10px] md:text-xs uppercase font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-5 py-4 md:px-8 md:py-5 text-center text-slate-400 dark:text-slate-600">{{ $index + 1 }}</td>
                            <td class="px-5 py-4 md:px-8 md:py-5">{{ $log->infrastructure->entity->name ?? '-' }}</td>
                            <td class="px-5 py-4 md:px-8 md:py-5 text-center">
                                <span class="bg-blue-50 dark:bg-sky-500/10 text-[#003366] dark:text-sky-400 px-3 py-1.5 rounded border border-blue-100 dark:border-sky-500/20 text-[9px] md:text-[10px] font-black">
                                    {{ $log->infrastructure->code_name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 md:px-8 md:py-5 text-slate-500 dark:text-slate-500 lowercase first-letter:uppercase font-medium italic max-w-xs truncate" title="{{ $log->issue_detail }}">{{ $log->issue_detail }}</td>
                            <td class="px-5 py-4 md:px-8 md:py-5 text-center">
                                @php
                                    $statusConfig = \App\Models\BreakdownLog::getStatusConfig();
                                    $conf = $statusConfig[$log->repair_status] ?? $statusConfig['reported'];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded {{ $conf['bg'] }} {{ $conf['text'] }} border {{ $conf['border'] }} text-[8px] md:text-[9px] font-black uppercase tracking-widest whitespace-nowrap">
                                    <i class="fas {{ $conf['icon'] }}"></i>
                                    {{ $conf['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 md:px-8 md:py-5 text-slate-500 dark:text-slate-500"><i class="fas fa-user-gear mr-2 opacity-30"></i>{{ $log->vendor_pic ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 md:px-8 md:py-10 text-center font-black text-slate-400 uppercase tracking-widest text-[9px] md:text-[10px]">
                                <i class="fas fa-check-circle mr-2 text-emerald-400 text-base md:text-lg align-middle"></i> Seluruh alat saat ini beroperasi dengan normal.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-6 md:py-8 mt-10 md:mt-12 text-center">
        <p class="text-[9px] md:text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] md:tracking-[0.3em] px-4">
            &copy; {{ date('Y') }} Danantara Indonesia x Pelindo. All Rights Reserved.
        </p>
    </footer>

    <template x-teleport="body">
        <div x-show="showTypeModal" class="fixed inset-0 z-[100] flex items-center justify-center px-4 bg-slate-900/60 backdrop-blur-sm transition-opacity" x-cloak style="display: none;">
            <div @click.away="showTypeModal = false" x-show="showTypeModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-lg max-h-[85vh] flex flex-col shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-800">
                <div class="bg-[#003366] dark:bg-slate-950 px-6 py-4 flex items-center justify-between shrink-0">
                    <div>
                        <h3 class="text-white font-black uppercase tracking-widest text-sm" x-text="selectedTypeTitle"></h3>
                        <p class="text-blue-200 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider mt-1" x-text="selectedTypeEntity"></p>
                    </div>
                    <button @click="showTypeModal = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-red-500 text-white flex items-center justify-center transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto p-6 bg-slate-50 dark:bg-slate-950">
                    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden overflow-x-auto hide-scrollbar">
                        <table class="w-full text-left text-xs whitespace-nowrap min-w-[300px]">
                            <thead class="bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[9px] font-black uppercase tracking-widest border-b border-slate-200 dark:border-slate-700">
                                <tr>
                                    <th class="px-5 py-3 w-12 text-center">No</th>
                                    <th class="px-5 py-3">Kode Alat</th>
                                    <th class="px-5 py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <template x-for="(item, index) in selectedTypeItems" :key="index">
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                        <td class="px-5 py-3 text-center font-bold text-slate-400 dark:text-slate-600" x-text="index + 1"></td>
                                        <td class="px-5 py-3 font-black text-[#003366] dark:text-sky-400 uppercase" x-text="item.code"></td>
                                        <td class="px-5 py-3 text-center">
                                            <template x-if="item.status === 'available'">
                                                <span class="px-2 py-1 rounded bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/20 text-[9px] font-black uppercase">Tersedia</span>
                                            </template>
                                            <template x-if="item.status !== 'available'">
                                                <span class="px-2 py-1 rounded bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-500/20 text-[9px] font-black uppercase animate-pulse">Rusak</span>
                                            </template>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Modal Detail Insiden -->
    <template x-teleport="body">
        <div x-show="showIncidentModal" class="fixed inset-0 z-[110] flex items-center justify-center px-4 bg-slate-900/60 backdrop-blur-md transition-opacity" x-cloak style="display: none;">
            <div @click.away="showIncidentModal = false" x-show="showIncidentModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="bg-white dark:bg-slate-900 rounded-3xl w-full max-w-md shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-800">
                <div class="bg-red-600 px-6 py-5 flex items-center justify-between text-white">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                        <div>
                            <h3 class="font-black uppercase tracking-widest text-sm">Detail Insiden</h3>
                            <p class="text-red-100 text-[10px] font-bold uppercase tracking-wider mt-0.5" x-text="selectedIncident?.infrastructure?.code_name"></p>
                        </div>
                    </div>
                    <button @click="showIncidentModal = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Entitas</p>
                            <p class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase" x-text="selectedIncident?.infrastructure?.entity?.name"></p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Status Perbaikan</p>
                            <span class="inline-flex px-2 py-1 rounded bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-800 text-[9px] font-black uppercase tracking-widest" x-text="selectedIncident?.repair_status?.replace('_', ' ')"></span>
                        </div>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-800/50 p-5 rounded-2xl border border-slate-100 dark:border-slate-700">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Kendala Dilaporkan</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300 italic leading-relaxed" x-text="'&quot;' + selectedIncident?.issue_detail + '&quot;'"></p>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-800">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Petugas (PIC)</p>
                            <p class="text-xs font-bold text-slate-600 dark:text-slate-400" x-text="selectedIncident?.vendor_pic || 'Sedang Ditunjuk'"></p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Waktu Lapor</p>
                            <p class="text-xs font-bold text-slate-600 dark:text-slate-400" x-text="selectedIncident ? new Date(selectedIncident.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : ''"></p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-700 flex justify-center">
                    <button @click="showIncidentModal = false" class="px-8 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all shadow-sm">Tutup Detail</button>
                </div>
            </div>
        </div>
    </template>

    <script src="{{ asset('js/theme-toggle.js') }}"></script>
</body>
</html>
