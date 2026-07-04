@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-xs text-slate-400 uppercase tracking-wider mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>
