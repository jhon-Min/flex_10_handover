<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $guard_name = 'api'; // or whatever guard you want to use

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'email_verified_at', 'password', 'remember_token', 'account_code', 'company_name', 'address_line1', 'address_line2', 'state', 'zip', 'profile_image', 'mobile', 'created_at', 'updated_at'
    ];

    protected $appends = ['image_url', 'name'];

    public function getImageUrlAttribute()
    {
        if ($this->profile_image) {
            return  Storage::disk('public')->url(Config::get('constant.USER_IMAGE_PATH')) . $this->profile_image;
        }
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = [
        'password', 'remember_token',
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
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'user_id');
    }


    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function status()
    {
        return Config::get('constant.user_account_status_lables')[$this->admin_approval_status];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }
    public function isAdmin()
    {
        return $this->roles()->whereIn('name', ['Admin', 'Super Admin'])->exists();
    }

    public function getUserId()
    {
        return $this->id;
    }
}
