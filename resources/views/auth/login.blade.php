<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMAGU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #001d3d 0%, #003566 100%);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .login-card {
            border-radius: 16px;
            overflow: hidden;
            border: none;
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.3);
        }

        .login-header {
            background: linear-gradient(135deg, #ffd60a 0%, #ffc300 100%);
            padding: 30px;
            text-align: center;
        }

        .login-header h3 {
            color: #001d3d;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .login-header p {
            color: #003566;
            font-size: 13px;
            margin-bottom: 0;
        }

        .login-icon {
            width: 70px;
            height: 70px;
            background-color: #001d3d;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }

        .login-icon i {
            font-size: 32px;
            color: #ffc300;
        }

        .login-body {
            padding: 30px;
            background-color: white;
        }

        .form-label {
            font-weight: 600;
            color: #001d3d;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #ffc300;
            box-shadow: 0 0 0 3px rgba(255, 195, 0, 0.1);
        }

        .btn-login {
            background: linear-gradient(135deg, #001d3d 0%, #003566 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #002b5c 0%, #004a7c 100%);
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0, 29, 61, 0.3);
        }

        .form-check-input:checked {
            background-color: #ffc300;
            border-color: #ffc300;
        }

        .form-check-input:focus {
            border-color: #ffc300;
            box-shadow: 0 0 0 2px rgba(255, 195, 0, 0.25);
        }

        .forgot-link {
            color: #003566;
            text-decoration: none;
            font-size: 12px;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #ffc300;
            text-decoration: underline;
        }

        .alert-success {
            background-color: #d1e7dd;
            border-color: #badbcc;
            color: #0f5132;
            border-radius: 10px;
            font-size: 13px;
        }

        .invalid-feedback {
            font-size: 11px;
            margin-top: 5px;
        }

        /* Animasi fade in */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 12px;
        }

        .login-footer a {
            color: #ffc300;
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-md-5">
                <div class="login-card">
                    <div class="login-header">
                        <div class="login-icon">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <h3>SIMAGU</h3>
                        <p>Sistem Monitoring Absensi Guru</p>
                    </div>

                    <div class="login-body">
                        <!-- Session Status -->
                        @if(session('status'))
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope-fill me-1"></i> Email Address
                                </label>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="admin@example.com"
                                       required
                                       autofocus>
                                @error('email')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock-fill me-1"></i> Password
                                </label>
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       placeholder="••••••••"
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                <label class="form-check-label" for="remember_me">
                                    <i class="bi bi-check-circle me-1"></i> Remember me
                                </label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-login text-white">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Login
                                </button>
                            </div>

                            @if (Route::has('password.request'))
                                <div class="text-center mt-3">
                                    <a href="{{ route('password.request') }}" class="forgot-link">
                                        <i class="bi bi-question-circle me-1"></i> Forgot password?
                                    </a>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="login-footer">
                    &copy; {{ date('Y') }} SIMAGU - Sistem Monitoring Absensi Guru
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
