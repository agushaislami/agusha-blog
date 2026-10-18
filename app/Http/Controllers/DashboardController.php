<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostAutosave;
use App\Models\Tag;
use App\Support\HtmlSanitizer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function create(Request $request, HtmlSanitizer $htmlSanitizer): View
    {
        $autosave = PostAutosave::query()
            ->where('user_id', $request->user()->id)
            ->whereNull('post_id')
            ->find($request->session()->get('post_autosave_create_id'));
        $autosaveData = $autosave?->payload ?? [];
        $bodyContent = $htmlSanitizer->sanitize(old('body', $autosaveData['body'] ?? ''));
        $featuredImagePreview = $autosaveData['featured_image'] ?? null;
        $featuredImageUrl = $this->externalImageUrl($featuredImagePreview);

        return view('dashboard.posts.create', compact('autosave', 'autosaveData', 'bodyContent', 'featuredImagePreview', 'featuredImageUrl'));
    }

    public function edit(Request $request, Post $post, HtmlSanitizer $htmlSanitizer): View
    {
        abort_unless($post->user_id === $request->user()->id, 403);

        $autosave = PostAutosave::query()
            ->where('user_id', $request->user()->id)
            ->where('post_id', $post->id)
            ->find($request->session()->get('post_autosave_edit_'.$post->id));
        $autosaveData = $autosave?->payload ?? [];
        $bodyContent = $htmlSanitizer->sanitize(old('body', $autosaveData['body'] ?? $post->body));
        $featuredImagePreview = $autosaveData['featured_image'] ?? $post->featured_image;
        $featuredImageUrl = $this->externalImageUrl($featuredImagePreview);

        return view('dashboard.posts.edit', compact('post', 'autosave', 'autosaveData', 'bodyContent', 'featuredImagePreview', 'featuredImageUrl'));
    }

    public function preview(Request $request, Post $post, HtmlSanitizer $htmlSanitizer): View
    {
        abort_unless($post->user_id === $request->user()->id, 403);

        $autosave = PostAutosave::query()
            ->where('user_id', $request->user()->id)
            ->where('post_id', $post->id)
            ->find($request->session()->get('post_autosave_edit_'.$post->id));

        if ($autosave) {
            $post->forceFill(array_intersect_key($autosave->payload, array_flip([
                'title',
                'excerpt',
                'body',
                'featured_image',
                'image_caption',
            ])));
        }

        $post->load(['user', 'category', 'tags']);
        $post->setRelation('comments', collect());
        $previewCategoryName = $autosave?->payload['category'] ?? $post->category?->name;
        $previewTagNames = isset($autosave?->payload['tags'])
            ? collect(explode(',', $autosave->payload['tags']))->map(fn (string $tag): string => trim($tag))->filter()->values()
            : $post->tags->pluck('name');
        $isPreview = true;
        $bodyHtml = $htmlSanitizer->sanitize($post->body);

        return view('pages.post', compact('post', 'isPreview', 'bodyHtml', 'previewCategoryName', 'previewTagNames'));
    }

    public function autosave(Request $request, HtmlSanitizer $htmlSanitizer): JsonResponse
    {
        $validated = $request->validate([
            'autosave_id' => ['nullable', 'integer'],
            'post_id' => ['nullable', 'integer'],
            'title' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'category' => ['nullable', 'string', 'max:100'],
            'tags' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string', 'max:1000000'],
            'featured_image' => ['nullable', 'url', 'max:255'],
            'featured_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            'image_caption' => ['nullable', 'string', 'max:255'],
        ]);

        $post = null;

        if (! empty($validated['post_id'])) {
            $post = Post::query()->where('user_id', $request->user()->id)->findOrFail($validated['post_id']);
        }

        $autosave = null;

        if (! empty($validated['autosave_id'])) {
            $autosave = PostAutosave::query()
                ->where('user_id', $request->user()->id)
                ->findOrFail($validated['autosave_id']);
            abort_unless($autosave->post_id === $post?->id, 403);
        }

        $previousPayload = $autosave?->payload ?? [];
        $featuredImage = $validated['featured_image'] ?: ($previousPayload['featured_image'] ?? $post?->featured_image);

        if (
            ! empty($validated['featured_image'])
            && isset($previousPayload['featured_image'])
            && $previousPayload['featured_image'] !== $validated['featured_image']
            && str_starts_with($previousPayload['featured_image'], 'storage/posts/autosave/')
        ) {
            Storage::disk('public')->delete(substr($previousPayload['featured_image'], strlen('storage/')));
        }

        if ($request->hasFile('featured_image_file')) {
            if (isset($previousPayload['featured_image']) && str_starts_with($previousPayload['featured_image'], 'storage/posts/autosave/')) {
                Storage::disk('public')->delete(substr($previousPayload['featured_image'], strlen('storage/')));
            }

            $imagePath = $request->file('featured_image_file')->storePublicly('posts/autosave', 'public');
            $featuredImage = 'storage/'.$imagePath;
        }

        $payload = [
            'title' => array_key_exists('title', $validated) ? ($validated['title'] ?? '') : ($previousPayload['title'] ?? $post?->title ?? ''),
            'excerpt' => array_key_exists('excerpt', $validated) ? $validated['excerpt'] : ($previousPayload['excerpt'] ?? $post?->excerpt),
            'body' => $htmlSanitizer->sanitize(array_key_exists('body', $validated) ? ($validated['body'] ?? '') : ($previousPayload['body'] ?? $post?->body ?? '')),
            'featured_image' => $featuredImage,
            'image_caption' => array_key_exists('image_caption', $validated) ? $validated['image_caption'] : ($previousPayload['image_caption'] ?? $post?->image_caption),
            'category' => $validated['category'] ?? '',
            'tags' => $validated['tags'] ?? '',
        ];

        if ($autosave) {
            $autosave->update(['payload' => $payload]);
        } else {
            $autosave = PostAutosave::query()->create([
                'user_id' => $request->user()->id,
                'post_id' => $post?->id,
                'payload' => $payload,
            ]);
        }

        $sessionKey = $post ? 'post_autosave_edit_'.$post->id : 'post_autosave_create_id';
        $request->session()->put($sessionKey, $autosave->id);

        return response()->json([
            'id' => $autosave->id,
            'saved_at' => $autosave->updated_at->format('H:i:s'),
        ]);
    }

    public function statuses(Request $request): View
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(['draft', 'published'])],
            'sort' => ['nullable', Rule::in(['newest', 'oldest', 'views'])],
        ]);

        $selectedStatus = $validated['status'] ?? 'published';
        $sort = $validated['sort'] ?? 'newest';
        $query = Post::query()
            ->where('user_id', $request->user()->id)
            ->with(['category', 'tags'])
            ->where('status', $selectedStatus);

        match ($sort) {
            'oldest' => $query->orderBy('created_at')->orderBy('id'),
            'views' => $query->orderByDesc('views')->orderByDesc('created_at'),
            default => $query->orderByDesc('created_at')->orderByDesc('id'),
        };

        $posts = $query->paginate(10)->withQueryString();

        return view('dashboard.statuses', compact('posts', 'selectedStatus', 'sort'));
    }

    public function updateStatus(Request $request, Post $post): RedirectResponse
    {
        abort_unless($post->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);

        $post->update([
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' ? ($post->published_at ?? now()) : null,
        ]);

        return redirect()
            ->route('dashboard.statuses', ['status' => $validated['status']])
            ->with('status', $validated['status'] === 'published' ? 'Artikel berhasil diterbitkan.' : 'Artikel dipindahkan ke draft.');
    }

    public function destroy(Request $request, Post $post): RedirectResponse
    {
        abort_unless($post->user_id === $request->user()->id, 403);

        $selectedStatus = in_array($post->status, ['draft', 'published'], true)
            ? $post->status
            : 'draft';

        if (is_string($post->featured_image) && str_starts_with($post->featured_image, 'storage/posts/')) {
            Storage::disk('public')->delete(substr($post->featured_image, strlen('storage/')));
        }

        $post->delete();

        return redirect()
            ->route('dashboard.statuses', ['status' => $selectedStatus])
            ->with('status', 'Artikel berhasil dihapus.');
    }

    public function store(Request $request, HtmlSanitizer $htmlSanitizer): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'category' => ['nullable', 'string', 'max:100'],
            'tags' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:1000000'],
            'featured_image' => ['nullable', 'url', 'max:255'],
            'featured_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            'image_caption' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'autosave_id' => ['nullable', 'integer'],
        ]);

        $autosave = null;

        if (! empty($validated['autosave_id'])) {
            $autosave = PostAutosave::query()
                ->where('user_id', $request->user()->id)
                ->whereNull('post_id')
                ->findOrFail($validated['autosave_id']);
        }

        $slugBase = Str::slug($validated['title']) ?: 'post';
        $slug = $slugBase;
        $suffix = 2;

        while (Post::query()->where('slug', $slug)->exists()) {
            $slug = $slugBase.'-'.$suffix;
            $suffix++;
        }

        $featuredImage = $validated['featured_image'] ?: ($autosave?->payload['featured_image'] ?? null);
        $validated['body'] = $htmlSanitizer->sanitize($validated['body']);
        $category = $validated['category'] ?? null;
        $tags = $validated['tags'] ?? null;

        if ($request->hasFile('featured_image_file')) {
            $imagePath = $request->file('featured_image_file')->storePublicly('posts', 'public');
            $featuredImage = 'storage/'.$imagePath;
        }

        unset($validated['featured_image_file'], $validated['autosave_id'], $validated['category'], $validated['tags']);

        $post = $request->user()->posts()->create([
            ...$validated,
            'slug' => $slug,
            'featured_image' => $featuredImage,
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);
        $this->syncPostTaxonomy($post, $category, $tags);

        $this->deleteAutosaveImage($autosave, $featuredImage);
        $autosave?->delete();
        $request->session()->forget('post_autosave_create_id');

        return redirect()
            ->route('dashboard')
            ->with('status', $validated['status'] === 'published' ? 'Artikel berhasil diterbitkan.' : 'Artikel tersimpan sebagai draft.');
    }

    public function update(Request $request, Post $post, HtmlSanitizer $htmlSanitizer): RedirectResponse
    {
        abort_unless($post->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'category' => ['nullable', 'string', 'max:100'],
            'tags' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:1000000'],
            'featured_image' => ['nullable', 'url', 'max:255'],
            'featured_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            'image_caption' => ['nullable', 'string', 'max:255'],
            'autosave_id' => ['nullable', 'integer'],
        ]);

        $autosave = null;

        if (! empty($validated['autosave_id'])) {
            $autosave = PostAutosave::query()
                ->where('user_id', $request->user()->id)
                ->where('post_id', $post->id)
                ->findOrFail($validated['autosave_id']);
        }

        $featuredImage = $validated['featured_image'] ?: ($autosave?->payload['featured_image'] ?? $post->featured_image);
        $validated['body'] = $htmlSanitizer->sanitize($validated['body']);
        $category = $validated['category'] ?? null;
        $tags = $validated['tags'] ?? null;

        if ($request->hasFile('featured_image_file')) {
            $imagePath = $request->file('featured_image_file')->storePublicly('posts', 'public');
            $featuredImage = 'storage/'.$imagePath;
        }

        unset($validated['featured_image_file'], $validated['featured_image'], $validated['autosave_id'], $validated['category'], $validated['tags']);

        $post->update([
            ...$validated,
            'featured_image' => $featuredImage,
            'status' => $post->status,
            'is_edit' => true,
            'published_at' => $post->status === 'published' ? ($post->published_at ?? now()) : null,
        ]);
        $this->syncPostTaxonomy($post, $category, $tags);

        $this->deleteAutosaveImage($autosave, $featuredImage);
        $autosave?->delete();
        $request->session()->forget('post_autosave_edit_'.$post->id);

        return redirect()
            ->route('dashboard.statuses', ['status' => $post->status])
            ->with('status', 'Perubahan artikel berhasil disimpan.');
    }

    public function index(Request $request): View
    {
        $posts = Post::query()
            ->where('user_id', $request->user()->id)
            ->latest('updated_at')
            ->take(5)
            ->get();

        $publishedPostIds = Post::query()
            ->where('user_id', $request->user()->id)
            ->where('status', 'published')
            ->select('id');

        $totalViews = (clone $publishedPostIds)->sum('views');
        $totalComments = Comment::query()
            ->whereIn('post_id', $publishedPostIds)
            ->count();
        $recentComments = Comment::query()
            ->whereIn('post_id', $publishedPostIds)
            ->with(['post', 'user'])
            ->latest()
            ->take(4)
            ->get();

        $postCounts = Post::query()
            ->where('user_id', $request->user()->id)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('dashboard.index', compact('posts', 'postCounts', 'totalViews', 'totalComments', 'recentComments'));
    }

    private function deleteAutosaveImage(?PostAutosave $autosave, ?string $preservedImage): void
    {
        $autosaveImage = $autosave?->payload['featured_image'] ?? null;

        if (
            is_string($autosaveImage)
            && $autosaveImage !== $preservedImage
            && str_starts_with($autosaveImage, 'storage/posts/autosave/')
        ) {
            Storage::disk('public')->delete(substr($autosaveImage, strlen('storage/')));
        }
    }

    private function externalImageUrl(?string $image): string
    {
        if (! is_string($image) || ! filter_var($image, FILTER_VALIDATE_URL)) {
            return '';
        }

        $scheme = parse_url($image, PHP_URL_SCHEME);

        return in_array(strtolower((string) $scheme), ['http', 'https'], true) ? $image : '';
    }

    private function syncPostTaxonomy(Post $post, ?string $categoryName, ?string $tagNames): void
    {
        $categoryName = trim($categoryName ?? '');

        if ($categoryName === '') {
            $post->category()->dissociate();
            $post->save();
        } else {
            $categorySlug = Str::slug($categoryName);

            if ($categorySlug === '') {
                $post->category()->dissociate();
                $post->save();
            } else {
                $category = Category::query()->firstOrCreate(
                    ['slug' => $categorySlug],
                    ['name' => $categoryName],
                );
                $post->category()->associate($category);
                $post->save();
            }
        }

        $tags = collect(explode(',', $tagNames ?? ''))
            ->map(fn (string $tag): string => trim($tag))
            ->filter()
            ->unique(fn (string $tag): string => Str::lower($tag))
            ->map(function (string $tag): ?int {
                $slug = Str::slug($tag);

                if ($slug === '') {
                    return null;
                }

                return Tag::query()->firstOrCreate(
                    ['slug' => $slug],
                    ['name' => $tag],
                )->id;
            })
            ->filter()
            ->values();

        $post->tags()->sync($tags);
    }
}
