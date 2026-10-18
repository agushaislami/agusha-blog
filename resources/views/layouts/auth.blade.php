<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Account | Agusha Blog')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet" />
    <style>
        body.auth-page { min-height: 100vh; display: grid; place-items: center; padding: 1.5rem; background: linear-gradient(145deg, #edf3f5, #dce8eb); }
        .auth-card { width: min(100%, 440px); padding: clamp(1.5rem, 5vw, 2.5rem); background: #fff; border-radius: 1rem; box-shadow: 0 1.25rem 3rem rgba(20, 45, 55, .14); }
        .auth-brand { display: inline-block; margin-bottom: 1.5rem; color: #0085a1; font-weight: 800; text-decoration: none; }
        .auth-card h1 { margin-bottom: .5rem; font-size: 2rem; }
        .auth-card .subheading { margin-bottom: 1.75rem; color: #6c757d; }
    </style>
</head>
<body class="auth-page">
    <main class="auth-card">
        <a class="auth-brand" href="{{ route('home') }}">Agusha Blog</a>
        @include('layout.flash-notification')
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
