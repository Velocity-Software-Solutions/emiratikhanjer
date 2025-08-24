<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingOptions extends Model
{
    //
    protected $fillable = [
        'name',
        'name_ar',
        'price',
        'delivery_time',
        'description',
        'country',
        'city',
        'region'
    ];
    // in ShippingOption.php
protected $casts = [
    'cities' => 'array',
];
}