<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    var $column_order   = array(null, 'usr_nama', 'usr_level', 'prt_nama', 'usr_username');
    var $column_search   = array('usr_nama', 'usr_level', 'prt_nama', 'usr_username');
    var $order = array('usr_id' => 'asc', 'usr_nama' => 'asc', 'usr_level' => 'asc', 'prt_nama' => 'asc', 'usr_username' => 'asc');

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
            $row['usr_username']    = $usr->usr_username;
            $row['usr_level']       = $usr->usr_level;
            $row['prt_nama']        = $usr->prt_nama;
            $row['usr_nama']        = $usr->usr_nama;
            $row['opsi']            = '<div class="btn-group" role="group">
                                            <button class="btn btn-icon btn-warning update-data" data-id="' . (string)$usr->up_id . '">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-icon btn-danger delete-data" data-id="' . (string)$usr->up_id . '" data-name="' . (string)$usr->usr_nama . '" data-idusr="' . (string)$usr->usr_id . '">
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
        $this->db->select('*');
        $this->db->from('user_perangkat');
        $this->db->join('user', 'user.usr_id = up_user', 'left');
        $this->db->join('perangkat', 'perangkat.prt_id = up_perangkat', 'left');
        $this->db->where(['prt_status' => 1]);
        $this->db->where(['usr_status' => 1]);
        $this->db->where(['up_status' => 1]);
        if ($where) {
            $this->db->where($where);
        }
        if ($this->input->post("fil_nama")) {
            $this->db->where(['prt_nama' => $this->input->post("fil_nama")]);
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
            $this->db->where(['up_id' => $this->input->post('id')]);
        }
        $this->db->join('perangkat', 'perangkat.prt_id = user_perangkat.up_perangkat', 'left');
        $this->db->join('user', 'user.usr_id = user_perangkat.up_user', 'left');

        $dt = $this->db->get_where('user_perangkat', ['up_status' => '1']);

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
            if ($this->input->post('up_id')) {
                $wr['up_id'] = $this->input->post('up_id');

                $data = [
                    'usr_perangkat' => $this->input->post('usr_perangkat'),
                    'usr_nama'      => $this->input->post('usr_nama'),
                    'usr_level'     => strtolower($this->input->post('usr_level')),
                    'usr_username'  => strtolower($this->input->post('usr_username'))
                ];
                // $pass = ['up_id'];
                // foreach ($_POST as $k => $v) {
                //     if (!in_array($k, $pass)) {
                //         $data[$k] = $v;
                //     }
                // }
                $ex = $this->db
                    ->where(['up_id !=' => $this->input->post('up_id')])
                    ->join('user', 'user.usr_id = user_perangkat.up_user', 'left')
                    ->get_where('user_perangkat', $data);
                if ($ex->num_rows() > 0) {
                    $ret['ok'] = 500;
                    $ret['form'] = 'Data Sudah Ada Sebelumnya';
                } else {
                    $update_up = $this->db->update('user_perangkat', [
                        'up_perangkat' => $this->input->post('usr_perangkat')
                    ], $wr);

                    $wr2['usr_id'] = $this->db->get_where('user_perangkat', $wr)->row()->up_user;
                    $update_usr = $this->db->update('user', [
                        'usr_nama'      => $this->input->post('usr_nama'),
                        'usr_level'     => strtolower($this->input->post('usr_level')),
                        'usr_username'  => strtolower($this->input->post('usr_username')),
                        'usr_password'  => $this->input->post('usr_password')
                    ], $wr2);
                    if ($update_up && $update_usr) {
                        $ret['ok'] = 200;
                        $ret['form'] = 'Sukses Update Data';
                    } else {
                        $ret['ok'] = 500;
                        $ret['form'] = 'Gagal Update Data';
                    }
                }
            } else {
                $wr1['usr_nama']     = $this->input->post('usr_nama');
                $user = $this->db->get_where('user', $wr1);
                if ($user->num_rows() > 0) {
                    $wr['up_user']      = $user->row()->usr_id;
                    $wr['up_perangkat'] = $this->input->post('usr_perangkat');
                    $wr['up_status']    = 1;
                    $row = $this->db->get_where('user_perangkat', $wr);

                    if ($row->num_rows() < 1) {
                        $data = [];
                        $data = $wr;
                        if ($this->db->insert('user_perangkat', $data)) {
                            $ret['ok']    = 200;
                            $ret['form']  = 'Sukses Insert Data';
                        } else {
                            $ret['ok']    = 500;
                            $ret['form']  = 'Gagal Insert Data';
                        }
                    }
                } else {
                    $data_usr = [
                        'usr_perangkat' => strtoupper($this->input->post('usr_perangkat')),
                        'usr_nama'      => $this->input->post('usr_nama'),
                        'usr_level'     => strtolower($this->input->post('usr_level')),
                        'usr_username'  => strtolower($this->input->post('usr_username')),
                        'usr_password'  => $this->input->post('usr_password')
                    ];
                    $user = $this->db->insert('user', $data_usr);
                    if ($user) {
                        $data['up_user']         = $this->db->insert_id();
                        $data['up_perangkat']    = $this->input->post('usr_perangkat');
                        $data['up_status']       = 1;
                        if ($this->db->insert('user_perangkat', $data)) {
                            $ret['ok']    = 200;
                            $ret['form']  = 'Sukses Insert Data';
                        } else {
                            $ret['ok']    = 500;
                            $ret['form']  = 'Gagal Insert Data';
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
            $ret['form']['usr_perangkat']   = form_error('usr_perangkat');
            $ret['ok']                      = 400;
        }
        echo json_encode($ret);
    }

    public function delete()
    {
        if ($this->input->post('id')) {
            $wr['up_id']    = $this->input->post('id');
            $wr2['usr_id']  = $this->input->post('idusr');
            if ($this->db->update('user_perangkat', ['up_status' => 0], $wr)) {
                $this->db->update('user', ['usr_status' => 0], $wr2);
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
                'field' => 'usr_perangkat',
                'label' => 'Perangkat',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi',
                ],
            ],
            [
                'field' => 'usr_password',
                'label' => 'Password',
                'rules' => 'required|min_length[8]|callback_is_password_strong',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'min_length' => '{field} harus minimal 8 karakter',
                    'is_password_strong' => '{field} harus berisi angka, huruf kapital, huruf kecil dan karakter khusus',
                ],
            ],
            [
                'field' => 'usr_password2',
                'label' => 'Kofirmasi Password',
                'rules' => 'required|matches[usr_password]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'matches' => '{field} tidak sesuai dengan password awal',
                ],
            ],
        ];

        $this->form_validation->set_rules($config);
        return $this->form_validation->run();
    }

    public function is_password_strong($usr_password)
    {
        if ($usr_password) {
            if (preg_match('#[0-9]#', $usr_password) && preg_match('#[a-zA-Z]#', $usr_password)) {
                return TRUE;
            }
            return FALSE;
        }
    }

    function getPerangkat()
    {
        $ids = $this->db->select('up_perangkat')->from('user_perangkat')->where('up_status', 1)->get();
        if ($ids->num_rows() > 0) {
            $this->db->where_not_in(
                'prt_id',
                array_column(
                    $ids->result_array(),
                    'up_perangkat'
                )
            );
        }

        if ($this->input->post('id')) {
            if ($this->input->post('is_edit')) {
                $this->db->or_where(
                    'prt_id',
                    $this->input->post('id')
                );
            }
        }
        $data = $this->db->get_where('perangkat', ['prt_status' => 1]);
        echo json_encode($data->result());
    }

    function login()
    {
        $data = array(
            'title' => "Login"
        );
        $this->load->view('dist/auth-login-2', $data);
    }

    function auth_login()
    {
        // Validate the user input
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, load the login form again
            $this->load->view('dist/auth-login-2');
        } else {
            // If validation succeeds, check the login credentials
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $this->db->where('username', $username);
            $this->db->where('password', $password);
            $query = $this->db->get('users'); // Replace 'users' with your table name

            if ($query->num_rows() == 1) {
                // Set session
                $this->session->set_userdata('logged_in', true);
                redirect('Home'); // Change 'dashboard' to your desired redirect page
            } else {
                // If login fails, reload the login form with an error message
                $data['error'] = 'Invalid username or password!';
                $this->load->view('dist/auth-login-2', $data);
            }
        }
    }
}
