<x-app-layout>
    <div class="max-w-[1600px] mx-auto w-full space-y-8 pb-16 animate-fade-up">
        
        <div class="flex items-center justify-between bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-blue-50 text-[#003366] rounded-xl flex items-center justify-center text-2xl border border-blue-100 shadow-inner">
                    <i class="fas fa-boxes"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-[#003366] uppercase tracking-tight">Manajemen Infrastruktur</h1>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Daftar seluruh aset operasional</p>
                </div>
            </div>
            <a href="{{ route('admin.infrastructures.create') }}" class="bg-[#003366] hover:bg-[#002244] text-white px-6 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-900/20 transition-all flex items-center gap-2 group">
                <i class="fas fa-plus text-blue-400 group-hover:rotate-90 transition-transform duration-300"></i> Tambah Aset
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-6 py-4 rounded-xl text-sm font-bold shadow-sm flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500 text-lg"></i> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-200">
                            <th class="px-8 py-6 w-16 text-center">NO</th>
                            <th class="px-8 py-6">Kode Alat</th>
                            <th class="px-8 py-6">Jenis / Kategori</th>
                            <th class="px-8 py-6">Lokasi Entitas</th>
                            <th class="px-8 py-6 text-center">Status</th>
                            <th class="px-8 py-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($infrastructures as $index => $item)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-8 py-6 text-center text-slate-400 font-bold text-xs">{{ $index + 1 }}</td>
                            <td class="px-8 py-6">
                                <span class="font-black text-[#0055a4] text-xs uppercase px-3 py-1.5 bg-blue-50 rounded-lg border border-blue-100 shadow-sm">
                                    {{ $item->code_name }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-slate-700 uppercase tracking-tight">{{ $item->type }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase mt-1">{{ $item->category }}</p>
                            </td>
                            <td class="px-8 py-6 text-slate-600 font-bold text-xs uppercase">{{ $item->entity->name ?? '-' }}</td>
                            <td class="px-8 py-6 text-center">
                                @if($item->status == 'available')
                                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest border border-emerald-200 shadow-sm">Ready</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest border border-red-200 shadow-sm">Down</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    
                                    <a href="{{ route('admin.infrastructures.edit', $item->id) }}" class="w-8 h-8 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg flex items-center justify-center transition-colors shadow-sm" title="Edit Aset">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.infrastructures.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset ini secara permanen?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg flex items-center justify-center transition-colors shadow-sm" title="Hapus Aset">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center opacity-30">
                                    <i class="fas fa-box-open text-5xl mb-4"></i>
                                    <p class="font-black uppercase tracking-[0.2em] text-sm text-slate-800">Inventaris Kosong</p>
                                    <p class="text-[10px] mt-2 font-bold uppercase tracking-widest text-slate-500">Silakan tambah infrastruktur operasional baru.</p>
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
