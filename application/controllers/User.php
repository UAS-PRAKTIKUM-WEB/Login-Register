<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function index()
    {
        $data['user'] = $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();
        //echo "SELAMAT DATANG " . $data['user']['nama'];


        $atas['title'] = 'Dashboard';
        $judul['nama_judul'] = $data['user']['nama'];
        $this->load->view('template/user_header', $atas);
        $this->load->view('template/view_user', $judul);
        $this->load->view('template/user_footer');
    }
}
