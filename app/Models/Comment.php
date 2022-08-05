<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Builder;

final class Comment extends Model
{
    use NodeTrait;

    public $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope(
            'order',
            fn (Builder $builder) => $builder->orderByDesc('created_at')
        );
    }
}
