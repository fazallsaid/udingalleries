<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    protected $fillable = [
        'email',
        'password',
        'nama_tampilan',
        'nomor_whatsapp',
        'role',
        'reset_token',
        'reset_token_expires'
    ];
}
