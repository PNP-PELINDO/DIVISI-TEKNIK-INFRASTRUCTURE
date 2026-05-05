@props(['name', 'title' => 'Konfirmasi Tindakan', 'message' => 'Apakah Anda yakin ingin melakukan tindakan ini?'])

<template x-teleport="body">
    <div x-show="show{{ ucfirst($name) }}Modal" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="show{{ ucfirst($name) }}Modal = false"
             x-show="show{{ ucfirst($name) }}Modal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl max-w-sm w-full border border-slate-200 dark:border-slate-800 overflow-hidden">
            <div class="p-10 text-center">
                <div class="w-20 h-20 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 border border-red-100 dark:border-red-800">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight mb-2">{{ $title }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed mb-8">
                    {!! $message !!}
                </p>
                <div class="flex gap-4">
                    <button @click="show{{ ucfirst($name) }}Modal = false" class="flex-1 py-4 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">Batal</button>
                    <form :action="{{ $name }}Url" method="POST" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-900/20 hover:bg-red-700 transition-all">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
