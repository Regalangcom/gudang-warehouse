{{-- resources/views/Admin/BarangMasuk/_modal.blade.php --}}
<!-- MODAL TAMBAH -->
<div class="modal fade" data-bs-backdrop="static" id="modaldemo8" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content modal-content-demo">
      <div class="modal-header">
        <h6 class="modal-title">Tambah Barang Masuk</h6>
        <button class="btn-close" data-bs-dismiss="modal" onclick="resetForm()" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        {{-- KOLOM KIRI – DATA UMUM --}}
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Kode Barang Masuk <span class="text-danger">*</span></label>
              <input type="text" name="bmkode" class="form-control" readonly>
            </div>
            <div class="form-group">
              <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
              <input type="text" name="tglmasuk" class="form-control datepicker-date">
            </div>
            <div class="form-group">
              <label class="form-label">Pilih Supplier <span class="text-danger">*</span></label>
              <select name="supplier" class="form-control">
                <option value="">-- Pilih Supplier --</option>
                @foreach($supplier as $s)
                  <option value="{{ $s->supplier_id }}">{{ $s->supplier_nama }}</option>
                @endforeach
              </select>
            </div>
          </div>

          {{-- KOLOM KANAN – LIST ITEM DINAMIS --}}
          <div class="col-md-6">
            <div id="itemsContainer">
              {{-- BARIS ITEM PERTAMA --}}
              <div class="item-row mb-3">
                <div class="form-group mb-1">
                  <label class="form-label d-block">Kode Barang <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="hidden" name="status[]" class="status" value="false">
                    <div class="spinner-border spinner-border-sm d-none loaderkd" role="status"></div>
                    <input type="text" name="kdbarang[]" class="form-control kdbarang" autocomplete="off">
                    <button class="btn btn-primary-light search-btn" type="button"><i class="fe fe-search"></i></button>
                    <button class="btn btn-success-light modal-btn" type="button"><i class="fe fe-box"></i></button>
                  </div>
                </div>

                <div class="form-group">
                  <label class="form-label">Nama Barang</label>
                  <input type="text" name="nmbarang[]" class="form-control nmbarang" readonly>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label">Satuan</label>
                      <input type="text" name="satuan[]" class="form-control satuan" readonly>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label">Jenis</label>
                      <input type="text" name="jenis[]" class="form-control jenis" readonly>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="form-label">Jumlah Masuk <span class="text-danger">*</span></label>
                  <input type="text" name="jml[]" value="0" class="form-control jml"
                         oninput="this.value=this.value.replace(/[^0-9.]/g,'')">
                </div>

                <button type="button" class="btn btn-danger btn-sm remove-item"><i class="fe fe-trash-2"></i> Hapus</button>
                <hr>
              </div>
              {{-- END BARIS ITEM --}}
            </div>

            <button type="button" id="addItem" class="btn btn-secondary btn-sm"><i class="fe fe-plus"></i> Tambah Item</button>
          </div>
        </div> {{-- /row --}}
      </div>

      <div class="modal-footer">
        <button id="btnLoader" class="btn btn-primary d-none" disabled>
          <span class="spinner-border spinner-border-sm me-1"></span> Loading…
        </button>
        <button id="btnSimpan" onclick="checkForm()" class="btn btn-primary">Simpan <i class="fe fe-check"></i></button>
        <button class="btn btn-light" data-bs-dismiss="modal" onclick="resetForm()">Batal <i class="fe fe-x"></i></button>
      </div>
    </div>
  </div>
</div>

{{-- ====================================================================== --}}
@section('formTambahJS')
<script>
/* -----------------------------------------------------------
 * 1. DUPLIKASI & HAPUS BARIS ITEM
 * --------------------------------------------------------- */
$('#addItem').on('click', () => {
  const $clone = $('#itemsContainer .item-row:first').clone(true);

  $clone.find('input').val('').filter('.jml').val('0');
  $clone.find('.status').val('false');
  $clone.find('.loaderkd').addClass('d-none');
  $('#itemsContainer').append($clone);
});

$(document).on('click', '.remove-item', function () {
  if ($('#itemsContainer .item-row').length > 1) {
    $(this).closest('.item-row').remove();
  }
});

/* -----------------------------------------------------------
 * 2. AJAX PENCARIAN BARANG PER BARIS
 * --------------------------------------------------------- */
$(document).on('keypress', '.kdbarang', function (e) {
  if (e.which === 13) {
    const $row = $(this).closest('.item-row');
    getBarang($row);
  }
});

$(document).on('click', '.search-btn', function () {
  getBarang($(this).closest('.item-row'));
});

function getBarang($row) {
  const id = $row.find('.kdbarang').val().trim();
  if (!id) return;

  $row.find('.loaderkd').removeClass('d-none');
  $.getJSON("{{ url('admin/barang/getbarang') }}/" + id, function (data) {
    $row.find('.loaderkd').addClass('d-none');
    if (data.length) {
      $row.find('.status').val('true');
      $row.find('.nmbarang').val(data[0].barang_nama);
      $row.find('.satuan').val(data[0].satuan_nama);
      $row.find('.jenis').val(data[0].jenisbarang_nama);
    } else {
      $row.find('.status').val('false');
      $row.find('.nmbarang, .satuan, .jenis').val('');
    }
  });
}

/* -----------------------------------------------------------
 * 3. VALIDASI FORM & KIRIM DATA
 * --------------------------------------------------------- */
function checkForm(){
  resetValid();
  let ok = true;

  if (!$('input[name="tglmasuk"]').val()){
    validasi('Tanggal Masuk wajib di isi!','warning');
    $('input[name="tglmasuk"]').addClass('is-invalid'); ok=false;
  }
  if (!$('select[name="supplier"]').val()){
    validasi('Supplier wajib di pilih!','warning');
    $('select[name="supplier"]').addClass('is-invalid'); ok=false;
  }

  $('#itemsContainer .item-row').each(function(){
    const status = $(this).find('.status').val();
    const jml    = $(this).find('.jml').val();
    if (status!=='true' || !jml || jml==='0'){
      $(this).find('.kdbarang, .jml').addClass('is-invalid');
      ok = false;
    }
  });

  if (!ok) return;
  submitForm();
}

function submitForm(){
  setLoading(true);
  $.ajax({
    type : 'POST',
    url  : "{{ route('barang-masuk.store') }}",
    data : {
      bmkode  : $('input[name="bmkode"]').val(),
      tglmasuk: $('input[name="tglmasuk"]').val(),
      supplier: $('select[name="supplier"]').val(),
      barang  : $('input[name="kdbarang[]"]').map((i,e)=>e.value).get(),
      jml     : $('input[name="jml[]"]').map((i,e)=>e.value).get(),
      _token  : '{{ csrf_token() }}'
    },
    success : res => {
      $('#modaldemo8').modal('hide');
      swal('Berhasil!', 'Semua data berhasil disimpan', 'success');
      table.ajax.reload(null,false);
      resetForm();
    },
    error   : () => swal('Gagal', 'Periksa kembali data Anda', 'error'),
    complete: () => setLoading(false)
  });
}

/* -----------------------------------------------------------
 * 4. UTILITAS
 * --------------------------------------------------------- */
function resetValid(){
  $('input,select').removeClass('is-invalid');
}

function resetForm(){
  resetValid();
  $('input[name],select[name]').val('');
  const $first = $('#itemsContainer .item-row:first');
  $('#itemsContainer').html($first.clone(true));
  $('#itemsContainer .item-row input').val('').filter('.jml').val('0');
}

function setLoading(on){
  $('#btnLoader').toggleClass('d-none', !on);
  $('#btnSimpan').toggleClass('d-none', on);
}
</script>
@endsection
