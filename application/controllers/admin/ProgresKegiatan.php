<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ProgresKegiatan extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->helper('app');
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
    }

    echo json_encode($data);
  }

  public function addOrEdit()
  {
    $cek = $this->validateData();
    if ($cek) {
      if ($this->input->post('prog_id')) {
        $wr['prog_kegiatan']     = $this->input->post('keg_id');
        $wr['prog_persentase']   = $this->input->post('prog_persentase');
        $wr['prog_tanggal']      = date('Y-m-d', strtotime($this->input->post('prog_tanggal'))) . ' ' . date('H:i:s');

        $cek = $this->db->where('prog_id !=', $this->input->post('prog_id'))->get_where('progres_kegiatan', $wr);
        if ($cek->num_rows()) {
          $ret['ok'] = 500;
          $ret['form'] = 'Data Sudah Ada Sebelumnya';
        } else {
          $data = [];
          $data = $wr;

          if ($this->input->post('prog_bukti')) {
            $foto = $this->input->post('prog_bukti');
            $nama_file = FCPATH . 'public/progress/' . str_replace(base_url() . 'public/progress/', '', $this->input->post('prog_bukti_old'));

            if (file_exists($nama_file)) {
              unlink($nama_file);
            }
          } else {
            $foto = str_replace(base_url() . 'public/progress/', '', $this->input->post('prog_bukti_old'));
          }

          $this->db->trans_begin(); // Memulai transaksi

          // Langkah 2: Input data
          $data['prog_bukti'] = $foto;
          $data['prog_keterangan'] = $this->input->post('prog_keterangan');
          $this->db->update('progres_kegiatan', $data, ['prog_id' => $this->input->post('prog_id')]);

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
          $data['prog_bukti'] = $this->input->post('prog_bukti');
          $this->db->trans_begin(); // Memulai transaksi

          $this->db->insert('progres_kegiatan', $data);

          $this->db->update('kegiatan', ['keg_progres' => $this->input->post('prog_persentase')], ['keg_id' => $this->input->post('keg_id')]);

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
        }
      }
    } else {
      $ret['form']['keg_id'] = form_error('keg_id');
      $ret['form']['prog_persentase']  = form_error('prog_persentase');
      $ret['form']['prog_keterangan'] = form_error('prog_keterangan');
      $ret['form']['prog_tanggal'] = form_error('prog_tanggal');
      if ($this->input->post('prog_id')) {
        $ret['form']['prog_bukti_old'] = form_error('prog_bukti_old');
      } else {
        $ret['form']['prog_bukti'] = form_error('prog_bukti');
      }
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

    if ($this->input->post('prog_id')) {
      $config[] = [
        'field' => 'prog_bukti_old',
        'label' => 'Foto Kegiatan',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus diisi',
        ],
      ];
    } else {
      $config[] = [
        'field' => 'prog_bukti',
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
}

/* End of file ProgresKegiatan.php */
