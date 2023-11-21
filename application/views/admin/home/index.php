<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-12">
        <div class="card card-statistic-2">
          <div class="card-icon shadow-primary bg-info">
            <i class="fas fa-tasks"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Total Kegiatan</h4>
            </div>
            <div class="card-body">
              <div id="kegTotal">0</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-12">
        <div class="card card-statistic-2">
          <div class="card-icon shadow-primary bg-warning">
            <i class="fas fa-spinner"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Kegiatan Proses</h4>
            </div>
            <div class="card-body">
              <div id="kegProses">0</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-12">
        <div class="card card-statistic-2">
          <div class="card-icon shadow-primary bg-primary">
            <i class="fas fa-check"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Kegiatan Selesai</h4>
            </div>
            <div class="card-body">
              <div id="kegSelesai">0</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-12">
        <div class="card card-statistic-2">
          <div class="card-icon shadow-primary bg-danger">
            <i class="fas fa-users"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Total Perangkat</h4>
            </div>
            <div class="card-body">
              <div id="prtTotal">0</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            <h4>Filter</h4>
          </div>
          <div class="card-body">
            <form action="">
              <div class="row">
                <div class="col-12 col-lg-4">
                  <select class="form form-control" name="fil_tahun" id="fil_tahun"></select>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div> -->

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

    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            <h4>Data Kegiatan Tahun 2023</h4>
          </div>
          <div class="card-body">
            <div class="chart">
              <canvas id="kegiatanChart" style="min-height: 250px; height: 320px; max-height: 400px; max-width: 100%;"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('dist/_partials/footer'); ?>

<script type="text/javascript">
  var fil_jabatan, fil_tanggal;
  var chartKegiatan;
  var kegiatanChartCanvas = $('#kegiatanChart').get(0).getContext('2d')

  $(document).ready(function() {
    getJabatanFilter('fil_jabatan');
    getKegiatan('', 'kegTotal');
    getKegiatan('proses', 'kegProses');
    getKegiatan('selesai', 'kegSelesai');
    getPerangkat();
    getChart();

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

    $(document).off("change", "select#fil_jabatan")
      .on("change", "select#fil_jabatan", function(e) {
        e.preventDefault();
        fil_jabatan = $(this).val()
        getKegiatan('', 'kegTotal');
        getKegiatan('proses', 'kegProses');
        getKegiatan('selesai', 'kegSelesai');
        getChart();
      });

    $(document).off("change", "input#fil_tanggal")
      .on("change", "input#fil_tanggal", function(e) {
        e.preventDefault();
        fil_tanggal = $(this).val()
        getKegiatan('', 'kegTotal');
        getKegiatan('proses', 'kegProses');
        getKegiatan('selesai', 'kegSelesai');
        getChart();
      });

    $('input#fil_tanggal').on('cancel.daterangepicker', function(ev, picker) {
      $('input#fil_tanggal').val(null).trigger('change');
    });
  });

  function getKegiatan(jn, elem) {
    $.ajax({
      type: "POST",
      url: base_url() + "admin/home/getKegiatan/" + jn,
      dataType: "json",
      data: {
        fil_jabatan: fil_jabatan ?? null,
        fil_tanggal: fil_tanggal ?? null
      },
      success: function(res) {
        $("#" + elem).html(res);
      }
    });
  }

  function getPerangkat() {
    $.ajax({
      type: "GET",
      url: base_url() + 'admin/home/prtTotal',
      dataType: "json",
      success: function(response) {
        $("#prtTotal").html(response);
      }
    });
  }

  function getChart() {
    $.ajax({
      type: "POST",
      url: base_url() + 'admin/home/getChartKegiatan',
      data: {
        fil_jabatan: fil_jabatan ?? null,
        fil_tanggal: fil_tanggal ?? null
      },
      dataType: "json",
      beforeSend: function() {
        $('#kegiatanChart').html(null)
      },
      success: function(res) {
        var selesai = []
        var progres = []
        selesai.label = res.selesai.label
        selesai.data = res.selesai.jumlah

        progres.label = res.progres.label
        progres.data = res.progres.jumlah

        createChart(selesai, progres);
      }
    });
  }

  function createChart(selesai, progres) {
    if (chartKegiatan) {
      chartKegiatan.destroy();
    }

    var kegiatanChartData = {
      labels: selesai.label,
      datasets: [{
          label: 'Kegiatan Progres',
          backgroundColor: '#f39c12',
          borderColor: 'rgba(210, 214, 222, 1)',
          pointRadius: false,
          pointColor: 'rgba(210, 214, 222, 1)',
          pointStrokeColor: '#c1c7d1',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data: progres.data
        },
        {
          label: 'Kegiatan Selesai',
          backgroundColor: '#00a65a',
          borderColor: 'rgba(60,141,188,0.8)',
          pointRadius: false,
          pointColor: '#3b8bba',
          pointStrokeColor: 'rgba(60,141,188,1)',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data: selesai.data
        }
      ]
    }

    var kegiatanChartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      datasetFill: false,
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true,
            userCallback: function(label, index, labels) {
              if (Math.floor(label) === label) {
                return label;
              }

            },
          }
        }],
      },
    }

    chartKegiatan = new Chart(kegiatanChartCanvas, {
      type: 'bar',
      data: kegiatanChartData,
      options: kegiatanChartOptions
    })
  }
</script>