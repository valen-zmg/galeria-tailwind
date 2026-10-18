<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = "productos";

    protected $fillable = [
        'producto',
        'precio',
        'stock',
        'categoria_id'
    ];
}
