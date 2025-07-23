<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Particular extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'type',
        'indoor_model',
        'outdoor_model',
        'indoor_sn',
        'outdoor_sn',
        'inv_1',
        'inv_2',
        'date_issued_1',
        'date_issued_2',
        'total',
        'remarks',
    ];

    protected $casts = [
        'indoor_sn' => 'array',
        'outdoor_sn' => 'array',
        'date_issued_1' => 'date',
        'date_issued_2' => 'date',
        'total' => 'float',
    ];
}
