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
    <ul class="nav nav-pills gap-2 mb-4" aria-label="Filter status artikel">
        <li class="nav-item"><a class="nav-link {{ $selectedStatus === 'draft' ? 'active' : '' }}" href="{{ route('dashboard.statuses', ['status' => 'draft']) }}">Draft</a></li>
        <li class="nav-item"><a class="nav-link {{ $selectedStatus === 'published' ? 'active' : '' }}" href="{{ route('dashboard.statuses', ['status' => 'published']) }}">Published</a></li>
    </ul>

    @if ($posts->isEmpty())
        <div class="dashboard-empty">Tidak ada artikel dengan status {{ $selectedStatus === 'draft' ? 'draft' : 'published' }}.</div>
    @else
        <div class="table-responsive">
            <table class="table dashboard-table mb-0">
                <thead>
                    <tr><th>Judul</th><th>Terakhir diubah</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-semibold">{{ $post->title }}</span>
                                    @if ($post->is_edit)
                                        <small class="text-warning-emphasis">Edited</small>
                                    @endif
                                </div>
                                <span class="badge text-bg-{{ $selectedStatus === 'published' ? 'success' : 'secondary' }}">{{ ucfirst($selectedStatus) }}</span>
                                @if ($post->category)
                                    <span class="badge text-bg-primary">{{ $post->category->name }}</span>
                                @endif
                                @foreach ($post->tags as $tag)
                                    <span class="badge text-bg-light border text-dark">#{{ $tag->name }}</span>
                                @endforeach
                            </td>
                            <td class="text-muted">{{ $post->updated_at->format('d M Y') }}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('dashboard.posts.preview', $post) }}" target="_blank" rel="noopener">Pratinjau</a>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('dashboard.posts.edit', $post) }}">Edit</a>
                                    <form action="{{ route('dashboard.posts.status.update', $post) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $selectedStatus === 'draft' ? 'published' : 'draft' }}" />
                                        <button class="btn btn-sm btn-outline-{{ $selectedStatus === 'draft' ? 'success' : 'secondary' }}" type="submit">
                                            {{ $selectedStatus === 'draft' ? 'Terbitkan' : 'Pindahkan ke draft' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('dashboard.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini? Tindakan ini tidak bisa dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
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
@endsection
