<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

  public function index()
  {
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
}

/* End of file Home.php */
