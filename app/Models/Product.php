<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    /**
     * prevent update allowed_stock_type_name but it can create 
     *
     * @param  string  $value
     * @return void
     */
    public function setAllowedStockTypeNameAttribute($value)
    {
        if ($this->allowed_stock_type_name) {
            return;
        }

        $this->attributes['allowed_stock_type_name'] = $value;
    }

    /**
     * prevent update stock_method_name but it can create 
     *
     * @param  string  $value
     * @return void
     */
    public function setStockMethodNameAttribute($value)
    {
        if ($this->stock_method_name) {
            return;
        }

        $this->attributes['stock_method_name'] = $value;
    }

    /**
     * Get the product group that this product belongs to.
     */
    public function productGroup()
    {
        return $this->belongsTo('App\Models\ProductGroup', 'product_group_id');
    }

    /**
     * Get the user that this product belongs to.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the rate for the product.
     */
    public function rates()
    {
        return $this->hasMany('App\Models\Rates');
    }

    /**
     * Get the accessories for the product.
     */
    public function accessories()
    {
        return $this->hasMany('App\Models\Accessories');
    }

    /**
     * Get the alternative products for the product.
     */
    public function alternativeProducts()
    {
        return $this->hasMany('App\Models\AlternativeProducts');
    }

    /**
     * Get the products's icon.
     */
    public function icon()
    {
        return $this->morphOne('App\Models\Icon', 'imageable');
    }

    /**
     * Get all of the products's custom fields.
     */
    public function customFields()
    {
        return $this->morphMany('App\Models\CustomFields', 'fieldable');
    }

    /**
     * Get all of the products based on the logged in user
     */
    public function scopeGetByUser($query, $id)
    {
        $query->where('user_id', $id);
    }

    /**
     * Filter the products based on user search
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('allowed_stock_type_name', 'like', '%' . $search . '%')
                    ->orWhere('purchase_price', 'like', '%' . $search . '%')
                    ->orWhere('weight', 'like', '%' . $search . '%')
                    ->orWhereHas('productGroup', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        });
    }

    /**
     * Get all the products from the Current RMS for the first time
     * return the collection of the products
     */
    public static function getProductsFromCurrentRms($api_token, $sub_domain)
    {
        $client = new CurrentRmsService($api_token, $sub_domain);
        $page = 0;
        $product_collections = collect();
        do {
            $call = $client->get('products', array('page' => ++$page, 'per_page' => '100', 'filtermode' => 'all'));

            $count = $call['meta']['row_count'];

            $product_collections->push($call['products']);
        } while ($count > 99);

        return $product_collections;
    }


    public static function updateProductRms($api_token, $sub_domain, $data, $prod_id)
    {
        $client = new CurrentRmsService($api_token, $sub_domain);
        $client->put('put','products',$prod_id,$data);

        return $client;
    }
}
