<!-- MODAL TAMBAH -->
<div class="modal fade" data-bs-backdrop="static" id="modaldemo8">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content modal-content-demo">
      <div class="modal-header">
        <h6 class="modal-title">Tambah Barang Keluar</h6>
        <button aria-label="Close" onclick="resetForm()" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-md-6">
            <!-- Field statis -->
            <div class="form-group">
              <label for="bkkode" class="form-label">Kode Barang Keluar <span class="text-danger">*</span></label>
              <input type="text" name="bkkode" readonly class="form-control" placeholder="">
            </div>
            <div class="form-group">
              <label for="tglkeluar" class="form-label">Tanggal Keluar <span class="text-danger">*</span></label>
              <input type="text" name="tglkeluar" class="form-control datepicker-date" placeholder="">
            </div>
            <div class="form-group">
              <label for="customer" class="form-label">Pilih Customer <span class="text-danger">*</span></label>
              <select name="customer" id="customer" class="form-control">
                <option value="">-- Pilih Customer --</option>
                @foreach ($customer as $c)
                  <option value="{{ $c->customer_id }}">{{ $c->customer_nama }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="tujuan" class="form-label">Tujuan</label>
              <input type="text" name="tujuan" class="form-control" placeholder="">
            </div>
          </div>

          <div class="col-md-6">
            <!-- Container untuk semua item -->
            <div id="itemsContainer">
              <div class="item-row mb-2">
                <div class="form-group">
                  <label>Kode Barang <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="hidden" name="status[]" class="status-field" value="false">
                    <div class="spinner-border spinner-border-sm d-none loaderkd" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                    <input type="text" name="kdbarang[]" class="form-control kdbarang" autocomplete="off" placeholder="">
                    <button class="btn btn-primary-light" type="button"><i class="fe fe-search"></i></button>
                    <button class="btn btn-success-light" type="button"><i class="fe fe-box"></i></button>
                  </div>
                </div>
                <div class="form-group">
                  <label>Nama Barang</label>
                  <input type="text" name="nmbarang[]" class="form-control nmbarang" readonly>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Satuan</label>
                      <input type="text" name="satuan[]" class="form-control satuan" readonly>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Jenis</label>
                      <input type="text" name="jenis[]" class="form-control jenis" readonly>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Jumlah Keluar <span class="text-danger">*</span></label>
                  <input type="text" name="jml[]" value="0" class="form-control jml" placeholder="" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-item"><i class="fe fe-trash-2"></i> Hapus</button>
                <hr>
              </div>
            </div>
            <button type="button" id="addItem" class="btn btn-secondary btn-sm"><i class="fe fe-plus"></i> Tambah Item</button>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-primary d-none" id="btnLoader" type="button" disabled>
          <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
          Loading...
        </button>
        <a href="javascript:void(0)" onclick="checkForm()" id="btnSimpan" class="btn btn-primary">Simpan <i class="fe fe-check"></i></a>
        <a href="javascript:void(0)" class="btn btn-light" onclick="resetForm()" data-bs-dismiss="modal">Batal <i class="fe fe-x"></i></a>
      </div>
    </div>
  </div>
</div>


@section('formTambahJS')
<script>
  // Fungsi clone baris item
  $('#addItem').click(function(){
    let $first = $('#itemsContainer .item-row:first');
    let $clone = $first.clone();

    // Reset semua input di clone
    $clone.find('input').val('').filter('[name="jml[]"]').val('0');
    $clone.find('.status-field').val('false');
    $clone.find('.loaderkd').addClass('d-none');
    $('#itemsContainer').append($clone);
  });

  // Hapus baris item
  $(document).on('click', '.remove-item', function(){
    if ($('#itemsContainer .item-row').length > 1) {
      $(this).closest('.item-row').remove();
    }
  });

  // Cari barang per baris
  $(document).on('click', '.item-row .btn-primary-light', function(){
    let $row = $(this).closest('.item-row');
    let id = $row.find('.kdbarang').val().trim();
    getBarangById(id, $row);
  });

  function getBarangById(id, $row) {
    $row.find('.loaderkd').removeClass('d-none');
    $.ajax({
      type: 'GET',
      url: "{{ url('admin/barang/getbarang') }}/" + id,
      dataType: 'json',
      success: function(data) {
        $row.find('.loaderkd').addClass('d-none');
        if (data.length > 0) {
          $row.find('.status-field').val('true');
          $row.find('.nmbarang').val(data[0].barang_nama);
          $row.find('.satuan').val(data[0].satuan_nama);
          $row.find('.jenis').val(data[0].jenisbarang_nama);
        } else {
          $row.find('.status-field').val('false');
          $row.find('.nmbarang, .satuan, .jenis').val('');
        }
      }
    });
  }

  // Validasi sebelum submit
  function checkForm(){
    let tgl = $('input[name="tglkeluar"]').val(),
        cust = $('select[name="customer"]').val(),
        valid = true;

    // Validasi tanggal & customer
    if (!tgl) { validasi('Tanggal Keluar wajib di isi!','warning'); valid = false; }
    if (!cust) { validasi('Customer wajib di pilih!','warning'); valid = false; }

    // Validasi tiap item
    $('#itemsContainer .item-row').each(function(){
      let status = $(this).find('.status-field').val(),
          jml    = $(this).find('.jml').val();
      if (status !== 'true' || !jml || jml == '0') {
        $(this).find('.kdbarang, .jml').addClass('is-invalid');
        valid = false;
      }
    });

    if (!valid) return false;
    submitForm();
  }

  // Kirim data
  function submitForm() {
    let bkkode   = $('input[name="bkkode"]').val(),
        tglkeluar= $('input[name="tglkeluar"]').val(),
        customer = $('select[name="customer"]').val(),
        tujuan   = $('input[name="tujuan"]').val(),
        barangs  = $('input[name="kdbarang[]"]').map((i,el)=>el.value).get(),
        jmls     = $('input[name="jml[]"]').map((i,el)=>el.value).get();

    setLoading(true);
    $.ajax({
      type: 'POST',
      url: "{{ route('barang-keluar.store') }}",
      data: {
        bkkode, tglkeluar, customer, tujuan,
        barang: barangs,
        jml: jmls,
        _token: '{{ csrf_token() }}'
      },
      success: function(res){
        $('#modaldemo8').modal('hide');
        swal('Berhasil!','Semua data berhasil disimpan','success');
        table.ajax.reload(null,false);
        resetForm();
      },
      error: function(){
        swal('Gagal','Cek kembali data Anda','error');
      },
      complete: function(){
        setLoading(false);
      }
    });
  }

  function resetForm(){
    // reset statis
    $('input[name], select[name]').val('').removeClass('is-invalid');
    // kembalikan satu baris item
    let $first = $('#itemsContainer .item-row:first').clone();
    $first.find('input').val('').filter('[name="jml[]"]').val('0');
    $('#itemsContainer').html($first);
    setLoading(false);
  }

  function setLoading(on){
    $('#btnSimpan').toggleClass('d-none', on);
    $('#btnLoader').toggleClass('d-none', !on);
  }
</script>
@endsection
