<x-app-layout>
    <div class="max-w-[1600px] mx-auto w-full space-y-8 pb-16 pt-8 px-4 animate-fade-up">
        
        <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6 z-[60]">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#003366] to-[#0055a4]"></div>
            
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-blue-50 text-[#0055a4] rounded-2xl flex items-center justify-center text-2xl border border-blue-100 shadow-inner">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-[#003366] uppercase tracking-tight">Profil Akun</h1>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Kelola informasi kredensial dan preferensi keamanan Anda</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 relative z-50">
            <div class="space-y-8">
                <!-- Profile Information Form -->
                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- Update Password Form -->
                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm p-8">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete User Form -->
            <div class="space-y-8">
                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm p-8 border-t-4 border-t-red-600 relative overflow-hidden">
                    <div class="absolute inset-0 bg-red-50/30"></div>
                    <div class="relative z-10">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
