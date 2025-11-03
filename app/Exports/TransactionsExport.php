<?php

namespace App\Exports;

use App\Models\Transactions;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Transactions::join('users', 'transactions.id_user', '=', 'users.id_user')
            ->join('customer_address', 'customer_address.id_alamat', '=', 'transactions.id_alamat')
            ->join('products', 'products.id_produk', '=', 'transactions.id_produk')
            ->select(
                'transactions.kode_transaksi',
                'transactions.tanggal_dan_waktu_pembelian',
                'users.nama_tampilan as customer_name',
                'users.email as customer_email',
                'customer_address.alamat',
                'customer_address.nama_kecamatan',
                'customer_address.nama_kota',
                'customer_address.nama_provinsi',
                'customer_address.kode_pos',
                'products.nama_produk',
                'products.kode_produk',
                'transactions.jumlah',
                'transactions.total',
                'transactions.metode_pembayaran',
                'transactions.jasa_pengiriman',
                'transactions.status_pembayaran',
                'transactions.status_pengiriman'
            );

        // Apply date filters
        if (isset($this->filters['start_date']) && $this->filters['start_date']) {
            $query->whereDate('transactions.tanggal_dan_waktu_pembelian', '>=', $this->filters['start_date']);
        }
        if (isset($this->filters['end_date']) && $this->filters['end_date']) {
            $query->whereDate('transactions.tanggal_dan_waktu_pembelian', '<=', $this->filters['end_date']);
        }

        // Apply payment status filter
        if (isset($this->filters['payment_status']) && $this->filters['payment_status'] && $this->filters['payment_status'] !== 'Semua Status Pembayaran') {
            $paymentStatusMapping = [
                'unpaid' => 'Belum Bayar',
                'pending' => 'Menunggu Verifikasi',
                'paid' => 'Lunas',
                'rejected' => 'Ditolak'
            ];

            if (isset($paymentStatusMapping[$this->filters['payment_status']])) {
                $query->where('transactions.status_pembayaran', '=', $paymentStatusMapping[$this->filters['payment_status']]);
            }
        }

        // Apply shipping status filter
        if (isset($this->filters['shipping_status']) && $this->filters['shipping_status'] && $this->filters['shipping_status'] !== 'Semua Status Pengiriman') {
            $shippingStatusMapping = [
                'belum_dikirim' => 'Belum Dikirim',
                'sedang_dikirim' => 'Sedang Dikirim',
                'diterima' => 'Diterima'
            ];

            if (isset($shippingStatusMapping[$this->filters['shipping_status']])) {
                $query->where('transactions.status_pengiriman', '=', $shippingStatusMapping[$this->filters['shipping_status']]);
            }
        }

        return $query->orderBy('transactions.tanggal_dan_waktu_pembelian', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Tanggal Pembelian',
            'Nama Customer',
            'Email Customer',
            'Alamat',
            'Kecamatan',
            'Kota',
            'Provinsi',
            'Kode Pos',
            'Nama Produk',
            'Kode Produk',
            'Jumlah',
            'Total',
            'Metode Pembayaran',
            'Jasa Pengiriman',
            'Status Pembayaran',
            'Status Pengiriman'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->kode_transaksi,
            \Carbon\Carbon::parse($transaction->tanggal_dan_waktu_pembelian)->format('d/m/Y H:i'),
            $transaction->customer_name,
            $transaction->customer_email,
            $transaction->alamat,
            $transaction->nama_kecamatan,
            $transaction->nama_kota,
            $transaction->nama_provinsi,
            $transaction->kode_pos,
            $transaction->nama_produk,
            $transaction->kode_produk,
            $transaction->jumlah,
            'Rp ' . number_format($transaction->total, 0, ',', '.'),
            $transaction->metode_pembayaran,
            $transaction->jasa_pengiriman,
            $transaction->status_pembayaran,
            $transaction->status_pengiriman
        ];
    }
}
