<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $table = 'customer_address';
    protected $primaryKey = 'id_alamat';
    protected $fillable = [
        'id_user',
        'alamat',
        'nama_kecamatan',
        'nama_kota',
        'nama_provinsi',
        'kode_pos',
        'is_primary'
    ];
}
