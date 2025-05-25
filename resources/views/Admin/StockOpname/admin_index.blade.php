@extends('Master.Layouts.app')

@section('title', $title)

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
                                <th class="wd-15p border-bottom-0">Kode Request</th>
                                <th class="wd-15p border-bottom-0">Tanggal</th>
                                <th class="wd-15p border-bottom-0">Status</th>
                                <th class="wd-15p border-bottom-0">Dibuat Oleh</th>
                                <th class="wd-15p border-bottom-0">Disetujui Oleh</th>
                                <th class="wd-15p border-bottom-0">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                            <tr>
                                <td>{{$request->request_code}}</td>
                                <td>{{$request->request_date->format('d/m/Y')}}</td>
                                <td>
                                    @if($request->status_request == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @elseif($request->status_request == 'approve')
                                    <span class="badge bg-success">Disetujui</span>
                                    @else
                                    <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>{{$request->user->user_nama}}</td>
                                <td>{{$request->approver ? $request->approver->user_nama : '-'}}</td>
                                <td>
                                    @if($request->status_request == 'pending')
                                    <button type="button" class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#approveModal{{$request->stock_id}}">
                                        <i class="fe fe-check"></i> Proses
                                    </button>
                                    @else
                                    <a href="{{route('stock-opname.show', $request->stock_id)}}"
                                        class="btn btn-info btn-sm">
                                        <i class="fe fe-eye"></i> Detail
                                    </a>
                                    @endif
                                </td>
                            </tr>

                            <!-- Modal Approve/Reject -->
                            <div class="modal fade" id="approveModal{{$request->stock_id}}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Proses Request Stock Opname</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{route('admin.stock-opname.update-status', $request->stock_id)}}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-control" required>
                                                        <option value="approve">Setujui</option>
                                                        <option value="reject">Tolak</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Keterangan</label>
                                                    <textarea name="keterangan" class="form-control" rows="3"
                                                        placeholder="Masukkan keterangan (opsional)"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#basic-datatable').DataTable();
    });
</script>
@endsection