<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
  protected $fillable = [
    'product_id',
    'user_id',
    'type',
    'quantity',
    'reference_type',
    'reference_id'
    ];


     public function product() {
         return $this->belongsTo(Product::class); 
         }
    public function user() { 
        return $this->belongsTo(User::class); 
        }
     protected static function booted()
    {
        static::created(function ($movement) {
            $change = $movement->type === 'out' ? -$movement->quantity : $movement->quantity;
            $movement->product()->increment('current_stock', $change);
        });
    }
}
