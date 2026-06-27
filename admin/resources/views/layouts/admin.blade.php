<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RME Dashboard') - Admin Panel</title>
    
    <!-- Google Fonts & FontAwesome Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-primary: #f0f4f9; /* Clean light sky blue/gray */
            --bg-secondary: rgba(255, 255, 255, 0.8); /* Translucent white glass */
            --border-color: rgba(99, 102, 241, 0.08); /* Soft blue-tinted border */
            --text-primary: #1e293b; /* Deep slate gray */
            --text-secondary: #64748b; /* Medium slate gray */
            --accent-primary: #3b82f6; /* Sky Blue */
            --accent-secondary: #06b6d4; /* Vivid Cyan */
            --success: #10b981; /* Emerald Green */
            --warning: #f59e0b; /* Amber */
            --danger: #ef4444; /* Rose Red */
            --glass-bg: rgba(255, 255, 255, 0.65);
            --glass-blur: blur(24px);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
            background-image: 
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(6, 182, 212, 0.08) 0px, transparent 50%);
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
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.35rem;
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
            position: relative;
        }

        .brand-logo::after {
            content: '';
            position: absolute;
            inset: -2px;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            border-radius: 16px;
            z-index: -1;
            opacity: 0.4;
            filter: blur(4px);
        }

        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(to right, #1e293b, #475569);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
            list-style: none;
            flex: 1;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 1.1rem;
            padding: 0.95rem 1.25rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 14px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
        }

        .nav-item.active a, .nav-item a:hover {
            color: var(--text-primary);
            background: rgba(59, 130, 246, 0.04);
            border-color: rgba(59, 130, 246, 0.1);
        }

        .nav-item.active a {
            background: rgba(59, 130, 246, 0.08);
            border-color: rgba(59, 130, 246, 0.2);
            color: var(--accent-primary);
        }

        .nav-item.active a i {
            color: var(--accent-primary);
            filter: drop-shadow(0 0 5px rgba(59, 130, 246, 0.3));
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
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(to right, #1e293b, #475569);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
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
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(59, 130, 246, 0.15);
            color: var(--text-primary);
        }

        .btn-outline:hover {
            background: #ffffff;
            border-color: var(--accent-primary);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
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
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Navigation -->
    <aside>
        <div class="brand">
            <div class="brand-logo">
                <i class="fa-solid fa-truck-ramp-box"></i>
            </div>
            <div class="brand-name">RME Logistics</div>
        </div>

        <ul class="nav-menu">
            <li class="nav-item {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Dasbor</span>
                </a>
            </li>
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
                    <span>Kalender Logistik</span>
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
</body>
</html>
