<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cast extends Model
{
    use Filterable;

    protected $table = 'casts';
    protected $guarded = [];

    public function movies(): HasMany
	{
		return $this->hasMany(Movies::class,'cast_id','id');
	}
}
