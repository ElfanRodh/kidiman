<?php
defined('BASEPATH') or exit('No direct script access allowed');

$user   = $_SESSION['usr'];
$waktu  = date('Y-m-d H:i:s');
$selisihWaktu = hitungSelisihWaktu($user['last_login'], $waktu);

?>

<style>
  #modal-profil .input-group-text:hover {
    cursor: pointer;
  }
</style>

<script>
  $(document).off("click", "#modal-profil button#save-form")
    .on("click", "#modal-profil button#save-form", function(e) {
      simpanProfil()
    });

  function editProfil(id) {
    $("#modal-profil").modal({
      backdrop: false
    });
    var user = JSON.parse('<?= json_encode($user) ?>');
    console.log(user);
    $("#modal-profil form#form-profil #usr_id_profil").val(user.users_id)
    $("#modal-profil form#form-profil #usr_level_profil").val(user.groups_id)
    $("#modal-profil form#form-profil #usr_nama_profil").val(user.first_name)
    $("#modal-profil form#form-profil #usr_username_profil").val(user.username)
  }

  function showHide(elem) {
    const password = $('#modal-profil form#form-profil #' + elem);
    const showHide = $('#modal-profil form#form-profil #showHide-' + elem); // id dari input password
    if (password.attr('type') === 'password') {
      // jika type inputnya password
      password.attr('type', 'text'); // ubah type menjadi text
      showHide.html('<i class="fa fa-eye-slash"></i>'); // ubah icon menjadi eye slash
    } else {
      // jika type bukan password (text)
      showHide.html('<i class="fa fa-eye"></i>'); // ubah icon menjadi eye
      password.attr('type', 'password'); // ubah type menjadi password
    }
  }

  function simpanProfil() {
    var datas = new FormData($("#modal-profil form#form-profil")[0]);
    $.ajax({
      type: "POST",
      url: base_url() + "admin/user/editProfil",
      data: datas,
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function() {
        $('#modal-profil form#form-profil .invalid-feedback').remove();
        $('#modal-profil form#form-profil .form-control').removeClass('is-invalid');
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
            $("#modal-profil").modal("hide");
            setTimeout(() => {
              swal({
                title: "Anda Harus Login Lagi",
                text: 'Anda harus login kembali setelah mengganti profil',
                icon: "info",
                confirmButtonClass: "btn btn-main",
                buttonsStyling: false,
              }).then(function(_res_) {
                window.location.href = '/auth/logout';
              });
            }, 200);
          });
        } else {
          if (res.ok == 400) {
            var frm = Object.keys(res.form);
            var val = Object.values(res.form);
            frm.forEach(function(el, ind) {
              if (val[ind] != '') {
                $('#modal-profil form#form-profil #' + el).removeClass('is-invalid').addClass("is-invalid");
                var app = '<div id="' + el + '-error" class="invalid-feedback" for="' + el + '">' + val[ind] + '</div>';
                if (el == 'usr_password_lama' || el == 'usr_password_baru' || el == 'usr_password_baru2') {
                  $('#modal-profil form#form-profil #' + el).closest('.input-group').append(app);
                } else {
                  $('#modal-profil form#form-profil #' + el).closest('.form-group').append(app);
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
</script>

<body>

  <div class="modal fade text-left" id="modal-profil" tabindex="-1" role="dialog" aria-labelledby="modal-profil-data" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pb-0">
          <form id="form-profil" class="form form-horizontal">
            <div class="form-body">
              <div class="row">
                <div class="col-12 col-md-12">
                  <div class="form-group">
                    <label for="usr_nama_profil">Nama</label>
                    <input type="hidden" id="usr_id_profil" name="usr_id_profil">
                    <input type="hidden" id="usr_level_profil" name="usr_level_profil">
                    <input type="text" class="form-control" name="usr_nama_profil" id="usr_nama_profil" placeholder="Nama User" />
                  </div>
                </div>
                <div class="col-12 col-md-12">
                  <div class="form-group">
                    <label for="usr_username_profil">Username</label>
                    <input type="text" class="form-control" name="usr_username_profil" id="usr_username_profil" placeholder="Username" />
                  </div>
                </div>
                <!-- <div class="col-12 col-md-12">
                  <div class="form-group">
                    <label for="usr_password_lama">Password Lama</label>
                    <div class="input-group">
                      <input type="password" class="form-control" name="usr_password_lama" id="usr_password_lama" placeholder="Masukkan Password Lama" />
                      <span class="input-group-text" id="showHide-usr_password_lama" onclick="showHide('usr_password_lama')">
                        <i class="fa fa-eye"></i>
                      </span>
                    </div>
                  </div>
                </div> -->
                <div class="col-12 col-md-12">
                  <div class="form-group">
                    <label for="usr_password_baru">Password Baru</label>
                    <p class="text-sm p-0 m-0"><small>(Kosongi jika tidak ingin dirubah)</small></p>
                    <div class="input-group">
                      <input type="password" class="form-control" name="usr_password_baru" id="usr_password_baru" placeholder="Masukkan Password Baru" />
                      <span class="input-group-text" id="showHide-usr_password_baru" onclick="showHide('usr_password_baru')">
                        <i class="fa fa-eye"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-12">
                  <div class="form-group">
                    <label for="usr_password_baru2">Ulangi Password Baru</label>
                    <div class="input-group">
                      <input type="password" class="form-control" name="usr_password_baru2" id="usr_password_baru2" placeholder="Ulangi Password Baru" />
                      <span class="input-group-text" id="showHide-usr_password_baru2" onclick="showHide('usr_password_baru2')">
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

  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
          </ul>
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              <img alt="image" src="<?= base_url(); ?>assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
              <div class="d-sm-none d-lg-inline-block">Halo, <?= $_SESSION['usr']['first_name'] . ' (' . $_SESSION['usr']['prt_nama'] . ')' ?></div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="dropdown-title">Log In <br> <?= $selisihWaktu ?> <br> yang lalu</div>
              <a href="javascript:void(0)" class="dropdown-item has-icon" onclick="editProfil('<?= $user['id'] ?>')">
                <i class="far fa-user"></i> Edit Profil
              </a>
              <div class="dropdown-divider"></div>
              <a href="<?= site_url('auth/logout'); ?>" class="dropdown-item has-icon text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
            </div>
          </li>
        </ul>
      </nav>