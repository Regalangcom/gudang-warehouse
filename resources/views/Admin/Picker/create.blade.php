@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Buat Request Stock Opname Baru</h3>
                    <div class="card-tools">
                        <a href="{{ route('picker.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('picker.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Request stock opname akan dibuat untuk semua barang aktif.
                            Setelah request dibuat, super admin akan menyetujui dan mengirimkan data stock sistem.
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan (Opsional)</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" rows="3"
                                placeholder="Tambahkan keterangan jika diperlukan">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Buat Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Add any additional JavaScript if needed
    });
</script>
@endpush