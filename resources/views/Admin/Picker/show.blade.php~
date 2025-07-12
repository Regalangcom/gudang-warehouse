@extends('Master.Layouts.app')
@section('title', $title)
@section('content')

<div class="main-content app-content mt-0 mx-auto">
    <div class="side-app">
        <div class="main-container container-fluid">
            <div class="page-header">
                <h1 class="page-title">{{ $title }}</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('picker.index') }}">Stock Opname</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Detail Stock Opname</h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5>Informasi Request</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="150">Kode Request</th>
                                            <td>{{ $stockOpname->request_code }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Request</th>
                                            <td>{{ \Carbon\Carbon::parse($stockOpname->request_date)->format('d-m-Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($stockOpname->status_request == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @elseif($stockOpname->status_request == 'approve')
                                                <span class="badge bg-success">Disetujui</span>
                                                @elseif($stockOpname->status_request == 'reject')
                                                <span class="badge bg-danger">Ditolak</span>
                                                @else
                                                <span class="badge bg-secondary">Tidak Diketahui</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td>{{ $stockOpname->keterangan ?: '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($stockOpname->status_request == 'approve')
                            @foreach($details as $index => $detail)
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="stock_opnamedetail">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0" width="50px">No</th>
                                            <th class="d-none"></th>
                                            <th class="border-bottom-0">Kode Barang</th>
                                            <th class="border-bottom-0">Nama Barang</th>
                                            <th class="border-bottom-0">Stock awal </th>
                                            <th class="border-bottom-0">Total Stock (Sistem)</th>
                                            <th class="border-bottom-0">Stock Aktual</th>
                                            <th class="border-bottom-0">Selisih</th>
                                            <!-- <th class="border-bottom-0">Stock Akhir</th> -->
                                            @if($stockOpname->user_id == session('user')->user_id)
                                            <th class="border-bottom-0" width="150px">Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="d-none">{{ $detail->stock_detail_id }}</td>
                                            <td>{{ $detail->barang->barang_kode }}</td>
                                            <td>{{ $detail->barang->barang_nama }}</td>
                                            <td>
                                                <span id="stock-system-{{ $detail->stock_detail_id }}" class="stock-system" style="display: none;">
                                                    <?= number_format($detail->stock_system, 2)  ?>
                                                </span>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input toggle-stock" type="checkbox" data-id="{{ $detail->stock_detail_id }}">
                                                    <label class="form-check-label">Lihat total stock</label>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                $totalStok = $totalStocks[$detail->stock_detail_id] ?? 0;
                                                @endphp
                                                @if($totalStok == 0)
                                                <span class="">{{ number_format($totalStok, 2) }}</span>
                                                @elseif($totalStok > 0)
                                                <span class="text-success">{{ number_format($totalStok, 2) }}</span>
                                                @else
                                                <span class="text-danger">{{ number_format($totalStok, 2) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="number" id="stock-in-{{ $detail->stock_detail_id }}" class="form-control" min="0" step="1">
                                            </td>
                                            <td id="difference-{{ $detail->stock_detail_id }}">
                                                @if($detail->stock_in !== null)
                                                @php
                                                $selisih = $detail->stock_in - ($totalStocks[$detail->stock_detail_id] ?? 0);
                                                @endphp
                                                @if($selisih > 0)
                                                <span class="text-success">+{{ $selisih }}</span>
                                                @elseif($selisih < 0)
                                                    <span class="text-danger">-{{ $selisih }}</span>
                                                    @else
                                                    <span>{{ $selisih }}</span>
                                                    @endif
                                                    @else
                                                    -
                                                    @endif
                                            </td>
                                            <!-- <td id="final-stock-{{ $detail->stock_detail_id }}">
                                                @if($detail->stock_in !== null)
                                                @php
                                                $systemStock = $totalStocks[$detail->stock_detail_id] ?? 0;
                                                $selisih = $detail->stock_in - $systemStock;
                                                $finalStock = $systemStock + $selisih;
                                                @endphp
                                                {{ $finalStock }}
                                                @else
                                                -
                                                @endif
                                            </td> -->
                                            @if($stockOpname->user_id == session('user')->user_id)
                                            <td>
                                                <form id="formUpdateStock">
                                                    <button type="submit" class="btn btn-primary btn-sm save-stock">
                                                        <i class="fe fe-save"></i> Simpan
                                                    </button>
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endforeach
                            @elseif($stockOpname->status_request == 'reject')
                            <div class="alert alert-danger">
                                Request stock opname ditolak.
                                @if($stockOpname->keterangan)
                                <br>Keterangan: {{ $stockOpname->keterangan }}
                                @endif
                            </div>
                            @else
                            <div class="alert alert-info">
                                Request stock opname masih menunggu persetujuan.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<?php if ($stockOpname->status_request == 'approve') { ?>
    <script>
        $(document).ready(function() {
            var table = $('#stock_opnamedetail').DataTable();
            $('.toggle-stock').on('change', function() {
                let detailId = $(this).data('id');
                if ($(this).is(':checked')) {
                    $(`#stock-system-${detailId}`).show();
                } else {
                    $(`#stock-system-${detailId}`).hide();
                }
            });
            $('#stock_opnamedetail tbody').on('submit', '#formUpdateStock', function(e) {
                e.preventDefault();
                let tr = $(this).closest('tr');
                let rowData = table.row(tr).data();
                let detailId = tr.find('td:eq(1)').text().trim();

                if (isUUID(detailId)) {
                    let stockIn = $(`#stock-in-${detailId}`).val();

                    if (!stockIn) {
                        alert('Silakan masukkan stok aktual yang valid.');
                        return;
                    }

                    $(`#stock-system-${detailId}`).show();
                    saveStock(detailId, stockIn);
                }
            });

            function saveStock(detailId, stockIn) {
                console.log("Saving detailId:", detailId);
                console.log("Stock in:", stockIn);

                $.ajax({
                    url: "/admin/picker/opname/update-stock/" + detailId,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        stock_in: stockIn
                    },
                    success: function(response) {
                        if (response.success) {
                            const systemStock = response.stockSystem;
                            const actualStock = response.stockIn;
                            const difference = response.selisih;
                            // Calculate final stock after adjustment
                            const finalStock = systemStock + difference;

                            let differenceHtml = '';
                            if (difference > 0) {
                                differenceHtml = `<span class="text-success">+${difference}</span>`;
                            } else if (difference < 0) {
                                differenceHtml = `<span class="text-danger">${difference}</span>`;
                            } else {
                                differenceHtml = `<span>${difference}</span>`;
                            }

                            // Update kolom selisih
                            $(`#difference-${detailId}`).html(differenceHtml);
                            // Update kolom stock akhir
                            $(`#final-stock-${detailId}`).html(finalStock);
                            $('#stock_opnamedetail').DataTable().ajax.reload();
                            alert('Stok berhasil disimpan.');
                        } else {
                            alert('Gagal menyimpan stok. Silakan coba lagi.');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat menyimpan stok.');
                    }
                });
            }

            function isUUID(uuid) {
                return /^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i.test(uuid);
            }
        });
    </script>
<?php } ?>
@endsection