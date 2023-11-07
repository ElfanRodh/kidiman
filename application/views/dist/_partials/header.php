<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?= $title; ?> &mdash; Kidiman Perang</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url("assets/modules/bootstrap/css/bootstrap.min.css"); ?>">
  <link rel="stylesheet" href="<?= base_url("assets/modules/fontawesome/css/all.min.css"); ?>">

  <link rel="stylesheet" href="<?= base_url("assets/modules/datatables/datatables.css"); ?>">
  <link rel="stylesheet" href="<?= base_url("assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css"); ?>">
  <link rel="stylesheet" href="<?= base_url("assets/modules/datatables/Responsive-2.2.1/css/responsive.bootstrap4.css"); ?>">
  <link rel="stylesheet" href="<?= base_url("assets/modules/select2/dist/css/select2.min.css"); ?>">
  <link rel="stylesheet" href="<?= base_url("assets/modules/summernote/summernote-bs4.css"); ?>">
  <link rel="stylesheet" href="<?= base_url("assets/modules/bootstrap-daterangepicker/daterangepicker.css"); ?>">

  <!-- Template CSS -->
  <link rel="stylesheet" href="<?= base_url("assets/modules/izitoast/css/iziToast.min.css"); ?>">
  <link rel="stylesheet" href="<?= base_url("assets/css/components.css"); ?>">
  <link rel="stylesheet" href="<?= base_url("assets/style.css"); ?>">
  <link rel="stylesheet" href="<?= base_url("assets/css/style.css"); ?>">
  <link rel="stylesheet" href="<?= base_url("assets/css/custom.css"); ?>">

  <script src="<?= base_url("assets/modules/jquery.min.js"); ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;0,900;1,400;1,700;1,900&display=swap" rel="stylesheet">
</head>

<?php
if ($this->uri->segment(2) == "layout_transparent") {
  $this->load->view('dist/_partials/layout-2');
  $this->load->view('dist/_partials/sidebar-2');
} elseif ($this->uri->segment(2) == "layout_top_navigation") {
  $this->load->view('dist/_partials/layout-3');
  $this->load->view('dist/_partials/navbar');
} elseif ($this->uri->segment(2) != "login" && $this->uri->segment(2) != "auth_forgot_password" && $this->uri->segment(2) != "auth_register" && $this->uri->segment(2) != "auth_reset_password" && $this->uri->segment(2) != "errors_503" && $this->uri->segment(2) != "errors_403" && $this->uri->segment(2) != "errors_404" && $this->uri->segment(2) != "errors_500" && $this->uri->segment(2) != "utilities_contact" && $this->uri->segment(2) != "utilities_subscribe") {
  $this->load->view('dist/_partials/layout');
  $this->load->view('dist/_partials/sidebar');
}
?>