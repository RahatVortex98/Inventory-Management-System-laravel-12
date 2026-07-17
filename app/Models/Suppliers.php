<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
protected $fillable = [
    'supplier_name',
    'contact_person' ,              
    'email',
    'phone',
    'address',
    'city',
    'postal_code',
    'country',
    'website',
    'tax_number',
    'status',
    'notes',
                    
];  

  public function products(){
        return $this->hasMany(Product::class);
    }
}
