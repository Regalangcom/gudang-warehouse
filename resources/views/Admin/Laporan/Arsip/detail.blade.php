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
                        <li class="breadcrumb-item"><a href="{{ route('arsip-stock-opname.index') }}">Arsip Stock Opname</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Detail Arsip Stock Opname</h3>
                            <div class="ms-auto">
                                <a href="{{ route('arsip-stock-opname.print', $stockOpname->stock_id) }}" class="btn btn-info btn-sm" target="_blank">
                                    <i class="fe fe-printer"></i> Print
                                </a>
                                <a href="{{ route('arsip-stock-opname.pdf', $stockOpname->stock_id) }}" class="btn btn-danger btn-sm">
                                    <i class="fe fe-file-text"></i> PDF
                                </a>
                            </div>
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
                                            <td>{{ \Carbon\Carbon::parse($stockOpname->created_at)->format('d-m-Y') }}</td>
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

                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="tbl_arsip_detail">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0" width="50px">No</th>
                                            <th class="border-bottom-0">Kode Barang</th>
                                            <th class="border-bottom-0">Nama Barang</th>
                                            <th class="border-bottom-0">Stok Awal</th>
                                            <th class="border-bottom-0">Total Stok Sistem</th>
                                            <th class="border-bottom-0">Jumlah Keluar</th>
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
                                            <td>{{ $detail->stok_awal }}</td>
                                            <td>{{ $detail->stock_system  }}</td>
                                            <td>{{ $detail->jml_keluar }}</td>
                                            <td>{{ $detail->stock_in ?? '-' }}</td>
                                            <td>
                                                @if(isset($detail->selisih))
                                                @if($detail->selisih > 0)
                                                <span class="text-success">+{{ $detail->selisih }}</span>
                                                @elseif($detail->selisih < 0)
                                                    <span class="text-danger">{{ $detail->selisih }}</span>
                                                    @else
                                                    <span>{{ $detail->selisih }}</span>
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
        $('#tbl_arsip_detail').DataTable();
    });
</script>
@endsection