<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="<?= site_url(); ?>">KIDIMAN</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="<?= site_url(); ?>">KDM</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">Dashboard</li>
      <li class="<?= $this->uri->segment(1) == '' || $this->uri->segment(2) == 'index' ? 'active' : ''; ?>">
        <a class="nav-link" href="<?= site_url(); ?>">
          <i class="fa fa-fire"></i> <span>Dashboard</span>
        </a>
      </li>
      <li class="menu-header">Master Data</li>
      <li class="<?= $this->uri->segment(2) == 'perangkat' ? 'active' : ''; ?>">
        <a class="nav-link" href="<?= site_url('admin/perangkat'); ?>">
          <i class="fa fa-users"></i> <span>Perangkat Desa</span>
        </a>
      </li>
      <li class="<?= $this->uri->segment(2) == 'user' ? 'active' : ''; ?>">
        <a class="nav-link" href="<?= site_url('admin/user'); ?>">
          <i class="fa fa-user-shield"></i> <span>Data User</span>
        </a>
      </li>
      <li class="menu-header">Perangkat</li>
      <li class="<?= $this->uri->segment(2) == 'tugas' ? 'active' : ''; ?>">
        <a class="nav-link" href="<?= site_url('admin/tugas'); ?>">
          <i class="fa fa-box"></i> <span>Tugas Pokok</span>
        </a>
      </li>
      <li class="<?= $this->uri->segment(2) == 'fungsi' ? 'active' : ''; ?>">
        <a class="nav-link" href="<?= site_url('admin/fungsi'); ?>">
          <i class="fa fa-hands"></i> <span>Fungsi</span>
        </a>
      </li>
      <li class="<?= $this->uri->segment(2) == 'kegiatan' ? 'active' : ''; ?>">
        <a class="nav-link" href="<?= site_url('admin/kegiatan'); ?>">
          <i class="fa fa-tasks"></i> <span>Kegiatan</span>
        </a>
      </li>
      <li class="menu-header">Stisla</li>
      <li class="dropdown <?= $this->uri->segment(2) == 'components_article' || $this->uri->segment(2) == 'components_avatar' || $this->uri->segment(2) == 'components_chat_box' || $this->uri->segment(2) == 'components_empty_state' || $this->uri->segment(2) == 'components_gallery' || $this->uri->segment(2) == 'components_hero' || $this->uri->segment(2) == 'components_multiple_upload' || $this->uri->segment(2) == 'components_pricing' || $this->uri->segment(2) == 'components_statistic' || $this->uri->segment(2) == 'components_tab' || $this->uri->segment(2) == 'components_table' || $this->uri->segment(2) == 'components_user' || $this->uri->segment(2) == 'components_wizard' ? 'active' : ''; ?>">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-th-large"></i> <span>Components</span></a>
        <ul class="dropdown-menu">
          <li class="<?= $this->uri->segment(2) == 'components_article' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/components_article">Article</a></li>
          <li class="<?= $this->uri->segment(2) == 'components_avatar' ? 'active' : ''; ?>"><a class="nav-link beep beep-sidebar" href="<?= base_url(); ?>dist/components_avatar">Avatar</a></li>
          <li class="<?= $this->uri->segment(2) == 'components_chat_box' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/components_chat_box">Chat Box</a></li>
          <li class="<?= $this->uri->segment(2) == 'components_empty_state' ? 'active' : ''; ?>"><a class="nav-link beep beep-sidebar" href="<?= base_url(); ?>dist/components_empty_state">Empty State</a></li>
          <li class="<?= $this->uri->segment(2) == 'components_gallery' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/components_gallery">Gallery</a></li>
          <li class="<?= $this->uri->segment(2) == 'components_hero' ? 'active' : ''; ?>"><a class="nav-link beep beep-sidebar" href="<?= base_url(); ?>dist/components_hero">Hero</a></li>
          <li class="<?= $this->uri->segment(2) == 'components_multiple_upload' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/components_multiple_upload">Multiple Upload</a></li>
          <li class="<?= $this->uri->segment(2) == 'components_pricing' ? 'active' : ''; ?>"><a class="nav-link beep beep-sidebar" href="<?= base_url(); ?>dist/components_pricing">Pricing</a></li>
          <li class="<?= $this->uri->segment(2) == 'components_statistic' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/components_statistic">Statistic</a></li>
          <li class="<?= $this->uri->segment(2) == 'components_tab' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/components_tab">Tab</a></li>
          <li class="<?= $this->uri->segment(2) == 'components_table' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/components_table">Table</a></li>
          <li class="<?= $this->uri->segment(2) == 'components_user' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/components_user">User</a></li>
          <li class="<?= $this->uri->segment(2) == 'components_wizard' ? 'active' : ''; ?>"><a class="nav-link beep beep-sidebar" href="<?= base_url(); ?>dist/components_wizard">Wizard</a></li>
        </ul>
      </li>
      <li class="dropdown <?= $this->uri->segment(2) == 'forms_advanced_form' || $this->uri->segment(2) == 'forms_editor' || $this->uri->segment(2) == 'forms_validation' ? 'active' : ''; ?>">
        <a href="#" class="nav-link has-dropdown"><i class="far fa-file-alt"></i> <span>Forms</span></a>
        <ul class="dropdown-menu">
          <li class="<?= $this->uri->segment(2) == 'forms_advanced_form' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/forms_advanced_form">Advanced Form</a></li>
          <li class="<?= $this->uri->segment(2) == 'forms_editor' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/forms_editor">Editor</a></li>
          <li class="<?= $this->uri->segment(2) == 'forms_validation' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/forms_validation">Validation</a></li>
        </ul>
      </li>
      <li class="dropdown <?= $this->uri->segment(2) == 'gmaps_advanced_route' || $this->uri->segment(2) == 'gmaps_draggable_marker' || $this->uri->segment(2) == 'gmaps_geocoding' || $this->uri->segment(2) == 'gmaps_geolocation' || $this->uri->segment(2) == 'gmaps_marker' || $this->uri->segment(2) == 'gmaps_multiple_marker' || $this->uri->segment(2) == 'gmaps_route' || $this->uri->segment(2) == 'gmaps_simple' ? 'active' : ''; ?>">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-map-marker-alt"></i> <span>Google Maps</span></a>
        <ul class="dropdown-menu">
          <li class="<?= $this->uri->segment(2) == 'gmaps_advanced_route' ? 'active' : ''; ?>"><a href="<?= base_url(); ?>dist/gmaps_advanced_route">Advanced Route</a></li>
          <li class="<?= $this->uri->segment(2) == 'gmaps_draggable_marker' ? 'active' : ''; ?>"><a href="<?= base_url(); ?>dist/gmaps_draggable_marker">Draggable Marker</a></li>
          <li class="<?= $this->uri->segment(2) == 'gmaps_geocoding' ? 'active' : ''; ?>"><a href="<?= base_url(); ?>dist/gmaps_geocoding">Geocoding</a></li>
          <li class="<?= $this->uri->segment(2) == 'gmaps_geolocation' ? 'active' : ''; ?>"><a href="<?= base_url(); ?>dist/gmaps_geolocation">Geolocation</a></li>
          <li class="<?= $this->uri->segment(2) == 'gmaps_marker' ? 'active' : ''; ?>"><a href="<?= base_url(); ?>dist/gmaps_marker">Marker</a></li>
          <li class="<?= $this->uri->segment(2) == 'gmaps_multiple_marker' ? 'active' : ''; ?>"><a href="<?= base_url(); ?>dist/gmaps_multiple_marker">Multiple Marker</a></li>
          <li class="<?= $this->uri->segment(2) == 'gmaps_route' ? 'active' : ''; ?>"><a href="<?= base_url(); ?>dist/gmaps_route">Route</a></li>
          <li class="<?= $this->uri->segment(2) == 'gmaps_simple' ? 'active' : ''; ?>"><a href="<?= base_url(); ?>dist/gmaps_simple">Simple</a></li>
        </ul>
      </li>
      <li class="dropdown <?= $this->uri->segment(2) == 'modules_calendar' || $this->uri->segment(2) == 'modules_chartjs' || $this->uri->segment(2) == 'modules_datatables' || $this->uri->segment(2) == 'modules_flag' || $this->uri->segment(2) == 'modules_font_awesome' || $this->uri->segment(2) == 'modules_ion_icons' || $this->uri->segment(2) == 'modules_owl_carousel' || $this->uri->segment(2) == 'modules_sparkline' || $this->uri->segment(2) == 'modules_sweet_alert' || $this->uri->segment(2) == 'modules_toastr' || $this->uri->segment(2) == 'modules_vector_map' || $this->uri->segment(2) == 'modules_weather_icon' ? 'active' : ''; ?>">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-plug"></i> <span>Modules</span></a>
        <ul class="dropdown-menu">
          <li class="<?= $this->uri->segment(2) == 'modules_calendar' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/modules_calendar">Calendar</a></li>
          <li class="<?= $this->uri->segment(2) == 'modules_chartjs' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/modules_chartjs">ChartJS</a></li>
          <li class="<?= $this->uri->segment(2) == 'modules_datatables' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/modules_datatables">DataTables</a></li>
          <li class="<?= $this->uri->segment(2) == 'modules_flag' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/modules_flag">Flag</a></li>
          <li class="<?= $this->uri->segment(2) == 'modules_font_awesome' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/modules_font_awesome">Font Awesome</a></li>
          <li class="<?= $this->uri->segment(2) == 'modules_ion_icons' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/modules_ion_icons">Ion Icons</a></li>
          <li class="<?= $this->uri->segment(2) == 'modules_owl_carousel' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/modules_owl_carousel">Owl Carousel</a></li>
          <li class="<?= $this->uri->segment(2) == 'modules_sparkline' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/modules_sparkline">Sparkline</a></li>
          <li class="<?= $this->uri->segment(2) == 'modules_sweet_alert' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/modules_sweet_alert">Sweet Alert</a></li>
          <li class="<?= $this->uri->segment(2) == 'modules_toastr' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/modules_toastr">Toastr</a></li>
          <li class="<?= $this->uri->segment(2) == 'modules_vector_map' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/modules_vector_map">Vector Map</a></li>
          <li class="<?= $this->uri->segment(2) == 'modules_weather_icon' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/modules_weather_icon">Weather Icon</a></li>
        </ul>
      </li>
      <li class="menu-header">Pages</li>
      <li class="dropdown">
        <a href="#" class="nav-link has-dropdown"><i class="far fa-user"></i> <span>Auth</span></a>
        <ul class="dropdown-menu">
          <li><a href="<?= base_url(); ?>dist/auth_forgot_password">Forgot Password</a></li>
          <li><a href="<?= base_url(); ?>dist/auth_login">Login</a></li>
          <li><a href="<?= base_url(); ?>dist/auth_register">Register</a></li>
          <li><a href="<?= base_url(); ?>dist/auth_reset_password">Reset Password</a></li>
        </ul>
      </li>
      <li class="dropdown">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-exclamation"></i> <span>Errors</span></a>
        <ul class="dropdown-menu">
          <li><a class="nav-link" href="<?= base_url(); ?>dist/errors_503">503</a></li>
          <li><a class="nav-link" href="<?= base_url(); ?>dist/errors_403">403</a></li>
          <li><a class="nav-link" href="<?= base_url(); ?>dist/errors_404">404</a></li>
          <li><a class="nav-link" href="<?= base_url(); ?>dist/errors_500">500</a></li>
        </ul>
      </li>
      <li class="dropdown <?= $this->uri->segment(2) == 'features_activities' || $this->uri->segment(2) == 'features_post_create' || $this->uri->segment(2) == 'features_posts' || $this->uri->segment(2) == 'features_profile' || $this->uri->segment(2) == 'features_settings' || $this->uri->segment(2) == 'features_setting_detail' || $this->uri->segment(2) == 'features_tickets' ? 'active' : ''; ?>">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-bicycle"></i> <span>Features</span></a>
        <ul class="dropdown-menu">
          <li class="<?= $this->uri->segment(2) == 'features_activities' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/features_activities">Activities</a></li>
          <li class="<?= $this->uri->segment(2) == 'features_post_create' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/features_post_create">Post Create</a></li>
          <li class="<?= $this->uri->segment(2) == 'features_posts' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/features_posts">Posts</a></li>
          <li class="<?= $this->uri->segment(2) == 'features_profile' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/features_profile">Profile</a></li>
          <li class="<?= $this->uri->segment(2) == 'features_settings' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/features_settings">Settings</a></li>
          <li class="<?= $this->uri->segment(2) == 'features_setting_detail' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/features_setting_detail">Setting Detail</a></li>
          <li class="<?= $this->uri->segment(2) == 'features_tickets' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/features_tickets">Tickets</a></li>
        </ul>
      </li>
      <li class="dropdown <?= $this->uri->segment(2) == 'utilities_invoice' ? 'active' : ''; ?>">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-ellipsis-h"></i> <span>Utilities</span></a>
        <ul class="dropdown-menu">
          <li><a href="<?= base_url(); ?>dist/utilities_contact">Contact</a></li>
          <li class="<?= $this->uri->segment(2) == 'utilities_invoice' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/utilities_invoice">Invoice</a></li>
          <li><a href="<?= base_url(); ?>dist/utilities_subscribe">Subscribe</a></li>
        </ul>
      </li>
      <li class="<?= $this->uri->segment(2) == 'credits' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url(); ?>dist/credits"><i class="fas fa-pencil-ruler"></i> <span>Credits</span></a></li>
    </ul>

    <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
      <a href="https://getstisla.com/docs" class="btn btn-primary btn-lg btn-block btn-icon-split">
        <i class="fas fa-rocket"></i> Documentation
      </a>
    </div>
  </aside>
</div>