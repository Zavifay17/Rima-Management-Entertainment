<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RME Dashboard') - Admin Panel</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png?v=3') }}">
    
    <!-- Google Fonts & FontAwesome Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-primary: #ffffff; /* White */
            --bg-secondary: #f8fafc; /* Very light gray for panels */
            --border-color: rgba(0, 0, 128, 0.08); /* Navy tint border */
            --text-primary: #000000; /* Black */
            --text-secondary: #334155; /* Dark gray */
            --accent-primary: #000080; /* RME Navy Blue */
            --accent-secondary: #ff0000; /* RME Red */
            --success: #10b981; /* Emerald Green */
            --warning: #f59e0b; /* Amber */
            --danger: #ff0000; /* Red */
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-blur: blur(24px);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Print Styles */
        @media print {
            aside {
                display: none !important;
            }
            main {
                margin-left: 0 !important;
                padding: 1rem !important;
                background: white !important;
            }
            body {
                background: white !important;
            }
            .btn, .btn-primary, .btn-success, .btn-outline, .btn-detail, .modal-backdrop {
                display: none !important;
            }
            .glass-card {
                box-shadow: none !important;
                border: 1px solid #ccc !important;
            }
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
            background-image: 
                radial-gradient(at 0% 0%, rgba(0, 0, 128, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(255, 0, 0, 0.05) 0px, transparent 50%);
            background-attachment: fixed;
        }

        /* Sidebar Styling */
        aside {
            width: 280px;
            background: var(--bg-secondary);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border-right: 1px solid var(--border-color);
            padding: 2.5rem 1.5rem;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            margin-bottom: 3.5rem;
            padding-left: 0.5rem;
        }

        .brand-logo {
            width: auto;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: 900;
            font-family: 'Outfit', sans-serif;
            font-style: italic;
            letter-spacing: -1px;
            background: transparent;
            box-shadow: none;
        }
        
        .brand-logo span:nth-child(1) { color: var(--accent-primary); letter-spacing: -3px; } /* R */
        .brand-logo span:nth-child(2) { color: var(--accent-primary); } /* M */
        .brand-logo span:nth-child(3) { color: var(--accent-secondary); } /* E */

        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            color: #000000;
            line-height: 1.1;
            text-transform: uppercase;
        }
        
        .brand-subtitle {
            display: block;
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 2px;
            color: #000000;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
            list-style: none;
            flex: 1;
            overflow-y: auto;
            padding-right: 0.5rem; /* space for scrollbar */
        }

        /* Slim scrollbar specifically for the sidebar menu */
        .nav-menu::-webkit-scrollbar {
            width: 4px;
        }
        .nav-menu::-webkit-scrollbar-track {
            background: transparent;
        }
        .nav-menu::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 128, 0.1);
            border-radius: 4px;
        }
        .nav-menu::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 128, 0.2);
        }

        /* ===== SIDEBAR ANIMATIONS ===== */

        /* Sidebar slide-in on load */
        aside {
            animation: sidebarSlideIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes sidebarSlideIn {
            from { transform: translateX(-100%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }

        /* Staggered nav items */
        .nav-item {
            opacity: 0;
            animation: navItemIn 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes navItemIn {
            from { opacity: 0; transform: translateX(-18px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* Each item gets a delay */
        .nav-item:nth-child(1)  { animation-delay: 0.10s; }
        .nav-item:nth-child(2)  { animation-delay: 0.18s; }
        .nav-item:nth-child(3)  { animation-delay: 0.26s; }
        .nav-item:nth-child(4)  { animation-delay: 0.34s; }
        .nav-item:nth-child(5)  { animation-delay: 0.42s; }
        .nav-item:nth-child(6)  { animation-delay: 0.50s; }
        .nav-item:nth-child(7)  { animation-delay: 0.58s; }
        .nav-item:nth-child(8)  { animation-delay: 0.66s; }
        .nav-item:nth-child(9)  { animation-delay: 0.74s; }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 1.1rem;
            padding: 0.95rem 1.25rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 14px;
            font-weight: 500;
            transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
        }

        /* Ripple effect layer */
        .nav-item a::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 14px;
            background: radial-gradient(circle at var(--rx, 50%) var(--ry, 50%), rgba(99,102,241,0.18) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }

        .nav-item a:hover::after {
            opacity: 1;
        }

        /* Icon animation on hover */
        .nav-item a i {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.3s ease;
            font-size: 1.05rem;
            width: 20px;
            text-align: center;
        }

        .nav-item a:hover i {
            transform: scale(1.25) rotate(-5deg);
            color: #6366f1;
        }

        /* Text slide on hover */
        .nav-item a span {
            transition: transform 0.25s ease, letter-spacing 0.25s ease;
            display: inline-block;
        }

        .nav-item a:hover span {
            transform: translateX(3px);
            letter-spacing: 0.01em;
        }

        .nav-item a:hover {
            color: var(--text-primary);
            background: rgba(99, 102, 241, 0.06);
            border-color: rgba(99, 102, 241, 0.14);
            box-shadow: 0 2px 12px rgba(99, 102, 241, 0.08);
            transform: translateX(3px);
        }

        /* Active item — glowing highlight */
        .nav-item.active a {
            background: linear-gradient(135deg, rgba(0,0,128,0.09), rgba(99,102,241,0.08));
            border-color: rgba(0, 0, 128, 0.18);
            color: var(--accent-primary);
            box-shadow: 0 4px 16px rgba(0,0,128,0.1), inset 0 0 0 1px rgba(0,0,128,0.08);
        }

        /* Active left bar indicator */
        .nav-item.active a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            height: 60%;
            width: 3px;
            border-radius: 0 4px 4px 0;
            background: linear-gradient(180deg, var(--accent-primary), #6366f1);
            animation: activeBarPulse 2s ease-in-out infinite;
        }

        @keyframes activeBarPulse {
            0%, 100% { opacity: 1; box-shadow: 0 0 6px rgba(0,0,128,0.4); }
            50%       { opacity: 0.6; box-shadow: 0 0 12px rgba(99,102,241,0.6); }
        }

        .nav-item.active a i {
            color: var(--accent-primary);
            filter: drop-shadow(0 0 6px rgba(0,0,128,0.35));
            animation: iconPulse 2.5s ease-in-out infinite;
        }

        @keyframes iconPulse {
            0%, 100% { transform: scale(1); }
            50%       { transform: scale(1.12); }
        }

        .nav-footer {
            margin-top: auto;
            padding-top: 1.25rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(59, 130, 246, 0.05);
            border: 2px solid var(--accent-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--accent-primary);
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.15);
        }

        .user-info h4 {
            font-size: 0.875rem;
            color: var(--text-primary);
            font-weight: 600;
        }

        .user-info p {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 0.15rem;
        }

        /* Main Workspace */
        main {
            margin-left: 280px;
            flex: 1;
            padding: 3rem 4rem;
            min-height: 100vh;
            min-width: 0;
        }

        /* Premium Shared UI Elements */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 2.25rem;
            box-shadow: 0 20px 50px rgba(59, 130, 246, 0.04);
            position: relative;
            overflow: hidden;
            margin-bottom: 2.5rem;
        }

        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
            opacity: 0.9;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            animation: fadeInHeader 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeInHeader {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .page-title h1 {
            font-size: 2.25rem;
            font-weight: 700;
            color: #000000;
            margin-bottom: 0.5rem;
            font-family: 'Outfit', sans-serif;
        }

        .page-title p {
            color: var(--text-secondary);
            font-size: 0.975rem;
        }

        /* Premium Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            padding: 0.85rem 1.75rem;
            border-radius: 14px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            color: white;
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
            filter: brightness(1.05);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.25);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
            filter: brightness(1.05);
        }

        .btn-outline {
            background: rgba(59, 130, 246, 0.06);
            border: 1px solid rgba(59, 130, 246, 0.25);
            color: var(--accent-primary);
        }

        .btn-outline:hover {
            background: rgba(59, 130, 246, 0.12);
            border-color: var(--accent-primary);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
            color: var(--accent-primary);
        }

        /* ===== GLOBAL: btn-detail - Abu-abu elegan ===== */
        .btn-detail {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.42rem 1.05rem;
            font-size: 0.83rem;
            font-weight: 600;
            border-radius: 10px;
            background: #f1f1f3;
            color: #4b5563;
            border: 1px solid #d1d5db;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.22s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            white-space: nowrap;
        }

        .btn-detail:hover {
            background: #e5e7eb;
            color: #1f2937;
            border-color: #9ca3af;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transform: translateY(-1px);
        }

        .btn-detail i {
            font-size: 0.75rem;
            opacity: 0.65;
        }

        /* ===== GLOBAL: price-badge - Nunito bubble ===== */
        .price-badge {
            font-family: 'Nunito', sans-serif !important;
            font-weight: 800;
            display: inline-flex;
            align-items: baseline;
            gap: 3px;
        }

        .price-badge .rp-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            background: linear-gradient(135deg, #10b981, #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .price-badge .rp-amount {
            font-size: 1rem;
            font-weight: 900;
            letter-spacing: -0.01em;
            background: linear-gradient(135deg, #10b981, #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Fallback jika pakai text biasa */
        .price-badge:not(:has(.rp-label)) {
            color: #059669;
            font-size: 1rem;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            border: 1px solid transparent;
        }

        .badge-pending {
            background: rgba(245, 158, 11, 0.08);
            color: var(--warning);
            border-color: rgba(245, 158, 11, 0.18);
        }

        .badge-process {
            background: rgba(59, 130, 246, 0.08);
            color: var(--accent-primary);
            border-color: rgba(59, 130, 246, 0.18);
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.08);
            color: var(--success);
            border-color: rgba(16, 185, 129, 0.18);
        }

        /* Dynamic Alerts */
        .alert {
            padding: 1.1rem 1.6rem;
            border-radius: 14px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1.1rem;
            font-weight: 500;
            animation: alertFadeIn 0.4s ease;
        }

        @keyframes alertFadeIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-primary);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.15);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.3);
        }
        @media (max-width: 768px) {
            aside {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            aside.open {
                transform: translateX(0) !important;
                box-shadow: 10px 0 30px rgba(0,0,0,0.1);
            }
            main {
                margin-left: 0 !important;
                padding: 5rem 1rem 2rem 1rem !important;
            }
            .mobile-header {
                display: flex !important;
            }
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            .page-title h1 {
                font-size: 1.5rem;
            }
            .glass-card {
                padding: 1.2rem;
            }
            .mobile-close-btn {
                display: block !important;
            }
            .brand {
                justify-content: space-between;
                width: 100%;
                padding-right: 0.5rem;
            }
        }
        
        .mobile-close-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-primary);
            cursor: pointer;
            padding: 0.25rem;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 99;
            backdrop-filter: blur(2px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }
        
        .mobile-header {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border-bottom: 1px solid var(--border-color);
            z-index: 99;
            align-items: center;
            padding: 0 1rem;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .mobile-header-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-primary);
            cursor: pointer;
            padding: 0.5rem;
        }
        
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="mobile-header">
        <div class="brand-logo" style="height:30px; font-size:1.2rem;">
            <span>R</span><span>M</span><span>E</span>
        </div>
        <button class="mobile-header-btn" id="mobileMenuToggle">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    <!-- Sidebar Navigation -->
    <aside>
        <div class="brand">
            <div style="display: flex; align-items: center; gap: 0.85rem;">
                <div class="brand-logo">
                    <span>R</span><span>M</span><span>E</span>
                </div>
            </div>
            <button type="button" class="mobile-close-btn" id="closeSidebarBtn">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <ul class="nav-menu">
            <li class="nav-item {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Dasbor</span>
                </a>
            </li>

            @if(session('admin_role') !== 'superadmin')
            <li class="nav-item {{ Request::routeIs('admin.order.*') ? 'active' : '' }}">
                <a href="{{ route('admin.order.index') }}">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Daftar Pemesanan</span>
                </a>
            </li>
            <li class="nav-item {{ Request::routeIs('admin.driver.*') ? 'active' : '' }}">
                <a href="{{ route('admin.driver.index') }}">
                    <i class="fa-solid fa-id-card"></i>
                    <span>Manajemen Driver</span>
                </a>
            </li>
            <li class="nav-item {{ Request::routeIs('admin.pengiriman.index') || Request::routeIs('admin.pengiriman.create') ? 'active' : '' }}">
                <a href="{{ route('admin.pengiriman.index') }}">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                    <span>Alokasi Pengiriman</span>
                </a>
            </li>
            <li class="nav-item {{ Request::routeIs('admin.pengiriman.calendar') ? 'active' : '' }}">
                <a href="{{ route('admin.pengiriman.calendar') }}">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Kalender Event</span>
                </a>
            </li>
            <li class="nav-item {{ Request::routeIs('admin.layanan.*') ? 'active' : '' }}">
                <a href="{{ route('admin.layanan.index') }}">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Katalog Alat</span>
                </a>
            </li>
            <li class="nav-item {{ Request::routeIs('admin.pelanggan.*') ? 'active' : '' }}">
                <a href="{{ route('admin.pelanggan.index') }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Direktori Pelanggan</span>
                </a>
            </li>
            @endif

            @if(session('admin_role') === 'superadmin')
            <li class="nav-item {{ Request::routeIs('admin.finance.*') ? 'active' : '' }}">
                <a href="{{ route('admin.finance.index') }}">
                    <i class="fa-solid fa-wallet"></i>
                    <span>Laporan Keuangan</span>
                </a>
            </li>
            <li class="nav-item {{ Request::routeIs('admin.report.orders') ? 'active' : '' }}">
                <a href="{{ route('admin.report.orders') }}">
                    <i class="fa-solid fa-map-location-dot"></i>
                    <span>Laporan Pemesanan</span>
                </a>
            </li>
            <li class="nav-item {{ Request::routeIs('admin.manage-admin.*') ? 'active' : '' }}">
                <a href="{{ route('admin.manage-admin.index') }}">
                    <i class="fa-solid fa-user-tie"></i>
                    <span>Kelola Admin</span>
                </a>
            </li>
            @endif
        </ul>

        <div class="nav-footer">
            <div class="user-avatar" title="{{ session('admin_role') === 'superadmin' ? 'Superadmin' : 'Admin' }}">
                {{ strtoupper(substr(session('admin_nama', 'AD'), 0, 2)) }}
            </div>
            <div class="user-info" style="flex: 1; min-width: 0;">
                <h4 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ session('admin_nama', 'Administrator') }}</h4>
                <p>{{ session('admin_role') === 'superadmin' ? 'Super Admin' : 'Admin Utama' }}</p>
            </div>
            <a href="{{ route('logout') }}" title="Keluar / Logout" style="color: var(--danger); font-size: 1.25rem; transition: transform 0.2s; display: flex; align-items: center; justify-content: center; text-decoration: none;" onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>
    </aside>

    <!-- Main Workspace -->
    <main>
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @yield('content')
    </main>

    @yield('scripts')

    <script>
    // ===== Mobile Sidebar Toggle =====
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const closeSidebarBtn = document.getElementById('closeSidebarBtn');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebar = document.querySelector('aside');
    
    function openSidebar(e) {
        if (e) e.preventDefault();
        sidebar.classList.add('open');
        sidebarOverlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar(e) {
        if (e) e.preventDefault();
        sidebar.classList.remove('open');
        sidebarOverlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', openSidebar);
    }
    if (closeSidebarBtn) {
        closeSidebarBtn.addEventListener('click', closeSidebar);
    }
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }

    // Auto-wrap tables for mobile responsiveness
    document.addEventListener("DOMContentLoaded", function() {
        const tables = document.querySelectorAll("table");
        tables.forEach(table => {
            if (!table.parentElement.classList.contains("table-responsive")) {
                const wrapper = document.createElement('div');
                wrapper.className = 'table-responsive';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            }
        });
    });

    // ===== Ripple effect on nav links =====
    document.querySelectorAll('.nav-item a').forEach(link => {
        link.addEventListener('mousemove', (e) => {
            const rect = link.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width)  * 100;
            const y = ((e.clientY - rect.top)  / rect.height) * 100;
            link.style.setProperty('--rx', x + '%');
            link.style.setProperty('--ry', y + '%');
        });
    });
    </script>
</body>
</html>
