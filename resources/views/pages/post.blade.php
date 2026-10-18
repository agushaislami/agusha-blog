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
    .post-body code { padding: .12em .35em; border: 1px solid #e1e7e9; border-radius: .3rem; background: #f1f4f5; color: #a23b52; font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', monospace; font-size: .9em; }
    .comments-section { padding: 2.5rem 0 4rem; border-top: 1px solid #e5ebed; }
    .comments-heading { margin-bottom: 1.25rem; font-family: 'Lora', serif; font-weight: 700; }
    .comment-composer, .comment-item { padding: 1.25rem; border: 1px solid #e3eaec; border-radius: .85rem; background: #fff; }
    .comment-item + .comment-item { margin-top: .9rem; }
    .comment-author { display: flex; align-items: center; gap: .75rem; min-width: 0; }
    .comment-avatar { display: grid; width: 2.75rem; height: 2.75rem; flex: 0 0 auto; place-items: center; overflow: hidden; border: 1px solid #dce5e7; border-radius: 50%; background: #e8f3f5; color: #08758a; }
    .comment-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .comment-body { margin: 1rem 0 0; white-space: pre-line; overflow-wrap: anywhere; }
    .comment-edit-form textarea { min-height: 6rem; }
    .comment-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
    .comment-login-note { padding: 1rem 1.15rem; border: 1px solid #e3eaec; border-radius: .75rem; background: #f7f9fa; }
</style>
@if ($isPreview ?? false)
    <style>
        #mainNav, #mainNav.is-fixed { position: relative; top: auto; transform: none; background-color: #fff; }
        #mainNav .navbar-brand, #mainNav .navbar-nav > li.nav-item > a.nav-link { color: #212529; }
        #mainNav .navbar-brand:hover, #mainNav .navbar-nav > li.nav-item > a.nav-link:hover { color: #0085a1; }
        .preview-notice { position: relative; z-index: 1; margin-top: 0; }
        html[data-theme="dark"] .preview-notice a { color: #a6e8f0; font-weight: 600; text-decoration: underline; }
        html[data-theme="dark"] .preview-notice a:hover { color: #fff; }
        @media (min-width: 992px) {
            #mainNav, #mainNav.is-fixed { position: relative; top: auto; transform: none; background-color: rgba(255, 255, 255, .96); }
            #mainNav .navbar-brand, #mainNav .navbar-nav > li.nav-item > a.nav-link { color: #212529; }
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
                        @if ($post->user)
                            <a href="{{ route('authors.show', $post->user) }}">{{ $post->user->name }}</a>
                        @else
                            <a href="{{ route('home') }}">Agusha Blog</a>
                        @endif
                        @if ($post->published_at)
                            on {{ $post->published_at->format('F j, Y') }}
                        @else
                            · Belum diterbitkan
                        @endif
                    </span>
                    <div class="post-view-count mt-2"><i class="far fa-eye me-1" aria-hidden="true"></i>{{ number_format($post->views ?? 0) }} kali dilihat</div>
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
                @unless ($isPreview ?? false)
                    <div class="mt-4">
                        @include('layout.reading-list-button', ['post' => $post, 'isSaved' => $isSaved ?? false])
                    </div>
                @endunless
                @if ($post->is_edit)
                    <div class="mt-3 text-end"><small class="text-muted">Edited</small></div>
                @endif
            </div>
        </div>
    </div>
</article>
@unless ($isPreview ?? false)
    <section class="comments-section" id="comments" aria-labelledby="commentsHeading">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="comment-toolbar mb-3">
                        <h2 class="comments-heading mb-0" id="commentsHeading">Komentar ({{ $post->comments->count() }})</h2>
                        <form action="{{ route('post', ['slug' => $post->slug]) }}" method="GET">
                            <label class="visually-hidden" for="commentsOrder">Urutkan komentar</label>
                            <select class="form-select form-select-sm" id="commentsOrder" name="comments_order" onchange="this.form.submit()">
                                <option value="oldest" {{ ($commentsOrder ?? 'oldest') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                                <option value="newest" {{ ($commentsOrder ?? 'oldest') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                            </select>
                        </form>
                    </div>

                    @auth
                        <form class="comment-composer mb-4" action="{{ route('dashboard.posts.comments.store', $post) }}" method="POST">
                            @csrf
                            <label class="form-label fw-semibold" for="newComment">Tulis komentar</label>
                            <textarea class="form-control @error('body') is-invalid @enderror" id="newComment" name="body" rows="4" maxlength="5000" required placeholder="Bagikan pendapatmu...">{{ old('body') }}</textarea>
                            @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="d-flex justify-content-end mt-3"><button class="btn btn-primary" type="submit">Kirim komentar</button></div>
                        </form>
                    @else
                        <div class="comment-login-note mb-4">Silakan <a href="{{ route('login') }}">login</a> untuk menulis komentar.</div>
                    @endauth

                    @forelse ($post->comments as $comment)
                        <article class="comment-item" id="comment-{{ $comment->id }}">
                            <div class="comment-toolbar">
                                <div class="comment-author">
                                    <div class="comment-avatar" aria-hidden="true">
                                        @if ($comment->user->avatar)
                                            <img src="{{ asset('storage/'.$comment->user->avatar) }}" alt="" />
                                        @else
                                            <i class="fas fa-user"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $comment->user->name }}</div>
                                        <time class="small text-muted" datetime="{{ $comment->created_at->toIso8601String() }}">{{ $comment->created_at->locale('id')->translatedFormat('d F Y, H:i') }}</time>
                                        @if ($comment->updated_at->gt($comment->created_at->addSeconds(2)))
                                            <small class="text-muted ms-1">· diedit</small>
                                        @endif
                                    </div>
                                </div>
                                @auth
                                    @if (auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Aksi komentar"><i class="fas fa-ellipsis" aria-hidden="true"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                @if (auth()->id() === $comment->user_id)
                                                    <li><button class="dropdown-item" type="button" data-comment-edit="{{ $comment->id }}">Edit</button></li>
                                                @endif
                                                <li>
                                                    <form action="{{ route('dashboard.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="dropdown-item text-danger" type="submit">Hapus</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                            <p class="comment-body" data-comment-body="{{ $comment->id }}">{{ $comment->body }}</p>
                            @auth
                                @if (auth()->id() === $comment->user_id)
                                    <form class="comment-edit-form mt-3" id="commentEditForm-{{ $comment->id }}" action="{{ route('dashboard.comments.update', $comment) }}" method="POST" hidden>
                                        @csrf
                                        @method('PUT')
                                        <label class="visually-hidden" for="commentEdit-{{ $comment->id }}">Edit komentar</label>
                                        <textarea class="form-control @error('body', 'editComment'.$comment->id) is-invalid @enderror" id="commentEdit-{{ $comment->id }}" name="body" maxlength="5000" required>{{ $errors->getBag('editComment'.$comment->id)->any() ? old('body', $comment->body) : $comment->body }}</textarea>
                                        @error('body', 'editComment'.$comment->id)<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        <div class="d-flex justify-content-end gap-2 mt-2">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" data-comment-cancel="{{ $comment->id }}">Batal</button>
                                            <button class="btn btn-sm btn-primary" type="submit">Simpan perubahan</button>
                                        </div>
                                    </form>
                                @endif
                            @endauth
                        </article>
                    @empty
                        <div class="text-muted py-3">Belum ada komentar. Jadilah yang pertama berkomentar.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('click', (event) => {
            const editButton = event.target.closest('[data-comment-edit]');
            const cancelButton = event.target.closest('[data-comment-cancel]');

            if (editButton) {
                const commentId = editButton.dataset.commentEdit;
                document.querySelector(`[data-comment-body="${commentId}"]`).hidden = true;
                document.getElementById(`commentEditForm-${commentId}`).hidden = false;
                document.getElementById(`commentEdit-${commentId}`).focus();
            }

            if (cancelButton) {
                const commentId = cancelButton.dataset.commentCancel;
                document.querySelector(`[data-comment-body="${commentId}"]`).hidden = false;
                document.getElementById(`commentEditForm-${commentId}`).hidden = true;
            }
        });
    </script>
@endunless
@endsection
