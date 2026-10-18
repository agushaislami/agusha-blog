@extends('dashboard.app')
@section('title', 'Status Artikel | Agusha Blog')

@section('content')
<header class="dashboard-heading d-flex flex-wrap align-items-end justify-content-between gap-3">
    <div>
        <h1>Status artikel</h1>
        <p>Kelola artikel draft dan artikel yang sudah terbit.</p>
    </div>
    <a class="btn btn-primary" href="{{ route('dashboard.posts.create') }}"><i class="fas fa-pen me-2" aria-hidden="true"></i>Tulis Artikel</a>
</header>

<section class="dashboard-panel">
    <ul class="nav nav-pills status-filter gap-2 mb-4" aria-label="Filter status artikel">
        <li class="nav-item"><a class="nav-link {{ $selectedStatus === 'draft' ? 'active' : '' }}" href="{{ route('dashboard.statuses', ['status' => 'draft', 'sort' => $sort]) }}">Draft</a></li>
        <li class="nav-item"><a class="nav-link {{ $selectedStatus === 'published' ? 'active' : '' }}" href="{{ route('dashboard.statuses', ['status' => 'published', 'sort' => $sort]) }}">Published</a></li>
    </ul>

    <form class="status-sort-form mb-3" action="{{ route('dashboard.statuses') }}" method="GET">
        <input type="hidden" name="status" value="{{ $selectedStatus }}" />
        <label class="small fw-semibold" for="postSort">Urutkan artikel</label>
        <select class="form-select form-select-sm" id="postSort" name="sort" onchange="this.form.submit()">
            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Terbaru</option>
            <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Terlama</option>
            <option value="views" {{ $sort === 'views' ? 'selected' : '' }}>Paling banyak dilihat</option>
        </select>
    </form>

    @if ($posts->isEmpty())
        <div class="dashboard-empty">Tidak ada artikel dengan status {{ $selectedStatus === 'draft' ? 'draft' : 'published' }}.</div>
    @else
        <div class="table-responsive">
            <table class="table dashboard-table status-table mb-0">
                <thead>
                    <tr><th>Judul</th><th>View</th><th>Terakhir diubah</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td>
                                <div class="status-title-line">
                                    <span class="status-post-title">{{ $post->title }}</span>
                                    @if ($post->is_edit)
                                        <small class="text-warning-emphasis">Edited</small>
                                    @endif
                                </div>
                                <div class="status-labels">
                                <span class="badge text-bg-{{ $selectedStatus === 'published' ? 'success' : 'secondary' }}">{{ ucfirst($selectedStatus) }}</span>
                                @if ($post->category)
                                    <span class="badge text-bg-primary">{{ $post->category->name }}</span>
                                @endif
                                @foreach ($post->tags as $tag)
                                    <span class="badge text-bg-light border text-dark">#{{ $tag->name }}</span>
                                @endforeach
                                </div>
                            </td>
                            <td><span class="status-view-count"><i class="far fa-eye me-1" aria-hidden="true"></i>{{ number_format($post->views ?? 0) }}</span></td>
                            <td class="text-muted">{{ $post->updated_at->format('d M Y') }}</td>
                            <td class="text-end">
                                <div class="status-actions">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('dashboard.posts.preview', $post) }}" target="_blank" rel="noopener" aria-label="Pratinjau artikel" title="Pratinjau"><i class="far fa-eye" aria-hidden="true"></i></a>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('dashboard.posts.edit', $post) }}" aria-label="Edit artikel" title="Edit"><i class="fas fa-pen" aria-hidden="true"></i></a>
                                    <form action="{{ route('dashboard.posts.status.update', $post) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $selectedStatus === 'draft' ? 'published' : 'draft' }}" />
                                        <button class="btn btn-sm btn-outline-{{ $selectedStatus === 'draft' ? 'success' : 'secondary' }}" type="submit" aria-label="{{ $selectedStatus === 'draft' ? 'Terbitkan artikel' : 'Pindahkan ke draft' }}" title="{{ $selectedStatus === 'draft' ? 'Terbitkan' : 'Pindahkan ke draft' }}">
                                            <i class="fas {{ $selectedStatus === 'draft' ? 'fa-paper-plane' : 'fa-box-archive' }}" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('dashboard.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini? Tindakan ini tidak bisa dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit" aria-label="Hapus artikel" title="Hapus"><i class="fas fa-trash" aria-hidden="true"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $posts->links() }}</div>
    @endif
</section>
<style>
    .status-table { font-size: .95rem; }
    .status-table td, .status-table th { padding: .65rem .55rem; }
    .status-table th:first-child { width: 48%; }
    .status-table th:nth-child(2) { width: 10%; }
    .status-table th:nth-child(3) { width: 17%; white-space: nowrap; }
    .status-table th:last-child { width: 25%; }
    .status-title-line { display: flex; align-items: center; gap: .45rem; margin-bottom: .35rem; }
    .status-post-title { font-weight: 650; line-height: 1.35; }
    .status-labels { display: flex; flex-wrap: wrap; gap: .25rem; }
    .status-labels .badge { padding: .25em .48em; font-size: .71rem; font-weight: 600; }
    .status-actions { display: flex; justify-content: flex-end; gap: .35rem; white-space: nowrap; }
    .status-actions .btn { min-width: 2rem; padding: .3rem .45rem; font-size: .78rem; }
    .status-view-count { font-size: .88rem; white-space: nowrap; }
    .status-sort-form { display: flex; align-items: center; justify-content: flex-end; gap: .65rem; }
    .status-sort-form .form-select { width: min(100%, 220px); }
    @media (max-width: 991.98px) { .status-actions { justify-content: flex-start; } }
</style>
@endsection
