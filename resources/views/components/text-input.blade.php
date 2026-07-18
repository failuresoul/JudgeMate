@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-2.5 bg-white border border-slate-300 text-slate-900 placeholder-slate-400 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all duration-200 dark:bg-slate-950/60 dark:border-slate-800 dark:text-slate-100 dark:placeholder-slate-600']) }}>
