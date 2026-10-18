@extends('dashboard.app')
@section('title', 'Daftar Bacaan | Agusha Blog')

@section('content')
<header class="dashboard-heading">
    <div class="reading-list-heading-row">
        <div>
            <span class="reading-list-eyebrow"><i class="fas fa-bookmark me-2" aria-hidden="true"></i>Koleksi pribadi</span>
            <h1>Daftar Bacaan</h1>
            <p>Artikel yang kamu simpan untuk dibaca nanti.</p>
        </div>
        <span class="reading-list-total">{{ number_format($posts->total()) }} artikel</span>
    </div>
</header>

<section class="dashboard-panel reading-list-panel">
    @forelse ($posts as $post)
        <article class="reading-list-item">
            <a class="reading-list-image {{ $post->featured_image ? '' : 'is-placeholder' }}" href="{{ route('post', ['slug' => $post->slug]) }}" @if ($post->featured_image) style="background-image: url('{{ asset($post->featured_image) }}')" @endif aria-label="Baca {{ $post->title }}">
                @unless ($post->featured_image)<i class="fas fa-book-open" aria-hidden="true"></i>@endunless
            </a>
            <div class="reading-list-copy">
                <div class="reading-list-meta">
                    <span class="reading-list-category">{{ $post->category?->name ?? 'Artikel' }}</span>
                    <span>{{ $post->published_at->locale('id')->translatedFormat('d F Y') }}</span>
                </div>
                <h2><a href="{{ route('post', ['slug' => $post->slug]) }}">{{ $post->title }}</a></h2>
                @if ($post->excerpt)
                    <p>{{ $post->excerpt }}</p>
                @endif
                @if ($post->tags->isNotEmpty())
                    <div class="reading-list-tags">
                        @foreach ($post->tags->take(3) as $tag)
                            <a href="{{ route('home', ['tag' => $tag->slug]) }}">#{{ $tag->name }}</a>
                        @endforeach
                    </div>
                @endif
                <div class="reading-list-actions">
                    <div class="reading-list-byline">
                        <span>Oleh {{ $post->user?->name ?? 'Agusha Blog' }}</span>
                        <span><i class="far fa-eye" aria-hidden="true"></i>{{ number_format($post->views ?? 0) }}</span>
                        <span><i class="far fa-comments" aria-hidden="true"></i>{{ number_format($post->comments_count) }}</span>
                    </div>
                    <div class="reading-list-action-buttons">
                        <a class="btn btn-sm btn-primary" href="{{ route('post', ['slug' => $post->slug]) }}">Baca artikel<i class="fas fa-arrow-right ms-2" aria-hidden="true"></i></a>
                        <form action="{{ route('dashboard.reading-list.destroy', $post) }}" method="POST" onsubmit="return confirm('Hapus artikel ini dari daftar bacaan?')">
                        @csrf
                        @method('DELETE')
                            <button class="btn btn-sm btn-outline-secondary" type="submit" aria-label="Hapus dari daftar bacaan" title="Hapus dari daftar bacaan"><i class="fas fa-bookmark" aria-hidden="true"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </article>
    @empty
        <div class="dashboard-empty">
            <span class="reading-list-empty-icon"><i class="far fa-bookmark" aria-hidden="true"></i></span>
            <h2 class="h5">Simpan bacaan yang ingin kamu kunjungi lagi</h2>
            <p>Tekan ikon bookmark pada artikel menarik. Koleksimu akan tersusun rapi di sini.</p>
            <a class="btn btn-primary btn-sm" href="{{ route('home') }}"><i class="fas fa-compass me-2" aria-hidden="true"></i>Jelajahi artikel</a>
        </div>
    @endforelse

    @if ($posts->hasPages())
        <nav class="reading-list-pagination mt-4" aria-label="Halaman daftar bacaan">{{ $posts->links('pagination::bootstrap-5') }}</nav>
    @endif
</section>

<style>
    .reading-list-heading-row { display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; }
    .reading-list-eyebrow { color: #08758a; font-size: .7rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
    .reading-list-heading-row h1 { margin-top: .4rem; }
    .reading-list-total { padding: .4rem .7rem; border: 1px solid #e2e9eb; border-radius: 999px; background: #fff; color: #66777d; font-size: .78rem; font-weight: 700; white-space: nowrap; }
    .reading-list-panel { width: 100%; padding-top: .5rem; }
    .reading-list-item { display: grid; grid-template-columns: minmax(0, 1fr); gap: 1.25rem; width: 100%; padding: 1.25rem 0; }
    .reading-list-item:has(.reading-list-image) { grid-template-columns: minmax(150px, 220px) minmax(0, 1fr); }
    .reading-list-item + .reading-list-item { border-top: 1px solid #e6ecee; }
    .reading-list-image { display: grid; min-height: 150px; place-items: center; border-radius: .75rem; background: linear-gradient(140deg, #d7e8ea, #eef4f5) center / cover; color: #62909a; font-size: 2rem; text-decoration: none; }
    .reading-list-image:hover { color: #08758a; }
    .reading-list-meta { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; color: #829197; font-size: .76rem; }
    .reading-list-category { color: #08758a; font-weight: 750; }
    .reading-list-copy h2 { margin: .4rem 0; font-family: 'Lora', serif; font-size: 1.3rem; line-height: 1.3; }
    .reading-list-copy h2 a { color: inherit; text-decoration: none; }
    .reading-list-copy p { margin: .5rem 0; color: var(--dashboard-muted); line-height: 1.55; }
    .reading-list-tags { display: flex; flex-wrap: wrap; gap: .4rem; margin-top: .6rem; }
    .reading-list-tags a { padding: .22rem .5rem; border-radius: 999px; background: #f0f5f6; color: #62747a; font-size: .7rem; text-decoration: none; }
    .reading-list-tags a:hover { background: #e4f3f5; color: #08758a; }
    .reading-list-actions { display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; margin-top: 1rem; }
    .reading-list-byline { display: flex; flex-wrap: wrap; gap: .75rem; color: #849197; font-size: .76rem; }
    .reading-list-byline span:not(:first-child) { display: inline-flex; align-items: center; gap: .3rem; }
    .reading-list-action-buttons { display: flex; align-items: center; gap: .5rem; }
    .reading-list-action-buttons form { margin: 0; }
    .reading-list-empty-icon { display: grid; width: 3.25rem; height: 3.25rem; margin: 0 auto 1rem; place-items: center; border-radius: 1rem; background: #eaf5f7; color: #08758a; font-size: 1.35rem; }
    .reading-list-pagination .pagination { --bs-pagination-padding-x: .75rem; --bs-pagination-padding-y: .375rem; --bs-pagination-font-size: .9rem; --bs-pagination-border-radius: .375rem; margin-bottom: 0; }
    @media (max-width: 650px) { .reading-list-heading-row { align-items: flex-start; } .reading-list-item:has(.reading-list-image) { grid-template-columns: 100px minmax(0, 1fr); gap: .85rem; } .reading-list-image { min-height: 110px; } .reading-list-copy { min-width: 0; } .reading-list-copy h2 { font-size: 1.08rem; } .reading-list-actions { align-items: flex-start; flex-direction: column; } }
</style>
@endsection
