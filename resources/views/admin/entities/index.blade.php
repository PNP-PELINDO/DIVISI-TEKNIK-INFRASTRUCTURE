<x-app-layout>
    <div class="max-w-[1600px] mx-auto w-full space-y-6 animate-fade-up"
         x-data="{ 
            showDeleteModal: false, 
            deleteUrl: '', 
            entityName: '' 
         }">

        <!-- MODAL DELETE (Clean Enterprise Style) -->
        <template x-teleport="body">
            <div x-show="showDeleteModal" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div @click.away="showDeleteModal = false"
                     x-show="showDeleteModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl max-w-sm w-full border border-slate-200 dark:border-slate-800 overflow-hidden">
                    <div class="p-10 text-center">
                        <div class="w-20 h-20 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 border border-red-100 dark:border-red-800 shadow-inner">
                            <i class="fas fa-building-circle-xmark"></i>
                        </div>
                        <h3 class="text-2xl font-black text-[#003366] dark:text-white uppercase tracking-tight mb-2">Hapus Entitas?</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed mb-8">
                            Anda yakin ingin menghapus data <strong class="text-red-600 dark:text-red-400" x-text="entityName"></strong>? <br>
                            Pastikan tidak ada aset atau user yang terikat ke area ini.
                        </p>
                        <div class="flex gap-4">
                            <button @click="showDeleteModal = false" class="flex-1 py-4 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">Batal</button>
                            <form :action="deleteUrl" method="POST" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-900/20 hover:bg-red-700 transition-all">Hapus</button>
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
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-[#003366] dark:text-white uppercase tracking-tight">Manajemen Entitas</h1>
                        <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 flex items-center gap-2">
                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span> 
                            Daftar Cabang & Anak Perusahaan Pelindo
                        </p>
                    </div>
                </div>
                
                <a href="{{ route('admin.entities.create') }}" 
                   class="bg-[#003366] hover:bg-[#001e3c] dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-3 shadow-lg shadow-blue-900/20 active:scale-95">
                    <i class="fas fa-plus text-xs"></i> Tambah Entitas Baru
                </a>
            </div>

            <!-- Server-side Filter Form -->
            <form action="{{ route('admin.entities.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 pt-8 border-t border-slate-100 dark:border-slate-800/50">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Entitas atau Kode..." 
                           class="w-full pl-12 pr-6 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs font-bold text-slate-700 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-[#0055a4] transition-all">
                </div>
                <button type="submit" class="bg-[#003366] hover:bg-[#001e3c] dark:bg-slate-800 dark:hover:bg-slate-700 text-white px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-3 border border-transparent dark:border-slate-700 shadow-lg">
                    Cari Data
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
