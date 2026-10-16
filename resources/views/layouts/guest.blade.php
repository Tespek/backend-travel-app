<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Travel Admin') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: { 50:'#eff6ff', 100:'#dbeafe', 200:'#bfdbfe', 300:'#93c5fd', 400:'#60a5fa', 500:'#3b82f6', 600:'#2563eb', 700:'#1d4ed8', 800:'#1e40af', 900:'#1e3a8a' }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-900 antialiased min-h-screen flex flex-col sm:justify-center items-center py-10 px-4">
    <!-- Brand Header -->
    <div class="mb-6 text-center">
        <a href="/" class="inline-flex items-center gap-3 group">
            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-md group-hover:scale-105 transition-transform duration-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
            <div class="text-left">
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">Travel Multi-Operator</h1>
                <p class="text-xs text-slate-500 font-medium">Sistem Reservasi & Operasional</p>
            </div>
        </a>
    </div>

    <!-- Auth Card Box -->
    <div class="w-full sm:max-w-md bg-white shadow-xl shadow-slate-200/50 rounded-2xl border border-slate-200/80 p-8 sm:p-10">
        {{ $slot }}
    </div>

    <!-- Footer -->
    <p class="text-xs text-slate-400 mt-8 text-center">
        &copy; {{ date('Y') }} Platform Reservasi & Operasional Travel Multi-Operator.
    </p>
</body>
</html>
