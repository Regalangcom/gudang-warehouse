@extends('Master.Layouts.app')
@section('title', 'Tambah Penyesuaian')
@section('content')
<div class="page-header">
    <h1 class="page-title">Tambah Penyesuaian</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/admin')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{url('/admin/penyesuaian')}}">Data Penyesuaian</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Penyesuaian</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Request Penyesuaian</h3>
            </div>
            <div class="card-body">
                <form action="{{route('penyesuaian.store')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Keterangan</label>
                        <p>Dengan melakukan request penyesuaian, Anda akan mendapatkan daftar barang beserta stok tercatat untuk dilakukan pengecekan fisik.</p>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                        <a href="{{route('penyesuaian.index')}}" class="btn btn-default">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection