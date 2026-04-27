<x-app-layout>
    <div class="max-w-[1600px] mx-auto w-full space-y-8 pb-16 pt-8 px-4 animate-fade-up" 
         x-data="{ 
            showDeleteModal: false, 
            deleteUrl: '', 
            userName: '' 
         }">
        
        <template x-teleport="body">
            <div x-show="showDeleteModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
                 style="display: none;">
                
                <div @click.away="showDeleteModal = false" 
                     class="bg-white rounded-[2rem] shadow-xl max-w-sm w-full overflow-hidden border border-slate-200 animate-in zoom-in-95 duration-300">
                    
                    <div class="p-8 text-center">
                        <div class="w-20 h-20 bg-red-50 text-red-600 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 border border-red-100">
                            <i class="fas fa-user-minus"></i>
                        </div>
                        
                        <h2 class="text-xl font-black text-[#003366] uppercase tracking-tight mb-2">Hapus Pengguna?</h2>
                        <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">
                            Anda yakin ingin menghapus akses untuk akun <br>
                            <strong class="text-red-600 text-base" x-text="userName"></strong>? <br>
                            <span class="text-xs">Tindakan ini tidak dapat dibatalkan.</span>
                        </p>

                        <div class="flex gap-3">
                            <button type="button" @click="showDeleteModal = false" 
                                    class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                                Batal
                            </button>
                            
                            <form :action="deleteUrl" method="POST" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="w-full py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-md shadow-red-600/20">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#003366] to-[#0055a4]"></div>
            
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-blue-50 text-[#0055a4] rounded-2xl flex items-center justify-center text-2xl border border-blue-100 shadow-inner">
                    <i class="fas fa-users-gear"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-[#003366] uppercase tracking-tight">Manajemen Akun</h1>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Kelola hak akses personil per bagian terminal</p>
                </div>
            </div>
            
            <a href="{{ route('admin.users.create') }}" class="bg-[#003366] hover:bg-[#001e3c] text-white px-6 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest shadow-md shadow-blue-900/10 transition-all flex items-center gap-2">
                <i class="fas fa-user-plus"></i> Tambah Pengguna
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-6 py-4 rounded-xl text-sm font-bold shadow-sm flex items-center gap-3 animate-fade-in">
                <i class="fas fa-check-circle text-emerald-500"></i> {{ session('success') }}
            </div>
        @endif

        @php
            // Mengelompokkan koleksi users berdasarkan field 'role'
            $groupedUsers = collect($users)->groupBy('role');
        @endphp

        <div class="space-y-8">
            @forelse($groupedUsers as $role => $roleUsers)
                @php
                    // Styling dinamis berdasarkan role (Superadmin = Emas/Amber, Operator = Biru)
                    $isSuperadmin = strtolower($role) === 'superadmin';
                    $headerBg = $isSuperadmin ? 'bg-amber-50 border-amber-200' : 'bg-blue-50 border-blue-200';
                    $iconColor = $isSuperadmin ? 'text-amber-500' : 'text-[#0055a4]';
                    $iconClass = $isSuperadmin ? 'fa-user-shield' : 'fa-user-tie';
                    $textColor = $isSuperadmin ? 'text-amber-900' : 'text-[#003366]';
                @endphp

                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                    <div class="{{ $headerBg }} border-b px-8 py-5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fas {{ $iconClass }} {{ $iconColor }} text-xl"></i>
                            <h2 class="font-black {{ $textColor }} uppercase tracking-[0.2em] text-sm">
                                Role: {{ $role }}
                            </h2>
                        </div>
                        <span class="bg-white px-3 py-1.5 rounded-lg border border-white/50 text-[10px] font-black {{ $textColor }} uppercase shadow-sm">
                            {{ $roleUsers->count() }} Pengguna
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100">
                                    <th class="px-8 py-5 w-16 text-center">No</th>
                                    <th class="px-8 py-5">Nama Pegawai</th>
                                    <th class="px-8 py-5">Email / Username</th>
                                    <th class="px-8 py-5">Penempatan Bagian</th>
                                    <th class="px-8 py-5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @foreach($roleUsers as $index => $user)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-8 py-5 text-center text-slate-400 font-bold text-xs">{{ $index + 1 }}</td>
                                    
                                    <td class="px-8 py-5">
                                        <p class="font-black text-[#003366] uppercase text-xs">{{ $user->name }}</p>
                                    </td>
                                    
                                    <td class="px-8 py-5">
                                        <span class="text-xs text-slate-500 font-medium bg-slate-50 px-2 py-1 rounded border border-slate-200">
                                            {{ $user->email }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-8 py-5">
                                        @if($user->entity)
                                            <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">
                                                <i class="fas fa-map-marker-alt mr-1 text-slate-400"></i> {{ $user->entity->name }}
                                            </span>
                                        @else
                                            <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest bg-amber-50 px-2 py-1 rounded border border-amber-100">
                                                Akses Pusat (Global)
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="w-8 h-8 bg-white border border-slate-200 text-slate-500 hover:text-[#0055a4] hover:bg-blue-50 hover:border-blue-200 rounded-lg flex items-center justify-center transition-all shadow-sm" title="Edit Akun">
                                                <i class="fas fa-pen text-[10px]"></i>
                                            </a>
                                            
                                            @if($user->id !== auth()->id())
                                            <button type="button" 
                                                    @click="
                                                        deleteUrl = '{{ route('admin.users.destroy', $user->id) }}'; 
                                                        userName = '{{ addslashes($user->name) }}'; 
                                                        showDeleteModal = true;
                                                    " 
                                                    class="w-8 h-8 bg-white border border-slate-200 text-slate-500 hover:text-red-600 hover:bg-red-50 hover:border-red-200 rounded-lg flex items-center justify-center transition-all shadow-sm" title="Hapus Akun">
                                                <i class="fas fa-trash-alt text-[10px]"></i>
                                            </button>
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
                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm p-16 text-center">
                    <div class="flex flex-col items-center justify-center opacity-40">
                        <i class="fas fa-users-slash text-6xl mb-4 text-slate-400"></i>
                        <p class="font-black uppercase tracking-[0.2em] text-sm text-slate-800">Tidak ada pengguna</p>
                        <p class="text-[10px] mt-2 font-bold uppercase tracking-widest text-slate-500">Sistem belum memiliki data akun terdaftar.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
