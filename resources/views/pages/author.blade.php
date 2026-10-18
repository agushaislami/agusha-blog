@extends('app')
@section('title', $user->name . ' | Profil Penulis | Agusha Blog')

@section('content')
<style>
    .author-page { min-height: 65vh; padding: 8rem 0 4rem; background: #f5f7f8; }
    .author-profile-card { display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem; padding: clamp(1.5rem, 4vw, 2.5rem); border: 1px solid #e3eaec; border-radius: 1rem; background: #fff; box-shadow: 0 .8rem 2rem rgba(27, 54, 61, .055); }
    .author-avatar { width: 128px; height: 128px; flex: 0 0 auto; border: 1px solid #dce5e7; border-radius: 50%; object-fit: cover; }
    .author-avatar-trigger { flex: 0 0 auto; padding: 0; overflow: hidden; border: 0; border-radius: 50%; background: transparent; cursor: zoom-in; }
    .author-avatar-trigger .author-avatar { display: block; }
    .author-avatar-placeholder { display: grid; place-items: center; background: #e8f3f5; color: #08758a; font-size: 2.75rem; }
    .author-kicker { color: #08758a; font-size: .75rem; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
    .author-profile-card h1 { margin: .3rem 0 .65rem; color: #20373d; font-family: 'Lora', serif; font-size: clamp(2rem, 5vw, 3rem); }
    .author-bio { max-width: 720px; margin: 0; color: #687a80; line-height: 1.75; white-space: pre-line; }
    .author-stat { margin-top: .85rem; color: #859398; font-size: .88rem; }
    .author-stats { display: flex; flex-wrap: wrap; gap: .65rem; margin: 0 0 2.5rem; }
    .author-stat-card { display: flex; align-items: center; gap: .65rem; min-width: 145px; padding: .8rem 1rem; border: 1px solid #e3eaec; border-radius: .75rem; background: #fff; color: #687a80; }
    .author-stat-card i { color: #08758a; }
    .author-stat-card strong { color: #20373d; font-size: 1rem; }
    .author-section-title { margin-bottom: 1.25rem; color: #24393f; font-family: 'Lora', serif; font-size: 1.6rem; font-weight: 700; }
    .author-post-card { display: grid; grid-template-columns: 190px minmax(0, 1fr); gap: 1.25rem; padding: 1.1rem; border: 1px solid #e5ebed; border-radius: .9rem; background: #fff; box-shadow: 0 .35rem 1rem rgba(27, 54, 61, .035); }
    .author-post-card + .author-post-card { margin-top: 1rem; }
    .author-post-image { min-height: 145px; border-radius: .65rem; background: linear-gradient(140deg, #d7e8ea, #eef4f5); background-position: center; background-size: cover; }
    .author-post-title { margin: .4rem 0; color: #20373d; font-family: 'Lora', serif; font-size: 1.3rem; }
    .author-post-title a { color: inherit; text-decoration: none; }
    .author-post-title a:hover { color: #007d96; }
    .author-post-excerpt { margin: .5rem 0; color: #687a80; line-height: 1.6; }
    .author-post-meta { color: #859398; font-size: .82rem; }
    .author-post-stats { display: flex; flex-wrap: wrap; gap: 1rem; margin-top: .65rem; color: #718087; font-size: .78rem; }
    .author-post-stats span { display: inline-flex; align-items: center; gap: .35rem; }
    .author-empty { padding: 2.5rem 1.5rem; border: 1px dashed #cad8db; border-radius: .9rem; background: rgba(255, 255, 255, .7); color: #718087; text-align: center; }
    .author-pagination .pagination { --bs-pagination-padding-x: .75rem; --bs-pagination-padding-y: .375rem; --bs-pagination-font-size: .9rem; --bs-pagination-border-radius: .375rem; margin-bottom: 0; }
    .author-avatar-lightbox { position: fixed; z-index: 3000; inset: 0; display: grid; place-items: center; padding: 2rem; background: rgba(8, 16, 19, .9); cursor: zoom-out; }
    .author-avatar-lightbox[hidden] { display: none; }
    .author-avatar-lightbox img { max-width: min(90vw, 720px); max-height: 84vh; border: 4px solid rgba(255, 255, 255, .9); border-radius: 1rem; object-fit: contain; box-shadow: 0 1rem 4rem rgba(0, 0, 0, .45); cursor: default; }
    .author-avatar-lightbox-close { position: absolute; top: 1rem; right: 1rem; display: grid; width: 3.75rem; height: 3.75rem; place-items: center; border: 1px solid rgba(255, 255, 255, .5); border-radius: 50%; background: rgba(255, 255, 255, .12); color: #fff; font-size: 2.5rem; }
    .author-avatar-lightbox-close:hover { background: rgba(255, 255, 255, .24); }
    @media (max-width: 575.98px) {
        .author-page { padding-top: 6.5rem; }
        .author-profile-card { align-items: flex-start; flex-direction: column; }
        .author-stat-card { min-width: calc(50% - .35rem); flex: 1; }
        .author-avatar { width: 96px; height: 96px; }
        .author-post-card { grid-template-columns: 110px minmax(0, 1fr); gap: .85rem; padding: .75rem; }
        .author-post-image { min-height: 115px; }
        .author-post-title { font-size: 1.05rem; }
        .author-post-excerpt { font-size: .88rem; }
    }
</style>

<main class="author-page">
    <div class="container px-4 px-lg-5">
        <section class="author-profile-card" aria-labelledby="authorName">
            @if ($user->avatar)
                <button class="author-avatar-trigger" id="authorAvatarTrigger" type="button" aria-haspopup="dialog" aria-label="Perbesar foto profil {{ $user->name }}">
                    <img class="author-avatar" src="{{ asset('storage/'.$user->avatar) }}" alt="Foto profil {{ $user->name }}" />
                </button>
            @else
                <div class="author-avatar author-avatar-placeholder" aria-hidden="true"><i class="fas fa-user"></i></div>
            @endif
            <div>
                <div class="author-kicker">Profil penulis</div>
                <h1 id="authorName">{{ $user->name }}</h1>
                @if ($user->bio)
                    <p class="author-bio">{{ $user->bio }}</p>
                @else
                    <p class="author-bio">Penulis di Agusha Blog.</p>
                @endif
                <div class="author-stat"><i class="fas fa-pen-nib me-2" aria-hidden="true"></i>Penulis Agusha Blog sejak {{ $user->created_at->locale('id')->translatedFormat('F Y') }}</div>
            </div>
        </section>

        <section class="author-stats" aria-label="Statistik penulis">
            <div class="author-stat-card"><i class="fas fa-book-open" aria-hidden="true"></i><span><strong>{{ number_format($publishedPostCount) }}</strong> artikel terbit</span></div>
            <div class="author-stat-card"><i class="far fa-eye" aria-hidden="true"></i><span><strong>{{ number_format($publishedPostViews) }}</strong> total view</span></div>
            <div class="author-stat-card"><i class="far fa-comments" aria-hidden="true"></i><span><strong>{{ number_format($publishedCommentCount) }}</strong> komentar</span></div>
        </section>

        <section aria-labelledby="authorPostsTitle">
            <div class="d-flex flex-wrap align-items-end justify-content-between gap-2 mb-3">
                <div>
                    <div class="author-kicker">Dari penulis</div>
                    <h2 class="author-section-title mb-0" id="authorPostsTitle">Tulisan {{ $user->name }}</h2>
                </div>
                @auth
                    @if (auth()->id() === $user->id)
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('dashboard.profile.edit') }}">Edit profil</a>
                    @endif
                @endauth
            </div>

            @forelse ($posts as $post)
                <article class="author-post-card">
                    <a class="author-post-image" href="{{ route('post', ['slug' => $post->slug]) }}" @if ($post->featured_image) style="background-image: url('{{ asset($post->featured_image) }}')" @endif aria-label="Baca {{ $post->title }}"></a>
                    <div>
                        @if ($post->category)
                            <a class="badge rounded-pill text-bg-primary text-decoration-none" href="{{ route('home', ['category' => $post->category->slug]) }}">{{ $post->category->name }}</a>
                        @endif
                        <h3 class="author-post-title"><a href="{{ route('post', ['slug' => $post->slug]) }}">{{ $post->title }}</a></h3>
                        @if ($post->excerpt)
                            <p class="author-post-excerpt">{{ $post->excerpt }}</p>
                        @endif
                        <div class="author-post-meta">{{ $post->published_at->locale('id')->translatedFormat('d F Y') }}</div>
                        <div class="author-post-stats">
                            <span><i class="far fa-eye" aria-hidden="true"></i>{{ number_format($post->views ?? 0) }} view</span>
                            <span><i class="far fa-comments" aria-hidden="true"></i>{{ number_format($post->comments_count) }} komentar</span>
                        </div>
                        @if ($post->tags->isNotEmpty())
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                @foreach ($post->tags as $tag)
                                    <a class="badge rounded-pill text-bg-light border text-dark text-decoration-none" href="{{ route('home', ['tag' => $tag->slug]) }}">#{{ $tag->name }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </article>
            @empty
                <div class="author-empty">Penulis ini belum memiliki artikel yang dipublikasikan.</div>
            @endforelse

            @if ($posts->hasPages())
                <nav class="author-pagination mt-4" aria-label="Navigasi artikel penulis">{{ $posts->links('pagination::bootstrap-5') }}</nav>
            @endif
        </section>
    </div>
</main>

@if ($user->avatar)
    <div class="author-avatar-lightbox" id="authorAvatarLightbox" role="dialog" aria-modal="true" aria-label="Foto profil {{ $user->name }}" tabindex="-1" hidden>
        <button class="author-avatar-lightbox-close" id="authorAvatarLightboxClose" type="button" aria-label="Tutup foto">&times;</button>
        <img src="{{ asset('storage/'.$user->avatar) }}" alt="Foto profil {{ $user->name }} diperbesar" />
    </div>
    <script>
        (() => {
            const trigger = document.getElementById('authorAvatarTrigger');
            const lightbox = document.getElementById('authorAvatarLightbox');
            const closeButton = document.getElementById('authorAvatarLightboxClose');
            let previousBodyOverflow = '';

            const closeLightbox = () => {
                if (lightbox.hidden) {
                    return;
                }

                lightbox.hidden = true;
                document.body.style.overflow = previousBodyOverflow;
                trigger.focus();
            };

            trigger.addEventListener('click', () => {
                previousBodyOverflow = document.body.style.overflow;
                lightbox.hidden = false;
                document.body.style.overflow = 'hidden';
                closeButton.focus();
            });

            closeButton.addEventListener('click', closeLightbox);
            lightbox.addEventListener('click', (event) => {
                if (event.target === lightbox) {
                    closeLightbox();
                }
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeLightbox();
                }
            });
        })();
    </script>
@endif
@endsection
