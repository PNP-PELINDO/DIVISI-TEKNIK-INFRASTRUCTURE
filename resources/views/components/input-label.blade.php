@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-1']) }}>
    {{ $value ?? $slot }}
</label>
