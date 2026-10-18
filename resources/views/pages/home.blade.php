@extends('app')
@section('title', 'Home | Agusha Blog')

@section('content')
<style>
    #mainNav { border-bottom-color: #e7edef; background: #fff; }
    #mainNav .navbar-brand, #mainNav .navbar-nav > li.nav-item > a.nav-link { color: #263c42; }
    #mainNav .navbar-brand:hover, #mainNav .navbar-nav > li.nav-item > a.nav-link:hover { color: #007d96; }
    .home-page { padding-bottom: 5rem; background: #f5f7f8; }
    .home-hero { padding: clamp(7rem, 12vw, 9rem) 0 3.5rem; background-image: linear-gradient(90deg, rgba(248, 251, 251, .94) 0%, rgba(248, 251, 251, .8) 58%, rgba(248, 251, 251, .48) 100%), url('{{ asset('assets/img/home-bg.jpg') }}'); background-position: center 52%; background-size: cover; }
    .home-eyebrow { color: #08758a; font-size: .75rem; font-weight: 800; letter-spacing: .16em; text-transform: uppercase; }
    .home-hero h1 { max-width: 760px; margin: .9rem 0 1rem; color: #172c32; font-family: 'Lora', serif; font-size: clamp(2.5rem, 6vw, 4.6rem); font-weight: 700; line-height: 1.08; }
    .home-hero-copy { max-width: 620px; margin-bottom: 1.75rem; color: #62747a; font-size: 1.1rem; line-height: 1.75; }
    .home-search { display: flex; width: min(100%, 580px); gap: .6rem; padding: .45rem; border: 1px solid #dce6e8; border-radius: .85rem; background: #fff; box-shadow: 0 .65rem 2rem rgba(23, 54, 63, .08); }
    .home-search input { min-width: 0; border: 0; box-shadow: none !important; }
    .home-search button { flex: 0 0 auto; border-radius: .6rem; }
    .home-content { padding-top: 2.5rem; }
    .home-section-title { margin-bottom: 1.25rem; color: #24393f; font-family: 'Lora', serif; font-size: 1.55rem; font-weight: 700; }
    .featured-story { display: grid; grid-template-columns: 1.05fr .95fr; overflow: hidden; margin-bottom: 2.5rem; border: 1px solid #e3eaec; border-radius: 1rem; background: #fff; box-shadow: 0 .8rem 2rem rgba(27, 54, 61, .055); }
    .featured-story-image { min-height: 300px; background: linear-gradient(140deg, #bfdee2, #e6f0f1); background-position: center; background-size: cover; }
    .featured-story-copy { display: flex; flex-direction: column; justify-content: center; padding: clamp(1.5rem, 4vw, 2.6rem); }
    .featured-story h2 { margin: .8rem 0; color: #1e343a; font-family: 'Lora', serif; font-size: clamp(1.6rem, 3vw, 2.25rem); line-height: 1.25; }
    .featured-story h2 a, .article-card-title a { color: inherit; text-decoration: none; }
    .featured-story h2 a:hover, .article-card-title a:hover { color: #007d96; }
    .featured-story-excerpt { display: -webkit-box; overflow: hidden; margin-bottom: 1rem; color: #687a80; line-height: 1.7; -webkit-box-orient: vertical; -webkit-line-clamp: 3; }
    .story-meta { color: #849197; font-size: .82rem; }
    .story-label { color: #08758a; font-size: .73rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
    .story-chips { display: flex; flex-wrap: wrap; gap: .4rem; }
    .story-chip { display: inline-flex; align-items: center; padding: .35rem .65rem; border: 1px solid #e0e8ea; border-radius: 999px; background: #fff; color: #52656b; font-size: .76rem; text-decoration: none; }
    .story-chip:hover, .story-chip.is-active { border-color: #a9d5dd; background: #eaf6f8; color: #006b80; }
    .article-card { display: grid; grid-template-columns: 190px minmax(0, 1fr); gap: 1.25rem; padding: 1.1rem; border: 1px solid #e5ebed; border-radius: .9rem; background: #fff; box-shadow: 0 .45rem 1.25rem rgba(27, 54, 61, .035); }
    .article-card + .article-card { margin-top: 1rem; }
    .article-card-image { display: block; min-height: 150px; border-radius: .65rem; background: linear-gradient(140deg, #d7e8ea, #eef4f5); background-position: center; background-size: cover; }
    .article-card-content { align-self: center; min-width: 0; padding: .35rem .35rem .35rem 0; }
    .article-card-title { margin: .45rem 0; color: #1e343a; font-family: 'Lora', serif; font-size: 1.3rem; line-height: 1.3; }
    .article-card-excerpt { display: -webkit-box; overflow: hidden; margin: .55rem 0 .8rem; color: #697a80; line-height: 1.6; -webkit-box-orient: vertical; -webkit-line-clamp: 2; }
    .home-sidebar { position: sticky; top: 1.5rem; }
    .sidebar-panel { padding: 1.25rem; border: 1px solid #e4eaec; border-radius: .9rem; background: #fff; }
    .sidebar-panel + .sidebar-panel { margin-top: 1rem; }
    .sidebar-heading { margin-bottom: 1rem; color: #263c42; font-family: 'Lora', serif; font-size: 1.15rem; font-weight: 700; }
    .category-list { display: grid; gap: .25rem; }
    .category-link { display: flex; justify-content: space-between; gap: .75rem; padding: .55rem .65rem; border-radius: .5rem; color: #586a70; text-decoration: none; }
    .category-link:hover, .category-link.is-active { background: #eef7f8; color: #006b80; }
    .category-count { color: #92a0a4; font-size: .8rem; }
    .home-empty { padding: 2.5rem 1.5rem; border: 1px dashed #cad8db; border-radius: .9rem; background: rgba(255, 255, 255, .7); color: #718087; text-align: center; }
    .home-filter-note { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; margin: 0 0 1.5rem; color: #687a80; font-size: .9rem; }
    .home-pagination .pagination { --bs-pagination-padding-x: .75rem; --bs-pagination-padding-y: .375rem; --bs-pagination-font-size: .9rem; --bs-pagination-border-radius: .375rem; margin-bottom: 0; }
    .home-sort-form { display: flex; align-items: center; gap: .5rem; }
    .home-sort-form .form-select { width: 190px; border-radius: .55rem; font-size: .82rem; }
    @media (max-width: 767.98px) {
        .home-hero { padding-top: 4.25rem; }
        .featured-story { grid-template-columns: 1fr; }
        .featured-story-image { min-height: 220px; }
        .article-card { grid-template-columns: 112px minmax(0, 1fr); gap: .85rem; padding: .75rem; }
        .article-card-image { min-height: 120px; }
        .article-card-title { font-size: 1.05rem; }
        .article-card-excerpt { font-size: .88rem; }
        .home-sidebar { position: static; margin-top: 2rem; }
    }
</style>

<div class="home-page">
    <header class="home-hero">
        <div class="container px-4 px-lg-5">
            <div class="home-eyebrow">Ruang untuk cerita dan gagasan</div>
            <h1>Temukan cerita yang membuatmu ingin tahu lebih jauh.</h1>
            <p class="home-hero-copy">Baca pengalaman, ide, dan pengetahuan dari para penulis Agusha Blog. Jelajahi berdasarkan topik atau cari artikel yang kamu butuhkan.</p>
            <form class="home-search" action="{{ route('home') }}" method="GET" role="search">
                @if ($selectedCategory)<input type="hidden" name="category" value="{{ $selectedCategory }}" />@endif
                @if ($selectedTag)<input type="hidden" name="tag" value="{{ $selectedTag }}" />@endif
                @if ($sort !== 'newest')<input type="hidden" name="sort" value="{{ $sort }}" />@endif
                <label class="visually-hidden" for="homeSearch">Cari artikel</label>
                <input class="form-control" id="homeSearch" name="q" type="search" value="{{ $searchTerm }}" placeholder="Cari judul atau ringkasan artikel..." />
                <button class="btn btn-primary" type="submit"><i class="fas fa-search me-2" aria-hidden="true"></i>Cari</button>
            </form>
        </div>
    </header>

    <main class="container px-4 px-lg-5 home-content">
                @if ($selectedCategory || $selectedTag || $searchTerm)
            <div class="home-filter-note">
                <span>Menampilkan hasil untuk:</span>
                @if ($selectedCategory)<span class="story-chip">Kategori: {{ str_replace('-', ' ', $selectedCategory) }}</span>@endif
                @if ($selectedTag)<span class="story-chip">Tag: {{ str_replace('-', ' ', $selectedTag) }}</span>@endif
                @if ($searchTerm)<span class="story-chip">“{{ $searchTerm }}”</span>@endif
                <a href="{{ route('home', ['sort' => $sort]) }}">Hapus filter</a>
            </div>
        @endif

        <div class="row g-4 g-xl-5">
            <section class="col-lg-8" aria-label="Artikel terbaru">
                @if ($showFeatured && $featuredPost)
                    <article class="featured-story">
                        <a class="featured-story-image" href="{{ route('post', ['slug' => $featuredPost->slug]) }}" @if ($featuredPost->featured_image) style="background-image: url('{{ asset($featuredPost->featured_image) }}')" @endif aria-label="Baca {{ $featuredPost->title }}"></a>
                        <div class="featured-story-copy">
                            <div class="story-label">{{ ['newest' => 'Pilihan terbaru', 'oldest' => 'Artikel terlama', 'views' => 'Paling banyak dilihat'][$sort] }}</div>
                            @if ($featuredPost->category)
                                <div class="story-chips mt-2"><a class="story-chip" href="{{ route('home', array_filter(['category' => $featuredPost->category->slug, 'sort' => $sort !== 'newest' ? $sort : null])) }}">{{ $featuredPost->category->name }}</a></div>
                            @endif
                            <h2><a href="{{ route('post', ['slug' => $featuredPost->slug]) }}">{{ $featuredPost->title }}</a></h2>
                            @if ($featuredPost->excerpt)
                                <p class="featured-story-excerpt">{{ $featuredPost->excerpt }}</p>
                            @endif
                            <div class="story-meta mb-3">Oleh
                                @if ($featuredPost->user)
                                    <a href="{{ route('authors.show', $featuredPost->user) }}">{{ $featuredPost->user->name }}</a>
                                @else
                                    Agusha Blog
                                @endif
                                · {{ $featuredPost->published_at->locale('id')->translatedFormat('d F Y') }}
                            </div>
                            @if ($featuredPost->tags->isNotEmpty())
                                <div class="story-chips">
                                    @foreach ($featuredPost->tags as $tag)
                                        <a class="story-chip" href="{{ route('home', array_filter(['tag' => $tag->slug, 'sort' => $sort !== 'newest' ? $sort : null])) }}">#{{ $tag->name }}</a>
                                    @endforeach
                                </div>
                            @endif
                            <div class="mt-3">
                                @include('layout.reading-list-button', ['post' => $featuredPost, 'isSaved' => in_array($featuredPost->id, $savedPostIds, true), 'iconOnly' => true])
                            </div>
                        </div>
                    </article>
                @endif

                <div class="d-flex flex-wrap align-items-end justify-content-between gap-2 mb-3">
                    <div>
                        <div class="home-eyebrow">Dari komunitas kami</div>
                        <h2 class="home-section-title mb-0">{{ $selectedCategory || $selectedTag || $searchTerm ? 'Artikel yang cocok' : 'Artikel lainnya' }}</h2>
                    </div>
                    <form class="home-sort-form" action="{{ route('home') }}" method="GET">
                        @if ($selectedCategory)<input type="hidden" name="category" value="{{ $selectedCategory }}" />@endif
                        @if ($selectedTag)<input type="hidden" name="tag" value="{{ $selectedTag }}" />@endif
                        @if ($searchTerm)<input type="hidden" name="q" value="{{ $searchTerm }}" />@endif
                        <label class="visually-hidden" for="homeSort">Urutkan artikel</label>
                        <select class="form-select form-select-sm" id="homeSort" name="sort" onchange="this.form.submit()">
                            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="views" {{ $sort === 'views' ? 'selected' : '' }}>Paling banyak dilihat</option>
                        </select>
                    </form>
                </div>

                @forelse ($posts as $post)
                    <article class="article-card">
                        <a class="article-card-image" href="{{ route('post', ['slug' => $post->slug]) }}" @if ($post->featured_image) style="background-image: url('{{ asset($post->featured_image) }}')" @endif aria-label="Baca {{ $post->title }}"></a>
                        <div class="article-card-content">
                            <div class="story-label">{{ $post->category?->name ?? 'Artikel' }}</div>
                            <h3 class="article-card-title"><a href="{{ route('post', ['slug' => $post->slug]) }}">{{ $post->title }}</a></h3>
                            @if ($post->excerpt)
                                <p class="article-card-excerpt">{{ $post->excerpt }}</p>
                            @endif
                            <div class="story-meta">
                                @if ($post->user)
                                    <a href="{{ route('authors.show', $post->user) }}">{{ $post->user->name }}</a>
                                @else
                                    Agusha Blog
                                @endif
                                · {{ $post->published_at->locale('id')->translatedFormat('d F Y') }}
                            </div>
                            @if ($post->tags->isNotEmpty())
                                <div class="story-chips mt-2">
                                    @foreach ($post->tags->take(3) as $tag)
                                        <a class="story-chip" href="{{ route('home', ['tag' => $tag->slug]) }}">#{{ $tag->name }}</a>
                                    @endforeach
                                </div>
                            @endif
                            <div class="mt-3">
                                @include('layout.reading-list-button', ['post' => $post, 'isSaved' => in_array($post->id, $savedPostIds, true), 'iconOnly' => true])
                            </div>
                        </div>
                    </article>
                @empty
                    @unless ($featuredPost && $showFeatured)
                        <div class="home-empty">
                            <i class="far fa-folder-open fa-2x mb-3" aria-hidden="true"></i>
                            <h3 class="h5">Belum ada artikel yang cocok</h3>
                            <p class="mb-0">Coba kata kunci atau topik lain, atau lihat semua artikel.</p>
                            <a class="btn btn-outline-primary btn-sm mt-3" href="{{ route('home') }}">Lihat semua artikel</a>
                        </div>
                    @endunless
                @endforelse

                @if ($posts->hasPages())
                    <nav class="home-pagination mt-4" aria-label="Navigasi halaman artikel">{{ $posts->links('pagination::bootstrap-5') }}</nav>
                @endif
            </section>

            <aside class="col-lg-4 home-sidebar" aria-label="Jelajahi artikel">
                <section class="sidebar-panel">
                    <h2 class="sidebar-heading">Jelajahi kategori</h2>
                    @if ($categories->isNotEmpty())
                        <nav class="category-list" aria-label="Kategori artikel">
                            @foreach ($categories as $category)
                                <a class="category-link {{ $selectedCategory === $category->slug ? 'is-active' : '' }}" href="{{ route('home', array_filter(['category' => $category->slug, 'tag' => $selectedTag, 'q' => $searchTerm, 'sort' => $sort !== 'newest' ? $sort : null])) }}">
                                    <span>{{ $category->name }}</span><span class="category-count">{{ $category->published_posts_count }}</span>
                                </a>
                            @endforeach
                        </nav>
                    @else
                        <p class="text-muted small mb-0">Kategori akan muncul setelah artikel dikelompokkan.</p>
                    @endif
                </section>

                <section class="sidebar-panel">
                    <h2 class="sidebar-heading">Topik populer</h2>
                    @if ($tags->isNotEmpty())
                        <div class="story-chips">
                            @foreach ($tags as $tag)
                                <a class="story-chip {{ $selectedTag === $tag->slug ? 'is-active' : '' }}" href="{{ route('home', array_filter(['category' => $selectedCategory, 'tag' => $tag->slug, 'q' => $searchTerm, 'sort' => $sort !== 'newest' ? $sort : null])) }}">#{{ $tag->name }}</a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted small mb-0">Tag artikel akan muncul di sini.</p>
                    @endif
                </section>

                <section class="sidebar-panel">
                    <div class="story-label mb-2">Punya cerita?</div>
                    <h2 class="sidebar-heading mb-2">Bagikan tulisanmu</h2>
                    <p class="text-muted small">Buat artikel dan bagikan pengalaman atau pengetahuanmu dengan pembaca.</p>
                    @auth
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('dashboard.posts.create') }}">Tulis artikel</a>
                    @else
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('register') }}">Mulai menulis</a>
                    @endauth
                </section>
            </aside>
        </div>
    </main>
</div>
<script>
    document.addEventListener('submit', async (event) => {
        const form = event.target.closest('[data-reading-list-form]');

        if (!form) {
            return;
        }

        event.preventDefault();

        const button = form.querySelector('[data-reading-list-button]');
        const icon = form.querySelector('[data-reading-list-icon]');
        const wasSaved = form.dataset.saved === 'true';
        button.disabled = true;
        button.classList.add('is-pending');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                },
                body: new FormData(form),
                credentials: 'same-origin',
            });

            if (!response.ok) {
                throw new Error('Permintaan menyimpan artikel gagal. Coba muat ulang halaman.');
            }

            const result = await response.json();
            form.dataset.saved = String(result.isSaved);
            icon.classList.toggle('fas', result.isSaved);
            icon.classList.toggle('far', ! result.isSaved);
            button.setAttribute('aria-label', result.isSaved ? 'Hapus dari daftar bacaan' : 'Simpan ke daftar bacaan');
            button.setAttribute('title', result.isSaved ? 'Hapus dari daftar bacaan' : 'Simpan ke daftar bacaan');
            window.dispatchEvent(new CustomEvent('reading-list:updated', { detail: { message: result.message } }));
        } catch (error) {
            window.dispatchEvent(new CustomEvent('reading-list:error', { detail: { message: error.message } }));
        } finally {
            button.disabled = false;
            button.classList.remove('is-pending');
        }
    });

    window.addEventListener('reading-list:updated', (event) => {
        showReadingListNotice(event.detail.message, false);
    });

    window.addEventListener('reading-list:error', (event) => {
        showReadingListNotice(event.detail.message, true);
    });

    function showReadingListNotice(message, isError) {
        document.querySelector('.reading-list-notice')?.remove();

        const notice = document.createElement('div');
        notice.className = `reading-list-notice${isError ? ' is-error' : ''}`;
        notice.setAttribute('role', isError ? 'alert' : 'status');
        notice.textContent = message;
        document.body.append(notice);

        window.setTimeout(() => {
            notice.classList.add('is-leaving');
            window.setTimeout(() => notice.remove(), 250);
        }, 2600);
    }
</script>
<style>
    .reading-list-icon-button.is-pending { opacity: .55; cursor: wait; }
    .reading-list-notice { position: fixed; z-index: 2100; top: 1rem; right: 4.5rem; max-width: min(340px, calc(100vw - 6rem)); padding: .65rem .9rem; border: 1px solid #b7dfc5; border-radius: .7rem; background: #f0fbf4; color: #185c32; box-shadow: 0 .4rem 1.25rem rgba(26, 55, 37, .15); font-size: .85rem; animation: reading-list-notice-enter .22s ease-out both; }
    .reading-list-notice.is-error { border-color: #edc0c3; background: #fff3f3; color: #842029; }
    .reading-list-notice.is-leaving { animation: reading-list-notice-exit .2s ease-in both; }
    @keyframes reading-list-notice-enter { from { opacity: 0; transform: translateY(-.5rem); } to { opacity: 1; transform: translateY(0); } }
    @keyframes reading-list-notice-exit { to { opacity: 0; transform: translateY(-.5rem); } }
    @media (prefers-reduced-motion: reduce) { .reading-list-notice, .reading-list-notice.is-leaving { animation-duration: .01ms; } }
    html[data-theme="dark"] .reading-list-notice { border-color: #356b48; background: #1c3929; color: #c6f1d3; }
    html[data-theme="dark"] .reading-list-notice.is-error { border-color: #82464d; background: #3f272b; color: #ffc2c6; }
</style>
@endsection
