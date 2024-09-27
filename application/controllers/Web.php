<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Web extends CI_Controller
{
    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            $data['login'] = "LOGIN";
        } else {
            $data['login'] = "DASHBOARD";
        }

        $data['keg'] = $this->getTop_4_Kegiatan();
        $data['prt'] = $this->getPerangkat();

        $this->load->view('admin/landing/index', $data);
    }

    public function getTop_4_Kegiatan()
    {
        $this->db->select('keg_nama, fun_nama');
        $this->db->join('fungsi', 'fungsi.fun_id = kegiatan.keg_fungsi', 'left');
        $this->db->where(['keg_status' => 1]);
        $this->db->order_by('kegiatan.keg_id', 'ASC');
        $this->db->limit(4);
        $query = $this->db->get('kegiatan');

        return $query->result_array();
    }

    public function getPerangkat()
    {
        $this->db->select('p.prt_nama as nama, p.prt_jk as jk, p.prt_foto as foto, j.jbt_nama as jabatan, t.tgs_nama as tugas');
        $this->db->join('perangkat_jabatan as pj', 'pj.prj_perangkat = p.prt_id', 'left');
        $this->db->join('jabatan as j', 'j.jbt_id = pj.prj_jabatan', 'left');
        $this->db->join('jabatan_tugas as jt', 'j.jbt_id = jt.jt_jabatan', 'left');
        $this->db->join('tugas as t', 't.tgs_id = jt.jt_tugas', 'left');
        $this->db->where(['prt_status' => 1, 'prj_status' => 1, 'jt_status' => 1, 'tgs_status' => 1, 'jbt_status' => 1]);
        $this->db->order_by('j.jbt_id', 'ASC');
        $query = $this->db->get('perangkat as p');

        $prt = $query->result_array();

        $data = [];
        foreach ($prt as $key => $value) {
            $data[$key] = $value;
            $nama_file = FCPATH . 'public/perangkat/' . str_replace(base_url() . 'public/perangkat/', '', $value['foto']);

            if ($value['foto'] && file_exists($nama_file)) {
                $foto = base_url('public/perangkat/' . $value['foto']);
            } else {
                if ($value['jk'] == 1) {
                    $foto = base_url('public/perangkat/man.PNG');
                } else {
                    $foto = base_url('public/perangkat/woman.PNG');
                }
            }

            $data[$key]['foto'] = $foto;
        }


        return $data;
    }
}
