@extends('Master.Layouts.app')
@section('title', 'Data Penyesuaian')
@section('content')
<div class="page-header">
    <h1 class="page-title">Data Penyesuaian</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/admin')}}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Data Penyesuaian</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Request Stock Opname</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-1" class="table table-bordered text-nowrap border-bottom">
                        <thead>
                            <tr>
                                <th class="border-bottom-0" width="5%">No</th>
                                <th class="border-bottom-0">Kode Request</th>
                                <th class="border-bottom-0">Tanggal</th>
                                <th class="border-bottom-0">Picker</th>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Request Stock Opname</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formApprove">
                    @csrf
                    <input type="hidden" name="penyesuaian_id" id="penyesuaian_id">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Dengan menyetujui request ini, picker akan mendapatkan data stock barang yang tercatat di sistem.
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Stock Tercatat</th>
                                </tr>
                            </thead>
                            <tbody id="stockList">
                                <!-- Data stock akan diisi melalui AJAX -->
                            </tbody>
                        </table>
                    </div>
                </form>
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

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#table-1').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('penyesuaian.getpenyesuaian') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false
                },
                {
                    data: 'penyesuaian_kode',
                    name: 'penyesuaian_kode'
                },
                {
                    data: 'tanggal',
                    name: 'penyesuaian_tanggal'
                },
                {
                    data: 'picker',
                    name: 'picker'
                },
                {
                    data: 'status',
                    name: 'penyesuaian_status'
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

    function showApproveModal(id) {
        $('#penyesuaian_id').val(id);

        // Ambil data stock barang
        $.get("{{ url('admin/penyesuaian/get-stock-data') }}/" + id, function(response) {
            if (response.status == 'success') {
                let html = '';
                response.data.forEach(function(item) {
                    html += `
                    <tr>
                        <td>${item.barang_kode}</td>
                        <td>${item.barang_nama}</td>
                        <td>${item.barang_stok}</td>
                    </tr>
                `;
                });
                $('#stockList').html(html);
                $('#modalApprove').modal('show');
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        });
    }

    function approveRequest() {
        let id = $('#penyesuaian_id').val();

        $.ajax({
            url: "{{ route('penyesuaian.approve') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                penyesuaian_id: id
            },
            success: function(response) {
                if (response.status == 'success') {
                    $('#modalApprove').modal('hide');
                    Swal.fire('Success', response.message, 'success').then(() => {
                        $('#table-1').DataTable().ajax.reload();
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            }
        });
    }

    function rejectRequest(id) {
        Swal.fire({
            title: 'Reject Request?',
            text: "Request yang ditolak tidak dapat dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Tolak Request!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('penyesuaian.reject') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        penyesuaian_id: id
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            Swal.fire('Success', response.message, 'success').then(() => {
                                $('#table-1').DataTable().ajax.reload();
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    }
                });
            }
        });
    }
</script>
@endpush