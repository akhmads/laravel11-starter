<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Filterable;

class Invoice extends Model
{
    use HasUlids, Filterable;

    protected $table = 'invoices';
    protected $guarded = [];

    public function items(): HasMany
	{
		return $this->hasMany(InvoiceItem::class,'invoice_id','id');
	}
}
