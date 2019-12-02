<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class App_Model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    //query login
    public function getLoginData($data)
    {
        $login['email'] = $data['username'];
        $login['password'] = $data['password'];
        $cek = $this->db->get_where('user', $login);
        if ($cek->num_rows() > 0) {
            foreach ($cek->result() as $qad) {
                $sess_data['logged_in'] = 'yesGetMeLoginBaby';

                //$sess_data['user_id'] = $qad->user_id;
                $sess_data['username'] = $qad->username;
                //$sess_data['ip_address'] = $this->input->ip_address();
                $this->session->set_userdata($sess_data);
            }
            $this->load->view('auth/register');
            redirect('Auth/register');
        } else {
            $this->session->set_flashdata('result_login', "Maaf, kombinasi username dan password yang anda masukkan tidak valid");
            header('location:' . base_url() . '');
        }
    }
}
