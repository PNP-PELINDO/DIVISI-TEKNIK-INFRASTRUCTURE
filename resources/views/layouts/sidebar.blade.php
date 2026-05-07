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
        background: linear-gradient(90deg, #58b9e4 0%, #0064a7 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 15px -3px rgba(0, 100, 167, 0.4) !important;
        border-radius: 12px !important;
        font-weight: 700 !important;
    }

    /* Warna Ikon saat Aktif */
    .sidebar-link-active i {
        color: #ffffff !important;
    }
</style>

<aside id="main-sidebar" x-data="{ showLogoutModal: false }" style="font-family: 'Inter', sans-serif;"
    class="sidebar-transition w-[280px] bg-gradient-to-b from-[#003d7a] to-pelindo-navy flex flex-col fixed lg:relative inset-y-0 left-0 z-[90] shadow-[10px_0_40px_rgba(0,45,93,0.1)] shrink-0">

    <!-- Logo Area -->
    <div class="h-[88px] flex items-center px-8 shrink-0 relative overflow-hidden">
        <!-- Efek Glow di belakang logo -->
        <div
            class="absolute inset-0 bg-pelindo-blue opacity-20 blur-2xl rounded-full scale-150 transform -translate-y-1/2">
        </div>
        <img src="{{ asset('pelindo.png') }}" alt="Pelindo Logo"
            class="h-8 md:h-9 object-contain grayscale brightness-0 invert relative z-10">
    </div>

    <!-- Navigation Area -->
    <div class="flex-1 overflow-y-auto hide-sidebar-scroll py-6 flex flex-col gap-6">

        <nav class="space-y-1.5 px-4">
            <!-- Menu Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="nav-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'sidebar-link-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i
                    class="fas fa-desktop text-sm w-6 text-center {{ request()->routeIs('admin.dashboard') ? '' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                <span class="text-xs font-semibold tracking-wide uppercase">Dashboard Utama</span>
            </a>

            <!-- Menu Portal Publik (Katalog) -->
            <a href="{{ route('home') }}" target="_blank"
                class="nav-item group flex items-center gap-4 px-4 py-3 rounded-xl text-slate-400 hover:bg-white/5 hover:text-white"
                title="Buka halaman depan di tab baru">
                <i
                    class="fas fa-external-link-alt text-sm w-6 text-center text-slate-500 group-hover:text-slate-300"></i>
                <span class="text-xs font-semibold tracking-wide uppercase">Portal Publik</span>
            </a>
        </nav>

        <nav class="space-y-1.5 px-4">
            <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] mb-3">Operasional</p>

            <a href="{{ route('admin.infrastructures.index') }}"
                class="nav-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.infrastructures.*') ? 'sidebar-link-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i
                    class="fas fa-boxes text-sm w-6 text-center {{ request()->routeIs('admin.infrastructures.*') ? '' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                <span class="text-xs font-semibold tracking-wide uppercase">Infrastruktur</span>
            </a>

            <a href="{{ route('admin.breakdowns.index') }}"
                class="nav-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.breakdowns.*') ? 'sidebar-link-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i
                    class="fas fa-clipboard-list text-sm w-6 text-center {{ request()->routeIs('admin.breakdowns.*') ? '' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                <span class="text-xs font-semibold tracking-wide uppercase">Log Kerusakan</span>
            </a>

            <a href="{{ route('admin.maintenance.index') }}"
                class="nav-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.maintenance.*') ? 'sidebar-link-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i
                    class="fas fa-calendar-check text-sm w-6 text-center {{ request()->routeIs('admin.maintenance.*') ? '' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                <span class="text-xs font-semibold tracking-wide uppercase">Jadwal Maintenance</span>
            </a>
        </nav>


        @if(auth()->check() && auth()->user()->role === 'superadmin')
            <nav class="space-y-1.5 px-4">
                <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] mb-3 mt-2">Sistem Pusat</p>

                <a href="{{ route('admin.entities.index') }}"
                    class="nav-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.entities.*') ? 'sidebar-link-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i
                        class="fas fa-building text-sm w-6 text-center {{ request()->routeIs('admin.entities.*') ? '' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span class="text-xs font-semibold tracking-wide uppercase">Manajemen Entitas</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="nav-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.users.*') ? 'sidebar-link-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i
                        class="fas fa-users-cog text-sm w-6 text-center {{ request()->routeIs('admin.users.*') ? '' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span class="text-xs font-semibold tracking-wide uppercase">Kelola Akun</span>
                </a>
            </nav>
        @endif

    </div>

    <!-- User Profile & Action Area -->
    <div class="mt-auto shrink-0 pb-2">
        @auth
            <div class="p-4 flex items-center justify-between gap-3">

                <!-- Profile Info -->
                <div class="flex items-center gap-3 min-w-0">
                    <div
                        class="relative w-10 h-10 rounded-full bg-gradient-to-br from-pelindo-cyan to-pelindo-blue flex items-center justify-center text-white font-bold text-sm shadow-md shrink-0 border border-blue-400/20">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        <!-- Online Indicator -->
                        <span
                            class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-[#010e1c] rounded-full"></span>
                    </div>

                    <div class="truncate">
                        <p class="text-xs font-bold text-slate-200 truncate leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] font-semibold text-blue-400 uppercase tracking-widest mt-0.5 truncate">
                            {{ auth()->user()->role }}</p>
                    </div>
                </div>

                <!-- Modern Logout Button -->
                <button type="button" @click="showLogoutModal = true" title="Akhiri Sesi"
                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-white/5 text-slate-400 hover:bg-red-500 hover:text-white hover:shadow-lg hover:shadow-red-500/20 transition-all duration-300 group">
                    <i class="fas fa-power-off text-sm group-hover:scale-110 transition-transform"></i>
                </button>

                <template x-teleport="body">
                    <div x-show="showLogoutModal" x-cloak x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md">
                        <div @click.away="showLogoutModal = false"
                            class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl max-w-sm w-full border border-slate-200 dark:border-slate-800 overflow-hidden">
                            <div class="p-10 text-center">
                                <div
                                    class="w-20 h-20 bg-amber-50 dark:bg-amber-900/30 text-amber-600 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 border border-amber-100 dark:border-amber-800">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                <h3
                                    class="text-2xl font-black text-[#003366] dark:text-white uppercase tracking-tight mb-2">
                                    Akhiri Sesi?</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed mb-8">
                                    Apakah Anda yakin ingin keluar dari sistem Management Infrastructure Pelindo?
                                </p>
                                <div class="flex gap-4">
                                    <button @click="showLogoutModal = false"
                                        class="flex-1 py-4 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">Batal</button>
                                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                                        @csrf
                                        <button type="submit"
                                            class="w-full py-4 bg-[#003366] text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-900/20 hover:bg-[#002244] transition-all">Keluar
                                            Sesi</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        @endauth

        <!-- Version Footer -->
        <div class="px-6 py-4 text-center opacity-40">
            <p class="text-[8px] font-black text-white uppercase tracking-[0.3em]">Infrastructure Management v2.0</p>
        </div>
    </div>
</aside>