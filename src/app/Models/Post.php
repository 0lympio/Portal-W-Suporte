<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $fillable = [
        'user_id',
        'title',
        'thumb',
        'description',
        'content',
        'slug',
        'published_at',
        'disabled_at',
        'category_id',
        'popup',
        'extras',
        'isMenu',
        'status_id',
        'protocol',
        'last_modified_by',
        'version',
    ];

    protected $casts = [
        'extras' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function views()
    {
        return $this->hasMany(PostView::class, 'post_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function lastModifiedBy()
    {
        return $this->belongsTo(User::class, 'last_modified_by', 'id');
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class, 'post_id', 'id');
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function getPostForQuestionnaire($questionnaire_id)
    {
        return self::where('extras->questionnaire_id', '=', $questionnaire_id)->get();
    }

    public function getValidPost()
    {
        return $this->where('published_at', '<=', now())->where('status_id', 1)->get();
    }
}
