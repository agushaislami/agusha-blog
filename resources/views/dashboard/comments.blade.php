@extends('dashboard.app')
@section('title', 'Komentar Artikel | Agusha Blog')

@section('content')
<header class="dashboard-heading">
    <h1>Komentar</h1>
    <p>Komentar pembaca pada semua artikel yang kamu tulis.</p>
</header>

<section class="dashboard-panel">
    @forelse ($comments as $comment)
        <article class="dashboard-comment">
            <div class="dashboard-comment-main">
                <a class="dashboard-comment-post" href="{{ route('post', ['slug' => $comment->post->slug]) }}#comment-{{ $comment->id }}">
                    <span class="dashboard-comment-post-label">Komentar pada artikel</span>
                    <span class="dashboard-comment-post-title">{{ $comment->post->title }}</span>
                    <i class="fas fa-arrow-up-right-from-square" style="font-size: 12px" aria-hidden="true"></i>
                </a>
                <div class="dashboard-comment-author">
                    <a class="dashboard-comment-avatar-link" href="{{ route('authors.show', $comment->user) }}" aria-label="Lihat profil {{ $comment->user->name }}">
                        <span class="dashboard-comment-avatar" aria-hidden="true">
                        @if ($comment->user->avatar)
                            <img src="{{ asset('storage/'.$comment->user->avatar) }}" alt="" />
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                        </span>
                    </a>
                    <div>
                        <strong>{{ $comment->user->name }}</strong>
                        <time class="d-block small text-muted" datetime="{{ $comment->created_at->toIso8601String() }}">{{ $comment->created_at->locale('id')->translatedFormat('d F Y, H:i') }}</time>
                    </div>
                </div>
                <p class="dashboard-comment-body">{{ $comment->body }}</p>
            </div>
            <form action="{{ route('dashboard.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" type="submit" aria-label="Hapus komentar"><i class="fas fa-trash me-1" aria-hidden="true"></i>Hapus</button>
            </form>
        </article>
    @empty
        <div class="dashboard-empty">
            <i class="far fa-comments fa-2x mb-3" aria-hidden="true"></i>
            <h2 class="h5">Belum ada komentar</h2>
            <p class="mb-0">Komentar pada artikelmu akan muncul di sini.</p>
        </div>
    @endforelse

    @if ($comments->hasPages())
        <nav class="mt-4" aria-label="Halaman komentar">{{ $comments->links() }}</nav>
    @endif
</section>

<style>
    .dashboard-comment { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.25rem; padding: 1.25rem 0; }
    .dashboard-comment + .dashboard-comment { border-top: 1px solid #e6ecee; }
    .dashboard-comment-main { min-width: 0; }
    .dashboard-comment-post { display: flex; flex-wrap: wrap; align-items: baseline; gap: .35rem .6rem; margin-bottom: 1rem; color: #21343a; text-decoration: none; }
    .dashboard-comment-post:hover .dashboard-comment-post-title { color: #007d96; text-decoration: underline; }
    .dashboard-comment-post-label { flex-basis: 100%; color: #718087; font-size: .72rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
    .dashboard-comment-post-title { font-family: 'Lora', serif; font-size: 1.08rem; font-weight: 700; }
    .dashboard-comment-post > i { color: #829197; font-size: .75rem; }
    .dashboard-comment-author { display: flex; align-items: center; gap: .75rem; }
    .dashboard-comment-avatar-link { display: block; flex: 0 0 auto; border-radius: 50%; text-decoration: none; }
    .dashboard-comment-avatar-link:focus-visible { outline: 3px solid #56b6c5; outline-offset: 3px; }
    .dashboard-comment-avatar-link:hover .dashboard-comment-avatar { border-color: #08758a; box-shadow: 0 0 0 3px rgba(8, 117, 138, .14); }
    .dashboard-comment-avatar { display: grid; width: 2.6rem; height: 2.6rem; flex: 0 0 auto; place-items: center; overflow: hidden; border: 1px solid #dce5e7; border-radius: 50%; background: #e8f3f5; color: #08758a; }
    .dashboard-comment-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .dashboard-comment-body { margin: .9rem 0 .6rem; white-space: pre-line; overflow-wrap: anywhere; }
    @media (max-width: 575.98px) { .dashboard-comment { align-items: flex-end; flex-direction: column; } }
</style>
@endsection
