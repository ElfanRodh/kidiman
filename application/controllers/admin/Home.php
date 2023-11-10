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
    $data = array(
      'title' => "Dashboard"
    );
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
}

/* End of file Home.php */
