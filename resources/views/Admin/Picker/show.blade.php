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
            <li class="breadcrumb-item"><a href="{{ route('picker.index') }}">Stock Opname</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
          </ol>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-sm-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title mb-0">Detail Stock Opname</h3>
            </div>
            <div class="card-body">
              <div class="row mb-4">
                <div class="col-md-6">
                  <h5>Informasi Request</h5>
                  <table class="table table-bordered">
                    <tr>
                      <th width="150">Kode Request</th>
                      <td>{{ $stockOpname->request_code }}</td>
                    </tr>
                    <tr>
                      <th>Tanggal Request</th>
                      <td>{{ \Carbon\Carbon::parse($stockOpname->request_date)->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                      <th>Status</th>
                      <td>
                        @if($stockOpname->status_request == 'pending')
                        <span class="badge bg-warning">Pending</span>
                        @elseif($stockOpname->status_request == 'approve')
                        <span class="badge bg-success">Disetujui</span>
                        @elseif($stockOpname->status_request == 'reject')
                        <span class="badge bg-danger">Ditolak</span>
                        @else
                        <span class="badge bg-secondary">Tidak Diketahui</span>
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Keterangan</th>
                      <td>{{ $stockOpname->keterangan ?: '-' }}</td>
                    </tr>
                  </table>
                </div>
              </div>


              <!-- PAGE HEADER -->
              <div class="page-header">
                <h1 class="page-title">{{ $title }}</h1>
                <div>
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('picker.index') }}">Stock Opname</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                  </ol>
                </div>
              </div>
              <!-- /PAGE HEADER -->

              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h3 class="card-title mb-0">Detail Stock Opname</h3>
                      @if($stockOpname->status_request == 'approve')
                      <!-- SIMPAN SEMUA BUTTON -->
                      <button id="btnSaveAll" class="btn btn-success">
                        <i class="fe fe-save"></i> Simpan Semua
                      </button>
                      @endif
                    </div>

                    <div class="card-body">
                      <!-- REQUEST INFO -->
                      <h5>Informasi Request</h5>
                      <table class="table table-bordered mb-4">
                        <tr>
                          <th width="150">Kode Request</th>
                          <td>{{ $stockOpname->request_code }}</td>
                        </tr>
                        <tr>
                          <th>Tanggal Request</th>
                          <td>{{ \Carbon\Carbon::parse($stockOpname->request_date)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                          <th>Status</th>
                          <td>
                            @if($stockOpname->status_request=='pending')
                            <span class="badge bg-warning">Pending</span>
                            @elseif($stockOpname->status_request=='approve')
                            <span class="badge bg-success">Disetujui</span>
                            @else
                            <span class="badge bg-danger">Ditolak</span>
                            @endif
                          </td>
                        </tr>
                        <tr>
                          <th>Keterangan</th>
                          <td>{{ $stockOpname->keterangan ?: '-' }}</td>
                        </tr>
                      </table>

                      @if($stockOpname->status_request == 'approve')
                      <!-- STOCK DETAIL TABLE -->
                      <div class="table-responsive">
                        <table id="stock_opnamedetail" class="table table-bordered text-nowrap">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th>Kode Barang</th>
                              <th>Nama Barang</th>
                              <th>Stock Before</th>
                              <th>Total Stock (Sistem)</th>
                              <th>Stock Aktual</th>
                              <th>Selisih</th>
                              <th>Stock After</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($details as $i => $detail)
                            @php
                            $total = $totalStocks[$detail->stock_detail_id] ?? 0;
                            $in = old("stock_in.{$detail->stock_detail_id}", $detail->stock_in);
                            $diff = $in !== null ? $in - $total : null;
                            $final = $diff !== null ? $total + $diff : null;
                            @endphp
                            <tr data-id="{{ $detail->stock_detail_id }}">
                              <td>{{ $i+1 }}</td>
                              <td>{{ $detail->barang->barang_kode }}</td>
                              <td>{{ $detail->barang->barang_nama }}</td>
                              <td>
                                <span class="stock-system">{{ number_format($total, 2) }}</span>
                                <div class="form-check form-switch d-inline ms-2">
                                  <input class="form-check-input toggle-stock" type="checkbox">
                                </div>
                              </td>
                              <td>
                                <span class="{{ $total>0?'text-success':($total<0?'text-danger':'') }}">
                                  {{ number_format($total, 2) }}
                                </span>
                              </td>
                              <td>
                                <input type="number" class="form-control stock-in" data-id="{{ $detail->stock_detail_id }}" min="0" step="1" value="{{ $in }}">
                              </td>
                              <td class="difference">
                                @if($diff !== null)
                                <span class="{{ $diff>0?'text-success':($diff<0?'text-danger':'') }}">
                                  {{ $diff>0?'+':'' }}{{ $diff }}
                                </span>
                                @else
                                -
                                @endif
                              </td>
                              <td class="final-stock">
                                {{ $final !== null ? $final : '-' }}
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                      @elseif($stockOpname->status_request == 'reject')
                      <div class="alert alert-danger">
                        Request stock opname ditolak.
                        @if($stockOpname->keterangan)
                        <br>Keterangan: {{ $stockOpname->keterangan }}
                        @endif
                      </div>
                      @else
                      <div class="alert alert-info">
                        Request stock opname masih menunggu persetujuan.
                      </div>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

        @endsection

        @section('scripts')
        <?php if ($stockOpname->status_request == 'approve') { ?>
          <script>
            $(document).ready(function() {
                  var table = $('#stock_opnamedetail').DataTable();
                  $('.toggle-stock').on('change', function() {
                    let detailId = $(this).data('id');
                    if ($(this).is(':checked')) {
                      $(`#stock-system-${detailId}`).show();
                    } else {
                      $(`#stock-system-${detailId}`).hide();
                    }
                  });
                  $('#stock_opnamedetail tbody').on('submit', '#formUpdateStock', function(e) {
                    e.preventDefault();
                    let tr = $(this).closest('tr');
                    let rowData = table.row(tr).data();
                    let detailId = tr.find('td:eq(1)').text().trim();

                    if (isUUID(detailId)) {
                      let stockIn = $(`#stock-in-${detailId}`).val();

                      if (!stockIn) {
                        alert('Silakan masukkan stok aktual yang valid.');
                        return;
                      }

                      $(`#stock-system-${detailId}`).show();
                      saveStock(detailId, stockIn);
                    }
                  });

                  function saveStock(detailId, stockIn) {
                    console.log("Saving detailId:", detailId);
                    console.log("Stock in:", stockIn);

                    $.ajax({
                          url: "/admin/picker/opname/update-stock/" + detailId,
                          type: "POST",
                          data: {
                            _token: "{{ csrf_token() }}",
                            stock_in: stockIn
                          },
                          success: function(response) {
                            if (response.success) {
                              const systemStock = response.stockSystem;
                              const actualStock = response.stockIn;
                              const difference = response.selisih;
                              // Calculate final stock after adjustment
                              const finalStock = systemStock + difference;


                              $('#stock_opnamedetail tbody tr').each(function() {
                                  var $tr = $(this),
                                    id = $tr.data('id'),
                                    stockIn = $tr.find('.stock-in').val();

                                  // skip if empty
                                  if (!stockIn) return;

                                  requests.push(
                                    $.post("/admin/picker/opname/update-stock/" + id, {
                                      _token: "{{ csrf_token() }}",
                                      stock_in: stockIn
                                    }).done(function(res) {
                                        if (res.success) {
                                          var sys = res.stockSystem,
                                            diff = res.selisih,
                                            final = sys + diff,
                                            diffHtml = diff > 0 ?
                                            '<span class="text-success">+' + diff + '</span>' :
                                            (diff < 0 ?
                                              '<span class="text-danger">' + diff + '</span>' :
                                              '<span>' + diff + '</span>');

                                          // Update the difference and final stock directly in the table
                                          $tr.find('.difference').html(diffHtml);
                                          $tr.find('.final-stock').text(final);
                                        }

                                        // Update kolom selisih
                                        $(`#difference-${detailId}`).html(differenceHtml);
                                        // Update kolom stock akhir
                                        $(`#final-stock-${detailId}`).html(finalStock);
                                        $('#stock_opnamedetail').DataTable().ajax.reload();
                                        alert('Stok berhasil disimpan.');
                                      } else {
                                        alert('Gagal menyimpan stok. Silakan coba lagi.');
                                      }
                                    },
                                    error: function() {
                                      alert('Terjadi kesalahan saat menyimpan stok.');
                                    }
                                  });
                              }

                              function isUUID(uuid) {
                                return /^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i.test(uuid);
                              }
                            });
          </script>
        <?php } ?>
        @endsection