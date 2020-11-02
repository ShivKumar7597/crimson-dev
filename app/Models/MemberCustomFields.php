<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberCustomFields extends Model
{
    use HasFactory;

    protected $fillable = ['field_name', 'field_value', 'fieldable_type', 'fieldable_id'];

    /**
     * Get the owning custom fieldable model.
     */
    public function fieldable()
    {
        return $this->morphTo();
    }
}
