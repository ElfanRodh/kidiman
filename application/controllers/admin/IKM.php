<?php
defined('BASEPATH') or exit('No direct script access allowed');

class IKM extends CI_Controller
{
    var $column_order   = array(null, 'sar_nama', 'sar_email', 'sar_no_hp', 'sar_kritik', 'sar_rating');
    var $column_search   = array('sar_nama', 'sar_email', 'sar_no_hp', 'sar_kritik', 'sar_rating');
    var $order = array('sar_nama' => 'asc', 'sar_email' => 'asc', 'sar_no_hp' => 'asc', 'sar_kritik' => 'asc', 'sar_rating' => 'asc');


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
            'title' => "IKM (Indeks Kepuasan Masyarakat)"
        );
        $this->load->view('admin/ikm/index', $data);
    }

    public function viewData()
    {
        $list = $this->getIKMList();
        $data = array();
        $no   = $this->input->post('start');
        $v    = 0;
        foreach ($list as $ikm) {
            $no++;
            $row                    = array();
            $row['no']              = $no;
            $row['nama']            = $ikm->sar_nama;
            $row['email']           = $ikm->sar_email;
            $row['no_hp']           = $ikm->sar_no_hp;
            $row['kritik']          = $ikm->sar_kritik;
            if ($ikm->sar_rating == 1) {
                $row['rating'] = '<div class="text-center"><img src="' . base_url() . '/assets/img/ikm/sar4.png"  class="sar" style="width: 48px; height: 48px;"><p class="text-center">Sangat Puas</p></div>';
            } else if ($ikm->sar_rating == 2) {
                $row['rating'] = '<div class="text-center"><img src="' . base_url() . '/assets/img/ikm/sar3.png"  class="sar" style="width: 48px; height: 48px;"><p class="text-center">Puas</p></div>';
            } else if ($ikm->sar_rating == 3) {
                $row['rating'] = '<div class="text-center"><img src="' . base_url() . '/assets/img/ikm/sar2.png"  class="sar" style="width: 48px; height: 48px;"><p class="text-center">Cukup Puas</p></div>';
            } else if ($ikm->sar_rating == 4) {
                $row['rating'] = '<div class="text-center"><img src="' . base_url() . '/assets/img/ikm/sar1.png"  class="sar" style="width: 48px; height: 48px;"><p class="text-center">Tidak Puas</p></div>';
            }
            $row['opsi']            = '<div class="btn-group" role="group">
                                            <button class="btn btn-icon btn-danger delete-data" data-toggle="tooltip" data-placement="top" title="Hapus Data" data-original-title="Hapus Data" data-id="' . (string)$ikm->sar_id . '" data-name="' . (string)$ikm->sar_nama . '">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>';
            $data[]                 = $row;
        }

        $output = array(
            "draw"            => $this->input->post('draw'),
            "recordsTotal"    => $this->countAll(),
            "recordsFiltered" => $this->countFiltered(),
            "data"            => $data,
        );
        echo json_encode($output);
    }

    public function getIKMList($where = null)
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
        $this->db->from('ref_saran');
        $this->db->where([]);
        if ($where) {
            $this->db->where($where);
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
            $this->db->where(['ref_saran.sar_id' => $this->input->post('id')]);
        }

        $dt = $this->db->get_where('ref_saran', []);

        $data['ok'] = 500;
        $data['data'] = 'Data Tidak Ada';
        if ($dt->num_rows() > 0) {
            $data['ok']   = 200;
            $data['data']  = $dt->row();
        }

        echo json_encode($data);
    }

    public function addOrEdit()
    {
        $cek = $this->validateData();
        if ($cek) {
            if ($this->input->post('id')) {
                $wr['ref_saran.sar_id'] = $this->input->post('id');

                $data_user = [
                    'username' => $this->input->post('usr_username'),
                    // 'first_name'      => $this->input->post('usr_nama'),
                    'jabatan_id'  => strtolower($this->input->post('usr_jabatan')),
                    // 'password'  => password_hash($this->input->post('usr_password'), PASSWORD_BCRYPT)
                ];

                $data_group = [
                    'sar_id' => $this->input->post('id'),
                    'group_id'      => $this->input->post('usr_level')
                ];

                $ex_user = $this->db
                    ->where(['ref_saran.sar_id !=' => $this->input->post('id')])
                    ->join('ref_saran_groups', 'ref_saran_groups.sar_id = ref_saran.sar_id', 'left')
                    ->get_where('ref_saran', $data_user);

                $ex_group = $this->db
                    ->get_where('ref_saran_groups', ['sar_id' => $this->input->post('id')]);

                if ($ex_user->num_rows() > 0) {
                    $ret['ok'] = 500;
                    $ret['form'] = 'Data Sudah Ada Sebelumnya';
                } else {

                    $this->db->trans_begin(); // Memulai transaksi

                    $data_user['first_name'] = $this->input->post('usr_nama');
                    if ($this->input->post('usr_password')) {
                        $data_user['password'] = password_hash($this->input->post('usr_password'), PASSWORD_BCRYPT);
                    }

                    $this->db->update('ref_saran', $data_user, $wr);
                    if ($ex_group->num_rows() > 0) {
                        $this->db->delete('ref_saran_groups', ['id' => $ex_group->row()->id]);
                    }
                    $this->db->insert('ref_saran_groups', $data_group);

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
                $wr1['username']   = $this->input->post('usr_username');
                $wr1['active']     = 1;
                $user = $this->db->get_where('ref_saran', $wr1);
                if ($user->num_rows() > 0) {
                    $ret['ok'] = 500;
                    $ret['form'] = 'Data Sudah Ada Sebelumnya';
                } else {
                    $data_user = [
                        'username'     => $this->input->post('usr_username'),
                        'first_name'   => $this->input->post('usr_nama'),
                        'jabatan_id'  => strtolower($this->input->post('usr_jabatan')),
                        'password'  => password_hash($this->input->post('usr_password'), PASSWORD_BCRYPT)
                    ];

                    $user = $this->db->insert('ref_saran', $data_user);

                    if ($user) {
                        $sar_id = $this->db->insert_id();
                        $data_group = [
                            'sar_id'       => $sar_id,
                            'group_id'      => $this->input->post('usr_level')
                        ];

                        $this->db->trans_begin(); // Memulai transaksi

                        $user = $this->db->insert('ref_saran_groups', $data_group);

                        $this->db->trans_complete(); // End transaksi
                        // Cek jika transaksi berhasil atau gagal
                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback(); // Transaksi gagal, rollback
                            $ret['ok'] = 500;
                            $ret['form'] = 'Gagal Tambah Data';
                        } else {
                            $this->db->trans_commit();
                            $ret['ok'] = 200;
                            $ret['form'] = 'Sukses Tambah Data';
                        }
                    }
                }
            }
        } else {
            $ret['form']['usr_nama']        = form_error('usr_nama');
            $ret['form']['usr_username']    = form_error('usr_username');
            $ret['form']['usr_level']       = form_error('usr_level');
            $ret['form']['usr_password']    = form_error('usr_password');
            $ret['form']['usr_password2']   = form_error('usr_password2');
            $ret['form']['usr_jabatan']     = form_error('usr_jabatan');
            $ret['ok']                      = 400;
        }
        echo json_encode($ret);
    }

    public function delete()
    {
        if ($this->input->post('id')) {
            $wr['id']    = $this->input->post('id');
            $ex_group = $this->db
                ->get_where('ref_saran_groups', ['sar_id' => $this->input->post('id')]);
            if ($this->db->update('ref_saran', ['active' => 0], $wr)) {
                $this->db->delete('ref_saran_groups', ['id' => $ex_group->row()->id]);
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
                'field' => 'usr_username',
                'label' => 'IKMname',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi',
                ],
            ],
            [
                'field' => 'usr_nama',
                'label' => 'Nama IKM',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi',
                ],
            ],
            [
                'field' => 'usr_level',
                'label' => 'Level',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi',
                ],
            ],
            [
                'field' => 'usr_jabatan',
                'label' => 'Jabatan',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi',
                ],
            ],
        ];

        if ($this->input->post('id')) {
            if ($this->input->post('usr_password')) {
                $config[] = [
                    'field' => 'usr_password',
                    'label' => 'Password',
                    'rules' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\d\s])[^\s]{8,}$/]',
                    'errors' => [
                        'required' => '{field} harus diisi',
                        'min_length' => '{field} harus minimal 8 karakter',
                        'regex_match' => '{field} harus berisi angka, huruf kapital, huruf kecil dan karakter khusus',
                    ],
                ];
                $config[] = [
                    'field' => 'usr_password2',
                    'label' => 'Kofirmasi Password',
                    'rules' => 'required|matches[usr_password]',
                    'errors' => [
                        'required' => '{field} harus diisi',
                        'matches' => '{field} tidak sesuai dengan password awal',
                    ],
                ];
            }
        } else {
            $config[] = [
                'field' => 'usr_password',
                'label' => 'Password',
                'rules' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\d\s])[^\s]{8,}$/]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'min_length' => '{field} harus minimal 8 karakter',
                    'regex_match' => '{field} harus berisi angka, huruf kapital, huruf kecil dan karakter khusus',
                ],
            ];
            $config[] = [
                'field' => 'usr_password2',
                'label' => 'Kofirmasi Password',
                'rules' => 'required|matches[usr_password]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'matches' => '{field} tidak sesuai dengan password awal',
                ],
            ];
        }

        $this->form_validation->set_rules($config);
        return $this->form_validation->run();
    }

    function getChart()
    {
        $wr = []; // Filter jika dibutuhkan
        $arrIKM = [
            'label' => [],
            'jumlah' => []
        ];

        // Mapping sar_rating ke label
        $ratingLabels = [
            1 => 'Sangat Puas',
            2 => 'Puas',
            3 => 'Cukup Puas',
            4 => 'Tidak Puas'
        ];

        // Query data dari database
        $ikm = $this->db
            ->select('COUNT(sar_id) AS jumlah, sar_rating as label')
            ->order_by('sar_rating')
            ->group_by('sar_rating')
            ->get_where('ref_saran', $wr)
            ->result();

        // Format data untuk Chart.js
        foreach ($ikm as $val) {
            $arrIKM['label'][] = $ratingLabels[$val->label]; // Ubah angka menjadi label
            $arrIKM['jumlah'][] = (int) $val->jumlah; // Jumlah untuk chart
        }

        // Return data dalam format JSON
        echo json_encode($arrIKM);
    }
}
