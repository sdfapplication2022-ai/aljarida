<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الدخول - AlJarida</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    
    <!-- Lien vers votre fichier CSS -->
    <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
</head>
<body class="login-page-body">
    <div class="login-wrapper">
        
        <!-- Côté Branding (Gauche) -->
        <div class="login-left">
            <h1><i class="fa-solid fa-newspaper"></i> الجريدة</h1>
            <p class="mt-3">لوحة التحكم وإدارة المحتوى</p>
        </div>
        
        <!-- Côté Formulaire (Droite) -->
        <div class="login-right">
            <div class="login-card">
                <div class="text-center mb-5">
                    <h3 class="mb-2">مرحباً بعودتك</h3>
                    <p class="text-muted">الرجاء إدخال بياناتك للدخول</p>
                </div>
                
                @if ($errors->any()) 
                    <div class="alert alert-danger border-0 small rounded-3">{{ $errors->first() }}</div> 
                @endif
                
                <form method="POST" action="{{ route('admin.login.post') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus placeholder="admin@aljarida.tn">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">كلمة المرور</label>
                        <input type="password" name="password" class="form-control" required placeholder="••••••••">
                    </div>
                    
                    <div class="form-check mb-4">
                        <input type="checkbox" name="remember" value="1" class="form-check-input" id="r">
                        <label for="r" class="form-check-label text-muted">تذكرني في المرة القادمة</label>
                    </div>
                    
                    <button class="btn-brand">تسجيل الدخول</button>
                </form>
            </div>
        </div>
        
    </div>
</body>
</html>