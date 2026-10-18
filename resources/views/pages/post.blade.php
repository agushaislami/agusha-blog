@extends('app')
@section('title', $post->title . ' | Agusha Blog')

@section('content')
<style>
    .post-body img { display: block; max-width: 100%; height: auto; margin-right: auto; margin-left: auto; }
    .post-body img.article-image-small { width: 50%; }
    .post-body img.article-image-medium { width: 75%; }
    .post-body img.article-image-full { width: 100%; }
    .post-body img.article-image-align-left { margin-right: auto; margin-left: 0; }
    .post-body img.article-image-align-center { margin-right: auto; margin-left: auto; }
    .post-body img.article-image-align-right { margin-right: 0; margin-left: auto; }
    .post-body figure { margin: 1.5rem 0; }
    .post-body figcaption { margin-top: .5rem; color: #6c757d; font-size: .875rem; text-align: center; }
</style>
@if ($isPreview ?? false)
    <style>
        #mainNav { background-color: #fff; }
        #mainNav .navbar-brand, #mainNav .navbar-nav > li.nav-item > a.nav-link { color: #212529; }
        #mainNav .navbar-brand:hover, #mainNav .navbar-nav > li.nav-item > a.nav-link:hover { color: #0085a1; }
        .preview-notice { position: relative; z-index: 1; margin-top: 5rem; }
        @media (min-width: 992px) {
            #mainNav { background-color: rgba(255, 255, 255, .96); }
            #mainNav .navbar-brand, #mainNav .navbar-nav > li.nav-item > a.nav-link { color: #212529; }
            .preview-notice { margin-top: 5.5rem; }
        }
    </style>
    <div class="preview-notice alert alert-info rounded-0 mb-0 text-center" role="status">
        Pratinjau {{ ucfirst($post->status) }} · Hanya kamu yang dapat melihat halaman ini.
        <a href="{{ route('dashboard.statuses', ['status' => $post->status === 'published' ? 'published' : 'draft']) }}">Kembali ke dashboard</a>
    </div>
@endif
<!-- Page Header-->
<header class="masthead" style="background-image: url('{{ $post->featured_image ? asset($post->featured_image) : asset('assets/img/post-bg.jpg') }}')">
    <div class="container position-relative px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <div class="post-heading">
                    <h1>{{ $post->title }}</h1>
                    @if ($post->excerpt)
                        <h2 class="subheading">{{ $post->excerpt }}</h2>
                    @endif
                    <span class="meta">
                        Posted by
                        <a href="#">{{ $post->user?->name ?? 'Agusha Blog' }}</a>
                        @if ($post->published_at)
                            on {{ $post->published_at->format('F j, Y') }}
                        @else
                            · Belum diterbitkan
                        @endif
                    </span>
                    @if (($previewCategoryName ?? null) || ($previewTagNames ?? collect())->isNotEmpty())
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            @if ($previewCategoryName ?? null)
                                <a class="badge rounded-pill text-bg-primary text-decoration-none" href="{{ route('home', ['category' => \Illuminate\Support\Str::slug($previewCategoryName)]) }}">{{ $previewCategoryName }}</a>
                            @endif
                            @foreach (($previewTagNames ?? collect()) as $tagName)
                                <a class="badge rounded-pill text-bg-light text-dark text-decoration-none" href="{{ route('home', ['tag' => \Illuminate\Support\Str::slug($tagName)]) }}">#{{ $tagName }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Post Content-->
<article class="mb-4">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                @if ($post->featured_image)
                    <figure class="mb-4">
                        <img class="img-fluid" src="{{ asset($post->featured_image) }}" alt="{{ $post->image_caption ?? $post->title }}" />
                        @if ($post->image_caption)
                            <figcaption class="caption text-muted">{{ $post->image_caption }}</figcaption>
                        @endif
                    </figure>
                @endif
                <div class="post-body">{!! $bodyHtml !!}</div>
                @if ($post->is_edit)
                    <div class="mt-3 text-end"><small class="text-muted">Edited</small></div>
                @endif
            </div>
        </div>
    </div>
</article>
@endsection
