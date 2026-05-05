@props([
    'options' => [], // Array of ['value' => '...', 'label' => '...', 'description' => '...']
    'name' => '',
    'value' => null,
    'placeholder' => 'Pilih opsi...',
    'label' => null,
    'required' => false,
    'readonly' => false,
    'class' => '',
])

<div 
    x-data="{
        open: false,
        search: '',
        value: '{{ $value }}',
        label: '',
        options: {{ json_encode($options) }},
        get filteredOptions() {
            if (this.search === '') return this.options;
            const s = this.search.toLowerCase();
            return this.options.filter(option => 
                (option.label && String(option.label).toLowerCase().includes(s)) ||
                (option.description && String(option.description).toLowerCase().includes(s)) ||
                (option.value && String(option.value).toLowerCase().includes(s))
            );
        },
        select(option) {
            this.value = option.value;
            this.label = option.label;
            this.open = false;
            this.search = '';
            $dispatch('change', this.value);
        },
        init() {
            const selected = this.options.find(o => o.value == this.value);
            if (selected) {
                this.label = selected.label;
            }
            
            this.$watch('value', (val) => {
                const selected = this.options.find(o => o.value == val);
                if (selected) {
                    this.label = selected.label;
                } else {
                    this.label = '';
                }
            });
        }
    }"
    class="relative {{ $class }}"
    @click.away="open = false"
>
    @if($label)
        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">{{ $label }}</label>
    @endif

    <input type="hidden" name="{{ $name }}" :value="value" @if($required) required @endif>

    <div 
        @click="if(!{{ $readonly ? 'true' : 'false' }}) open = !open"
        class="w-full border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm font-bold p-4 focus-within:ring-4 focus-within:ring-blue-50 dark:focus-within:ring-blue-900/20 focus-within:border-[#003366] dark:focus-within:border-blue-400 transition-all cursor-pointer flex items-center justify-between group {{ $readonly ? 'bg-slate-100 dark:bg-slate-800/50 cursor-not-allowed' : '' }}"
    >
        <span x-text="label || '{{ $placeholder }}'" :class="label ? 'text-slate-700 dark:text-slate-200' : 'text-slate-400 dark:text-slate-500 font-medium'"></span>
        <i class="fas fa-chevron-down text-xs text-slate-400 group-hover:text-[#003366] dark:group-hover:text-blue-400 transition-colors" :class="open ? 'rotate-180' : ''"></i>
    </div>

    <!-- Dropdown Panel -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-[110] mt-2 w-full bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 overflow-hidden"
        style="display: none;"
    >
        <div class="p-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                <input 
                    type="text" 
                    x-model="search" 
                    @keydown.escape="open = false"
                    class="w-full pl-9 pr-4 py-2 bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-700 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/20 focus:border-blue-400 transition-all"
                    placeholder="Ketik untuk mencari..."
                    x-ref="searchInput"
                    @show.window="if(open) $nextTick(() => $refs.searchInput.focus())"
                >
            </div>
        </div>

        <div class="max-h-60 overflow-y-auto custom-scrollbar">
            <template x-for="option in filteredOptions" :key="option.value">
                <div 
                    @click="select(option)"
                    class="px-4 py-3 hover:bg-blue-50 dark:hover:bg-blue-900/30 cursor-pointer transition-colors group"
                    :class="value == option.value ? 'bg-blue-50/50 dark:bg-blue-900/20' : ''"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-black text-slate-700 dark:text-slate-200 uppercase tracking-tight group-hover:text-[#003366] dark:group-hover:text-blue-400" x-text="option.label"></p>
                            <template x-if="option.description">
                                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase mt-0.5" x-text="option.description"></p>
                            </template>
                        </div>
                        <i x-show="value == option.value" class="fas fa-check text-blue-500 text-[10px]"></i>
                    </div>
                </div>
            </template>
            
            <div x-show="filteredOptions.length === 0" class="px-4 py-8 text-center bg-slate-50/30 dark:bg-slate-800/30">
                <i class="fas fa-search text-slate-200 dark:text-slate-700 text-xl mb-2"></i>
                <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Tidak ada hasil</p>
            </div>
        </div>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #334155;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #cbd5e1;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #475569;
}
</style>
