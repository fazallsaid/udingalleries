<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_category';
    protected $primaryKey = 'id_kategori';
    protected $fillable = [
        'nama_kategori'
    ];
}
