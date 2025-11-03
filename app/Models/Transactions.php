<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id_transaksi';
    protected $fillable = [
        'kode_transaksi',
        'id_produk',
        'id_user',
        'jumlah',
        'total',
        'tanggal_dan_waktu_pembelian',
        'jasa_pengiriman',
        'id_alamat',
        'metode_pembayaran'
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'id_user', 'id_user');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'id_produk', 'id_produk');
    }
}
