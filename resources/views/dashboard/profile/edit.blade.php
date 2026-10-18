@extends('dashboard.app')
@section('title', 'Edit Profil Penulis | Agusha Blog')

@section('content')
<header class="dashboard-heading d-flex flex-wrap align-items-end justify-content-between gap-3">
    <div>
        <a class="text-decoration-none" href="{{ route('dashboard') }}">← Kembali ke dashboard</a>
        <h1 class="mt-3">Edit profil penulis</h1>
        <p>Perkenalkan dirimu kepada pembaca Agusha Blog.</p>
    </div>
    <a class="btn btn-outline-primary" href="{{ route('authors.show', $user) }}" target="_blank" rel="noopener">Lihat profil publik</a>
</header>

<section class="dashboard-panel">
    <form action="{{ route('dashboard.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="form-label fw-semibold" for="name">Nama penulis</label>
            <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name', $user->name) }}" maxlength="255" required />
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold" for="bio">Tentang penulis</label>
            <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="5" maxlength="1000" placeholder="Ceritakan minat, pengalaman, atau topik yang kamu tulis.">{{ old('bio', $user->bio) }}</textarea>
            <div class="form-text">Maksimal 1.000 karakter. Bio ini dapat dibaca publik.</div>
            @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold" for="avatar">Foto profil <span class="text-muted fw-normal">(opsional)</span></label>
            <input class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar" type="file" accept="image/jpeg,image/png,image/webp" />
            <div class="form-text">JPG, PNG, atau WebP. Maksimal 2 MB.</div>
            @error('avatar')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="mt-3">
                <img
                    class="author-avatar author-avatar-edit {{ $user->avatar ? '' : 'd-none' }}"
                    id="avatarPreview"
                    src="{{ $user->avatar ? asset('storage/'.$user->avatar) : '' }}"
                    alt="Preview foto profil"
                />
            </div>
            @if ($user->avatar)
                <div class="form-check mt-3">
                    <input class="form-check-input" id="remove_avatar" name="remove_avatar" type="checkbox" value="1" />
                    <label class="form-check-label" for="remove_avatar">Hapus foto profil saat ini</label>
                </div>
            @endif
        </div>

        <div class="d-flex justify-content-end border-top pt-4">
            <button class="btn btn-primary" type="submit">Simpan profil</button>
        </div>
    </form>
</section>

<style>
    .author-avatar { width: 128px; height: 128px; border: 1px solid #dce5e7; border-radius: 50%; object-fit: cover; }
    .author-avatar-edit { width: 112px; height: 112px; }
</style>
<script>
    (() => {
        const input = document.getElementById('avatar');
        const preview = document.getElementById('avatarPreview');

        input?.addEventListener('change', () => {
            const image = input.files?.[0];

            if (!image || !preview) {
                return;
            }

            preview.src = URL.createObjectURL(image);
            preview.classList.remove('d-none');
        });
    })();
</script>
@endsection
