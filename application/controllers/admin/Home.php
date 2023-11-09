<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

  public function index()
  {
    // $data['kegTotal'] = $this->kegTotal();
    // $data['kegProses'] = $this->kegProses();
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

  function kegTotal()
  {
    $this->db->select('COUNT(*) as total');
    $this->db->where('keg_status = 1');
    $query = $this->db->get('kegiatan')->row();
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
