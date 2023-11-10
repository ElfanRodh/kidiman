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
            <h4>Grafik</h4>
          </div>
          <div class="card-body">
            <canvas id="myChart" height="158"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h4>Invoices</h4>
            <div class="card-header-action">
              <a href="#" class="btn btn-danger">View More <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive table-invoice">
              <table class="table table-striped">
                <tr>
                  <th>Invoice ID</th>
                  <th>Customer</th>
                  <th>Status</th>
                  <th>Due Date</th>
                  <th>Action</th>
                </tr>
                <tr>
                  <td><a href="#">INV-87239</a></td>
                  <td class="font-weight-600">Kusnadi</td>
                  <td>
                    <div class="badge badge-warning">Unpaid</div>
                  </td>
                  <td>July 19, 2018</td>
                  <td>
                    <a href="#" class="btn btn-primary">Detail</a>
                  </td>
                </tr>
                <tr>
                  <td><a href="#">INV-48574</a></td>
                  <td class="font-weight-600">Hasan Basri</td>
                  <td>
                    <div class="badge badge-success">Paid</div>
                  </td>
                  <td>July 21, 2018</td>
                  <td>
                    <a href="#" class="btn btn-primary">Detail</a>
                  </td>
                </tr>
                <tr>
                  <td><a href="#">INV-76824</a></td>
                  <td class="font-weight-600">Muhamad Nuruzzaki</td>
                  <td>
                    <div class="badge badge-warning">Unpaid</div>
                  </td>
                  <td>July 22, 2018</td>
                  <td>
                    <a href="#" class="btn btn-primary">Detail</a>
                  </td>
                </tr>
                <tr>
                  <td><a href="#">INV-84990</a></td>
                  <td class="font-weight-600">Agung Ardiansyah</td>
                  <td>
                    <div class="badge badge-warning">Unpaid</div>
                  </td>
                  <td>July 22, 2018</td>
                  <td>
                    <a href="#" class="btn btn-primary">Detail</a>
                  </td>
                </tr>
                <tr>
                  <td><a href="#">INV-87320</a></td>
                  <td class="font-weight-600">Ardian Rahardiansyah</td>
                  <td>
                    <div class="badge badge-success">Paid</div>
                  </td>
                  <td>July 28, 2018</td>
                  <td>
                    <a href="#" class="btn btn-primary">Detail</a>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card card-hero">
          <div class="card-header">
            <div class="card-icon">
              <i class="far fa-question-circle"></i>
            </div>
            <h4>14</h4>
            <div class="card-description">Customers need help</div>
          </div>
          <div class="card-body p-0">
            <div class="tickets-list">
              <a href="#" class="ticket-item">
                <div class="ticket-title">
                  <h4>My order hasn't arrived yet</h4>
                </div>
                <div class="ticket-info">
                  <div>Laila Tazkiah</div>
                  <div class="bullet"></div>
                  <div class="text-primary">1 min ago</div>
                </div>
              </a>
              <a href="#" class="ticket-item">
                <div class="ticket-title">
                  <h4>Please cancel my order</h4>
                </div>
                <div class="ticket-info">
                  <div>Rizal Fakhri</div>
                  <div class="bullet"></div>
                  <div>2 hours ago</div>
                </div>
              </a>
              <a href="#" class="ticket-item">
                <div class="ticket-title">
                  <h4>Do you see my mother?</h4>
                </div>
                <div class="ticket-info">
                  <div>Syahdan Ubaidillah</div>
                  <div class="bullet"></div>
                  <div>6 hours ago</div>
                </div>
              </a>
              <a href="<?= base_url(); ?>dist/features_tickets" class="ticket-item ticket-more">
                View All <i class="fas fa-chevron-right"></i>
              </a>
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
</script>