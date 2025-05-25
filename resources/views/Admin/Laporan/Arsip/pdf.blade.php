<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 5px 0;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table th, .info-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .info-table th {
            width: 150px;
            background-color: #f2f2f2;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #f2f2f2;
        }
        .text-success {
            color: green;
        }
        .text-danger {
            color: red;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN ARSIP STOCK OPNAME</h2>
        <p>Tanggal: {{ \Carbon\Carbon::parse($stockOpname->created_at)->format('d-m-Y') }}</p>
    </div>
    
    <table class="info-table">
        <tr>
            <th>Kode Request</th>
            <td>{{ $stockOpname->request_code }}</td>
        </tr>
        <tr>
            <th>Tanggal Request</th>
            <td>{{ \Carbon\Carbon::parse($stockOpname->created_at)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <th>Requester</th>
            <td>{{ $stockOpname->user ? $stockOpname->user->user_nmlengkap : '-' }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @if($stockOpname->status_request == 'approve')
                Disetujui
                @elseif($stockOpname->status_request == 'reject')
                Ditolak
                @else
                Pending
                @endif
            </td>
        </tr>
        <tr>
            <th>Keterangan</th>
            <td>{{ $stockOpname->keterangan ?: '-' }}</td>
        </tr>
    </table>
    
    <table class="data-table">
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Stok Awal</th>
                <th>Total Stok Sistem</th>
                <th>Jumlah Keluar</th>
                <th>Stok Aktual</th>
                <th>Selisih</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->barang->barang_kode }}</td>
                <td>{{ $detail->barang->barang_nama }}</td>
                <td>{{ $detail->stok_awal }}</td>
                <td>{{ $detail->total_stok }}</td>
                <td>{{ $detail->jml_keluar }}</td>
                <td>{{ $detail->stock_in ?? '-' }}</td>
                <td>
                    @if(isset($detail->selisih))
                        @if($detail->selisih > 0)
                        <span class="text-success">+{{ $detail->selisih }}</span>
                        @elseif($detail->selisih < 0)
                        <span class="text-danger">{{ $detail->selisih }}</span>
                        @else
                        {{ $detail->selisih }}
                        @endif
                    @else
                    -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>
