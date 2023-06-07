<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    //
    protected $fillable = ['id', 'product_id', 'user_id', 'description', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
