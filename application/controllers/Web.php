<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Web extends CI_Controller
{
    public function index()
    {
        redirect('admin/home');
    }
}
