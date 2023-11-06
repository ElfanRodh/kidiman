<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

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


  // Menghitung persentase
  $selisih_total_hari = $tanggal_awal->diff($tanggal_akhir)->days + 1;
  $selisih_tanggal_sekarang = $tanggal_awal->diff($tanggal_sekarang)->days + 1;
  if ($tanggal_sekarang < $tanggal_awal) {
    $selisih_tanggal_sekarang = -$selisih_tanggal_sekarang + 1;
  }
  $persentase = ($selisih_tanggal_sekarang / $selisih_total_hari) * 100;

  return [
    'selisih_total_hari' => $selisih_total_hari,
    'selisih_tanggal_sekarang' => $selisih_tanggal_sekarang,
    'persentase' => number_format($persentase, 2),
  ];
}
