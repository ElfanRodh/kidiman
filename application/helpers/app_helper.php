<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function hitungPersentase($tanggal_mulai, $tanggal_selesai, $tanggal_input)
{
  // Tanggal awal
  $tanggal_awal = new DateTime($tanggal_mulai);
  // Tanggal akhir
  $tanggal_akhir = new DateTime($tanggal_selesai);

  // Tanggal tertentu
  $tanggal_sekarang = new DateTime($tanggal_input);

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
