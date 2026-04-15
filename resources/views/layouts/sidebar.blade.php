<aside id="main-sidebar" class="sidebar-transition w-72 bg-[#001e3c] text-slate-400 flex flex-col fixed lg:relative inset-y-0 left-0 z-50 shadow-2xl shrink-0">
    
    <div class="h-20 border-b border-white/5 flex items-center px-8 bg-[#00152b]">
        <span class="text-[10px] font-black tracking-[0.3em] text-slate-500 uppercase leading-none">Navigation System</span>
    </div>

    <div class="flex-1 overflow-y-auto py-6 space-y-8">
        <nav class="space-y-1">
            
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-white/5 text-white border-l-4 border-blue-500 font-bold' : 'hover:bg-white/5 hover:text-white font-medium' }} flex items-center gap-4 px-8 py-3 transition-all">
                <i class="fas fa-th-large text-sm w-4 text-center"></i> <span>Dashboard Utama</span>
            </a>
            
            <p class="px-8 text-[10px] font-black text-slate-500 uppercase tracking-widest pt-6 pb-2">Master Data</p>
            
            <a href="{{ route('admin.entities.index') }}" class="{{ request()->routeIs('admin.entities.*') ? 'bg-white/5 text-white border-l-4 border-blue-500 font-bold' : 'hover:bg-white/5 hover:text-white font-medium' }} flex items-center gap-4 px-8 py-2.5 transition-all">
                <i class="fas fa-building text-xs w-4 text-center"></i> <span class="text-sm">Bagian</span>
            </a>
            
            <a href="{{ route('admin.infrastructures.index') }}" class="{{ request()->routeIs('admin.infrastructures.*') ? 'bg-white/5 text-white border-l-4 border-blue-500 font-bold' : 'hover:bg-white/5 hover:text-white font-medium' }} flex items-center gap-4 px-8 py-2.5 transition-all">
                <i class="fas fa-boxes text-xs w-4 text-center"></i> <span class="text-sm">Infrastruktur</span>
            </a>
            
            <a href="{{ route('admin.breakdowns.index') }}" class="{{ request()->routeIs('admin.breakdowns.*') ? 'bg-white/5 text-white border-l-4 border-blue-500 font-bold' : 'hover:bg-white/5 hover:text-white font-medium' }} flex items-center gap-4 px-8 py-2.5 transition-all">
                <i class="fas fa-clipboard-list text-xs w-4 text-center"></i> <span class="text-sm">Log Kerusakan</span>
            </a>

        </nav>

        <div class="pt-6 border-t border-white/5 mx-4">
            @auth
                <div class="p-4 bg-white/5 rounded-xl border border-white/10">
                    <p class="text-[10px] font-bold text-blue-400 uppercase tracking-tighter mb-1">Authenticated</p>
                    <p class="text-xs font-bold text-white mb-3 truncate">{{ Auth::user()->name }}</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full py-2 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white text-[10px] font-black rounded transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                            <i class="fas fa-power-off"></i> Logout
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="flex items-center justify-center gap-3 w-full py-3 bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-black rounded-xl transition-all uppercase tracking-widest shadow-lg">
                    <i class="fas fa-key"></i> Login Panel
                </a>
            @endauth
        </div>
    </div>

    <div class="p-6 text-center border-t border-white/5">
        <p class="text-[9px] font-bold text-slate-600 uppercase tracking-widest">DIA PORTAL v4.0</p>
    </div>
</aside>
