<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RME Logistics</title>
    
    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            --success: #10b981;
            --warning: #fbbf24;
            --danger: #ef4444;
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
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 1.5rem;
        }

        /* Animated Ambient Background Glows */
        body::before, body::after {
            content: '';
            position: absolute;
            width: 40vw;
            height: 40vw;
            border-radius: 50%;
            filter: blur(120px);
            z-index: -1;
            opacity: 0.25;
            animation: pulse 10s infinite alternate;
        }

        body::before {
            top: -10%;
            left: -10%;
            background: radial-gradient(circle, #dbeafe, transparent); /* Soft sky blue glow */
        }

        body::after {
            bottom: -10%;
            right: -10%;
            background: radial-gradient(circle, #ccfbf1, transparent); /* Soft cyan glow */
            animation-delay: 5s;
        }

        @keyframes pulse {
            0% { transform: scale(1) translate(0, 0); }
            100% { transform: scale(1.2) translate(5%, 5%); }
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: 1px solid var(--border-color);
            border-radius: 28px;
            padding: 3rem 2.5rem;
            box-shadow: 0 25px 60px rgba(59, 130, 246, 0.08);
            position: relative;
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

        .brand-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.75rem;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
            margin-bottom: 1.25rem;
            position: relative;
        }

        .brand-logo::after {
            content: '';
            position: absolute;
            inset: -2px;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            border-radius: 20px;
            z-index: -1;
            opacity: 0.4;
            filter: blur(6px);
        }

        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(to right, #1e293b, #475569);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .brand-tagline {
            font-size: 0.9rem;
            color: var(--text-secondary);
            line-height: 1.4;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
            padding-left: 0.25rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1rem;
            transition: color 0.3s;
        }

        .form-control {
            width: 100%;
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(59, 130, 246, 0.15);
            border-radius: 14px;
            padding: 0.95rem 1rem 0.95rem 2.8rem;
            color: var(--text-primary);
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-primary);
            background: #ffffff;
            box-shadow: 0 0 18px rgba(59, 130, 246, 0.18);
        }

        .form-control:focus + .input-icon {
            color: var(--accent-primary);
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            color: white;
            padding: 0.95rem;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.25);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2.25rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
            filter: brightness(1.05);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Alert Styling */
        .alert {
            padding: 0.95rem 1.25rem;
            border-radius: 14px;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.15);
            color: var(--danger);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.15);
            color: var(--success);
        }

        .toggle-password {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1rem;
            cursor: pointer;
            background: none;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: var(--text-primary);
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="glass-card">
        <div class="brand-header">
            <div class="brand-logo">
                <i class="fa-solid fa-truck-ramp-box"></i>
            </div>
            <h1 class="brand-name">RME Logistics</h1>
            <p class="brand-tagline">Sistem Manajemen Logistik & Pemesanan Alat Event</p>
        </div>

        <!-- Feedback Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if ($errors->has('login_error'))
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>{{ $errors->first('login_error') }}</div>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <!-- Username -->
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-user input-icon"></i>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username Anda" value="{{ old('username') }}" required autofocus>
                </div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" name="password" id="password" class="form-control" style="padding-right: 2.5rem;" placeholder="Masukkan password Anda" required>
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">
                        <i class="fa-solid fa-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-right-to-bracket"></i> Masuk Dasbor
            </button>
        </form>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('togglePasswordIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>

</body>
</html>
