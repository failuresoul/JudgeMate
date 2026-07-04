<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'JudgeMate') }}</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Fonts Style Override -->
        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
        </style>

        <!-- Vite Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full antialiased text-slate-100 bg-slate-950 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-slate-950">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <!-- Brand / Logo Header -->
            <div class="flex flex-col items-center gap-3 mb-6">
                <a href="/" class="flex flex-col items-center gap-2 group">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition-transform duration-200">
                        <x-application-logo class="w-10 h-10" />
                    </div>
                </a>
                <div class="text-center">
                    <h2 class="text-2xl font-bold tracking-tight bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent">Judge<span class="text-indigo-500">Mate</span></h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Competitive Programming Judge Platform</p>
                </div>
            </div>

            <!-- Form Card Container -->
            <div class="w-full sm:max-w-md mt-2 px-8 py-8 border border-slate-800/80 bg-slate-900/40 shadow-2xl backdrop-blur-sm sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
