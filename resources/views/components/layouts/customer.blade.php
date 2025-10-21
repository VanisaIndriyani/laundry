<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'LaundryKu - Layanan Laundry Terpercaya' }}</title>
    
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
            background: linear-gradient(135deg, var(--light-blue) 0%, #ffffff 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar-custom {
            background: linear-gradient(90deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
            box-shadow: 0 2px 10px rgba(30, 58, 138, 0.3);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: white !important;
            transform: translateY(-1px);
        }
        
        .btn-primary-custom {
            background: linear-gradient(45deg, var(--secondary-blue), var(--accent-blue));
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }
        
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(30, 58, 138, 0.1);
            transition: all 0.3s ease;
        }
        
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(30, 58, 138, 0.15);
        }
        
        .status-badge {
            border-radius: 20px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-processing {
            background-color: var(--light-blue);
            color: var(--primary-blue);
        }
        
        .status-ready {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-completed {
            background-color: #e5e7eb;
            color: #374151;
        }
        
        .footer-custom {
            background: var(--primary-blue);
            color: white;
            margin-top: auto;
        }
        
        .main-content {
            min-height: calc(100vh - 200px);
            padding: 2rem 0;
        }
        
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.25rem;
            }
            
            .main-content {
                padding: 1rem 0;
            }
        }
    </style>
</head>
<body class="d-flex flex-column">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-droplet-fill me-2"></i>LaundryKu
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
         
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content flex-grow-1">
        <div class="container">
            {{ $slot }}
        </div>
    </main>

   

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS for interactions -->
    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (alert.classList.contains('alert-dismissible')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);
    </script>
</body>
</html>