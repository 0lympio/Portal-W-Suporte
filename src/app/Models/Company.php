<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['name', 'description', 'status'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'company_id', 'id');
    }
}
