<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Web extends CI_Controller
{
    var $column_order   = array(null, 'jbt_nama', 'keg_nama');
    var $column_search   = array('prt_nama', 'jbt_nama', 'keg_nama');
    var $column_search_kegiatan   = array('jbt_nama', 'keg_nama');
    var $order = array('jbt_id' => 'asc', 'jbt_nama' => 'asc', 'keg_nama' => 'asc');

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

    public function kegiatan()
    {
        $data['prt'] = $this->getPerangkat();

        $this->load->view('admin/landing/kegiatan', $data);
    }

    public function getTop_4_Kegiatan()
    {
        $this->db->select('keg_nama, fun_nama');
        $this->db->join('fungsi', 'fungsi.fun_id = kegiatan.keg_fungsi', 'left');
        $this->db->where(['keg_status' => 1]);
        $this->db->order_by('kegiatan.keg_tanggal_mulai', 'DESC');
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

    public function getJabatan()
    {
        $wr['jbt_status'] = 1;
        $wr['prt_status'] = 1;
        $wr['prj_status'] = 1;
        $this->db->join('jabatan', 'jabatan.jbt_id = perangkat_jabatan.prj_jabatan', 'left');
        $this->db->join('perangkat', 'perangkat.prt_id = perangkat_jabatan.prj_perangkat', 'left');
        $data = $this->db->order_by('jbt_id')->get_where('perangkat_jabatan', $wr);
        echo json_encode($data->result());
    }

    // Kegiatan
    public function viewDataKegiatan()
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
            // $row['opsi']        = '<div class="btn-group" role="group">
            //                   <button type="button" class="btn btn-icon btn-warning update-data" data-toggle="tooltip" data-placement="top" title="Edit Data" data-original-title="Edit Data" data-id="' . (string)$keg->jbt_id . '">
            //                     <i class="fa fa-edit"></i>
            //                   </button>
            //                 </div>';
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
        $wr_admin = [];

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
        $this->db->where($wr_admin);
        $this->db->order_by('prj_jabatan');
        $this->db->order_by('keg_tanggal_mulai', 'DESC');
        $this->db->group_by('jbt_id');
        if ($where) {
            $this->db->where($where);
        }
        if ($this->input->post("fil_jabatan")) {
            $this->db->where(['jbt_id' => $this->input->post("fil_jabatan")]);
        }
        if ($this->input->post("fil_tanggal")) {
            $tanggal = explode(' - ', $this->input->post('fil_tanggal'));
            $keg_tanggal_mulai   = date('Y-m-d', strtotime($tanggal[0]));
            $keg_tanggal_selesai = date('Y-m-d', strtotime($tanggal[1]));
            $this->db->group_start();
            $this->db->where("keg_tanggal_mulai BETWEEN '$keg_tanggal_mulai' AND '$keg_tanggal_selesai'");
            $this->db->or_where("keg_tanggal_selesai BETWEEN '$keg_tanggal_mulai' AND '$keg_tanggal_selesai'");
            $this->db->group_end();
        }
        if ($this->input->post("fil_status")) {
            if ($this->input->post("fil_status") == 'selesai') {
                $status = 1;
                $this->db->where(['keg_is_selesai' => $status]);
            } else if ($this->input->post("fil_status") == 'proses') {
                $status = 0;
                $this->db->where(['keg_is_selesai' => $status]);
            }
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

    private function getKegiatanJabatan($jbt_id)
    {
        $this->db->join('jabatan', 'jabatan.jbt_id = kegiatan.keg_jabatan', 'left');
        $this->db->join('fungsi', 'fungsi.fun_id = kegiatan.keg_fungsi', 'left');
        $this->db->where(['keg_status' => 1]);
        $this->db->where(['fun_status' => 1]);

        if ($this->input->post("fil_tanggal")) {
            $tanggal = explode(' - ', $this->input->post('fil_tanggal'));
            $keg_tanggal_mulai   = date('Y-m-d', strtotime($tanggal[0]));
            $keg_tanggal_selesai = date('Y-m-d', strtotime($tanggal[1]));
            $this->db->group_start();
            $this->db->where("keg_tanggal_mulai BETWEEN '$keg_tanggal_mulai' AND '$keg_tanggal_selesai'");
            $this->db->or_where("keg_tanggal_selesai BETWEEN '$keg_tanggal_mulai' AND '$keg_tanggal_selesai'");
            $this->db->group_end();
        }

        if ($this->input->post("fil_status")) {
            if ($this->input->post("fil_status") == 'selesai') {
                $status = 1;
                $this->db->where(['keg_is_selesai' => $status]);
            } else if ($this->input->post("fil_status") == 'proses') {
                $status = 0;
                $this->db->where(['keg_is_selesai' => $status]);
            }
        }

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

        $this->db->order_by('keg_tanggal_mulai', 'DESC');
        $data = $this->db->get_where('kegiatan', ['keg_jabatan' => $jbt_id]);

        $list = '';

        if ($data->num_rows()) {
            $list .= '<table class="" style="width: 100%;">';
            $list .= '<tr>';
            $list .= '<th class="text-left">No</th>';
            $list .= '<th class="text-left">Kegiatan</th>';
            $list .= '<th class="text-center">Pelaksanaan</th>';
            $list .= '<th class="text-center">Progres</th>';
            $list .= '<th class="text-center">Detail</th>';
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
                $tgl_sekarang = date('Y-m-d');
                $add_btn = '';
                $list .= '<td class="text-center" style="width: 25%;">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" data-width="' . $v->keg_progres . '%" aria-valuenow="' . $v->keg_progres . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $v->keg_progres . '%; background-color: ' . setColor(number_format($v->keg_progres, 0)) . ';">' . $v->keg_progres . '%</div>
                            </div>
                            ' . $add_btn . '
                        </td>
                            ';
                $btn_detail = '<button type="button" class="btn btn-icon btn-info" data-toggle="tooltip" data-placement="top" title="Detail Kegiatan" onclick="detailKegiatan(' . (string)$v->keg_id . ')">
                                    Detail
                                </button>';
                $list .= '<td class="text-center">
                            <div class="btn-group" role="group">
                            ' . $btn_detail . '
                            </div>          
                        </td>';
                $list .= '</tr>';
            }
            $list .= '</table>';
        }

        return $list;
    }

    function detailKegiatan()
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
        $progres = $this->db->order_by('prog_tanggal')->get_where('progres_kegiatan', ['prog_kegiatan' => $prog_kegiatan]);
        $data = [];
        foreach ($progres->result() as $k => $v) {
            $data[$k]['prog_id'] = $v->prog_id;
            $data[$k]['prog_kegiatan'] = $v->prog_kegiatan;
            $data[$k]['prog_persentase'] = $v->prog_persentase;
            if ($v->prog_bukti && file_exists(FCPATH . 'public/progress/' . $v->prog_bukti)) {
                $file = base_url() . 'public/progress/' . $v->prog_bukti;
            } else {
                $file = 0;
            }
            $data[$k]['prog_bukti'] = $file;
            $data[$k]['prog_keterangan'] = $v->prog_keterangan;
            $data[$k]['prog_tanggal'] = date('d-m-Y H:i:s', strtotime($v->prog_tanggal));
            $data[$k]['bukti'] = $this->getBuktiProgres($v->prog_id, $prog_kegiatan);
        }
        return $data;
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

    public function storeIKM()
    {
        $cek = $this->validasiIKM();
        $ret = [];
        if ($cek['status'] == 1) {
            $this->load->library('user_agent');
            $data['sar_url']         = $_POST['sar_url'];
            $data['sar_nama']     = $_POST['sar_nama'];
            $data['sar_email']     = $_POST['sar_email'];
            $data['sar_no_hp']     = $_POST['sar_no_hp'];
            $data['sar_kritik'] = $_POST['sar_kritik'];
            $data['sar_rating'] = $_POST['sar_rating'];

            $data['sar_ip']     = $_SERVER['REMOTE_ADDR'];
            $data['sar_platform']     = $this->agent->platform();
            $data['sar_user_agent'] = $this->agent->agent_string();

            if ($this->db->insert('ref_saran', $data)) {
                $ret['status']     = 1;
                $ret['message'] = 'Terimakasih Atas Krik & Saran Anda';
                $ret['url_wa']     = $this->sendMessage();
            } else {
                $ret['status']     = 2;
                $ret['message'] = 'Ups, terjadi kesalahan';
            }
        } else {
            $ret = $cek;
        }

        echo json_encode($ret);
    }

    public function sendMessage()
    {
        $phone      = '08123456789';
        $text        = $_POST['sar_nama'] . "\r\n";
        $text        .= $_POST['sar_email'] . "\r\n";
        $text        .= $_POST['sar_no_hp'] . "\r\n\r\n";
        $text        .= $_POST['sar_kritik'] . "\r\n\r\n";

        $feed = [
            1 => 'Tidak Puas',
            2 => 'Cukup Puas',
            3 => 'Puas',
            4 => 'Sangat Puas',
        ];

        $text        .= "Saya merasa " . $feed[$_POST['sar_rating']] . " dengan adanya KIDIMAN PERANG (" . site_url() . ") ini";
        $msg         = "https://api.whatsapp.com/send?phone=" . $phone . "&text=" . urlencode($text);

        return $msg;
    }

    private function validasiIKM()
    {
        $val = array();
        $val['status']  = 1;
        $val['form']   = [];
        if (!isset($_POST['sar_nama']) || $_POST['sar_nama'] == '') {
            $val['form'][] = 'sar_nama';
            $val['status']  = 0;
            $val['message'][] = 'Nama Tidak Valid';
        }
        if (!isset($_POST['sar_email']) || $_POST['sar_email'] == '' || !filter_var($_POST['sar_email'], FILTER_VALIDATE_EMAIL)) {
            $val['form'][] = 'sar_email';
            $val['status']  = 0;
            $val['message'][] = 'E-mail Tidak Valid';
        }
        if (!isset($_POST['sar_no_hp']) || $_POST['sar_no_hp'] == '' || !is_numeric($_POST['sar_no_hp']) || strlen($_POST['sar_no_hp']) > 13) {
            $val['form'][] = 'sar_no_hp';
            $val['status']  = 0;
            $val['message'][] = 'No. HP Tidak Valid';
        }
        if (!isset($_POST['sar_kritik']) || $_POST['sar_kritik'] == '') {
            $val['form'][] = 'sar_kritik';
            $val['status']  = 0;
            $val['message'][] = 'IKM Harus Diisi';
        }
        if (!isset($_POST['sar_rating']) || $_POST['sar_rating'] == '' || $_POST['sar_rating'] <= 0 || $_POST['sar_rating'] > 4) {
            $val['form'][] = 'sar_rating';
            $val['status']  = 0;
            $val['message'][] = 'Rating Harus Diisi';
        }
        // if (isset($_POST['g-recaptcha-response'])) {
        //     $verify     = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $this->config->item('recaptcha_secret_key') . '&response=' . $_POST['g-recaptcha-response']);
        //     $response = json_decode($verify);
        //     if (!$response->success) {
        //         $val['form'][] = 'g-recaptcha';
        //         $val['status']  = 0;
        //         $val['message'][] = 'Captcha Tidak Valid';
        //     }
        // } else {
        //     $val['form'][] = 'g-recaptcha';
        //     $val['status']  = 0;
        //     $val['message'][] = 'Captcha Tidak Valid';
        // }

        return $val;
    }
}
