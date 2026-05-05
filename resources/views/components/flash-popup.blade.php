<div x-data="{ 
        show: false, 
        message: '', 
        type: 'success',
        init() {
            @if(session('success'))
                this.message = '{{ session('success') }}';
                this.type = 'success';
                this.show = true;
                setTimeout(() => this.show = false, 5000);
            @elseif(session('error'))
                this.message = '{{ session('error') }}';
                this.type = 'error';
                this.show = true;
            @elseif($errors->any())
                this.message = '{{ $errors->first() }}';
                this.type = 'error';
                this.show = true;
            @endif
        }
     }"
     x-show="show"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
     class="fixed bottom-10 right-10 z-[200] max-w-sm w-full">
    
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border p-6 flex items-start gap-4 relative overflow-hidden"
         :class="type === 'success' ? 'border-emerald-100 dark:border-emerald-900/50' : 'border-red-100 dark:border-red-900/50'">
        
        <div class="absolute top-0 left-0 w-full h-1"
             :class="type === 'success' ? 'bg-emerald-500' : 'bg-red-500'"></div>

        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 text-xl"
             :class="type === 'success' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600' : 'bg-red-50 dark:bg-red-900/20 text-red-600'">
            <i class="fas" :class="type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'"></i>
        </div>

        <div class="flex-1">
            <h4 class="text-xs font-black uppercase tracking-widest mb-1"
                :class="type === 'success' ? 'text-emerald-800 dark:text-emerald-400' : 'text-red-800 dark:text-red-400'"
                x-text="type === 'success' ? 'Berhasil!' : 'Terjadi Kesalahan'"></h4>
            <p class="text-[11px] font-bold text-slate-600 dark:text-slate-400 leading-relaxed" x-text="message"></p>
        </div>

        <button @click="show = false" class="text-slate-300 hover:text-slate-500 transition-colors">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>
</div>
