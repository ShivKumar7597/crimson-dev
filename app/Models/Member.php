<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];


   
    public function memberAddress()
    {
        return $this->belongsTo('App\Models\MembersAddress');
    }


    /**
     * Get the rate for the product.
     */
    public function Membership()
    {
        return $this->hasMany('App\Models\Membership');
    }

    // *
    //  * Get the accessories for the product.
    

    public function memberIcons()
    {
        return $this->hasMany('App\Models\MemberIcons');
    }

        /**
     * Get all of the products's custom fields.
     */
    public function customFields()
    {
        return $this->morphMany('App\Models\MemberCustomFields', 'fieldable');
    }

    /**
     * Get the user that this product belongs to.
     */

    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
   
    /**
     * Get all of the products based on the logged in user
     */
    public function scopeGetByUser($query, $id)
    {
        $query->where('user_id', $id);
    }


  public function scopeFilter($query, array $filters)
  {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('Members', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        });
    }


   public static function getMembersCurrentRms($api_token, $sub_domain)
    {
        $client = new CurrentRmsService($api_token, $sub_domain);
        $page = 0;
        $member_collections = collect();
        do {
            $call = $client->get('members', array('page' => ++$page, 'per_page' => '100', 'filtermode' => 'all'));
            $count = $call['meta']['row_count'];
            $member_collections->push($call['members']);
        } while ($count > 99);

        return $member_collections;
    }

}
