<x-app-layout>
    <div class="max-w-3xl mx-auto w-full space-y-6 animate-fade-up">
        
        <div class="bg-white dark:bg-slate-900 p-8 md:p-10 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden transition-colors duration-300">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#003366] to-[#0055a4]"></div>

            <div class="mb-10 text-center">
                <h2 class="text-2xl font-black text-[#003366] dark:text-blue-400 uppercase tracking-tight">Registrasi Akun Baru</h2>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Daftarkan personil baru ke dalam sistem portal</p>
            </div>

            @if ($errors->any())
                <div class="mb-8 p-5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl flex items-start gap-4">
                    <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center text-red-600 dark:text-red-400 shrink-0">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-red-800 dark:text-red-400 uppercase tracking-tight">Pendaftaran Gagal</h3>
                        <p class="text-[10px] font-bold text-red-700 dark:text-red-500 mt-1 uppercase tracking-tighter">Mohon periksa kembali inputan Anda.</p>
                    </div>
                </div>
            @endif
            
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-8" x-data="{ role: '{{ old('role', 'operator') }}' }">
                @csrf
                
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: John Doe"
                                   class="w-full pl-10 pr-4 py-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-4 focus:ring-blue-50 dark:focus:ring-blue-900/20 focus:border-[#003366] dark:focus:border-blue-400 transition-all" required>
                        </div>
                        @error('name') <p class="text-[10px] font-bold text-red-500 dark:text-red-400 mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Alamat Email</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="email@pelindo.co.id"
                                   class="w-full pl-10 pr-4 py-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-4 focus:ring-blue-50 dark:focus:ring-blue-900/20 focus:border-[#003366] dark:focus:border-blue-400 transition-all" required>
                        </div>
                        @error('email') <p class="text-[10px] font-bold text-red-500 dark:text-red-400 mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Password Info -->
                <div class="bg-slate-50 dark:bg-slate-800/50 p-8 rounded-[2rem] border border-slate-200 dark:border-slate-700 space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fas fa-key text-[#0055a4] text-lg"></i>
                        <h3 class="text-xs font-black text-slate-700 dark:text-slate-200 uppercase tracking-widest">Keamanan & Password</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Password</label>
                            <input type="password" name="password" placeholder="••••••••" required
                                   class="w-full px-4 py-3.5 bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 transition-all">
                            @error('password') <p class="text-[10px] font-bold text-red-500 mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" placeholder="••••••••" required
                                   class="w-full px-4 py-3.5 bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 transition-all">
                        </div>
                    </div>
                </div>

                <!-- Role & Entity -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Hak Akses (Role)</label>
                        <div class="relative">
                            <i class="fas fa-user-tag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <select name="role" x-model="role" 
                                    class="w-full pl-10 pr-4 py-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-4 focus:ring-blue-50 dark:focus:ring-blue-900/20 focus:border-[#003366] dark:focus:border-blue-400 transition-all uppercase appearance-none cursor-pointer">
                                <option value="operator">Operator Cabang</option>
                                <option value="superadmin">Super Admin Pusat</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                        </div>
                        @error('role') <p class="text-[10px] font-bold text-red-500 dark:text-red-400 mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2" x-show="role === 'operator'" x-transition>
                        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Penempatan Bagian</label>
                        <x-searchable-select 
                            name="entity_id" 
                            :value="old('entity_id')"
                            placeholder="-- Pilih Lokasi Tugas --"
                            :options="$entities->map(fn($e) => ['value' => $e->id, 'label' => $e->name . ' (' . $e->code . ')'])->toArray()"
                        />
                        @error('entity_id') <p class="text-[10px] font-bold text-red-500 dark:text-red-400 mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-8 flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-[#003366] dark:bg-blue-600 hover:bg-[#001e3c] dark:hover:bg-blue-700 text-white py-4 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-900/20 transition-all flex items-center justify-center gap-3 active:scale-95 group">
                        <i class="fas fa-user-plus group-hover:scale-110 transition-transform"></i> Daftarkan Akun
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="px-10 py-4 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-center flex items-center justify-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
