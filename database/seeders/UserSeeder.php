<?php

namespace Database\Seeders;

use App\Models\Users;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Users::create([
            'email' => 'user@mail.com',
            'password' => Hash::make('user1234'),
            'nama_tampilan' => 'Pengguna 1',
            'nomor_whatsapp' => '089372839283',
            'role' => 'customer'
        ]);

        Users::create([
            'email' => 'admin@mail.com',
            'password' => Hash::make('adm1234'),
            'nama_tampilan' => 'Admin Pusat',
            'nomor_whatsapp' => '0895386138202',
            'role' => 'admin'
        ]);
    }
}
