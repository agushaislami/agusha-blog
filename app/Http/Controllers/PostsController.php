<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Support\HtmlSanitizer;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'category' => ['nullable', 'string', 'max:255'],
            'tag' => ['nullable', 'string', 'max:255'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

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

        $featuredPost = (clone $query)->latest('published_at')->first();
        $showFeatured = $request->integer('page', 1) === 1;

        if ($featuredPost) {
            $query->whereKeyNot($featuredPost->id);
        }

        $posts = $query->latest('published_at')->paginate(5)->withQueryString();
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

        return view('pages.home', compact('posts', 'featuredPost', 'showFeatured', 'categories', 'tags', 'selectedCategory', 'selectedTag', 'searchTerm'));
    }

    public function show(HtmlSanitizer $htmlSanitizer, string $slug): View
    {
        $post = Post::query()
            ->with(['user', 'category', 'tags'])
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('slug', $slug)
            ->firstOrFail();

        $bodyHtml = $htmlSanitizer->sanitize($post->body);
        $previewCategoryName = $post->category?->name;
        $previewTagNames = $post->tags->pluck('name');

        return view('pages.post', compact('post', 'bodyHtml', 'previewCategoryName', 'previewTagNames'));
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }
}
