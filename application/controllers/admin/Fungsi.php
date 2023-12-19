<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Fungsi extends CI_Controller
{

  var $column_order   = array(null, 'jbt_nama');
  var $column_search   = array('jbt_nama');
  var $column_search_fungsi   = array('jabatan.jbt_nama', 'fungsi.fun_nama');
  var $order = array('jbt_id' => 'asc', 'jbt_nama' => 'asc');


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
      'title' => "Fungsi Perangkat Desa"
    );
    $this->load->view('admin/fungsi/index', $data);
  }

  public function viewData()
  {
    $list = $this->getFungsiList();
    $data = array();
    $no   = $this->input->post('start');
    $v    = 0;
    foreach ($list as $fun) {
      $no++;
      $row                = array();
      $row['no']          = $no;
      $row['jbt_nama']    = $fun->jbt_nama . '<br> (' . $fun->prt_nama . ')';
      // $row['fun_nama']    = $fun->fun_nama;
      $row['fungsi']      = $this->getFungsiJabatan($fun->jbt_id);
      // $row['opsi']        = '<div class="btn-group" role="group">
      // 						<button class="btn btn-icon btn-warning update-data" data-id="' . (string)$fun->jbt_id . '">
      // 							<i class="fa fa-edit"></i>
      // 						</button>
      // 						<button class="btn btn-icon btn-danger delete-data" data-id="' . (string)$fun->jbt_id . '" data-name="' . (string)$fun->jbt_nama . '">
      // 							<i class="fa fa-trash"></i>
      // 						</button>
      // 					</div>';
      $row['opsi']        = '<div class="btn-group" role="group">
                              <button class="btn btn-icon btn-warning update-data" data-toggle="tooltip" data-placement="top" title="Edit Data" data-original-title="Edit Data" data-id="' . (string)$fun->jbt_id . '">
                                <i class="fa fa-edit"></i>
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

  public function getFungsiList($where = null)
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
    $this->db->join('perangkat', 'perangkat.prt_id = perangkat_jabatan.prj_perangkat', 'left');
    $this->db->where(['prj_status' => 1]);
    $this->db->where(['jbt_status' => 1]);
    $this->db->where(['prt_status' => 1]);
    $this->db->group_by('jbt_id');
    if ($where) {
      $this->db->where($where);
    }
    if ($this->input->post("fil_jabatan")) {
      $this->db->where(['jbt_id' => $this->input->post("fil_jabatan")]);
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
      $this->db->where(['jf_jabatan' => $this->input->post('id')]);
    }
    $this->db->join('jabatan', 'jabatan.jbt_id = jabatan_fungsi.jf_jabatan', 'left');
    $this->db->join('fungsi', 'fungsi.fun_id = jabatan_fungsi.jf_fungsi', 'left');
    $dt = $this->db->get_where('jabatan_fungsi', ['jf_status' => '1']);

    $data['ok'] = 500;
    $data['data'] = 'Data Tidak Ada';
    if ($dt->num_rows() > 0) {
      $data['ok']   = 200;
      $data['data']  = $dt->result_array();
      $data['data']['jabatan']  = $this->input->post('id');
      $data['data']['fun_ids']  = array_column($dt->result_array(), 'fun_id');
    } else {
      $data['ok']   = 200;
      $data['data']  = [];
      $data['data']['jabatan']  = $this->input->post('id');
      $data['data']['fun_ids']  = [];
    }

    echo json_encode($data);
  }

  public function addOrEdit()
  {
    $cek = $this->validateData();
    if ($cek) {
      if ($this->input->post('jf_edit')) {
        $wr['jf_jabatan'] = $this->input->post('jf_jabatan');

        $data = [];
        $jf_fungsi = $this->input->post('jf_fungsi');
        if (!empty($jf_fungsi)) {
          foreach ($jf_fungsi as $i => $value) {
            $insert[$i]['jf_jabatan'] = $this->input->post('jf_jabatan');
            $insert[$i]['jf_fungsi']  = $value;
          }

          $this->db->trans_begin(); // Memulai transaksi

          // Langkah 1: Hapus data
          $this->db->where($wr)->delete('jabatan_fungsi');

          // Langkah 2: Input batch data
          $this->db->insert_batch('jabatan_fungsi', $insert);

          // Cek jika transaksi berhasil atau gagal
          if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback(); // Transaksi gagal, rollback
            $ret['ok'] = 500;
            $ret['form'] = 'Gagal Update Data';
          } else {
            $this->db->trans_commit(); // Transaksi berhasil, commit
            $ret['ok'] = 200;
            $ret['form'] = 'Sukses Update Data';
          }
        }
      } else {
        $wr1['prt_nama']     = strtoupper($this->input->post('prt_nama'));
        $perangkat = $this->db->get_where('perangkat', $wr1);
        if ($perangkat->num_rows() > 0) {
          $wr['prj_perangkat']  = $perangkat->row()->prt_id;
          $wr['fun_jabatan']    = $this->input->post('fun_jabatan');
          $wr['fun_status']     = 1;
          $row = $this->db->get_where('perangkat_jabatan', $wr);

          if ($row->num_rows() < 1) {
            $data = [];
            $data = $wr;
            if ($this->db->insert('perangkat_jabatan', $data)) {
              $ret['ok']    = 200;
              $ret['form']  = 'Sukses Insert Data';
            } else {
              $ret['ok']    = 500;
              $ret['form']  = 'Gagal Insert Data';
            }
          }
        } else {
          $data_prt = [
            'prt_nama' => strtoupper($this->input->post('prt_nama'))
          ];
          $perangkat = $this->db->insert('perangkat', $data_prt);
          if ($perangkat) {
            $data['prj_perangkat'] = $this->db->insert_id();
            $data['fun_jabatan']    = $this->input->post('fun_jabatan');
            $data['fun_status']     = 1;
            if ($this->db->insert('perangkat_jabatan', $data)) {
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
      $ret['form']['jf_jabatan'] = form_error('jf_jabatan');
      $ret['form']['jf_fungsi']    = form_error('jf_fungsi');
      $ret['ok']    = 400;
    }
    echo json_encode($ret);
  }

  public function delete()
  {
    if ($this->input->post('id')) {
      $wr['jf_jabatan'] = $this->input->post('id');
      if ($this->db->update('jabatan_fungsi', ['jf_status' => 0], $wr)) {
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
        'field' => 'jf_jabatan',
        'label' => 'Jabatan',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus diisi',
        ],
      ],
      // [
      //   'field' => 'jf_fungsi',
      //   'label' => 'Fungsi Perangkat',
      //   'rules' => 'required',
      //   'errors' => [
      //     'required' => '{field} harus diisi',
      //   ],
      // ],
    ];

    $jf_fungsi = $this->input->post('jf_fungsi');
    if (!empty($jf_fungsi)) {
      foreach ($jf_fungsi as $index => $value) {
        $field = "jf_fungsi[$index]";
        $label = "Fungsi Perangkat (indeks $index)";
        $this->form_validation->set_rules($field, $label, 'required', [
          'required' => '{field} harus diisi'
        ]);
      }
    }

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
      $ids = $this->db->select('jf_jabatan')->from('jabatan_fungsi')->where('jf_status', 1)->get();
      if ($ids->num_rows() > 0) {
        $this->db->where_not_in(
          'jbt_id',
          array_column(
            $ids->result_array(),
            'jf_jabatan'
          )
        );
      }
    }

    $data = $this->db->get_where('jabatan', ['jbt_status' => 1]);
    echo json_encode($data->result());
  }

  private function getFungsiJabatan($jbt_id)
  {
    $this->db->join('jabatan_fungsi', 'jabatan_fungsi.jf_jabatan = jabatan.jbt_id', 'left');
    $this->db->join('fungsi', 'fungsi.fun_id = jabatan_fungsi.jf_fungsi', 'left');
    $this->db->where(['jf_status' => 1]);
    $this->db->where(['jf_status' => 1]);
    $this->db->where(['jbt_status' => 1]);

    $i = 0;
    foreach ($this->column_search_fungsi as $item) {
      if ($this->input->post('search')['value']) {
        if ($i === 0) {
          $this->db->group_start();
          $this->db->like($item, $this->input->post('search')['value']);
        } else {
          $this->db->or_like($item, $this->input->post('search')['value']);
        }
        if (count($this->column_search_fungsi) - 1 == $i)
          $this->db->group_end();
      }
      $i++;
    }

    $data = $this->db->get_where('jabatan', ['jbt_id' => $jbt_id]);

    $list = '';

    if ($data->num_rows()) {
      $list .= '<table class="" style="width: 100%;">';
      $list .= '<tr>';
      $list .= '<th class="text-left">No</th>';
      $list .= '<th class="text-left">Fungsi</th>';
      $list .= '<th class="text-left">Kegiatan</th>';
      $list .= '</tr>';
      foreach ($data->result() as $k => $v) {
        $k++;
        $list .= '<tr>';
        $list .= '<td class="text-left">' . $k . '</td>';
        $list .= '<td class="text-left">' . $v->fun_nama . '</td>';
        $list .= '<td class="text-left">' . $this->getKegiatanJabatan($jbt_id, $v->fun_id) . '</td>';
        $list .= '</tr>';
      }
      $list .= '</table>';
    }

    return $list;
  }

  function getKegiatanJabatan($jbt_id, $fun_id)
  {
    $ret = '-';
    $wr['keg_jabatan']  = $jbt_id;
    $wr['keg_fungsi']   = $fun_id;
    $wr['keg_status']   = 1;
    $keg = $this->db->get_where('kegiatan', $wr);
    if ($keg->num_rows()) {
      // $ret = '<div class="btn-group" role="group">
      //           <a href="' . site_url('admin/kegiatan/detail/' . $jbt_id . '/' . $fun_id) . '" class="btn btn-icon btn-info text-white detail-data" data-id="' . (string)$keg->row()->keg_jabatan . '" data-name="' . (string)$keg->row()->keg_nama . '">
      //             <i class="fa fa-list"></i>
      //           </a>
      //         </div>';
      $ret = '<div class="btn-group" role="group">
                <button class="btn btn-icon btn-info text-white detail-kegiatan" onclick="detaiKegiatan(' . $jbt_id . ', ' . $fun_id . ')">
                  <i class="fa fa-list"></i>
                </button>
              </div>';
    }
    return $ret;
  }

  function getFungsi()
  {
    if ($this->input->get('q')) {
      $this->db->like('prt_nama', $this->input->get('q'));
    }
    $data = $this->db->get_where('perangkat', ['prt_status' => 1]);
    $ret = array_column($data->result_array(), 'prt_nama');
    header('Content-Type: application/json');
    echo json_encode($ret);
  }

  function getFungsiData()
  {
    // if ($this->input->post('id')) {
    //   if ($this->input->post('is_edit')) {
    //     $this->db->where(
    //       'jbt_id',
    //       $this->input->post('id')
    //     );
    //   }
    // } else {
    //   $ids = $this->db->select('jf_jabatan')->from('jabatan_fungsi')->where('jf_status', 1)->get();
    //   if ($ids->num_rows() > 0) {
    //     $this->db->where_not_in(
    //       'jbt_id',
    //       array_column(
    //         $ids->result_array(),
    //         'jf_jabatan'
    //       )
    //     );
    //   }
    // }

    $data = $this->db->get_where('fungsi', ['fun_status' => 1]);
    echo json_encode($data->result());
  }
}

/* End of file Fungsi.php */
