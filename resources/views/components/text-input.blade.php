@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-slate-200 bg-slate-50 focus:border-[#003366] focus:ring-[#003366] rounded-xl shadow-sm transition-all p-3']) !!}>
