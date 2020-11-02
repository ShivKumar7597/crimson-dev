<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductGroup extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'currentRmsId', 'current_rms_created_at', 'curent_rms_updated_at', 'user_id'];

    /**
     * Get the products for the product group.
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    /**
     * Get the user that this product group belongs to.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the product category icon.
     */
    public function icon()
    {
        return $this->morphOne('App\Models\Icon', 'imageable');
    }


    /**
     * Get all of the product group's custom fields.
     */
    public function customFields()
    {
        return $this->morphMany('App\Models\CustomFields', 'fieldable');
    }

    /**
     * Get all of the product groups based on the logged in user
     */
    public function scopeGetByUser($query, $id)
    {
        $query->select('id', 'name')->where('user_id', $id);
    }

    /**
     * Get all the product groups from the Current RMS for the first time
     * return the collection of the product groups
     */
    public static function getProductGroupsFromCurrentRms($api_token, $sub_domain)
    {
        $client = new CurrentRmsService($api_token, $sub_domain);
        $page = 0;
        $product_group_collections = collect();
        do {
            $call = $client->get('product_groups', array('page' => ++$page, 'per_page' => '100'));

            $count = $call['meta']['row_count'];

            $product_group_collections->push($call['product_groups']);
        } while ($count > 2);

        return $product_group_collections;
    }
}
