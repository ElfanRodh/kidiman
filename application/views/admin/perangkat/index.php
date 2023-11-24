<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>

<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Data Perangkat Desa</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url() ?>">Dashboard</a></div>
        <div class="breadcrumb-item">Data Perangkat Desa</div>
      </div>
    </div>

    <div class="row align-items-left align-middle p-0 mb-3">
      <div class="col-12 col-md-6">
        <h2 class="section-title">Data Perangkat Desa</h2>
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
              <h4>Data Perangkat Desa</h4>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped" id="tb_data" style="width: 100%;">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>Foto</th>
                      <th>Nama</th>
                      <th>Jenis Kelamin</th>
                      <th>Jabatan</th>
                      <th>Action</th>
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
            <input type="hidden" name="prj_id" id="prj_id">
            <div class="row">
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="prt_nama">Nama Perangkat</label>
                  <input type="text" class="form-control" name="prt_nama" id="prt_nama" placeholder="Nama Perangkat">
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="prt_jk">Jenis Kelamin</label>
                  <select class="form-control select2" data-width="100%" data-allow-clear="true" data-placeholder="Pilih Jenis Kelamin" id="prt_jk" name="prt_jk">
                    <option value="1">Laki-laki</option>
                    <option value="2">Perempuan</option>
                  </select>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="prj_jabatan">Jabatan</label>
                  <select class="form-control select2" data-width="100%" data-allow-clear="true" data-placeholder="Pilih Jabatan" id="prj_jabatan" name="prj_jabatan"></select>
                </div>
              </div>
              <div class="col-12">
                <div class="row">
                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label for="prt_foto">Foto</label>
                      <small class="form-text text-muted my-1">File maksimal 5MB</small>
                      <input type="file" accept="image/*" class="form-control file" id="prt_foto" placeholder="Foto">
                      <input type="hidden" name="prt_foto">
                    </div>
                  </div>
                  <div class="col-12 col-md-6 konten_prt_foto d-none">
                    <div class="form-group">
                      <input type="hidden" name="prt_foto_old">
                      <img src="" class="img-fluid" id="prt_foto_old" alt="">
                    </div>
                  </div>
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
        url: base_url() + "admin/perangkat/viewData",
        data: function(posts) {
          posts.fil_nama = fil_nama ?? null;
        }
      },
      columns: [{
          data: "no",
          className: "text-center align-middle",
          orderable: false
        },
        {
          data: "prt_foto",
          className: "text-left align-middle"
        },
        {
          data: "prt_nama",
          className: "text-left align-middle"
        },
        {
          data: "prt_jk",
          className: "text-left align-middle"
        },
        {
          data: "jbt_nama",
          className: "text-left align-middle"
        },
        {
          data: "opsi",
          className: "text-center align-middle",
          orderable: false
        }
      ],
      order: [],
      lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]
      ],
    });
  });

  $(document).off("click", "table#tb_data button.update-data")
    .on("click", "table#tb_data button.update-data", function(e) {
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: base_url() + "admin/perangkat/getData",
        data: {
          id: $(this).attr("data-id")
        },
        dataType: "json",
        success: function(res) {
          if (res.ok == 200) {
            $("#modal-form").modal({
              backdrop: false
            });
            $("#modal-form div.modal-header h4.modal-title").html("Ubah Data Perangkat Desa");
            $("#modal-form form#form-data #prj_id").val(res.data.prj_id).trigger('change');
            getJabatan('prj_jabatan', res.data.prj_jabatan, 1).done(function() {
              $("#modal-form form#form-data #prj_jabatan").val(res.data.prj_jabatan).trigger('change');
            });
            $("#modal-form form#form-data #jbt_nama").val(res.data.jbt_nama).trigger('change');
            $("#modal-form form#form-data #prt_jk").val(res.data.prt_jk).trigger('change');
            $("#modal-form form#form-data #prt_nama").val(res.data.prt_nama).trigger('change');

            if (res.data.prt_foto) {
              $("#modal-form form#form-data [name=prt_foto_old]").val(res.data.prt_foto);
              $("#modal-form form#form-data img#prt_foto_old").attr('src', res.data.prt_foto);
              $("#modal-form form#form-data .konten_prt_foto").removeClass('d-none');
            }
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
            url: base_url() + "admin/perangkat/delete",
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
      $("#modal-form div.modal-header h4.modal-title").html("Tambah Data Perangkat Desa");
      $("#modal-form form#form-data input").val(null);
      getJabatan('prj_jabatan');
    });

  $(document).off("click", "#modal-form button#save-form")
    .on("click", "#modal-form button#save-form", function(e) {
      simpan()
    });

  $(document).off("hidden.bs.modal", "#modal-form")
    .on("hidden.bs.modal", "#modal-form", function(e) {
      $("#modal-form div.modal-header h4.modal-title").html(null);
      $("#modal-form form#form-data input").val(null);
      $("#modal-form form#form-data textarea").val(null);
      $("#modal-form form#form-data select").val(null).trigger("change");
      $("form#form-data input").removeClass("is-invalid");
      $("form#form-data textarea").removeClass("is-invalid");
      $("form#form-data select").removeClass("is-invalid");
      $("form#form-data [name=prt_foto_old]").val(null);
      $("form#form-data img#prt_foto_old").attr('src', null);
      $("form#form-data .konten_prt_foto").addClass('d-none');
    })

  $('input[type="file"]').change(function(e) {
    var id = $(this).attr('id');
    var fileInput = this;
    $('.custom-file-label[for="' + id + '"]').html(fileInput.files[0].name);
    // upload single file
    var form_data = new FormData();
    form_data.append('file', (this).files[0]);
    form_data.append('id', id);

    $.ajax({
      url: "<?= site_url('admin/perangkat/uploadSingleDokumen') ?>",
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

  function simpan() {
    var datas = new FormData($("form#form-data")[0]);
    $.ajax({
      type: "POST",
      url: base_url() + "admin/perangkat/addOrEdit",
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
                $('form#form-data #' + el).closest('.form-group').append(app);
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
    var link = base_url() + "admin/perangkat/getJabatan";
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
</script>
<script>
  $('#prt_nama').autoComplete({
    resolverSettings: {
      url: base_url() + 'admin/perangkat/getPerangkat'
    }
  });
</script>