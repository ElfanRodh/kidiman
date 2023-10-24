<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tugas extends CI_Controller
{

  var $column_order   = array(null, 'jbt_nama', 'prt_nama', 'tgs_nama');
  var $column_search   = array('jbt_nama', 'prt_nama', 'tgs_nama');
  var $order = array('jbt_id' => 'asc', 'jbt_nama' => 'asc', 'prt_nama' => 'asc', 'tgs_nama' => 'asc');

  public function index()
  {
    $data = array(
      'title' => "Tugas Perangkat Desa"
    );
    $this->load->view('admin/tugas/index', $data);
  }

  public function viewData()
  {
    $list = $this->getTugasList();
    $data = array();
    $no   = $this->input->post('start');
    $v    = 0;
    foreach ($list as $tgs) {
      $no++;
      $row                = array();
      $row['no']          = $no;
      $row['jbt_nama']    = $tgs->jbt_nama;
      $row['tgs_nama']    = $tgs->tgs_nama;
      $row['opsi']        = '<div class="btn-group" role="group">
									<button class="btn btn-icon btn-warning update-data" data-id="' . (string)$tgs->jbt_id . '">
										<i class="fa fa-edit"></i>
									</button>
									<button class="btn btn-icon btn-danger delete-data" data-id="' . (string)$tgs->jbt_id . '" data-name="' . (string)$tgs->tgs_nama . '">
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

  public function getTugasList($where = null)
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
    $this->db->from('perangkat_jabatan');
    $this->db->join('jabatan', 'jabatan.jbt_id = perangkat_jabatan.prj_jabatan', 'left');
    $this->db->join('jabatan_tugas', 'jabatan_tugas.jt_jabatan = jabatan.jbt_id', 'left');
    $this->db->join('tugas', 'tugas.tgs_id = jabatan_tugas.jt_tugas', 'left');
    $this->db->where(['prj_status' => 1]);
    $this->db->where(['jbt_status' => 1]);
    $this->db->where(['jt_status' => 1]);
    $this->db->where(['tgs_status' => 1]);
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

  public function getData()
  {
    if ($this->input->post('id')) {
      $this->db->where(['jt_jabatan' => $this->input->post('id')]);
    }
    $this->db->join('jabatan', 'jabatan.jbt_id = jabatan_tugas.jt_jabatan', 'left');
    $this->db->join('tugas', 'tugas.tgs_id = jabatan_tugas.jt_tugas', 'left');
    $dt = $this->db->get_where('jabatan_tugas', ['jt_status' => '1']);

    $data['ok'] = 500;
    $data['data'] = 'Data Tidak Ada';
    if ($dt->num_rows() > 0) {
      $data['ok']   = 200;
      $data['data']  = $dt->row_array();
      $data['data']['jabatan']  = $this->input->post('id');
    } else {
      $data['ok']   = 200;
      $data['data']  = [];
      $data['data']['jabatan']  = $this->input->post('id');
    }

    echo json_encode($data);
  }

  public function addOrEdit()
  {
    $cek = $this->validateData();
    if ($cek) {
      if ($this->input->post('tgs_id')) {
        $wr['tgs_id'] = $this->input->post('tgs_id');

        $data = [];
        $pass = ['tgs_id'];
        foreach ($_POST as $k => $v) {
          if (!in_array($k, $pass)) {
            $data[$k] = $v;
          }
        }
        $ex = $this->db
          ->where(['tgs_id !=' => $this->input->post('tgs_id')])
          ->join('perangkat', 'perangkat.prt_id = perangkat_jabatan.prj_perangkat', 'left')
          ->get_where('perangkat_jabatan', $data);
        if ($ex->num_rows() > 0) {
          $ret['ok'] = 500;
          $ret['form'] = 'Data Sudah Ada Sebelumnya';
        } else {
          $update_prj = $this->db->update('perangkat_jabatan', [
            'tgs_jabatan' => $this->input->post('tgs_jabatan')
          ], $wr);
          $wr2['prt_id'] = $this->db->get_where('perangkat_jabatan', $wr)->row()->prj_perangkat;
          $update_prt = $this->db->update('perangkat', [
            'prt_nama' => $this->input->post('prt_nama')
          ], $wr2);
          if ($update_prj && $update_prt) {
            $ret['ok'] = 200;
            $ret['form'] = 'Sukses Update Data';
          } else {
            $ret['ok'] = 500;
            $ret['form'] = 'Gagal Update Data';
          }
        }
      } else {
        $wr1['tgs_nama']     = strip_tags($this->input->post('tgs_nama'));
        $tugas = $this->db->get_where('tugas', $wr1);
        if ($tugas->num_rows() > 0) {
          $wr['jt_tugas']      = $tugas->row()->tgs_id;
          $wr['jt_jabatan']    = $this->input->post('jt_jabatan');
          $wr['jt_status']     = 1;
          $row = $this->db->get_where('jabatan_tugas', $wr);

          if ($row->num_rows() < 1) {
            $data = [];
            $data = $wr;
            if ($this->db->insert('jabatan_tugas', $data)) {
              $ret['ok']    = 200;
              $ret['form']  = 'Sukses Insert Data';
            } else {
              $ret['ok']    = 500;
              $ret['form']  = 'Gagal Insert Data';
            }
          }
        } else {
          $data_tugas = [
            'tgs_nama' => $this->input->post('tgs_nama')
          ];
          $tugas = $this->db->insert('tugas', $data_tugas);
          if ($tugas) {
            $data['jt_tugas']      = $this->db->insert_id();
            $data['jt_jabatan']    = $this->input->post('jt_jabatan');
            $data['jt_status']     = 1;
            if ($this->db->insert('jabatan_tugas', $data)) {
              $ret['ok']    = 200;
              $ret['form']  = 'Sukses Insert Data';
            } else {
              $ret['ok']    = 500;
              $ret['form']  = 'Gagal Insert Data';
            }
          }
          // $ret['ok']    = 500;
          // $ret['form']  = 'Data Sudah Ada Sebelumnya';
        }
      }
    } else {
      $ret['form']['jt_jabatan'] = form_error('jt_jabatan');
      $ret['form']['tgs_nama']    = form_error('tgs_nama');
      $ret['ok']    = 400;
    }
    echo json_encode($ret);
  }

  public function delete()
  {
    if ($this->input->post('id')) {
      $wr['jt_jabatan'] = $this->input->post('id');
      if ($this->db->update('jabatan_tugas', ['jt_status' => 0], $wr)) {
        $out["ok"]    = 200;
        $out["data"]  = "Berhasil Menghapus Data";
      } else {
        $out["ok"]    = 500;
        $out["data"]  = "Gagal Menghapus Data";
      }
    }
    echo json_encode($out);
  }

  private function validateData()
  {
    $this->load->library('form_validation');
    $config = [
      [
        'field' => 'jt_jabatan',
        'label' => 'Jabatan',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus diisi',
        ],
      ],
      [
        'field' => 'tgs_nama',
        'label' => 'Nama Tugas',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus diisi',
        ],
      ],
    ];

    $this->form_validation->set_rules($config);
    return $this->form_validation->run();
  }

  function getJabatan()
  {
    if ($this->input->post('id')) {
      if ($this->input->post('is_edit')) {
        $this->db->where(
          'jbt_id',
          $this->input->post('id')
        );
      }
    } else {
      $ids = $this->db->select('jt_jabatan')->from('jabatan_tugas')->where('jt_status', 1)->get();
      if ($ids->num_rows() > 0) {
        $this->db->where_not_in(
          'jbt_id',
          array_column(
            $ids->result_array(),
            'jt_jabatan'
          )
        );
      }
    }

    $data = $this->db->get_where('jabatan', ['jbt_status' => 1]);
    echo json_encode($data->result());
  }

  function getTugas()
  {
    if ($this->input->get('q')) {
      $this->db->like('prt_nama', $this->input->get('q'));
    }
    $data = $this->db->get_where('perangkat', ['prt_status' => 1]);
    $ret = array_column($data->result_array(), 'prt_nama');
    header('Content-Type: application/json');
    echo json_encode($ret);
  }
}

/* End of file Tugas.php */
