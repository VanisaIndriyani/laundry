<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard - LaundryKu' }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-blue: #1e3a8a;
            --secondary-blue: #3b82f6;
            --light-blue: #dbeafe;
            --dark-blue: #1e40af;
            --accent-blue: #60a5fa;
        }
        
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(30, 58, 138, 0.15);
            display: flex;
            flex-direction: column;
            overflow-y: hidden;
        }
        
        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        
        .sidebar-brand {
            color: white;
            font-size: 1.75rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .sidebar-brand:hover {
            color: var(--accent-blue);
            text-decoration: none;
        }
        
        .admin-info {
            background: rgba(255,255,255,0.1);
            padding: 1rem;
            margin: 1rem;
            border-radius: 10px;
            text-align: center;
            color: white;
        }
        
        .admin-avatar {
            width: 50px;
            height: 50px;
            background: var(--accent-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-size: 1.5rem;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
            flex: 1 1 auto;
            overflow-y: auto;
        }
        
        .nav-item {
            margin: 0.5rem 1rem;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.85);
            padding: 1rem 1.25rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-weight: 500;
            position: relative;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(8px);
            box-shadow: 0 4px 15px rgba(255,255,255,0.1);
        }
        
        .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
            border-left: 4px solid var(--accent-blue);
            box-shadow: 0 4px 15px rgba(96, 165, 250, 0.3);
        }
        
        .nav-link i {
            margin-right: 1rem;
            width: 24px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .nav-text {
            font-size: 0.95rem;
        }
        
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
        }
        
        .content-header {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(30, 58, 138, 0.1);
            margin-bottom: 2rem;
            border-left: 5px solid var(--secondary-blue);
        }
        
        .content-title {
            color: var(--primary-blue);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .content-subtitle {
            color: #64748b;
            margin: 0;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(30, 58, 138, 0.1);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(30, 58, 138, 0.15);
        }
        
        .btn-primary {
            background: linear-gradient(45deg, var(--secondary-blue), var(--accent-blue));
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }
        
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: var(--primary-blue);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
        }
        
        @media (max-width: 992px) {
            .mobile-toggle {
                display: block;
            }
            
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 1rem;
                padding-top: 5rem;
            }
            
            .content-header {
                padding: 1.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .sidebar {
                width: 100%;
            }
            
            .main-content {
                padding: 0.75rem;
                padding-top: 5rem;
            }
            
            .content-header {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="/admin/dashboard" class="sidebar-brand">
                <i class="bi bi-droplet-fill"></i>
                LaundryKu
            </a>
        </div>
        
        <div class="admin-info">
            <div class="admin-avatar">
                <i class="bi bi-person-fill"></i>
            </div>
            <div class="fw-bold">Admin</div>
            <small class="opacity-75">Dashboard Panel</small>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="/admin/dashboard" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="/admin/orders" class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}">
                    <i class="bi bi-basket3"></i>
                    <span class="nav-text">Kelola Pesanan</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="/admin/customers" class="nav-link {{ request()->is('admin/customers*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span class="nav-text">Data Pelanggan</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="/admin/reports" class="nav-link {{ request()->is('admin/reports*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up"></i>
                    <span class="nav-text">Laporan</span>
                </a>
            </div>
            
            <div class="nav-item">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link text-danger d-flex align-items-center w-100 text-start">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="nav-text">Logout</span>
                    </button>
                </form>
            </div>
            
            <hr class="my-3 mx-3" style="border-color: rgba(255,255,255,0.2);">
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        {{ $slot }}
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Mobile sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            
            if (window.innerWidth <= 992 && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target) && 
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth > 992) {
                sidebar.classList.remove('show');
            }
        });
        
        // Button active states for keyboard navigation
        document.addEventListener('keydown', function(e) {
            if ((e.key === ' ' || e.key === 'Enter') && e.target.classList.contains('btn')) {
                e.target.classList.add('active');
            }
        });
        
        document.addEventListener('keyup', function(e) {
            if ((e.key === ' ' || e.key === 'Enter') && e.target.classList.contains('btn')) {
                setTimeout(() => e.target.classList.remove('active'), 150);
            }
        });
        
        document.addEventListener('blur', function(e) {
            if (e.target.classList.contains('btn')) {
                e.target.classList.remove('active');
            }
        }, true);
    </script>
</body>
</html>