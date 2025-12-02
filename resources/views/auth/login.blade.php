<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - ELESS CMS</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='75' font-size='80' fill='%238b5cf6'>E</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0a0a1f;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Background Animation */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .glow {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(60px);
            animation: glow-move 15s ease-in-out infinite;
        }

        .glow:nth-child(1) { top: -200px; left: -200px; }
        .glow:nth-child(2) { bottom: -200px; right: -200px; animation-delay: 5s; }

        @keyframes glow-move {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(50px, 50px); }
        }

        /* Login Container */
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 16px;
            padding: 50px 40px;
            backdrop-filter: blur(20px);
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-logo {
            width: 80px;
            margin-bottom: 20px;
        }

        .login-header h1 {
            color: #fff;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .login-header p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
        }

        /* Form */
        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            color: rgba(255, 255, 255, 0.8);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            font-size: 16px;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 48px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #8b5cf6;
            background: rgba(139, 92, 246, 0.1);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .error-message {
            color: #ef4444;
            font-size: 12px;
            margin-top: 6px;
            display: block;
        }

        /* Checkbox */
        .form-check {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
            cursor: pointer;
        }

        .checkbox-label input {
            width: 18px;
            height: 18px;
            accent-color: #8b5cf6;
        }

        .forgot-link {
            color: #8b5cf6;
            font-size: 13px;
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #a78bfa;
        }

        /* Button */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid rgba(139, 92, 246, 0.1);
        }

        .login-footer a {
            color: rgba(255, 255, 255, 0.5);
            font-size: 13px;
            text-decoration: none;
            transition: color 0.3s;
        }

        .login-footer a:hover {
            color: #8b5cf6;
        }

        /* Alert */
        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 13px;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
    </style>
</head>
<body>
    <!-- Background -->
    <div class="bg-animation">
        <div class="glow"></div>
        <div class="glow"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="{{ asset('img/logo_blanco.png') }}" alt="ELESS" class="login-logo">
                <h1>Bienvenido</h1>
                <p>Ingresa tus credenciales para acceder al CMS</p>
            </div>

            @if($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" id="email" 
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" 
                               placeholder="tu@email.com" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" 
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="••••••••" required>
                    </div>
                </div>

                <div class="form-check">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span>Recordarme</span>
                    </label>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </button>
            </form>

            <div class="login-footer">
                <a href="{{ route('home') }}">
                    <i class="fas fa-arrow-left"></i> Volver al sitio
                </a>
            </div>
        </div>
    </div>
</body>
</html>
