<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\PostStatus;
use App\Traits\HasFilter;

class Post extends Model
{
    use HasFactory, HasFilter;

    protected $table = 'posts';
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => PostStatus::class,
        ];
    }
}
