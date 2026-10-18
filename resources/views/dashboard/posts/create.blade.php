@extends('dashboard.app')
@section('title', 'Tulis Artikel | Agusha Blog')

@section('content')
<header class="dashboard-heading">
    <a class="text-decoration-none" href="{{ route('dashboard') }}">← Kembali ke dashboard</a>
    <h1 class="mt-3">Tulis artikel</h1>
    <p>Tulis artikel baru, lalu simpan sebagai draft atau terbitkan.</p>
</header>

<section class="dashboard-panel">
    <form action="{{ route('dashboard.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('dashboard.posts._fields', ['post' => null])

        <div class="d-flex flex-wrap justify-content-end gap-2 border-top pt-4">
            <button class="btn btn-outline-secondary" type="submit" name="status" value="draft">Simpan sebagai draft</button>
            <button class="btn btn-primary" type="submit" name="status" value="published">Terbitkan artikel</button>
        </div>
    </form>
</section>
@endsection
