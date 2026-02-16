<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - مصنع ألبان الحاج محمود</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif !important; }
        body {
            background: linear-gradient(135deg, #2565AE 0%, #3a7fd4 50%, #2565AE 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .logo-circle {
            width: 90px; height: 90px;
            background: linear-gradient(135deg, #2565AE, #3a7fd4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2.5rem;
            color: white;
            box-shadow: 0 8px 25px rgba(26, 107, 60, 0.4);
        }
        .app-title { font-size: 1.6rem; font-weight: 800; color: #1a1a1a; margin-bottom: 5px; }
        .app-subtitle { color: #777; font-size: 0.9rem; margin-bottom: 30px; }
        .form-label { font-weight: 700; color: #333; }
        .form-control {
            border: 2px solid #e8ecef;
            border-radius: 12px;
            padding: 12px 15px;
            font-size: 1rem;
        }
        .form-control:focus {
            border-color: #2565AE;
            box-shadow: 0 0 0 3px rgba(26, 107, 60, 0.1);
        }
        .form-control.is-invalid { border-color: #dc3545; }
        .input-icon-wrap { position: relative; }
        .input-icon {
            position: absolute;
            left: 15px; top: 50%;
            transform: translateY(-50%);
            color: #aaa; font-size: 1rem;
        }
        .input-icon-wrap .form-control { padding-left: 42px; }
        .btn-login {
            background: linear-gradient(135deg, #2565AE, #3a7fd4);
            border: none;
            color: white;
            font-weight: 800;
            font-size: 1.1rem;
            padding: 14px;
            border-radius: 12px;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 107, 60, 0.4);
            color: white;
        }
        .invalid-feedback { font-size: 0.85rem; font-weight: 600; }
        .form-check-label { font-size: 0.9rem; color: #555; font-weight: 600; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center">
            <div class="logo-circle">
                <i class="bi bi-building-fill-gear"></i>
            </div>
            <h1 class="app-title">نظام مصنع الألبان</h1>
            <p class="app-subtitle">سجّل دخولك للمتابعة</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger border-0" style="border-radius: 10px; font-weight: 600;">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-4">
                <label class="form-label">
                    <i class="bi bi-envelope-fill me-1" style="color: #2565AE;"></i>
                    البريد الإلكتروني
                </label>
                <div class="input-icon-wrap">
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="أدخل بريدك الإلكتروني"
                           autofocus>
                    <i class="bi bi-at input-icon"></i>
                </div>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">
                    <i class="bi bi-lock-fill me-1" style="color: #2565AE;"></i>
                    كلمة المرور
                </label>
                <div class="input-icon-wrap">
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="أدخل كلمة المرور">
                    <i class="bi bi-key-fill input-icon"></i>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">تذكرني</label>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                دخول
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
