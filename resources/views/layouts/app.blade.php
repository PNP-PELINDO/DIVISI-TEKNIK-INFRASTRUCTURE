<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link rel="icon" href="{{ asset('logo_pelindo.png') }}">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'DIA Portal') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            pelindo: {
                                dark: '#001e3c',
                                blue: '#003366',
                                light: '#0055a4'
                            }
                        }
                    }
                }
            }
        </script>
        <script>
            if (localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

            body { font-family: 'Inter', sans-serif; }
            .sidebar-transition { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
            .sidebar-hidden { margin-left: -18rem; }
            @media (max-width: 1023px) { #main-sidebar { transform: translateX(-100%); } #main-sidebar.show { transform: translateX(0); } }
            
            /* Custom Scrollbar for Dark Mode */
            .dark ::-webkit-scrollbar { width: 8px; height: 8px; }
            .dark ::-webkit-scrollbar-track { background: #001e3c; }
            .dark ::-webkit-scrollbar-thumb { background: #003366; border-radius: 10px; }
            .dark ::-webkit-scrollbar-thumb:hover { background: #0055a4; }
        </style>
    </head>
    <body class="font-sans antialiased flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-500">
        
        <!-- MOBILE OVERLAY -->
        <div id="mobile-overlay" 
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[80] hidden lg:hidden opacity-0 transition-all duration-300" 
             onclick="toggleSidebar()"></div>

        @include('layouts.sidebar')

        <!-- MAIN CONTENT WRAPPER -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden relative z-10">
            
            <!-- HEADER / NAVBAR -->
            <header class="bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl sticky top-0 z-[70] px-8 py-5 flex items-center justify-between border-b border-slate-200 dark:border-slate-800 transition-all duration-300">
                <div class="flex items-center gap-5">
                    <button onclick="toggleSidebar()" 
                            class="w-11 h-11 flex items-center justify-center rounded-2xl bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-all shadow-sm group">
                        <i id="toggle-icon" class="fas fa-bars-staggered group-hover:scale-110 transition-transform"></i>
                    </button>
                    
                    <div class="h-8 w-[1px] bg-slate-200 dark:bg-slate-800 hidden sm:block"></div>

                    <!-- Dark Mode Toggle -->
                    <button onclick="toggleDarkMode()" 
                            class="w-11 h-11 flex items-center justify-center rounded-2xl bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-amber-400 hover:bg-white dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-all shadow-sm group">
                        <i id="dark-icon" class="fas fa-moon dark:fa-sun group-hover:rotate-12 transition-transform text-lg"></i>
                    </button>
                </div>

                <!-- REAL-TIME CLOCK ENTERPRISE STYLE -->
                <div class="flex items-center bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-inner p-1.5 select-none transition-all duration-300">
                    <div class="flex items-center gap-3 px-4 border-r border-slate-200 dark:border-slate-700 hidden lg:flex">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.6)]"></div>
                        <span id="realtime-date" class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em]">Memuat...</span>
                    </div>
                    <div class="flex items-center gap-3 px-4">
                        <i class="far fa-clock text-[#0055a4] dark:text-blue-400 animate-pulse"></i>
                        <span id="realtime-time" class="text-sm font-black text-[#003366] dark:text-blue-400 tracking-widest font-mono">00:00:00</span>
                        <span class="text-[8px] font-black text-slate-400 dark:text-slate-500 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 px-2 py-1 rounded-lg uppercase tracking-widest ml-2">WIB</span>
                    </div>
                </div>
            </header>

            <!-- MAIN CONTENT AREA -->
            <div class="flex-1 overflow-y-auto custom-scrollbar bg-slate-50 dark:bg-slate-950 transition-colors duration-500">
                <main class="w-full max-w-[1600px] mx-auto p-6 sm:p-8 lg:p-10 min-h-[calc(100vh-140px)]">
                    {{ $slot }}
                </main>

                <!-- STANDARDIZED FOOTER -->
                <footer class="w-full border-t border-slate-200 dark:border-slate-800 py-8 px-10 bg-white dark:bg-slate-900/50 transition-all duration-300 mt-auto">
                    <div class="max-w-[1600px] mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-4 group">
                            <img src="{{ asset('danantara.png') }}" alt="Danantara" class="h-5 brightness-0 dark:brightness-100 opacity-40 group-hover:opacity-100 transition-all">
                            <div class="w-[1px] h-4 bg-slate-200 dark:bg-slate-700"></div>
                            <img src="{{ asset('pelindo.png') }}" alt="Pelindo" class="h-5 brightness-0 dark:brightness-100 opacity-40 group-hover:opacity-100 transition-all">
                            <div class="h-4 w-[1px] bg-slate-200 dark:bg-slate-700"></div>
                            <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">
                                &copy; {{ date('Y') }} PT Pelabuhan Indonesia (Persero).
                            </p>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="flex items-center gap-2 px-4 py-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                <span class="text-[9px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Sistem Aktif</span>
                            </div>
                            <p class="text-[9px] font-black text-slate-300 dark:text-slate-600 uppercase tracking-widest">V 2.0.1 - PRODUCTION</p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <style>
            .custom-scrollbar::-webkit-scrollbar { width: 6px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
            .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        </style>

        <script>
            function updateDarkIcon() {
                const darkIcon = document.getElementById('dark-icon');
                if (!darkIcon) return;
                if (document.documentElement.classList.contains('dark')) {
                    darkIcon.classList.remove('fa-moon');
                    darkIcon.classList.add('fa-sun');
                } else {
                    darkIcon.classList.remove('fa-sun');
                    darkIcon.classList.add('fa-moon');
                }
            }

            function toggleDarkMode() {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('dark-mode', isDark);
                updateDarkIcon();
            }

            // Initialize icon on load
            document.addEventListener('DOMContentLoaded', updateDarkIcon);

            function toggleSidebar() {
                const sidebar = document.getElementById('main-sidebar');
                const overlay = document.getElementById('mobile-overlay');
                if (!sidebar) return;
                
                const isMobile = window.innerWidth < 1024;

                if (isMobile) {
                    sidebar.classList.toggle('show');
                    if (overlay.classList.contains('hidden')) { 
                        overlay.classList.remove('hidden'); 
                        setTimeout(() => overlay.classList.add('opacity-100'), 10); 
                    } else { 
                        overlay.classList.remove('opacity-100'); 
                        setTimeout(() => {
                            overlay.classList.add('hidden');
                        }, 300); 
                    }
                } else {
                    sidebar.classList.toggle('sidebar-hidden');
                }
            }

            // Real-Time Digital Clock Logic
            function initRealTimeClock() {
                const dateElement = document.getElementById('realtime-date');
                const timeElement = document.getElementById('realtime-time');

                if (!dateElement || !timeElement) return;

                const days = ['MINGGU', 'SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU'];
                const months = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGU', 'SEP', 'OKT', 'NOV', 'DES'];

                function updateClock() {
                    const now = new Date();
                    const dayName = days[now.getDay()];
                    const day = String(now.getDate()).padStart(2, '0');
                    const monthName = months[now.getMonth()];
                    const year = now.getFullYear();
                    dateElement.textContent = `${dayName}, ${day} ${monthName} ${year}`;

                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');
                    timeElement.textContent = `${hours}:${minutes}:${seconds}`;
                }

                updateClock();
                setInterval(updateClock, 1000);
            }

            document.addEventListener('DOMContentLoaded', initRealTimeClock);
        </script>
    </body>
</html>
