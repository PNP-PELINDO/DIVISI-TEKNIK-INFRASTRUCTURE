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

    <div class="min-h-screen py-8"
         x-data="{
            showDeleteModal: false,
            deleteUrl: '',
            entityName: ''
         }">

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
                                <h3 class="text-sm font-bold text-slate-900">Konfirmasi Penghapusan</h3>
                                <p class="text-xs text-slate-500 mt-1">Anda yakin ingin menghapus entitas <strong class="text-slate-800" x-text="entityName"></strong>?</p>
                                <p class="text-[10px] text-red-500 font-medium mt-1">Pastikan tidak ada aset yang terikat ke area ini.</p>
                            </div>
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

        <div class="max-w-[1600px] mx-auto w-full px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- HEADER KORPORAT -->
            <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden animate-fade">
                <!-- Aksen Garis Kiri -->
                <div class="absolute left-0 top-0 h-full w-1.5 bg-[#0055a4] rounded-l-lg"></div>

                <div>
                    <h1 class="text-lg font-bold text-[#003366] flex items-center gap-2">
                        <i class="fas fa-building text-[#0055a4]"></i> Manajemen Entitas / Cabang
                    </h1>
                    <p class="text-xs font-medium text-slate-500 mt-1">Pengelolaan data struktur organisasi dan lokasi operasional Pelindo.</p>
                </div>

                <div class="shrink-0">
                    <a href="{{ route('admin.entities.create') }}" class="inline-flex items-center justify-center gap-2 bg-[#0055a4] hover:bg-[#003366] text-white px-4 py-2.5 rounded text-xs font-semibold transition-colors shadow-sm">
                        <i class="fas fa-plus"></i> Tambah Entitas
                    </a>
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

                <div class="overflow-x-auto table-scroll w-full">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                                <th class="px-6 py-4 w-16 text-center">No</th>
                                <th class="px-6 py-4 w-48">Kode Internal</th>
                                <th class="px-6 py-4">Nama Entitas / Cabang</th>
                                <th class="px-6 py-4 w-48 text-center">Total Infrastruktur</th>
                                <th class="px-6 py-4 w-32 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                            @forelse($entities as $index => $entity)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4 text-center font-medium text-slate-500">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="inline-flex items-center justify-center bg-slate-100 border border-slate-200 px-2.5 py-1 rounded text-[#003366] font-mono font-bold text-[11px] uppercase shadow-sm">
                                        {{ $entity->code }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 font-bold text-slate-800 uppercase tracking-wide">
                                    {{ $entity->name }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($entity->infrastructures_count > 0)
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold text-[10px] uppercase tracking-wide">
                                            {{ $entity->infrastructures_count }} Unit Aset
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded bg-slate-50 text-slate-500 border border-slate-200 font-bold text-[10px] uppercase tracking-wide">
                                            Belum Ada Aset
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.entities.edit', $entity->id) }}"
                                           class="w-7 h-7 inline-flex items-center justify-center bg-white border border-slate-300 text-slate-500 hover:bg-slate-50 hover:text-[#0055a4] rounded transition-colors"
                                           title="Edit Entitas">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </a>

                                        <button type="button"
                                                @click="deleteUrl = '{{ route('admin.entities.destroy', $entity->id) }}'; entityName = '{{ addslashes($entity->name) }}'; showDeleteModal = true;"
                                                class="w-7 h-7 inline-flex items-center justify-center bg-white border border-slate-300 text-slate-500 hover:bg-red-50 hover:text-red-600 hover:border-red-200 rounded transition-colors"
                                                title="Hapus Entitas">
                                            <i class="fas fa-trash-alt text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="w-12 h-12 bg-slate-50 border border-slate-200 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-building text-xl text-slate-300"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-700">Belum Ada Data Entitas</p>
                                    <p class="text-xs text-slate-500 mt-1">Silakan tambah cabang atau anak perusahaan baru ke dalam sistem.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- FOOTER INFO -->
            <div class="flex items-center justify-between text-slate-400 pt-2">
                <p class="text-[10px] font-semibold uppercase tracking-wider">&copy; {{ date('Y') }} Pelindo Command Center</p>
            </div>

        </div>
    </div>
</x-app-layout>
