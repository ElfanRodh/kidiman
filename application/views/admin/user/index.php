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
                      <th>Nama Perangkat</th>
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
            <input type="hidden" name="up_id" id="up_id">
            <div class="row">
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <label for="usr_nama">Nama</label>
                  <input type="text" class="form-control" name="usr_nama" id="usr_nama" placeholder="Nama User">
                </div>
              </div>
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <label for="usr_perangkat">Nama Perangkat</label>
                  <select class="form-control select2" data-width="100%" data-allow-clear="true" data-placeholder="Pilih Perangkat" id="usr_perangkat" name="usr_perangkat"></select>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="usr_level">Level</label>

                  <select class="form-control select2" data-width="100%" data-allow-clear="true" data-placeholder="Pilih Level" id="usr_level" name="usr_level">
                    <option value="" selected></option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                  </select>

                </div>
              </div>
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <label for="usr_username">Username</label>
                  <input type="text" class="form-control" name="usr_username" id="usr_username" placeholder="Username">
                </div>
              </div>
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <label for="usr_password">Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control input-group" name="usr_password" id="usr_password">
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
                    <input type="password" class="form-control" name="usr_password2" id="usr_password2">
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
          data: "usr_username",
          className: "text-left align-top"
        },
        {
          data: "usr_nama",
          className: "text-left align-top"
        },
        {
          data: "prt_nama",
          className: "text-left align-top"
        },
        {
          data: "usr_level",
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
            $("#modal-form form#form-data #up_id").val(res.data.up_id);
            getPerangkat('usr_perangkat', res.data.up_perangkat, 1).done(function() {
              $("#modal-form form#form-data #usr_perangkat").val(res.data.up_perangkat);
            });
            $("#modal-form form#form-data #usr_nama").val(res.data.usr_nama);
            if (res.data.usr_level == "admin") {
              $("#modal-form form#form-data #usr_level").val("admin").trigger('change');
            } else if (res.data.usr_level == "user") {
              $("#modal-form form#form-data #usr_level").val("user").trigger('change');
            }
            $("#modal-form form#form-data #usr_username").val(res.data.usr_username);
            $("#modal-form form#form-data #usr_password").val(res.data.usr_password);
            $("#modal-form form#form-data #usr_password2").val(res.data.usr_password);
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
      var _idusr_ = $(this).attr("data-idusr");
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
              id: _id_,
              idusr: _idusr_
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
      getPerangkat('usr_perangkat');
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
      $("form#form-data input").removeClass("is-invalid");
      $("form#form-data textarea").removeClass("is-invalid");
      $("form#form-data select").removeClass("is-invalid");
      $("#modal-form form#form-data #showHide").class("fa fa-eye");
    })

  function simpan() {
    var datas = new FormData($("form#form-data")[0]);
    $.ajax({
      type: "POST",
      url: base_url() + "admin/user/addOrEdit",
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

  function getPerangkat(elem, id, isEdit = 0) {
    var link = base_url() + "admin/user/getPerangkat";
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
          list += '<option value="' + el.prt_id + '">' + el.prt_nama + "</option>";
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
  const password = document.getElementById('usr_password'); // id dari input password
  const password2 = document.getElementById('usr_password2'); // id dari input password2
  const showHide = document.getElementById('showHide'); // id span showHide dalam input group password
  const showHide2 = document.getElementById('showHide2'); // id span showHide2 dalam input group password

  password.type = 'password'; // set type input password menjadi password
  password2.type = 'password'; // set type input password menjadi password2
  showHide.innerHTML = '<i class="fa fa-eye"></i>'; // masukan icon eye dalam icon bootstrap 5
  showHide.style.cursor = 'pointer'; // ubah cursor menjadi pointer
  showHide2.innerHTML = '<i class="fa fa-eye"></i>'; // masukan icon eye dalam icon bootstrap 5
  showHide2.style.cursor = 'pointer'; // ubah cursor menjadi pointer

  showHide.addEventListener('click', () => {
    // ketika span diclick
    if (password.type === 'password') {
      // jika type inputnya password
      password.type = 'text'; // ubah type menjadi text
      showHide.innerHTML = '<i class="fa fa-eye-slash"></i>'; // ubah icon menjadi eye slash
    } else {
      // jika type bukan password (text)
      showHide.innerHTML = '<i class="fa fa-eye"></i>'; // ubah icon menjadi eye
      password.type = 'password'; // ubah type menjadi password
    }
  });

  showHide2.addEventListener('click', () => {
    // ketika span diclick
    if (password2.type === 'password') {
      // jika type inputnya password2
      password2.type = 'text'; // ubah type menjadi text
      showHide2.innerHTML = '<i class="fa fa-eye-slash"></i>'; // ubah icon menjadi eye slash
    } else {
      // jika type bukan password (text)
      showHide2.innerHTML = '<i class="fa fa-eye"></i>'; // ubah icon menjadi eye
      password2.type = 'password'; // ubah type menjadi password
    }
  });
</script>