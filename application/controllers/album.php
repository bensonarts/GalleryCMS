<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Album extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    if ($this->is_logged_in() === FALSE)
    {
      redirect('auth');
    }
    else
    {
      $this->load->model('album_model', 'Album_Model');
    }
  }
  
  public function index()
  {
    if ($this->is_admin() === TRUE)
    {
      $data['albums'] = $this->Album_Model->fetch_all();
    }
    else
    {
      $data['albums'] = $this->Album_Model->fetch_by_user_id($this->get_user_id());
    }
    $flash_login_success = $this->session->flashdata('flash_message'); 
    if (isset($flash_login_success) && strlen($flash_login_success) > 0)
    {
      $data['flash'] = $flash_login_success;
    }
    $this->load->view('album/index', $data);
  }
  
  public function create()
  {
    $this->load->helper('form');
    $this->load->view('album/create');
  }
  
  public function add()
  {
    // Validate form.
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<div class="alert alert-error"><strong>Error: </strong>', '</div>');
    $user_data = $this->session->all_userdata();
    $this->form_validation->set_rules('album_name', 'Album Name', 'trim|required|max_length[45]|xss_clean');
    if ($this->form_validation->run() == FALSE)
    {
      // Form didn't validate
      $this->load->view('album/create');
    }
    else
    {
      // Success, create album & redirect
      $now = date('Y-m-d H:i:s');
      $data = array(
                   'name' => $this->input->post('album_name'), 
                   'created_by' => $user_data['user_id'],
                   'updated_by' => $user_data['user_id']);
      $this->Album_Model->create($data);
      $this->session->set_flashdata('flash_message', "Successfully created album.");
      redirect('album');
    }
  }
  
  public function edit($album_id)
  {
    $this->load->helper('form');
    $data['album'] = $this->Album_Model->find_by_id($album_id);
    $this->load->view('album/edit', $data);
  }
  
  public function update($album_id)
  {
    // Validate form.
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<div class="alert alert-error"><strong>Error: </strong>', '</div>');
    $data['album'] = $this->Album_Model->find_by_id($album_id);
    $user_data = $this->session->all_userdata();
    $this->form_validation->set_rules('album_name', 'Album Name', 'trim|required|max_length[45]|xss_clean');
    if ($this->form_validation->run() == FALSE)
    {
      // Form didn't validate
      $this->load->view('album/create', $data);
    }
    else
    {
      // Success, create album & redirect
      $now = date('Y-m-d H:i:s');
      $data = array(
                   'name' => $this->input->post('album_name'), 
                   'created_by' => $user_data['user_id'],
                   'updated_by' => $user_data['user_id']);
      $this->Album_Model->update($data, $album_id);
      $this->session->set_flashdata('flash_message', "Successfully updated album.");
      redirect('album');
    }
  }
  
  public function remove($album_id)
  {
    // TODO Implement functionality
    $this->Album_Model->delete($album_id);
    $this->session->set_flashdata('flash_message', "Successfully deleted album.");
    redirect('album');
  }
}
