@extends('app')
@section('title', 'Login | Agusha Blog')

@section('content')
<header class="masthead" style="background-image: url('{{ asset('assets/img/home-bg.jpg') }}')">
    <div class="container position-relative px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <div class="page-heading">
                    <h1>Login</h1>
                    <span class="subheading">Welcome back to Agusha Blog</span>
                </div>
            </div>
        </div>
    </div>
</header>
<main class="mb-4">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <form method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <div class="form-floating mb-3">
                        <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" placeholder=" " autocomplete="email" required autofocus />
                        <label for="email">Email address</label>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" placeholder=" " autocomplete="current-password" required />
                        <label for="password">Password</label>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" id="remember" name="remember" type="checkbox" value="1" />
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <button class="btn btn-primary text-uppercase" type="submit">Login</button>
                    <p class="mt-4">Belum punya akun? <a href="{{ route('register') }}">Register</a></p>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
