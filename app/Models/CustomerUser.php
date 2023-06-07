<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\ProductVehicles;

class CustomerUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
