<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Contractor Estimator') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-800">
            <div class="w-full sm:max-w-md relative">
                <!-- Logo block — positioned absolute, hanging over the card -->
                <div class="absolute left-1/2 -translate-x-1/2 -top-16 z-10">
                    <div class="w-28 h-28 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 shadow-xl flex items-center justify-center ring-4 ring-slate-800">
                        <x-application-logo class="w-16 h-16 fill-current text-white" />
                    </div>
                </div>

                <!-- Card -->
                <div class="mt-20 px-6 pt-16 pb-6 bg-white shadow-lg overflow-hidden sm:rounded-xl">
                    <h1 class="text-center text-xl font-bold text-slate-800 tracking-tight">Contractor Estimator</h1>
                    <p class="text-center text-sm text-slate-400 mb-6">Professional estimates, every time.</p>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
