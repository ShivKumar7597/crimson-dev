<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessories extends Model
{
    use HasFactory;

    protected $fillable = [
        'related_id', 'parent_transaction_type', 'item_transaction_type', 'inclusion_type', 'mode', 'quantity', 'currentRmsId', 'zero_priced',
        'relatable_id', 'product_id'
    ];


    /**
     * Get the product that this accessory belongs to.
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
