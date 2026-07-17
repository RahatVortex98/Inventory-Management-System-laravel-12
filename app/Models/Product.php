<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'supplier_id',
        'brand_id',
        'name',
        'sku',
        'unit',
        'cost_price',
        'selling_price',
        'current_stock',
        'reorder',
        'status'
    ];

    public function supplier(){
        return $this->belongsTo(Suppliers::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function brand(){
        return $this->belongsTo(Brand::class);
    }
      public function stockmovements(){
        return $this->hasMany(StockMovement::class);
    }
}
