<?php

namespace App\Models;

use App\Models\Scopes\OrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends BaseModel
{
    use HasFactory;

    protected $with = ["user"];

    protected $guarded = ['id'];

    protected $hidden = ['pivot'];

    public function user()
    {
        return $this->belongsTo(User::class,"user_id");
    }

    public function products(){
        return $this->belongsToMany(Product::class,"order_products")->withPivot(["price","quantity"]);
    }

    public function order_products(){
        return $this->hasMany(OrderProduct::class,"order_id");
    }

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new OrderScope());
    }
}
