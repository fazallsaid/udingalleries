<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BankAccount::create([
            'nama_bank' => 'BNI',
            'nomor_rekening' => '1234567890',
            'tanggal_ditambahkan' => '2025-10-25 04:00:00',
            'aktif' => 'ya'
        ]);
    }
}
