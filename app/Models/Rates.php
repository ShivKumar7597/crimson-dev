<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rates extends Model
{
    use HasFactory;

    protected $guarded=[];

    /**
    * Get the product that this rate belongs to.
    */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
     * Get the category prices for the rate.
     */
    public function categoryPrices()
    {
        return $this->hasMany('App\Models\RateCategoryPrices');
    }
}
