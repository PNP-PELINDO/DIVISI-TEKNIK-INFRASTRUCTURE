<x-app-layout>
    <div class="max-w-[1600px] mx-auto w-full space-y-8 pb-16 pt-8 px-4 animate-fade-up">
        
        <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-red-600 to-red-400"></div>
            
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center text-2xl border border-red-100 shadow-inner">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-[#003366] uppercase tracking-tight">Riwayat Log Kerusakan</h1>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Pemantauan Kendala Seluruh Cabang Pelindo</p>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <button onclick="exportTableToCSV('Laporan_Kerusakan_DIA_{{ date('d_M_Y') }}.csv')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-emerald-900/20 transition-all flex items-center gap-2">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>

                <div class="bg-slate-50 px-6 py-3 rounded-xl border border-slate-200 text-center hidden md:block">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Akses Mode</p>
                    <p class="text-sm font-black text-red-600 uppercase">Administrator Pusat</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-6 py-4 rounded-xl text-sm font-bold shadow-sm flex items-center gap-3 animate-fade-in">
                <i class="fas fa-check-circle text-emerald-500 text-lg"></i> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto hide-scrollbar">
                <table id="logTable" class="w-full text-left border-collapse whitespace-nowrap min-w-[1600px]">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-[9px] font-black uppercase tracking-[0.15em] border-b border-slate-200">
                            <th class="px-4 py-5 w-12 text-center sticky left-0 bg-slate-50 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)]">NO</th>
                            <th class="px-4 py-5 sticky left-12 bg-slate-50 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)]">Identitas Alat</th>
                            <th class="px-4 py-5">Lokasi Entitas</th>
                            <th class="px-4 py-5">Detail Laporan</th>
                            <th class="px-4 py-5 text-center">Status Akhir</th>
                            <th class="px-4 py-5 text-center">Tgl Lapor</th>
                            <th class="px-4 py-5 text-center">T.Shoot</th>
                            <th class="px-4 py-5 text-center">BA</th>
                            <th class="px-4 py-5 text-center">Work Order</th>
                            <th class="px-4 py-5 text-center">PR/PO</th>
                            <th class="px-4 py-5 text-center">S.Part Site</th>
                            <th class="px-4 py-5 text-center">Mulai Kerja</th>
                            <th class="px-4 py-5 text-center">Com Test</th>
                            <th class="px-4 py-5 text-center">Selesai</th>
                            <th class="px-4 py-5">PIC/Vendor</th>
                            <th class="px-4 py-5 text-right export-ignore">Aksi / Bukti</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($logs as $index => $log)
                        <tr class="hover:bg-red-50/30 transition-colors group">
                            <td class="px-4 py-4 text-center text-slate-400 font-bold text-xs sticky left-0 bg-white group-hover:bg-red-50/90 z-10">{{ $index + 1 }}</td>
                            <td class="px-4 py-4 sticky left-12 bg-white group-hover:bg-red-50/90 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)]">
                                <span class="font-black text-[#003366] text-[11px] uppercase">{{ $log->infrastructure->code_name ?? 'TERHAPUS' }}</span>
                            </td>

                            <td class="px-4 py-4 text-[10px] font-bold text-slate-600 uppercase tracking-tight">
                                {{ $log->infrastructure->entity->name ?? 'TERHAPUS' }}
                            </td>
                            <td class="px-4 py-4 text-[10px] text-slate-700 font-medium max-w-[150px] truncate italic" title="{{ $log->issue_detail }}">
                                "{{ $log->issue_detail }}"
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($log->repair_status == 'resolved')
                                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-1 rounded text-[8px] font-black uppercase tracking-widest">Ready (Selesai)</span>
                                @else
                                    <span class="bg-red-50 text-red-600 border border-red-200 px-2 py-1 rounded text-[8px] font-black uppercase tracking-widest">{{ str_replace('_', ' ', $log->repair_status) }}</span>
                                @endif
                            </td>

                            <td class="px-4 py-4 text-center text-[10px] font-bold text-[#0055a4]">{{ $log->created_at->format('d/m/y') }}</td>
                            <td class="px-4 py-4 text-center text-[10px] font-bold text-slate-600">{{ $log->troubleshoot_date ? \Carbon\Carbon::parse($log->troubleshoot_date)->format('d/m/y') : '-' }}</td>
                            <td class="px-4 py-4 text-center text-[10px] font-bold text-slate-600">{{ $log->ba_date ? \Carbon\Carbon::parse($log->ba_date)->format('d/m/y') : '-' }}</td>
                            <td class="px-4 py-4 text-center text-[10px] font-bold text-slate-600">{{ $log->work_order_date ? \Carbon\Carbon::parse($log->work_order_date)->format('d/m/y') : '-' }}</td>
                            <td class="px-4 py-4 text-center text-[10px] font-bold text-slate-600">{{ $log->pr_po_date ? \Carbon\Carbon::parse($log->pr_po_date)->format('d/m/y') : '-' }}</td>
                            <td class="px-4 py-4 text-center text-[10px] font-bold text-slate-600">{{ $log->sparepart_date ? \Carbon\Carbon::parse($log->sparepart_date)->format('d/m/y') : '-' }}</td>
                            <td class="px-4 py-4 text-center text-[10px] font-bold text-slate-600">{{ $log->start_work_date ? \Carbon\Carbon::parse($log->start_work_date)->format('d/m/y') : '-' }}</td>
                            <td class="px-4 py-4 text-center text-[10px] font-bold text-slate-600">{{ $log->com_test_date ? \Carbon\Carbon::parse($log->com_test_date)->format('d/m/y') : '-' }}</td>
                            <td class="px-4 py-4 text-center text-[10px] font-black text-emerald-600">{{ $log->resolved_date ? \Carbon\Carbon::parse($log->resolved_date)->format('d/m/y') : '-' }}</td>

                            <td class="px-4 py-4 text-[10px] font-black text-slate-500 uppercase">{{ $log->vendor_pic ?? 'Internal' }}</td>
                            
                            <td class="px-4 py-4 text-right export-ignore">
                                <div class="flex items-center justify-end gap-2">
                                    @if($log->document_proof)
                                        <a href="{{ asset('storage/'.$log->document_proof) }}" target="_blank" class="w-7 h-7 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded flex items-center justify-center border border-emerald-200 transition-colors" title="Lihat Dokumen Bukti">
                                            <i class="fas fa-file-pdf text-[10px]"></i>
                                        </a>
                                    @endif
                                    
                                    <form action="{{ route('admin.breakdowns.destroy', $log->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus log ini secara permanen?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-7 h-7 bg-white border border-slate-200 text-slate-400 hover:text-red-600 hover:bg-red-50 hover:border-red-200 rounded flex items-center justify-center transition-all shadow-sm">
                                            <i class="fas fa-trash-alt text-[10px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="16" class="px-8 py-32 text-center bg-slate-50/50">
                                <div class="flex flex-col items-center justify-center opacity-40">
                                    <i class="fas fa-shield-check text-6xl mb-4 text-emerald-500"></i>
                                    <p class="font-black uppercase tracking-[0.3em] text-sm text-slate-800">Tidak Ada Log Kerusakan</p>
                                    <p class="text-[10px] mt-2 font-bold uppercase tracking-widest text-slate-500">Sistem bersih, tidak ada riwayat yang tercatat.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function exportTableToCSV(filename) {
            var csv = [];
            var rows = document.querySelectorAll("#logTable tr");
            
            for (var i = 0; i < rows.length; i++) {
                var row = [];
                // Ambil semua kolom (th dan td)
                var cols = rows[i].querySelectorAll("td, th");
                
                for (var j = 0; j < cols.length; j++) {
                    // Abaikan kolom yang memiliki class 'export-ignore' (Kolom Aksi/Tombol)
                    if (cols[j].classList.contains('export-ignore')) {
                        continue;
                    }
                    
                    // Bersihkan teks dari spasi berlebih dan enter
                    let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").trim();
                    // Hilangkan tanda kutip ganda yang mungkin ada
                    text = text.replace(/"/g, '""');
                    // Bungkus dengan kutipan agar koma dalam teks tidak merusak format CSV
                    row.push('"' + text + '"');
                }
                csv.push(row.join(","));
            }

            downloadCSV(csv.join("\n"), filename);
        }

        function downloadCSV(csv, filename) {
            var csvFile;
            var downloadLink;

            // Buat file CSV
            csvFile = new Blob([csv], {type: "text/csv;charset=utf-8;"});

            // Buat link download tersembunyi
            downloadLink = document.createElement("a");
            downloadLink.download = filename;
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = "none";
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    </script>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>
