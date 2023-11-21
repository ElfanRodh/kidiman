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
    $data['title'] = "Dashboard";
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

    $this->db->select('COUNT(*) as total');
    $this->db->where('keg_status = 1');
    $query = $this->db->get_where('kegiatan', $wr)->row();
    $data = $query->total;

    echo json_encode($data);
  }

  function kegTotal()
  {
    if ($this->is_admin) {
      $wr = [];
    } else {
      $user = $_SESSION['usr'];
      $wr['keg_jabatan'] = $user['jabatan_id'];
    }
    $this->db->select('COUNT(*) as total');
    $this->db->where('keg_status = 1');
    $query = $this->db->get_where('kegiatan', $wr)->row();
    $data = $query->total;

    echo json_encode($data);
  }

  function kegProses()
  {
    $this->db->select('COUNT(*) as total');
    $this->db->where('keg_is_selesai = 0 AND keg_status = 1');
    $query = $this->db->get('kegiatan')->row();
    $data = $query->total;

    echo json_encode($data);
  }

  function kegSelesai()
  {
    $this->db->select('COUNT(*) as total');
    $this->db->where('keg_is_selesai = 1 AND keg_status = 1');
    $query = $this->db->get('kegiatan')->row();
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
    // List Bulan
    $bulanArr = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    // Data Kegiatan Selesai
    $this->db->select("DATE_FORMAT(STR_TO_DATE(keg_tanggal_mulai, '%Y-%m-%d'), '%Y-%m') AS bulan_tahun, DATE_FORMAT(STR_TO_DATE(keg_tanggal_mulai, '%Y-%m-%d'), '%m') AS bulan, COUNT(*) AS jumlah");
    $this->db->where('keg_status', 1);
    $this->db->where('keg_is_selesai', 1);
    $this->db->group_by('bulan');
    $query = $this->db->get('kegiatan');
    $selesai = $query->result();

    // Data Kegiatan Progress
    $this->db->select("DATE_FORMAT(STR_TO_DATE(keg_tanggal_mulai, '%Y-%m-%d'), '%Y-%m') AS bulan_tahun, DATE_FORMAT(STR_TO_DATE(keg_tanggal_mulai, '%Y-%m-%d'), '%m') AS bulan, COUNT(*) AS jumlah");
    $this->db->where('keg_status', 1);
    $this->db->where('keg_is_selesai', 0);
    $this->db->group_by('bulan');
    $query1 = $this->db->get('kegiatan');
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
