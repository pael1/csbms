<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'item_num',
        'invoice_no',
        'invoice_date',
        'delivery_no',
        'delivery_date',
        'amount',
        'remarks',
    ];

    protected $casts = [
        'item_num' => 'array',
        'invoice_date' => 'date',
        'delivery_date' => 'date',
    ];
}
