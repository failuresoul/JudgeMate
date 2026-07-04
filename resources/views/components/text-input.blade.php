@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-2.5 bg-slate-950/60 border border-slate-800 text-slate-100 placeholder-slate-600 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all duration-200']) }}>
