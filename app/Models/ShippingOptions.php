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
        'region'
    ];
    protected $appends = ['cities']; // so ->cities is available

    public function cityItems()
    {
        return $this->hasMany(ShippingOptionCity::class, 'shipping_option_id');
    }

    // Accessor: returns array of city strings
    public function getCitiesAttribute()
    {
        // If relation is not loaded, this will lazy load it
        return $this->cityItems->pluck('city')->values()->all();
    }


}