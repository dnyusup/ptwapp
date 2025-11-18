<style>
        .mobile-menu-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1050;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 12px 14px;
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.4);
            display: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .mobile-menu-toggle:hover {
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: white;
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(30, 60, 114, 0.6);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .mobile-menu-toggle:active {
            transform: scale(0.95);
        }

        .mobile-menu-toggle i {
            font-size: 18px;
            color: white;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            z-index: 1040;
            overflow-y: auto;
            transform: translateX(0);
            transition: transform 0.3s ease;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(30, 60, 114, 0.9) 0%, rgba(42, 82, 152, 0.9) 100%);
            backdrop-filter: blur(10px);
            z-index: -1;
        }

        /* Force white color for all text in sidebar header */
        .sidebar .sidebar-header * {
            color: white !important;
        }

        .sidebar .sidebar-header h5,
        .sidebar h5,
        .sidebar-header h5 {
            color: white !important;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .sidebar > * {
            position: relative;
            z-index: 1;
        }

        .sidebar-header {
            padding: 30px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .sidebar-header h5,
        .sidebar-header h5.text-white,
        .sidebar h5 {
            color: white !important;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            font-size: 1.5rem;
            margin-bottom: 0;
        }

        .sidebar-header .brand-icon {
            background: rgba(255, 255, 255, 0.2);
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .sidebar-header .brand-icon i {
            font-size: 24px;
            color: white;
        }

        .sidebar-header .user-info {
            margin-top: 20px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-header .user-name {
            font-weight: 600;
            font-size: 1.1rem;
            color: white !important;
            margin-bottom: 5px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .sidebar-header .user-role {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8) !important;
            text-transform: capitalize;
        }

        .sidebar .sidebar-header .user-info .user-name {
            color: white !important;
        }

        .sidebar .sidebar-header .user-info .user-role {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .sidebar-nav {
            padding: 0;
            list-style: none;
            flex: 1;
            overflow-y: auto;
            min-height: 0; /* Important for flex scrolling */
        }

        /* Custom scrollbar for sidebar */
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 15px 25px;
            border: none;
            background: none;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 16px;
            text-align: center;
        }

        .sidebar .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.15);
            border-left-color: #60a5fa;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            color: white !important;
            background: rgba(255, 255, 255, 0.2);
            border-left-color: #3b82f6;
        }

        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #3b82f6;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
            margin-top: auto;
        }

        .logout-btn {
            width: 100%;
            background: rgba(239, 68, 68, 0.2);
            color: white !important;
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.3);
            border-color: rgba(239, 68, 68, 0.5);
            color: white !important;
            transform: translateY(-2px);
        }

        .main-content {
            margin-left: 280px;
            padding: 30px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .content-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .content-header h4 {
            color: #1f2937;
            font-weight: 700;
            margin: 0;
        }

        .content-header p {
            color: #6b7280;
            margin: 0;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(2px);
            z-index: 1030;
            transition: all 0.3s ease;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
                box-shadow: 8px 0 25px rgba(0, 0, 0, 0.3);
                height: 100vh;
                max-height: 100vh;
            }

            .sidebar-header {
                padding: 20px 15px;
            }

            .sidebar-nav {
                padding-bottom: 20px;
            }

            .sidebar .nav-link {
                padding: 12px 20px;
                font-size: 0.95rem;
            }

            .sidebar-footer {
                padding: 15px 20px;
                background: rgba(0, 0, 0, 0.1);
            }

            .logout-btn {
                padding: 10px 15px;
                font-size: 0.9rem;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                display: block;
                animation: fadeIn 0.3s ease;
            }

            .main-content {
                margin-left: 0;
                padding: 80px 15px 20px 15px;
            }

            .content-header {
                margin-top: 60px;
                padding: 20px 25px;
            }

            .content-header h4 {
                font-size: 1.5rem;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Dashboard Card Hover Effects */
        .dashboard-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.125);
            text-decoration: none;
            display: block;
            color: inherit;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            text-decoration: none;
            color: inherit;
        }

        .dashboard-card .card-body {
            transition: all 0.3s ease;
        }

        .dashboard-card:hover .card-body {
            background: linear-gradient(135deg, rgba(0, 123, 255, 0.05) 0%, rgba(108, 117, 125, 0.05) 100%);
        }

        .dashboard-card .display-4 {
            transition: all 0.3s ease;
        }

        .dashboard-card:hover .display-4 {
            transform: scale(1.1);
        }

        .dashboard-card .text-muted {
            transition: all 0.3s ease;
        }

        .dashboard-card:hover .text-muted {
            color: #495057 !important;
            font-weight: 600;
        }
</style>
