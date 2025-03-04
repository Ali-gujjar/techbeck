<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSlug extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
