@extends('Master.Layouts.app')
@section('title', 'Request Stock Opname')
@section('content')
<div class="page-header">
    <h1 class="page-title">Request Stock Opname</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/admin')}}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Request Stock Opname</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Request Stock Opname</h3>
                @if(Session::get('user')->role_id == 3)
                <div class="card-options">
                    <a href="{{route('picker.tambah')}}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Buat Request
                    </a>
                </div>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="stockOpnameTable" class="table table-bordered text-nowrap border-bottom">
                        <thead>
                            <tr>
                                <th class="border-bottom-0" width="5%">No</th>
                                <th class="border-bottom-0">Kode Request</th>
                                <th class="border-bottom-0">Tanggal</th>
                                <th class="border-bottom-0">Barang</th>
                                <th class="border-bottom-0">Picker</th>
                                <th class="border-bottom-0">Stock System</th>
                                <th class="border-bottom-0">Stock Fisik</th>
                                <th class="border-bottom-0">Status</th>
                                <th class="border-bottom-0" width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Approve -->
<div class="modal fade" id="modalApprove" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Request Stock Opname</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menyetujui request stock opname ini?</p>
                <p>Dengan menyetujui request ini, picker akan mendapatkan data stock barang yang tercatat di sistem.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" onclick="approveRequest()">
                    <i class="fas fa-check"></i> Setujui Request
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="modalReject" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Request Stock Opname</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menolak request stock opname ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="rejectRequest()">
                    <i class="fas fa-times"></i> Tolak Request
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#stockOpnameTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('picker.getstockopname') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false
                },
                {
                    data: 'request_code',
                    name: 'request_code'
                },
                {
                    data: 'tanggal',
                    name: 'tanggal'
                },
                {
                    data: 'barang',
                    name: 'barang'
                },
                {
                    data: 'picker',
                    name: 'picker'
                },
                {
                    data: 'stock_system',
                    name: 'stock_system'
                },
                {
                    data: 'stock_in',
                    name: 'stock_in'
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
                }
            ],
            order: [
                [2, 'desc']
            ], // Sort by tanggal descending
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });

        // Handle stock checkbox change
        $(document).on('change', '.stock-checkbox', function() {
            var stockId = $(this).data('id');
            var checked = $(this).prop('checked');

            $.ajax({
                url: "{{ route('picker.updateStock') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    stock_id: stockId,
                    checked: checked
                },
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                        // Revert checkbox if failed
                        $(this).prop('checked', !checked);
                    }
                },
                error: function(xhr) {
                    toastr.error('Terjadi kesalahan saat mengupdate stock');
                    // Revert checkbox if failed
                    $(this).prop('checked', !checked);
                }
            });
        });
    });

    var currentStockId = null;

    function showApproveModal(stockId) {
        currentStockId = stockId;
        $('#modalApprove').modal('show');
    }

    function showRejectModal(stockId) {
        currentStockId = stockId;
        $('#modalReject').modal('show');
    }

    function approveRequest() {
        if (!currentStockId) return;

        $.ajax({
            url: "{{ route('picker.approve') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                stock_id: currentStockId
            },
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    $('#modalApprove').modal('hide');
                    $('#stockOpnameTable').DataTable().ajax.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('Terjadi kesalahan saat menyetujui request');
            }
        });
    }

    function rejectRequest() {
        if (!currentStockId) return;

        $.ajax({
            url: "{{ route('picker.reject') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                stock_id: currentStockId
            },
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    $('#modalReject').modal('hide');
                    $('#stockOpnameTable').DataTable().ajax.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('Terjadi kesalahan saat menolak request');
            }
        });
    }
</script>
@endpush