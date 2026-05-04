<x-app-layout>
    <div class="max-w-[1600px] mx-auto w-full space-y-6 animate-fade-up"
         x-data="{ 
            showDeleteModal: false, 
            deleteUrl: '', 
            entityName: '' 
         }">

        <!-- MODAL DELETE (Enterprise Style) -->
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
                     class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-xl max-w-sm w-full overflow-hidden border border-slate-200 dark:border-slate-800 animate-in zoom-in-95 duration-300">
                    
                    <div class="p-8 text-center">
                        <div class="w-20 h-20 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 border border-red-100 dark:border-red-800">
                            <i class="fas fa-building-circle-xmark"></i>
                        </div>
                        
                        <h2 class="text-xl font-black text-[#003366] dark:text-blue-400 uppercase tracking-tight mb-2">Hapus Entitas?</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium leading-relaxed mb-6">
                            Anda yakin ingin menghapus terminal <br>
                            <strong class="text-red-600 dark:text-red-400 text-base" x-text="entityName"></strong>? <br>
                            <span class="text-xs">Pastikan tidak ada alat yang terikat ke area ini.</span>
                        </p>

                        <div class="flex gap-3">
                            <button type="button" @click="showDeleteModal = false" 
                                    class="flex-1 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
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
                    <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 flex justify-end gap-2">
                        <button @click="showDeleteModal = false" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded text-xs font-semibold hover:bg-slate-50 transition-colors">Batal</button>
                        <form :action="deleteUrl" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded text-xs font-semibold hover:bg-red-700 transition-colors shadow-sm">Hapus Entitas</button>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#003366] to-[#0055a4]"></div>
            
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/30 text-[#0055a4] dark:text-blue-400 rounded-2xl flex items-center justify-center text-2xl border border-blue-100 dark:border-blue-800 shadow-inner">
                    <i class="fas fa-building"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-[#003366] dark:text-blue-400 uppercase tracking-tight">Manajemen Entitas</h1>
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Daftar Cabang & Anak Perusahaan</p>
                </div>

        <!-- Search Form -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <form action="{{ route('admin.entities.index') }}" method="GET" class="flex gap-4">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Entitas atau Kode..." 
                           class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-[#0055a4] focus:border-[#0055a4] transition-all">
                </div>
                <button type="submit" class="bg-[#003366] hover:bg-[#001e3c] text-white px-8 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-md shadow-blue-900/10">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.entities.index') }}" class="px-4 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-xl flex items-center justify-center transition-all">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-6 py-4 rounded-xl text-sm font-bold shadow-sm flex items-center gap-3 animate-fade-in">
                <i class="fas fa-check-circle text-emerald-500 text-lg"></i> {{ session('success') }}
            </div>

        @if(session('error'))
            <div class="bg-red-50 text-red-700 border border-red-200 px-6 py-4 rounded-xl text-sm font-bold shadow-sm flex items-center gap-3 animate-fade-in">
                <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i> {{ session('error') }}
            </div>
        @endif

        <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-200 dark:border-slate-800">
                            <th class="px-8 py-5 w-16 text-center">No</th>
                            <th class="px-8 py-5">Kode Internal</th>
                            <th class="px-8 py-5">Nama Entitas / Cabang</th>
                            <th class="px-8 py-5 text-center">Total Infrastruktur</th>
                            <th class="px-8 py-5 text-right">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($entities as $index => $entity)
                        <tr class="hover:bg-blue-50/40 dark:hover:bg-blue-900/10 transition-colors group">
                            <td class="px-8 py-5 text-center text-slate-400 dark:text-slate-500 font-bold text-xs">{{ $index + 1 }}</td>
                            <td class="px-8 py-5">
                                <span class="font-black text-[#003366] dark:text-blue-400 text-xs uppercase px-3 py-1.5 bg-slate-50 dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-700 shadow-sm">
                                    {{ $entity->code }}
                                </span>
                            </td>
                            <td class="px-8 py-5 font-black text-slate-700 dark:text-slate-200 uppercase tracking-tight text-sm">
                                {{ $entity->name }}
                            </td>
                            <td class="px-8 py-5 text-center">
                                @if($entity->infrastructures_count > 0)
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 font-black text-[10px] uppercase tracking-widest shadow-sm">
                                        {{ $entity->infrastructures_count }} Unit
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 border border-slate-200 dark:border-slate-700 font-black text-[10px] uppercase tracking-widest">
                                        Kosong
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <a href="{{ route('admin.entities.edit', $entity->id) }}" class="w-8 h-8 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:text-[#0055a4] dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:border-blue-200 dark:hover:border-blue-800 rounded-lg flex items-center justify-center transition-all shadow-sm" title="Edit Entitas">
                                        <i class="fas fa-pen text-[10px]"></i>
                                    </a>
                                    
                                    <button type="button" 
                                            @click="
                                                deleteUrl = '{{ route('admin.entities.destroy', $entity->id) }}';
                                                entityName = '{{ addslashes($entity->name) }}';
                                                showDeleteModal = true;
                                            "
                                            class="w-8 h-8 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 hover:border-red-200 dark:hover:border-red-800 rounded-lg flex items-center justify-center transition-all shadow-sm" title="Hapus Entitas">
                                        <i class="fas fa-trash-alt text-[10px]"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center bg-slate-50/50 dark:bg-slate-800/20">
                                <div class="flex flex-col items-center justify-center opacity-40">
                                    <div class="w-16 h-16 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center border border-slate-200 dark:border-slate-700 shadow-sm mb-4">
                                        <i class="fas fa-building-circle-xmark text-2xl text-slate-400 dark:text-slate-500"></i>
                                    </div>
                                    <p class="font-black uppercase tracking-[0.2em] text-sm text-slate-600 dark:text-slate-300">Belum Ada Data Entitas</p>
                                    <p class="text-[10px] mt-1 font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Silakan tambah cabang atau anak perusahaan baru.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- FOOTER INFO -->
            <div class="flex items-center justify-between text-slate-400 pt-2">
                <p class="text-[10px] font-semibold uppercase tracking-wider">&copy; {{ date('Y') }} Pelindo Command Center</p>
            </div>

        </div>
    </div>
</x-app-layout>
