<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') - AlJarida</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    
    <!-- Jodit Editor CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.9/jodit.min.css" rel="stylesheet">
    
    <!-- Custom Admin CSS -->
    <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
</head>
<body>
@php($resources = \App\Support\AdminResources::all())

<!-- Sidebar -->
<nav class="sidebar">
    <div class="sidebar-brand">
        <i class="fa-solid fa-newspaper"></i> الجريدة
    </div>
    
    <div class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge"></i> الرئيسية
        </a>
        
        <div class="mt-3 mb-2 px-3 text-uppercase" style="font-size:0.75rem; color:rgba(255,255,255,0.4); font-weight:700;">إدارة المحتوى</div>
        
        @foreach ($resources as $key => $r)
            @continue(($r['adminOnly'] ?? false) && ! auth()->user()->isAdmin())
            <a href="{{ route('admin.crud.index', $key) }}" class="{{ request()->route('resource') === $key ? 'active' : '' }}">
                <i class="fa-solid {{ $r['icon'] }}"></i> {{ $r['title'] }}
            </a>
        @endforeach
        
        <div class="mt-4 mb-2 px-3 text-uppercase" style="font-size:0.75rem; color:rgba(255,255,255,0.4); font-weight:700;">النظام</div>
        <a href="{{ route('home') }}" target="_blank">
            <i class="fa-solid fa-globe"></i> عرض الموقع
        </a>
    </div>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-link w-100 text-start p-2" style="color:#ffb3b3; text-decoration:none; font-weight:600;">
                <i class="fa-solid fa-right-from-bracket ms-2"></i> تسجيل الخروج
            </button>
        </form>
    </div>
</nav>

<!-- Main Content -->
<div class="main-wrapper">
    <header class="topbar">
        <h1 class="page-title">@yield('heading', 'لوحة التحكم')</h1>
        <div class="user-profile">
            <div class="text-end d-none d-md-block">
                <div class="mb-0 lh-1">{{ auth()->user()->name }}</div>
                <small class="text-muted" style="font-size:0.75rem">{{ auth()->user()->role }}</small>
            </div>
            <div class="avatar">
                {{ mb_substr(auth()->user()->name, 0, 1) }}
            </div>
        </div>
    </header>

    <main class="content-area">
        @if (session('success')) 
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div> 
        @endif
        @if (session('error')) 
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div> 
        @endif
        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.9/jodit.min.js"></script>
@stack('scripts')
</body>
</html>