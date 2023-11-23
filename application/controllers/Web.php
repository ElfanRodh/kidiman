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
        $this->load->view('admin/landing/index', $data);
    }
}
