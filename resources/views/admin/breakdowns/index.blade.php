<x-app-layout>
    <div class="max-w-[1600px] mx-auto w-full space-y-6 animate-fade-up">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-xl flex items-center justify-center text-2xl border border-red-100 dark:border-red-800 shadow-inner">
                    <i class="fas fa-tools"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-[#003366] dark:text-blue-400 uppercase tracking-tight">Log Kerusakan Alat</h1>
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Pemantauan progres perbaikan infrastruktur secara real-time</p>
                </div>
            </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-6 py-4 rounded-xl text-sm font-bold shadow-sm flex items-center gap-3 animate-fade-up">
                <i class="fas fa-check-circle text-emerald-500 text-lg"></i> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#001e3c] dark:bg-slate-800 text-white text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-700 dark:border-slate-700">
                            <th class="px-8 py-6 w-16 text-center">NO</th>
                            <th class="px-8 py-5">Unit ID</th>
                            <th class="px-8 py-5">Lokasi Entitas</th>
                            <th class="px-8 py-5">Detail Kendala</th>
                            <th class="px-8 py-5 text-center">Status Kerja</th>
                            <th class="px-8 py-5">PIC/Vendor</th>
                            <th class="px-8 py-5 text-right">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($logs as $index => $log)
                        <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors group">
                            <td class="px-8 py-6 text-center text-slate-400 dark:text-slate-500 font-bold text-xs">{{ $index + 1 }}</td>
                            <td class="px-8 py-6">
                                <span class="font-black text-[#003366] dark:text-blue-400 text-xs uppercase px-3 py-1.5 bg-slate-100 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                                    {{ $log->infrastructure->code_name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-tight">
                                {{ $log->infrastructure->entity->name ?? '-' }}
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-xs text-slate-600 dark:text-slate-300 font-medium max-w-xs leading-relaxed">{{ $log->issue_detail }}</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <i class="far fa-clock text-[10px] text-slate-400 dark:text-slate-500"></i>
                                    <span class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase">{{ $log->created_at->format('d M Y | H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <form action="{{ route('admin.breakdowns.update', $log->id) }}" method="POST" class="inline-flex">
                                    @csrf @method('PUT')
                                    <select name="repair_status" onchange="this.form.submit()" 
                                        class="text-[9px] font-black uppercase tracking-widest rounded-lg border-slate-200 dark:border-slate-700 py-1.5 px-3 focus:ring-2 focus:ring-[#003366] cursor-pointer transition-all
                                        {{ $log->repair_status == 'resolved' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800' : 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 border-red-200 dark:border-red-800' }}">
                                        <option value="reported" {{ $log->repair_status == 'reported' ? 'selected' : '' }}>Reported</option>
                                        <option value="order_part" {{ $log->repair_status == 'order_part' ? 'selected' : '' }}>Order Part</option>
                                        <option value="on_progress" {{ $log->repair_status == 'on_progress' ? 'selected' : '' }}>On Progress</option>
                                        <option value="resolved" {{ $log->repair_status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-500"></div>
                                    <span class="text-xs font-black text-slate-700 dark:text-slate-200 uppercase">{{ $log->vendor_pic ?? 'Internal' }}</span>
                                </div>
                                @if($log->updated_by)
                                    <div class="flex items-center gap-1.5 mt-2 opacity-60">
                                        <i class="fas fa-user-edit text-[8px] text-slate-400 dark:text-slate-500"></i>
                                        <p class="text-[8px] font-black tracking-widest text-slate-400 dark:text-slate-500 uppercase">{{ $log->updatedBy->name ?? 'System' }}</p>
                                    </div>
                                </td>

                                <td class="px-5 py-4 font-semibold text-slate-700">
                                    {{ $log->infrastructure->entity->name ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    <!-- Line clamp agar tidak terlalu panjang memakan ruang vertikal -->
                                    <p class="text-slate-700 font-medium leading-relaxed line-clamp-2" title="{{ $log->issue_detail }}">
                                        {{ $log->issue_detail }}
                                    </p>
                                    <div class="flex items-center gap-1.5 mt-1.5 text-[10px] text-slate-400 font-medium">
                                        <i class="far fa-clock"></i> Dilaporkan: {{ $log->created_at->format('d M Y, H:i') }}
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <form action="{{ route('admin.breakdowns.update', $log->id) }}" method="POST" class="w-full">
                                        @csrf @method('PUT')
                                        <select name="repair_status" onchange="this.form.submit()"
                                            class="status-select w-full text-[11px] font-semibold rounded border py-1.5 px-3 focus:ring-1 focus:ring-offset-0 focus:ring-[#003366] transition-colors cursor-pointer
                                            {{ $log->repair_status == 'resolved' ? 'bg-emerald-50 text-emerald-700 border-emerald-200 focus:border-emerald-500' :
                                              ($log->repair_status == 'on_progress' ? 'bg-amber-50 text-amber-700 border-amber-200 focus:border-amber-500' :
                                              ($log->repair_status == 'order_part' ? 'bg-purple-50 text-purple-700 border-purple-200 focus:border-purple-500' :
                                              'bg-red-50 text-red-700 border-red-200 focus:border-red-500')) }}">

                                            <option value="reported" {{ $log->repair_status == 'reported' ? 'selected' : '' }}>Dilaporkan</option>
                                            <option value="order_part" {{ $log->repair_status == 'order_part' ? 'selected' : '' }}>Order Suku Cadang</option>
                                            <option value="on_progress" {{ $log->repair_status == 'on_progress' ? 'selected' : '' }}>Sedang Diperbaiki</option>
                                            <option value="resolved" {{ $log->repair_status == 'resolved' ? 'selected' : '' }}>Telah Selesai (Resolved)</option>
                                        </select>
                                    </form>
                                </td>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-8 py-24 text-center bg-slate-50/50 dark:bg-slate-800/20">
                                <div class="flex flex-col items-center justify-center opacity-30">
                                    <i class="fas fa-clipboard-check text-6xl mb-4 text-slate-400 dark:text-slate-500"></i>
                                    <p class="font-black uppercase tracking-[0.3em] text-sm text-slate-800 dark:text-slate-200">No Active Incident Reports</p>
                                    <p class="text-[10px] mt-2 font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">Seluruh alat saat ini beroperasi secara normal</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="flex items-center justify-between px-4 text-slate-400">
            <p class="text-[9px] font-bold uppercase tracking-widest">&copy; 2026 Pelindo Regional Group</p>
            <div class="flex gap-4">
                <div class="flex items-center gap-1.5">
                    <div class="w-2 h-2 rounded-full bg-red-500"></div>
                    <span class="text-[9px] font-bold uppercase">Breakdown</span>
                </div>

                <!-- Pagination Section (Jika data sangat banyak) -->
                @if(method_exists($logs, 'links') && $logs->hasPages())
                <div class="bg-slate-50 border-t border-slate-200 px-6 py-3">
                    {{ $logs->links() }}
                </div>
                @endif
            </div>

            <!-- FOOTER INFO -->
            <div class="flex items-center justify-between text-slate-500 pt-2">
                <p class="text-[10px] font-semibold uppercase tracking-wider">&copy; {{ date('Y') }} Pelindo Command Center</p>
                <div class="flex gap-4">
                    <span class="text-[10px] font-medium flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-red-500"></span> Dilaporkan</span>
                    <span class="text-[10px] font-medium flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Proses</span>
                    <span class="text-[10px] font-medium flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Selesai</span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
