<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token',
        'sub_domain'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the migration status for the current rms models associated.
     */
    public function currentRmsMigrations()
    {
        return $this->hasMany('App\Models\CurrentRmsMigration')->latest();
    }

    /**
     * Get the products added by the user.
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function members()
    {
        return $this->hasMany('App\Models\Members');
    }


    public function getLastSyncDate()
    {
        if ($this->currentRmsMigrations()->exists()) {
            $migration = $this->currentRmsMigrations()->first();
            return $migration->created_at->diffForHumans();
        }

        return "Not synced";
    }

    public function getCurrentRmsSyncStatus()
    {
        if (isset($this->sub_domain) && !empty($this->sub_domain)) {
            if ($this->currentRmsMigrations()->exists()) {
                return CurrentRmsSyncProcess::READY;
            } else {
                return CurrentRmsSyncProcess::SYNC;
            }
        }

        return CurrentRmsSyncProcess::SETUP;
    }
}
