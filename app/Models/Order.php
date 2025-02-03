<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['customer_id','total_price'];

    public function items(){
       return $this->hasMany(OrderItem::class,'order_id','id');
    }

    public function customer(){
       return $this->belongsTo(Customer::class,'customer_id','id');
    }
}
