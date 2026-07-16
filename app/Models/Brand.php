<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'status'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }


}

