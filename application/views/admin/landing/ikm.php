<style>
  /*--------------------------------------------------------------
# IKM button
--------------------------------------------------------------*/
  .ikm {
    position: fixed;
    right: 65px;
    bottom: 15px;
    z-index: 996;
    background: linear-gradient(270deg, #39bdc4 -17.47%, #47da96 98.16%);
    width: 45px;
    height: 45px;
    border-radius: 50px;
    transition: all 0.4s;
    border: 1px solid #fff;
  }

  .ikm i {
    font-size: 24px;
    color: #fff;
    line-height: 0;
  }

  .ikm:hover {
    background: #dc3545;
    color: #fff;
  }

  .ikm.active {
    visibility: visible;
    opacity: 1;
  }

  .fa-chat-2x {
    font-size: 28px !important;
  }

  label {
    font-weight: bold;
  }

  .sar {
    height: 3em;
    cursor: pointer;
  }

  .modal-dialog {
    max-height: 90%
  }

  @media (min-width: 992px) and (min-aspect-ratio: 8 / 5) {
    .modal-portal .modal-chat {
      min-height: 65vh;
      padding: 1.2em 2em !important;
      z-index: 11;
    }
  }

  table {
    background-color: #fff0
  }
</style>

<div class="modal fade" id="ikmModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ikmModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="ikmModalLabel">KRITIK & SARAN</h1>
        <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      <div class="modal-body modal-ikm">
        <form id="form-ikm">
          <div class="row">
            <div class="col-12 mt-4 mt20">
              <div class="form-group">
                <label for="sar_nama">Nama</label>
                <input type="text" name="sar_nama" id="sar_nama" class="form-control" placeholder="Masukkan Nama">
                <input type="hidden" name="sar_url" id="sar_url" value="<?= current_url(); ?>">
              </div>
            </div>
            <div class="col-12 mt-4 mt20">
              <div class="form-group">
                <label for="sar_email">E-mail</label>
                <input type="email" name="sar_email" id="sar_email" class="form-control" placeholder="Masukkan E-mail">
              </div>
            </div>
            <div class="col-12 mt-4 mt20">
              <div class="form-group">
                <label for="sar_no_hp">No. HP</label>
                <input type="email" name="sar_no_hp" id="sar_no_hp" class="form-control" placeholder="Masukkan No. HP">
              </div>
            </div>
            <div class="col-12 mt-4 mt20">
              <div class="form-group">
                <label for="sar_kritik">Kritik & Saran</label>
                <textarea name="sar_kritik" id="sar_kritik" class="form-control" placeholder="Masukkan Kritik & Saran" rows="5"></textarea>
              </div>
            </div>
            <!-- <div class="col-12 mt-4 mt20">
              <div class="form-group">
                <div class="g-recaptcha" id="g-recaptcha" data-sitekey="<?php echo $this->config->item('recaptcha_site_key') ?>"></div>
              </div>
            </div> -->
            <div class="col-12 mt-4 mt20">
              <div class="form-group">
                <label for="sar_rating">Bagaimana Tanggapanmu?</label>
                <div class="m10 m-2">
                  <input type="hidden" id="sar_rating" name="sar_rating" value="0">

                  <table>
                    <tr>
                      <td class="text-center mr-2 mr5" style="width: 10em; vertical-align: top;">
                        <input type="hidden" id="sar4_hidden" value="4">
                        <img src="<?= base_url() ?>/assets/img/ikm/sar4.png" onclick="setRating(this.id);" id="sar4" class="sar">
                      </td>
                      <td class="text-center mr-2 mr5" style="width: 10em; vertical-align: top;">
                        <input type="hidden" id="sar3_hidden" value="3">
                        <img src="<?= base_url() ?>/assets/img/ikm/sar3.png" onclick="setRating(this.id);" id="sar3" class="sar">
                      </td>
                      <td class="text-center mr-2 mr5" style="width: 10em; vertical-align: top;">
                        <input type="hidden" id="sar2_hidden" value="2">
                        <img src="<?= base_url() ?>/assets/img/ikm/sar2.png" onclick="setRating(this.id);" id="sar2" class="sar">
                      </td>
                      <td class="text-center mr-2 mr5" style="width: 10em; vertical-align: top;">
                        <input type="hidden" id="sar1_hidden" value="1">
                        <img src="<?= base_url() ?>/assets/img/ikm/sar1.png" onclick="setRating(this.id);" id="sar1" class="sar">
                      </td>
                    </tr>
                    <tr>
                      <td class="text-center mr-2 mr5" style="width: 10em; vertical-align: top;">
                        <span>Sangat Puas</span>
                      </td>
                      <td class="text-center mr-2 mr5" style="width: 10em; vertical-align: top;">
                        <span>Puas</span>
                      </td>
                      <td class="text-center mr-2 mr5" style="width: 10em; vertical-align: top;">
                        <span>Cukup Puas</span>
                      </td>
                      <td class="text-center mr-2 mr5" style="width: 10em; vertical-align: top;">
                        <span>Tidak Puas</span>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="btn-add-ikm" class="btn btn-lg btn-success">KIRIM</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="ikmModalNotif" tabindex="-1" role="dialog" aria-labelledby="ikmModalNotifLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="w-100 text-center">
          <h2 class="modal-title" id="chatModalNotifLabel">Terimakasih Sudah Mengirimkan Kritik dan Saran</h2>
        </div>
        <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      <div class="modal-body p-0 m-0"></div>
    </div>
  </div>
</div>

<script>
  $(document).off("click", "#ikm").on("click", "#ikm", function(event) {
    $('.form-group').removeClass('has-error');
    $('input').removeClass('is-invalid');
    $('textarea').removeClass('is-invalid');
    $('.error').remove();
    $('#ikmModal').modal('show')
  });

  $(document).off("click", "#btn-add-ikm").on("click", "#btn-add-ikm", function(e) {
    e.preventDefault();
    storeIKM()
  });

  function changeRating(id) {
    var cname = $('#' + id).attr('class');
    var ab = $('#' + id + '_hidden').val();
    // $('#'+cname+'_rating').val(ab)

    $('#' + id).height('62')
  }

  function backRating(id) {
    $('.sar').height('48')
  }

  function setRating(id) {
    $('.sar').height('48')
    var cname = $('#' + id).attr('class');
    var ab = $('#' + id + '_hidden').val();
    $('#' + cname + '_rating').val(ab)
    $('#' + id).height('62')
  }

  function storeIKM() {
    var form_data = new FormData($('#form-ikm')[0]);
    $.ajax({
      type: "POST",
      url: '<?= site_url('Web/storeIKM') ?>',
      data: form_data,
      dataType: "json",
      contentType: false,
      processData: false,
      success: function(res) {
        $('.form-group').removeClass('has-error');
        $('.error').remove();

        if (res.status == 1) {
          // window.open(
          //   res.url_wa,
          //   '_blank'
          // );
          setTimeout(function() {
            $('#ikmModal').modal('hide');
            $('#ikmModalNotifLabel').html(res.message);
            $('#ikmModalNotif').modal('show');
            // setTimeout(() => {
            //   $('#ikmModalNotif').modal('hide');
            // }, 4000);
          }, 300)
        } else if (res.status == 0) {
          $('.form-group').removeClass('has-error');
          $('.error').remove();
          $('input').removeClass('is-invalid');
          $('textarea').removeClass('is-invalid');
          res.form.forEach(function(el, ind) {
            $('#' + el).closest('.form-group').removeClass('has-success').addClass('has-error');
            $('#' + el).addClass('is-invalid');
            var app = '<label id="' + el + '-error" class="error invalid-feedback d-block" for="' + el + '">' + res.message[ind] + '</label>';
            $('#' + el).closest('.form-group').append(app);
          });
        } else if (res.status == 2) {
          setTimeout(function() {
            $('#ikmModal').modal('hide');
            $('#ikmModalNotifLabel').html(res.message);
            $('#ikmModalNotif').modal('show');
            // setTimeout(() => {
            //   $('#ikmModalNotif').modal('hide');
            // }, 4000);
          }, 300)
        }
        // grecaptcha.reset()
      }
    });
  }
</script>