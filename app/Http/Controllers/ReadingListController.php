<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReadingListController extends Controller
{
    public function index(Request $request): View
    {
        $posts = $request->user()
            ->savedPosts()
            ->with(['user', 'category', 'tags'])
            ->withCount('comments')
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderByPivotDesc('created_at')
            ->paginate(10);

        return view('dashboard.reading-list', compact('posts'));
    }

    public function store(Request $request, Post $post): RedirectResponse
    {
        abort_unless(
            $post->status === 'published' && $post->published_at !== null && $post->published_at->lte(now()),
            404
        );

        $request->user()->savedPosts()->syncWithoutDetaching([$post->id]);

        return back()->with('status', 'Artikel ditambahkan ke daftar bacaan.');
    }

    public function toggle(Request $request, Post $post): JsonResponse
    {
        abort_unless(
            $post->status === 'published' && $post->published_at !== null && $post->published_at->lte(now()),
            404
        );

        $savedPosts = $request->user()->savedPosts();
        $isSaved = $savedPosts->whereKey($post->id)->exists();

        if ($isSaved) {
            $savedPosts->detach($post->id);
        } else {
            $savedPosts->syncWithoutDetaching([$post->id]);
        }

        return response()->json([
            'isSaved' => ! $isSaved,
            'message' => $isSaved ? 'Artikel dihapus dari daftar bacaan.' : 'Artikel ditambahkan ke daftar bacaan.',
        ]);
    }

    public function destroy(Request $request, Post $post): RedirectResponse
    {
        $request->user()->savedPosts()->detach($post->id);

        return back()->with('status', 'Artikel dihapus dari daftar bacaan.');
    }
}
