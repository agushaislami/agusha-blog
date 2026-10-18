<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light" id="mainNav">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="{{ route('home') }}">Agusha Blog</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto py-4 py-lg-0">
                <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="{{ route('contact') }}">Contact</a></li>
            </ul>
            <ul class="navbar-nav ms-lg-3 py-4 py-lg-0">
                @guest
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-lg-3 py-3 py-lg-4" href="#" id="accountMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">Account</a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountMenu">
                            <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                            <li><a class="dropdown-item" href="{{ route('register') }}">Register</a></li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-lg-3 py-3 py-lg-4" href="#" id="accountMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ auth()->user()->name }}</a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountMenu">
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
