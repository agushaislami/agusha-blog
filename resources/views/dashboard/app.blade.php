<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Dashboard | Agusha Blog')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet" />
    <style>
        :root { --dashboard-sidebar-width: 260px; --dashboard-ink: #21343a; --dashboard-muted: #718087; --dashboard-accent: #0085a1; }
        body.dashboard-page { min-height: 100vh; margin: 0; color: var(--dashboard-ink); background: #f3f6f7; font-family: 'Open Sans', sans-serif; }
        .dashboard-sidebar { position: fixed; inset: 0 auto 0 0; z-index: 10; display: flex; width: var(--dashboard-sidebar-width); flex-direction: column; padding: 2rem 1.25rem 1.25rem; background: #fff; border-right: 1px solid #e5ebed; }
        .dashboard-brand { margin: 0 0 2.5rem .75rem; color: var(--dashboard-accent); font-family: 'Lora', serif; font-size: 1.35rem; font-weight: 700; text-decoration: none; }
        .dashboard-nav-label { margin: 0 0 .75rem .75rem; color: #98a5a9; font-size: .7rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
        .dashboard-sidebar .nav-link { display: flex; align-items: center; gap: .8rem; margin: .15rem 0; padding: .8rem .9rem; border-radius: .65rem; color: #53636a; font-size: .92rem; font-weight: 600; text-decoration: none; }
        .dashboard-sidebar .nav-link:hover, .dashboard-sidebar .nav-link.active { color: var(--dashboard-accent); background: #eaf5f7; }
        .dashboard-sidebar .nav-link i { width: 1.15rem; text-align: center; }
        .dashboard-sidebar-bottom { margin-top: auto; }
        .dashboard-account .dropdown-toggle { width: 100%; padding: .85rem .65rem; border: 0; border-radius: .75rem; background: #f5f8f8; color: var(--dashboard-ink); text-align: left; }
        .dashboard-account .dropdown-menu { width: 100%; }
        .dashboard-account-name { display: block; overflow: hidden; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }
        .dashboard-account-caption { display: block; color: var(--dashboard-muted); font-size: .76rem; }
        .dashboard-main { min-height: 100vh; margin-left: var(--dashboard-sidebar-width); padding: clamp(1.5rem, 4vw, 3.5rem); }
        .dashboard-content { width: min(100%, 1120px); margin: 0 auto; }
        .dashboard-heading { margin-bottom: 2rem; }
        .dashboard-heading h1 { margin-bottom: .4rem; font-family: 'Lora', serif; font-size: clamp(1.8rem, 4vw, 2.5rem); }
        .dashboard-heading p { margin: 0; color: var(--dashboard-muted); }
        .dashboard-panel { padding: 1.5rem; border: 1px solid #e6ecee; border-radius: .9rem; background: #fff; box-shadow: 0 .4rem 1.5rem rgba(28, 54, 62, .035); }
        .dashboard-stat { color: var(--dashboard-muted); font-size: .85rem; font-weight: 600; }
        .dashboard-stat strong { display: block; margin-top: .55rem; color: var(--dashboard-ink); font-family: 'Lora', serif; font-size: 2rem; }
        .dashboard-table th { color: var(--dashboard-muted); font-size: .75rem; letter-spacing: .06em; text-transform: uppercase; }
        .dashboard-table td, .dashboard-table th { padding: .9rem .75rem; vertical-align: middle; }
        .dashboard-empty { padding: 2rem 1rem; color: var(--dashboard-muted); text-align: center; }
        @media (max-width: 767.98px) {
            .dashboard-sidebar { position: static; width: 100%; min-height: auto; padding: 1rem; border-right: 0; border-bottom: 1px solid #e5ebed; }
            .dashboard-brand { margin: 0 0 .75rem .5rem; }
            .dashboard-nav-label { display: none; }
            .dashboard-sidebar nav { display: flex; flex-wrap: wrap; }
            .dashboard-sidebar .nav-link { margin-right: .25rem; padding: .65rem .75rem; }
            .dashboard-sidebar-bottom { margin-top: .5rem; }
            .dashboard-account { max-width: 280px; }
            .dashboard-main { margin-left: 0; padding: 1.25rem; }
        }
    </style>
</head>
<body class="dashboard-page">
    <aside class="dashboard-sidebar">
        <a class="dashboard-brand" href="{{ route('home') }}">Agusha Blog</a>
        <p class="dashboard-nav-label">Workspace</p>
        <nav aria-label="Dashboard navigation">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="fas fa-chart-line" aria-hidden="true"></i>Dashboard</a>
            <a class="nav-link {{ request()->routeIs('dashboard.statuses') ? 'active' : '' }}" href="{{ route('dashboard.statuses') }}"><i class="fas fa-layer-group" aria-hidden="true"></i>Status</a>
            <a class="nav-link {{ request()->routeIs('dashboard.posts.*') ? 'active' : '' }}" href="{{ route('dashboard.posts.create') }}"><i class="fas fa-pen" aria-hidden="true"></i>Tulis Artikel</a>
        </nav>
        <div class="dashboard-sidebar-bottom">
            <div class="dropdown dashboard-account">
                <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="dashboard-account-name">{{ auth()->user()->name }}</span>
                    <span class="dashboard-account-caption">Account</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('home') }}">Lihat blog</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item" type="submit">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </aside>
    <main class="dashboard-main">
        <div class="dashboard-content">
            @include('layout.flash-notification')
            @yield('content')
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
