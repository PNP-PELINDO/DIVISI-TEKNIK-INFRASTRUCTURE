<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Otentikasi Sistem | DIA Portal</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }

        /* --- Animasi Premium --- */
        @keyframes fadeRight {
            0% { opacity: 0; transform: translateX(-30px); }
            100% { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes scaleIn {
            0% { opacity: 0; transform: scale(0.95); }
            100% { opacity: 1; transform: scale(1); }
        }

        .animate-fade-right { animation: fadeRight 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .animate-fade-up { animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        .animate-scale-in { animation: scaleIn 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }

        /* Custom Input Focus */
        .input-corporate:focus {
            border-color: #003366;
            box-shadow: 0 0 0 4px rgba(0, 51, 102, 0.1);
            outline: none;
        }

        /* Background Pattern Minimalis */
        .bg-corporate-pattern {
            background-image: radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 32px 32px;
        }
    </style>
</head>
<body class="antialiased overflow-hidden">

    <div class="flex h-screen w-full bg-white">
        
        <div class="hidden lg:flex lg:w-5/12 xl:w-1/2 bg-gradient-to-br from-[#00152b] via-[#002244] to-[#003366] relative flex-col justify-center items-center p-12 bg-corporate-pattern animate-scale-in">
            
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
                <div class="absolute -top-[20%] -left-[10%] w-[70%] h-[70%] bg-blue-500/10 rounded-full blur-[100px]"></div>
                <div class="absolute -bottom-[20%] -right-[10%] w-[70%] h-[70%] bg-cyan-400/10 rounded-full blur-[100px]"></div>
            </div>

            <div class="relative z-10 w-full max-w-lg text-center flex flex-col items-center">
                
                <div class="animate-fade-up delay-100">
                    <h2 class="text-xl xl:text-2xl font-extrabold tracking-[0.3em] text-blue-400 uppercase mb-8">
                        Dashboard
                    </h2>
                </div>

                <div class="w-full flex items-center justify-center gap-6 md:gap-8 bg-white/5 border border-white/10 p-6 rounded-3xl backdrop-blur-md shadow-2xl mb-8 animate-fade-up delay-200">
                    <img src="{{ asset('danantara.png') }}" alt="Danantara" class="h-12 xl:h-14 object-contain">
                    <div class="w-px h-12 bg-white/20"></div>
                    <img src="{{ asset('pelindo.png') }}" alt="Pelindo" class="h-12 xl:h-14 object-contain">
                </div>

                <div class="animate-fade-up delay-300">
                    <h1 class="text-4xl xl:text-5xl font-black tracking-tight text-white uppercase leading-[1.1] shadow-black/50 drop-shadow-lg">
                        Infrastructure<br>Availability
                    </h1>
                    <div class="mt-6 w-16 h-1.5 bg-blue-500 rounded-full mx-auto"></div>
                    <p class="mt-6 text-slate-300 font-medium text-sm xl:text-base leading-relaxed">
                        Sistem manajemen operasional terintegrasi untuk pemantauan ketersediaan aset dan penanganan log kerusakan secara real-time.
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

        <div class="w-full lg:w-7/12 xl:w-1/2 flex flex-col justify-center items-center p-8 sm:p-12 relative bg-slate-50">
            
            <div class="absolute top-8 left-0 w-full flex justify-center lg:hidden px-6 animate-fade-up">
                <div class="flex items-center gap-4 bg-white px-6 py-3 rounded-2xl shadow-sm border border-slate-200 w-full max-w-sm justify-center">
                    <img src="{{ asset('danantara.png') }}" alt="Danantara" class="h-6 sm:h-8 object-contain">
                    <div class="w-px h-8 bg-slate-200"></div>
                    <img src="{{ asset('pelindo.png') }}" alt="Pelindo" class="h-6 sm:h-8 object-contain">
                </div>
            </div>

            <div class="w-full max-w-md bg-white p-8 sm:p-10 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 relative z-10 animate-fade-up">
                
                <div class="mb-8">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-[#003366] uppercase tracking-widest transition-colors group">
                        <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                        Kembali ke Portal
                    </a>
                </div>

                <div class="mb-10">
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight leading-none mb-2">Otentikasi Sistem</h2>
                    <p class="text-slate-500 font-medium text-sm">Gunakan kredensial korporat Anda untuk mengakses konsol manajemen.</p>
                </div>

                @if (session('status'))
                    <div class="mb-6 flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-bold shadow-sm">
                        <i class="fas fa-check-circle text-emerald-600 mt-0.5"></i>
                        <p>{{ session('status') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Alamat Email</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-slate-400 group-focus-within:text-[#003366] transition-colors"></i>
                            </div>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus 
                                   class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 placeholder-slate-400 input-corporate transition-all @error('email') border-red-500 bg-red-50 @enderror"
                                   placeholder="admin@pelindo.co.id" />
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-2 font-bold flex items-center gap-1.5 ml-1">
                                <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Kata Sandi</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-slate-400 group-focus-within:text-[#003366] transition-colors"></i>
                            </div>
                            <input id="password" 
                                   type="password" 
                                   name="password" 
                                   required 
                                   class="block w-full pl-11 pr-12 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 placeholder-slate-400 input-corporate transition-all @error('password') border-red-500 bg-red-50 @enderror"
                                   placeholder="••••••••" />
                            
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-[#003366] transition-colors focus:outline-none">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-2 font-bold flex items-center gap-1.5 ml-1">
                                <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="flex items-center cursor-pointer group">
                            <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-[#003366] focus:ring-[#003366] transition-colors cursor-pointer bg-slate-50" name="remember">
                            <span class="ml-2 text-xs font-bold text-slate-600 group-hover:text-[#003366] transition-colors">Ingat Sesi Saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-[#0055a4] hover:text-[#003366] transition-colors">
                                Lupa Sandi?
                            </a>
                        @endif
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full flex items-center justify-center gap-3 py-4 px-6 bg-[#003366] hover:bg-[#002244] text-white text-sm font-black rounded-xl shadow-lg shadow-blue-900/20 transition-all hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-blue-900/10 uppercase tracking-widest group">
                            Login Portal
                            <i class="fas fa-arrow-right text-blue-400 group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="mt-8 text-center lg:hidden">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Infrastructure Portal</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Regional Hub Management</p>
            </div>

            <div class="absolute bottom-6 left-0 w-full text-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em]">
                    &copy; {{ date('Y') }} Danantara Group
                </p>
            </div>
            
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            if (type === 'text') {
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
                eyeIcon.classList.add('text-blue-500');
            } else {
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
                eyeIcon.classList.remove('text-blue-500');
            }
        });
    </script>
</body>
</html>
