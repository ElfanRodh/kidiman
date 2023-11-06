$(document).ready(function () {
  $(".select2").select2({
    allowClear: true,
    // maximumSelectionLength: 1,
    templateResult: templateSelect,
    // language: {
    //   maximumSelected: function() {
    //     return 'Maksimal 1 Pilihan Cabor';
    //   }
    // }
  });
});

function base_url() {
  var pathparts, url;
  if (location.host == "localhost" || location.host == "127.0.0.1" || location.host == "10.0.44.112") {
    pathparts = location.pathname.split("/");
    url = location.protocol + "//" + location.host + "/" + pathparts[1].trim("/") + "/";
  } else {
    url = location.protocol + "//" + location.host + "/";
  }
  return url;
}

function table_language() {
  var _language = {
    sLengthMenu: "_MENU_",
    sSearch: "",
    sInfo: "_START_ to _END_ from _TOTAL_",
    infoEmpty: "",
    infoFiltered: "",
    sZeroRecords: "<b>Data Tidak Ditemukan</b>",
    processing: '<span class="fa fa-refresh" aria-hidden="true"></span> Sedang memuat data',
    decimal: ",",
    thousands: ".",
    sSearchPlaceholder: "Cari ...",
    paginate: {
      previous: '<span class="fa fa-chevron-left" aria-hidden="true"></span>',
      next: '<span class="fa fa-chevron-right" aria-hidden="true"></span>',
    },
  };
  return _language;
}

function tglIndo(tgl) {
  var days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
  var months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

  var tanggal = new Date(tgl).getDate();
  var jam = new Date(tgl).getHours();
  var menit = new Date(tgl).getMinutes();
  var detik = new Date(tgl).getSeconds();
  var xhari = new Date(tgl).getDay();
  var xbulan = new Date(tgl).getMonth();
  var xtahun = new Date(tgl).getYear();

  var hari = days[xhari];
  var bulan = months[xbulan];
  var tahun = xtahun < 1000 ? xtahun + 1900 : xtahun;

  if (jam < 10) {
    jam = "0" + jam;
  }
  if (menit < 10) {
    menit = "0" + menit;
  }
  if (detik < 10) {
    detik = "0" + detik;
  }

  return hari + ", " + tanggal + " " + bulan + " " + tahun;
}

function tglIndoJam(tgl) {
  var days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
  var months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

  var tanggal = new Date(tgl).getDate();
  var jam = new Date(tgl).getHours();
  var menit = new Date(tgl).getMinutes();
  var detik = new Date(tgl).getSeconds();
  var xhari = new Date(tgl).getDay();
  var xbulan = new Date(tgl).getMonth();
  var xtahun = new Date(tgl).getYear();

  var hari = days[xhari];
  var bulan = months[xbulan];
  var tahun = xtahun < 1000 ? xtahun + 1900 : xtahun;

  if (jam < 10) {
    jam = "0" + jam;
  }
  if (menit < 10) {
    menit = "0" + menit;
  }
  if (detik < 10) {
    detik = "0" + detik;
  }

  return hari + ", " + tanggal + " " + bulan + " " + tahun + " " + jam + ":" + menit + ":" + detik;
}

function tglView(tgl) {
  var days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
  var months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

  var tanggal = new Date(tgl).getDate();
  var jam = new Date(tgl).getHours();
  var menit = new Date(tgl).getMinutes();
  var detik = new Date(tgl).getSeconds();
  var xhari = new Date(tgl).getDay();
  var xbulan = new Date(tgl).getMonth() + 1;
  var xtahun = new Date(tgl).getYear();

  var hari = days[xhari];
  var bulan = months[xbulan];
  var tahun = xtahun < 1000 ? xtahun + 1900 : xtahun;

  if (tanggal < 10) {
    tanggal = "0" + tanggal;
  }
  if (xbulan < 10) {
    xbulan = "0" + xbulan;
  }

  if (jam < 10) {
    jam = "0" + jam;
  }
  if (menit < 10) {
    menit = "0" + menit;
  }
  if (detik < 10) {
    detik = "0" + detik;
  }

  return tanggal + "/" + xbulan + "/" + tahun;
}

function rtrwFormat(rtrw) {
  if (rtrw) {
    if (rtrw.length == 1) {
      rtrw = "00" + rtrw;
    } else if (rtrw.length == 2) {
      rtrw = "0" + rtrw;
    }
  }
  return rtrw;
}

function onlyNumber(textbox, inputFilter) {
  ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function (event) {
    textbox.on(event, function () {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    });
  });
}

function getJabatanFilter(elem, id = null, val = null, fixElem = null) {
  var link = base_url() + "admin/home/getJabatan";
  if (id) {
    param = { id: id };
  } else {
    param = {};
  }
  if (fixElem) {
    var elemen = $(elem);
  } else {
    var elemen = $("select#" + elem);
  }
  elemen.html("");
  return $.ajax({
    url: link,
    type: "POST",
    dataType: "json",
    data: param,
    success: function (res) {
      var list = "";
      res.forEach(function (el, ind) {
        list += '<option data-subtext="(' + el.prt_nama + ')" value="' + el.jbt_id + '">' + el.jbt_nama + "</option>";
      });
      $(elemen).html(list);
      $(elemen).val(val).trigger("change");
    },
  });
}

function templateSelect(state) {
  var text = state.text;
  var subtext = "";
  if ($(state.element).attr("data-subtext") && $(state.element).attr("data-subtext") != "null") {
    text = "<strong>" + state.text + "</strong>";
    subtext = $(state.element).attr("data-subtext");
  }
  return $("<div><div>" + text + '</div><div style="font-size: 0.9em; line-height: 1.5em;">' + subtext + "</div></div>");
}

function setSess(name, value) {
  return $.ajax({
    type: "POST",
    url: base_url() + "web/setSess",
    data: {
      sess_name: name,
      sess_value: value,
    },
    dataType: "json",
    success: function (res) {},
  });
}

function maxLength(el, num) {
  $(document)
    .off("keypress", "#" + el)
    .on("keypress", "#" + el, function (event) {
      $(this).attr("maxlength", num);
    });
}

$(document).ready(function () {
  $(document)
    .off("keyup, input", ".uppercase")
    .on("keyup, input", ".uppercase", function (e) {
      var val = $(this).val().toUpperCase();
      $(this).val(val);
    });

  $("body").on("shown.bs.modal", ".modal", function () {
    var sel = $(this).find(".modal-body select");
    sel.each(function () {
      var dropdownParent = $(document.body);
      if ($(this).parents(".modal").length !== 0) {
        dropdownParent = $(this).parents(".modal");
      }
      $(".select2").select2({
        dropdownParent: dropdownParent,
        allowClear: true,
        // maximumSelectionLength: 1,
        // language: {
        //   maximumSelected: function () {
        //     return "Maksimal 1 Pilihan Cabor";
        //   },
        // },
      });
      $(".select2#pcb_cabor").select2({
        dropdownParent: dropdownParent,
        allowClear: true,
        maximumSelectionLength: 1,
        language: {
          maximumSelected: function () {
            return "Maksimal 1 Pilihan Cabor";
          },
        },
      });
    });
  });

  $("body").on("hidden.bs.modal", ".modal", function () {
    var dropdownParent = $(document.body);
    $(".select2").select2({
      dropdownParent: dropdownParent,
      allowClear: true,
      // maximumSelectionLength: 1,
      // language: {
      //   maximumSelected: function () {
      //     return "Maksimal 1 Pilihan Cabor";
      //   },
      // },
    });
    $(".select2#pcb_cabor").select2({
      dropdownParent: dropdownParent,
      allowClear: true,
      maximumSelectionLength: 1,
      language: {
        maximumSelected: function () {
          return "Maksimal 1 Pilihan Cabor";
        },
      },
    });
  });

  $("#show_hide_password .input-group-text").on("click", function (event) {
    event.preventDefault();
    if ($("#show_hide_password input").attr("type") == "text") {
      $("#show_hide_password input").attr("type", "password");
      $("#show_hide_password i").addClass("fa-eye-slash");
      $("#show_hide_password i").removeClass("fa-eye");
    } else if ($("#show_hide_password input").attr("type") == "password") {
      $("#show_hide_password input").attr("type", "text");
      $("#show_hide_password i").removeClass("fa-eye-slash");
      $("#show_hide_password i").addClass("fa-eye");
    }
  });

  $("#show_hide_password_br .input-group-text").on("click", function (event) {
    event.preventDefault();
    if ($("#show_hide_password_br input").attr("type") == "text") {
      $("#show_hide_password_br input").attr("type", "password");
      $("#show_hide_password_br i").addClass("fa-eye-slash");
      $("#show_hide_password_br i").removeClass("fa-eye");
    } else if ($("#show_hide_password_br input").attr("type") == "password") {
      $("#show_hide_password_br input").attr("type", "text");
      $("#show_hide_password_br i").removeClass("fa-eye-slash");
      $("#show_hide_password_br i").addClass("fa-eye");
    }
  });

  $("#show_hide_password_conn .input-group-text").on("click", function (event) {
    event.preventDefault();
    if ($("#show_hide_password_conn input").attr("type") == "text") {
      $("#show_hide_password_conn input").attr("type", "password");
      $("#show_hide_password_conn i").addClass("fa-eye-slash");
      $("#show_hide_password_conn i").removeClass("fa-eye");
    } else if ($("#show_hide_password_conn input").attr("type") == "password") {
      $("#show_hide_password_conn input").attr("type", "text");
      $("#show_hide_password_conn i").removeClass("fa-eye-slash");
      $("#show_hide_password_conn i").addClass("fa-eye");
    }
  });

  $(document)
    .off("click", "#modalPassword button#update-password")
    .on("click", "#modalPassword button#update-password", function (e) {
      let datas = new FormData($("form#form-password")[0]);
      $.ajax({
        type: "POST",
        url: base_url() + "admin/dashboard/updatePassword",
        data: datas,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false,
        success: function (res) {
          if (res.status == 200) {
            swal({
              title: "Sukses",
              text: res.pesan,
              icon: "success",
              confirmButtonClass: "btn btn-main",
              buttonsStyling: false,
            }).then(function (_res_) {
              $("#modalPassword").modal("hide");
              hideModalPass();
            });
          } else {
            if (res.status == 400) {
              let frm = Object.keys(res.pesan);
              let val = Object.values(res.pesan);
              $("form#form-password .invalid-feedback").remove();
              frm.forEach(function (el, ind) {
                if (val[ind] != "") {
                  $("form#form-password #" + el)
                    .removeClass("is-invalid")
                    .addClass("is-invalid");
                  let app = '<div id="' + el + '-error" class="invalid-feedback" for="' + el + '">' + val[ind] + "</div>";
                  $("form#form-password #" + el)
                    .closest(".input-group")
                    .append(app);
                } else {
                  $("form#form-password #" + el)
                    .removeClass("is-invalid")
                    .addClass("is-valid");
                }
              });
            } else {
              swal({
                title: "Error",
                text: res.pesan,
                icon: "error",
                confirmButtonClass: "btn btn-danger",
                buttonsStyling: false,
              });
            }
          }
        },
      });
    });

  function hideModalPass() {
    $("form#form-password .invalid-feedback").remove();
    $("form#form-password #password").val("");
    $("form#form-password #password").removeClass("is-invalid");
    $("form#form-password #password").removeClass("is-valid");
    $("form#form-password #password_br").val("");
    $("form#form-password #password_br").removeClass("is-invalid");
    $("form#form-password #password_br").removeClass("is-valid");
    $("form#form-password #password_conn").val("");
    $("form#form-password #password_conn").removeClass("is-invalid");
    $("form#form-password #password_conn").removeClass("is-valid");
    $("#show_hide_password input").attr("type", "password");
    $("#show_hide_password i").addClass("fa-eye-slash");
    $("#show_hide_password i").removeClass("fa-eye");
    $("#show_hide_password_br input").attr("type", "password");
    $("#show_hide_password_br i").addClass("fa-eye-slash");
    $("#show_hide_password_br i").removeClass("fa-eye");
    $("#show_hide_password_conn input").attr("type", "password");
    $("#show_hide_password_conn i").addClass("fa-eye-slash");
    $("#show_hide_password_conn i").removeClass("fa-eye");
  }

  $(document)
    .off("click", "#modalProfile button#update-profile")
    .on("click", "#modalProfile button#update-profile", function (e) {
      let datas = new FormData($("form#form-profile")[0]);
      $.ajax({
        type: "POST",
        url: base_url() + "admin/dashboard/updateProfile",
        data: datas,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false,
        success: function (res) {
          if (res.status == 200) {
            swal({
              title: "Sukses",
              text: res.pesan,
              icon: "success",
              confirmButtonClass: "btn btn-main",
              buttonsStyling: false,
            }).then(function (_res_) {
              $("#modalProfile").modal("hide");
              //  hideModalPass();
              location.reload();
            });
          } else {
            if (res.status == 400) {
              let frm = Object.keys(res.pesan);
              let val = Object.values(res.pesan);
              $("form#form-profile .invalid-feedback").remove();
              frm.forEach(function (el, ind) {
                if (val[ind] != "") {
                  $("form#form-profile #" + el)
                    .removeClass("is-invalid")
                    .addClass("is-invalid");
                  let app = '<div id="' + el + '-error" class="invalid-feedback" for="' + el + '">' + val[ind] + "</div>";
                  $("form#form-profile #" + el)
                    .closest(".form-group")
                    .append(app);
                } else {
                  $("form#form-profile #" + el)
                    .removeClass("is-invalid")
                    .addClass("is-valid");
                }
              });
            } else {
              swal({
                title: "Error",
                text: res.pesan,
                icon: "error",
                confirmButtonClass: "btn btn-danger",
                buttonsStyling: false,
              });
            }
          }
        },
      });
    });

  function readURL(input, image) {
    set_null_image(image + "");
    var FileUploadPath = input.value;
    var Extension = FileUploadPath.substring(FileUploadPath.lastIndexOf(".") + 1).toLowerCase();

    if (Extension == "gif" || Extension == "png" || Extension == "bmp" || Extension == "jpeg" || Extension == "jpg") {
      if (input.files && input.files[0]) {
        var size = input.files[0].size;
        var name = input.files[0].name;
        if (size > 2000000) {
          $(image + "_error").html("Ukuran Maksimum 2Mb");
          $(image + "_error").show();
          $(image).addClass("is-invalid");
          $(image).val("");
        } else {
          $(image + "-label").html(name);
          var reader = new FileReader();

          $(image + "-display").html(`<img id="blah" src="" alt="Mengambil Foto ..." class="mt-2" />`);
          reader.onload = function (e) {
            $("#blah").attr("src", e.target.result).height(200);
          };

          reader.readAsDataURL(input.files[0]);
          // $('#blah').show();
          $(image).addClass("is-valid");
        }
      }
    } else {
      $(image + "_error").html("Foto hanya boleh (GIF, PNG, JPG, JPEG and BMP)");
      $(image + "_error").show();
      $(image).addClass("is-invalid");
      $(image).val("");
    }
  }

  function set_null_image(image) {
    $(image).removeClass("is-valid");
    $(image).removeClass("is-invalid");
    $(image + "-display").html("");
    $(image + "_error").html("");
    $(image + "-label").html("Pilih file");
  }
});
