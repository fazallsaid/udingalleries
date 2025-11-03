<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Udin Gallery</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            color: #8D5B4C;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-section td {
            padding: 5px;
            vertical-align: top;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        .table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
            font-size: 11px;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-align: center;
            display: inline-block;
        }
        .status-belum-bayar { background-color: #fee2e2; color: #dc2626; }
        .status-menunggu-verifikasi { background-color: #fef3c7; color: #d97706; }
        .status-lunas { background-color: #d1fae5; color: #059669; }
        .status-ditolak { background-color: #fee2e2; color: #dc2626; }
        .status-belum-dikirim { background-color: #f3f4f6; color: #6b7280; }
        .status-sedang-dikirim { background-color: #dbeafe; color: #2563eb; }
        .status-diterima { background-color: #d1fae5; color: #059669; }
        .total-row {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body onload="window.print();">
    <div class="header">
        <h1>Udin Gallery</h1>
        <p>Laporan Transaksi</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now('Asia/Jakarta')->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="info-section">
        <table>
            <tr>
                <td width="30%"><strong>Total Transaksi:</strong></td>
                <td>{{ $transactions->count() }} transaksi</td>
            </tr>
            <tr>
                <td><strong>Periode:</strong></td>
                <td>{{ $transactions->min('tanggal_dan_waktu_pembelian') ? \Carbon\Carbon::parse($transactions->min('tanggal_dan_waktu_pembelian'))->format('d/m/Y') : '-' }} - {{ $transactions->max('tanggal_dan_waktu_pembelian') ? \Carbon\Carbon::parse($transactions->max('tanggal_dan_waktu_pembelian'))->format('d/m/Y') : '-' }}</td>
            </tr>
            <tr>
                <td><strong>Total Pendapatan:</strong></td>
                <td>Rp {{ number_format($transactions->sum('total'), 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Kode Transaksi</th>
                <th width="12%">Tanggal</th>
                <th width="15%">Customer</th>
                <th width="20%">Alamat</th>
                <th width="15%">Produk</th>
                <th width="5%">Qty</th>
                <th width="8%">Total</th>
                <th width="5%">Status Bayar</th>
                <th width="5%">Status Kirim</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($transactions as $transaction)
            <tr>
                <td style="text-align: center;">{{ $no++ }}</td>
                <td style="font-family: 'Courier New', monospace; font-size: 10px;">{{ $transaction->kode_transaksi }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->tanggal_dan_waktu_pembelian)->format('d/m/Y H:i') }}</td>
                <td>
                    <strong>{{ $transaction->customer_name }}</strong><br>
                    <small>{{ $transaction->customer_email }}</small>
                </td>
                <td style="font-size: 10px;">
                    {{ $transaction->alamat }}<br>
                    {{ $transaction->nama_kecamatan }}, {{ $transaction->nama_kota }}<br>
                    {{ $transaction->nama_provinsi }} {{ $transaction->kode_pos }}
                </td>
                <td>
                    <strong>{{ $transaction->nama_produk }}</strong><br>
                    <small>{{ $transaction->kode_produk }}</small>
                </td>
                <td style="text-align: center;">{{ $transaction->jumlah }}</td>
                <td style="text-align: right;">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                <td style="text-align: center;">
                    @php
                        $statusClass = match($transaction->status_pembayaran) {
                            'Belum Bayar' => 'status-belum-bayar',
                            'Menunggu Verifikasi' => 'status-menunggu-verifikasi',
                            'Lunas' => 'status-lunas',
                            'Ditolak' => 'status-ditolak',
                            default => 'status-belum-bayar'
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ $transaction->status_pembayaran }}</span>
                </td>
                <td style="text-align: center;">
                    @php
                        $shippingClass = match($transaction->status_pengiriman) {
                            'Belum Dikirim' => 'status-belum-dikirim',
                            'Sedang Dikirim' => 'status-sedang-dikirim',
                            'Diterima' => 'status-diterima',
                            default => 'status-belum-dikirim'
                        };
                    @endphp
                    <span class="status-badge {{ $shippingClass }}">{{ $transaction->status_pengiriman }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="7" style="text-align: right;"><strong>TOTAL:</strong></td>
                <td style="text-align: right;"><strong>Rp {{ number_format($transactions->sum('total'), 0, ',', '.') }}</strong></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem Udin Gallery</p>
        <p>&copy; {{ date('Y') }} Udin Gallery. All rights reserved.</p>
    </div>
</body>
</html>
