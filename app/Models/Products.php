<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id_produk';
    protected $fillable = [
        'kode_produk',
        'id_kategori',
        'nama_produk',
        'slug_produk',
        'detail_produk',
        'harga_produk',
        'stok_produk',
        'gambar_produk'
    ];
}
