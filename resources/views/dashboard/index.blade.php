@extends('dashboard.app')
@section('title', 'Dashboard | Agusha Blog')

@section('content')
<header class="dashboard-heading">
    <h1>Dashboard</h1>
    <p>Halo, {{ auth()->user()->name }}. Ini ringkasan artikelmu.</p>
</header>

<section class="row g-3 mb-4" id="statuses" aria-label="Status artikel">
    @foreach (['published' => 'Terbit', 'draft' => 'Draft'] as $status => $label)
        <div class="col-sm-6 col-xl-4">
            <a class="dashboard-panel dashboard-stat d-block text-decoration-none" href="{{ route('dashboard.statuses', ['status' => $status]) }}">
                {{ $label }}
                <strong>{{ $postCounts[$status] ?? 0 }}</strong>
            </a>
        </div>
    @endforeach
</section>

<section class="dashboard-panel mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div>
            <h2 class="h4 mb-1">Artikel terbaru</h2>
            <p class="text-muted mb-0">Lima artikel terakhir yang kamu kelola.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('dashboard.posts.create') }}"><i class="fas fa-pen me-2" aria-hidden="true"></i>Tulis Artikel</a>
    </div>
    @if ($posts->isEmpty())
        <div class="dashboard-empty">Kamu belum memiliki artikel. Mulai dengan menulis artikel pertamamu.</div>
    @else
        <div class="table-responsive">
            <table class="table dashboard-table mb-0">
                <thead>
                    <tr><th>Judul</th><th>Status</th><th>Terakhir diubah</th></tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td>
                                <span class="fw-semibold">{{ $post->title }}</span>
                                @if ($post->is_edit)<small class="ms-2 text-warning-emphasis">Edited</small>@endif
                            </td>
                            <td><span class="badge text-bg-{{ $post->status === 'published' ? 'success' : 'secondary' }}">{{ ucfirst($post->status) }}</span></td>
                            <td class="text-muted">{{ $post->updated_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>

@endsection
