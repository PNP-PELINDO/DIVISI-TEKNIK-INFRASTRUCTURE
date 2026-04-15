<x-app-layout>
    <div class="max-w-[1600px] mx-auto w-full space-y-8 pb-16 animate-fade-up">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-blue-50 text-[#003366] rounded-xl flex items-center justify-center text-2xl border border-blue-100 shadow-inner">
                    <i class="fas fa-building"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-[#003366] uppercase tracking-tight">Manajemen Entitas</h1>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Daftar Cabang & Anak Perusahaan</p>
                </div>
            </div>
            <a href="{{ route('admin.entities.create') }}" class="bg-[#003366] hover:bg-[#002244] text-white px-6 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-900/20 transition-all flex items-center gap-2 group">
                <i class="fas fa-plus text-blue-400 group-hover:rotate-90 transition-transform duration-300"></i> Tambah Entitas
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-6 py-4 rounded-xl text-sm font-bold shadow-sm flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500 text-lg"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 text-red-700 border border-red-200 px-6 py-4 rounded-xl text-sm font-bold shadow-sm flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i> {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-200">
                            <th class="px-8 py-6 w-16 text-center">No</th>
                            <th class="px-8 py-6">Kode Internal</th>
                            <th class="px-8 py-6">Nama Entitas / Cabang</th>
                            <th class="px-8 py-6 text-center">Total Infrastruktur</th>
                            <th class="px-8 py-6 text-right">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($entities as $index => $entity)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-8 py-6 text-center text-slate-400 font-bold text-xs">{{ $index + 1 }}</td>
                            <td class="px-8 py-6">
                                <span class="font-black text-[#0055a4] text-xs uppercase px-3 py-1.5 bg-blue-50 rounded-lg border border-blue-100 shadow-sm">
                                    {{ $entity->code }}
                                </span>
                            </td>
                            <td class="px-8 py-6 font-black text-slate-700 uppercase tracking-tight text-sm">
                                {{ $entity->name }}
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $entity->infrastructures_count > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-400' }} font-black text-xs">
                                    {{ $entity->infrastructures_count }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.entities.edit', $entity->id) }}" class="w-8 h-8 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg flex items-center justify-center transition-colors shadow-sm" title="Edit Entitas">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.entities.destroy', $entity->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus entitas ini? Pastikan tidak ada alat yang terikat.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg flex items-center justify-center transition-colors shadow-sm" title="Hapus Entitas">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center opacity-30">
                                    <i class="fas fa-building-circle-xmark text-5xl mb-4"></i>
                                    <p class="font-black uppercase tracking-[0.2em] text-sm text-slate-800">Belum Ada Data Entitas</p>
                                    <p class="text-[10px] mt-2 font-bold uppercase tracking-widest text-slate-500">Silakan tambah cabang atau anak perusahaan baru.</p>
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
