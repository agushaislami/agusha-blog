@extends('app')
@section('title', 'Register | Agusha Blog')

@section('content')
<header class="masthead" style="background-image: url('{{ asset('assets/img/home-bg.jpg') }}')">
    <div class="container position-relative px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <div class="page-heading">
                    <h1>Register</h1>
                    <span class="subheading">Create your Agusha Blog account</span>
                </div>
            </div>
        </div>
    </div>
</header>
<main class="mb-4">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <form method="POST" action="{{ route('register.store') }}">
                    @csrf
                    <div class="form-floating mb-3">
                        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Your name" autocomplete="name" required autofocus />
                        <label for="name">Name</label>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="name@example.com" autocomplete="email" required />
                        <label for="email">Email address</label>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" placeholder="Password" autocomplete="new-password" required />
                        <label for="password">Password</label>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm password" autocomplete="new-password" required />
                        <label for="password_confirmation">Confirm password</label>
                    </div>
                    <button class="btn btn-primary text-uppercase" type="submit">Create account</button>
                    <p class="mt-4">Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection