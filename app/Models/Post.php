<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Post extends Model
{
    public $guarded = [];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
