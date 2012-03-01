<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Album extends MY_Controller
{
  public function index()
  {
    $this->load->model('album_model', 'Album_Model');
    if ($this->is_admin() === TRUE)
    {
      $data['albums'] = $this->Album_Model->fetch_all();
    }
    else
    {
      $data['albums'] = $this->Album_Model->fetch_by_user_id($this->get_user_id());
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
    $this->load->model('album_model', 'Album_Model');
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
      $user_data = $session_data = $this->session->all_userdata();
      $data = array(
                   'name' => $this->input->post('album_name'), 
                   'created_by' => $user_data['user_id'],
                   'updated_by' => $user_data['user_id']);
      $this->Album_Model->create($data);
      redirect('album');
    }
  }
  
  public function update()
  {
    
  }
  
  public function remove()
  {
    
  }
}
