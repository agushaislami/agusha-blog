<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Support\HtmlSanitizer;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PostsController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'category' => ['nullable', 'string', 'max:255'],
            'tag' => ['nullable', 'string', 'max:255'],
            'q' => ['nullable', 'string', 'max:100'],
            'sort' => ['nullable', Rule::in(['newest', 'oldest', 'views'])],
        ]);

        $sort = $filters['sort'] ?? 'newest';
        $query = Post::query()
            ->with(['user', 'category', 'tags'])
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());

        if (! empty($filters['category'])) {
            $query->whereHas('category', fn (Builder $categoryQuery): Builder => $categoryQuery->where('slug', $filters['category']));
        }

        if (! empty($filters['tag'])) {
            $query->whereHas('tags', fn (Builder $tagQuery): Builder => $tagQuery->where('slug', $filters['tag']));
        }

        if (! empty($filters['q'])) {
            $searchTerm = trim($filters['q']);
            $query->where(function (Builder $searchQuery) use ($searchTerm): void {
                $searchQuery->where('title', 'like', '%'.$searchTerm.'%')
                    ->orWhere('excerpt', 'like', '%'.$searchTerm.'%');
            });
        } else {
            $searchTerm = '';
        }

        match ($sort) {
            'oldest' => $query->orderBy('published_at')->orderBy('id'),
            'views' => $query->orderByDesc('views')->orderByDesc('published_at'),
            default => $query->orderByDesc('published_at')->orderByDesc('id'),
        };

        $featuredPost = (clone $query)->first();
        $showFeatured = $request->integer('page', 1) === 1;

        if ($featuredPost) {
            $query->whereKeyNot($featuredPost->id);
        }

        $posts = $query->paginate(5)->withQueryString();
        $publishedPostConstraint = fn (Builder $postQuery): Builder => $postQuery
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
        $categories = Category::query()
            ->whereHas('posts', $publishedPostConstraint)
            ->withCount(['posts as published_posts_count' => $publishedPostConstraint])
            ->orderBy('name')
            ->get();
        $tags = Tag::query()
            ->whereHas('posts', $publishedPostConstraint)
            ->withCount(['posts as published_posts_count' => $publishedPostConstraint])
            ->orderByDesc('published_posts_count')
            ->orderBy('name')
            ->limit(12)
            ->get();
        $selectedCategory = $filters['category'] ?? null;
        $selectedTag = $filters['tag'] ?? null;
        $authenticatedUser = Auth::user();
        $savedPostIds = $authenticatedUser
            ? $authenticatedUser->savedPosts()->pluck('posts.id')->map(fn ($id): int => (int) $id)->all()
            : [];

        return view('pages.home', compact('posts', 'featuredPost', 'showFeatured', 'categories', 'tags', 'selectedCategory', 'selectedTag', 'searchTerm', 'savedPostIds', 'authenticatedUser', 'sort'));
    }

    public function show(Request $request, HtmlSanitizer $htmlSanitizer, string $slug): View
    {
        $validated = $request->validate([
            'comments_order' => ['nullable', Rule::in(['oldest', 'newest'])],
        ]);

        $commentsOrder = $validated['comments_order'] ?? 'oldest';
        $post = Post::query()
            ->with(['user', 'category', 'tags'])
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('slug', $slug)
            ->firstOrFail();

        $viewedPosts = $request->session()->get('viewed_posts', []);

        if (! in_array($post->id, $viewedPosts, true)) {
            $post->increment('views');
            $request->session()->put('viewed_posts', [...$viewedPosts, $post->id]);
        }

        $post->load(['comments' => function ($query) use ($commentsOrder): void {
            $query->with('user')->orderBy('created_at', $commentsOrder === 'newest' ? 'desc' : 'asc');
        }]);

        $bodyHtml = $htmlSanitizer->sanitize($post->body);
        $previewCategoryName = $post->category?->name;
        $previewTagNames = $post->tags->pluck('name');
        $isSaved = $request->user()?->savedPosts()->whereKey($post->id)->exists() ?? false;

        return view('pages.post', compact('post', 'bodyHtml', 'previewCategoryName', 'previewTagNames', 'isSaved', 'commentsOrder'));
    }

    public function about(Request $request): View
    {
        $validated = $request->validate([
            'lang' => ['nullable', Rule::in(['en', 'id'])],
        ]);
        $language = $validated['lang'] ?? 'en';

        return view('pages.about', compact('language'));
    }

    public function contact(): View
    {
        return view('pages.contact');
    }
}
