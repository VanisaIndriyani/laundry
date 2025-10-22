<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Pelanggan - LaundryKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
:root {
            --primary-blue: #1e3a8a;
            --secondary-blue: #3b82f6;
            --light-blue: #dbeafe;
            --dark-blue: #1e40af;
            --accent-blue:rgb(15, 66, 128);
        }
        body {
            min-height: 100vh;
            background: radial-gradient(1200px 600px at 10% 10%, #eef3ff 0%, #ffffff 40%),
                        linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        .brand-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            color: var(--primary-blue);
            font-weight: 800;
            letter-spacing: .3px;
        }
        .brand-header .logo {
            width: 42px; height: 42px;
            display: grid; place-items: center;
            border-radius: 12px;
            background: linear-gradient(160deg, var(--secondary-blue), var(--accent-blue));
            color: #fff;
            box-shadow: 0 8px 24px rgba(59,130,246,.35);
        }
        .login-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 18px 50px rgba(30,58,138,.12);
        }
        .btn-primary-custom {
            background: linear-gradient(45deg, var(--secondary-blue), var(--accent-blue));
            border: none;
            border-radius: 10px;
            padding: .75rem 1.25rem;
            font-weight: 600;
        }
        .btn-primary-custom:hover { box-shadow: 0 6px 18px rgba(59,130,246,.45); transform: translateY(-1px); }
        .form-label { color: #334155; font-weight: 600; }
        .form-control.form-control-lg { padding-top: .9rem; padding-bottom: .9rem; }
        .helper-link { color: var(--secondary-blue); text-decoration: none; font-weight: 600; }
        .helper-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container py-5">
        <!-- Logo seperti nav -->
        <div class="brand-header mb-4">
            <div class="logo"><i class="bi bi-droplet-fill"></i></div>
            <div>
                <div style="font-size:1.35rem; line-height:1;">LaundryKu</div>
                <small class="text-muted">Masuk Pelanggan</small>
            </div>
        </div>

        <!-- Card Login -->
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="card login-card">
                    <div class="card-body p-4 p-lg-5">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="customer_email" class="form-label"><i class="bi bi-envelope me-2"></i>Email</label>
                                <input type="email" class="form-control form-control-lg" id="customer_email" name="email" placeholder="nama@email.com" required>
                            </div>
                            <div class="mb-4">
                                <label for="customer_password" class="form-label"><i class="bi bi-lock me-2"></i>Password</label>
                                <input type="password" class="form-control form-control-lg" id="customer_password" name="password" placeholder="Masukkan password" required>
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary-custom btn-lg">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                                </button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ url('register') }}" class="helper-link">Daftar</a>
                                <a href="{{ url('admin/login') }}" class="text-secondary text-decoration-none"><i class="bi bi-gear me-1"></i>Akses Admin</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="text-center text-muted mt-3">
                    <small>&copy; {{ date('Y') }} LaundryKu</small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>