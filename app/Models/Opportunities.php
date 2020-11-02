<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Opportunities extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];


    public function Membership()
    {
        return $this->hasMany('App\Models\Membership');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
   

    public function scopeGetByUser($query, $id)
    {
        $query->where('user_id', $id);
    }


  public function scopeFilter($query, array $filters)
  {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('Opportunities', function ($query) use ($search) {
                        $query->where('subject', 'like', '%' . $search . '%');
                    });
            });
        });
    }



   public static function getOpportunitiesCurrentRms($api_token, $sub_domain)
    {
        $client = new CurrentRmsService($api_token, $sub_domain);
        $page = 0;
        $opportunities_collections = collect();
        do {
            $call = $client->get('opportunities', array('page' => ++$page, 'per_page' => '100', 'filtermode' => 'all'));
            $count = $call['meta']['row_count'];
            $opportunities_collections->push($call['opportunities']);
        } while ($count > 99);

        return $opportunities_collections;
    }

}
