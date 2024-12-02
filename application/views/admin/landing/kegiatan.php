<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />

  <title>Ki Diman Perangkat Desa Perang</title>
  <meta content="" name="description" />
  <meta content="" name="keywords" />

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon" />
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet" />

  <!-- Vendor CSS Files -->
  <link href="<?= base_url("assets/landing/modules/aos/aos.css"); ?>" rel="stylesheet" />
  <!-- <link href="<?= base_url("assets/landing/modules/bootstrap/css/bootstrap.min.css"); ?>" rel="stylesheet" /> -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="<?= base_url("assets/landing/modules/bootstrap-icons/bootstrap-icons.css"); ?>" rel=" stylesheet" />
  <link href="<?= base_url("assets/landing/modules/boxicons/css/boxicons.min.css"); ?>" rel=" stylesheet" />
  <link href="<?= base_url("assets/landing/modules/glightbox/css/glightbox.min.css"); ?>" rel=" stylesheet" />
  <link href="<?= base_url("assets/landing/modules/remixicon/remixicon.css"); ?>" rel=" stylesheet" />
  <link href="<?= base_url("assets/landing/modules/swiper/swiper-bundle.min.css"); ?>" rel=" stylesheet" />

  <!-- Admin CSS -->
  <link rel="stylesheet" href="<?= base_url("assets/modules/fontawesome/css/all.min.css"); ?>">
  <link rel="stylesheet" href="<?= base_url("assets/modules/datatables/datatables.css"); ?>">
  <link rel="stylesheet" href="<?= base_url("assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css"); ?>">
  <link rel="stylesheet" href="<?= base_url("assets/modules/datatables/Responsive-2.2.1/css/responsive.bootstrap4.css"); ?>">
  <link rel="stylesheet" href="<?= base_url("assets/modules/select2/dist/css/select2.min.css"); ?>">
  <link rel="stylesheet" href="<?= base_url("assets/modules/bootstrap-daterangepicker/daterangepicker.css"); ?>">
  <link rel="stylesheet" href="<?= base_url("assets/css/components.css"); ?>">
  <link rel="stylesheet" href="<?= base_url("assets/style.css"); ?>">
  <!-- <link rel="stylesheet" href="<?= base_url("assets/css/style.css"); ?>"> -->
  <link rel="stylesheet" href="<?= base_url("assets/css/custom.css"); ?>">

  <!-- Template Main CSS File -->
  <link href=<?= base_url("assets/landing/css/style.css"); ?> rel="stylesheet" />

  <script src="<?= base_url("assets/modules/jquery.min.js"); ?>"></script>

  <style>
    body {
      font-size: 14px;
    }

    #header {
      background: linear-gradient(270deg, #39bdc4 -17.47%, #47da96 98.16%);
    }

    .container,
    .container-fluid,
    .container-lg,
    .container-md,
    .container-sm,
    .container-xl,
    .container-xxl {
      --bs-gutter-x: 1.5rem;
      --bs-gutter-y: 0;
      width: 100%;
      padding-right: calc(var(--bs-gutter-x)* .5);
      padding-left: calc(var(--bs-gutter-x)* .5);
      margin-right: auto;
      margin-left: auto;
    }

    @media (min-width: 1400px) {

      .container,
      .container-lg,
      .container-md,
      .container-sm,
      .container-xl,
      .container-xxl {
        max-width: 1320px;
      }
    }

    .input-group-text,
    select.form-control:not([size]):not([multiple]),
    .form-control:not(.form-control-sm):not(.form-control-lg) {
      font-size: 14px;
      padding: 10px 15px;
      height: 42px;
    }
  </style>
</head>

<body>
  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">
      <h1 class="logo mr-auto me-auto"><a href="<?= site_url() ?>">KI DIMAN PERANGKAT DESA</a></h1>
      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href="index2.html" class="logo mr-auto me-auto"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link" href="<?= site_url() ?>">Home</a></li>
          <li><a class="nav-link scrollto" href="#about">Tentang</a></li>
          <li><a class="nav-link active" href="<?= site_url('web/kegiatan') ?>">Kegiatan</a></li>
          <li><a class="nav-link scrollto" href="#team">Perangkat</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>
      <!-- .navbar -->
    </div>
  </header>
  <!-- End Header -->

  <div id="main" class="pt-5 mt-5">
    <!-- ======= About Us Section ======= -->
    <section id="" class="">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>Kegiatan Perangkat Desa</h2>
        </div>

        <div class="row content">
          <div class="col-12">
            <section class="section">

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
                <div class="col-12 col-md-4">
                  <div class="form-group">
                    <label for="fil_status">Status</label>
                    <select class="form-control select2" data-width="100%" data-allow-clear="true" data-placeholder="Pilih Status" id="fil_status" name="fil_status">
                      <option value="all">Semua</option>
                      <option value="proses">Proses</option>
                      <option value="selesai">Selesai</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="section-body">
                <div class="row">
                  <div class="col-12">
                    <div class="card">
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
        </div>
      </div>
    </section>
    <!-- End About Us Section -->
  </div>

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container footer-bottom clearfix">
      <div class="copyright">
        Copyright &copy; 2023
      </div>
    </div>
  </footer>
  <!-- End Footer -->

  <div id="preloader"></div>
  <!-- IKM -->
  <a href="#" id="ikm" class="ikm d-flex align-items-center justify-content-center"><i class="bi bi-chat-heart"></i></a>
  <!-- IKM -->
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

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

</body>

</html>
<!-- Vendor JS Files -->
<script src="<?= base_url("assets/landing/modules/aos/aos.js"); ?>"></script>
<script src="<?= base_url("assets/landing/modules/glightbox/js/glightbox.min.js"); ?>"></script>
<script src="<?= base_url("assets/landing/modules/isotope-layout/isotope.pkgd.min.js"); ?>"></script>
<script src="<?= base_url("assets/landing/modules/swiper/swiper-bundle.min.js"); ?>"></script>
<script src="<?= base_url("assets/landing/modules/waypoints/noframework.waypoints.js"); ?>"></script>
<script src="<?= base_url("assets/landing/modules/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>

<!-- Template Main JS File -->
<script src="<?= base_url("assets/landing/js/main.js"); ?>"></script>
<?php $this->load->view('dist/_partials/js'); ?>
<?php $this->load->view('admin/landing/ikm'); ?>

<script>
  var fil_jabatan, fil_tanggal, fil_status;
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
        url: base_url() + "web/viewDataKegiatan",
        data: function(posts) {
          posts.fil_jabatan = fil_jabatan ?? null;
          posts.fil_tanggal = fil_tanggal ?? null;
          posts.fil_status = fil_status ?? null;
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

    getJabatan('fil_jabatan');

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

    $(document).off("change", "select#fil_status")
      .on("change", "select#fil_status", function(e) {
        e.preventDefault();
        fil_status = $(this).val()
        tb_data.ajax.reload(null, true);
      });

    $('input#fil_tanggal').on('cancel.daterangepicker', function(ev, picker) {
      $('input#fil_tanggal').val(null).trigger('change');
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

  function getJabatan(elem, id = null, val = null, fixElem = null) {
    var link = base_url() + "web/getJabatan";
    if (id) {
      param = {
        id: id
      };
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
      success: function(res) {
        var list = "";
        res.forEach(function(el, ind) {
          list += '<option data-subtext="(' + el.prt_nama + ')" value="' + el.jbt_id + '">' + el.jbt_nama + "</option>";
        });
        $(elemen).html(list);
        $(elemen).val(val).trigger("change");
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
      url: base_url() + "web/detailKegiatan",
      data: {
        keg_id: keg_id,
      },
      dataType: "json",
      success: function(res) {
        // $("#modal-kegiatan").modal({
        //   backdrop: false
        // });
        $("#modal-kegiatan").modal('show')
        $('#modal-kegiatan #keg-text').html('Kegiatan : <br>' + res.keg_nama + ' (' + res.keg_progres + '%)')

        var acc = ''
        var prog = '';
        $.each(res.progres, function(idx, val) {
          if (val.prog_bukti) {
            // Periksa ekstensi file
            var fileExtension = val.prog_bukti.split('.').pop().toLowerCase();

            // Jika file adalah gambar
            if (fileExtension === 'jpg' || fileExtension === 'jpeg' || fileExtension === 'png' || fileExtension === 'gif') {
              var bukti = `<img src="` + val.prog_bukti + `" class="img-fluid mb-2">`;
            }
            // Jika file adalah PDF
            else if (fileExtension === 'pdf') {
              var bukti = `<embed src="` + val.prog_bukti + `" type="application/pdf" width="100%" height="500px" class="mb-2">`;
            }
            // Jika tipe file tidak dikenal
            else {
              var bukti = `<p>File tidak dapat ditampilkan.</p>`;
            }
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
                    </tr>`
        });

        acc += `<table class="table table-striped">
                    <thead>
                      <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Bukti / Progres / Tanggal</th>
                        <th class="text-center">Keterangan</th>
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

  function convertTanggal(date) {
    var formattedDate = String(date.getDate()).padStart(2, '0') + "-" +
      String(date.getMonth() + 1).padStart(2, '0') + "-" +
      date.getFullYear();

    return formattedDate;
  }

  function truncateWords(str, wordLimit = 10) {
    // Pecah string menjadi array berdasarkan spasi
    const words = str.split(' ');

    // Ambil sejumlah kata yang dibatasi oleh wordLimit
    const truncated = words.slice(0, wordLimit);

    // Gabungkan kembali array kata menjadi string
    let result = truncated.join(' ');

    // Jika jumlah kata lebih dari batas, tambahkan "..." di akhir
    if (words.length > wordLimit) {
      result += '...';
    }

    return result;
  }
</script>