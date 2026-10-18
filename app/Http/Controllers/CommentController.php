<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request): View
    {
        $comments = Comment::query()
            ->whereHas('post', fn (Builder $postQuery): Builder => $postQuery->where('user_id', $request->user()->id))
            ->with(['post', 'user'])
            ->latest()
            ->paginate(15);

        return view('dashboard.comments', compact('comments'));
    }

    public function store(Request $request, Post $post): RedirectResponse
    {
        abort_unless(
            $post->status === 'published' && $post->published_at !== null && $post->published_at->lte(now()),
            404
        );

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:1', 'max:5000'],
        ]);

        $request->user()->comments()->create([
            'post_id' => $post->id,
            'body' => trim($validated['body']),
        ]);

        return redirect()->to(route('post', ['slug' => $post->slug]).'#comments')->with('status', 'Komentar berhasil ditambahkan.');
    }

    public function update(Request $request, Comment $comment): RedirectResponse
    {
        abort_unless($comment->user_id === $request->user()->id, 403);
        $this->ensurePostIsPublished($comment->post);

        $validated = $request->validateWithBag('editComment'.$comment->id, [
            'body' => ['required', 'string', 'min:1', 'max:5000'],
        ]);

        $comment->update(['body' => trim($validated['body'])]);

        return redirect()->to(route('post', ['slug' => $comment->post->slug]).'#comment-'.$comment->id)->with('status', 'Komentar berhasil diperbarui.');
    }

    public function destroy(Request $request, Comment $comment): RedirectResponse
    {
        $post = $comment->post;
        abort_unless(
            $comment->user_id === $request->user()->id || $post->user_id === $request->user()->id,
            403
        );
        $this->ensurePostIsPublished($post);

        $comment->delete();

        return redirect()->to(route('post', ['slug' => $post->slug]).'#comments')->with('status', 'Komentar berhasil dihapus.');
    }

    private function ensurePostIsPublished(Post $post): void
    {
        abort_unless(
            $post->status === 'published' && $post->published_at !== null && $post->published_at->lte(now()),
            404
        );
    }
}
