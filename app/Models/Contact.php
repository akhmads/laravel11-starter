<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFilter;

class Contact extends Model
{
    use HasFactory, HasFilter;

    protected $table = 'contacts';
    protected $guarded = [];
}
