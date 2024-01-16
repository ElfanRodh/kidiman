<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if (!$this->ion_auth->logged_in()) {
      redirect('auth/login');
    } else {
      $this->is_admin = $this->ion_auth->is_admin();
      $this->user = $this->ion_auth->user()->row();
    }
  }

  public function index()
  {
    date_default_timezone_set('Asia/Jakarta');
    $tahun = date('Y');

    $data['title'] = "Dashboard";
    $data['tahun'] = $tahun;
    $this->load->view('admin/home/index', $data);
  }

  function getJabatan()
  {
    $wr['jbt_status'] = 1;
    $wr['prt_status'] = 1;
    $wr['prj_status'] = 1;
    $this->db->join('jabatan', 'jabatan.jbt_id = perangkat_jabatan.prj_jabatan', 'left');
    $this->db->join('perangkat', 'perangkat.prt_id = perangkat_jabatan.prj_perangkat', 'left');
    $data = $this->db->order_by('jbt_id')->get_where('perangkat_jabatan', $wr);
    echo json_encode($data->result());
  }

  function getKegiatan($jn = '')
  {
    if ($this->is_admin) {
      $wr = [];
    } else {
      $user = $_SESSION['usr'];
      $wr['keg_jabatan'] = $user['jabatan_id'];
    }

    if ($jn) {
      if ($jn == 'proses') {
        $wr['keg_is_selesai'] = 0;
      } else if ($jn == 'selesai') {
        $wr['keg_is_selesai'] = 1;
      }
    }

    if ($this->input->post('fil_jabatan')) {
      $wr['keg_jabatan'] = $this->input->post('fil_jabatan');
    }

    if ($this->input->post("fil_tanggal")) {
      $tanggal = explode(' - ', $this->input->post('fil_tanggal'));
      $keg_tanggal_mulai   = date('Y-m-d', strtotime($tanggal[0]));
      $keg_tanggal_selesai = date('Y-m-d', strtotime($tanggal[1]));
      $this->db->group_start();
      $this->db->where("keg_tanggal_mulai BETWEEN '$keg_tanggal_mulai' AND '$keg_tanggal_selesai'");
      $this->db->or_where("keg_tanggal_selesai BETWEEN '$keg_tanggal_mulai' AND '$keg_tanggal_selesai'");
      $this->db->group_end();

      $keg_tahun_mulai   = date('Y', strtotime($tanggal[0]));
      $keg_tahun_selesai = date('Y', strtotime($tanggal[1]));
      $this->db->group_start();
      $this->db->where("YEAR(keg_tanggal_mulai) BETWEEN '$keg_tahun_mulai' AND '$keg_tahun_selesai'");
      $this->db->or_where("YEAR(keg_tanggal_selesai) BETWEEN '$keg_tahun_mulai' AND '$keg_tahun_selesai'");
      $this->db->group_end();
    }

    $this->db->select('COUNT(*) as total');
    $this->db->where('keg_status', '1');
    $query = $this->db->get_where('kegiatan', $wr)->row();
    $data = $query->total;

    echo json_encode($data);
  }

  function prtTotal()
  {
    $this->db->select('COUNT(*) as total');
    $this->db->where('prj_status = 1');
    $query = $this->db->get('perangkat_jabatan')->row();
    $data = $query->total;

    echo json_encode($data);
  }

  function getChartKegiatan()
  {
    $wr = [];
    if ($this->input->post('fil_jabatan')) {
      $wr['keg_jabatan'] = $this->input->post('fil_jabatan');
    }

    // List Bulan
    $bulanArr = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    // Data Kegiatan Selesai
    if ($this->input->post("fil_tanggal")) {
      $tanggal = explode(' - ', $this->input->post('fil_tanggal'));
      $keg_tanggal_mulai   = date('Y-m-d', strtotime($tanggal[0]));
      $keg_tanggal_selesai = date('Y-m-d', strtotime($tanggal[1]));
      $this->db->group_start();
      $this->db->where("keg_tanggal_mulai BETWEEN '$keg_tanggal_mulai' AND '$keg_tanggal_selesai'");
      $this->db->or_where("keg_tanggal_selesai BETWEEN '$keg_tanggal_mulai' AND '$keg_tanggal_selesai'");
      $this->db->group_end();

      $keg_tahun_mulai   = date('Y', strtotime($tanggal[0]));
      $keg_tahun_selesai = date('Y', strtotime($tanggal[1]));
      $this->db->group_start();
      $this->db->where("YEAR(keg_tanggal_mulai) BETWEEN '$keg_tahun_mulai' AND '$keg_tahun_selesai'");
      $this->db->or_where("YEAR(keg_tanggal_selesai) BETWEEN '$keg_tahun_mulai' AND '$keg_tahun_selesai'");
      $this->db->group_end();
    }
    $this->db->select("DATE_FORMAT(STR_TO_DATE(keg_tanggal_mulai, '%Y-%m-%d'), '%Y-%m') AS bulan_tahun, DATE_FORMAT(STR_TO_DATE(keg_tanggal_mulai, '%Y-%m-%d'), '%m') AS bulan, COUNT(*) AS jumlah");
    $this->db->where('keg_status', 1);
    $this->db->where('keg_is_selesai', 1);
    $this->db->group_by('bulan');
    $query = $this->db->get_where('kegiatan', $wr);
    $selesai = $query->result();

    // Data Kegiatan Progress
    if ($this->input->post("fil_tanggal")) {
      $tanggal = explode(' - ', $this->input->post('fil_tanggal'));
      $keg_tanggal_mulai   = date('Y-m-d', strtotime($tanggal[0]));
      $keg_tanggal_selesai = date('Y-m-d', strtotime($tanggal[1]));
      $this->db->group_start();
      $this->db->where("keg_tanggal_mulai BETWEEN '$keg_tanggal_mulai' AND '$keg_tanggal_selesai'");
      $this->db->or_where("keg_tanggal_selesai BETWEEN '$keg_tanggal_mulai' AND '$keg_tanggal_selesai'");
      $this->db->group_end();

      $keg_tahun_mulai   = date('Y', strtotime($tanggal[0]));
      $keg_tahun_selesai = date('Y', strtotime($tanggal[1]));
      $this->db->group_start();
      $this->db->where("YEAR(keg_tanggal_mulai) BETWEEN '$keg_tahun_mulai' AND '$keg_tahun_selesai'");
      $this->db->or_where("YEAR(keg_tanggal_selesai) BETWEEN '$keg_tahun_mulai' AND '$keg_tahun_selesai'");
      $this->db->group_end();
    }
    $this->db->select("DATE_FORMAT(STR_TO_DATE(keg_tanggal_mulai, '%Y-%m-%d'), '%Y-%m') AS bulan_tahun, DATE_FORMAT(STR_TO_DATE(keg_tanggal_mulai, '%Y-%m-%d'), '%m') AS bulan, COUNT(*) AS jumlah");
    $this->db->where('keg_status', 1);
    $this->db->where('keg_is_selesai', 0);
    $this->db->group_by('bulan');
    $query1 = $this->db->get_where('kegiatan', $wr);
    $progres = $query1->result();

    $arrSelesai = [];
    $arrProgres = [];

    // Cek Ulang Jumlah Per Bulan
    foreach ($bulanArr as $key => $val) {
      // Definisi Label dan Jumlah Data Default
      $arrSelesai['label'][$key]     = $val;
      $arrSelesai['jumlah'][$key]    = 0;
      // Cek Jika Ada Bulan yang ada Jumlah Data nya
      foreach ($selesai as $k => $v) {
        if ((int) ($key + 1) == (int) $v->bulan) {
          $arrSelesai['jumlah'][$key] = $v->jumlah;
        }
      }

      $arrProgres['label'][$key]     = $val;
      $arrProgres['jumlah'][$key]    = 0;
      foreach ($progres as $k => $v) {
        if ((int) ($key + 1) == (int) $v->bulan) {
          $arrProgres['jumlah'][$key] = $v->jumlah;
        }
      }
    }

    $data = [
      'selesai' => $arrSelesai,
      'progres' => $arrProgres,
    ];

    // header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    // return $data;
  }
}

/* End of file Home.php */
