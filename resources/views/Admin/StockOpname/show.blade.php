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
                        <li class="breadcrumb-item"><a href="{{ route('stock-opname.index') }}">Stock Opname</a></li>
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
                                            <th>Requester</th>
                                            <td>{{ $stockOpname->user ? $stockOpname->user->user_nmlengkap : '-' }}</td>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($details as $index => $detail)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $detail->barang->barang_kode }}</td>
                                            <td>{{ $detail->barang->barang_nama }}</td>
                                            <td>{{ $detail->stock_system }}</td>
                                            <td>{{ $detail->stock_in ?? '-' }}</td>
                                            <td>
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
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
<script>
    $(document).ready(function() {
        <?php if ($stockOpname->status_request == 'approve') { ?>
            $('#tbl_stockopname_detail').DataTable();
        <?php } ?>
    });
</script>
@endsection