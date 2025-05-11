@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Request Stock Opname</h3>
                    <div class="card-tools">
                        <a href="{{ route('picker.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="200">Kode Request</th>
                                    <td>{{ $stockOpname->request_code }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Request</th>
                                    <td>{{ \Carbon\Carbon::parse($stockOpname->created_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Picker</th>
                                    <td>{{ $stockOpname->user->user_nmlengkap }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($stockOpname->status_request == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                        @elseif($stockOpname->status_request == 'approve')
                                        <span class="badge bg-success">Approved</span>
                                        @else
                                        <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($stockOpname->status_request != 'pending')
                                <tr>
                                    <th>Disetujui Oleh</th>
                                    <td>{{ $stockOpname->approver->user_nmlengkap }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Persetujuan</th>
                                    <td>{{ \Carbon\Carbon::parse($stockOpname->approved_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Stock System</th>
                                    <th>Stock Fisik</th>
                                    @if($stockOpname->status_request == 'approve' && Session::get('user')->role_id == 3)
                                    <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>{{ $stockOpname->barang->barang_kode }}</td>
                                    <td>{{ $stockOpname->barang->barang_nama }}</td>
                                    <td>{{ number_format($stockOpname->stock_system, 2) }}</td>
                                    <td>
                                        @if($stockOpname->status_request == 'approve')
                                        @if(Session::get('user')->role_id == 3)
                                        <input type="checkbox" class="stock-checkbox"
                                            data-id="{{ $stockOpname->stock_id }}"
                                            {{ $stockOpname->stock_in !== null ? 'checked' : '' }}>
                                        @else
                                        {{ $stockOpname->stock_in !== null ? 'Ada' : 'Belum Dicek' }}
                                        @endif
                                        @else
                                        -
                                        @endif
                                    </td>
                                    @if($stockOpname->status_request == 'approve' && Session::get('user')->role_id == 3)
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" onclick="viewHistory('{{ $stockOpname->stock_id }}')">
                                            <i class="fas fa-history"></i> Riwayat
                                        </button>
                                    </td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @if($stockOpname->keterangan)
                    <div class="mt-3">
                        <h5>Keterangan:</h5>
                        <p>{{ $stockOpname->keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Riwayat -->
<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel">Riwayat Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Stock Sebelum</th>
                                <th>Stock Sesudah</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody id="historyContent">
                            <!-- Content will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle checkbox change
        $('.stock-checkbox').change(function() {
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

    function viewHistory(stockId) {
        // Show loading state
        $('#historyContent').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');
        $('#historyModal').modal('show');

        // Fetch history data
        $.ajax({
            url: "{{ url('admin/stock-opname/history') }}/" + stockId,
            type: "GET",
            success: function(response) {
                if (response.status === 'success') {
                    let html = '';
                    if (response.data.length > 0) {
                        response.data.forEach(function(item) {
                            html += `
                                <tr>
                                    <td>${item.tanggal}</td>
                                    <td>${item.keterangan}</td>
                                    <td>${item.stock_sebelum}</td>
                                    <td>${item.stock_sesudah}</td>
                                    <td>${item.user}</td>
                                </tr>
                            `;
                        });
                    } else {
                        html = '<tr><td colspan="5" class="text-center">Tidak ada riwayat stock</td></tr>';
                    }
                    $('#historyContent').html(html);
                } else {
                    $('#historyContent').html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat data riwayat</td></tr>');
                }
            },
            error: function() {
                $('#historyContent').html('<tr><td colspan="5" class="text-center text-danger">Terjadi kesalahan saat memuat data riwayat</td></tr>');
            }
        });
    }
</script>
@endpush