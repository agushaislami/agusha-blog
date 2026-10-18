@extends('dashboard.app')
@section('title', 'Edit Artikel | Agusha Blog')

@section('content')
<header class="dashboard-heading">
    <a class="text-decoration-none" href="{{ route('dashboard.statuses', ['status' => $post->status]) }}">← Kembali ke status artikel</a>
    <h1 class="mt-3">Edit artikel</h1>
    <p>Setelah disimpan, status draft atau published tetap dipertahankan dan artikel diberi tanda Edited.</p>
</header>

<section class="dashboard-panel">
    <form action="{{ route('dashboard.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('dashboard.posts._fields', ['post' => $post])
        <div class="d-flex flex-wrap justify-content-end gap-2 border-top pt-4">
            <button class="btn btn-primary" type="submit">Simpan perubahan</button>
        </div>
    </form>
</section>
@endsection
