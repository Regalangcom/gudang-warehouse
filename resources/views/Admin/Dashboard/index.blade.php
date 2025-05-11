@extends('Master.Layouts.app', ['title' => $title])

@section('content')
<!-- PAGE-HEADER -->
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item text-gray">Admin</li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->

<!-- ROW 1 OPEN -->
<div class="row">
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card bg-primary img-card box-primary-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font">{{$jenis}}</h2>
                        <p class="text-white mb-0">Jenis Barang </p>
                    </div>
                    <div class="ms-auto"> <i class="fe fe-package text-white fs-40 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>
    <!-- COL END -->
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card bg-secondary img-card box-secondary-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font">{{$satuan}}</h2>
                        <p class="text-white mb-0">Satuan Barang</p>
                    </div>
                    <div class="ms-auto"> <i class="fe fe-package text-white fs-40 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>
    <!-- COL END -->
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card  bg-success img-card box-success-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font">{{$merk}}</h2>
                        <p class="text-white mb-0">Merk Barang</p>
                    </div>
                    <div class="ms-auto"> <i class="fe fe-package text-white fs-40 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>
    <!-- COL END -->
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card bg-info img-card box-info-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font">{{$barang}}</h2>
                        <p class="text-white mb-0">Barang</p>
                    </div>
                    <div class="ms-auto"> <i class="fe fe-package text-white fs-40 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>
    <!-- COL END -->
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card bg-success img-card box-success-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font">{{$bm}}</h2>
                        <p class="text-white mb-0">Barang Masuk</p>
                    </div>
                    <div class="ms-auto"> <i class="fe fe-repeat text-white fs-40 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>
    <!-- COL END -->
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card bg-danger img-card box-danger-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font">{{$bk}}</h2>
                        <p class="text-white mb-0">Barang Keluar</p>
                    </div>
                    <div class="ms-auto"> <i class="fe fe-repeat text-white fs-40 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>
    <!-- COL END -->
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card bg-purple img-card box-purple-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font">{{$customer}}</h2>
                        <p class="text-white mb-0">Customer</p>
                    </div>
                    <div class="ms-auto"> <i class="fe fe-user text-white fs-40 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>
    <!-- COL END -->
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card bg-warning img-card box-warning-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font">{{$user}}</h2>
                        <p class="text-white mb-0">User</p>
                    </div>
                    <div class="ms-auto"> <i class="fe fe-user text-white fs-40 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card bg-orange img-card box-warning-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font">{{$supplier}}</h2>
                        <p class="text-white mb-0">Supplier</p>
                    </div>
                    <div class="ms-auto"> <i class="fe fe-user text-white fs-40 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card bg-black img-card box-warning-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h3 class="mb-0 number-font" style="font-size:16px">Date:</h3>
                        <p id="tanggalwaktu" class="text-warning"></p>
                        <script>
                            var tw = new Date();
                            if (tw.getTimezoneOffset() == 0)(a = tw.getTime() + (7 * 60 * 60 * 1000))
                            else(a = tw.getTime());
                            tw.setTime(a);
                            var tahun = tw.getFullYear();
                            var hari = tw.getDay();
                            var bulan = tw.getMonth();
                            var tanggal = tw.getDate();
                            var hariarray = new Array("Minggu,", "Senin,", "Selasa,", "Rabu,", "Kamis,", "Jum'at,", "Sabtu,");
                            var bulanarray = new Array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember");
                            document.getElementById("tanggalwaktu").innerHTML = hariarray[hari] + " " + tanggal + " " + bulanarray[bulan] + " " + tahun;
                        </script>
                    </div>
                    <div class="ms-auto"> <i class="fe fe-calendar text-white fs-40 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card bg-black img-card box-warning-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h3 class="mb-0 number-font" style="font-size:26px">O'clock:</h3>
                        <a href="https://time.is/Bandung" id="time_is_link" rel="nofollow" style="font-size:15px" class="text-white">Banten,</a>
                        <span id="Bandung_z41c" style="font-size:15px" class="text-warning"></span>
                        <script src="//widget.time.is/t.js"></script>
                        <script>
                            time_is_widget.init({
                                Bandung_z41c: {}
                            });
                        </script>
                    </div>
                    <div class="ms-auto"> <i class="fe fe-clock text-white fs-40 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>
    <!-- COL END -->
</div>
<!-- ROW 1 CLOSED -->


@endsection