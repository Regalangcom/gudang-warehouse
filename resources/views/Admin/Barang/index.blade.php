{{-- resources/views/Admin/Barang/index.blade.php --}}
@extends('Master.Layouts.app', ['title' => $title])

@section('content')
{{-- ========== PAGE-HEADER ========== --}}
<div class="page-header">
  <h1 class="page-title">{{ $title }}</h1>
  <div>
    <ol class="breadcrumb">
      <li class="breadcrumb-item text-gray">Master Data</li>

      <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
    </ol>
  </div>
</div>

<!-- DATA TABLE -->
<div class="row row-sm">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header justify-content-between">
        <h3 class="card-title">Data</h3>

        @if($hakTambah > 0)
        <div>
          <a class="btn btn-primary-light" data-bs-toggle="modal" href="#modaldemo8" onclick="resetRows()">
            Tambah Data <i class="fe fe-plus"></i>
          </a>
        </div>
        @endif
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="table-1" class="table table-bordered text-nowrap border-bottom">
            <thead>
              <tr>
                <th width="1%">No</th>
                <th>Gambar</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jenis</th>
                <th>Satuan</th>
                <th>Merk</th>
                <th>Stok Awal</th>
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


<!-- END DATA TABLE -->

{{-- MODAL TAMBAH MULTI --}}
<div class="modal fade" data-bs-backdrop="static" id="modaldemo8" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content modal-content-demo">
      <div class="modal-header">
        <h6 class="modal-title">Tambah Barang</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetRows()"></button>
      </div>
      <div class="modal-body">
        <div id="barang-rows"></div>
        <a href="javascript:void(0)" class="btn btn-sm btn-secondary mb-3" onclick="addRow()">
          + Tambah Baris
        </a>
      </div>
      <div class="modal-footer">
        <button id="btnLoader" class="btn btn-primary d-none" disabled>
          <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
          Loading...
        </button>
        <a href="javascript:void(0)" id="btnSimpan" class="btn btn-primary" onclick="checkForm()">
          Simpan <i class="fe fe-check"></i>
        </a>
        <a href="javascript:void(0)" class="btn btn-light" data-bs-dismiss="modal" onclick="resetRows()">
          Batal <i class="fe fe-x"></i>
        </a>
      </div>
    </div>
  </div>
</div>


{{-- TEMPLATE BARIS (hidden) --}}
<div id="template-row" class="d-none">
  <div class="barang-row row mb-3 align-items-end">
    <div class="col-md-3">
      <label class="form-label">Kode Barang <span class="text-danger">*</span></label>
      <input type="text" name="kode[]" readonly class="form-control">
    </div>
    <div class="col-md-3">
      <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
      <input type="text" name="nama[]" class="form-control">
    </div>
    <div class="col-md-2">
      <label class="form-label">Jenis</label>
      <select name="jenisbarang[]" class="form-control">
        <option value="">-- Pilih --</option>
        @foreach($jenisbarang as $jb)
        <option value="{{ $jb->jenisbarang_id }}">{{ $jb->jenisbarang_nama }}</option>

        <option value="{{ $jb->jenisbarang_id }}">{{ $jb->jenisbarang_nama }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label">Satuan</label>

      <select name="satuan[]" class="form-control">
        <option value="">-- Pilih --</option>
        @foreach($satuan as $s)
        <option value="{{ $s->satuan_id }}">{{ $s->satuan_nama }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label">Merk</label>

      <select name="merk[]" class="form-control">
        <option value="">-- Pilih --</option>
        @foreach($merk as $m)
        <option value="{{ $m->merk_id }}">{{ $m->merk_nama }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3 mt-2">
      <label class="form-label">Harga <span class="text-danger">*</span></label>
      <input type="text" name="harga[]" class="form-control"
        oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
    </div>
    <div class="col-md-3 mt-2">
      <label class="form-label">Foto</label>
      <div class="d-flex align-items-center">
        <img src="{{ url('/assets/default/barang/image.png') }}" class="outputImg me-2" width="60" alt="preview">
        <input type="file" name="photo[]" class="form-control photo-input"
          accept=".png,.jpeg,.jpg,.svg" onchange="VerifyFileNameAndFileSize(this)">
      </div>
    </div>
    <div class="col-md-1 mt-2">
      <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.barang-row').remove()">
        &times;
      </button>
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
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img id="outputImgG" class="img-fluid"
          src="{{ url('/assets/default/barang/image.png') }}">
      </div>
    </div>
  </div>
</div>
=======
@include('Admin.Barang.edit', ['jenisbarang'=>$jenisbarang,'satuan'=>$satuan,'merk'=>$merk])
=======
@include('Admin.Barang.edit', ['jenisbarang'=>$jenisbarang,'satuan'=>$satuan,'merk'=>$merk])
>>>>>>> eeeedc2 (up)
@include('Admin.Barang.hapus')
@include('Admin.Barang.gambar')
>>>>>>> 0b2f4c4 (up)
@endsection

{{-- =========================  S C R I P T  ========================= --}}
@section('scripts')
<script>
  // Setup AJAX CSRF
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // 1) Inisialisasi DataTable
  var table;
  $(document).ready(function() {
    table = $('#table-1').DataTable({
      processing: true,
      serverSide: true,
      scrollX: true,
      stateSave: true,
      ajax: "{{ route('barang.getbarang') }}",
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
        }
      ]
    });

    // siapkan 1 baris kosong di modal
    resetRows();
  });

  // 2) Tambah satu baris input
  function addRow() {
    let tpl = $('#template-row').html();
    $('#barang-rows').append(tpl);
    generateIDRow($('#barang-rows .barang-row').last());
    generateIDRow($row);
    dd($row)
    console.log("Kode setelah generate:", $row.find("input[name='kode[]']").val());
  }

  // 3) Reset semua baris jadi 1
  function resetRows() {
    $('#barang-rows').html('');
    addRow();
    setLoading(false);
  }

  // 4) Generate kode barang unik per baris
  // function generateIDRow($row) {
  //   let kode = 'BRG-' + Date.now();
  //   $row.find("input[name='kode[]']").val(kode);
  // }

  function generateIDRow($row) {
    if (!$row.length) return;

    let kode = 'BRG-' + Date.now() + Math.floor(Math.random() * 1000); // + random biar unik
    let $kodeInput = $row.find("input[name='kode[]']");

    // Pastikan input ditemukan
    if ($kodeInput.length) {
      $kodeInput.val(kode);
    } else {
      console.warn('Input kode[] tidak ditemukan di baris ini:', $row);
    }
  }

  // 5) Validasi & kirim form multi
  function checkForm() {
    setLoading(true);
    let valid = true;
    // hapus tanda invalid
    $('#barang-rows').find('input, select').removeClass('is-invalid');

    $('#barang-rows .barang-row').each(function(i, row) {
      let $r = $(row),
        kode = $r.find("input[name='kode[]']").val().trim(),
        nama = $r.find("input[name='nama[]']").val().trim(),
        harga = $r.find("input[name='harga[]']").val().trim();

      if (!kode) {
        swal('Baris ' + (i + 1) + ': Kode wajib diisi!', '', 'warning');
        $r.find("input[name='kode[]']").addClass('is-invalid');
        valid = false;
        return false;
      }
      if (!nama) {
        swal('Baris ' + (i + 1) + ': Nama wajib diisi!', '', 'warning');
        $r.find("input[name='nama[]']").addClass('is-invalid');
        valid = false;
        return false;
      }
      if (!harga) {
        swal('Baris ' + (i + 1) + ': Harga wajib diisi!', '', 'warning');
        $r.find("input[name='harga[]']").addClass('is-invalid');
        valid = false;
        return false;
      }
    });

    if (!valid) {
      setLoading(false);
      return;
    }
    submitForm();
  }

  // function submitForm() {
  //   let fd = new FormData();
  //   // append array fields
  //   $("input[name='kode[]']").each((i, el) => fd.append('kode[]', el.value));
  //   $("input[name='nama[]']").each((i, el) => fd.append('nama[]', el.value));
  //   $("select[name='jenisbarang[]']").each((i, el) => fd.append('jenisbarang[]', el.value));
  //   $("select[name='satuan[]']").each((i, el) => fd.append('satuan[]', el.value));
  //   $("select[name='merk[]']").each((i, el) => fd.append('merk[]', el.value));
  //   $("input[name='harga[]']").each((i, el) => fd.append('harga[]', el.value));
  //   // file uploads
  //   $(".photo-input").each((i, el) => {
  //     if (el.files[0]) fd.append('photo[]', el.files[0]);
  //   });

  //   $.ajax({
  //     type: 'POST',
  //     url: "{{ route('barang.store') }}",
  //     processData: false,
  //     contentType: false,
  //     dataType: 'json',
  //     data: fd,
  //     success: function() {
  //       $('#modaldemo8').modal('hide');
  //       swal('Berhasil ditambah!', '', 'success');
  //       table.ajax.reload(null, false);
  //       resetRows();
  //     },
  //     error: function() {
  //       swal('Gagal menyimpan!', '', 'error');
  //       setLoading(false);
  //     }
  //   });
  // }


  function submitForm() {
    let fd = new FormData();

    // Loop semua baris
    $('#barang-rows .barang-row').each(function(i, row) {
      let $r = $(row);
      let kode = $r.find("input[name='kode[]']").val().trim();
      let nama = $r.find("input[name='nama[]']").val().trim();
      let jenisbarang = $r.find("select[name='jenisbarang[]']").val();
      let satuan = $r.find("select[name='satuan[]']").val();
      let merk = $r.find("select[name='merk[]']").val();
      let harga = $r.find("input[name='harga[]']").val().trim();
      let photoInput = $r.find("input[name='photo[]']")[0];

      // ✅ Hanya kirim jika baris terisi
      if (kode && nama && harga) {
        fd.append('kode[]', kode);
        fd.append('nama[]', nama);
        fd.append('jenisbarang[]', jenisbarang);
        fd.append('satuan[]', satuan);
        fd.append('merk[]', merk);
        fd.append('harga[]', harga);

        // Kirim gambar jika ada
        if (photoInput && photoInput.files.length > 0) {
          fd.append('photo[]', photoInput.files[0]);
        } else {
          fd.append('photo[]', ''); // Tetap kirim agar sejajar dengan input lainnya
        }
      }
    });

    $.ajax({
      type: 'POST',
      url: "{{ route('barang.store') }}",
      processData: false,
      contentType: false,
      dataType: 'json',
      data: fd,
      success: function() {
        $('#modaldemo8').modal('hide');
        swal('Berhasil ditambah!', '', 'success');
        table.ajax.reload(null, false);
        resetRows();
      },
      error: function(xhr, status, error) {
        console.error("XHR Status:", status);
        console.error("Error:", error);
        // console.error("Response:", xhr.responseText);
        swal('Gagal menyimpan!', xhr.responseText, 'error');
        setLoading(false);
      }
    });
  }


  function setLoading(on) {
    $('#btnLoader').toggleClass('d-none', !on);
    $('#btnSimpan').toggleClass('d-none', on);
  }

  // 6) Validasi & preview gambar
  function VerifyFileNameAndFileSize(input) {
    let file = input.files[0];
    if (!file) return;
    let ext = file.name.split('.').pop().toLowerCase();
    if (!['png', 'jpg', 'jpeg', 'svg'].includes(ext)) {
      swal('Format bukan gambar!', '', 'warning');
      input.value = '';
      return;
    }
    if (file.size > 3 * 1024 * 1024) {
      swal('Ukuran maksimum 3 MB', '', 'warning');
      input.value = '';
      return;
    }
    let url = URL.createObjectURL(file);
    $(input).closest('.barang-row').find('.outputImg').attr('src', url);
  }
</script>
@endsection