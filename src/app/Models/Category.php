<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use HasFactory, Sluggable, SoftDeletes;

    protected $fillable = ['icon', 'name', 'description', 'status', 'category_id', 'isMenu'];

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'category_id', 'id');
    }
    public function subCategoria(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id')->withDefault([
            'name' => '',
        ]);;
    }
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id', 'id');
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
