<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    // This is product model using products table
    protected $table = "products";
    protected $primaryKey = "id";

    public function category()
    {
        return $this->hasOne('App\Models\Category', 'id');
    }

}