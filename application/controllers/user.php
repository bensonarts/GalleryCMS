<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class User extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    if ($this->is_logged_in() === FALSE && $this->is_admin() === FALSE)
    {
      redirect('album');
    }
    else
    {
      $this->load->model('user_model');
    }
  }
  
  public function index()
  {
    $data['users'] = $this->user_model->fetch_all();
    $flash_login_success = $this->session->flashdata('flash_message'); 
    if (isset($flash_login_success) && strlen($flash_login_success) > 0)
    {
      $data['flash'] = $flash_login_success;
    }
    $data['user_id'] = $this->get_user_id();
    $this->load->view('user/index', $data);
  }

  public function create()
  {
    $this->load->helper('form');
    $this->load->view('user/create');
  }
  
  public function add()
  {
    // Validate form.
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<div class="alert alert-error"><strong>Error: </strong>', '</div>');
    $this->form_validation->set_rules('email_address', 'Email Address', 'trim|required|valid_email|is_unique[user.email_address]|xss_clean');
    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|matches[password_conf]|sha1');
    if ($this->form_validation->run() == FALSE)
    {
      // Form didn't validate
      $this->load->view('user/create');
    }
    else
    {
      // Success, create user & redirect
      $now = date('Y-m-d H:i:s');
      $user_data = array(
                   'email_address' => $this->input->post('email_address'), 
                   'password' => $this->input->post('password'),
                   'is_active' => $this->input->post('is_active'),
                   'is_admin' => $this->input->post('is_admin'),
                   'created_at' => $now,
                   'updated_at' => $now);
      $this->user_model->create($user_data);
      $this->session->set_flashdata('flash_message', "User successfully created.");
      redirect('user/index');
    }
  }

  public function edit($user_id)
  {
    $this->load->helper('form');
    $data['user'] = $this->user_model->find_by_id($user_id);
    $this->load->view('user/edit', $data);
  }
  
  public function update($user_id)
  {
    // Validate form.
    $this->load->helper('form');
    $user = $this->user_model->find_by_id($user_id);
    $data['user'] = $user;
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<div class="alert alert-error"><strong>Error: </strong>', '</div>');
    $email_address = $this->input->post('email_address');
    // Can set a new email address or keep the same.
    if ($email_address !== $user->email_address)
    {
      $this->form_validation->set_rules('email_address', 'Email Address', 'trim|required|valid_email|is_unique[user.email_address]|xss_clean');
    }
    $this->form_validation->set_rules('password', 'Password', 'trim|min_length[5]|matches[password_conf]|sha1');
    if ($this->form_validation->run() == FALSE)
    {
      // Form didn't validate
      $this->load->view('user/edit', $data);
    }
    else
    {
      // Success, create user & redirect
      $now = date('Y-m-d H:i:s');
      $user_data = array(
                   'email_address' => $this->input->post('email_address'), 
                   'is_active' => $this->input->post('is_active'),
                   'is_admin' => $this->input->post('is_admin'),
                   'created_at' => $now,
                   'updated_at' => $now);
      // Password can be optionally changed.
      $password = $this->input->post('password');
      if (isset($password) && strlen($password) > 0)
      {
        $user_data['password'] = $password;
      }
      $this->user_model->update($user_data, $user_id);
      $this->session->set_flashdata('flash_message', "User successfully updated.");
      redirect("user");
    }
  }

  public function deactivate($user_id)
  {
    // TODO Implement functionality.
    // TODO Unpublish user's images and albums
    $this->user_model->update(array('is_active' => 0), $user_id);
    $this->session->set_flashdata('flash_message', "User has been deactivated.");
    redirect("user");
  }
  
  public function remove($user_id)
  {
    // TODO Implement functionality.
    // TODO Remove user's images and albums
    $this->user_model->delete($user_id);
    $this->session->set_flashdata('flash_message', "User has been deleted.");
    redirect("user");
  }
  
}