@extends('dashboard.app')
@section('title', 'Dashboard | Agusha Blog')

@section('content')
<section class="dashboard-welcome">
    <div>
        <span class="dashboard-eyebrow">Ringkasan akun</span>
        <h1>Selamat datang, {{ auth()->user()->name }}</h1>
        <p>Kelola tulisanmu, ikuti respons pembaca, dan lanjutkan ide berikutnya.</p>
    </div>
    <a class="btn btn-light dashboard-write-button" href="{{ route('dashboard.posts.create') }}"><i class="fas fa-pen me-2" aria-hidden="true"></i>Tulis artikel</a>
</section>

<section class="dashboard-overview" aria-label="Ringkasan aktivitas">
    <a class="dashboard-overview-card" href="{{ route('dashboard.statuses', ['status' => 'published']) }}">
        <span class="dashboard-overview-icon is-published"><i class="fas fa-book-open" aria-hidden="true"></i></span>
        <span class="dashboard-overview-label">Artikel terbit</span>
        <strong>{{ $postCounts['published'] ?? 0 }}</strong>
        <span class="dashboard-overview-note">Lihat tulisan yang sudah terbit <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
    </a>
    <a class="dashboard-overview-card" href="{{ route('dashboard.statuses', ['status' => 'draft']) }}">
        <span class="dashboard-overview-icon is-draft"><i class="fas fa-file-pen" aria-hidden="true"></i></span>
        <span class="dashboard-overview-label">Draft</span>
        <strong>{{ $postCounts['draft'] ?? 0 }}</strong>
        <span class="dashboard-overview-note">Lanjutkan tulisan yang tersimpan <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
    </a>
    <div class="dashboard-overview-card" aria-label="Jumlah view pada artikel terbit">
        <span class="dashboard-overview-icon is-views"><i class="far fa-eye" aria-hidden="true"></i></span>
        <span class="dashboard-overview-label">Total view</span>
        <strong>{{ number_format($totalViews) }}</strong>
        <span class="dashboard-overview-note">Akumulasi pada artikel terbit</span>
    </div>
    <a class="dashboard-overview-card" href="{{ route('dashboard.comments.index') }}">
        <span class="dashboard-overview-icon is-comments"><i class="fas fa-comments" aria-hidden="true"></i></span>
        <span class="dashboard-overview-label">Komentar</span>
        <strong>{{ number_format($totalComments) }}</strong>
        <span class="dashboard-overview-note">Lihat percakapan pembaca <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
    </a>
</section>

<div class="dashboard-home-grid">
    <section class="dashboard-panel dashboard-home-panel">
        <header class="dashboard-section-heading">
            <div>
                <span class="dashboard-eyebrow">Tulisanmu</span>
                <h2>Artikel terbaru</h2>
                <p>Lima artikel terakhir yang kamu kelola.</p>
            </div>
            <a class="dashboard-text-link" href="{{ route('dashboard.statuses') }}">Semua artikel <i class="fas fa-arrow-right ms-1" aria-hidden="true"></i></a>
        </header>

        @if ($posts->isEmpty())
            <div class="dashboard-empty dashboard-home-empty">
                <span class="dashboard-empty-icon"><i class="fas fa-feather-pointed" aria-hidden="true"></i></span>
                <h3>Mulai dengan satu cerita</h3>
                <p>Artikel pertamamu bisa menjadi awal percakapan baru.</p>
                <a class="btn btn-primary btn-sm" href="{{ route('dashboard.posts.create') }}">Tulis artikel</a>
            </div>
        @else
            <div class="dashboard-recent-posts">
                @foreach ($posts as $post)
                    <article class="dashboard-recent-post">
                        <div class="dashboard-post-mark"><i class="fas {{ $post->status === 'published' ? 'fa-book-open' : 'fa-file-lines' }}" aria-hidden="true"></i></div>
                        <div class="dashboard-recent-post-copy">
                            <h3>{{ $post->title }}</h3>
                            <div class="dashboard-recent-post-meta">
                                <span class="badge text-bg-{{ $post->status === 'published' ? 'success' : 'secondary' }}">{{ ucfirst($post->status) }}</span>
                                @if ($post->is_edit)<span class="dashboard-edited-label">Edited</span>@endif
                                <span>{{ $post->updated_at->locale('id')->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="dashboard-recent-post-stats"><i class="far fa-eye me-1" aria-hidden="true"></i>{{ number_format($post->views ?? 0) }}</div>
                        <a class="dashboard-post-open" href="{{ $post->status === 'published' ? route('post', ['slug' => $post->slug]) : route('dashboard.posts.edit', $post) }}" aria-label="{{ $post->status === 'published' ? 'Buka artikel' : 'Edit draft' }} {{ $post->title }}"><i class="fas fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <aside class="dashboard-home-side">
        <section class="dashboard-panel dashboard-shortcuts">
            <span class="dashboard-eyebrow">Pintasan</span>
            <h2>Apa berikutnya?</h2>
            <a href="{{ route('dashboard.posts.create') }}"><span class="dashboard-shortcut-icon"><i class="fas fa-pen" aria-hidden="true"></i></span><span><strong>Tulis artikel</strong><small>Mulai ide baru</small></span><i class="fas fa-arrow-right ms-auto" aria-hidden="true"></i></a>
            <a href="{{ route('dashboard.statuses', ['status' => 'draft']) }}"><span class="dashboard-shortcut-icon"><i class="fas fa-file-pen" aria-hidden="true"></i></span><span><strong>Lanjutkan draft</strong><small>{{ $postCounts['draft'] ?? 0 }} tersimpan</small></span><i class="fas fa-arrow-right ms-auto" aria-hidden="true"></i></a>
            <a href="{{ route('dashboard.comments.index') }}"><span class="dashboard-shortcut-icon"><i class="fas fa-comments" aria-hidden="true"></i></span><span><strong>Baca komentar</strong><small>{{ number_format($totalComments) }} komentar</small></span><i class="fas fa-arrow-right ms-auto" aria-hidden="true"></i></a>
        </section>

        <section class="dashboard-panel dashboard-recent-comments">
            <header class="dashboard-section-heading">
                <div><span class="dashboard-eyebrow">Dari pembaca</span><h2>Komentar terbaru</h2></div>
                <a class="dashboard-icon-link" href="{{ route('dashboard.comments.index') }}" aria-label="Semua komentar"><i class="fas fa-arrow-right" aria-hidden="true"></i></a>
            </header>
            @forelse ($recentComments as $comment)
                <a class="dashboard-mini-comment" href="{{ route('post', ['slug' => $comment->post->slug]) }}#comment-{{ $comment->id }}">
                    <span class="dashboard-mini-avatar">
                        @if ($comment->user->avatar)
                            <img src="{{ asset('storage/'.$comment->user->avatar) }}" alt="" />
                        @else
                            <i class="fas fa-user" aria-hidden="true"></i>
                        @endif
                    </span>
                    <span class="dashboard-mini-comment-copy"><strong>{{ $comment->user->name }}</strong><span>{{ $comment->body }}</span><small>Pada: {{ $comment->post->title }}</small></span>
                </a>
            @empty
                <p class="dashboard-side-empty">Komentar pada artikel terbit akan muncul di sini.</p>
            @endforelse
        </section>
    </aside>
</div>

<style>
    .dashboard-welcome { display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; margin-bottom: 1.5rem; padding: clamp(1.5rem, 3vw, 2.25rem); border-radius: 1rem; background: linear-gradient(115deg, #075a6b, #0787a0 58%, #45aeb9); color: #fff; box-shadow: 0 .8rem 2rem rgba(8, 90, 107, .2); }
    .dashboard-welcome h1 { margin: .55rem 0 .4rem; font-family: 'Lora', serif; font-size: clamp(1.7rem, 3vw, 2.35rem); font-weight: 700; }
    .dashboard-welcome p { margin: 0; color: rgba(255,255,255,.82); }
    .dashboard-eyebrow { color: #07829a; font-size: .69rem; font-weight: 800; letter-spacing: .13em; text-transform: uppercase; }
    .dashboard-welcome .dashboard-eyebrow { color: #b7f2f4; }
    .dashboard-write-button { flex: 0 0 auto; border: 0; color: #075a6b; font-weight: 700; }
    .dashboard-write-button:hover { background: #e9fbfc; color: #064956; }
    .dashboard-overview { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: .85rem; margin-bottom: 1.5rem; }
    .dashboard-overview-card { display: grid; grid-template-columns: auto 1fr; align-items: center; column-gap: .8rem; padding: 1.1rem; border: 1px solid #e4eaec; border-radius: .85rem; background: #fff; color: inherit; text-decoration: none; transition: transform .18s ease, box-shadow .18s ease; }
    .dashboard-overview-card:hover { transform: translateY(-2px); box-shadow: 0 .6rem 1.5rem rgba(28,54,62,.08); }
    .dashboard-overview-icon { display: grid; width: 2.5rem; height: 2.5rem; grid-row: span 2; place-items: center; border-radius: .75rem; background: #e7f5f7; color: #08758a; }
    .dashboard-overview-icon.is-draft { background: #f3efff; color: #7357b5; }
    .dashboard-overview-icon.is-views { background: #eaf7ef; color: #368252; }
    .dashboard-overview-icon.is-comments { background: #fff2e7; color: #b36b25; }
    .dashboard-overview-label { color: #718087; font-size: .78rem; font-weight: 650; }
    .dashboard-overview-card > strong { grid-column: 2; font-family: 'Lora', serif; font-size: 1.65rem; line-height: 1.15; }
    .dashboard-overview-note { display: flex; grid-column: 1 / -1; align-items: center; justify-content: space-between; gap: .5rem; margin-top: .8rem; color: #849197; font-size: .7rem; }
    .dashboard-overview-card:hover .dashboard-overview-note { color: #08758a; }
    .dashboard-home-grid { display: grid; grid-template-columns: minmax(0, 1.55fr) minmax(280px, .85fr); align-items: start; gap: 1.25rem; }
    .dashboard-home-panel, .dashboard-shortcuts, .dashboard-recent-comments { padding: 1.35rem; }
    .dashboard-section-heading { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1rem; }
    .dashboard-section-heading h2, .dashboard-shortcuts h2 { margin: .25rem 0 0; font-family: 'Lora', serif; font-size: 1.25rem; font-weight: 700; }
    .dashboard-section-heading p { margin: .3rem 0 0; color: #849197; font-size: .82rem; }
    .dashboard-text-link, .dashboard-icon-link { color: #08758a; font-size: .8rem; font-weight: 700; text-decoration: none; white-space: nowrap; }
    .dashboard-icon-link { display: grid; width: 2rem; height: 2rem; place-items: center; border-radius: 50%; background: #eaf5f7; }
    .dashboard-recent-post { display: grid; grid-template-columns: auto minmax(0, 1fr) auto auto; align-items: center; gap: .8rem; padding: .9rem 0; }
    .dashboard-recent-post + .dashboard-recent-post { border-top: 1px solid #e7edef; }
    .dashboard-post-mark { display: grid; width: 2.3rem; height: 2.3rem; place-items: center; border-radius: .7rem; background: #f0f5f6; color: #48737b; }
    .dashboard-recent-post-copy { min-width: 0; }
    .dashboard-recent-post-copy h3 { overflow: hidden; margin: 0 0 .3rem; font-size: .91rem; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }
    .dashboard-recent-post-meta { display: flex; flex-wrap: wrap; align-items: center; gap: .4rem; color: #89969b; font-size: .7rem; }
    .dashboard-recent-post-meta .badge { padding: .3em .5em; font-size: .65rem; }
    .dashboard-edited-label { color: #a66a17; font-weight: 700; }
    .dashboard-recent-post-stats { color: #77868c; font-size: .75rem; white-space: nowrap; }
    .dashboard-post-open { color: #809096; font-size: .78rem; }
    .dashboard-home-side { display: grid; gap: 1.25rem; }
    .dashboard-shortcuts h2 { margin-bottom: .8rem; }
    .dashboard-shortcuts > a { display: flex; align-items: center; gap: .75rem; padding: .75rem 0; color: inherit; text-decoration: none; }
    .dashboard-shortcuts > a + a { border-top: 1px solid #e7edef; }
    .dashboard-shortcut-icon { display: grid; width: 2.25rem; height: 2.25rem; flex: 0 0 auto; place-items: center; border-radius: .65rem; background: #edf6f7; color: #08758a; }
    .dashboard-shortcuts > a > span:nth-child(2) { display: grid; gap: .15rem; }
    .dashboard-shortcuts strong { font-size: .82rem; }
    .dashboard-shortcuts small { color: #89969b; font-size: .72rem; }
    .dashboard-shortcuts > a > i { color: #93a1a5; font-size: .72rem; }
    .dashboard-mini-comment { display: flex; gap: .7rem; padding: .75rem 0; color: inherit; text-decoration: none; }
    .dashboard-mini-comment + .dashboard-mini-comment { border-top: 1px solid #e7edef; }
    .dashboard-mini-avatar { display: grid; width: 2rem; height: 2rem; flex: 0 0 auto; place-items: center; overflow: hidden; border-radius: 50%; background: #e8f3f5; color: #08758a; font-size: .75rem; }
    .dashboard-mini-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .dashboard-mini-comment-copy { display: grid; min-width: 0; gap: .15rem; }
    .dashboard-mini-comment-copy strong { font-size: .75rem; }
    .dashboard-mini-comment-copy > span { display: -webkit-box; overflow: hidden; color: #67777d; font-size: .76rem; line-height: 1.45; -webkit-box-orient: vertical; -webkit-line-clamp: 2; }
    .dashboard-mini-comment-copy small { overflow: hidden; color: #89969b; font-size: .68rem; text-overflow: ellipsis; white-space: nowrap; }
    .dashboard-side-empty { margin: .8rem 0 0; color: #849197; font-size: .8rem; }
    .dashboard-home-empty { padding: 2rem 1rem; }
    .dashboard-empty-icon { display: grid; width: 3rem; height: 3rem; margin: 0 auto .8rem; place-items: center; border-radius: 1rem; background: #eaf5f7; color: #08758a; font-size: 1.2rem; }
    .dashboard-home-empty h3 { font-family: 'Lora', serif; font-size: 1.15rem; }
    .dashboard-home-empty p { font-size: .85rem; }
    @media (max-width: 1100px) { .dashboard-overview { grid-template-columns: repeat(2, minmax(0, 1fr)); } .dashboard-home-grid { grid-template-columns: minmax(0, 1fr); } .dashboard-home-side { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 650px) { .dashboard-welcome { align-items: flex-start; flex-direction: column; } .dashboard-overview { gap: .6rem; } .dashboard-overview-card { padding: .85rem; } .dashboard-overview-note { font-size: .65rem; } .dashboard-home-side { grid-template-columns: minmax(0, 1fr); } .dashboard-home-panel, .dashboard-shortcuts, .dashboard-recent-comments { padding: 1rem; } .dashboard-recent-post { grid-template-columns: auto minmax(0, 1fr) auto; gap: .6rem; } .dashboard-recent-post-stats { display: none; } }
    .dashboard-welcome { margin-bottom: 1.25rem; padding: 0 0 1.25rem; border-bottom: 1px solid #e5ebed; border-radius: 0; background: transparent; color: var(--dashboard-ink); box-shadow: none; }
    .dashboard-welcome h1 { margin: .35rem 0; font-size: clamp(1.7rem, 3vw, 2.15rem); }
    .dashboard-welcome p { color: var(--dashboard-muted); font-size: .92rem; }
    .dashboard-welcome .dashboard-eyebrow { color: var(--dashboard-accent); }
    .dashboard-write-button { border: 1px solid #0085a1; background: #0085a1; color: #fff; }
    .dashboard-write-button:hover { background: #006f86; color: #fff; }
    .dashboard-overview { gap: .75rem; margin-bottom: 1.25rem; }
    .dashboard-overview-card { padding: .95rem 1rem; border-radius: .7rem; box-shadow: none; transition: border-color .15s ease, background-color .15s ease; }
    .dashboard-overview-card:hover { transform: none; border-color: #b9d8de; background: #fbfefe; box-shadow: none; }
    .dashboard-overview-icon, .dashboard-overview-icon.is-draft, .dashboard-overview-icon.is-views, .dashboard-overview-icon.is-comments { width: 2.15rem; height: 2.15rem; border-radius: .55rem; background: #f0f5f6; color: #54757b; }
    .dashboard-overview-card > strong { font-family: 'Open Sans', sans-serif; font-size: 1.5rem; font-weight: 700; }
    .dashboard-overview-note { margin-top: .55rem; font-size: .68rem; }
    .dashboard-home-grid { gap: 1rem; }
    .dashboard-home-panel, .dashboard-shortcuts, .dashboard-recent-comments { padding: 1.2rem; }
    .dashboard-home-side { gap: 1rem; }
    .dashboard-section-heading h2, .dashboard-shortcuts h2 { font-family: 'Open Sans', sans-serif; font-size: 1.1rem; }
    .dashboard-post-mark, .dashboard-shortcut-icon, .dashboard-icon-link, .dashboard-empty-icon { border-radius: .55rem; background: #f0f5f6; color: #54757b; }
    .dashboard-recent-post { padding: .75rem 0; }
    .dashboard-shortcuts > a { padding: .6rem 0; }
    .dashboard-empty-icon { margin-bottom: .7rem; }
    html[data-theme="dark"] .dashboard-welcome { border-bottom-color: #2f4147; background: transparent; color: #e0e9eb; }
    html[data-theme="dark"] .dashboard-welcome .dashboard-eyebrow { color: #84d9e6; }
    html[data-theme="dark"] .dashboard-write-button { border-color: #16879b; background: #087c91; color: #fff; }
    html[data-theme="dark"] .dashboard-write-button:hover { background: #096b7c; }
    html[data-theme="dark"] .dashboard-overview-card:hover { border-color: #42636b; background: #1c2d32; }
    html[data-theme="dark"] .dashboard-overview-icon,
    html[data-theme="dark"] .dashboard-overview-icon.is-draft,
    html[data-theme="dark"] .dashboard-overview-icon.is-views,
    html[data-theme="dark"] .dashboard-overview-icon.is-comments,
    html[data-theme="dark"] .dashboard-post-mark,
    html[data-theme="dark"] .dashboard-shortcut-icon,
    html[data-theme="dark"] .dashboard-empty-icon { background: #24383e; color: #9ab9bf; }
</style>
@endsection
