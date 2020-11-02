<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateCategoryPrices extends Model
{
    use HasFactory;

    protected $fillable=['price', 'price_category_id', 'price_category_name', 'current_rate_id', 'rate_id'];

    /**
    * Get the rate that this category price belongs to.
    */
    public function rate()
    {
        return $this->belongsTo('App\Models\Rates');
    }
}
