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
                <h3 class="card-title">Form Request Stock Opname</h3>
            </div>
            <div class="card-body">
                <form action="{{route('stock-opname.store')}}" method="POST">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3"
                                    placeholder="Masukkan keterangan request (opsional)"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fe fe-save"></i> Buat Request
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection