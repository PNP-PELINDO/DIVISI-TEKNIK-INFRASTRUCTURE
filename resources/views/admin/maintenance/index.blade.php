<x-app-layout>
    <div class="max-w-[1600px] mx-auto w-full space-y-6 animate-fade-up">
        
        <!-- HEADER SECTION -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm space-y-10 relative overflow-hidden mb-8">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-600 to-blue-400"></div>

            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-[1.5rem] flex items-center justify-center text-3xl border border-blue-100 dark:border-blue-800 shadow-inner">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-[#003366] dark:text-white uppercase tracking-tight">Maintenance Terjadwal</h1>
                        <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 flex items-center gap-2">
                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span> 
                            Pemeliharaan Preventif Infrastruktur Pelindo
                        </p>
                    </div>
                </div>
                
                <a href="{{ route('admin.maintenance.create') }}" 
                   class="bg-[#003366] hover:bg-[#001e3c] dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-3 shadow-lg shadow-blue-900/20 active:scale-95">
                    <i class="fas fa-plus text-xs"></i> Tambah Jadwal Baru
                </a>
            </div>

            <!-- Server-side Filter Form -->
            <form action="{{ route('admin.maintenance.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 pt-8 border-t border-slate-100 dark:border-slate-800/50">
                <div class="relative lg:col-span-2">
                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aset atau nama kegiatan..." 
                           class="w-full pl-12 pr-6 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs font-bold text-slate-700 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                
                @if(auth()->user()->role === 'superadmin')
                <div class="relative">
                    <select name="entity_id" onchange="this.form.submit()" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs font-black text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 uppercase transition-all appearance-none cursor-pointer">
                        <option value="all">Semua Terminal</option>
                        @foreach($allEntities as $entity)
                            <option value="{{ $entity->id }}" {{ request('entity_id') == $entity->id ? 'selected' : '' }}>{{ $entity->name }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                </div>
                @endif

                <div class="relative">
                    <select name="status" onchange="this.form.submit()" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs font-black text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 uppercase transition-all appearance-none cursor-pointer">
                        <option value="all">Status Jadwal</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2 shadow-lg shadow-blue-900/20 active:scale-95">
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
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.maintenance.edit', $item->id) }}" class="text-blue-400 hover:text-blue-600 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.maintenance.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
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
