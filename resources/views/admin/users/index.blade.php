<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

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
            userName: ''
         }">

        <!-- MODAL DELETE (Enterprise Style, Konsisten) -->
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
                                <p class="text-xs text-slate-500 mt-1">Anda yakin ingin menghapus akses akun <strong class="text-slate-800" x-text="userName"></strong>? Data ini akan dihapus secara permanen.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 flex justify-end gap-2">
                        <button @click="showDeleteModal = false" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded text-xs font-semibold hover:bg-slate-50 transition-colors">Batal</button>
                        <form :action="deleteUrl" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded text-xs font-semibold hover:bg-red-700 transition-colors shadow-sm">Hapus Akun</button>
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
                        <i class="fas fa-users-gear text-[#0055a4]"></i> Manajemen Akun (Users)
                    </h1>
                    <p class="text-xs font-medium text-slate-500 mt-1">Kelola direktori hak akses personel dan otorisasi wilayah terminal Pelindo.</p>
                </div>

                <div class="shrink-0">
                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-2 bg-[#0055a4] hover:bg-[#003366] text-white px-4 py-2.5 rounded text-xs font-semibold transition-colors shadow-sm">
                        <i class="fas fa-user-plus"></i> Tambah Pengguna Baru
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

            @php
                // Mengelompokkan koleksi users berdasarkan field 'role'
                $groupedUsers = collect($users)->groupBy('role');
            @endphp

            <div class="space-y-6 animate-fade" style="animation-delay: 100ms;">
                @forelse($groupedUsers as $role => $roleUsers)
                    @php
                        $isSuperadmin = strtolower($role) === 'superadmin';
                        $headerBg = $isSuperadmin ? 'bg-amber-50/50' : 'bg-slate-50/50';
                        $iconClass = $isSuperadmin ? 'fa-user-shield text-amber-500' : 'fa-user-tie text-[#0055a4]';
                    @endphp

                    <!-- TABLE CONTAINER PER ROLE -->
                    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden flex flex-col">

                        <!-- Role Header Divider -->
                        <div class="{{ $headerBg }} px-6 py-3 flex items-center justify-between border-b border-slate-200">
                            <div class="flex items-center gap-2.5">
                                <i class="fas {{ $iconClass }} text-sm"></i>
                                <h2 class="font-bold text-slate-800 text-xs uppercase tracking-widest">
                                    Akses: {{ $role }}
                                </h2>
                            </div>
                            <span class="inline-flex items-center justify-center px-2 py-1 rounded bg-white text-slate-500 border border-slate-200 font-bold text-[10px] uppercase tracking-wide shadow-sm">
                                {{ $roleUsers->count() }} Terdaftar
                            </span>
                        </div>

                        <div class="overflow-x-auto table-scroll w-full">
                            <table class="w-full text-left border-collapse min-w-[800px]">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                                        <th class="px-6 py-4 text-center w-16">No</th>
                                        <th class="px-6 py-4 w-1/3">Informasi Pegawai</th>
                                        <th class="px-6 py-4 w-1/3">Penempatan Area (Entitas)</th>
                                        <th class="px-6 py-4 w-32 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                                    @foreach($roleUsers as $index => $user)
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <td class="px-6 py-4 text-center font-medium text-slate-500">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="font-bold text-[#003366] uppercase tracking-wide">{{ $user->name }}</div>
                                            <div class="text-[10px] text-slate-500 mt-1 flex items-center gap-1.5">
                                                <i class="far fa-envelope"></i> {{ $user->email }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            @if($user->entity)
                                                <span class="inline-flex items-center justify-center bg-slate-100 border border-slate-200 px-2.5 py-1 rounded text-[#003366] font-bold text-[10px] uppercase tracking-wide shadow-sm gap-1.5">
                                                    <i class="fas fa-map-marker-alt text-[#0055a4]"></i> {{ $user->entity->name }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded text-emerald-700 font-bold text-[10px] uppercase tracking-wide shadow-sm gap-1.5">
                                                    <i class="fas fa-globe text-emerald-500"></i> Kantor Pusat / Global
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                                   class="w-7 h-7 inline-flex items-center justify-center bg-white border border-slate-300 text-slate-500 hover:bg-slate-50 hover:text-[#0055a4] rounded transition-colors"
                                                   title="Edit Akun">
                                                    <i class="fas fa-pen text-[10px]"></i>
                                                </a>

                                                @if($user->id !== auth()->id())
                                                    <button type="button"
                                                            @click="deleteUrl = '{{ route('admin.users.destroy', $user->id) }}'; userName = '{{ addslashes($user->name) }}'; showDeleteModal = true;"
                                                            class="w-7 h-7 inline-flex items-center justify-center bg-white border border-slate-300 text-slate-500 hover:bg-red-50 hover:text-red-600 hover:border-red-200 rounded transition-colors"
                                                            title="Hapus Akun">
                                                        <i class="fas fa-trash-alt text-[10px]"></i>
                                                    </button>
                                                @else
                                                    <!-- Indikator tidak bisa hapus diri sendiri -->
                                                    <span class="w-7 h-7 inline-flex items-center justify-center bg-slate-50 border border-slate-200 text-slate-300 rounded cursor-not-allowed" title="Sedang Aktif (Current User)">
                                                        <i class="fas fa-user-check text-[10px]"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <!-- KONDISI KOSONG -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-16 text-center">
                        <div class="w-12 h-12 bg-slate-50 border border-slate-200 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-users-slash text-xl text-slate-300"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">Belum Ada Pengguna Lain</p>
                        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Sistem belum memiliki data akun terdaftar selain Administrator Utama.</p>
                        <a href="{{ route('admin.users.create') }}" class="mt-4 inline-flex bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded text-xs font-semibold shadow-sm transition-colors">
                            Tambah Personel
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- FOOTER INFO -->
            <div class="flex items-center justify-between text-slate-400 pt-2">
                <p class="text-[10px] font-semibold uppercase tracking-wider">&copy; {{ date('Y') }} Pelindo Command Center</p>
            </div>

        </div>
    </div>
</x-app-layout>
