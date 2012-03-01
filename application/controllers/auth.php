<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Auth extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function index()
  {
    $this->load->helper('form');
    $this->load->view('auth/index');
    if ($this->_is_logged_in() == TRUE)
    {
      redirect('user');
    }
  }

  public function authenticate()
  {
    // Authenticate user.
    $this->load->model('user_model', 'User_Model');
    $this->load->helper('form');
    $userData = array('email_address' => $this->input->post('email_address'), 'password' => $this->input->post('password'));
    $user_id = $this->User_Model->authenticate($userData);
    if ($user_id > 0)
    {
      // Create session var, session cookie
      $user = $this->User_Model->find_by_id($user_id);
      $this->load->library('session');
      $session_data = array(
        'email_address' => $user->email_address,
        'user_id' => $user_id,
        'logged_in' => TRUE,
        'is_admin' => $user->is_admin,
        'ip' => $this->input->ip_address()
      );
      $this->session->set_userdata($session_data);

      $this->load->helper('cookie');
      /** @todo Create cookie vars */
      $cookie_data = array('name' => 'gallerycms_login', 'value' => 1, 'expire' => -1, 'domain' => 'dev-gallerycms.com', 'path' => '/', 'prefix' => 'gcms_');
      $this->input->set_cookie($cookie_data);
      redirect('album');
    }
    else
    {
      $data['login_error'] = 'Incorrect login';
      $this->load->view('auth/index', $data);
    }
  }

  public function logout()
  {
    $this->load->library('session');
    $this->session->sess_destroy();
    //delete_cookie('gallerycms_login');
    redirect('auth');
  }

  private function _is_logged_in()
  {
    $this->load->library('session');
    $session_data = $this->session->all_userdata();
    return (isset($session_data['user_id']) && $session_data['user_id'] > 0 && $session_data['logged_in'] == TRUE);
  }

}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */