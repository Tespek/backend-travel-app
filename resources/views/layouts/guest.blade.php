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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: {
                            50:'#f0fdf4', 100:'#dcfce7', 200:'#bbf7d0', 300:'#86efac',
                            400:'#4ade80', 500:'#22c55e', 600:'#16a34a', 700:'#15803d',
                            800:'#166534', 900:'#14532d'
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Animated background gradient */
        .auth-bg {
            background: linear-gradient(135deg, #0f2027 0%, #0d3b35 35%, #1a4a3a 60%, #0f3460 100%);
            background-size: 400% 400%;
            animation: gradientShift 12s ease infinite;
        }
        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Floating orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.18;
            animation: floatOrb 8s ease-in-out infinite;
        }
        .orb-1 {
            width: 420px; height: 420px;
            background: radial-gradient(circle, #10b981, #059669);
            top: -100px; left: -100px;
            animation-delay: 0s;
        }
        .orb-2 {
            width: 320px; height: 320px;
            background: radial-gradient(circle, #3b82f6, #1d4ed8);
            bottom: -80px; right: -80px;
            animation-delay: -4s;
        }
        .orb-3 {
            width: 200px; height: 200px;
            background: radial-gradient(circle, #a78bfa, #7c3aed);
            top: 40%; right: 15%;
            animation-delay: -2s;
        }
        @keyframes floatOrb {
            0%, 100% { transform: translateY(0px) scale(1); }
            50%       { transform: translateY(-30px) scale(1.08); }
        }

        /* Dot grid overlay */
        .dot-grid {
            background-image: radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 28px 28px;
        }

        /* Glass card */
        .glass-card {
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.13);
        }

        /* Auth card (white) */
        .auth-card {
            background: rgba(255, 255, 255, 0.97);
            box-shadow: 0 32px 64px -12px rgba(0,0,0,0.45), 0 0 0 1px rgba(255,255,255,0.1);
        }

        /* Input focus style */
        input:focus { outline: none; }
    </style>
</head>
<body class="auth-bg min-h-screen relative overflow-x-hidden flex flex-col items-center justify-center py-12 px-4">

    {{-- Floating background orbs --}}
    <div class="orb orb-1 pointer-events-none"></div>
    <div class="orb orb-2 pointer-events-none"></div>
    <div class="orb orb-3 pointer-events-none"></div>

    {{-- Dot grid texture --}}
    <div class="dot-grid absolute inset-0 pointer-events-none"></div>

    {{-- ===== CONTENT WRAPPER ===== --}}
    <div class="relative z-10 w-full flex flex-col items-center">

        {{-- Brand Header --}}
        <div class="mb-8 text-center">
            <a href="/" class="inline-flex flex-col items-center gap-3 group">
                {{-- Logo icon --}}
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center shadow-2xl shadow-emerald-500/40 group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                {{-- Brand name --}}
                <div>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight drop-shadow-md">
                        SI-TRAVEL
                    </h1>
                    <p class="text-sm text-emerald-300/80 font-medium mt-0.5">
                        Sistem Reservasi & Operasional Multi-Operator
                    </p>
                </div>
            </a>
        </div>

        {{-- ===== AUTH CARD ===== --}}
        <div class="auth-card w-full max-w-md rounded-2xl p-8 sm:p-10">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        <p class="text-xs text-white/30 mt-6 text-center">
            &copy; {{ date('Y') }} Platform Reservasi & Operasional Travel Multi-Operator. All rights reserved.
        </p>
    </div>

</body>
</html>
