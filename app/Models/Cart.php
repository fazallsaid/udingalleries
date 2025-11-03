<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'id_keranjang';
    protected $fillable = [
        'id_user',
        'id_produk',
        'jumlah'
    ];
}
