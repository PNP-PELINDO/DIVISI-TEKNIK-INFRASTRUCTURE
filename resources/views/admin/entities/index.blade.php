<x-app-layout>
    <div class="max-w-[1600px] mx-auto w-full space-y-8 pb-16 pt-8 px-4 animate-fade-up"
         x-data="{ 
            showDeleteModal: false, 
            deleteUrl: '', 
            entityName: '' 
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
                            <i class="fas fa-building-circle-xmark"></i>
                        </div>
                        
                        <h2 class="text-xl font-black text-[#003366] uppercase tracking-tight mb-2">Hapus Entitas?</h2>
                        <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">
                            Anda yakin ingin menghapus terminal <br>
                            <strong class="text-red-600 text-base" x-text="entityName"></strong>? <br>
                            <span class="text-xs">Pastikan tidak ada alat yang terikat ke area ini.</span>
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
                    <i class="fas fa-building"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-[#003366] uppercase tracking-tight">Manajemen Entitas</h1>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Daftar Cabang & Anak Perusahaan</p>
                </div>
            </div>
            
            <a href="{{ route('admin.entities.create') }}" class="bg-[#003366] hover:bg-[#001e3c] text-white px-6 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest shadow-md shadow-blue-900/10 transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Entitas
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-6 py-4 rounded-xl text-sm font-bold shadow-sm flex items-center gap-3 animate-fade-in">
                <i class="fas fa-check-circle text-emerald-500 text-lg"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 text-red-700 border border-red-200 px-6 py-4 rounded-xl text-sm font-bold shadow-sm flex items-center gap-3 animate-fade-in">
                <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i> {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-200">
                            <th class="px-8 py-5 w-16 text-center">No</th>
                            <th class="px-8 py-5">Kode Internal</th>
                            <th class="px-8 py-5">Nama Entitas / Cabang</th>
                            <th class="px-8 py-5 text-center">Total Infrastruktur</th>
                            <th class="px-8 py-5 text-right">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($entities as $index => $entity)
                        <tr class="hover:bg-blue-50/40 transition-colors group">
                            <td class="px-8 py-5 text-center text-slate-400 font-bold text-xs">{{ $index + 1 }}</td>
                            <td class="px-8 py-5">
                                <span class="font-black text-[#003366] text-xs uppercase px-3 py-1.5 bg-slate-50 rounded border border-slate-200 shadow-sm">
                                    {{ $entity->code }}
                                </span>
                            </td>
                            <td class="px-8 py-5 font-black text-slate-700 uppercase tracking-tight text-sm">
                                {{ $entity->name }}
                            </td>
                            <td class="px-8 py-5 text-center">
                                @if($entity->infrastructures_count > 0)
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded bg-emerald-50 text-emerald-600 border border-emerald-200 font-black text-[10px] uppercase tracking-widest shadow-sm">
                                        {{ $entity->infrastructures_count }} Unit
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded bg-slate-50 text-slate-400 border border-slate-200 font-black text-[10px] uppercase tracking-widest">
                                        Kosong
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <a href="{{ route('admin.entities.edit', $entity->id) }}" class="w-8 h-8 bg-white border border-slate-200 text-slate-500 hover:text-[#0055a4] hover:bg-blue-50 hover:border-blue-200 rounded-lg flex items-center justify-center transition-all shadow-sm" title="Edit Entitas">
                                        <i class="fas fa-pen text-[10px]"></i>
                                    </a>
                                    
                                    <button type="button" 
                                            @click="
                                                deleteUrl = '{{ route('admin.entities.destroy', $entity->id) }}';
                                                entityName = '{{ addslashes($entity->name) }}';
                                                showDeleteModal = true;
                                            "
                                            class="w-8 h-8 bg-white border border-slate-200 text-slate-500 hover:text-red-600 hover:bg-red-50 hover:border-red-200 rounded-lg flex items-center justify-center transition-all shadow-sm" title="Hapus Entitas">
                                        <i class="fas fa-trash-alt text-[10px]"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center bg-slate-50/50">
                                <div class="flex flex-col items-center justify-center opacity-40">
                                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center border border-slate-200 shadow-sm mb-4">
                                        <i class="fas fa-building-circle-xmark text-2xl text-slate-400"></i>
                                    </div>
                                    <p class="font-black uppercase tracking-[0.2em] text-sm text-slate-600">Belum Ada Data Entitas</p>
                                    <p class="text-[10px] mt-1 font-bold uppercase tracking-widest text-slate-400">Silakan tambah cabang atau anak perusahaan baru.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
