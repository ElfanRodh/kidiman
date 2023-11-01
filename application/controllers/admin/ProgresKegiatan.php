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
    $tgl = date('Y-m-d', strtotime($this->input->post('tgl')));
    $kegiatan = $this->db->get_where('kegiatan', ['keg_id' => $keg_id])->row();

    $persen = hitungPersentase($kegiatan->keg_tanggal_mulai, $kegiatan->keg_tanggal_selesai, $tgl);

    echo json_encode($persen);
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
        $wr1['prog_kegiatan']     = $this->input->post('keg_id');
        $wr1['prog_persentase']   = $this->input->post('prog_persentase');
        $wr1['prog_tanggal']      = date('Y-m-d H:i:s', strtotime($this->input->post('prog_tanggal')));
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
            $ret['ok']    = 200;
            $ret['form']  = 'Sukses Insert Data';
          }
        }
      }
    } else {
      $ret['form']['keg_id'] = form_error('keg_id');
      $ret['form']['prog_persentase']  = form_error('prog_persentase');
      $ret['form']['prog_bukti'] = form_error('prog_bukti');
      $ret['form']['prog_keterangan'] = form_error('prog_keterangan');
      $ret['form']['prog_tanggal'] = form_error('prog_tanggal');
      if ($this->input->post('keg_edit')) {
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
        'field' => 'prog_bukti',
        'label' => 'Bukti Kegiatan',
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

    if ($this->input->post('keg_edit')) {
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
