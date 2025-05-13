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
                                <table class="table table-bordered text-nowrap border-bottom" id="request_stockopname">
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
<script type="text/javascript">
    $(document).ready(function() {
        <?php if ($current_role == 3) { ?>
            var ajaxUrl = {
                url: "{{ route('picker.getstockopname') }}",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                error: function (xhr, error, thrown) {
                    console.log("AJAX error: " + thrown);
                }
            };
        <?php } else { ?>
            var ajaxUrl = {
                url: "{{ route('stock-opname.getdata') }}",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                error: function (xhr, error, thrown) {
                    console.log("AJAX error: " + thrown);
                }
            };
        <?php } ?>

        $('#request_stockopname').DataTable({
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