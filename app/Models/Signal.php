<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signal extends Model
{
    public function orderType() {
        return $this->belongsTo(OrderType::class, 'order_type_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_name', 'name');
    }
}
