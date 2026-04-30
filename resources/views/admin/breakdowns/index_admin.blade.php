<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        [x-cloak] { display: none !important; }

        /* Custom Scrollbar Korporat */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .table-scroll::-webkit-scrollbar { height: 8px; }
        .table-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
    </style>

    <div class="min-h-screen py-8" x-data="{ showDeleteModal: false, deleteUrl: '', assetCode: '' }">

        <!-- MODAL DELETE (Enterprise Style) -->
        <template x-teleport="body">
            <div x-show="showDeleteModal" x-cloak
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
                <div @click.away="showDeleteModal = false"
                     x-show="showDeleteModal"
                     x-transition.scale.origin.bottom.duration.200ms
                     class="bg-white rounded-lg shadow-xl max-w-sm w-full border border-slate-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-red-100 text-red-600 rounded-full flex items-center justify-center shrink-0">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Konfirmasi Penghapusan Log</h3>
                                <p class="text-xs text-slate-500 mt-1">Anda yakin menghapus riwayat laporan <strong class="text-slate-800" x-text="assetCode"></strong>? Data akan terhapus dari sistem.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 flex justify-end gap-2">
                        <button @click="showDeleteModal = false" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded text-xs font-semibold hover:bg-slate-50 transition-colors">Batal</button>
                        <form :action="deleteUrl" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded text-xs font-semibold hover:bg-red-700 transition-colors shadow-sm">Hapus Data</button>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        <div class="max-w-[1600px] mx-auto w-full px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- HEADER KORPORAT -->
            <!-- FIX: overflow-hidden dihapus agar dropdown tidak terpotong -->
            <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm flex flex-col xl:flex-row gap-6 justify-between items-start xl:items-center relative animate-fade">
                <!-- FIX: Ditambahkan rounded-l-lg agar garis merah tetap rapi di sudut -->
                <div class="absolute left-0 top-0 h-full w-1.5 bg-red-600 rounded-l-lg"></div>

                <div>
                    <h1 class="text-lg font-bold text-[#003366] flex items-center gap-2">
                        <i class="fas fa-clipboard-list text-red-600"></i> Riwayat Log Kerusakan (Global)
                    </h1>
                    <p class="text-xs font-medium text-slate-500 mt-1">Pemantauan riwayat pelaporan insiden seluruh cabang Pelindo.</p>
                </div>

                <div class="w-full xl:w-auto flex flex-col sm:flex-row gap-3">
                    <div x-data="{ openExport: false }" class="relative w-full sm:w-auto">
                        <button @click="openExport = !openExport" @click.away="openExport = false" class="w-full justify-center bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2.5 rounded text-xs font-semibold transition-colors flex items-center gap-2">
                            <i class="fas fa-print text-slate-400"></i> Export Data <i class="fas fa-caret-down ml-1"></i>
                        </button>

                        <!-- FIX: z-[100] ditambahkan agar muncul di atas tabel -->
                        <div x-show="openExport" x-transition class="absolute right-0 mt-1 w-40 bg-white rounded shadow-lg border border-slate-200 z-[100] py-1">
                            <button onclick="openExportModal('pdf')" class="w-full text-left px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-100 flex items-center gap-2">
                                <i class="fas fa-file-pdf text-red-500 w-4"></i> PDF format
                            </button>
                            <button onclick="openExportModal('excel')" class="w-full text-left px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-100 flex items-center gap-2">
                                <i class="fas fa-file-excel text-emerald-500 w-4"></i> Excel format
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ALERTS -->
            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-4 py-3 rounded-md text-sm font-medium shadow-sm flex items-center gap-3">
                    <i class="fas fa-check-circle text-emerald-500"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 text-red-700 border border-red-200 px-4 py-3 rounded-md text-sm font-medium shadow-sm flex items-center gap-3">
                    <i class="fas fa-exclamation-triangle text-red-500"></i> {{ session('error') }}
                </div>
            @endif

            <!-- TABLE CONTAINER -->
            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden flex flex-col animate-fade" style="animation-delay: 100ms;">

                <div class="overflow-x-auto table-scroll w-full relative z-0">
                    <table class="w-full text-left border-collapse min-w-[1200px]">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                                <th class="px-5 py-3.5 w-12 text-center sticky left-0 bg-slate-50 shadow-[1px_0_0_0_#e2e8f0] z-10">No</th>
                                <th class="px-5 py-3.5 w-48 sticky left-[3rem] bg-slate-50 shadow-[1px_0_0_0_#e2e8f0] z-10">Unit & Lokasi</th>
                                <th class="px-5 py-3.5 min-w-[200px]">Uraian Laporan</th>
                                <th class="px-5 py-3.5 w-32 text-center">Status Akhir</th>
                                <th class="px-5 py-3.5 w-24 text-center">Tgl Lapor</th>
                                <th class="px-5 py-3.5 w-32 text-center">Tanggal Proses</th>
                                <th class="px-5 py-3.5 w-32 text-center">Penyelesaian</th>
                                <th class="px-5 py-3.5 w-40">Pelaksana/PIC</th>
                                <th class="px-5 py-3.5 w-20 text-center">Dokumen</th>
                                <th class="px-5 py-3.5 w-16 text-center">Hapus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                            @forelse($logs as $index => $log)
                            <tr class="hover:bg-slate-50/70 transition-colors group">
                                <td class="px-5 py-3 text-center font-medium text-slate-500 sticky left-0 bg-white group-hover:bg-slate-50 shadow-[1px_0_0_0_#f1f5f9] z-10">
                                    {{ $logs->firstItem() + $index }}
                                </td>

                                <td class="px-5 py-3 sticky left-[3rem] bg-white group-hover:bg-slate-50 shadow-[1px_0_0_0_#f1f5f9] z-10">
                                    <div class="font-bold text-[#003366]">{{ $log->infrastructure->code_name ?? 'ASET TERHAPUS' }}</div>
                                    <div class="text-[10px] text-slate-500 mt-0.5">{{ $log->infrastructure->entity->name ?? '-' }}</div>
                                </td>

                                <td class="px-5 py-3">
                                    <p class="text-slate-700 font-medium leading-snug line-clamp-2" title="{{ $log->issue_detail }}">
                                        {{ $log->issue_detail }}
                                    </p>
                                </td>

                                <td class="px-5 py-3 text-center">
                                    @php
                                        $statusConfig = [
                                            'reported' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'label' => 'Dilaporkan'],
                                            'order_part' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'label' => 'Order Suku Cadang'],
                                            'on_progress' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'label' => 'On Progress'],
                                            'resolved' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'label' => 'Resolved']
                                        ];
                                        $conf = $statusConfig[$log->repair_status] ?? $statusConfig['reported'];
                                    @endphp
                                    <span class="inline-flex {{ $conf['bg'] }} {{ $conf['text'] }} border {{ $conf['border'] }} px-2 py-1 rounded text-[9px] font-bold uppercase whitespace-nowrap">
                                        {{ $conf['label'] }}
                                    </span>
                                </td>

                                <td class="px-5 py-3 text-center font-medium">
                                    {{ $log->created_at->format('d/m/Y') }}
                                </td>

                                <td class="px-5 py-3 text-center">
                                    <div class="text-[10px] text-slate-500 space-y-0.5">
                                        <div class="flex justify-between"><span>T.Shoot:</span> <span class="font-medium text-slate-800">{{ $log->troubleshoot_date ? \Carbon\Carbon::parse($log->troubleshoot_date)->format('d/m/y') : '-' }}</span></div>
                                        <div class="flex justify-between"><span>W.Order:</span> <span class="font-medium text-slate-800">{{ $log->work_order_date ? \Carbon\Carbon::parse($log->work_order_date)->format('d/m/y') : '-' }}</span></div>
                                        <div class="flex justify-between"><span>Mulai:</span> <span class="font-medium text-slate-800">{{ $log->start_work_date ? \Carbon\Carbon::parse($log->start_work_date)->format('d/m/y') : '-' }}</span></div>
                                    </div>
                                </td>

                                <td class="px-5 py-3 text-center">
                                    <div class="text-[10px] text-slate-500 space-y-0.5">
                                        <div class="flex justify-between"><span>Test:</span> <span class="font-medium text-slate-800">{{ $log->com_test_date ? \Carbon\Carbon::parse($log->com_test_date)->format('d/m/y') : '-' }}</span></div>
                                        <div class="flex justify-between"><span>BA:</span> <span class="font-medium text-slate-800">{{ $log->ba_date ? \Carbon\Carbon::parse($log->ba_date)->format('d/m/y') : '-' }}</span></div>
                                        <div class="flex justify-between border-t border-slate-100 pt-0.5 mt-0.5">
                                            <span class="font-bold">Selesai:</span>
                                            <span class="font-bold text-emerald-600">{{ $log->resolved_date ? \Carbon\Carbon::parse($log->resolved_date)->format('d/m/y') : '-' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-3">
                                    <p class="font-semibold text-slate-800">{{ $log->vendor_pic ?? 'Internal' }}</p>
                                    @if($log->updated_by)
                                        <p class="text-[9px] text-slate-400 mt-0.5" title="Diperbarui oleh System/User">By: {{ $log->updatedBy->name ?? 'System' }}</p>
                                    @endif
                                </td>

                                <td class="px-5 py-3 text-center">
                                    @if($log->document_proof)
                                        <a href="{{ asset('storage/'.$log->document_proof) }}" target="_blank" class="inline-flex items-center justify-center w-7 h-7 bg-slate-50 border border-slate-200 text-slate-500 hover:text-[#0055a4] hover:bg-blue-50 hover:border-blue-200 rounded transition-colors" title="Lihat Bukti Fisik">
                                            <i class="fas fa-file-alt text-xs"></i>
                                        </a>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>

                                <td class="px-5 py-3 text-center">
                                    <button type="button"
                                            @click="deleteUrl = '{{ route('admin.breakdowns.destroy', $log->id) }}'; assetCode = '{{ addslashes($log->infrastructure->code_name ?? 'Aset Terhapus') }}'; showDeleteModal = true;"
                                            class="inline-flex items-center justify-center w-7 h-7 bg-white border border-slate-200 text-slate-400 hover:text-red-600 hover:bg-red-50 hover:border-red-200 rounded transition-colors">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="px-5 py-16 text-center">
                                    <div class="w-12 h-12 bg-slate-50 border border-slate-200 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-history text-xl text-slate-300"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-700">Belum Ada Riwayat</p>
                                    <p class="text-xs text-slate-500 mt-1">Sistem belum mencatat adanya pelaporan kerusakan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($logs, 'links') && $logs->hasPages())
                <div class="bg-slate-50 border-t border-slate-200 px-6 py-3">
                    {{ $logs->links() }}
                </div>
                @endif
            </div>

            <!-- FOOTER INFO -->
            <div class="flex flex-col sm:flex-row items-center justify-between text-slate-400 pt-2 gap-2">
                <p class="text-[10px] font-semibold uppercase tracking-wider">&copy; {{ date('Y') }} Pelindo Command Center</p>
                <div class="flex gap-4">
                    <span class="text-[10px] font-medium flex items-center gap-1.5"><i class="fas fa-info-circle text-blue-400"></i> Data disajikan secara Real-time</span>
                </div>
            </div>

        </div>
    </div>

    <!-- Hidden Logic Components -->
    <x-export-report :infrastructures="$allInfrastructures" :recentBreakdowns="$recentBreakdowns" />
    <x-export-filter-modal />

</x-app-layout>
