<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentRmsMigration extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the user that owns the migration.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function member()
    {
    	return $this->belongsTo('App\Models\Member');
    }
}
