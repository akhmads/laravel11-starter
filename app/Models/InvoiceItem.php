<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;

class InvoiceItem extends Model
{
    use Filterable;

    protected $table = 'invoice_items';
    protected $guarded = [];
}
