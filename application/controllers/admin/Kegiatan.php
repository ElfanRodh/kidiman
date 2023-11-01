<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kegiatan extends CI_Controller
{

  var $column_order   = array(null, 'jbt_nama', 'keg_nama');
  var $column_search   = array('prt_nama', 'jbt_nama', 'keg_nama');
  var $column_search_kegiatan   = array('jbt_nama', 'keg_nama');
  var $order = array('jbt_id' => 'asc', 'jbt_nama' => 'asc', 'keg_nama' => 'asc');

  public function index()
  {
    $data = array(
      'title' => "Kegiatan Perangkat Desa"
    );
    $this->load->view('admin/kegiatan/index', $data);
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
    foreach ($list as $keg) {
      $no++;
      $row                = array();
      $row['no']          = $no;
      $row['jbt_nama']    = $keg->jbt_nama . '<br> (' . $keg->prt_nama . ')';
      // $row['fun_nama']    = $keg->fun_nama;
      $row['kegiatan']      = $this->getKegiatanJabatan($keg->jbt_id);
      $row['opsi']        = '<div class="btn-group" role="group">
                              <button class="btn btn-icon btn-warning update-data" data-id="' . (string)$keg->jbt_id . '">
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
    // $this->db->join('fungsi', 'fungsi.fun_id = kegiatan.keg_fungsi', 'left');
    $this->db->join('perangkat_jabatan', 'perangkat_jabatan.prj_jabatan = kegiatan.keg_jabatan', 'left');
    $this->db->join('perangkat', 'perangkat.prt_id = perangkat_jabatan.prj_perangkat', 'left');
    $this->db->where(['keg_status' => 1]);
    $this->db->where(['jbt_status' => 1]);
    // $this->db->where(['fun_status' => 1]);
    $this->db->where(['prj_status' => 1]);
    $this->db->where(['prt_status' => 1]);
    $this->db->group_by('jbt_id');
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
      $this->db->where(['keg_id' => $this->input->post('id')]);
    }
    $this->db->join('jabatan', 'jabatan.jbt_id = kegiatan.keg_jabatan', 'left');
    $dt = $this->db->get_where('kegiatan', ['keg_status' => '1']);

    $data['ok'] = 500;
    $data['data'] = 'Data Tidak Ada';
    if ($dt->num_rows() > 0) {
      $data['ok']   = 200;
      $data['data']  = $dt->row_array();
      $data['data']['tanggal_mulai']  = date('d/m/Y', strtotime($dt->row_array()['keg_tanggal_mulai']));
      $data['data']['tanggal_selesai']  = date('d/m/Y', strtotime($dt->row_array()['keg_tanggal_selesai']));
      $data['data']['progres']  = $this->getProgresKegiatan($dt->row_array()['keg_id']);
    }

    echo json_encode($data);
  }

  public function addOrEdit()
  {
    $cek = $this->validateData();
    if ($cek) {
      if ($this->input->post('keg_edit')) {
        $wr['keg_jabatan'] = $this->input->post('keg_jabatan');
        $wr['keg_fungsi'] = $this->input->post('keg_fungsi');
        $wr['keg_nama'] = $this->input->post('keg_nama');
        $tanggal = explode(' - ', $this->input->post('keg_tanggal'));
        $wr['keg_tanggal_mulai']   = date('Y-m-d', strtotime($tanggal[0]));
        $wr['keg_tanggal_selesai'] = date('Y-m-d', strtotime($tanggal[1]));

        $cek = $this->db->where('keg_id !=', $this->input->post('keg_id'))->get_where('kegiatan', $wr);
        if ($cek->num_rows()) {
          $ret['ok'] = 500;
          $ret['form'] = 'Data Sudah Ada Sebelumnya';
        } else {
          $data = [];
          $data = $wr;

          $program_id = $this->db->get_where('progres_kegiatan', ['prog_kegiatan' => $this->input->post('keg_id'), 'prog_persentase' => 0])->row()->prog_id;

          if ($this->input->post('keg_foto')) {
            $foto = $this->input->post('keg_foto');
            $nama_file = FCPATH . 'public/progress/' . str_replace(base_url() . 'public/progress/', '', $this->input->post('keg_foto_old'));

            if (file_exists($nama_file)) {
              unlink($nama_file);
            }
          } else {
            $foto = str_replace(base_url() . 'public/progress/', '', $this->input->post('keg_foto_old'));
          }


          $this->db->trans_begin(); // Memulai transaksi

          // Langkah 2: Input batch data
          $this->db->update('kegiatan', $data, ['keg_id' => $this->input->post('keg_id')]);
          $this->db->update('progres_kegiatan', ['prog_keterangan' => 'Mulai Kegiatan <br>' . $this->input->post('keg_nama'), 'prog_bukti' => $foto], ['prog_id' => $program_id]);

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
        $wr1['keg_nama']        = $this->input->post('keg_nama');
        $wr1['keg_jabatan']     = $this->input->post('keg_jabatan');
        $wr1['keg_fungsi']      = $this->input->post('keg_fungsi');
        $tanggal = explode(' - ', $this->input->post('keg_tanggal'));
        $wr1['keg_tanggal_mulai']   = date('Y-m-d', strtotime($tanggal[0]));
        $wr1['keg_tanggal_selesai'] = date('Y-m-d', strtotime($tanggal[1]));
        $kegiatan = $this->db->get_where('kegiatan', $wr1);
        if ($kegiatan->num_rows() > 0) {
          $ret['ok']    = 500;
          $ret['form']  = 'Data Sudah Ada Sebelumnya';
        } else {
          $data = [];
          $data = $wr1;
          $data['keg_progres'] = 0;
          $this->db->trans_begin(); // Memulai transaksi
          if ($this->db->insert('kegiatan', $data)) {
            $data_progres = [
              'prog_kegiatan' => $this->db->insert_id(),
              'prog_persentase' => 0,
              'prog_keterangan' => 'Mulai Kegiatan <br>' . $this->input->post('keg_nama'),
              'prog_tanggal'  => date('Y-m-d H:i:s'),
              'prog_bukti'    => $this->input->post('keg_foto'),
            ];
            if ($this->db->insert('progres_kegiatan', $data_progres)) {
              $ret['ok']    = 200;
              $ret['form']  = 'Sukses Insert Data';
            }
            if ($this->db->trans_status() === FALSE) {
              $this->db->trans_rollback();
              $ret['ok']    = 500;
              $ret['form']  = 'Gagal Insert Data';
            } else {
              $this->db->trans_commit(); // Transaksi berhasil, commit
              $ret['ok']    = 200;
              $ret['form']  = 'Sukses Insert Data';
            }
          } else {
            $ret['ok']    = 500;
            $ret['form']  = 'Gagal Insert Data';
          }
        }
      }
    } else {
      $ret['form']['keg_jabatan'] = form_error('keg_jabatan');
      $ret['form']['keg_fungsi']  = form_error('keg_fungsi');
      $ret['form']['keg_tanggal'] = form_error('keg_tanggal');
      $ret['form']['keg_nama'] = form_error('keg_nama');
      if ($this->input->post('keg_edit')) {
        $ret['form']['keg_foto_old'] = form_error('keg_foto_old');
      } else {
        $ret['form']['keg_foto'] = form_error('keg_foto');
      }
      $ret['ok']    = 400;
    }
    echo json_encode($ret);
  }

  public function delete()
  {
    if ($this->input->post('id')) {
      $wr['keg_id'] = $wr2['prog_kegiatan'] = $this->input->post('id');
      $this->db->trans_begin(); // Memulai transaksi

      $this->db->update('kegiatan', ['keg_status' => 0], $wr);
      $this->db->update('progres_kegiatan', ['prog_status' => 0], $wr2);

      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        $ret['ok']    = 500;
        $ret['data']  = 'Gagal Menghapus Data';
      } else {
        $this->db->trans_commit(); // Transaksi berhasil, commit
        $ret['ok']    = 200;
        $ret['data']  = 'Sukses Menghapus Data';
      }
    }
    echo json_encode($ret);
  }

  private function validateData()
  {
    $this->load->library('form_validation');
    $config = [
      [
        'field' => 'keg_jabatan',
        'label' => 'Jabatan',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus diisi',
        ],
      ],
      [
        'field' => 'keg_fungsi',
        'label' => 'Fungsi',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus diisi',
        ],
      ],
      [
        'field' => 'keg_tanggal',
        'label' => 'Tanggal Kegiatan',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus diisi',
        ],
      ],
      [
        'field' => 'keg_nama',
        'label' => 'Nama Kegiatan',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus diisi',
        ],
      ]
    ];

    if ($this->input->post('keg_edit')) {
      $config[] = [
        'field' => 'keg_foto_old',
        'label' => 'Foto Kegiatan',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus diisi',
        ],
      ];
    } else {
      $config[] = [
        'field' => 'keg_foto',
        'label' => 'Foto Kegiatan',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus diisi',
        ],
      ];
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
      // $ids = $this->db->select('keg_jabatan')->from('kegiatan')->where('keg_status', 1)->get();
      // if ($ids->num_rows() > 0) {
      //   $this->db->where_not_in(
      //     'jbt_id',
      //     array_column(
      //       $ids->result_array(),
      //       'keg_jabatan'
      //     )
      //   );
      // }
    }

    $data = $this->db->get_where('jabatan', ['jbt_status' => 1]);
    echo json_encode($data->result());
  }

  function getKegiatan()
  {
    $wr['keg_jabatan'] = $this->input->post('jbt_id');
    $wr['keg_fungsi'] = $this->input->post('fun_id');

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

  function getFungsiData()
  {
    $fun_jabatan = $this->input->post('id');
    $this->db->join('fungsi', 'fungsi.fun_id = jabatan_fungsi.jf_fungsi', 'left');
    $data = $this->db->get_where('jabatan_fungsi', ['jf_jabatan' => $fun_jabatan]);
    echo json_encode($data->result());
  }

  private function getKegiatanJabatan($jbt_id)
  {
    $this->db->join('jabatan', 'jabatan.jbt_id = kegiatan.keg_jabatan', 'left');
    $this->db->join('fungsi', 'fungsi.fun_id = kegiatan.keg_fungsi', 'left');
    $this->db->where(['keg_status' => 1]);
    $this->db->where(['fun_status' => 1]);

    $i = 0;
    foreach ($this->column_search_kegiatan as $item) {
      if ($this->input->post('search')['value']) {
        if ($i === 0) {
          $this->db->group_start();
          $this->db->like($item, $this->input->post('search')['value']);
        } else {
          $this->db->or_like($item, $this->input->post('search')['value']);
        }
        if (count($this->column_search_kegiatan) - 1 == $i)
          $this->db->group_end();
      }
      $i++;
    }

    $data = $this->db->get_where('kegiatan', ['keg_jabatan' => $jbt_id]);

    $list = '';

    if ($data->num_rows()) {
      $list .= '<table class="" style="width: 100%;">';
      $list .= '<tr>';
      $list .= '<th class="text-left">No</th>';
      $list .= '<th class="text-left">Kegiatan</th>';
      $list .= '<th class="text-center">Pelaksanaan</th>';
      $list .= '<th class="text-center">Progres</th>';
      $list .= '<th class="text-center">Aksi</th>';
      $list .= '</tr>';
      foreach ($data->result() as $k => $v) {
        $k++;
        $list .= '<tr>';
        $list .= '<td class="text-left">' . $k . '</td>';
        $list .= '<td class="text-left">
                    <p class="p-0 m-0"><strong>' . $v->keg_nama . '</strong></p>
                    Fungsi : ' . $v->fun_nama . '
                  </td>';
        $list .= '<td class="text-center">' . date('d-m-Y', strtotime($v->keg_tanggal_mulai)) . '<br> s/d <br>' . date('d-m-Y', strtotime($v->keg_tanggal_selesai)) . '</td>';
        $list .= '
              <td class="text-center" style="width: 25%;">
                <div class="progress">
                  <div class="progress-bar" role="progressbar" data-width="' . $v->keg_progres . '%" aria-valuenow="' . $v->keg_progres . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $v->keg_progres . '%;">' . $v->keg_progres . '%</div>
                </div>
                <div class="btn-group mt-2" role="group">
                  <button class="btn btn-sm btn-icon btn-success add-progres" data-id="' . (string)$v->keg_id . '" data-name="' . strip_tags($v->keg_nama) . '">
                    <i class="fa fa-plus mr-1"></i> Progres
                  </button>
                </div>  
              </td>
              ';
        $list .= '<td class="text-center">
                    <div class="btn-group" role="group">
                      <button class="btn btn-icon btn-info" onclick="detaiKegiatan(' . (string)$v->keg_id . ')">
                        <i class="fa fa-list"></i>
                      </button>
                      <button class="btn btn-icon btn-warning update-data" data-id="' . (string)$v->keg_id . '">
                        <i class="fa fa-edit"></i>
                      </button>
                      <button class="btn btn-icon btn-danger delete-data" data-id="' . (string)$v->keg_id . '" data-name="' . strip_tags((string)$v->keg_nama) . '">
                        <i class="fa fa-trash"></i>
                      </button>
                    </div>          
                  </td>';
        $list .= '</tr>';
      }
      $list .= '</table>';
    }

    return $list;
  }

  function detaiKegiatan()
  {
    $wr['keg_id'] = $this->input->post('keg_id');
    $kegiatan = $this->db->get_where('kegiatan', $wr);

    $data = [];
    foreach ($kegiatan->result() as $k => $v) {
      $data['keg_nama'] = $v->keg_nama;
      $data['keg_progres'] = $v->keg_progres;
      $data['progres']  = $this->getProgresKegiatan($v->keg_id);
    }

    echo json_encode($data);
  }

  function getProgresKegiatan($prog_kegiatan)
  {
    $progres = $this->db->order_by('prog_persentase')->get_where('progres_kegiatan', ['prog_kegiatan' => $prog_kegiatan]);
    $data = [];
    foreach ($progres->result() as $k => $v) {
      $data[$k]['prog_persentase'] = $v->prog_persentase;
      if ($v->prog_bukti && file_exists(FCPATH . 'public/progress/' . $v->prog_bukti)) {
        $file = base_url() . 'public/progress/' . $v->prog_bukti;
      } else {
        $file = 0;
      }
      $data[$k]['prog_bukti'] = $file;
      $data[$k]['prog_keterangan'] = $v->prog_keterangan;
      $data[$k]['prog_tanggal'] = date('d-m-Y H:i:s', strtotime($v->prog_tanggal));
    }
    return $data;
  }

  function uploadSingleDokumen()
  {
    $ret['ok']    = 200;
    $ret['form']  = 'Sukses Upload File';

    if (!empty($_FILES['file']["name"]) && $_FILES['file']["error"] === 0) {
      $f_type = strtolower(pathinfo($_FILES['file']["name"], PATHINFO_EXTENSION));
      $config['upload_path']    = './public/progress/';
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
          $this->compressImage('./public/progress/' . $file, 80); // Nilai 80 adalah tingkat kualitas kompresi gambar, sesuaikan dengan kebutuhan Anda
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

  function compressImage($source_path, $quality)
  {
    $config['image_library'] = 'gd2';
    $config['source_image'] = $source_path;
    $config['create_thumb'] = FALSE;
    $config['maintain_ratio'] = TRUE;
    $config['quality'] = $quality;
    $config['width'] = 800;
    $config['height'] = 600;

    $this->load->library('image_lib');
    $this->image_lib->initialize($config);

    if (!$this->image_lib->resize()) {
      // Jika gagal melakukan kompresi, Anda dapat menangani kesalahan di sini
      log_message('error', 'Gagal melakukan kompresi gambar: ' . $this->image_lib->display_errors());
    }
  }
}

/* End of file Kegiatan.php */
