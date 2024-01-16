<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>

<style>
  .card .card-header {
    border-bottom-color: #f9f9f9;
    line-height: 30px;
    -ms-grid-row-align: center;
    align-self: center;
    width: 100%;
    min-height: 70px;
    padding: 15px 25px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    align-content: center;
  }

  .note-toolbar-wrapper {
    height: auto !important;
  }
</style>

<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Data Kegiatan</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url() ?>">Dashboard</a></div>
        <div class="breadcrumb-item">Data Kegiatan</div>
      </div>
    </div>

    <div class="row align-items-left align-middle p-0 mb-3">
      <div class="col-12 col-md-6">
        <h2 class="section-title">Data Kegiatan</h2>
      </div>
      <div class="col-12 col-md-6 text-center text-md-right m-auto">
        <button class="btn btn-primary" id="add-form-data">Tambah Data</button>
      </div>
    </div>

    <div class="row">
      <div class="col-12 col-md-4">
        <div class="form-group">
          <label for="fil_jabatan">Jabatan</label>
          <select class="form-control select2" data-width="100%" data-allow-clear="true" data-placeholder="Pilih Jabatan" id="fil_jabatan" name="fil_jabatan"></select>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="form-group">
          <label>Tanggal Kegiatan</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <i class="fas fa-calendar"></i>
              </div>
            </div>
            <input type="text" id="fil_tanggal" name="fil_tanggal" class="form-control daterange-kegiatan">
          </div>
        </div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Data Kegiatan</h4>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped" id="tb_data" style="width: 100%;">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>Jabatan</th>
                      <th>Kegiatan</th>
                      <!-- <th>Action</th> -->
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<div class="modal fade text-left" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form-data" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pb-0">
        <form id="form-data" class="form form-horizontal">
          <div class="form-body">
            <input type="hidden" name="keg_id" id="keg_id">
            <input type="hidden" name="keg_edit" id="keg_edit" value="0">
            <div class="row">
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="keg_jabatan">Jabatan</label>
                  <select class="form-control select2" data-width="100%" data-allow-clear="true" data-placeholder="Pilih Jabatan" id="keg_jabatan" name="keg_jabatan"></select>
                </div>
              </div>
              <div class="col-12 col-md-12" id="elemen-fungsi">
                <div class="form-group">
                  <label for="keg_fungsi">Fungsi</label>
                  <select class="form-control select2" data-width="100%" data-allow-clear="true" data-placeholder="Pilih Fungsi" id="keg_fungsi" name="keg_fungsi"></select>
                </div>
              </div>
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <label>Tanggal Kegiatan</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-calendar"></i>
                      </div>
                    </div>
                    <input type="text" id="keg_tanggal" name="keg_tanggal" class="form-control daterange-kegiatan">
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <label for="keg_nama">Nama Kegiatan</label>
                  <textarea class="form-control keg-summernote" id="keg_nama" name="keg_nama"></textarea>
                </div>
              </div>
              <!-- <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="keg_foto">Foto</label>
                  <small class="form-text text-muted my-1">File maksimal 5MB</small>
                  <input type="file" accept="image/*" class="form-control file" id="keg_foto" placeholder="Foto">
                  <input type="hidden" name="keg_foto">
                </div>
              </div>
              <div class="col-12 col-md-6 konten_keg_foto d-none">
                <div class="form-group">
                  <input type="hidden" name="keg_foto_old">
                  <img src="" class="" id="keg_foto_old" alt="" style="max-width: 95%; height: auto;">
                  <button href="#" class="btn btn-icon btn-danger rounded-pill position-absolute" style="right: 10px; top: -20px;">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div> -->
              <div class="col-12">
                <div class="form-group">
                  <label for="keg_foto">Foto</label>
                  <small class="form-text text-muted my-1">File maksimal 5MB</small>
                  <div class="row py-2" id="imageContainer"></div>
                  <button type="button" class="btn btn-success" onclick="addImage('imageContainer', 'keg_foto')">Tambah Foto</button>
                  <!-- <button type="button" class="btn btn-danger" onclick="removeImage()">Hapus Foto Terakhir</button> -->
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-outline-danger">Close</button>
        <button type="button" class="btn btn-primary" id="save-form">Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade text-left" id="modal-kegiatan" tabindex="-1" role="dialog" aria-labelledby="modal-kegiatan-data" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pb-0">
        <h6 id="keg-text" class="mb-4"></h6>
        <div class="row">
          <div class="col-12" id="konten-kegiatan">
            <table class="table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Progres</th>
                  <th>Bukti</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>
                    <div class="progress mb-3">
                      <div class="progress-bar" role="progressbar" data-width="75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%;">75%</div>
                    </div>
                  </td>
                  <td>1</td>
                  <td>1</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-outline-danger">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade text-left" id="modal-progres" tabindex="-1" role="dialog" aria-labelledby="modal-progres-data" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pb-0">
        <form id="form-data" class="form form-horizontal">
          <div class="form-body">
            <input type="hidden" name="prog_id" id="prog_id">
            <input type="hidden" name="keg_id" id="keg_id">
            <input type="hidden" name="keg_edit" id="keg_edit" value="0">
            <div class="row">
              <div class="col-12">
                <h4 id="keg_nama"></h4>
              </div>
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <label for="prog_tanggal">Tanggal Kegiatan</label>
                  <input type="text" class="form-control datepicker" id="prog_tanggal" name="prog_tanggal">
                </div>
              </div>
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <label for="keg_progres">Progres Kegiatan</label>
                  <input type="hidden" id="prog_persentase" name="prog_persentase">
                  <div class="progress" id="keg_progres">
                    <div class="progress-bar" role="progressbar" data-width="" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
              </div>
              <!-- <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="prog_bukti">Bukti Kegiatan</label>
                  <small class="form-text text-muted my-1">File maksimal 5MB</small>
                  <input type="file" accept="image/*" class="form-control file" id="prog_bukti" placeholder="Foto">
                  <input type="hidden" name="prog_bukti">
                </div>
              </div>
              <div class="col-12 col-md-6 konten_prog_bukti d-none">
                <div class="form-group">
                  <input type="hidden" name="prog_bukti_old">
                  <img src="" class="img-fluid" id="prog_bukti_old" alt="">
                </div>
              </div> -->
              <div class="col-12">
                <div class="form-group">
                  <label for="prog_bukti">Bukti Kegiatan</label>
                  <small class="form-text text-muted my-1">File maksimal 5MB</small>
                  <div class="row py-2" id="imageContainerProg"></div>
                  <button type="button" class="btn btn-success" onclick="addImage('imageContainerProg', 'prog_bukti')">Tambah Foto</button>
                </div>
              </div>
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <label for="prog_keterangan">Keterangan</label>
                  <textarea class="form-control prog-summernote" id="prog_keterangan" name="prog_keterangan"></textarea>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-outline-danger">Close</button>
        <button type="button" class="btn btn-primary" id="save-form">Simpan</button>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('dist/_partials/footer'); ?>
<script src="https://cdn.jsdelivr.net/gh/xcash/bootstrap-autocomplete@v2.3.7/dist/latest/bootstrap-autocomplete.min.js"></script>

<script>
  // Tambahkan gambar
  function addImage(container, name) {
    var container = $('#' + container);
    var input = $('<input>').attr({
      type: 'file',
      name: name + '[]',
      accept: 'image/*',
      required: true,
      class: 'form-control img-multi'
    }).on('change', previewImage);

    var inputHidden = $('<input>').attr({
      type: 'hidden',
      name: name + '_old[]'
    });

    var imageDiv = $('<div>').addClass('image-container col-12 col-md-6').append(input).append(inputHidden);
    container.append(imageDiv);
    return [
      input,
      inputHidden
    ];
  }

  // Tampilkan pratinjau gambar
  function previewImage(event) {
    var input = event.target;
    var imageContainer = $(input).parent();

    // Hapus pratinjau sebelumnya
    imageContainer.find('.preview').remove();

    // Buat dan tampilkan pratinjau
    var preview = $('<img>').addClass('preview img-fluid py-3').attr('src', URL.createObjectURL(input.files[0]));
    imageContainer.append(preview);

    // Buat tombol hapus
    var removeBtn = $('<button>').addClass('btn btn-icon btn-danger rounded-pill position-absolute').css({
      'right': '10px',
      'top': '30px'
    }).html('<i class="fas fa-times"></i>').click(function() {
      imageContainer.remove();
    });

    imageContainer.append(removeBtn);
  }

  function setSrcImage(imgElement, inpHidden, file) {
    var imageContainer = imgElement.parent();

    // Hapus pratinjau sebelumnya
    imageContainer.find('.preview').remove();

    // Buat dan tampilkan pratinjau
    var preview = $('<img>').addClass('preview img-fluid py-3').attr('src', file);
    imageContainer.append(preview);

    inpHidden.val(file)

    // Buat tombol hapus
    var removeBtn = $('<button>').addClass('btn btn-icon btn-danger rounded-pill position-absolute').css({
      'right': '10px',
      'top': '30px'
    }).html('<i class="fas fa-times"></i>').click(function() {
      imageContainer.remove();
    });

    imageContainer.append(removeBtn);
  }

  $(document).ready(function() {

    // Event handler untuk tombol tambah dan hapus
    $('#addImageButton').click(addImage);
  });
</script>

<script>
  var fil_jabatan, fil_tanggal;
  var tb_data;
  var id_kegiatan;

  $(document).ready(function() {
    tb_data = $("table#tb_data").DataTable({
      bInfo: false,
      bLengthChange: true,
      searching: true,
      processing: true,
      language: table_language(),
      responsive: true,
      serverSide: true,
      ajax: {
        type: "POST",
        url: base_url() + "admin/kegiatan/viewData",
        data: function(posts) {
          posts.fil_jabatan = fil_jabatan ?? null;
          posts.fil_tanggal = fil_tanggal ?? null;
        }
      },
      columns: [{
          data: "no",
          className: "text-center align-top",
          orderable: false
        },
        {
          data: "jbt_nama",
          className: "text-left align-top"
        },
        {
          data: "kegiatan",
          className: "text-left align-top",
          orderable: false
        },
        // {
        //   data: "opsi",
        //   className: "text-center align-top",
        //   orderable: false
        // }
      ],
      order: [],
      lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]
      ],
    });

    $('.daterange-kegiatan').daterangepicker({
      locale: {
        format: 'DD-MM-YYYY',
        applyLabel: 'Pilih',
        cancelLabel: 'Batal'
      },
      autoUpdateInput: true,
      showDropdowns: true,
      drops: 'down',
      opens: 'down'
    });

    $('input[type="file"].file').change(function(e) {
      var id = $(this).attr('id');
      var fileInput = this;
      $('.custom-file-label[for="' + id + '"]').html(fileInput.files[0].name);
      // upload single file
      var form_data = new FormData();
      form_data.append('file', (this).files[0]);
      form_data.append('id', id);

      $.ajax({
        url: "<?= site_url('admin/kegiatan/uploadSingleDokumen') ?>",
        type: 'POST',
        data: form_data,
        processData: false,
        contentType: false,
        dataType: 'json',
        beforeSend: function() {
          $('form#form-data input').removeClass('is-invalid');
          $('form#form-data select').removeClass('is-invalid');
          $('form#form-data textarea').removeClass('is-invalid');
          $('form#form-data span').removeClass('is-invalid');
          $('form#form-data .invalid-feedback').remove();
          $('form#form-data .valid-feedback').remove();
        },
        success: function(res) {
          var frm = Object.keys(res.form);
          var val = Object.values(res.form);
          $('form#form-data input').removeClass('is-invalid');
          $('form#form-data select').removeClass('is-invalid');
          $('form#form-data textarea').removeClass('is-invalid');
          $('form#form-data span').removeClass('is-invalid');
          $('form#form-data .invalid-feedback').remove();
          $('form#form-data .valid-feedback').remove();
          if (res.ok == 400) {
            frm.forEach(function(el, ind) {
              if (val[ind] != '') {
                $('form#form-data #' + el).removeClass('is-invalid').addClass("is-invalid");
                $('form#form-data span[aria-labelledby="select2-' + el + '-container"]').removeClass('is-invalid').addClass("is-invalid");
                var app = '<div id="' + el + '-error" class="invalid-feedback d-block" for="' + el + '">' + val[ind] + '</div>';
                $('form#form-data #' + el).closest('.form-group').append(app);
              }
            });
          } else {
            $('form#form-data input[name="' + id + '"]').val(res.file);
            frm.forEach(function(el, ind) {
              if (val[ind] != '') {
                $('form#form-data #' + el).removeClass('is-invalid');
                $('form#form-data span[aria-labelledby="select2-' + el + '-container"]').removeClass('is-invalid');
                var app = '<div id="' + el + '-error" class="valid-feedback d-block" for="' + el + '">' + val[ind] + '</div>';
                $('form#form-data #' + el).closest('.form-group').append(app);
              }
            });

            // Memeriksa apakah pengguna telah memilih file gambar
            if (fileInput.files && fileInput.files[0]) {
              var reader = new FileReader();

              // Ketika proses baca file selesai
              reader.onload = function(e) {
                // Menetapkan sumber gambar pada elemen img
                $('#' + id + '_old').attr('src', e.target.result);
                $('.konten_' + id + '').removeClass('d-none');
              };

              // Membaca file gambar yang dipilih
              reader.readAsDataURL(fileInput.files[0]);
            }
          }
        }
      });
    });

    getJabatanFilter('fil_jabatan');

    $(document).off("change", "select#fil_jabatan")
      .on("change", "select#fil_jabatan", function(e) {
        e.preventDefault();
        fil_jabatan = $(this).val()
        tb_data.ajax.reload(null, true);
      });

    $(document).off("change", "input#fil_tanggal")
      .on("change", "input#fil_tanggal", function(e) {
        e.preventDefault();
        fil_tanggal = $(this).val()
        tb_data.ajax.reload(null, true);
      });

    $('input#fil_tanggal').on('cancel.daterangepicker', function(ev, picker) {
      $('input#fil_tanggal').val(null).trigger('change');
    });
  });

  $(document).off("click", "table#tb_data button.update-data")
    .on("click", "table#tb_data button.update-data", function(e) {
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: base_url() + "admin/kegiatan/getData",
        data: {
          id: $(this).attr("data-id")
        },
        dataType: "json",
        beforeSend: () => {
          id_kegiatan = null
        },
        success: function(res) {
          if (res.ok == 200) {
            $("#modal-form").modal({
              backdrop: false
            });
            $("#modal-form div.modal-header h4.modal-title").html("Ubah Data Kegiatan");
            // $("#modal-form form#form-data #keg_jabatan").val(res.data.keg_jabatan);
            $("#modal-form form#form-data #keg_edit").val(1);
            $("#modal-form form#form-data #keg_id").val(res.data.keg_id);
            id_kegiatan = res.data.keg_id;
            getJabatan('keg_jabatan', res.data.keg_jabatan, 1).done(function() {
              $("#modal-form form#form-data #keg_jabatan").val(res.data.keg_jabatan).trigger('change');
              getFungsiData('keg_fungsi', res.data.keg_jabatan).done(function() {
                setTimeout(() => {
                  $("#modal-form form#form-data select#keg_fungsi").val(res.data.keg_fungsi).trigger("change");
                }, 500);
              });
            });
            setTimeout(() => {
              $('#modal-form form#form-data #keg_tanggal').data('daterangepicker').setStartDate(res.data.tanggal_mulai);
              $('#modal-form form#form-data #keg_tanggal').data('daterangepicker').setEndDate(res.data.tanggal_selesai);
              $("#modal-form form#form-data .keg-summernote").summernote();
              setTimeout(() => {
                $("#modal-form form#form-data .keg-summernote").summernote({
                  dialogsInBody: true,
                  // airMode: true,
                  minHeight: 200,
                  toolbar: [
                    ["style", ["bold", "italic", "underline", "clear"]],
                    ["font", ["strikethrough"]],
                    ["para", ["paragraph"]],
                  ],
                });
              }, 200);
              $("#modal-form form#form-data #keg_nama").summernote('code', res.data.keg_nama);
              // $("#modal-form form#form-data [name=keg_foto_old]").val(res.data.progres[0].prog_bukti);
              // $("#modal-form form#form-data img#keg_foto_old").attr('src', res.data.progres[0].prog_bukti);
              // console.log(res.data.progres[0].bukti);
              res.data.progres[0].bukti.forEach((el, id) => {
                var img = addImage('imageContainer', 'keg_foto');
                setSrcImage(img[0], img[1], el.buk_foto)
              });
              $("#modal-form form#form-data .konten_keg_foto").removeClass('d-none');
            }, 500);
          } else {
            swal({
              title: "Error",
              text: res.data,
              type: "error",
              confirmButtonClass: "btn btn-danger",
              buttonsStyling: false,
            });
          }
        }
      });
    });

  $(document).off("click", "table#tb_data button.delete-data")
    .on("click", "table#tb_data button.delete-data", function(e) {
      e.preventDefault();
      var id = $(this).attr("data-id");
      var _name_ = $(this).attr("data-name");
      swal({
        title: "Hapus Data ?",
        text: _name_,
        icon: "warning",
        confirmButtonColor: '#ff0000',
        cancelButtonColor: '#d33',
        buttons: {
          cancel: {
            text: "Cancel",
            value: null,
            visible: true,
            className: "",
            closeModal: true,
          },
          confirm: {
            text: "OK",
            value: true,
            visible: true,
            className: "",
            closeModal: true,
          }
        },
        dangerMode: true,
      }).then(function(conf) {
        if (conf) {
          $.ajax({
            type: "POST",
            url: base_url() + "admin/kegiatan/delete",
            data: {
              id: id
            },
            dataType: "json",
            success: function(res) {
              swal({
                icon: (res.ok == 200) ? "success" : "error",
                title: res.data,
              }).then(function(res_) {
                tb_data.ajax.reload(null, true);
              })
            }
          });
        }
      })
    });

  $(document).off("click", "button#add-form-data")
    .on("click", "button#add-form-data", function(e) {
      e.preventDefault();
      $("#modal-form").modal({
        backdrop: false
      });
      $("#modal-form div.modal-header h4.modal-title").html("Tambah Data Kegiatan");
      $("#modal-form form#form-data input").val(null);
      $("#modal-form form#form-data .keg-summernote").summernote();
      setTimeout(() => {
        $("#modal-form form#form-data .keg-summernote").summernote({
          dialogsInBody: true,
          // airMode: true,
          minHeight: 200,
          toolbar: [
            ["style", ["bold", "italic", "underline", "clear"]],
            ["font", ["strikethrough"]],
            ["para", ["paragraph"]],
          ],
        });
      }, 500);
      getJabatan('keg_jabatan');
    });

  $(document).off("click", "#modal-form button#save-form")
    .on("click", "#modal-form button#save-form", function(e) {
      simpan()
    });

  $(document).ready(function() {
    $(document).off("click", "table#tb_data button.add-progres")
      .on("click", "table#tb_data button.add-progres", function(e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        var name = $(this).attr("data-name");
        addProgres(id, name)
      });
  });

  $(document).off("change", "select#keg_jabatan")
    .on("change", "select#keg_jabatan", function(e) {
      e.preventDefault();
      var id = $(this).val();
      if (id) {
        getFungsiData('keg_fungsi', id)
        $('#elemen-fungsi').removeClass('d-none');
      } else {
        $('#elemen-fungsi').addClass('d-none');
        $('select#keg_fungsi').val(null).trigger('change')
      }
    });

  $(document).off("hidden.bs.modal", "#modal-form")
    .on("hidden.bs.modal", "#modal-form", function(e) {
      $("div.modal-header h4.modal-title").html(null);
      $("form#form-data input").val(null);
      $("form#form-data textarea").val(null);
      $("form#form-data select").val(null).trigger("change");
      $("form#form-data .keg-summernote").summernote('code', '');
      $("form#form-data input").removeClass("is-invalid");
      $("form#form-data textarea").removeClass("is-invalid");
      $("form#form-data select").removeClass("is-invalid");
      $("form span").removeClass("is-invalid");
      $("form .invalid-feedback").remove();
      $("form .valid-feedback").remove();
      $("form#form-data [name=keg_foto_old]").val(null);
      $("form#form-data #imageContainer").html(null);
      $("form#form-data #imageContainerProg").html(null);
      $("form#form-data img#keg_foto_old").attr('src', null);
      $("form#form-data .konten_keg_foto").addClass('d-none');
      $('#keg_tanggal').daterangepicker({
        locale: {
          format: 'DD-MM-YYYY',
          applyLabel: 'Pilih',
          cancelLabel: 'Batal'
        },
        autoUpdateInput: true,
        showDropdowns: true,
        drops: 'down',
        opens: 'down'
      });
      $("#keg_tanggal").data('daterangepicker').setStartDate(null);
      $("#keg_tanggal").data('daterangepicker').setEndDate(null);
    });

  $(document).off("hidden.bs.modal", "#modal-progres")
    .on("hidden.bs.modal", "#modal-progres", function(e) {
      $("div.modal-header h4.modal-title").html(null);
      $("form#form-data input").val(null);
      $("form#form-data textarea").val(null);
      $("form#form-data select").val(null).trigger("change");
      $("form#form-data input").removeClass("is-invalid");
      $("form#form-data textarea").removeClass("is-invalid");
      $("form#form-data select").removeClass("is-invalid");
      $("form span").removeClass("is-invalid");
      $("form .invalid-feedback").remove();
      $("form .valid-feedback").remove();
      $("form#form-data .konten_keg_foto").addClass('d-none');
      $("form#form-data img#prog_bukti_old").attr('src', null);
      $("form#form-data #imageContainer").html(null);
      $("form#form-data #imageContainerProg").html(null);
      $("form#form-data .konten_prog_bukti").addClass('d-none');
      // $("#prog_tanggal").daterangepicker();
      $("#modal-progres form#form-data #prog_tanggal").daterangepicker({
        locale: {
          format: 'DD-MM-YYYY',
          applyLabel: 'Pilih',
          cancelLabel: 'Batal'
        },
        singleDatePicker: true,
      });
      // $("#prog_tanggal").data('daterangepicker').setStartDate(null);
      // $("#prog_tanggal").data('daterangepicker').setEndDate(null);
      $('#modal-progres form#form-data #keg_progres').html('<div class="progress-bar" role="progressbar" data-width="" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>')
      $("#modal-progres .prog-summernote").summernote('code', '');
    });

  function simpan() {
    var datas = new FormData($("#modal-form form#form-data")[0]);
    $.ajax({
      type: "POST",
      url: base_url() + "admin/kegiatan/addOrEdit",
      data: datas,
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      success: function(res) {
        if (res.ok == 200) {
          swal({
            title: "Sukses",
            text: res.form,
            icon: "success",
            confirmButtonClass: "btn btn-main",
            buttonsStyling: false,
          }).then(function(_res_) {
            $("#modal-form").modal("hide");
            tb_data.ajax.reload(null, true);
          });
        } else {
          if (res.ok == 400) {
            var frm = Object.keys(res.form);
            var val = Object.values(res.form);
            $('form#form-data .invalid-feedback').remove();
            frm.forEach(function(el, ind) {
              if (val[ind] != '') {
                var app = '<div id="' + el + '-error" class="invalid-feedback" for="' + el + '">' + val[ind] + '</div>';
                if (el == 'keg_tanggal') {
                  $('form#form-data #' + el).closest('.form-group .input-group').append(app);
                  $('form#form-data #' + el).removeClass('is-invalid').addClass("is-invalid");
                } else if (el == 'keg_foto') {
                  $('form#form-data .img-multi').closest('.form-group').append(app);
                  $('form#form-data .img-multi').removeClass('is-invalid').addClass("is-invalid");
                } else {
                  $('form#form-data #' + el).closest('.form-group').append(app);
                  $('form#form-data #' + el).removeClass('is-invalid').addClass("is-invalid");
                }
              }
            });
          } else {
            swal({
              title: "Error",
              text: res.form,
              icon: "error",
              confirmButtonClass: "btn btn-danger",
              buttonsStyling: false,
            });
          }
        }
      }
    });
  }

  function getJabatan(elem, id, isEdit = 0) {
    var link = base_url() + "admin/kegiatan/getJabatan";
    var param = null;
    if (id) {
      param = {
        id: id,
        is_edit: isEdit
      };
    } else {
      param = {
        is_edit: isEdit
      };
    }
    $("select#" + elem).html("");
    return $.ajax({
      url: link,
      type: "POST",
      dataType: "json",
      data: param,
      success: function(res) {
        var list = "";
        res.forEach(function(el, ind) {
          list += '<option value="' + el.jbt_id + '">' + el.jbt_nama + "</option>";
        });

        $("select#" + elem).html(list);
        $("select#" + elem)
          .val(null)
          .trigger("change");
      },
    });
  }

  function getFungsiData(elem, id = null, isEdit = 0) {
    var link = base_url() + "admin/kegiatan/getFungsiData";
    var param = null;
    if (id) {
      param = {
        id: id,
        is_edit: isEdit
      };
    } else {
      param = {
        is_edit: isEdit
      };
    }
    $("select#" + elem).html("");
    return $.ajax({
      url: link,
      type: "POST",
      dataType: "json",
      data: param,
      success: function(res) {
        var list = "";
        res.forEach(function(el, ind) {
          list += '<option value="' + el.fun_id + '">' + el.fun_nama + "</option>";
        });

        $("select#" + elem).html(list);
        $("select#" + elem)
          .val(null)
          .trigger("change");
      },
    });
  }

  function detailKegiatan(keg_id) {
    $.ajax({
      type: "POST",
      url: base_url() + "admin/kegiatan/detailKegiatan",
      data: {
        keg_id: keg_id,
      },
      dataType: "json",
      success: function(res) {
        $("#modal-kegiatan").modal({
          backdrop: false
        });
        $('#modal-kegiatan #keg-text').html('Kegiatan : <br>' + res.keg_nama + ' (' + res.keg_progres + '%)')

        var acc = ''
        var prog = '';
        $.each(res.progres, function(idx, val) {
          if (val.prog_bukti) {
            var bukti = `<img src="` + val.prog_bukti + `" class="img-fluid mb-2">`;
          } else {
            var bukti = ``;
          }

          prog += `<tr>
                      <td class="text-center">` + (idx + 1) + `</td>
                      <td class="text-center py-2" style="width: 45%">
                        ` + bukti + `
                        <div class="progress">
                          <div class="progress-bar" role="progressbar" data-width="` + val.prog_persentase + `%" aria-valuenow="` + val.prog_persentase + `" aria-valuemin="0" aria-valuemax="100" style="width: ` + val.prog_persentase + `%; background-color:` + setColor(val.prog_persentase) + `;">` + val.prog_persentase + `%</div>
                        </div>
                        <div>
                          <p>` + val.prog_tanggal + `</p>
                        </div>
                      </td>
                      <td>` + val.prog_keterangan + `</td>
                      <td class="text-center">
                        <div class="btn-group" role="group">
                          <button class="btn btn-icon btn-warning" onclick="editProgres(` + val.prog_id + `)">
                            <i class="fa fa-edit"></i>
                          </button>
                        </div>
                      </td>
                    </tr>`
        });

        acc += `<table class="table table-striped">
                    <thead>
                      <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Bukti / Progres / Tanggal</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      ` + prog + `
                    </tbody>
                  </table>`
        $('#modal-kegiatan #konten-kegiatan').html(acc)
      }
    });
  }

  function addProgres(keg_id, keg_nama) {
    $("#modal-progres").modal({
      backdrop: false
    });

    $('#modal-progres form#form-data #keg_id').val(keg_id)
    id_kegiatan = keg_id;
    $('#modal-progres form#form-data #keg_nama').html("Kegiatan : " + keg_nama)

    var currentDate = moment().format('DD-MM-YYYY');

    $("#modal-progres form#form-data #prog_tanggal").daterangepicker({
      locale: {
        format: "DD-MM-YYYY"
      },
      singleDatePicker: true,
      startDate: currentDate,
      endDate: currentDate
    });

    setTimeout(() => {
      $("#modal-progres form#form-data #prog_tanggal").trigger('change');
      $("#modal-progres form#form-data .prog-summernote").summernote({
        dialogsInBody: true,
        // airMode: true,
        minHeight: 200,
        toolbar: [
          ["style", ["bold", "italic", "underline", "clear"]],
          ["font", ["strikethrough"]],
          ["para", ["paragraph"]],
        ],
      });
    }, 500);
  }

  function editProgres(prog_id) {
    $.ajax({
      type: "POST",
      url: base_url() + "admin/ProgresKegiatan/getData",
      data: {
        prog_id: prog_id
      },
      dataType: "json",
      success: function(res) {
        $("#modal-progres").modal({
          backdrop: false
        });

        $('#modal-progres form#form-data #prog_id').val(prog_id)
        $('#modal-progres form#form-data #keg_id').val(res.data.keg_id)
        id_kegiatan = res.data.keg_id;
        $('#modal-progres form#form-data #keg_nama').html("Kegiatan : " + res.data.keg_nama)

        var tanggalan = convertTanggal(new Date(res.data.prog_tanggal));
        $("#modal-progres form#form-data #prog_tanggal").daterangepicker({
          locale: {
            format: "DD-MM-YYYY"
          },
          singleDatePicker: true,
          startDate: tanggalan,
          endDate: tanggalan
        });

        setTimeout(() => {
          $("#modal-progres form#form-data #prog_tanggal").trigger('change');

          $("#modal-progres form#form-data .prog-summernote").summernote({
            dialogsInBody: true,
            // airMode: true,
            minHeight: 200,
            toolbar: [
              ["style", ["bold", "italic", "underline", "clear"]],
              ["font", ["strikethrough"]],
              ["para", ["paragraph"]],
            ],
          });
        }, 500);

        // $("#modal-progres form#form-data [name=prog_bukti_old]").val(res.data.prog_bukti);
        // $("#modal-progres form#form-data img#prog_bukti_old").attr('src', res.data.prog_bukti);
        // $("#modal-progres form#form-data .konten_prog_bukti").removeClass('d-none');
        res.data.bukti.forEach((el, id) => {
          var img = addImage('imageContainerProg', 'prog_bukti');
          setSrcImage(img[0], img[1], el.buk_foto)
        });
        $("#modal-progres form#form-data #prog_keterangan").summernote('code', res.data.prog_keterangan);

      }
    });

  }

  $(document).off("change", "#modal-progres form#form-data #prog_tanggal")
    .on("change", "#modal-progres form#form-data #prog_tanggal", function(e) {
      e.preventDefault();
      var tgl = $(this).val();
      getPersen(id_kegiatan, tgl, 1);
      // console.log(tgl);
      // console.log(id_kegiatan);
      // var keg_id = $('form#form-data #keg_id').val()
    });

  $(document).off("click", "#modal-progres button#save-form")
    .on("click", "#modal-progres button#save-form", function(e) {
      simpanProgres()
    });

  function getPersen(keg_id, tgl, is_progres = 0) {
    $.ajax({
      type: "POST",
      url: base_url() + "admin/ProgresKegiatan/getPersen",
      data: {
        keg_id: keg_id,
        tgl: tgl,
        is_progres: is_progres,
      },
      dataType: "json",
      success: function(res) {
        if (res.ok) {
          var prog = `<div class="progress-bar" role="progressbar" data-width="` + res.persentase + `%" aria-valuenow="` + res.persentase + `" aria-valuemin="0" aria-valuemax="100" style="width: ` + res.persentase + `%; background-color:` + setColor(res.persentase) + `;">` + res.persentase + `%</div>`
          $('#modal-progres form#form-data #keg_progres').html(prog)
          $('#modal-progres form#form-data #prog_persentase').val(res.persentase)
        } else {
          swal({
            title: "Error",
            text: res.message,
            icon: "error",
            confirmButtonClass: "btn btn-main",
            buttonsStyling: false,
          });

          var currentDate = moment().format('DD-MM-YYYY');

          $("#modal-progres form#form-data #prog_tanggal").daterangepicker({
            locale: {
              format: "DD-MM-YYYY"
            },
            singleDatePicker: true,
            startDate: currentDate,
            endDate: currentDate
          });
        }
      }
    });
  }

  function simpanProgres() {
    var datas = new FormData($("#modal-progres form#form-data")[0]);
    $.ajax({
      type: "POST",
      url: base_url() + "admin/progresKegiatan/addOrEdit",
      data: datas,
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      success: function(res) {
        if (res.ok == 200) {
          swal({
            title: "Sukses",
            text: res.form,
            icon: "success",
            confirmButtonClass: "btn btn-main",
            buttonsStyling: false,
          }).then(function(_res_) {
            $("#modal-progres").modal("hide");
            $("#modal-kegiatan").modal("hide");
            tb_data.ajax.reload(null, true);
          });
        } else {
          if (res.ok == 400) {
            var frm = Object.keys(res.form);
            var val = Object.values(res.form);
            $('#modal-progres form#form-data .invalid-feedback').remove();
            frm.forEach(function(el, ind) {
              if (val[ind] != '') {
                $('#modal-progres form#form-data #' + el).removeClass('is-invalid').addClass("is-invalid");
                var app = '<div id="' + el + '-error" class="invalid-feedback" for="' + el + '">' + val[ind] + '</div>';
                if (el == 'keg_tanggal') {
                  $('#modal-progres form#form-data #' + el).closest('.form-group .input-group').append(app);
                  $('#modal-progres form#form-data #' + el).removeClass('is-invalid').addClass("is-invalid");
                } else if (el == 'prog_bukti') {
                  $('#modal-progres form#form-data .img-multi').closest('.form-group').append(app);
                  $('#modal-progres form#form-data .img-multi').removeClass('is-invalid').addClass("is-invalid");
                } else {
                  $('#modal-progres form#form-data #' + el).closest('.form-group').append(app);
                  $('#modal-progres form#form-data #' + el).removeClass('is-invalid').addClass("is-invalid");
                }
              }
            });
          } else {
            swal({
              title: "Error",
              text: res.form,
              icon: "error",
              confirmButtonClass: "btn btn-danger",
              buttonsStyling: false,
            });
          }
        }
      }
    });
  }

  function convertTanggal(date) {
    var formattedDate = String(date.getDate()).padStart(2, '0') + "-" +
      String(date.getMonth() + 1).padStart(2, '0') + "-" +
      date.getFullYear();

    return formattedDate;
  }
</script>