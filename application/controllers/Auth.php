<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('App_model', 'app');
    }
    public function index()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email', [
            'required' => 'Email Harus di Isi',
            'valid_email' => 'Email Anda tidak Valid'
        ]);
        $this->form_validation->set_rules('pass', 'Pass', 'required|trim', [
            'required' => 'Password Harus di Isi'
        ]);

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Halaman Login';
            $this->load->view('template/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('template/auth_footer');
        } else {
            $this->_masuk();
        }
    }
    private function _masuk()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('pass');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        if ($user) {
            //user ada
            if ($user['is_active'] == 1) {
                if (password_verify($password, $user['password'])) {

                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id']
                    ];
                    $this->session->set_userdata($data);
                    redirect('user');
                } else {
                    //$this->session->set_flashdata('eror', 'PASSWORD SALAH');
                    $this->session->set_flashdata('message', '<div class="alert 
                    alert-warning" role="alert"> PASSWORD SALAH </div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert 
                alert-warning" role="alert"> MAAF USER BELUM DI AKTIFKAN </div>');
                //$this->session->set_flashdata('eror', 'LOGIN GAGAL');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert 
            alert-warning" role="alert">USER TIDAK ADA</div>');
            //$this->session->set_flashdata('eror', 'ADA YANG SALAH');
            redirect('auth');
        }
    }
    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');
        $this->session->set_flashdata('message', '<div class="alert 
        alert-success" role="alert"> KAMU TELAH LOGOUT </div>');
        redirect('auth');
    }
    public function register()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim', [
            'required' => 'Nama Harus di Isi'
        ]);
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'required' => 'Email Harus di Isi',
            'is_unique' => 'Email Sudah Digunakan'
        ]);
        $this->form_validation->set_rules('password1', 'Password1', 'required|trim|min_length[3]|matches[password2]', [
            'required' => 'Password Harus di Isi',
            'matches' => 'Password Tidak Sama',
            'min_length' => 'Password Terlalu Pendek'
        ]);
        $this->form_validation->set_rules('password2', 'Password1', 'required|trim|matches[password1]');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Halaman Pendaftaran';
            $this->load->view('template/auth_header', $data);
            $this->load->view('auth/register');
            $this->load->view('template/auth_footer');
        } else {
            $data = [
                'nama' => htmlspecialchars($this->input->post('nama', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => 1,
                'is_active' => 1,
                'date_create' => time()
            ];
            $this->db->insert('user', $data);
            $this->session->set_flashdata('message', '<div class="alert 
                alert-success" role="alert"> AKUN BERHASIL DI BUAT </div>');
            redirect('auth');
        }
    }
}
