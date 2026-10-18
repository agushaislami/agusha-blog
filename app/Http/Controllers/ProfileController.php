<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(User $user): View
    {
        $publishedPosts = Post::query()
            ->where('user_id', $user->id)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
        $publishedPostCount = (clone $publishedPosts)->count();
        $publishedPostViews = (clone $publishedPosts)->sum('views');
        $publishedCommentCount = Comment::query()
            ->whereIn('post_id', (clone $publishedPosts)->select('id'))
            ->count();
        $posts = $publishedPosts
            ->with(['category', 'tags'])
            ->withCount('comments')
            ->latest('published_at')
            ->paginate(6)
            ->withQueryString();

        return view('pages.author', compact('user', 'posts', 'publishedPostCount', 'publishedPostViews', 'publishedCommentCount'));
    }

    public function edit(Request $request): View
    {
        return view('dashboard.profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
        ]);

        $user = $request->user();
        $oldAvatar = $user->avatar;
        $user->name = $validated['name'];
        $user->bio = $validated['bio'] ?? null;

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->storePublicly('avatars', 'public');

            if ($avatarPath === false) {
                return back()
                    ->withErrors(['avatar' => 'Foto profil gagal disimpan. Silakan coba lagi.'])
                    ->withInput();
            }

            $user->avatar = $avatarPath;
        } elseif ($request->boolean('remove_avatar')) {
            $user->avatar = null;
        }

        $user->save();

        if ($oldAvatar && $oldAvatar !== $user->avatar && str_starts_with($oldAvatar, 'avatars/')) {
            Storage::disk('public')->delete($oldAvatar);
        }

        return redirect()
            ->route('dashboard.profile.edit')
            ->with('status', 'Profil penulis berhasil diperbarui.');
    }
}
