<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ActiveStatus;
use App\Traits\HasFilter;

class Country extends Model
{
    use HasFilter;

    protected $table = 'countries';
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => ActiveStatus::class,
        ];
    }
}
