<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>

<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Data User Perangkat Desa</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url() ?>">Dashboard</a></div>
        <div class="breadcrumb-item">Data User Perangkat Desa</div>
      </div>
    </div>

    <div class="row align-items-left align-middle p-0 mb-3">
      <div class="col-12 col-md-6">
        <h2 class="section-title">Data User Perangkat Desa</h2>
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
              <h4>Data User Perangkat Desa</h4>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped" id="tb_data" style="width: 100%;">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>Username</th>
                      <th>Nama</th>
                      <th>Jabatan</th>
                      <th>Level</th>
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
            <input type="hidden" name="id" id="id">
            <div class="row">
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <label for="usr_nama">Nama</label>
                  <input type="text" class="form-control" name="usr_nama" id="usr_nama" placeholder="Nama User" />
                </div>
              </div>
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <label for="usr_jabatan">Pilih Jabatan</label>
                  <select class="form-control select2" data-width="100%" data-allow-clear="true" data-placeholder="Pilih Jabatan" id="usr_jabatan" name="usr_jabatan"></select>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="usr_level">Level</label>

                  <select class="form-control select2" data-width="100%" data-allow-clear="true" data-placeholder="Pilih Level" id="usr_level" name="usr_level">
                    <option value="" selected></option>
                    <option value="1">Admin</option>
                    <option value="2">User</option>
                  </select>

                </div>
              </div>
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <label for="usr_username">Username</label>
                  <input type="text" class="form-control" name="usr_username" id="usr_username" placeholder="Username" />
                </div>
              </div>
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <label for="usr_password">Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" name="usr_password" id="usr_password" placeholder="Masukkan Password" />
                    <span class="input-group-text" id="showHide">
                      <i class="fa fa-eye"></i>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <label for="usr_password2">Ulangi Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" name="usr_password2" id="usr_password2" placeholder="Ulangi Password" />
                    <span class="input-group-text" id="showHide2">
                      <i class="fa fa-eye"></i>
                    </span>
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
        url: base_url() + "admin/user/viewData",
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
          data: "username",
          className: "text-left align-top"
        },
        {
          data: "first_name",
          className: "text-left align-top"
        },
        {
          data: "jbt_nama",
          className: "text-left align-top"
        },
        {
          data: "group_name",
          className: "text-left align-top"
        },
        {
          data: "opsi",
          className: "text-center align-top",
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
        url: base_url() + "admin/user/getData",
        data: {
          id: $(this).attr("data-id")
        },
        dataType: "json",
        success: function(res) {
          if (res.ok == 200) {
            $("#modal-form").modal({
              backdrop: false
            });
            $("#modal-form div.modal-header h4.modal-title").html("Ubah Data User");
            $("#modal-form form#form-data #id").val(res.data.user_id);
            getJabatan('usr_jabatan', res.data.jabatan_id, 1).done(function() {
              $("#modal-form form#form-data #usr_jabatan").val(res.data.jabatan_id);
            });
            $("#modal-form form#form-data #usr_nama").val(res.data.first_name);
            $("#modal-form form#form-data #usr_level").val(res.data.group_id).trigger('change');
            $("#modal-form form#form-data #usr_username").val(res.data.username);
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
            url: base_url() + "admin/user/delete",
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
      $("#modal-form div.modal-header h4.modal-title").html("Tambah Data User");
      $("#modal-form form#form-data input").val(null);
      getJabatan('usr_jabatan');
    });

  $(document).off("click", "#modal-form button#save-form")
    .on("click", "#modal-form button#save-form", function(e) {
      simpan()
    });

  $(document).off("hidden.bs.modal", "#modal-form")
    .on("hidden.bs.modal", "#modal-form", function(e) {
      const password = document.getElementById('usr_password');
      const password2 = document.getElementById('usr_password2');
      const showHide = document.getElementById('showHide');
      const showHide2 = document.getElementById('showHide2');

      password.type = 'password';
      password2.type = 'password';
      showHide.innerHTML = '<i class="fa fa-eye"></i>';
      showHide.style.cursor = 'pointer';
      showHide2.innerHTML = '<i class="fa fa-eye"></i>';
      showHide2.style.cursor = 'pointer';

      $("#modal-form div.modal-header h4.modal-title").html(null);
      $("#modal-form form#form-data input").val(null);
      $("#modal-form form#form-data textarea").val(null);
      $("#modal-form form#form-data select").val(null).trigger("change");
      $("#modal-form form#form-data input").removeClass("is-invalid");
      $("#modal-form form#form-data textarea").removeClass("is-invalid");
      $("#modal-form form#form-data select").removeClass("is-invalid");
      // $("#modal-form form#form-data #showHide").addClass("fa fa-eye");
    })

  function simpan() {
    var datas = new FormData($("#modal-form form#form-data")[0]);
    $.ajax({
      type: "POST",
      url: base_url() + "admin/user/addOrEdit",
      data: datas,
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function() {
        $('#modal-form form#form-data .invalid-feedback').remove();
        $('#modal-form form#form-data .form-control').removeClass('is-invalid');
      },
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
            frm.forEach(function(el, ind) {
              if (val[ind] != '') {
                $('#modal-form form#form-data #' + el).removeClass('is-invalid').addClass("is-invalid");
                var app = '<div id="' + el + '-error" class="invalid-feedback" for="' + el + '">' + val[ind] + '</div>';
                if (el == 'usr_password' || el == 'usr_password2') {
                  $('#modal-form form#form-data #' + el).closest('.input-group').append(app);
                } else {
                  $('#modal-form form#form-data #' + el).closest('.form-group').append(app);
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
    var link = base_url() + "admin/user/getJabatan";
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
  $(document).ready(function() {
    var password = $('#modal-form form#form-data #usr_password'); // id dari input password
    var password2 = $('#modal-form form#form-data #usr_password2'); // id dari input password2
    var showHide = $('#modal-form form#form-data #showHide'); // id span showHide dalam input group password
    var showHide2 = $('#modal-form form#form-data #showHide2'); // id span showHide2 dalam input group password

    password.attr('type', 'password'); // set type input password menjadi password
    password2.attr('type', 'password'); // set type input password menjadi password2
    showHide.html('<i class="fa fa-eye"></i>'); // masukkan icon eye dalam icon bootstrap 5
    showHide.css('cursor', 'pointer'); // ubah cursor menjadi pointer
    showHide2.html('<i class="fa fa-eye"></i>'); // masukkan icon eye dalam icon bootstrap 5
    showHide2.css('cursor', 'pointer'); // ubah cursor menjadi pointer

    showHide.on('click', function() {
      // ketika span diclick
      if (password.attr('type') === 'password') {
        // jika type inputnya password
        password.attr('type', 'text'); // ubah type menjadi text
        showHide.html('<i class="fa fa-eye-slash"></i>'); // ubah icon menjadi eye slash
      } else {
        // jika type bukan password (text)
        showHide.html('<i class="fa fa-eye"></i>'); // ubah icon menjadi eye
        password.attr('type', 'password'); // ubah type menjadi password
      }
    });

    showHide2.on('click', function() {
      // ketika span diclick
      if (password2.attr('type') === 'password') {
        // jika type inputnya password2
        password2.attr('type', 'text'); // ubah type menjadi text
        showHide2.html('<i class="fa fa-eye-slash"></i>'); // ubah icon menjadi eye slash
      } else {
        // jika type bukan password (text)
        showHide2.html('<i class="fa fa-eye"></i>'); // ubah icon menjadi eye
        password2.attr('type', 'password'); // ubah type menjadi password
      }
    });
  });
</script>