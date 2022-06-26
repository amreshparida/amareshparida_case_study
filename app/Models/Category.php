<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
        // This is category model using categories table

    protected $table = "categories";
    protected $primaryKey = "id";

        // Mass fillable for generating auto category based on unique category name
    protected $fillable = [
        'name'
    ];
}
