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
                            <h3 class="card-title mb-0">Daftar Request Stock Opname</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="stock_opnames">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0" width="50px">No</th>
                                            <th class="border-bottom-0">Kode Request</th>
                                            <th class="border-bottom-0">Tanggal</th>
                                            <th class="border-bottom-0">Requester</th>
                                            <th class="border-bottom-0">Status</th>
                                            <th class="border-bottom-0" width="100px">Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Approve -->
<div class="modal fade" id="ApproveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Setujui Request Stock Opname</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="formApprove">
                <div class="modal-body">
                    <input type="hidden" name="stock_id" id="approve_stock_id">
                    <p>Apakah Anda yakin ingin menyetujui request stock opname ini?</p>
                    <p>Kode Request: <strong id="approve_request_code"></strong></p>
                    <div class="form-group">
                        <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                        <textarea name="keterangan" id="approve_keterangan" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="RejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Request Stock Opname</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="formReject">
                <div class="modal-body">
                    <input type="hidden" name="stock_id" id="reject_stock_id">
                    <p>Apakah Anda yakin ingin menolak request stock opname ini?</p>
                    <p>Kode Request: <strong id="reject_request_code"></strong></p>
                    <div class="form-group">
                        <label for="keterangan" class="form-label">Keterangan Penolakan</label>
                        <textarea name="keterangan" id="reject_keterangan" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection



@section('scripts')
<script type="text/javascript">
    // better handle ajak is here 
    // csrf token sudah terdapat di semua input framework
    $(document).ready(function() {
        $('#stock_opnames').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('stock-opname.getdata') }}",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                error: function(xhr, error, thrown) {
                    console.log("AJAX error: " + thrown);
                }
            },
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
                    data: 'requester',
                    name: 'requester'
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
            ]
        });

        // Approve Request
        $('#formApprove').on('submit', function(e) {
            e.preventDefault();
            let stock_id = $('#approve_stock_id').val();
            let keterangan = $('#approve_keterangan').val();

            $.ajax({
                url: "/admin/opname/update-status/" + stock_id,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    status: 'approved',
                    keterangan: keterangan
                },
                success: function(response) {
                    if (response.success) {
                        $('#ApproveModal').modal('hide');
                        $('#stock_opnames').DataTable().ajax.reload();
                        alert('Request stock opname berhasil disetujui.');
                    }
                },
                error: function(error) {
                    alert('Terjadi kesalahan. Mohon coba lagi.');
                }
            });
        });

        // Reject Request
        $('#formReject').on('submit', function(e) {
            e.preventDefault();
            let stock_id = $('#reject_stock_id').val();
            let keterangan = $('#reject_keterangan').val();

            $.ajax({
                url: "/admin/opname/update-status/" + stock_id,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    status: 'rejected',
                    keterangan: keterangan
                },
                success: function(response) {
                    if (response.success) {
                        $('#RejectModal').modal('hide');
                        $('#stock_opnames').DataTable().ajax.reload();
                        alert('Request stock opname berhasil ditolak.');
                    }
                },
                error: function(error) {
                    alert('Terjadi kesalahan. Mohon coba lagi.');
                }
            });
        });
    });

    // Function untuk set data modal approve
    function approve(data) {
        $('#approve_stock_id').val(data.stock_id);
        $('#approve_request_code').text(data.request_code);
    }

    // Function untuk set data modal reject
    function reject(data) {
        $('#reject_stock_id').val(data.stock_id);
        $('#reject_request_code').text(data.request_code);
    }
</script>
@endsection