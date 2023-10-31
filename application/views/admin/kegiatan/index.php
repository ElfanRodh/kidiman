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
  <div class="modal-dialog modal-lg" role="document">
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
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="keg_foto">Foto</label>
                  <small class="form-text text-muted my-1">File maksimal 2MB</small>
                  <input type="file" class="form-control file" id="keg_foto" placeholder="Foto">
                  <input type="hidden" name="keg_foto">
                </div>
              </div>
              <div class="col-12 col-md-6 konten_keg_foto d-none">
                <div class="form-group">
                  <input type="hidden" name="keg_foto_old">
                  <img src="" class="img-fluid" id="keg_foto_old" alt="">
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
  <div class="modal-dialog modal-lg" role="document">
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

<?php $this->load->view('dist/_partials/footer'); ?>
<script src="https://cdn.jsdelivr.net/gh/xcash/bootstrap-autocomplete@v2.3.7/dist/latest/bootstrap-autocomplete.min.js"></script>

<script>
  var fil_nama;
  var tb_data;

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
          posts.fil_nama = fil_nama ?? null;
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
      drops: 'down',
      opens: 'left'
    });

    $('input[type="file"]').change(function(e) {
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
                $('#keg_foto_old').attr('src', e.target.result);
                $('.konten_keg_foto').removeClass('d-none');
              };

              // Membaca file gambar yang dipilih
              reader.readAsDataURL(fileInput.files[0]);
            }
          }
        }
      });
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
        success: function(res) {
          if (res.ok == 200) {
            $("#modal-form").modal({
              backdrop: false
            });
            $("#modal-form div.modal-header h4.modal-title").html("Ubah Data Kegiatan");
            // $("#modal-form form#form-data #keg_jabatan").val(res.data.keg_jabatan);
            $("#modal-form form#form-data #keg_edit").val(1);
            $("#modal-form form#form-data #keg_id").val(res.data.keg_id);
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
              $("#modal-form form#form-data #keg_nama").summernote('code', res.data.keg_nama);
              $("#modal-form form#form-data [name=keg_foto_old]").val(res.data.progres[0].prog_bukti);
              $("#modal-form form#form-data img#keg_foto_old").attr('src', res.data.progres[0].prog_bukti);
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
      var _id_ = $(this).attr("data-id");
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
              id: _id_
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
      getJabatan('keg_jabatan');
    });

  $(document).off("click", "#modal-form button#save-form")
    .on("click", "#modal-form button#save-form", function(e) {
      simpan()
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
      $("#modal-form div.modal-header h4.modal-title").html(null);
      $("#modal-form form#form-data input").val(null);
      $("#modal-form form#form-data textarea").val(null);
      $("#modal-form form#form-data select").val(null).trigger("change");
      $("#modal-form form#form-data .summernote-simple").summernote('code', '');
      $("#modal-form form#form-data .keg-summernote").summernote('code', '');
      $("form#form-data input").removeClass("is-invalid");
      $("form#form-data textarea").removeClass("is-invalid");
      $("form#form-data select").removeClass("is-invalid");
      $('form span').removeClass('is-invalid');
      $('form .invalid-feedback').remove();
      $('form .valid-feedback').remove();
      $("#modal-form form#form-data [name=keg_foto_old]").val(null);
      $("#modal-form form#form-data img#keg_foto_old").attr('src', null);
      $("#modal-form form#form-data .konten_keg_foto").addClass('d-none');
    })

  function simpan() {
    var datas = new FormData($("form#form-data")[0]);
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
                $('form#form-data #' + el).removeClass('is-invalid').addClass("is-invalid");
                var app = '<div id="' + el + '-error" class="invalid-feedback" for="' + el + '">' + val[ind] + '</div>';
                if (el == 'keg_tanggal') {
                  $('form#form-data #' + el).closest('.form-group .input-group').append(app);
                } else {
                  $('form#form-data #' + el).closest('.form-group').append(app);
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

  function detaiKegiatan(keg_id) {
    $.ajax({
      type: "POST",
      url: base_url() + "admin/kegiatan/detaiKegiatan",
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
                      <td>` + (idx + 1) + `</td>
                      <td class="py-2" style="width: 45%">
                        ` + bukti + `
                        <div class="progress">
                          <div class="progress-bar" role="progressbar" data-width="` + val.prog_persentase + `%" aria-valuenow="` + val.prog_persentase + `" aria-valuemin="0" aria-valuemax="100" style="width: ` + val.prog_persentase + `%;">` + val.prog_persentase + `%</div>
                        </div>
                        <div>
                          <p>` + val.prog_tanggal + `</p>
                        </div>
                      </td>
                      <td>` + val.prog_keterangan + `</td>
                    </tr>`
        });

        acc += `<table class="table table-striped">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Bukti / Progres / Tanggal</th>
                        <th>Keterangan</th>
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
</script>