<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

/**
 * 
 */
class Feed extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->is_logged_in() == FALSE)
    {
      //redirect('auth');
    }
    else
    {
      $this->load->model('feed_model');
      $this->load->model('album_model');
    }
  }

  /**
   * 
   */
  public function index()
  {
    $rs = $this->feed_model->fetch_by_user_id($this->get_user_id());
    $albums = $this->album_model->fetch_by_user_id($this->get_user_id());
    $data = array();
    $data['feeds'] = $rs;
    $data['albums'] = $albums;
    $this->load->view('feed/index', $data);
  }

  /**
   *
   * @return type 
   */
  public function create()
  {
    if ($this->is_method_post() == TRUE)
    {
      // Validate form.
      $this->load->library('form_validation');
      $this->form_validation->set_error_delimiters('<div class="alert alert-error"><strong>Error: </strong>', '</div>');
      $this->form_validation->set_rules('name', 'Category Name', 'trim|required|max_length[45]|xss_clean');
      if ($this->form_validation->run() == TRUE)
      {
        // Success, create album & redirect
        $session_data = $this->get_user_data();
        $data = array('name' => $this->input->post('name'), 'created_by' => $session_data['user_id']);
        $category_id = $this->feed_model->create($data);

        $this->session->set_flashdata('flash_message', "Successfully created category.");
        redirect('category');
        return;
      }
    }

    $albums = $this->album_model->fetch_by_user_id($this->get_user_id());

    $data = array();
    $data['albums'] = $albums;

    $this->load->helper('form');
    $this->load->view('feed/create', $data);
  }

  /**
   *
   * @param type $category_id
   * @return type 
   */
  public function edit($category_id)
  {
    if ($this->is_method_post() == TRUE)
    {
      // Validate form.
      $this->load->library('form_validation');
      $this->form_validation->set_error_delimiters('<div class="alert alert-error"><strong>Error: </strong>', '</div>');
      $this->form_validation->set_rules('name', 'Category Name', 'trim|required|max_length[45]|xss_clean');
      if ($this->form_validation->run() == TRUE)
      {
        // Success, create album & redirect
        $session_data = $this->get_user_data();
        $data = array('name' => $this->input->post('name'), 'created_by' => $session_data['user_id']);
        $category_id = $this->feed_model->update($data, $category_id);

        $this->session->set_flashdata('flash_message', "Successfully created category.");
        redirect('category');
        return;
      }
    }
    $category = $this->category_model->find_by_id($category_id);
    $this->load->helper('form');
    $data = array();
    $data['category'] = $category;
    $this->load->view('category/edit', $data);
  }

  /**
   *
   * @param type $category_id 
   */
  public function remove($category_id)
  {
    $this->feed_model->delete($category_id);
    redirect('feed');
  }
  

}
