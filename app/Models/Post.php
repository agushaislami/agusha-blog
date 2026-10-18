<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'user_id',
    'category_id',
    'title',
    'slug',
    'excerpt',
    'body',
    'featured_image',
    'image_caption',
    'status',
    'is_edit',
    'published_at',
])]
class Post extends Model
{
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_edit' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
