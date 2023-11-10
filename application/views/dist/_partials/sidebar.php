<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_admin = $this->ion_auth->is_admin();
if ($is_admin) {
  $menu = [
    [
      'header' => 'Dashboard',
      'menus' => [
        [
          'active'  => 'home',
          'link'    => 'admin/home',
          'text'    => '<i class="fa fa-fire"></i> <span>Dashboard</span>',
        ],
      ]
    ],
    [
      'header' => 'Master Data',
      'menus' => [
        [
          'active'  => 'perangkat',
          'link'    => 'admin/perangkat',
          'text'    => '<i class="fa fa-users"></i> <span>Perangkat Desa</span>',
        ],
        [
          'active'  => 'user',
          'link'    => 'admin/user',
          'text'    => '<i class="fa fa-user-shield"></i> <span>Data User</span>',
        ],
      ]
    ],
    [
      'header' => 'Perangkat',
      'menus' => [
        [
          'active'  => 'tugas',
          'link'    => 'admin/tugas',
          'text'    => '<i class="fa fa-box"></i> <span>Tugas Pokok</span>',
        ],
        [
          'active'  => 'fungsi',
          'link'    => 'admin/fungsi',
          'text'    => '<i class="fa fa-hands"></i> <span>Fungsi</span>',
        ],
        [
          'active'  => 'kegiatan',
          'link'    => 'admin/kegiatan',
          'text'    => '<i class="fa fa-tasks"></i> <span>Kegiatan</span>',
        ],
      ]
    ]
  ];
} else {
  $menu = [
    [
      'header' => 'Dashboard',
      'menus' => [
        [
          'active'  => 'home',
          'link'    => 'admin/home',
          'text'    => '<i class="fa fa-fire"></i> <span>Dashboard</span>',
        ],
      ]
    ],
    [
      'header' => 'Perangkat',
      'menus' => [
        [
          'active'  => 'kegiatan',
          'link'    => 'admin/kegiatan',
          'text'    => '<i class="fa fa-tasks"></i> <span>Kegiatan</span>',
        ],
      ]
    ]
  ];
}

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
      <?php foreach ($menu as $k => $v) : ?>
        <li class="menu-header"><?= $v['header'] ?></li>
        <?php foreach ($v['menus'] as $value) : ?>
          <li class="<?= $this->uri->segment(2) == $value['active'] ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= site_url($value['link']); ?>">
              <?= $value['text'] ?>
            </a>
          </li>
        <?php endforeach; ?>
      <?php endforeach; ?>
  </aside>
</div>