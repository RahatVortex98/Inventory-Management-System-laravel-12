<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
   protected $fillable = ['supplier_id','user_id','total_amount','status'];
    public function supplier() { return $this->belongsTo(Suppliers::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(PurchaseItem::class); }
}
