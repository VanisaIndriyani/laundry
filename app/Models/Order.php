<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'code','customer_name','phone','items','status','received_at','due_at','completed_at','quantity','total_price','notes'
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'due_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->code)) {
                $order->code = strtoupper(Str::random(8));
            }
            if (empty($order->received_at)) {
                $order->received_at = now();
            }
        });
    }
}
