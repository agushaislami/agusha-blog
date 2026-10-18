@if (isset($authenticatedUser) ? $authenticatedUser !== null : auth()->check())
    <form action="{{ ($iconOnly ?? false) ? route('dashboard.reading-list.toggle', $post) : ($isSaved ? route('dashboard.reading-list.destroy', $post) : route('dashboard.reading-list.store', $post)) }}" method="POST" class="d-inline-flex" @if ($iconOnly ?? false) data-reading-list-form data-saved="{{ $isSaved ? 'true' : 'false' }}" @endif>
        @csrf
        @if (($isSaved && ! ($iconOnly ?? false)))
            @method('DELETE')
        @elseif ($iconOnly ?? false)
            @method('PATCH')
        @endif
        <button class="{{ ($iconOnly ?? false) ? 'reading-list-icon-button' : 'btn btn-sm '.($isSaved ? 'btn-outline-secondary' : 'btn-outline-primary') }}" type="submit" aria-label="{{ $isSaved ? 'Hapus dari daftar bacaan' : 'Simpan ke daftar bacaan' }}" title="{{ $isSaved ? 'Hapus dari daftar bacaan' : 'Simpan ke daftar bacaan' }}" @if ($iconOnly ?? false) data-reading-list-button @endif>
            <i class="{{ $isSaved ? 'fas fa-bookmark' : 'far fa-bookmark' }}" aria-hidden="true" data-reading-list-icon></i>
            @unless ($iconOnly ?? false)
                <span class="ms-1">{{ $isSaved ? 'Tersimpan' : 'Simpan' }}</span>
            @endunless
        </button>
    </form>
@else
    <button class="{{ ($iconOnly ?? false) ? 'reading-list-icon-button' : 'btn btn-sm btn-outline-primary' }}" type="button" aria-label="Masuk untuk menyimpan ke daftar bacaan" title="Masuk untuk menyimpan ke daftar bacaan" onclick="window.alert('Silakan login terlebih dahulu untuk menyimpan artikel.')"><i class="far fa-bookmark" aria-hidden="true"></i>@unless ($iconOnly ?? false)<span class="ms-1">Simpan</span>@endunless</button>
@endauth

@if ($iconOnly ?? false)
    <style>
        .reading-list-icon-button { display: inline-grid; width: 2.4rem; height: 2.4rem; place-items: center; padding: 0; border: 1px solid #d5e0e2; border-radius: 50%; background: #fff; color: #08758a; font-size: 1rem; line-height: 1; text-decoration: none; transition: background-color .18s ease, border-color .18s ease, color .18s ease, transform .18s ease; }
        .reading-list-icon-button:hover { transform: translateY(-1px); border-color: #08758a; background: #eaf6f8; color: #006b80; }
        .reading-list-icon-button:focus-visible { outline: 3px solid #56b6c5; outline-offset: 2px; }
        html[data-theme="dark"] .reading-list-icon-button { border-color: #46595e; background: #203238; color: #8de0ed; }
        html[data-theme="dark"] .reading-list-icon-button:hover { border-color: #67cad8; background: #203e45; color: #c5f5fa; }
    </style>
@endif
