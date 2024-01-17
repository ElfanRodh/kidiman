<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists("hitungPersentase")) {
  function hitungPersentase($tanggal_mulai, $tanggal_selesai, $tanggal_input, $keg_id = null, $isProgres = 0)
  {
    // Tanggal awal
    $tanggal_awal = new DateTime($tanggal_mulai);
    // Tanggal akhir
    $tanggal_akhir = new DateTime($tanggal_selesai);

    if ($keg_id && !$isProgres) {
      $CI = &get_instance(); // Dapatkan instance CodeIgniter

      // Muat library database jika belum dimuat
      if (!isset($CI->db)) {
        $CI->load->database();
      }

      $data = $CI->db->order_by('prog_tanggal DESC')->limit(1)->get_where('progres_kegiatan', ['prog_kegiatan' => $keg_id])->row();
      $tanggal_sekarang = new DateTime($data->prog_tanggal);
    } else {
      $tanggal_sekarang = new DateTime($tanggal_input);
    }


    // // Menghitung persentase
    // $selisih_total_hari = $tanggal_awal->diff($tanggal_akhir)->days + 1;
    // $selisih_tanggal_sekarang = $tanggal_awal->diff($tanggal_sekarang)->days + 1;
    // if ($tanggal_sekarang < $tanggal_awal) {
    //   $selisih_tanggal_sekarang = -$selisih_tanggal_sekarang + 1;
    // }
    // $persentase = ($selisih_tanggal_sekarang / $selisih_total_hari) * 100;

    // Menghitung persentase
    $selisih_total_hari = $tanggal_awal->diff($tanggal_akhir)->days + 1;
    $selisih_tanggal_sekarang = $tanggal_awal->diff($tanggal_sekarang)->days + 1;

    // Pastikan selisih_tanggal_sekarang tidak melebihi selisih_total_hari
    $selisih_tanggal_sekarang = min($selisih_tanggal_sekarang, $selisih_total_hari);

    // Menghindari nilai persentase di luar rentang 0-100
    $persentase = max(0, min(100, ($selisih_tanggal_sekarang / $selisih_total_hari) * 100));


    return [
      'selisih_total_hari' => $selisih_total_hari,
      'selisih_tanggal_sekarang' => $selisih_tanggal_sekarang,
      'persentase' => number_format($persentase, 2),
    ];
  }
}

if (!function_exists("setColor")) {
  function setColor($inputValue)
  {
    $arrColor = [
      25 => '#fc544b',
      50 => '#ffa426',
      75 => '#3abaf4',
      100 => '#33d9b2',
    ];

    foreach ($arrColor as $value => $color) {
      if ($inputValue <= $value) {
        return $color;
      }
    }

    return '#fc544b';
  }
}

if (!function_exists("getUser")) {
  function getUser()
  {
    $CI = &get_instance();
    $CI->load->library('ion_auth');

    $return = [];

    if ($CI->ion_auth->logged_in()) {
      $user = $CI->ion_auth->user()->row();
      $CI->db->select('perangkat.*, groups.*, groups.id AS groups_id, users.*, users.id AS users_id');
      $CI->db->join('users_groups', 'users_groups.user_id = users.id', 'left');
      $CI->db->join('groups', 'groups.id = users_groups.group_id', 'left');
      $CI->db->join('perangkat_jabatan', 'perangkat_jabatan.prj_jabatan = users.jabatan_id', 'left');
      $CI->db->join('perangkat', 'perangkat.prt_id = perangkat_jabatan.prj_perangkat', 'left');
      $data = $CI->db->get_where('users', ['users.id' => $user->id]);
      $return = $data->row_array();
    }

    return $return;
  }
}

if (!function_exists("hitungSelisihWaktu")) {
  function hitungSelisihWaktu($datetime1, $datetime2, $format = '%h jam, %i menit, %s detik')
  {
    $datetime1 = new DateTime($datetime1);
    $datetime2 = new DateTime($datetime2);

    $interval = $datetime1->diff($datetime2);

    return $interval->format($format);
  }
}

if (!function_exists("compressImage")) {
  function compressImage($source_path, $quality)
  {
    $config['image_library'] = 'gd2';
    $config['source_image'] = $source_path;
    $config['create_thumb'] = FALSE;
    $config['maintain_ratio'] = TRUE;
    $config['quality'] = $quality;
    $config['width'] = 800;
    $config['height'] = 600;

    $CI = &get_instance();

    $CI->load->library('image_lib');
    $CI->image_lib->initialize($config);

    if (!$CI->image_lib->resize()) {
      // Jika gagal melakukan kompresi, Anda dapat menangani kesalahan di sini
      log_message('error', 'Gagal melakukan kompresi gambar: ' . $CI->image_lib->display_errors());
    }
  }
}
