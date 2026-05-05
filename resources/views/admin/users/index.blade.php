<x-app-layout>
    <div class="max-w-[1600px] mx-auto w-full space-y-6 animate-fade-up" 
         x-data="{ 
            showDeleteModal: false, 
            deleteUrl: '', 
            userName: '' 
         }">

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

        @php
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
                                                    x-on:click="
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

        <x-confirm-modal 
            name="delete" 
            title="Hapus Pengguna?" 
            message="Apakah Anda yakin ingin menghapus akun <strong class='text-red-600' x-text='userName'></strong>? Data akses ini akan dihapus secara permanen dari sistem."
        />
    </div>
</x-app-layout>
