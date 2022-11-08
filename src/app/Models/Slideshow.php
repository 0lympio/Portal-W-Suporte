<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slideshow extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'slideshow';

    protected $fillable = [
        'upload_id',
        'duration',
        'position',
        'published_at',
        'disabled_at',
        'status_id',
        'link',
    ];

    public function upload(): BelongsTo
    {
        return $this->belongsTo(Upload::class, 'upload_id', 'id');
    }
}
