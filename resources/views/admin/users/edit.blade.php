<x-app-layout>
    <div class="max-w-3xl mx-auto w-full space-y-6 animate-fade-up">
        
        <div class="bg-white dark:bg-slate-900 p-8 md:p-10 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#003366] to-[#0055a4]"></div>

            <div class="mb-10 text-center">
                <h2 class="text-2xl font-black text-[#003366] dark:text-blue-400 uppercase tracking-tight">Edit Akun Operator</h2>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Perbarui profil atau hak akses pegawai</p>
            </div>
            
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6" x-data="{ role: '{{ $user->role }}' }">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-blue-50 dark:focus:ring-blue-900/20 focus:border-[#003366] dark:focus:border-blue-400 transition-all text-slate-900 dark:text-slate-100" required>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Alamat Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-blue-50 dark:focus:ring-blue-900/20 focus:border-[#003366] dark:focus:border-blue-400 transition-all text-slate-900 dark:text-slate-100" required>
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-slate-200 dark:border-slate-800">
                    <p class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-4 italic">Ganti Password (Kosongkan jika tidak ingin ganti)</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <input type="password" name="password" placeholder="Password Baru" class="w-full border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-blue-50 dark:focus:ring-blue-900/20 focus:border-[#003366] dark:focus:border-blue-400 transition-all text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500">
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password Baru" class="w-full border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-blue-50 dark:focus:ring-blue-900/20 focus:border-[#003366] dark:focus:border-blue-400 transition-all text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Hak Akses (Role)</label>
                        <select name="role" x-model="role" class="w-full border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-bold p-4 focus:ring-4 focus:ring-blue-50 dark:focus:ring-blue-900/20 focus:border-[#003366] dark:focus:border-blue-400 transition-all uppercase text-slate-900 dark:text-slate-100">
                            <option value="operator" {{ $user->role == 'operator' ? 'selected' : '' }} class="dark:bg-slate-900">Operator Cabang</option>
                            <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }} class="dark:bg-slate-900">Super Admin Pusat</option>
                        </select>
                    </div>

                    <div x-show="role === 'operator'" x-transition>
                        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Penempatan Bagian</label>
                        <x-searchable-select 
                            name="entity_id" 
                            :value="old('entity_id') ?? $user->entity_id"
                            placeholder="-- Pilih Lokasi Tugas --"
                            :options="$entities->map(fn($e) => ['value' => $e->id, 'label' => $e->name . ' (' . $e->code . ')'])->toArray()"
                        />
                    </div>
                </div>

                <div class="pt-8 flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-[#003366] dark:bg-blue-600 hover:bg-[#001e3c] dark:hover:bg-blue-700 text-white py-4 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-900/20 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-save text-blue-400 dark:text-blue-300"></i> Perbarui Akun
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="px-8 py-4 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
