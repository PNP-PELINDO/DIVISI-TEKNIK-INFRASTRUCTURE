<x-app-layout>
    <div class="max-w-[1600px] mx-auto w-full space-y-6 animate-fade-up" 
         x-data="{ 
            showDeleteModal: false, 
            deleteUrl: '', 
            userName: '' 
         }">

        <!-- MODAL DELETE (Enterprise Style, Konsisten) -->
        <template x-teleport="body">
            <div x-show="showDeleteModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 backdrop-blur-none"
                 x-transition:enter-end="opacity-100 backdrop-blur-sm"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 backdrop-blur-sm"
                 x-transition:leave-end="opacity-0 backdrop-blur-none"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
                 style="display: none;">
                
                <div @click.away="showDeleteModal = false" 
                     class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-200 dark:border-slate-800 transform transition-all">
                    
                    <div class="p-6 sm:p-8">
                        <div class="flex items-start gap-5">
                            <div class="flex-shrink-0 w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xl">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-1">Hapus Akses Pengguna</h2>
                                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                    Apakah Anda yakin ingin menghapus akun <span class="font-bold text-slate-900 dark:text-slate-100" x-text="userName"></span>? Data akses ini akan dihapus secara permanen dari sistem.
                                </p>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col-reverse sm:flex-row gap-3 sm:justify-end">
                            <button type="button" @click="showDeleteModal = false" 
                                    class="w-full sm:w-auto px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-semibold transition-colors">
                                Batal
                            </button>
                            
                            <form :action="deleteUrl" method="POST" class="w-full sm:w-auto">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="w-full px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                                    Ya, Hapus Akun
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- HEADER SECTION -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm space-y-10 relative overflow-hidden mb-8">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#003366] to-[#0055a4]"></div>

            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/30 text-[#0055a4] dark:text-blue-400 rounded-[1.5rem] flex items-center justify-center text-3xl border border-blue-100 dark:border-blue-800 shadow-inner">
                        <i class="fas fa-users-gear"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-[#003366] dark:text-white uppercase tracking-tight">Manajemen Akun</h1>
                        <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 flex items-center gap-2">
                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span> 
                            Kelola Hak Akses Personil & Operator Terminal
                        </p>
                    </div>
                </div>
                
                <a href="{{ route('admin.users.create') }}" 
                   class="bg-[#003366] hover:bg-[#001e3c] dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-3 shadow-lg shadow-blue-900/20 active:scale-95">
                    <i class="fas fa-user-plus text-xs"></i> Tambah Pengguna Baru
                </a>
            </div>

            <!-- Server-side Filter Form -->
            <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 pt-8 border-t border-slate-100 dark:border-slate-800/50">
                <div class="relative lg:col-span-1">
                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama atau Email..." 
                           class="w-full pl-12 pr-6 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs font-bold text-slate-700 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-[#003366] transition-all">
                </div>

                <div class="relative">
                    <select name="role" onchange="this.form.submit()" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs font-black text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-[#003366] uppercase transition-all appearance-none cursor-pointer">
                        <option value="all">Semua Role</option>
                        <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                        <option value="operator" {{ request('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                </div>

                <div class="relative">
                    <select name="entity_id" onchange="this.form.submit()" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs font-black text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-[#003366] uppercase transition-all appearance-none cursor-pointer">
                        <option value="all">Semua Terminal</option>
                        @foreach($entities as $entity)
                            <option value="{{ $entity->id }}" {{ request('entity_id') == $entity->id ? 'selected' : '' }}>{{ $entity->name }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                </div>

                <button type="submit" class="bg-[#003366] hover:bg-[#001e3c] dark:bg-slate-800 dark:hover:bg-slate-700 text-white px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-3 border border-transparent dark:border-slate-700 shadow-lg active:scale-95">
                    <i class="fas fa-filter"></i> Apply Filter
                </button>
            </form>
        </div>

        <!-- ALERTS -->
        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 p-5 rounded-3xl flex items-start gap-4 shadow-sm animate-fade-in mb-8">
                <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-emerald-800 dark:text-emerald-400 uppercase tracking-tight">Berhasil</h3>
                    <p class="text-xs text-emerald-700 dark:text-emerald-300 mt-1 font-medium leading-relaxed">{{ session('success') }}</p>
                </div>
            </div>
        @endif


        @php
            // Ambil underlying collection dari paginator sebelum di-grouping
            $groupedUsers = $users->getCollection()->groupBy('role');
        @endphp

        <div class="space-y-8">
            @forelse($groupedUsers as $role => $roleUsers)
                @php
                    $isSuperadmin = strtolower($role) === 'superadmin';
                    $iconClass = $isSuperadmin ? 'fa-user-shield text-amber-500' : 'fa-user-tie text-blue-500';
                @endphp

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                    
                    <div class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 px-6 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center shadow-sm">
                                <i class="fas {{ $iconClass }} text-lg"></i>
                            </div>
                            <div>
                                <h2 class="font-black text-slate-800 dark:text-slate-100 text-sm uppercase tracking-widest">
                                    Akses: {{ ucfirst($role) }}
                                </h2>
                                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-0.5">Role Management</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] border {{ $isSuperadmin ? 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800' : 'bg-blue-50 dark:bg-blue-900/30 text-[#003366] dark:text-blue-400 border-blue-100 dark:border-blue-800' }}">
                            {{ $roleUsers->count() }} Users
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 dark:bg-slate-800/20 border-b border-slate-100 dark:border-slate-800">
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] w-20 text-center">No</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Profil Pengguna</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Penempatan / Area</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] text-right">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach($roleUsers as $index => $user)
                                <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-8 py-5 text-center text-xs font-bold text-slate-400 dark:text-slate-500">
                                        {{ $index + 1 }}
                                    </td>
                                    
                                    <td class="px-8 py-5">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-[#003366] dark:text-blue-400 uppercase tracking-tight group-hover:text-blue-600 transition-colors">{{ $user->name }}</span>
                                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 mt-1"><i class="far fa-envelope mr-1 opacity-70"></i> {{ $user->email }}</span>
                                        </div>
                                    </td>
                                    
                                    <td class="px-8 py-5">
                                        @if($user->entity)
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                                <i class="fas fa-map-marker-alt text-blue-500 opacity-70"></i> {{ $user->entity->name }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-blue-50 dark:bg-blue-900/30 text-[#003366] dark:text-blue-400 border border-blue-100 dark:border-blue-800">
                                                <i class="fas fa-globe-asia text-blue-500 opacity-70"></i> Akses Global (Pusat)
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                                               class="w-9 h-9 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-200 dark:hover:border-blue-800 flex items-center justify-center transition-all shadow-sm">
                                                <i class="fas fa-pen text-xs"></i>
                                            </a>
                                            
                                            @if($user->id !== auth()->id())
                                            <button type="button" 
                                                    @click="
                                                        deleteUrl = '{{ route('admin.users.destroy', $user->id) }}'; 
                                                        userName = '{{ addslashes($user->name) }}'; 
                                                        showDeleteModal = true;
                                                    " 
                                                    class="w-9 h-9 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-500 hover:text-red-600 dark:hover:text-red-400 hover:border-red-200 dark:hover:border-red-800 flex items-center justify-center transition-all shadow-sm">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                            @else
                                            <div class="w-9 h-9 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700 text-slate-300 dark:text-slate-600 flex items-center justify-center cursor-not-allowed" title="Current Active Session">
                                                <i class="fas fa-user-check text-xs"></i>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm p-20 text-center flex flex-col items-center justify-center animate-fade-in">
                    <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 text-slate-200 dark:text-slate-700 rounded-full flex items-center justify-center text-4xl mb-6 border border-slate-100 dark:border-slate-700 shadow-inner">
                        <i class="fas fa-users-slash"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 dark:text-slate-100 uppercase tracking-tight">Data Pegawai Kosong</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 max-w-sm font-medium">Belum ada akun operasional yang terdaftar untuk area yang Anda pilih.</p>
                    <a href="{{ route('admin.users.create') }}" class="mt-8 bg-[#003366] hover:bg-[#002244] text-white px-8 py-3.5 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-blue-900/20 transition-all flex items-center gap-3">
                        <i class="fas fa-plus"></i> Tambah Pengguna Baru
                    </a>
                </div>
            @endforelse
        </div>

        @if($users->hasPages())
            <div class="px-8 py-6 bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm mt-8">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

