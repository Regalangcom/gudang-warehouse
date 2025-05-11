@extends('Master.Layouts.app')

@section('title', $title)

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{$title}}</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <a href="{{route('stock-opname.index')}}" class="btn btn-primary btn-icon text-white me-2">
            <span><i class="fe fe-arrow-left"></i></span> Kembali
        </a>
    </div>
</div>

<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Request Stock Opname</h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">Kode Request</th>
                                <td>{{$request->request_code}}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Request</th>
                                <td>{{$request->request_date->format('d/m/Y')}}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($request->status_request == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @elseif($request->status_request == 'approve')
                                    <span class="badge bg-success">Disetujui</span>
                                    @else
                                    <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Dibuat Oleh</th>
                                <td>{{$request->user->user_nama}}</td>
                            </tr>
                            <tr>
                                <th>Disetujui Oleh</th>
                                <td>{{$request->approver ? $request->approver->user_nama : '-'}}</td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td>{{$request->keterangan ?: '-'}}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($request->status_request == 'approve')
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap border-bottom" id="basic-datatable">
                        <thead>
                            <tr>
                                <th class="wd-15p border-bottom-0">Kode Barang</th>
                                <th class="wd-15p border-bottom-0">Nama Barang</th>
                                <th class="wd-15p border-bottom-0">Stock Sistem</th>
                                <th class="wd-15p border-bottom-0">Stock Fisik</th>
                                <th class="wd-15p border-bottom-0">Selisih</th>
                                <th class="wd-15p border-bottom-0">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($request->details as $detail)
                            <tr>
                                <td>{{$detail->barang->barang_kode}}</td>
                                <td>{{$detail->barang->barang_nama}}</td>
                                <td>
                                    <span class="stock-system" style="display: {{$detail->is_checked ? 'block' : 'none'}}">
                                        {{number_format($detail->stock_system, 2)}}
                                    </span>
                                </td>
                                <td>
                                    @if($request->status_request == 'approve')
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input stock-checkbox"
                                            data-id="{{$detail->id}}"
                                            {{$detail->is_checked ? 'checked' : ''}}>
                                    </div>
                                    @else
                                    {{$detail->stock_in ? number_format($detail->stock_in, 2) : '-'}}
                                    @endif
                                </td>
                                <td>
                                    @if($detail->difference !== null)
                                    <span class="{{$detail->difference != 0 ? 'text-danger' : 'text-success'}}">
                                        {{number_format($detail->difference, 2)}}
                                    </span>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    @if($request->status_request == 'approve' && $detail->stock_in === null)
                                    <button class="btn btn-primary btn-sm save-stock"
                                        data-id="{{$detail->id}}">
                                        <i class="fe fe-save"></i> Simpan
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#basic-datatable').DataTable();

        // Handle save stock
        $('.save-stock').click(function() {
            var detailId = $(this).data('id');
            var stockInput = $('.stock-input[data-id="' + detailId + '"]');
            var stockValue = stockInput.val();

            if (!stockValue) {
                alert('Mohon isi stock fisik');
                return;
            }

            $.ajax({
                url: '{{route("stock-opname.update-stock", "")}}/' + detailId,
                type: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    stock_in: stockValue
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan: ' + xhr.responseJSON.error);
                }
            });
        });

        // Handle checkbox change
        $('.stock-checkbox').change(function() {
            var detailId = $(this).data('id');
            var isChecked = $(this).prop('checked');
            var stockSystem = $(this).closest('tr').find('.stock-system');

            // Show/hide stock system value
            stockSystem.toggle(isChecked);

            // Update in database
            $.ajax({
                url: '{{route("stock-opname.update-checkbox", "")}}/' + detailId,
                type: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    is_checked: isChecked
                },
                success: function(response) {
                    if (response.success) {
                        // Optional: Show success message
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan: ' + xhr.responseJSON.error);
                }
            });
        });
    });
</script>
@endsection