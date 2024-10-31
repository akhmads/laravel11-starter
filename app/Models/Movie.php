<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movie extends Model
{
    use Filterable;

    protected $table = 'movies';
    protected $guarded = [];

    public function cast(): BelongsTo
	{
		return $this->belongsTo(Cast::class,'cast_id','id')->withDefault();
	}
}
