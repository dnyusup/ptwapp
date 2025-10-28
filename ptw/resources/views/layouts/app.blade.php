<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PTW Portal') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='45' fill='%23155ABB'/><path d='M25 45h50v35c0 5-5 10-10 10H35c-5 0-10-5-10-10V45z' fill='%23FFD700'/><path d='M30 40h40v10H30z' fill='%23FF6B35'/><circle cx='50' cy='35' r='8' fill='%23FFD700'/><path d='M20 50h60v5H20z' fill='%23333'/></svg>">
    
    <!-- Alternative favicons for different sizes -->
    <link rel="apple-touch-icon" sizes="180x180" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='45' fill='%23155ABB'/><path d='M25 45h50v35c0 5-5 10-10 10H35c-5 0-10-5-10-10V45z' fill='%23FFD700'/><path d='M30 40h40v10H30z' fill='%23FF6B35'/><circle cx='50' cy='35' r='8' fill='%23FFD700'/><path d='M20 50h60v5H20z' fill='%23333'/></svg>">
    <link rel="icon" type="image/png" sizes="32x32" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='45' fill='%23155ABB'/><path d='M25 45h50v35c0 5-5 10-10 10H35c-5 0-10-5-10-10V45z' fill='%23FFD700'/><path d='M30 40h40v10H30z' fill='%23FF6B35'/><circle cx='50' cy='35' r='8' fill='%23FFD700'/><path d='M20 50h60v5H20z' fill='%23333'/></svg>">
    <link rel="icon" type="image/png" sizes="16x16" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='45' fill='%23155ABB'/><path d='M25 45h50v35c0 5-5 10-10 10H35c-5 0-10-5-10-10V45z' fill='%23FFD700'/><path d='M30 40h40v10H30z' fill='%23FF6B35'/><circle cx='50' cy='35' r='8' fill='%23FFD700'/><path d='M20 50h60v5H20z' fill='%23333'/></svg>">

    <!-- Modern Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}?v={{ time() }}" rel="stylesheet">

    @yield('styles')

    <style>
        :root {
            --primary-color: #1e40af;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 64, 175, 0.3);
        }

        .btn-secondary {
            background: var(--secondary-color);
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 500;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #475569;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(100, 116, 139, 0.3);
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.25);
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }

        .dashboard-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .table {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .table thead th {
            background: var(--primary-color);
            color: white !important;
            border: none;
            padding: 15px;
            font-weight: 600;
        }

        /* Specific styling for table headers */
        .table thead th,
        .table-hover thead th,
        .card .table thead th {
            background: #1e40af !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
            border-bottom: 2px solid #1d4ed8 !important;
        }

        /* Additional specificity for Recent Permits table */
        .card .table-responsive .table thead th {
            background: #1e40af !important;
            color: #ffffff !important;
        }

        .badge {
            padding: 8px 12px;
            border-radius: 6px;
            font-weight: 500;
        }

        .sidebar {
            background: linear-gradient(180deg, #1f2937 0%, #111827 100%);
            color: white;
            min-height: 100vh;
            width: 280px;
            padding: 0;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, #1f2937 0%, #111827 100%);
            opacity: 0.95;
        }

        /* Force white color for all text in sidebar header */
        .sidebar .sidebar-header * {
            color: #ffffff !important;
        }

        .sidebar .sidebar-header h5,
        .sidebar h5,
        .sidebar-header h5 {
            color: #ffffff !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.8) !important;
            font-weight: 700 !important;
        }

        .sidebar > * {
            position: relative;
            z-index: 1;
        }

        .sidebar-header {
            padding: 30px 20px 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-header h5,
        .sidebar-header h5.text-white,
        .sidebar h5 {
            margin: 0 0 5px 0;
            font-weight: 700;
            font-size: 1.25rem;
            color: #ffffff !important;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5) !important;
        }

        .sidebar-header .brand-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
            color: white;
            box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
        }

        .sidebar-header .user-info {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 15px;
            margin-top: 15px;
            backdrop-filter: blur(15px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .sidebar-header .user-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: #ffffff !important;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.8) !important;
            letter-spacing: 0.3px;
        }

        .sidebar-header .user-role {
            font-size: 0.8rem;
            color: #60a5fa !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.8) !important;
            font-weight: 600;
        }

        /* Additional specificity for user info */
        .sidebar .sidebar-header .user-info .user-name {
            color: #ffffff !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.9) !important;
        }

        .sidebar .sidebar-header .user-info .user-role {
            color: #60a5fa !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.9) !important;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .sidebar .nav-link {
            color: #d1d5db;
            padding: 14px 24px;
            margin: 3px 16px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 16px;
        }

        .sidebar .nav-link:hover {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            transform: translateX(8px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #60a5fa;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-footer .logout-btn {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 12px;
            border-radius: 10px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .sidebar-footer .logout-btn:hover {
            background: #ef4444;
            color: white;
            border-color: #ef4444;
            transform: translateY(-2px);
        }

        .main-content {
            background: #f8fafc;
            min-height: 100vh;
            padding: 20px;
            margin-left: 280px;
            transition: margin-left 0.3s ease;
        }

        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .mobile-menu-toggle:hover {
            background: #1d4ed8;
            transform: scale(1.1);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar-overlay {
                display: block;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                width: 280px;
                z-index: 1000;
                transition: left 0.3s ease;
                height: 100vh;
                overflow-y: auto;
            }

            .sidebar.show {
                left: 0;
            }

            .sidebar.show + .sidebar-overlay {
                opacity: 1;
            }

            .sidebar-header {
                padding: 25px 20px 20px;
            }

            .sidebar-header .brand-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
                margin-bottom: 12px;
            }

            .sidebar-header h5 {
                font-size: 1.1rem;
            }

            .sidebar .nav-link {
                padding: 12px 20px;
                margin: 2px 12px;
                font-size: 0.9rem;
            }

            .sidebar .nav-link i {
                width: 18px;
                font-size: 14px;
                margin-right: 10px;
            }

            .sidebar-footer {
                padding: 15px;
            }

            .main-content {
                margin-left: 0;
                padding: 80px 15px 20px 15px;
            }

            .content-header h4 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
