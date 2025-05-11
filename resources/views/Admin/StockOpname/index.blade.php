@extends('Master.Layouts.app', ['title' => $title])

<!-- @section('title', $title) -->

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{$title}}</h1>
    </div>
</div>

<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Request Stock Opname</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap border-bottom" id="basic-datatable">
                        <thead>
                            <tr>
                                <th class="wd-15p border-bottom-0">No</th>
                                <th class="wd-15p border-bottom-0">Kode Request</th>
                                <th class="wd-15p border-bottom-0">Tanggal</th>
                                <th class="wd-15p border-bottom-0">Status</th>
                                <th class="wd-15p border-bottom-0">Dibuat Oleh</th>
                                <th class="wd-15p border-bottom-0">Disetujui Oleh</th>
                                <th class="wd-15p border-bottom-0">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal App -->
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#basic-datatable').DataTable({
            processing: true,
            serverSide: false,
            ajax: "{{ route('stock-opname.data') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'request_code',
                    name: 'request_code'
                },
                {
                    data: 'request_date',
                    name: 'request_date'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'user.user_nama',
                    name: 'user.user_nama'
                },
                {
                    data: 'approver.user_nama',
                    name: 'approver.user_nama'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });
</script>
@endsection