{{-- resources/views/Admin/Barang/index.blade.php --}}
@extends('Master.Layouts.app', ['title' => $title])

@section('content')
{{-- ========== PAGE-HEADER ========== --}}
<div class="page-header">
  <h1 class="page-title">{{ $title }}</h1>
  <div>
    <ol class="breadcrumb">
      <li class="breadcrumb-item text-gray">Master Data</li>

      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
</div>

{{-- ========== TABEL DATA ========== --}}
<div class="row row-sm">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header justify-content-between">
        <h3 class="card-title">Data</h3>

        @if($hakTambah)
        <a class="btn btn-primary-light" data-bs-toggle="modal"
          href="#modaldemo8" data-bs-effect="effect-super-scaled"
          onclick="resetRows()">Tambah Data <i class="fe fe-plus"></i></a>
        @endif
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table id="table-1" class="table table-bordered text-nowrap border-bottom w-100">
            <thead>
              <tr>
                <th width="1%">No</th>
                <th>Gambar</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Satuan</th>
                <th>Merk</th>
                <th>Stok</th>
                <th>Harga</th>
                <th width="1%">Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ============================================================ --}}
{{-- =======================   M O D A L S   ===================== --}}
{{-- ============================================================ --}}

{{-- ---------- TAMBAH (multi-insert) ---------- --}}
<div class="modal fade" id="modaldemo8" data-bs-backdrop="static" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Tambah Barang</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"
          onclick="resetRows()"></button>
      </div>
      <div class="modal-body">
        <div id="barang-rows"></div>
        <button type="button" class="btn btn-sm btn-secondary mt-2"
          onclick="addRow()">+ Tambah Baris</button>
      </div>
      <div class="modal-footer">
        <button id="btnLoader" class="btn btn-primary d-none" disabled>
          <span class="spinner-border spinner-border-sm me-1"></span> Loading…
        </button>
        <button id="btnSimpan" class="btn btn-primary" onclick="checkForm()">
          Simpan <i class="fe fe-check"></i>
        </button>
        <button class="btn btn-light" data-bs-dismiss="modal"
          onclick="resetRows()">Batal <i class="fe fe-x"></i></button>
      </div>
    </div>
  </div>
</div>


{{-- ---- template baris (hidden) ---- --}}
<div id="template-row" class="d-none">
  <div class="barang-row row g-2 align-items-end mb-2">
    <div class="col-md-3">
      <label class="form-label">Kode <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="kode[]" readonly>
    </div>
    <div class="col-md-3">
      <label class="form-label">Nama <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="nama[]">
    </div>
    <div class="col-md-2">
      <label class="form-label">Jenis</label>
      <select class="form-control" name="jenisbarang[]">
        <option value="">-- Pilih --</option>
        @foreach($jenisbarang as $j)
        <option value="{{ $j->jenisbarang_id }}">{{ $j->jenisbarang_nama }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label">Satuan</label>

      <select class="form-control" name="satuan[]">
        <option value="">-- Pilih --</option>
        @foreach($satuan as $s)
        <option value="{{ $s->satuan_id }}">{{ $s->satuan_nama }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label">Merk</label>

      <select class="form-control" name="merk[]">
        <option value="">-- Pilih --</option>
        @foreach($merk as $m)
        <option value="{{ $m->merk_id }}">{{ $m->merk_nama }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3 mt-1">
      <label class="form-label">Harga <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="harga[]"
        oninput="this.value=this.value.replace(/[^0-9.]/g,'')">
    </div>
    <div class="col-md-4 mt-1">
      <label class="form-label">Foto</label>
      <div class="d-flex align-items-center">
        <img src="{{ url('/assets/default/barang/image.png') }}" width="60"
          class="outputImg me-2">
        <input type="file" class="form-control photo-input"
          name="photo[]" accept=".png,.jpg,.jpeg,.svg"
          onchange="VerifyFileNameAndFileSize(this)">
      </div>
    </div>
    <div class="col-md-1 mt-1">
      <button type="button" class="btn btn-sm btn-danger"
        onclick="$(this).closest('.barang-row').remove()">&times;</button>
    </div>
  </div>
</div>

{{-- ---------- EDIT (id Umodaldemo8 cocok dg kolom action) ---------- --}}
<div class="modal fade" id="Umodaldemo8" data-bs-backdrop="static" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Ubah Barang</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="formEdit" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" name="idbarangU">
          <div class="mb-2">
            <label class="form-label">Kode</label>
            <input type="text" name="kodeU" class="form-control" readonly>
          </div>
          <div class="mb-2">
            <label class="form-label">Nama</label>
            <input type="text" name="namaU" class="form-control">
          </div>
          <div class="row g-2">
            <div class="col-md-4">
              <label class="form-label">Jenis</label>
              <select name="jenisbarangU" class="form-control">
                <option value="">-- Pilih --</option>
                @foreach($jenisbarang as $j)

                <option value="{{ $j->jenisbarang_id }}">{{ $j->jenisbarang_nama }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Satuan</label>
              <select name="satuanU" class="form-control">
                <option value="">-- Pilih --</option>
                @foreach($satuan as $s)

                <option value="{{ $s->satuan_id }}">{{ $s->satuan_nama }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Merk</label>
              <select name="merkU" class="form-control">
                <option value="">-- Pilih --</option>
                @foreach($merk as $m)

                <option value="{{ $m->merk_id }}">{{ $m->merk_nama }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row g-2 mt-1">
            <div class="col-md-6">
              <label class="form-label">Stok Awal</label>
              <input type="number" name="stokU" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Harga</label>
              <input type="text" name="hargaU" class="form-control"

                oninput="this.value=this.value.replace(/[^0-9.]/g,'')">
            </div>
          </div>
          <div class="mt-2">
            <label class="form-label">Foto</label>
            <div class="d-flex align-items-center">
              <img id="outputImgU" width="70" class="me-2"

                src="{{ url('/assets/default/barang/image.png') }}">
              <input type="file" name="foto" class="form-control"
                accept=".png,.jpg,.jpeg,.svg">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button id="btnUpdateLoad" class="btn btn-primary d-none" disabled>
            <span class="spinner-border spinner-border-sm me-1"></span> Updating…
          </button>
          <button id="btnUpdate" class="btn btn-primary">Perbarui</button>
          <button class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ---------- HAPUS ---------- --}}
<div class="modal fade" id="Hmodaldemo8" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h6 class="modal-title">Konfirmasi</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Yakin hapus <span id="vbarang"></span> ?
        <input type="hidden" name="idbarang">
      </div>
      <div class="modal-footer">
        <button id="btnConfirmDelete" class="btn btn-danger">Hapus</button>
        <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>

{{-- ---------- GAMBAR ---------- --}}
<div class="modal fade" id="Gmodaldemo8" tabindex="-1">

  @include('Admin.Barang.edit', ['jenisbarang'=>$jenisbarang,'satuan'=>$satuan,'merk'=>$merk])
  @include('Admin.Barang.edit', ['jenisbarang'=>$jenisbarang,'satuan'=>$satuan,'merk'=>$merk])
  @include('Admin.Barang.hapus')
  @include('Admin.Barang.gambar')
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img id="outputImgG" class="img-fluid"
          src="{{ url('/assets/default/barang/image.png') }}">
      </div>
    </div>
  </div>
</div>
@endsection

{{-- =========================  S C R I P T  ========================= --}}
@section('scripts')
<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  /* ---------------- DATATABLE ---------------- */
  let table = $('#table-1').DataTable({
    processing: true,
    serverSide: true,
    stateSave: true,
    scrollX: true,
    ajax: "{{ route('barang.getbarang') }}", // route TANPA parameter
    columns: [{
        data: 'DT_RowIndex',
        name: 'DT_RowIndex',
        orderable: false,
        searchable: false
      },
      {
        data: 'img',
        name: 'barang_gambar',
        orderable: false,
        searchable: false
      },
      {
        data: 'barang_kode',
        name: 'barang_kode'
      },
      {
        data: 'barang_nama',
        name: 'barang_nama'
      },
      {
        data: 'jenisbarang',
        name: 'jenisbarang_nama'
      },
      {
        data: 'satuan',
        name: 'satuan_nama'
      },
      {
        data: 'merk',
        name: 'merk_nama'
      },
      {
        data: 'barang_stok',
        name: 'barang_stok'
      },
      {
        data: 'currency',
        name: 'barang_harga'
      },
      {
        data: 'action',
        name: 'action',
        orderable: false,
        searchable: false
      },
    ]
  });

  /* ================================================================= */
  /* =============   TAMBAH BARANG  (MULTI-ROW)   ==================== */
  /* ================================================================= */
  function resetRows() {
    $('#barang-rows').empty();
    addRow();
    setLoading(false);
  }

  function addRow() {
    $('#barang-rows').append($('#template-row').html());
    $('#barang-rows .barang-row').last()
      .find("[name='kode[]']")
      .val('BRG-' + Date.now() + Math.floor(Math.random() * 90));
  }

  function setLoading(on) {
    $('#btnLoader').toggleClass('d-none', !on);
    $('#btnSimpan').toggleClass('d-none', on);
  }

  /* ------------ validasi & submit ------------ */
  function checkForm() {
    setLoading(true);
    let ok = true;
    $('#barang-rows input,#barang-rows select').removeClass('is-invalid');

    $('#barang-rows .barang-row').each(function(_, row) {
      const $r = $(row);
      const kode = $r.find("[name='kode[]']").val().trim();
      const nama = $r.find("[name='nama[]']").val().trim();
      const harga = $r.find("[name='harga[]']").val().trim();
      if (!(kode && nama && harga)) {
        ['kode', 'nama', 'harga'].forEach(n => {
          const el = $r.find(`[name='${n}[]']`);
          if (!el.val().trim()) el.addClass('is-invalid');
        });
        ok = false;
      }
    });

    if (!ok) {
      setLoading(false);
      swal('Isian wajib masih kosong', '', 'warning');
      return;
    }
    submitForm();
  }

  function submitForm() {
    const fd = new FormData();
    $('#barang-rows .barang-row').each(function(_, row) {
      const $r = $(row);
      const kode = $r.find("[name='kode[]']").val().trim();
      const nama = $r.find("[name='nama[]']").val().trim();
      const harga = $r.find("[name='harga[]']").val().trim();
      if (!(kode && nama && harga)) return; // abaikan baris invalid

      fd.append('kode[]', kode);
      fd.append('nama[]', nama);
      fd.append('jenisbarang[]', $r.find("[name='jenisbarang[]']").val());
      fd.append('satuan[]', $r.find("[name='satuan[]']").val());
      fd.append('merk[]', $r.find("[name='merk[]']").val());
      fd.append('harga[]', harga);

      const fi = $r.find(".photo-input")[0];
      fd.append('photo[]', fi && fi.files[0] ? fi.files[0] : '');
    });

    $.post({
        url: "{{ route('barang.store') }}",
        data: fd,
        processData: false,
        contentType: false
      })
      .done(() => {
        $('#modaldemo8').modal('hide');
        swal('Berhasil ditambah', '', 'success');
        table.ajax.reload(null, false);
        resetRows();
      })
      .fail(x => {
        swal('Error', x.responseText, 'error');
        setLoading(false);
      });
  }

  /* ------------ preview foto tiap baris ------------ */
  function VerifyFileNameAndFileSize(i) {
    const f = i.files[0];
    if (!f) return;
    const ext = f.name.split('.').pop().toLowerCase();
    if (!['png', 'jpg', 'jpeg', 'svg'].includes(ext)) {
      swal('Format salah', '', 'warning');
      i.value = '';
      return;
    }
    if (f.size > 3 * 1024 * 1024) {
      swal('Max 3 MB', '', 'warning');
      i.value = '';
      return;
    }
    $(i).closest('.barang-row').find('.outputImg').attr('src', URL.createObjectURL(f));
  }

  /* ================================================================= */
  /* ========== FUNGSI UPDATE / HAPUS / PREVIEW GAMBAR =============== */
  /* ================================================================= */
  function update(d) {
    $("input[name='idbarangU']").val(d.barang_id);
    $("input[name='kodeU']").val(d.barang_kode);
    $("input[name='namaU']").val(d.barang_nama.replace(/_/g, ' '));
    $("select[name='jenisbarangU']").val(d.jenisbarang_id);
    $("select[name='satuanU']").val(d.satuan_id);
    $("select[name='merkU']").val(d.merk_id);
    $("input[name='stokU']").val(d.barang_stok);
    $("input[name='hargaU']").val(d.barang_harga);
    const img = d.barang_gambar !== 'image.png' ?
      "{{ asset('storage/barang') }}/" + d.barang_gambar :
      "{{ url('/assets/default/barang/image.png') }}";
    $('#outputImgU').attr('src', img);
  }

  function hapus(d) {
    $("input[name='idbarang']").val(d.barang_id);
    $("#vbarang").html("barang <b>" + d.barang_nama.replace(/_/g, ' ') + "</b>");
  }

  function gambar(d) {
    const img = d.barang_gambar !== 'image.png' ?
      "{{ asset('storage/barang') }}/" + d.barang_gambar :
      "{{ url('/assets/default/barang/image.png') }}";
    $('#outputImgG').attr('src', img);
  }

  /* ---------------- SUBMIT EDIT ---------------- */
  $('#formEdit').on('submit', function(e) {
    e.preventDefault();
    $('#btnUpdateLoad').removeClass('d-none');
    $('#btnUpdate').addClass('d-none');

    const id = $("input[name='idbarangU']").val();
    const fd = new FormData(this);
    fd.append('kode', fd.get('kodeU'));
    fd.append('nama', fd.get('namaU'));
    fd.append('jenisbarang', fd.get('jenisbarangU'));
    fd.append('satuan', fd.get('satuanU'));
    fd.append('merk', fd.get('merkU'));
    fd.append('harga', fd.get('hargaU'));
    fd.append('stok', fd.get('stokU'));

    $.post({
        url: "{{ url('/admin/barang/proses_ubah') }}/" + id,
        data: fd,
        processData: false,
        contentType: false
      })
      .done(() => {
        $('#Umodaldemo8').modal('hide');
        swal('Berhasil diperbarui', '', 'success');
        table.ajax.reload(null, false);
      })
      .fail(x => {
        swal('Error', x.responseText, 'error');
      })
      .always(() => {
        $('#btnUpdateLoad').addClass('d-none');
        $('#btnUpdate').removeClass('d-none');
      });
  });

  /* ---------------- SUBMIT DELETE ---------------- */
  $('#btnConfirmDelete').on('click', function() {
    const id = $("input[name='idbarang']").val();

    $.ajax({
      type: 'POST',
      url: "{{ url('admin/barang/proses_hapus') }}/" + id,
      data: {
        _token: "{{ csrf_token() }}"
      }, // wajib kalau pakai Laravel
      success: function(data) {
        swal({
            title: "Berhasil dihapus!",
            type: "success",
            confirmButtonText: "OK"
          },
          function() {
            // callback dipanggil setelah user menutup alert
            window.location.reload(); // refresh halaman
          }
        );
      },
      error: function() {
        window.location.reload(); // refresh halaman
      }
    });
  });
</script>
@endsection