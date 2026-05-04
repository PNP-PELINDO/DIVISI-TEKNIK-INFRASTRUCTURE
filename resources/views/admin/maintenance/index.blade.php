<x-app-layout>
    <div class="max-w-[1600px] mx-auto w-full space-y-6 animate-fade-up">
        
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm relative flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden transition-colors duration-300">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-600 to-blue-400"></div>
            
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center text-2xl border border-blue-100 dark:border-blue-800 shadow-inner">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-[#003366] dark:text-blue-400 uppercase tracking-tight">Maintenance Terjadwal</h1>
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Pemeliharaan Preventif Infrastruktur Pelindo</p>
                </div>
            </div>
            
            <a href="{{ route('admin.maintenance.create') }}" class="bg-[#003366] dark:bg-blue-600 hover:bg-[#002244] dark:hover:bg-blue-700 text-white px-8 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-900/20 transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Jadwal
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 px-6 py-4 rounded-xl text-sm font-bold shadow-sm flex items-center gap-3 animate-fade-in">
                <i class="fas fa-check-circle text-emerald-500"></i> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-200 dark:border-slate-800">
                            <th class="px-8 py-5 w-16 text-center">NO</th>
                            <th class="px-8 py-5">Identitas Alat</th>
                            <th class="px-8 py-5">Kegiatan Maintenance</th>
                            <th class="px-8 py-5 text-center">Tanggal Jadwal</th>
                            <th class="px-8 py-5 text-center">Status</th>
                            <th class="px-8 py-5 text-right">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($schedules as $index => $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                            <td class="px-8 py-5 text-center text-slate-400 font-bold text-xs">{{ $schedules->firstItem() + $index }}</td>
                            <td class="px-8 py-5">
                                <div class="flex flex-col">
                                    <span class="font-black text-[#003366] dark:text-blue-400 text-xs uppercase">{{ $item->infrastructure->code_name }}</span>
                                    <span class="text-[9px] text-slate-400 font-bold uppercase mt-0.5">{{ $item->infrastructure->entity->name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase">{{ $item->title }}</p>
                                <p class="text-[10px] text-slate-500 mt-1 italic">{{ $item->description ?? 'Tidak ada deskripsi' }}</p>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <div class="inline-flex flex-col items-center bg-slate-50 dark:bg-slate-800 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700">
                                    <span class="text-[10px] font-black text-[#003366] dark:text-blue-400 uppercase tracking-tighter">{{ \Carbon\Carbon::parse($item->scheduled_date)->format('d M') }}</span>
                                    <span class="text-[14px] font-black text-slate-700 dark:text-slate-200">{{ \Carbon\Carbon::parse($item->scheduled_date)->format('Y') }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                @php
                                    $statusClasses = [
                                        'scheduled' => 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-blue-100 dark:border-blue-800',
                                        'completed' => 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-800',
                                        'cancelled' => 'bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 border-red-100 dark:border-red-800',
                                    ];
                                    $statusLabels = [
                                        'scheduled' => 'Terjadwal',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                    ];
                                @endphp
                                <form action="{{ route('admin.maintenance.update', $item->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <select name="status" onchange="this.form.submit()" 
                                            class="text-[9px] font-black uppercase tracking-widest rounded-lg border-slate-200 dark:border-slate-700 py-1.5 px-3 focus:ring-2 focus:ring-blue-500 cursor-pointer transition-all {{ $statusClasses[$item->status] ?? '' }} dark:bg-slate-800">
                                        @foreach($statusLabels as $val => $label)
                                            <option value="{{ $val }}" {{ $item->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <form action="{{ route('admin.maintenance.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center bg-slate-50/50 dark:bg-slate-800/20">
                                <div class="flex flex-col items-center opacity-20">
                                    <i class="fas fa-calendar-xmark text-6xl mb-4 text-slate-400 dark:text-slate-500"></i>
                                    <p class="text-sm font-black uppercase tracking-widest text-slate-800 dark:text-slate-200">Belum ada jadwal maintenance</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($schedules->hasPages())
                <div class="px-8 py-6 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800">
                    {{ $schedules->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
