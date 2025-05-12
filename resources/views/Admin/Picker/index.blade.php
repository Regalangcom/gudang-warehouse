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
            <div class="row ">
                <div class="col-12 col-sm-12  ">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Daftar Request Stock Opname</h3>
                            @if($current_role == 3) {{-- Asumsi role_id 2 adalah Picker --}}
                            <div class="card-options">
                                <a href="{{ route('picker.create') }}" class="btn btn-primary">Tambah Request</a>
                            </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="tbl_stockopname">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0" width="50px">No</th>
                                            <th class="border-bottom-0">Kode Request</th>
                                            <th class="border-bottom-0">Tanggal</th>
                                            <th class="border-bottom-0">Status</th>
                                            <th class="border-bottom-0" width="100px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($requests as $request)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $request->request_code }}</td>
                                            <td>{{ \Carbon\Carbon::parse($request->request_date)->format('d-m-Y') }}</td>
                                            <td>
                                                @if($request->status_request == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @elseif($request->status_request == 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                                @elseif($request->status_request == 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                                @else
                                                <span class="badge bg-secondary">Tidak Diketahui</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-info btn-sm" href="{{ route('picker.show', $request->stock_id) }}"><span class="fe fe-eye text-white fs-14"></span></a>
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
        // Load DataTables dengan URL yang sesuai berdasarkan role
        <?php if ($current_role == 3) { ?>
            var ajaxUrl = "<?php echo route('picker.getstockopname'); ?>";
        <?php } else { ?>
            var ajaxUrl = "<?php echo route('stock-opname.data'); ?>";
        <?php } ?>

        $('#tbl_stockopname').DataTable({
            processing: true,
            serverSide: true,
            ajax: ajaxUrl,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false
                },
                {
                    data: 'kode',
                    name: 'kode'
                },
                {
                    data: 'tanggal',
                    name: 'tanggal'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });
</script>
@endsection