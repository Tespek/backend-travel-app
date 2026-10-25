<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $isSuperAdmin = auth()->user()->role === 'super_admin';
        $pendingCount = $isSuperAdmin ? \App\Models\Operator::where('verification_status', 'pending')->count() : 0;
    @endphp

    <title>{{ $title ?? config('app.name', 'Travel App') }} - {{ $isSuperAdmin ? 'Platform Owner Portal' : 'Admin Travel' }}</title>

    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb', // Royal Blue (#2563EB)
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        indigo: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5', // Super Admin Indigo (#4F46E5)
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                            950: '#1e1b4b',
                        },
                        violet: {
                            50: '#f5f3ff',
                            500: '#8b5cf6', // Super Admin Violet (#8B5CF6)
                            600: '#7c3aed',
                            700: '#6d28d9',
                        },
                        secondary: {
                            50: '#ecfdf5',
                            500: '#10b981', // Emerald (#10B981)
                            600: '#059669',
                            700: '#047857',
                        },
                        accent: {
                            50: '#fffbeb',
                            500: '#f59e0b', // Amber (#F59E0B)
                            600: '#d97706',
                        },
                        danger: {
                            50: '#fef2f2',
                            500: '#ef4444', // Red (#EF4444)
                            600: '#dc2626',
                        }
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                        'glow-indigo': '0 0 20px -2px rgba(79, 70, 229, 0.4)',
                        'glow-blue': '0 0 20px -2px rgba(37, 99, 235, 0.35)',
                    }
                }
            }
        }
    </script>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; }
        
        /* Smooth Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Super Admin Dark Sidebar Active Item */
        .sa-nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.875rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #cbd5e1;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        .sa-nav-item:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.08);
        }
        .sa-nav-item.active {
            color: #ffffff;
            background: #4f46e5;
            box-shadow: 0 4px 12px -2px rgba(79, 70, 229, 0.45);
            border-right: 4px solid #a78bfa;
            font-weight: 600;
        }
        .sa-nav-item.active svg {
            color: #ffffff;
        }
        .sa-nav-item:not(.active) svg {
            color: #64748b;
            transition: color 0.2s;
        }
        .sa-nav-item:hover svg {
            color: #c7d2fe;
        }

        /* Admin Travel Light/Blue Sidebar Active Item */
        .at-nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.875rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #475569;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        .at-nav-item:hover {
            color: #1e40af;
            background-color: #f0f7ff;
        }
        .at-nav-item.active {
            color: #ffffff;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            box-shadow: 0 4px 12px -2px rgba(37, 99, 235, 0.35);
            font-weight: 600;
        }
        .at-nav-item.active svg {
            color: #ffffff;
        }
        .at-nav-item:not(.active) svg {
            color: #64748b;
            transition: color 0.2s;
        }
        .at-nav-item:hover svg {
            color: #2563eb;
        }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-900 antialiased font-sans {{ $isSuperAdmin ? 'selection:bg-indigo-600' : 'selection:bg-primary-600' }} selection:text-white">

<div x-data="{ 
    mobileSidebarOpen: false,
    notificationsOpen: false,
    userDropdownOpen: false 
}" class="min-h-full flex flex-col lg:flex-row">

    <!-- ===== BACKDROP MOBILE SIDEBAR ===== -->
    <div x-show="mobileSidebarOpen" 
         x-cloak 
         @click="mobileSidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-slate-950/70 backdrop-blur-sm lg:hidden"></div>

    <!-- ========================================================================= -->
    <!-- ========================== SIDEBAR NAVIGATION =========================== -->
    <!-- ========================================================================= -->
    <aside :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col justify-between transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto lg:h-screen lg:flex-shrink-0 shadow-2xl lg:shadow-none
                  {{ $isSuperAdmin ? 'bg-slate-950 border-r border-slate-800' : 'bg-white border-r border-slate-200/90 shadow-xs' }}">

        <!-- Top Section: Brand & Nav Links -->
        <div class="flex flex-col h-full overflow-hidden">
            
            <!-- BRAND / LOGO HEADER -->
            <div class="flex items-center justify-between px-5 py-5 flex-shrink-0 border-b
                        {{ $isSuperAdmin ? 'border-slate-800/90 bg-slate-950' : 'border-slate-100 bg-white' }}">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    @if($isSuperAdmin)
                        <!-- Icon Super Admin: Indigo/Violet Shield -->
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-700 to-violet-600 flex items-center justify-center text-white shadow-lg shadow-indigo-600/30 group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="font-heading font-black text-white text-sm tracking-tight">TRAVEL PLATFORM</span>
                            </div>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-violet-500/20 text-violet-300 border border-violet-500/30">SUPER ADMIN</span>
                                <span class="text-[10px] text-slate-400">Owner</span>
                            </div>
                        </div>
                    @else
                        <!-- Icon Admin Travel: Royal Blue Bus -->
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-primary-600 to-blue-400 flex items-center justify-center text-white shadow-lg shadow-primary-600/30 group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="font-heading font-extrabold text-slate-900 text-base tracking-tight">SI-TRAVEL</span>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-primary-50 text-primary-600 border border-primary-200">MITRA</span>
                            </div>
                            <p class="text-[11px] text-slate-400 font-medium">Multi-Operator System</p>
                        </div>
                    @endif
                </a>

                <!-- Mobile Close Button -->
                <button @click="mobileSidebarOpen = false" 
                        class="lg:hidden p-1.5 rounded-lg {{ $isSuperAdmin ? 'text-slate-400 hover:text-white hover:bg-slate-800' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Operator Travel Context Banner (Khusus Admin Travel) -->
            @if(!$isSuperAdmin && auth()->user()->operator)
            <div class="px-4 pt-3 pb-1 flex-shrink-0">
                <div class="px-3 py-2 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center gap-2.5">
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Mitra Travel</p>
                        <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->operator->name }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- NAVIGATION LINKS (SCROLLABLE) -->
            <nav class="flex-1 overflow-y-auto px-3.5 py-4 space-y-6">

                @if($isSuperAdmin)
                {{-- ========================================================================= --}}
                {{-- =========================== SUPER ADMIN MENU ============================ --}}
                {{-- ========================================================================= --}}

                <!-- MENU UTAMA -->
                <div class="space-y-1">
                    <p class="px-3 text-[10px] font-bold text-indigo-400 uppercase tracking-wider">Pusat Analitik</p>
                    
                    <a href="{{ route('dashboard') }}" class="sa-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span>Dashboard Platform</span>
                    </a>
                </div>

                <!-- MANAJEMEN MITRA OPERATOR -->
                <div class="space-y-1">
                    <p class="px-3 text-[10px] font-bold text-indigo-400 uppercase tracking-wider">Ekosistem Platform</p>

                    <a href="{{ route('super-admin.operators.index') }}" class="sa-nav-item {{ request()->routeIs('super-admin.operators.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="flex-1">Operator Travel</span>
                        @if($pendingCount > 0)
                            <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('super-admin.users.index') }}" class="sa-nav-item {{ request()->routeIs('super-admin.users.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Pengguna Global</span>
                    </a>

                    <a href="{{ route('super-admin.cities.index') }}" class="sa-nav-item {{ request()->routeIs('super-admin.cities.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Kota & Wilayah</span>
                    </a>
                </div>

                <!-- AUDIT TRANSAKSI & LAPORAN -->
                <div class="space-y-1">
                    <p class="px-3 text-[10px] font-bold text-indigo-400 uppercase tracking-wider">Audit & Finansial</p>

                    <a href="{{ route('super-admin.reports.index') }}" class="sa-nav-item {{ request()->routeIs('super-admin.reports.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span>Laporan Fee Platform</span>
                    </a>
                </div>

                @else
                {{-- ========================================================================= --}}
                {{-- =========================== ADMIN TRAVEL MENU =========================== --}}
                {{-- ========================================================================= --}}

                <!-- MENU UTAMA -->
                <div class="space-y-1">
                    <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Menu Utama</p>
                    
                    <a href="{{ route('dashboard') }}" class="at-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </div>

                <!-- ARMADA & RUTE -->
                <div class="space-y-1">
                    <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Armada & Rute</p>

                    <a href="{{ route('admin-travel.vehicles.index') }}" class="at-nav-item {{ request()->routeIs('admin-travel.vehicles.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8m-8 4h8m-4 4h4m-8 0H8m0 0H5.2A2.2 2.2 0 013 12.8V6.2A2.2 2.2 0 015.2 4h13.6A2.2 2.2 0 0121 6.2v6.6a2.2 2.2 0 01-2.2 2.2H16"/>
                        </svg>
                        <span>Armada Mobil</span>
                    </a>

                    <a href="{{ route('admin-travel.routes.index') }}" class="at-nav-item {{ request()->routeIs('admin-travel.routes.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        <span>Rute Perjalanan</span>
                    </a>

                    <a href="{{ route('admin-travel.pickup-points.index') }}" class="at-nav-item {{ request()->routeIs('admin-travel.pickup-points.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Pool & Penjemputan</span>
                    </a>

                    <a href="{{ route('admin-travel.schedules.index') }}" class="at-nav-item {{ request()->routeIs('admin-travel.schedules.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Jadwal Keberangkatan</span>
                    </a>
                </div>

                <!-- OPERASIONAL & LAYANAN -->
                <div class="space-y-1">
                    <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Layanan Penumpang</p>

                    <a href="{{ route('admin-travel.drivers.index') }}" class="at-nav-item {{ request()->routeIs('admin-travel.drivers.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Manajemen Driver</span>
                    </a>

                    <a href="{{ route('admin-travel.bookings.index') }}" class="at-nav-item {{ request()->routeIs('admin-travel.bookings.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span>Reservasi Tiket</span>
                    </a>
                </div>

                <!-- LAPORAN KEUANGAN -->
                <div class="space-y-1">
                    <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Keuangan</p>

                    <a href="{{ route('admin-travel.reports.index') }}" class="at-nav-item {{ request()->routeIs('admin-travel.reports.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span>Laporan Keuangan</span>
                    </a>
                </div>

                <!-- PROFIL MITRA -->
                <div class="space-y-1">
                    <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Profil Mitra</p>

                    <a href="{{ route('admin-travel.operator-profile.show') }}" class="at-nav-item {{ request()->routeIs('admin-travel.operator-profile.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Profil Mitra Travel</span>
                    </a>
                </div>
                @endif

            </nav>

            <!-- USER PROFILE BOTTOM CARD -->
            <div class="p-3.5 border-t flex-shrink-0
                        {{ $isSuperAdmin ? 'border-slate-800 bg-slate-950/80' : 'border-slate-100 bg-white' }}">
                <div class="p-2.5 rounded-xl flex items-center justify-between gap-3
                            {{ $isSuperAdmin ? 'bg-slate-900 border border-slate-800' : 'bg-slate-50 border border-slate-200/80' }}">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-xs shadow-md flex-shrink-0
                                    {{ $isSuperAdmin ? 'bg-gradient-to-tr from-indigo-600 to-violet-600' : 'bg-gradient-to-tr from-blue-600 to-indigo-600' }}">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold truncate {{ $isSuperAdmin ? 'text-white' : 'text-slate-900' }}">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] truncate {{ $isSuperAdmin ? 'text-indigo-300' : 'text-slate-500' }}">
                                {{ $isSuperAdmin ? 'Super Administrator' : 'Admin Travel' }}
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Keluar dari Aplikasi" 
                                class="p-2 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-500/10 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </aside>

    <!-- ========================================================================= -->
    <!-- ======================== MAIN CONTENT WRAPPER =========================== -->
    <!-- ========================================================================= -->
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">

        <!-- ===== TOP NAVBAR ===== -->
        <header class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 h-16 px-4 sm:px-6 flex items-center justify-between flex-shrink-0 z-10 shadow-2xs">
            
            <!-- Left: Mobile Trigger & Breadcrumbs -->
            <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                <button @click="mobileSidebarOpen = true" 
                        class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors focus:outline-hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Breadcrumbs -->
                <div class="flex items-center gap-2 text-sm">
                    <span class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold {{ $isSuperAdmin ? 'text-indigo-600' : 'text-primary-600' }}">
                        @if($isSuperAdmin)
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Platform Master
                        @else
                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Travel Panel
                        @endif
                    </span>
                    <span class="hidden sm:inline text-slate-300">/</span>
                    <h1 class="text-base sm:text-lg font-heading font-bold text-slate-800 truncate">
                        {{ $header ?? 'Dashboard' }}
                    </h1>
                </div>
            </div>

            <!-- Right: Date, Notifications, Profile Dropdown -->
            <div class="flex items-center gap-2 sm:gap-3">
                
                <!-- Date Pill -->
                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-xs font-medium border border-slate-200/80">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
                </div>

                <!-- Notifications Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="relative p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors focus:outline-hidden">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>

                        <!-- Notification indicator badge -->
                        @if($isSuperAdmin && $pendingCount > 0)
                            <span class="absolute top-1 right-1 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 ring-2 ring-white"></span>
                            </span>
                        @else
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full {{ $isSuperAdmin ? 'bg-indigo-600' : 'bg-primary-600' }} ring-2 ring-white"></span>
                        @endif
                    </button>

                    <!-- Notification Menu -->
                    <div x-show="open" 
                         x-cloak 
                         @click.outside="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-200/90 py-2 z-50 overflow-hidden">
                        <div class="px-4 py-2.5 border-b border-slate-100 flex items-center justify-between">
                            <p class="font-heading font-bold text-xs text-slate-800 uppercase tracking-wider">Notifikasi Sistem</p>
                            @if($isSuperAdmin && $pendingCount > 0)
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700">
                                    {{ $pendingCount }} Menunggu
                                </span>
                            @else
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600">Sistem Normal</span>
                            @endif
                        </div>
                        <div class="divide-y divide-slate-100 max-h-64 overflow-y-auto">
                            @if($isSuperAdmin && $pendingCount > 0)
                            <a href="{{ route('super-admin.operators.index', ['status' => 'pending']) }}" class="p-3.5 hover:bg-amber-50/50 transition-colors flex items-start gap-3 block">
                                <div class="w-7 h-7 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-amber-900">{{ $pendingCount }} Operator Butuh Verifikasi</p>
                                    <p class="text-[11px] text-amber-700 mt-0.5">Tinjau kesiapan pendaftaran dan persetujuan mitra travel baru.</p>
                                    <span class="text-[10px] text-amber-600 font-semibold mt-1 block">Klik untuk review →</span>
                                </div>
                            </a>
                            @endif

                            <div class="p-3.5 hover:bg-slate-50 transition-colors flex items-start gap-3">
                                <div class="w-7 h-7 rounded-lg {{ $isSuperAdmin ? 'bg-indigo-50 text-indigo-600' : 'bg-blue-50 text-blue-600' }} flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-semibold text-slate-800">Sinkronisasi Server Aktif</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Gateway pembayaran dan database reservasi terhubung baik.</p>
                                    <span class="text-[10px] text-slate-400 mt-1 block">Real-time</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Separator -->
                <div class="h-6 w-px bg-slate-200"></div>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-slate-100 transition-colors focus:outline-hidden">
                        <div class="w-8 h-8 rounded-xl text-white font-bold text-xs flex items-center justify-center shadow-xs
                                    {{ $isSuperAdmin ? 'bg-gradient-to-tr from-indigo-600 to-violet-600' : 'bg-gradient-to-tr from-primary-600 to-blue-500' }}">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-slate-800 leading-tight truncate max-w-[120px]">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] font-medium capitalize {{ $isSuperAdmin ? 'text-indigo-600 font-semibold' : 'text-slate-500' }}">
                                {{ $isSuperAdmin ? 'Super Administrator' : 'Admin Travel' }}
                            </p>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         x-cloak 
                         @click.outside="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-200/90 py-1.5 z-50 overflow-hidden">
                        
                        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
                            <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email }}</p>
                        </div>

                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Profil Akun
                            </a>

                            @if(!$isSuperAdmin)
                            <a href="{{ route('admin-travel.operator-profile.show') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Profil Mitra Travel
                            </a>
                            @endif
                        </div>

                        <div class="border-t border-slate-100 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Keluar (Logout)
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <!-- ===== TOAST FLASH NOTIFICATIONS ===== -->
        <div class="fixed top-20 right-6 z-50 space-y-2 pointer-events-none max-w-sm w-full">
            @if(session('success'))
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-init="setTimeout(() => show = false, 4500)"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="pointer-events-auto flex items-start gap-3 bg-white border border-emerald-200/90 shadow-xl rounded-2xl p-4 text-slate-800">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Sukses</p>
                        <p class="text-xs text-slate-600 mt-0.5">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-init="setTimeout(() => show = false, 5000)"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     class="pointer-events-auto flex items-start gap-3 bg-white border border-rose-200/90 shadow-xl rounded-2xl p-4 text-slate-800">
                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-rose-800 uppercase tracking-wider">Terjadi Kesalahan</p>
                        <p class="text-xs text-slate-600 mt-0.5">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif
        </div>

        <!-- ===== SCROLLABLE BODY CONTENT ===== -->
        <main class="flex-1 overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                {{ $slot }}
            </div>
        </main>

    </div>

</div>

@stack('scripts')
</body>
</html>
