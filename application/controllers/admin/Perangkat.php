<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Perangkat extends CI_Controller
{

  var $column_order   = array(null, 'jbt_nama', 'prt_nama');
  var $column_search   = array('jbt_nama', 'prt_nama');
  var $order = array('jbt_id' => 'asc', 'jbt_nama' => 'asc', 'prt_nama' => 'asc');


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
      'title' => "Perangkat Desa"
    );
    $this->load->view('admin/perangkat/index', $data);
  }

  public function viewData()
  {
    $list = $this->getPerangkatList();
    $data = array();
    $no   = $this->input->post('start');
    $v    = 0;
    foreach ($list as $prt) {
      $no++;
      $row                = array();
      $row['no']          = $no;
      $row['prt_nama']    = $prt->prt_nama;
      $row['prt_jk']      = $prt->prt_jk == 1 ? 'L' : 'P';

      $nama_file = FCPATH . 'public/perangkat/' . str_replace(base_url() . 'public/perangkat/', '', $prt->prt_foto);

      if ($prt->prt_foto && file_exists($nama_file)) {
        $foto = '<img class="img rounded" style="height: 125px; width: auto;" src="' . base_url('public/perangkat/' . $prt->prt_foto) . '"/>';
      } else {
        if ($prt->prt_jk == 1) {
          $foto = '<img class="img rounded" style="height: 125px; width: auto;" src="' . base_url('public/perangkat/man.PNG') . '"/>';
        } else {
          $foto = '<img class="img rounded" style="height: 125px; width: auto;" src="' . base_url('public/perangkat/woman.PNG') . '"/>';
        }
      }

      $row['prt_foto']    = $foto;
      $row['jbt_nama']    = $prt->jbt_nama;
      $row['opsi']     = '<div class="btn-group" role="group">
									<button class="btn btn-icon btn-warning update-data" data-id="' . (string)$prt->prj_id . '">
										<i class="fa fa-edit"></i>
									</button>
									<button class="btn btn-icon btn-danger delete-data" data-id="' . (string)$prt->prj_id . '" data-name="' . (string)$prt->jbt_nama . '">
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

  public function getPerangkatList($where = null)
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
    $this->db->join('perangkat', 'perangkat.prt_id = perangkat_jabatan.prj_perangkat', 'left');
    $this->db->join('jabatan', 'jabatan.jbt_id = perangkat_jabatan.prj_jabatan', 'left');
    $this->db->where(['prt_status' => 1]);
    $this->db->where(['jbt_status' => 1]);
    $this->db->where(['prj_status' => 1]);
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
      $this->db->where(['prj_id' => $this->input->post('id')]);
    }
    $this->db->join('perangkat', 'perangkat.prt_id = perangkat_jabatan.prj_perangkat', 'left');
    $this->db->join('jabatan', 'jabatan.jbt_id = perangkat_jabatan.prj_jabatan', 'left');

    $dt = $this->db->get_where('perangkat_jabatan', ['prj_status' => '1']);

    $data['ok'] = 500;
    $data['data'] = 'Data Tidak Ada';
    if ($dt->num_rows() > 0) {
      $prt = $dt->row_array();
      if ($prt['prt_foto']) {
        $foto = base_url() . 'public/perangkat/' . $prt['prt_foto'];
      } else {
        if ($prt['prt_jk'] == 1) {
          $foto = base_url() . 'public/perangkat/man.PNG';
        } else {
          $foto = base_url() . 'public/perangkat/woman.PNG';
        }
      }
      $prt['prt_foto'] = $foto;
      $data['ok']   = 200;
      $data['data']  = $prt;
    }

    echo json_encode($data);
  }

  public function addOrEdit()
  {
    $cek = $this->validateData();
    if ($cek) {
      if ($this->input->post('prj_id')) {
        $wr['prj_id'] = $this->input->post('prj_id');

        $data = [];
        $pass = ['prj_id', 'prt_foto', 'prt_foto_old'];
        foreach ($_POST as $k => $v) {
          if (!in_array($k, $pass)) {
            $data[$k] = $v;
          }
        }
        $ex = $this->db
          ->where(['prj_id !=' => $this->input->post('prj_id')])
          ->join('perangkat', 'perangkat.prt_id = perangkat_jabatan.prj_perangkat', 'left')
          ->get_where('perangkat_jabatan', $data);
        if ($ex->num_rows() > 0) {
          $ret['ok'] = 500;
          $ret['form'] = 'Data Sudah Ada Sebelumnya';
        } else {
          $update_prj = $this->db->update('perangkat_jabatan', [
            'prj_jabatan' => $this->input->post('prj_jabatan')
          ], $wr);
          $wr2['prt_id'] = $this->db->get_where('perangkat_jabatan', $wr)->row()->prj_perangkat;
          if ($this->input->post('prt_foto')) {
            $foto = $this->input->post('prt_foto');
            $nama_file = FCPATH . 'public/perangkat/' . str_replace(base_url() . 'public/perangkat/', '', $this->input->post('prt_foto_old'));

            if ($this->input->post('prt_foto_old') && file_exists($nama_file)) {
              unlink($nama_file);
            }
          } else {
            $foto = str_replace(base_url() . 'public/perangkat/', '', $this->input->post('prt_foto_old'));
          }
          $update_prt = $this->db->update('perangkat', [
            'prt_nama'  => $this->input->post('prt_nama'),
            'prt_jk'    => $this->input->post('prt_jk'),
            'prt_foto'  => $foto,
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
        $wr1['prt_nama']     = strtoupper($this->input->post('prt_nama'));
        $perangkat = $this->db->get_where('perangkat', $wr1);
        if ($perangkat->num_rows() > 0) {
          $wr['prj_perangkat']  = $perangkat->row()->prt_id;
          $wr['prj_jabatan']    = $this->input->post('prj_jabatan');
          $wr['prj_status']     = 1;
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
            'prt_nama'  => strtoupper($this->input->post('prt_nama')),
            'prt_jk'    => $this->input->post('prt_jk'),
            'prt_foto'  => $this->input->post('prt_foto'),
          ];
          $perangkat = $this->db->insert('perangkat', $data_prt);
          if ($perangkat) {
            $data['prj_perangkat']  = $this->db->insert_id();
            $data['prj_jabatan']    = $this->input->post('prj_jabatan');
            $data['prj_status']     = 1;
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
      $ret['form']['prj_jabatan'] = form_error('prj_jabatan');
      $ret['form']['prt_nama']    = form_error('prt_nama');
      $ret['form']['prt_jk']      = form_error('prt_jk');
      $ret['ok']    = 400;
    }
    echo json_encode($ret);
  }

  public function delete()
  {
    if ($this->input->post('id')) {
      $wr['prj_id'] = $this->input->post('id');
      $jbt = $this->db->get_where('perangkat_jabatan', $wr)->row();
      $prt = $this->db->update('perangkat', ['prt_foto' => NULL], ['prt_id' => $jbt->prj_perangkat]);
      $prj = $this->db->update('perangkat_jabatan', ['prj_status' => 0], $wr);
      if ($prt && $prj) {
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
        'field' => 'prj_jabatan',
        'label' => 'Jabatan',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus diisi',
        ],
      ],
      [
        'field' => 'prt_nama',
        'label' => 'Nama Perangkat',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus diisi',
        ],
      ],
      [
        'field' => 'prt_jk',
        'label' => 'Jenis Kelamin',
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
    $ids = $this->db->select('prj_jabatan')->from('perangkat_jabatan')->where('prj_status', 1)->get();
    if ($ids->num_rows() > 0) {
      $this->db->where_not_in(
        'jbt_id',
        array_column(
          $ids->result_array(),
          'prj_jabatan'
        )
      );
    }

    if ($this->input->post('id')) {
      if ($this->input->post('is_edit')) {
        $this->db->or_where(
          'jbt_id',
          $this->input->post('id')
        );
      }
    }

    $data = $this->db->get_where('jabatan', ['jbt_status' => 1]);
    echo json_encode($data->result());
  }

  function getPerangkat()
  {
    if ($this->input->get('q')) {
      $this->db->like('prt_nama', $this->input->get('q'));
    }
    $data = $this->db->get_where('perangkat', ['prt_status' => 1]);
    $ret = array_column($data->result_array(), 'prt_nama');
    header('Content-Type: application/json');
    echo json_encode($ret);
  }

  function uploadSingleDokumen()
  {
    $ret['ok']    = 200;
    $ret['form']  = 'Sukses Upload File';

    if (!empty($_FILES['file']["name"]) && $_FILES['file']["error"] === 0) {
      $f_type = strtolower(pathinfo($_FILES['file']["name"], PATHINFO_EXTENSION));
      $config['upload_path']    = './public/perangkat/';
      $config['allowed_types']  = 'jpg|png|jpeg|pdf|JPG|PNG|JPEG';
      // $config['allowed_types']  = 'pdf|PDF';
      $config['max_size']      = 5120;
      $config['remove_spaces']  = TRUE;
      $ext = explode(".", $_FILES['file']["name"]);
      $config["file_name"]    = date('Y-m-d') . "-" . random_string("alnum", 20) . "." . strtolower(end($ext));
      $this->upload->initialize($config);
      $ret["form"] = [];
      if (!$this->upload->do_upload('file')) {
        $file  = NULL;
      } else {
        $file = $config["file_name"];

        // Cek tipe file
        if (in_array($f_type, ['jpg', 'jpeg', 'png'])) {
          // File adalah gambar, lakukan kompresi
          compressImage('./public/perangkat/' . $file, 80); // Nilai 80 adalah tingkat kualitas kompresi gambar, sesuaikan dengan kebutuhan Anda
        }
      }
    } else {
      $file  = NULL;
    }

    if (!$file) {
      $ret["ok"]      = 400;
      if ($this->upload->display_errors() == '<p>The filetype you are attempting to upload is not allowed.</p>') {
        $err = 'Tipe file tidak sesuai ketentuan';
      } else {
        $err = 'Gagal Upload File';
      }
      $ret["form"][$_POST['id']] = $err;
      echo json_encode($ret);
      exit();
    } else {
      $ret['file'] = $file;
      $ret["form"][$_POST['id']] = 'Upload Sukses';
    }
    echo json_encode($ret);
  }
}

/* End of file Perangkat.php */
