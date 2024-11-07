<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use Filterable;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function (Model $model) {
            $model->sender_id = auth()->user()->id;
        });
    }

    public function sender(): BelongsTo
	{
		return $this->belongsTo(User::class,'sender_id','id')->withDefault();
	}

    public function receiver(): BelongsTo
	{
		return $this->belongsTo(User::class,'receiver_id','id')->withDefault();
	}
}
