@extends('Master.Layouts.app')
@section('title', $title)
@section('content')

<div class="main-content app-content mt-0 mx-auto">
  <div class="side-app">
    <div class="main-container container-fluid">

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
                      <th>Stock Awal</th>
                      <th>Total Stock<br>(Sistem)</th>
                      <th>Stock Aktual</th>
                      <th>Selisih</th>
                      <th>Stock Akhir</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($details as $i => $detail)
                      @php
                        $total = $totalStocks[$detail->stock_detail_id] ?? 0;
                        $in    = old("stock_in.{$detail->stock_detail_id}", $detail->stock_in);
                        $diff  = $in !== null ? $in - $total : null;
                        $final = $diff !== null ? $total + $diff : null;
                      @endphp
                      <tr data-id="{{ $detail->stock_detail_id }}">
                        <td>{{ $i+1 }}</td>
                        <td>{{ $detail->barang->barang_kode }}</td>
                        <td>{{ $detail->barang->barang_nama }}</td>
                        <td>
                          <span class="stock-system">{{ number_format($detail->stock_system,2) }}</span>
                          <div class="form-check form-switch d-inline ms-2">
                            <input class="form-check-input toggle-stock" type="checkbox">
                          </div>
                        </td>
                        <td>
                          <span class="{{ $total>0?'text-success':($total<0?'text-danger':'') }}">
                            {{ number_format($total,2) }}
                          </span>
                        </td>
                        <td>
                          <input type="number"
                                 class="form-control stock-in"
                                 data-id="{{ $detail->stock_detail_id }}"
                                 min="0"
                                 step="1"
                                 value="{{ $in }}">
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
              <!-- /TABLE -->

              @elseif($stockOpname->status_request == 'reject')
                <div class="alert alert-danger">
                  Request ditolak.<br>
                  @if($stockOpname->keterangan)
                    Keterangan: {{ $stockOpname->keterangan }}
                  @endif
                </div>
              @else
                <div class="alert alert-info">
                  Request masih menunggu persetujuan.
                </div>
              @endif

            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!-- main-content -->
@endsection

@section('scripts')
<script>
  $(function(){
    // INITIALIZE DATATABLE
    var table = $('#stock_opnamedetail').DataTable({
      scrollX: true,
      lengthMenu: [5,10,25,50,100],
      pageLength: 10
    });

    // TOGGLE STOCK-SYSTEM SPAN
    $('.stock-system').hide();
    $('#stock_opnamedetail').on('change', '.toggle-stock', function(){
      $(this).closest('td').find('.stock-system').toggle(this.checked);
    });

    // SAVE ALL BUTTON
    $('#btnSaveAll').on('click', function(){
      var $btn = $(this).prop('disabled', true).text('Menyimpan...');
      var requests = [];

      $('#stock_opnamedetail tbody tr').each(function(){
        var $tr    = $(this),
            id     = $tr.data('id'),
            stockIn= $tr.find('.stock-in').val();

        // skip if empty
        if (!stockIn) return;

        requests.push(
          $.post("/admin/picker/opname/update-stock/"+id, {
            _token: "{{ csrf_token() }}",
            stock_in: stockIn
          }).done(function(res){
            if (res.success) {
              var sys   = res.stockSystem,
                  diff  = res.selisih,
                  final = sys + diff,
                  diffHtml = diff>0
                    ? '<span class="text-success">+'+diff+'</span>'
                    : (diff<0
                      ? '<span class="text-danger">'+diff+'</span>'
                      : '<span>'+diff+'</span>');

              $tr.find('.difference').html(diffHtml);
              $tr.find('.final-stock').text(final);
            }
          })
        );
      });

      // when all AJAX calls finish
      $.when.apply($, requests).always(function(){
        alert('Semua stok berhasil disimpan.');
        $btn.prop('disabled', false).html('<i class="fe fe-save"></i> Simpan Semua');
        table.draw(false);
      });
    });
  });
</script>
@endsection
