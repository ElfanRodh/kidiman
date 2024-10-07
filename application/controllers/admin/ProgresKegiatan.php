<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ProgresKegiatan extends CI_Controller
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


  function addProgres()
  {
    $keg_id = $this->input->post('keg_id');
    $prog_bukti = $this->input->post('prog_bukti');
    $prog_keterangan = $this->input->post('prog_keterangan');
    $prog_tanggal = $this->input->post('prog_tanggal');
    $kegiatan = $this->db->get_where('kegiatan', ['keg_id' => $keg_id])->row();

    $persen = hitungPersentase($kegiatan->keg_tanggal_mulai, $kegiatan->keg_tanggal_selesai, $prog_tanggal);

    echo json_encode($persen);
  }

  function getPersen()
  {
    $keg_id = $this->input->post('keg_id');
    $is_progres = $this->input->post('is_progres');
    $tgl = date('Y-m-d', strtotime($this->input->post('tgl')));
    $kegiatan = $this->db->get_where('kegiatan', ['keg_id' => $keg_id])->row();

    $persen = hitungPersentase($kegiatan->keg_tanggal_mulai, $kegiatan->keg_tanggal_selesai, $tgl, $keg_id, $is_progres);

    $persen['ok'] = true;
    $persen['message'] = 'OK';
    if ($persen['persentase'] < 0) {
      $persen['ok'] = false;
      $persen['message'] = 'Persentase Tidak Boleh Kurang dari 0%';
    } else if ($persen['persentase'] > 100) {
      $persen['persentase'] = 100;
      $persen['ok'] = true;
      $persen['message'] = 'Persentase Tidak Boleh Lebih dari 100% (Persentase otomatis dikonversi menjadi 100%)';
    }
    echo json_encode($persen);
  }

  function getData()
  {
    $id = $this->input->post('prog_id');
    $this->db->join('kegiatan', 'kegiatan.keg_id = progres_kegiatan.prog_kegiatan', 'left');
    $progres = $this->db->get_where('progres_kegiatan', ['prog_id' => $id]);

    $data['ok'] = 500;
    $data['data'] = 'Data Tidak Ada';
    if ($progres->num_rows() > 0) {
      $data['ok']   = 200;
      $data['data'] = $dt = $progres->row_array();
      if ($dt['prog_bukti'] && file_exists(FCPATH . 'public/progress/' . $dt['prog_bukti'])) {
        $file = base_url() . 'public/progress/' . $dt['prog_bukti'];
      } else {
        $file = 0;
      }
      $data['data']['prog_bukti'] = $file;
      // $data['data']['prog_tanggal'] = date('d-m-Y H:i:s', strtotime($dt['prog_tanggal']));
      $data['data']['prog_tanggal'] = $dt['prog_tanggal'];
      $data['data']['bukti'] = $this->getBuktiProgres($dt['prog_id'], $dt['prog_kegiatan']);
    }

    echo json_encode($data);
  }

  function getBuktiProgres($buk_progres, $buk_kegiatan)
  {
    $progres = $this->db->order_by('buk_tanggal')->get_where('bukti_kegiatan', ['buk_progres' => $buk_progres, 'buk_kegiatan' => $buk_kegiatan]);
    $data = [];
    foreach ($progres->result() as $k => $v) {
      $data[$k]['buk_id'] = $v->buk_id;
      $data[$k]['buk_kegiatan'] = $v->buk_kegiatan;
      if ($v->buk_foto && file_exists(FCPATH . 'public/progress/' . $v->buk_foto)) {
        $file = base_url() . 'public/progress/' . $v->buk_foto;
      } else {
        $file = 0;
      }
      $data[$k]['buk_foto'] = $file;
      $data[$k]['buk_tanggal'] = date('d-m-Y H:i:s', strtotime($v->buk_tanggal));
    }
    return $data;
  }

  public function addOrEdit()
  {
    $cek = $this->validateData();
    if ($cek) {
      if ($this->input->post('prog_id')) {
        $wr['prog_kegiatan']     = $this->input->post('keg_id');
        // $wr['prog_persentase']   = $this->input->post('prog_persentase');
        $wr['prog_tanggal']      = date('Y-m-d', strtotime($this->input->post('prog_tanggal'))) . ' ' . date('H:i:s');

        $cek = $this->db->where('prog_id !=', $this->input->post('prog_id'))->get_where('progres_kegiatan', $wr);
        if ($cek->num_rows()) {
          $ret['ok'] = 500;
          $ret['form'] = 'Data Sudah Ada Sebelumnya';
        } else {
          $data = [];
          $data = $wr;

          // if ($this->input->post('prog_bukti')) {
          //   $foto = $this->input->post('prog_bukti');
          //   $nama_file = FCPATH . 'public/progress/' . str_replace(base_url() . 'public/progress/', '', $this->input->post('prog_bukti_old'));

          //   if ($this->input->post('prog_bukti_old') && file_exists($nama_file)) {
          //     unlink($nama_file);
          //   }
          // } else {
          //   $foto = str_replace(base_url() . 'public/progress/', '', $this->input->post('prog_bukti_old'));
          // }

          $file_old = $this->input->post('prog_bukti_old');

          $file_upload = $this->uploadMultipleDokumen('prog_bukti');

          // echo json_encode($file_old);
          // exit();

          if ($file_upload['file']) {
            $file_arr = array_diff($file_old, $file_upload['file']);
            $hasil_gabungan = array_unique(array_merge($file_old, $file_upload['file']));

            $hasil_gabungan = array_map(function ($nilai) {
              return str_replace(base_url() . 'public/progress/', '', $nilai);
            }, $hasil_gabungan);

            $bukti_kegiatan = $this->db->get_where('bukti_kegiatan', ['buk_kegiatan' => $this->input->post('keg_id'), 'buk_progres' => $this->input->post('prog_id')]);

            foreach ($bukti_kegiatan->result() as $kb => $vb) {
              $nama_file = FCPATH . 'public/progress/' . $vb->buk_foto;
              $nama_file_url = FCPATH . 'public/progress/' . str_replace(base_url() . 'public/progress/', '', $vb->buk_foto);
              if (file_exists($nama_file) && !in_array($vb->buk_foto, $hasil_gabungan)) {
                unlink($nama_file);
                $this->db->delete('bukti_kegiatan', ['buk_foto' => $vb->buk_foto]);
              }
            }

            $data_bukti = [];
            foreach ($file_upload['file'] as $kf => $vf) {
              $data_bukti[$kf] = [
                'buk_progres'  => $this->input->post('prog_id'),
                'buk_kegiatan' => $this->input->post('keg_id'),
                'buk_tanggal'  => date('Y-m-d H:i:s'),
                'buk_foto'    => $vf,
              ];
            }
            if (!$data_bukti) {
              $ret['ok'] = 500;
              $ret['form'] = 'Foto tidak boleh kosong';
              echo json_encode($ret);
              exit();
            }
          } else if ($file_old) {
            $hasil_gabungan = $file_old;

            $hasil_gabungan = array_map(function ($nilai) {
              return str_replace(base_url() . 'public/progress/', '', $nilai);
            }, $hasil_gabungan);

            $bukti_kegiatan = $this->db->get_where('bukti_kegiatan', ['buk_kegiatan' => $this->input->post('keg_id'), 'buk_progres' => $this->input->post('prog_id')]);

            foreach ($bukti_kegiatan->result() as $kb => $vb) {
              $nama_file = FCPATH . 'public/progress/' . $vb->buk_foto;
              $nama_file_url = FCPATH . 'public/progress/' . str_replace(base_url() . 'public/progress/', '', $vb->buk_foto);
              if (file_exists($nama_file) && !in_array($vb->buk_foto, $hasil_gabungan)) {
                unlink($nama_file);
                $this->db->delete('bukti_kegiatan', ['buk_foto' => $vb->buk_foto]);
              }
            }

            $data_bukti = [];
          } else {
            $ret['ok'] = 500;
            $ret['form'] = 'Foto tidak boleh kosong';
            echo json_encode($ret);
            exit();
          }

          $this->db->trans_begin(); // Memulai transaksi

          // Langkah 2: Input data
          $data['prog_bukti'] = str_replace(base_url() . 'public/progress/', '', $hasil_gabungan[0]);
          $data['prog_keterangan'] = $this->input->post('prog_keterangan');
          $this->db->update('progres_kegiatan', $data, ['prog_id' => $this->input->post('prog_id')]);
          if ($data_bukti) {
            $this->db->insert_batch('bukti_kegiatan', $data_bukti);
          }

          // Cek jika transaksi berhasil atau gagal
          if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback(); // Transaksi gagal, rollback
            $ret['ok'] = 500;
            $ret['form'] = 'Gagal Update Data';
          } else {
            $this->db->trans_commit(); // Transaksi berhasil, commit

            $last_progres = $this->db->order_by('prog_tanggal DESC')->limit(1)->get_where('progres_kegiatan', ['prog_kegiatan' => $this->input->post('keg_id')])->row();
            $up = [];
            $up['keg_progres'] = $last_progres->prog_persentase;
            if ((int)$last_progres->prog_persentase >= 100) {
              $up['keg_is_selesai'] = 1;
            } else {
              $up['keg_is_selesai'] = 0;
            }

            $this->db->trans_begin(); // Memulai transaksi

            $this->db->update('kegiatan', $up, ['keg_id' => $this->input->post('keg_id')]);

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
        }
      } else {
        $wr1['prog_kegiatan']     = $this->input->post('keg_id');
        $wr1['prog_persentase']   = $this->input->post('prog_persentase');
        $wr1['prog_tanggal']      = date('Y-m-d', strtotime($this->input->post('prog_tanggal'))) . ' ' . date('H:i:s');
        $kegiatan = $this->db->get_where('progres_kegiatan', $wr1);
        if ($kegiatan->num_rows() > 0) {
          $ret['ok']    = 500;
          $ret['form']  = 'Data Sudah Ada Sebelumnya';
        } else {
          $data = [];
          $data = $wr1;
          $data['prog_keterangan'] = $this->input->post('prog_keterangan');
          // $data['prog_bukti'] = $this->input->post('prog_bukti');

          $file_upload = $this->uploadMultipleDokumen('prog_bukti');
          if ($file_upload['file']) {
            $this->db->trans_begin(); // Memulai transaksi

            $data['prog_bukti'] = $file_upload['file'][0];
            $this->db->insert('progres_kegiatan', $data);
            $prog_id  = $this->db->insert_id();
            $keg_id   = $this->input->post('keg_id');

            $this->db->update('kegiatan', ['keg_progres' => $this->input->post('prog_persentase')], ['keg_id' => $this->input->post('keg_id')]);

            $data_bukti = [];
            foreach ($file_upload['file'] as $kf => $vf) {
              $data_bukti[$kf] = [
                'buk_progres'  => $prog_id,
                'buk_kegiatan' => $keg_id,
                'buk_tanggal'  => date('Y-m-d H:i:s'),
                'buk_foto'    => $vf,
              ];
            }
            if ($this->db->insert_batch('bukti_kegiatan', $data_bukti)) {
              $ret['ok']    = 200;
              $ret['form']  = 'Sukses Insert Data';
            }

            if ($this->db->trans_status() === FALSE) {
              $this->db->trans_rollback();
              $ret['ok']    = 500;
              $ret['form']  = 'Gagal Insert Data';
            } else {
              $this->db->trans_commit(); // Transaksi berhasil, commit

              $last_progres = $this->db->order_by('prog_tanggal DESC')->limit(1)->get_where('progres_kegiatan', ['prog_kegiatan' => $this->input->post('keg_id')])->row();
              $up = [];
              $up['keg_progres'] = $last_progres->prog_persentase;
              if ((int)$last_progres->prog_persentase >= 100) {
                $up['keg_is_selesai'] = 1;
              } else {
                $up['keg_is_selesai'] = 0;
              }

              $this->db->trans_begin(); // Memulai transaksi

              $this->db->update('kegiatan', $up, ['keg_id' => $this->input->post('keg_id')]);

              if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $ret['ok'] = 500;
                $ret['form'] = 'Gagal Update Data';
              } else {
                $this->db->trans_commit(); // Transaksi berhasil, commit
                $ret['ok'] = 200;
                $ret['form'] = 'Sukses Update Data';
              }
            }
          } else {
            $ret['ok']    = 500;
            $ret['form'] = 'Foto tidak boleh kosong';
            echo json_encode($ret);
            exit();
          }
        }
      }
    } else {
      $ret['form']['keg_id'] = form_error('keg_id');
      $ret['form']['prog_persentase']  = form_error('prog_persentase');
      $ret['form']['prog_keterangan'] = form_error('prog_keterangan');
      $ret['form']['prog_tanggal'] = form_error('prog_tanggal');
      // if ($this->input->post('prog_id')) {
      //   $ret['form']['prog_bukti_old'] = form_error('prog_bukti_old');
      // } else {
      //   $ret['form']['prog_bukti'] = form_error('prog_bukti');
      // }
      $ret['ok']    = 400;
    }
    echo json_encode($ret);
  }

  private function validateData()
  {
    $this->load->library('form_validation');
    $config = [
      [
        'field' => 'keg_id',
        'label' => 'Kegiatan',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus diisi',
        ],
      ],
      [
        'field' => 'prog_persentase',
        'label' => 'Persentase',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus diisi',
        ],
      ],
      [
        'field' => 'prog_keterangan',
        'label' => 'Keterangan',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus diisi',
        ],
      ],
      [
        'field' => 'prog_tanggal',
        'label' => 'Tanggal',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus diisi',
        ],
      ]
    ];

    // if ($this->input->post('prog_id')) {
    //   $config[] = [
    //     'field' => 'prog_bukti_old',
    //     'label' => 'Foto Kegiatan',
    //     'rules' => 'required',
    //     'errors' => [
    //       'required' => '{field} harus diisi',
    //     ],
    //   ];
    // } else {
    //   $config[] = [
    //     'field' => 'prog_bukti',
    //     'label' => 'Foto Kegiatan',
    //     'rules' => 'required',
    //     'errors' => [
    //       'required' => '{field} harus diisi',
    //     ],
    //   ];
    // }

    $this->form_validation->set_rules($config);
    return $this->form_validation->run();
  }

  function uploadMultipleDokumen($frm = 'keg_foto')
  {
    $ret['ok']    = 200;
    $ret['form']  = 'Sukses Upload File';

    $files = $_FILES;
    $jumlahFile = 0;
    if (isset($files[$frm])) {
      $jumlahFile = count($files[$frm]['name']);
    }

    $data['totalFiles'] = [];
    $ret["form"] = [];
    if ($jumlahFile > 0) {
      for ($i = 0; $i < $jumlahFile; $i++) {
        if (!empty($_FILES[$frm]['name'][$i])) {
          $_FILES['file']['name'] = $_FILES[$frm]['name'][$i];
          $_FILES['file']['type'] = $_FILES[$frm]['type'][$i];
          $_FILES['file']['tmp_name'] = $_FILES[$frm]['tmp_name'][$i];
          $_FILES['file']['error'] = $_FILES[$frm]['error'][$i];
          $_FILES['file']['size'] = $_FILES[$frm]['size'][$i];

          $f_type = strtolower(pathinfo($_FILES[$frm]["name"][$i], PATHINFO_EXTENSION));
          $config['upload_path']    = './public/progress/';
          $config['allowed_types']  = 'jpg|png|jpeg|pdf|JPG|PNG|JPEG|PDF';
          $config['max_size']      = 10240;
          $config['remove_spaces']  = TRUE;

          $ext = explode(".", $_FILES[$frm]["name"][$i]);
          $config["file_name"]    = date('Y-m-d') . "-" . random_string("alnum", 20) . "." . strtolower(end($ext));
          $this->upload->initialize($config);

          if (!$this->upload->do_upload('file')) {
            $file  = NULL;
            if ($this->upload->display_errors() == '<p>The filetype you are attempting to upload is not allowed.</p>') {
              $file = 'Tipe file tidak sesuai ketentuan';
            } else {
              $file = 'Gagal Upload File';
            }
          } else {
            $file = $config["file_name"];

            // Cek tipe file
            if (in_array($f_type, ['jpg', 'jpeg', 'png'])) {
              // File adalah gambar, lakukan kompresi
              compressImage('./public/progress/' . $file, 80); // Nilai 80 adalah tingkat kualitas kompresi gambar, sesuaikan dengan kebutuhan Anda
            }

            $data['totalFiles'][$i] = $file;
          }
        }
      }
    }

    $ret['file'] = $data['totalFiles'];
    $ret['form'] = 'Upload Sukses';
    return $ret;
  }
}

/* End of file ProgresKegiatan.php */
