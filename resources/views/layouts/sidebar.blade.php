<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    .hide-sidebar-scroll::-webkit-scrollbar {
        display: none;
    }
    .hide-sidebar-scroll {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Animasi Hover Modern */
    .nav-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .nav-item:hover {
        transform: translateX(6px);
    }

    /* Styling Active State Premium */
    .sidebar-link-active {
        background: linear-gradient(90deg, #0055a4 0%, #003366 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 15px -3px rgba(0, 85, 164, 0.4) !important;
        border-radius: 12px !important;
        font-weight: 700 !important;
    }

    /* Warna Ikon saat Aktif */
    .sidebar-link-active i {
        color: #93c5fd !important; /* Warna biru muda menyala */
    }
</style>

<aside id="main-sidebar" style="font-family: 'Inter', sans-serif;" class="sidebar-transition w-[280px] bg-[#021326] flex flex-col fixed lg:relative inset-y-0 left-0 z-[90] shadow-[4px_0_24px_rgba(0,0,0,0.15)] shrink-0 border-r border-white/5">

    <!-- Logo Area -->
    <div class="h-[88px] flex items-center px-8 bg-[#010e1c] border-b border-white/5 shrink-0 relative overflow-hidden">
        <!-- Efek Glow di belakang logo -->
        <div class="absolute inset-0 bg-[#0055a4] opacity-10 blur-xl rounded-full scale-150 transform -translate-y-1/2"></div>
        <img src="{{ asset('pelindo.png') }}" alt="Pelindo Logo" class="h-8 md:h-9 object-contain filter brightness-110 relative z-10">
    </div>

    <!-- Navigation Area -->
    <div class="flex-1 overflow-y-auto hide-sidebar-scroll py-6 flex flex-col gap-6">

        <nav class="space-y-1.5 px-4">
            <!-- Menu Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="nav-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('dashboard') ? 'sidebar-link-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fas fa-desktop text-sm w-6 text-center {{ request()->routeIs('dashboard') ? '' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                <span class="text-xs font-semibold tracking-wide uppercase">Dashboard Utama</span>
            </a>

            <!-- Menu Portal Publik (Katalog) -->
            <a href="{{ route('home') }}" target="_blank"
               class="nav-item group flex items-center gap-4 px-4 py-3 rounded-xl text-slate-400 hover:bg-white/5 hover:text-white"
               title="Buka halaman depan di tab baru">
                <i class="fas fa-external-link-alt text-sm w-6 text-center text-slate-500 group-hover:text-slate-300"></i>
                <span class="text-xs font-semibold tracking-wide uppercase">Portal Publik</span>
            </a>
        </nav>

        <nav class="space-y-1.5 px-4">
            <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] mb-3">Operasional</p>

            <a href="{{ route('admin.infrastructures.index') }}"
               class="nav-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.infrastructures.*') ? 'sidebar-link-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fas fa-boxes text-sm w-6 text-center {{ request()->routeIs('admin.infrastructures.*') ? '' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                <span class="text-xs font-semibold tracking-wide uppercase">Infrastruktur</span>
            </a>

            <a href="{{ route('admin.breakdowns.index') }}"
               class="nav-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.breakdowns.*') ? 'sidebar-link-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fas fa-clipboard-list text-sm w-6 text-center {{ request()->routeIs('admin.breakdowns.*') ? '' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                <span class="text-xs font-semibold tracking-wide uppercase">Log Kerusakan</span>
            </a>
        </nav>

        @if(auth()->check() && auth()->user()->role === 'superadmin')
        <nav class="space-y-1.5 px-4">
            <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] mb-3 mt-2">Sistem Pusat</p>

            <a href="{{ route('admin.entities.index') }}"
               class="nav-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.entities.*') ? 'sidebar-link-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fas fa-building text-sm w-6 text-center {{ request()->routeIs('admin.entities.*') ? '' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                <span class="text-xs font-semibold tracking-wide uppercase">Manajemen Bagian</span>
            </a>

            <a href="{{ route('admin.users.index') }}"
               class="nav-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.users.*') ? 'sidebar-link-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fas fa-users-cog text-sm w-6 text-center {{ request()->routeIs('admin.users.*') ? '' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                <span class="text-xs font-semibold tracking-wide uppercase">Kelola Akun</span>
            </a>
        </nav>
        @endif

    </div>

    <!-- User Profile & Action Area -->
    <div class="mt-auto bg-[#010e1c] border-t border-white/5 shrink-0 pb-2">
        @auth
            <div class="p-4 flex items-center justify-between gap-3">

                <!-- Profile Info -->
                <div class="flex items-center gap-3 min-w-0">
                    <div class="relative w-10 h-10 rounded-full bg-gradient-to-br from-[#0055a4] to-[#003366] flex items-center justify-center text-white font-bold text-sm shadow-md shrink-0 border border-blue-400/20">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        <!-- Online Indicator -->
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-[#010e1c] rounded-full"></span>
                    </div>

                    <div class="truncate">
                        <p class="text-xs font-bold text-slate-200 truncate leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] font-semibold text-blue-400 uppercase tracking-widest mt-0.5 truncate">{{ auth()->user()->role }}</p>
                    </div>
                </div>

                <!-- Modern Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                    @csrf
                    <button type="submit" title="Akhiri Sesi" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white/5 text-slate-400 hover:bg-red-500 hover:text-white hover:shadow-lg hover:shadow-red-500/20 transition-all duration-300 group">
                        <i class="fas fa-power-off text-sm group-hover:scale-110 transition-transform"></i>
                    </button>
                </form>
            </div>
        @endauth

        <!-- Version Footer -->
        <div class="px-4 text-center">
            <p class="text-[9px] font-bold text-slate-600 uppercase tracking-[0.2em]">Dashboard Infrastructure</p>
        </div>
    </div>
</aside>
