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
  $(document).ready(function() {
    getKegiatan('', 'kegTotal');
    getKegiatan('proses', 'kegProses');
    getKegiatan('selesai', 'kegSelesai');
    getPerangkat();
  });

  function getKegiatan(jn, elem) {
    $.ajax({
      type: "POST",
      url: base_url() + "admin/home/getKegiatan/" + jn,
      dataType: "json",
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

  //-------------
  //- KEGIATAN BAR CHART -
  //-------------
  var kegiatanChartCanvas = $('#kegiatanChart').get(0).getContext('2d')
  var kegiatanChartData = {
    // label untuk bulan
    labels: [
      <?php foreach ($chartKegiatan['selesai'] as $k => $v) : ?> "<?= $v['label'] ?>",
      <?php endforeach; ?>
    ],
    datasets: [{
        label: 'Kegiatan Progres',
        backgroundColor: '#f39c12',
        borderColor: 'rgba(210, 214, 222, 1)',
        pointRadius: false,
        pointColor: 'rgba(210, 214, 222, 1)',
        pointStrokeColor: '#c1c7d1',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(220,220,220,1)',
        // data: [65, 59, 80, 81, 56, 55, 40]
        data: [
          <?php foreach ($chartKegiatan['progres'] as $k => $v) : ?> '<?= $v['jumlah'] ?>',
          <?php endforeach; ?>
        ]
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
        // data: [28, 48, 40, 19, 86, 27, 90]
        data: [
          <?php foreach ($chartKegiatan['selesai'] as $k => $v) : ?> '<?= $v['jumlah'] ?>',
          <?php endforeach; ?>
        ]
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
            // when the floored value is the same as the value we have a whole number
            if (Math.floor(label) === label) {
              return label;
            }

          },
        }
      }],
    },
  }

  new Chart(kegiatanChartCanvas, {
    type: 'bar',
    data: kegiatanChartData,
    options: kegiatanChartOptions
  })
</script>