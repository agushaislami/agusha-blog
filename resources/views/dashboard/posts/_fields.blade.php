<div class="autosave-indicator mb-4" id="autosaveStatus" role="status" aria-live="polite">
    <i class="fas fa-cloud" aria-hidden="true"></i>
    <span>
        @if ($autosave)
            Draft tersimpan otomatis pukul {{ $autosave->updated_at->format('H:i:s') }}.
        @else
            Autosave aktif
        @endif
    </span>
</div>

<div class="mb-4">
    <label class="form-label fw-semibold" for="title">Judul artikel</label>
    <input class="form-control form-control-lg @error('title') is-invalid @enderror" id="title" name="title" type="text" value="{{ old('title', $autosaveData['title'] ?? $post?->title ?? '') }}" maxlength="255" placeholder="Contoh: Catatan perjalanan pertamaku" required autofocus />
    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-4">
    <label class="form-label fw-semibold" for="excerpt">Ringkasan <span class="text-muted fw-normal">(opsional)</span></label>
    <textarea class="form-control @error('excerpt') is-invalid @enderror" id="excerpt" name="excerpt" rows="2" maxlength="1000" placeholder="Ringkasan singkat untuk daftar artikel">{{ old('excerpt', $autosaveData['excerpt'] ?? $post?->excerpt ?? '') }}</textarea>
    @error('excerpt')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="category">Kategori <span class="text-muted fw-normal">(opsional)</span></label>
        <input class="form-control @error('category') is-invalid @enderror" id="category" name="category" type="text" value="{{ old('category', $autosaveData['category'] ?? $post?->category?->name ?? '') }}" maxlength="100" placeholder="Contoh: Teknologi" />
        <div class="form-text">Artikel dengan nama kategori yang sama akan dikelompokkan bersama.</div>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="tags">Tag <span class="text-muted fw-normal">(opsional)</span></label>
        <input class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags" type="text" value="{{ old('tags', $autosaveData['tags'] ?? ($post?->tags?->pluck('name')->implode(', ') ?? '')) }}" maxlength="500" placeholder="Contoh: laravel, tutorial, web" />
        <div class="form-text">Pisahkan beberapa tag dengan koma.</div>
        @error('tags')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-4">
    <label class="form-label fw-semibold" for="body">Isi artikel</label>
    <div class="rich-editor-toolbar" role="toolbar" aria-label="Format artikel">
        <button class="btn btn-sm btn-light" type="button" data-command="bold" aria-label="Tebal" title="Tebal"><i class="fas fa-bold" aria-hidden="true"></i></button>
        <button class="btn btn-sm btn-light" type="button" data-command="italic" aria-label="Miring" title="Miring"><i class="fas fa-italic" aria-hidden="true"></i></button>
        <button class="btn btn-sm btn-light" type="button" data-command="underline" aria-label="Garis bawah" title="Garis bawah"><i class="fas fa-underline" aria-hidden="true"></i></button>
        <button class="btn btn-sm btn-light" type="button" data-command="inlineCode" aria-label="Kode inline" title="Kode inline"><i class="fas fa-code" aria-hidden="true"></i></button>
        <button class="btn btn-sm btn-light" type="button" data-command="formatBlock" data-value="h2" aria-label="Judul bagian" title="Judul bagian"><i class="fas fa-heading" aria-hidden="true"></i></button>
        <button class="btn btn-sm btn-light" type="button" data-command="formatBlock" data-value="blockquote" aria-label="Kutipan" title="Kutipan"><i class="fas fa-quote-right" aria-hidden="true"></i></button>
        <button class="btn btn-sm btn-light" type="button" data-command="insertUnorderedList" aria-label="Daftar" title="Daftar"><i class="fas fa-list-ul" aria-hidden="true"></i></button>
        <button class="btn btn-sm btn-light" type="button" data-command="createLink" aria-label="Tautan" title="Tautan"><i class="fas fa-link" aria-hidden="true"></i></button>
        <span class="rich-editor-toolbar-divider" aria-hidden="true"></span>
        <label class="visually-hidden" for="imageWidthSelect">Ukuran gambar terpilih</label>
        <select class="form-select form-select-sm align-self-center" id="imageWidthSelect" aria-label="Ukuran gambar" disabled style="width: auto">
            <option value="" selected>Ukuran gambar</option>
            <option value="small">Kecil · 50%</option>
            <option value="medium">Sedang · 75%</option>
            <option value="full">Penuh · 100%</option>
        </select>
        <label class="visually-hidden" for="imageAlignSelect">Posisi gambar terpilih</label>
        <select class="form-select form-select-sm align-self-center" id="imageAlignSelect" aria-label="Posisi gambar" disabled style="width: auto">
            <option value="" selected>Posisi gambar</option>
            <option value="left">Kiri</option>
            <option value="center">Tengah</option>
            <option value="right">Kanan</option>
        </select>
        <button class="btn btn-sm btn-light" id="insertInlineImage" type="button" aria-label="Sisipkan gambar" title="Sisipkan gambar"><i class="fas fa-image" aria-hidden="true"></i></button>
        <input class="d-none" id="inlineImageInput" type="file" accept="image/jpeg,image/png,image/gif,image/webp" />
    </div>
    <div class="rich-editor @error('body') is-invalid @enderror" id="richArticleEditor" contenteditable="true" role="textbox" aria-label="Isi artikel" aria-multiline="true" data-placeholder="Mulai menulis artikelmu di sini...">{!! $bodyContent !!}</div>
    <textarea class="d-none" id="body" name="body">{{ old('body', $post?->body ?? '') }}</textarea>
    <input id="autosaveId" name="autosave_id" type="hidden" value="{{ $autosave?->id }}" />
    <input id="autosavePostId" name="post_id" type="hidden" value="{{ $post?->id }}" />
    <div class="form-text">Gunakan toolbar untuk pemformatan dan menyisipkan gambar ke dalam artikel. Gambar maksimal 5 MB.</div>
    <div class="small text-danger mt-1" id="inlineImageFeedback" role="status"></div>
    @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="featured_image_file">Unggah gambar utama <span class="text-muted fw-normal">(opsional)</span></label>
        <input class="form-control @error('featured_image_file') is-invalid @enderror" id="featured_image_file" name="featured_image_file" type="file" accept="image/jpeg,image/png,image/gif,image/webp" />
        <div class="form-text">Format JPG, PNG, GIF, atau WebP. Maksimal 5 MB.</div>
        @error('featured_image_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <a
            class="featured-image-preview-link mt-3 {{ $featuredImagePreview ? '' : 'd-none' }}"
            id="featuredImagePreviewLink"
            href="{{ $featuredImagePreview ? asset($featuredImagePreview) : '' }}"
            target="_blank"
            rel="noopener"
            aria-label="Buka gambar utama di tab baru"
        >
            <img
                class="featured-image-preview"
                id="featuredImagePreview"
                src="{{ $featuredImagePreview ? asset($featuredImagePreview) : '' }}"
                alt="Preview gambar utama"
            />
        </a>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="featured_image">Atau URL gambar <span class="text-muted fw-normal">(opsional)</span></label>
        <input class="form-control @error('featured_image') is-invalid @enderror" id="featured_image" name="featured_image" type="url" value="{{ old('featured_image', $featuredImageUrl) }}" maxlength="255" placeholder="https://example.com/gambar.jpg" />
        @error('featured_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-12">
        <label class="form-label fw-semibold" for="image_caption">Keterangan gambar</label>
        <input class="form-control @error('image_caption') is-invalid @enderror" id="image_caption" name="image_caption" type="text" value="{{ old('image_caption', $autosaveData['image_caption'] ?? $post?->image_caption ?? '') }}" maxlength="255" placeholder="Keterangan singkat" />
        @error('image_caption')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<style>
    .autosave-indicator { display: flex; align-items: center; gap: .55rem; width: fit-content; max-width: 100%; padding: .55rem .8rem; border: 1px solid #b6dce3; border-radius: 999px; background: #eef9fb; color: #075b6b; font-size: .875rem; font-weight: 600; }
    .autosave-indicator.is-saving { border-color: #f0d89b; background: #fff9e8; color: #765400; }
    .autosave-indicator.is-error { border-color: #f1b9bd; background: #fff1f2; color: #842029; }
    .autosave-indicator span { overflow-wrap: anywhere; }
    .rich-editor-toolbar { display: flex; flex-wrap: wrap; gap: .4rem; padding: .65rem; border: 1px solid #ced4da; border-bottom: 0; border-radius: .375rem .375rem 0 0; background: #f7f9fa; }
    .rich-editor-toolbar button.is-active { border-color: #006b80; background: #0085a1; color: #fff; box-shadow: 0 0 0 .15rem rgba(0, 133, 161, .2); }
    .rich-editor-toolbar-divider { width: 1px; margin: 0 .25rem; background: #ced4da; }
    .rich-editor-toolbar button:focus-visible { outline: 2px solid #005f73; outline-offset: 2px; }
    .rich-editor { min-height: 18rem; padding: 1rem; overflow-wrap: anywhere; border: 1px solid #ced4da; border-radius: 0 0 .375rem .375rem; background: #fff; outline: none; }
    .rich-editor:focus { border-color: #86b7fe; box-shadow: 0 0 0 .25rem rgba(13, 110, 253, .15); }
    .rich-editor:empty::before { color: #6c757d; content: attr(data-placeholder); pointer-events: none; }
    .rich-editor img { display: block; max-width: 100%; height: auto; margin-right: auto; margin-left: auto; }
    .rich-editor img.article-image-small { width: 50%; }
    .rich-editor img.article-image-medium { width: 75%; }
    .rich-editor img.article-image-full { width: 100%; }
    .rich-editor img.article-image-align-left { margin-right: auto; margin-left: 0; }
    .rich-editor img.article-image-align-center { margin-right: auto; margin-left: auto; }
    .rich-editor img.article-image-align-right { margin-right: 0; margin-left: auto; }
    .rich-editor img.is-selected { outline: 2px solid #0085a1; outline-offset: 3px; }
    .rich-editor code, .post-body code { padding: .12em .35em; border: 1px solid #e1e7e9; border-radius: .3rem; background: #f1f4f5; color: #a23b52; font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', monospace; font-size: .9em; }
    .rich-editor figure { margin: 1rem 0; }
    .featured-image-preview-link { display: block; width: fit-content; max-width: 100%; }
    .featured-image-preview { display: block; width: min(100%, 420px); max-height: 280px; object-fit: contain; object-position: left center; border: 1px solid #dee2e6; border-radius: .5rem; background: #f7f9fa; }
</style>

<script>
    (() => {
        const editor = document.getElementById('richArticleEditor');
        const bodyField = document.getElementById('body');
        const form = editor?.closest('form');
        const uploadInput = document.getElementById('inlineImageInput');
        const uploadButton = document.getElementById('insertInlineImage');
        const imageWidthSelect = document.getElementById('imageWidthSelect');
        const imageAlignSelect = document.getElementById('imageAlignSelect');
        const featuredImageInput = document.getElementById('featured_image_file');
        const featuredImagePreview = document.getElementById('featuredImagePreview');
        const featuredImagePreviewLink = document.getElementById('featuredImagePreviewLink');
        const feedback = document.getElementById('inlineImageFeedback');
        const autosaveId = document.getElementById('autosaveId');
        const autosavePostId = document.getElementById('autosavePostId');
        const autosaveStatus = document.getElementById('autosaveStatus');
        let savedSelection = null;
        let selectedImage = null;
        let autosaveTimer = null;
        let autosaveQueue = Promise.resolve();
        let featuredImageUploaded = featuredImagePreview?.src.includes('/storage/posts/autosave/') ?? false;
        let allowFormSubmit = false;

        if (!editor || !bodyField || !form) {
            return;
        }

        const saveAutosave = async () => {
            bodyField.value = editor.innerHTML;
            setAutosaveStatus('Menyimpan draft...', 'is-saving');
            const formData = new FormData();
            const token = form.querySelector('input[name="_token"]').value;
            formData.append('_token', token);
            formData.append('autosave_id', autosaveId.value);
            formData.append('post_id', autosavePostId.value);
            formData.append('title', form.querySelector('[name="title"]').value);
            formData.append('excerpt', form.querySelector('[name="excerpt"]').value);
            formData.append('body', bodyField.value);
            formData.append('featured_image', form.querySelector('[name="featured_image"]').value);
            formData.append('image_caption', form.querySelector('[name="image_caption"]').value);
            formData.append('category', form.querySelector('[name="category"]').value);
            formData.append('tags', form.querySelector('[name="tags"]').value);
            const imageFile = featuredImageInput.files?.[0];

            if (imageFile && !featuredImageUploaded) {
                formData.append('featured_image_file', imageFile);
            }

            try {
                const response = await fetch(@json(route('dashboard.post-autosaves.store')), {
                    method: 'POST',
                    headers: { Accept: 'application/json' },
                    body: formData,
                });
                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message ?? 'Draft belum berhasil disimpan.');
                }

                autosaveId.value = result.id;

                if (imageFile && featuredImageInput.files?.[0] === imageFile) {
                    featuredImageUploaded = true;
                }

                setAutosaveStatus(`Draft tersimpan otomatis pukul ${result.saved_at}.`);
            } catch (error) {
                setAutosaveStatus(error.message, 'is-error');
            }
        };

        const setAutosaveStatus = (message, state = '') => {
            autosaveStatus.classList.remove('is-saving', 'is-error');
            if (state) {
                autosaveStatus.classList.add(state);
            }
            autosaveStatus.querySelector('span').textContent = message;
        };

        const queueAutosave = () => {
            window.clearTimeout(autosaveTimer);
            setAutosaveStatus('Perubahan belum tersimpan.', 'is-saving');
            autosaveTimer = window.setTimeout(() => {
                autosaveQueue = autosaveQueue.catch(() => {}).then(saveAutosave);
            }, 1200);
        };

        editor.addEventListener('input', queueAutosave);
        form.querySelectorAll('[name="title"], [name="excerpt"], [name="category"], [name="tags"], [name="featured_image"], [name="image_caption"]').forEach((field) => {
            field.addEventListener('input', queueAutosave);
        });

        form.addEventListener('submit', (event) => {
            if (allowFormSubmit) {
                bodyField.value = editor.innerHTML;

                return;
            }

            event.preventDefault();
            window.clearTimeout(autosaveTimer);
            autosaveQueue = autosaveQueue.catch(() => {}).then(saveAutosave).finally(() => {
                allowFormSubmit = true;
                form.requestSubmit(event.submitter);
                allowFormSubmit = false;
            });
        });

        featuredImageInput?.addEventListener('change', () => {
            const image = featuredImageInput.files?.[0];
            featuredImageUploaded = false;

            if (!image || !featuredImagePreview || !featuredImagePreviewLink) {
                queueAutosave();

                return;
            }

            const previewUrl = URL.createObjectURL(image);
            featuredImagePreview.src = previewUrl;
            featuredImagePreviewLink.href = previewUrl;
            featuredImagePreviewLink.classList.remove('d-none');
            queueAutosave();
        });

        const saveSelection = () => {
            const selection = window.getSelection();

            if (selection?.rangeCount && editor.contains(selection.anchorNode)) {
                savedSelection = selection.getRangeAt(0).cloneRange();
                updateToolbarState();
            }
        };

        const getSelectedHeading = () => {
            const selection = window.getSelection();
            const selectedNode = selection?.anchorNode;
            const selectedElement = selectedNode instanceof Element ? selectedNode : selectedNode?.parentElement;

            return selectedElement?.closest('#richArticleEditor h2') ?? null;
        };

        const getSelectedCode = () => {
            const selection = window.getSelection();
            const selectedNode = selection?.anchorNode;
            const selectedElement = selectedNode instanceof Element ? selectedNode : selectedNode?.parentElement;

            return selectedElement?.closest('#richArticleEditor code') ?? null;
        };

        const updateToolbarState = () => {
            document.querySelectorAll('.rich-editor-toolbar [data-command]').forEach((button) => {
                const command = button.dataset.command;
                let isActive = false;

                if (command === 'formatBlock') {
                    if (button.dataset.value === 'h2') {
                        isActive = getSelectedHeading() !== null;
                    } else {
                        const activeBlock = document.queryCommandValue('formatBlock').replace(/[<>]/g, '').toLowerCase();
                        isActive = activeBlock === button.dataset.value;
                    }
                } else if (command === 'inlineCode') {
                    isActive = getSelectedCode() !== null;
                } else {
                    isActive = document.queryCommandState(command);
                }

                button.classList.toggle('is-active', isActive);
                button.setAttribute('aria-pressed', String(isActive));
            });
        };

        const restoreSelection = () => {
            if (!savedSelection) {
                editor.focus();
                return;
            }

            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(savedSelection);
            editor.focus();
        };

        document.addEventListener('selectionchange', saveSelection);
        editor.addEventListener('keyup', updateToolbarState);
        editor.addEventListener('mouseup', updateToolbarState);
        editor.addEventListener('click', (event) => {
            const clickedImage = event.target.closest('img');

            if (!clickedImage || !editor.contains(clickedImage)) {
                return;
            }

            selectedImage?.classList.remove('is-selected');
            selectedImage = clickedImage;
            selectedImage.classList.add('is-selected');
            updateImageControls();
        });

        const updateImageControls = () => {
            imageWidthSelect.disabled = !selectedImage;
            imageWidthSelect.value = ['small', 'medium', 'full'].find((width) => selectedImage?.classList.contains(`article-image-${width}`)) ?? '';
            imageAlignSelect.disabled = !selectedImage;
            imageAlignSelect.value = ['left', 'center', 'right'].find((align) => selectedImage?.classList.contains(`article-image-align-${align}`)) ?? 'center';
        };

        imageWidthSelect.addEventListener('mousedown', saveSelection);
        imageWidthSelect.addEventListener('change', () => {
            if (!selectedImage || !imageWidthSelect.value) {
                return;
            }

            selectedImage.classList.remove('article-image-small', 'article-image-medium', 'article-image-full');
            selectedImage.classList.add(`article-image-${imageWidthSelect.value}`);
            editor.focus();
            queueAutosave();
        });

        imageAlignSelect.addEventListener('mousedown', saveSelection);
        imageAlignSelect.addEventListener('change', () => {
            if (!selectedImage || !imageAlignSelect.value) {
                return;
            }

            selectedImage.classList.remove('article-image-align-left', 'article-image-align-center', 'article-image-align-right');
            selectedImage.classList.add(`article-image-align-${imageAlignSelect.value}`);
            editor.focus();
            queueAutosave();
        });

        document.querySelectorAll('.rich-editor-toolbar [data-command]').forEach((button) => {
            button.addEventListener('mousedown', (event) => event.preventDefault());
            button.addEventListener('click', () => {
                restoreSelection();
                const command = button.dataset.command;

                if (command === 'createLink') {
                    const link = window.prompt('Masukkan URL tautan:');

                    if (link) {
                        document.execCommand(command, false, link);
                    }
                } else if (command === 'formatBlock') {
                    const activeHeading = button.dataset.value === 'h2' ? getSelectedHeading() : null;

                    if (activeHeading) {
                        const paragraph = document.createElement('p');

                        while (activeHeading.firstChild) {
                            paragraph.append(activeHeading.firstChild);
                        }

                        activeHeading.replaceWith(paragraph);
                        const range = document.createRange();
                        range.selectNodeContents(paragraph);
                        range.collapse(false);
                        const selection = window.getSelection();
                        selection.removeAllRanges();
                        selection.addRange(range);
                    } else {
                        document.execCommand(command, false, button.dataset.value);
                    }
                } else if (command === 'inlineCode') {
                    const selection = window.getSelection();

                    if (!selection || selection.isCollapsed || !editor.contains(selection.anchorNode)) {
                        return;
                    }

                    const selectedCode = getSelectedCode();

                    if (selectedCode) {
                        const selectionRange = selection.getRangeAt(0);
                        const codeContents = document.createDocumentFragment();

                        while (selectedCode.firstChild) {
                            codeContents.append(selectedCode.firstChild);
                        }

                        selectedCode.replaceWith(codeContents);
                        selection.removeAllRanges();
                        selection.addRange(selectionRange);
                    } else {
                        const range = selection.getRangeAt(0);
                        const codeElement = document.createElement('code');
                        codeElement.append(range.extractContents());
                        range.insertNode(codeElement);
                        range.selectNodeContents(codeElement);
                        selection.removeAllRanges();
                        selection.addRange(range);
                    }
                } else {
                    document.execCommand(command, false, button.dataset.value ?? null);
                }

                saveSelection();
                updateToolbarState();
                queueAutosave();
            });
        });

        uploadButton?.addEventListener('mousedown', (event) => event.preventDefault());
        uploadButton?.addEventListener('click', () => uploadInput?.click());

        uploadInput?.addEventListener('change', async () => {
            const image = uploadInput.files?.[0];

            if (!image) {
                return;
            }

            feedback.textContent = 'Mengunggah gambar...';
            const formData = new FormData();
            formData.append('image', image);

            try {
                const response = await fetch(@json(route('dashboard.posts.inline-images.store')), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                        Accept: 'application/json',
                    },
                    body: formData,
                });
                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.errors?.image?.[0] ?? 'Gambar gagal diunggah.');
                }

                restoreSelection();
                const figure = document.createElement('figure');
                const imageElement = document.createElement('img');
                imageElement.src = result.url;
                imageElement.alt = image.name.replace(/\.[^.]+$/, '');
                imageElement.className = 'article-image article-image-medium article-image-align-center';
                figure.append(imageElement);
                const selection = window.getSelection();

                if (selection?.rangeCount && editor.contains(selection.anchorNode)) {
                    selection.getRangeAt(0).insertNode(figure);
                } else {
                    editor.append(figure);
                }
                selectedImage?.classList.remove('is-selected');
                selectedImage = imageElement;
                selectedImage.classList.add('is-selected');
                updateImageControls();
                savedSelection = null;
                feedback.textContent = '';
                queueAutosave();
            } catch (error) {
                feedback.textContent = error.message;
            } finally {
                uploadInput.value = '';
            }
        });

        form.addEventListener('submit', () => {
            bodyField.value = editor.innerHTML;
        });
    })();
</script>
