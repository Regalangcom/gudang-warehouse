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
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Filter Arsip Stock Opname</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('arsip-stock-opname.index') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label">Tanggal Mulai</label>
                                            <input type="date" class="form-control" name="start_date" value="{{ $start_date }}">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label">Tanggal Akhir</label>
                                            <input type="date" class="form-control" name="end_date" value="{{ $end_date }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Daftar Arsip Stock Opname</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="tbl_arsip_stock_opname">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0" width="50px">No</th>
                                            <th class="border-bottom-0">Kode Request</th>
                                            <th class="border-bottom-0">Tanggal</th>
                                            <th class="border-bottom-0">Requester</th>
                                            <th class="border-bottom-0">Status</th>
                                            <th class="border-bottom-0">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stockOpnameList as $index => $stockOpname)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $stockOpname->request_code }}</td>
                                            <td>{{ \Carbon\Carbon::parse($stockOpname->created_at)->format('d-m-Y') }}</td>
                                            <td>{{ $stockOpname->user ? $stockOpname->user->user_nmlengkap : '-' }}</td>
                                            <td>
                                                @if($stockOpname->status_request == 'approve')
                                                <span class="badge bg-success">Disetujui</span>
                                                @elseif($stockOpname->status_request == 'reject')
                                                <span class="badge bg-danger">Ditolak</span>
                                                @else
                                                <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('arsip-stock-opname.detail', $stockOpname->stock_id) }}" class="btn btn-primary btn-sm">
                                                    <i class="fe fe-eye"></i> Detail
                                                </a>
                                                <a href="{{ route('arsip-stock-opname.print', $stockOpname->stock_id) }}" class="btn btn-info btn-sm" target="_blank">
                                                    <i class="fe fe-printer"></i> Print
                                                </a>
                                                <a href="{{ route('arsip-stock-opname.pdf', $stockOpname->stock_id) }}" class="btn btn-danger btn-sm">
                                                    <i class="fe fe-file-text"></i> PDF
                                                </a>
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
        $('#tbl_arsip_stock_opname').DataTable();
    });
</script>
@endsection