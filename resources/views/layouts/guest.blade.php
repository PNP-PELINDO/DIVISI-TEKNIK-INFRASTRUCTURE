<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Otentikasi Sistem | DIA Portal</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        @keyframes fadeUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
        @keyframes scaleIn { 0% { opacity: 0; transform: scale(0.95); } 100% { opacity: 1; transform: scale(1); } }
        .animate-fade-up { animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        .animate-scale-in { animation: scaleIn 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .bg-corporate-pattern { background-image: radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 32px 32px; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased overflow-hidden">

    <div class="flex h-screen w-full bg-white">
        
        <div class="hidden lg:flex lg:w-5/12 xl:w-1/2 bg-gradient-to-br from-[#00152b] via-[#002244] to-[#003366] relative flex-col justify-center items-center p-12 bg-corporate-pattern animate-scale-in">
            
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
                <div class="absolute -top-[20%] -left-[10%] w-[70%] h-[70%] bg-blue-500/10 rounded-full blur-[100px]"></div>
                <div class="absolute -bottom-[20%] -right-[10%] w-[70%] h-[70%] bg-cyan-400/10 rounded-full blur-[100px]"></div>
            </div>

            <div class="relative z-10 w-full max-w-lg text-center flex flex-col items-center">
                
                <div class="animate-fade-up" style="animation-delay: 100ms;">
                    <h2 class="text-xl xl:text-2xl font-extrabold tracking-[0.3em] text-blue-400 uppercase mb-8">
                        Keamanan Sistem
                    </h2>
                </div>

                <div class="w-full flex items-center justify-center gap-6 md:gap-8 bg-white/5 border border-white/10 p-6 rounded-3xl backdrop-blur-md shadow-2xl mb-8 animate-fade-up" style="animation-delay: 200ms;">
                    <img src="{{ asset('danantara.png') }}" alt="Danantara" class="h-12 xl:h-14 object-contain filter brightness-0 invert">
                    <div class="w-px h-12 bg-white/20"></div>
                    <img src="{{ asset('pelindo.png') }}" alt="Pelindo" class="h-12 xl:h-14 object-contain filter brightness-0 invert">
                </div>

                <div class="animate-fade-up" style="animation-delay: 300ms;">
                    <h1 class="text-4xl xl:text-5xl font-black tracking-tight text-white uppercase leading-[1.1] shadow-black/50 drop-shadow-lg">
                        Infrastructure<br>Availability
                    </h1>
                    <div class="mt-6 w-16 h-1.5 bg-blue-500 rounded-full mx-auto"></div>
                    <p class="mt-6 text-slate-300 font-medium text-sm xl:text-base leading-relaxed">
                        Sistem otentikasi aman untuk akses ke portal pemantauan ketersediaan infrastruktur secara real-time.
                    </p>
                </div>

            </div>

            <div class="absolute bottom-8 left-0 w-full text-center z-10">
                <div class="inline-flex items-center gap-2 bg-black/20 px-4 py-2 rounded-full border border-white/5">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">Secured Corporate Network</span>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-7/12 xl:w-1/2 flex flex-col justify-center items-center p-8 sm:p-12 relative bg-slate-50 overflow-y-auto">
            
            <div class="absolute top-8 left-0 w-full flex justify-center lg:hidden px-6 animate-fade-up">
                <div class="flex items-center gap-4 bg-white px-6 py-3 rounded-2xl shadow-sm border border-slate-200 w-full max-w-sm justify-center">
                    <img src="{{ asset('danantara.png') }}" alt="Danantara" class="h-6 sm:h-8 object-contain">
                    <div class="w-px h-8 bg-slate-200"></div>
                    <img src="{{ asset('pelindo.png') }}" alt="Pelindo" class="h-6 sm:h-8 object-contain">
                </div>
            </div>

            <div class="w-full max-w-md bg-white p-8 sm:p-10 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 relative z-10 mt-16 lg:mt-0 animate-fade-up">
                
                <div class="mb-8 flex justify-between items-center">
                    <a href="{{ url('/login') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-[#003366] uppercase tracking-widest transition-colors group">
                        <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                        Kembali
                    </a>
                </div>

                {{ $slot }}

            </div>
            
            <div class="mt-8 text-center lg:hidden animate-fade-up" style="animation-delay: 300ms;">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Infrastructure Portal</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Regional Hub Management</p>
            </div>

            <div class="absolute bottom-6 left-0 w-full text-center hidden lg:block">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em]">
                    &copy; {{ date('Y') }} Danantara Group
                </p>
            </div>
            
        </div>
    </div>
</body>
</html>
