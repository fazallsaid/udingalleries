<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table = 'bank_account';
    protected $primaryKey = 'id_akun_bank';
    protected $fillable = [
        'nama_bank',
        'nomor_rekening',
        'tanggal_ditambahkan',
        'aktif'
    ];

    protected $casts = [
        'nomor_rekening' => 'integer',
        'tanggal_ditambahkan' => 'datetime'
    ];
}
