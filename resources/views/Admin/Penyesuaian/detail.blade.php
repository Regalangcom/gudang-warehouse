@extends('Master.Layouts.app')
@section('title', 'Detail Penyesuaian')
@section('content')
<div class="page-header">
    <h1 class="page-title">Detail Penyesuaian</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/admin')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{url('/admin/penyesuaian')}}">Data Penyesuaian</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Penyesuaian</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Penyesuaian</h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Kode Penyesuaian</label>
                            <input type="text" class="form-control" value="{{ $penyesuaian->penyesuaian_kode }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Tanggal</label>
                            <input type="text" class="form-control" value="{{ date('d F Y', strtotime($penyesuaian->penyesuaian_tanggal)) }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <input type="text" class="form-control" value="{{ $penyesuaian->penyesuaian_status == 0 ? 'Pending' : ($penyesuaian->penyesuaian_status == 1 ? 'Approved' : 'Rejected') }}" readonly>
                        </div>
                    </div>
                </div>

                @if(count($detail) > 0)
                <hr>
                <div class="form-group mb-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="showStokTercatat">
                        <label class="custom-control-label" for="showStokTercatat">Tampilkan Stok Tercatat</label>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap border-bottom" id="table-detail">
                        <thead>
                            <tr>
                                <th class="border-bottom-0">No</th>
                                <th class="border-bottom-0">Kode Barang</th>
                                <th class="border-bottom-0">Nama Barang</th>
                                <th class="border-bottom-0 stok-tercatat" style="display:none;">Stok Tercatat</th>
                                <th class="border-bottom-0">Stok Fisik</th>
                                <th class="border-bottom-0 stok-tercatat" style="display:none;">Selisih</th>
                                @if($penyesuaian->penyesuaian_status == 1 && Session::get('user')->role_id == 2)
                                <th class="border-bottom-0">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($detail as $d)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $d->barang_kode }}</td>
                                <td>{{ $d->barang_nama }}</td>
                                <td class="stok-tercatat" style="display:none;">{{ $d->stok_tercatat }}</td>
                                <td>{{ $d->stok_fisik ?? '-' }}</td>
                                <td class="stok-tercatat" style="display:none;">{{ $d->stok_fisik ? $d->stok_fisik - $d->stok_tercatat : '-' }}</td>
                                @if($penyesuaian->penyesuaian_status == 1 && Session::get('user')->role_id == 2)
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="updateStok('{{ $d->penyesuaian_detail_id }}')">Update Stok</button>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info">
                    Belum ada data detail penyesuaian. Silahkan approve terlebih dahulu untuk melihat detail.
                </div>
                @endif

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ url('/admin/penyesuaian') }}" class="btn btn-default">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update Stok -->
<div class="modal fade" id="modalUpdateStok">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Update Stok Fisik</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="formUpdateStok">
                    @csrf
                    <input type="hidden" id="penyesuaian_detail_id" name="id">
                    <div class="form-group">
                        <label class="form-label">Stok Fisik</label>
                        <input type="number" class="form-control" id="stok_fisik" name="stok_fisik" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Toggle stok tercatat visibility
        $('#showStokTercatat').change(function() {
            if (this.checked) {
                $('.stok-tercatat').show();
            } else {
                $('.stok-tercatat').hide();
            }
        });

        // DataTable
        $('#table-detail').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });

        // Form update stok
        $('#formUpdateStok').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('penyesuaian.updateStok') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#modalUpdateStok').modal('hide');
                        swal({
                            title: "Berhasil!",
                            text: "Stok fisik berhasil diupdate",
                            type: "success"
                        }).then(function() {
                            location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    swal({
                        title: "Error!",
                        text: "Terjadi kesalahan saat mengupdate stok",
                        type: "error"
                    });
                }
            });
        });
    });

    function updateStok(id) {
        $('#penyesuaian_detail_id').val(id);
        $('#modalUpdateStok').modal('show');
    }
</script>
@endsection