@extends('Admin.Layouts.template')
@section('title', $title)
@section('content')

<div class="main-content app-content mt-0">
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
                                                @elseif($stockOpname->status_request == 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                                @elseif($stockOpname->status_request == 'rejected')
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

                            @if($stockOpname->status_request == 'approved')
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="tbl_stockopname_detail">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0" width="50px">No</th>
                                            <th class="border-bottom-0">Kode Barang</th>
                                            <th class="border-bottom-0">Nama Barang</th>
                                            <th class="border-bottom-0">Stok Sistem</th>
                                            <th class="border-bottom-0">Stok Aktual</th>
                                            <th class="border-bottom-0">Selisih</th>
                                            <th class="border-bottom-0" width="150px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($details as $index => $detail)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $detail->barang->barang_kode }}</td>
                                            <td>{{ $detail->barang->barang_nama }}</td>
                                            <td>
                                                <span id="stock-system-{{ $detail->id }}" class="stock-system" style="display: none;">
                                                    {{ $detail->stock_system }}
                                                </span>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input toggle-stock" type="checkbox" data-id="{{ $detail->id }}">
                                                    <label class="form-check-label">Lihat stok</label>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" id="stock-in-{{ $detail->id }}" class="form-control"
                                                    value="{{ $detail->stock_in }}" min="0" step="1">
                                            </td>
                                            <td id="difference-{{ $detail->id }}">
                                                @if($detail->stock_in !== null)
                                                @php
                                                $difference = $detail->stock_in - $detail->stock_system;
                                                @endphp

                                                @if($difference > 0)
                                                <span class="text-success">+{{ $difference }}</span>
                                                @elseif($difference < 0)
                                                    <span class="text-danger">{{ $difference }}</span>
                                                    @else
                                                    <span>{{ $difference }}</span>
                                                    @endif
                                                    @else
                                                    -
                                                    @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-primary btn-sm save-stock" data-id="{{ $detail->id }}">
                                                    <i class="fe fe-save"></i> Simpan
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @elseif($stockOpname->status_request == 'rejected')
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
<script>
    $(document).ready(function() {
        @if($stockOpname - > status_request == 'approved')
        $('#tbl_stockopname_detail').DataTable();

        // Toggle tampilan stok sistem
        $('.toggle-stock').on('change', function() {
            const detailId = $(this).data('id');
            if ($(this).is(':checked')) {
                $(`#stock-system-${detailId}`).show();
            } else {
                $(`#stock-system-${detailId}`).hide();
            }
        });

        // Simpan stok
        $('.save-stock').on('click', function() {
            const detailId = $(this).data('id');
            const stockIn = $(`#stock-in-${detailId}`).val();

            if (!stockIn) {
                alert('Silakan masukkan stok aktual.');
                return;
            }

            $.ajax({
                url: "{{ route('picker.updateStock', '') }}/" + detailId,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    stock_in: stockIn
                },
                success: function(response) {
                    if (response.success) {
                        // Hitung dan update selisih
                        const systemStock = parseFloat($(`#stock-system-${detailId}`).text());
                        const actualStock = parseFloat(stockIn);
                        const difference = actualStock - systemStock;

                        let differenceHtml = '';
                        if (difference > 0) {
                            differenceHtml = `<span class="text-success">+${difference}</span>`;
                        } else if (difference < 0) {
                            differenceHtml = `<span class="text-danger">${difference}</span>`;
                        } else {
                            differenceHtml = `<span>${difference}</span>`;
                        }

                        $(`#difference-${detailId}`).html(differenceHtml);
                        alert('Stok berhasil disimpan.');
                    }
                },
                error: function(error) {
                    alert('Terjadi kesalahan. Mohon coba lagi.');
                }
            });
        });
        @endif
    });
</script>
@endsection