<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kegiatan extends CI_Controller
{

  public function index()
  {
  }

  function detail($jbt_id = null, $fun_id = null)
  {
    if ($fun_id) {
      echo $jbt_id . '/' . $fun_id;
    } else {
      echo $jbt_id;
    }
  }

  public function viewData()
  {
    $list = $this->getKegiatanList();
    $data = array();
    $no   = $this->input->post('start');
    $v    = 0;
    foreach ($list as $jbt) {
      $no++;
      $row                = array();
      $row['no']          = $no;
      $row['jbt_nama']    = $jbt->jbt_nama;
      $row['opsi']     = '<div class="btn-group" role="group">
									<button class="btn btn-icon btn-warning update-data" data-id="' . (string)$jbt->prj_id . '">
										<i class="fa fa-edit"></i>
									</button>
									<button class="btn btn-icon btn-danger delete-data" data-id="' . (string)$jbt->prj_id . '" data-name="' . (string)$jbt->jbt_nama . '">
										<i class="fa fa-trash"></i>
									</button>
								</div>';
      $data[]        = $row;
    }

    $output = array(
      "draw"            => $this->input->post('draw'),
      "recordsTotal"    => $this->countAll(),
      "recordsFiltered" => $this->countFiltered(),
      "data"            => $data,
    );
    echo json_encode($output);
  }

  public function getKegiatanList($where = null)
  {
    $this->getList($where);
    if ($this->input->post('length') != -1) {
      $this->db->limit($this->input->post('length'), $this->input->post('start'));
    }
    $a = $this->db->get();
    return $a->result();
  }

  private function getList($where = null)
  {
    $this->db->select('*');
    $this->db->from('kegiatan');
    $this->db->join('jabatan', 'jabatan.jbt_id = kegiatan.keg_jabatan', 'left');
    $this->db->join('fungsi', 'fungsi.fun_id = kegiatan.keg_fungsi', 'left');
    $this->db->join('perangkat', 'perangkat.prt_jabatan = jabatan.keg_perangkat', 'left');
    $this->db->where(['keg_status' => 1]);
    $this->db->where(['jbt_status' => 1]);
    $this->db->where(['fun_status' => 1]);
    if ($where) {
      $this->db->where($where);
    }
    if ($this->input->post("fil_nama")) {
      $this->db->where(['jbt_nama' => $this->input->post("fil_nama")]);
    }

    $i = 0;
    foreach ($this->column_search as $item) {
      if ($this->input->post('search')['value']) {
        if ($i === 0) {
          $this->db->group_start();
          $this->db->like($item, $this->input->post('search')['value']);
        } else {
          $this->db->or_like($item, $this->input->post('search')['value']);
        }
        if (count($this->column_search) - 1 == $i)
          $this->db->group_end();
      }
      $i++;
    }
    if ($this->input->post('order')) {
      $this->db->order_by($this->column_order[$this->input->post('order')['0']['column']], $this->input->post('order')['0']['dir']);
    } else if (isset($this->order)) {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function countFiltered()
  {
    $this->getList();
    $a = $this->db->get();
    return $a->num_rows();
  }

  public function countAll()
  {
    $this->getList();
    return $this->db->count_all_results();
  }

  function getKegiatan()
  {
    $wr['keg_jabatan'] = $this->input->post('jbt_id');
    $er['keg_fungsi'] = $this->input->post('fun_id_id');

    $this->db->join('fungsi', 'fungsi.fun_id = kegiatan.keg_fungsi', 'left');
    $kegiatan = $this->db->get_where('kegiatan', $wr);

    $data = [];
    foreach ($kegiatan->result() as $k => $v) {
      $data[$k]['fun_nama'] = $v->fun_nama;
      $data[$k]['keg_nama'] = $v->keg_nama;
      $data[$k]['keg_progres'] = $v->keg_progres;
      $data[$k]['progres']  = $this->getProgresKegiatan($v->keg_id);
    }

    echo json_encode($data);
  }

  function getProgresKegiatan($prog_kegiatan)
  {
    $progres = $this->db->order_by('prog_persentase')->get_where('progres_kegiatan', ['prog_kegiatan' => $prog_kegiatan]);
    $data = [];
    foreach ($progres->result() as $k => $v) {
      $data[$k]['prog_persentase'] = $v->prog_persentase;
      $data[$k]['prog_bukti'] = base_url() . 'assets/img/progress/' . $v->prog_bukti;
      $data[$k]['prog_keterangan'] = $v->prog_keterangan;
      $data[$k]['prog_tanggal'] = date('d-m-Y H:i:s', strtotime($v->prog_tanggal));
    }
    return $data;
  }
}

/* End of file Kegiatan.php */
