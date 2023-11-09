<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    var $column_order   = array(null, 'username', 'jabatan.jbt_nama', 'groups.name', 'first_name');
    var $column_search   = array('username', 'jabatan.jbt_nama', 'first_name');
    var $order = array('users.id' => 'asc', 'username' => 'asc', 'first_name' => 'asc', 'jabatan.jbt_nama' => 'asc', 'groups.name' => 'asc');


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
            'title' => "User Perangkat Desa"
        );
        $this->load->view('admin/user/index', $data);
    }

    public function viewData()
    {
        $list = $this->getUserList();
        $data = array();
        $no   = $this->input->post('start');
        $v    = 0;
        foreach ($list as $usr) {
            $no++;
            $row                    = array();
            $row['no']              = $no;
            $row['username']        = $usr->username;
            $row['first_name']      = $usr->first_name;
            $row['jbt_nama']        = $usr->jbt_nama;
            $row['group_name']      = $usr->group_name;
            $row['opsi']            = '<div class="btn-group" role="group">
                                            <button class="btn btn-icon btn-warning update-data" data-id="' . (string)$usr->user_id . '">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-icon btn-danger delete-data" data-id="' . (string)$usr->user_id . '" data-name="' . (string)$usr->username . '">
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

    public function getUserList($where = null)
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
        $this->db->select('users.id AS user_id, username, first_name, groups.name AS group_name, jbt_nama');
        $this->db->from('users');
        $this->db->join('users_groups', 'users_groups.user_id = users.id', 'left');
        $this->db->join('groups', 'users_groups.group_id = groups.id', 'left');
        $this->db->join('jabatan', 'jabatan.jbt_id = users.jabatan_id', 'left');
        $this->db->where(['active' => 1]);
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
            $this->db->where(['users.id' => $this->input->post('id')]);
        }
        $this->db->join('users_groups', 'users_groups.user_id = users.id', 'left');
        $this->db->join('groups', 'users_groups.group_id = groups.id', 'left');

        $this->db->select('users.id AS user_id, username, first_name, groups.id AS group_id, jabatan_id');

        $dt = $this->db->get_where('users', ['active' => '1']);

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
                $wr['users.id'] = $this->input->post('id');

                $data_user = [
                    'username' => $this->input->post('usr_username'),
                    // 'first_name'      => $this->input->post('usr_nama'),
                    'jabatan_id'  => strtolower($this->input->post('usr_jabatan')),
                    // 'password'  => password_hash($this->input->post('usr_password'), PASSWORD_BCRYPT)
                ];

                $data_group = [
                    'user_id' => $this->input->post('id'),
                    'group_id'      => $this->input->post('usr_level')
                ];

                $ex_user = $this->db
                    ->where(['users.id !=' => $this->input->post('id')])
                    ->join('users_groups', 'users_groups.user_id = users.id', 'left')
                    ->get_where('users', $data_user);

                $ex_group = $this->db
                    ->get_where('users_groups', ['user_id' => $this->input->post('id')]);

                if ($ex_user->num_rows() > 0) {
                    $ret['ok'] = 500;
                    $ret['form'] = 'Data Sudah Ada Sebelumnya';
                } else {

                    $this->db->trans_begin(); // Memulai transaksi

                    $data_user['first_name'] = $this->input->post('usr_nama');
                    $data_user['password'] = password_hash($this->input->post('usr_password'), PASSWORD_BCRYPT);

                    $this->db->update('users', $data_user, $wr);
                    if ($ex_group->num_rows() > 0) {
                        $this->db->delete('users_groups', ['id' => $ex_group->row()->id]);
                    }
                    $this->db->insert('users_groups', $data_group);

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
                $user = $this->db->get_where('users', $wr1);
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

                    $user = $this->db->insert('users', $data_user);

                    if ($user) {
                        $user_id = $this->db->insert_id();
                        $data_group = [
                            'user_id'       => $user_id,
                            'group_id'      => $this->input->post('usr_level')
                        ];

                        $this->db->trans_begin(); // Memulai transaksi

                        $user = $this->db->insert('users_groups', $data_group);

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
                ->get_where('users_groups', ['user_id' => $this->input->post('id')]);
            if ($this->db->update('users', ['active' => 0], $wr)) {
                $this->db->delete('users_groups', ['id' => $ex_group->row()->id]);
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
                'label' => 'Username',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi',
                ],
            ],
            [
                'field' => 'usr_nama',
                'label' => 'Nama User',
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
                    'rules' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&*!])[A-Za-z\d@#$%^&*!]{8,}$/]',
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
                'rules' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&*!])[A-Za-z\d@#$%^&*!]{8,}$/]',
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

    public function is_password_strong($usr_password)
    {
        // $this->form_validation->set_rules('password', 'Password', 'required|', array('regex_match' => 'Password harus mengandung setidaknya satu huruf kecil, satu huruf kapital, satu angka, dan satu karakter khusus.'));

        if ($usr_password) {
            if (preg_match('#[0-9]#', $usr_password) && preg_match('#[a-zA-Z]#', $usr_password)) {
                return TRUE;
            }
            return FALSE;
        }
    }

    function getJabatan()
    {
        $data = $this->db->get_where('jabatan', ['jbt_status' => 1]);
        echo json_encode($data->result());
    }

    function login()
    {
        $data = array(
            'title' => "Login"
        );
        $this->load->view('dist/auth-login-2', $data);
    }
}
