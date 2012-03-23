<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

/**
 * 
 */
class Category extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    if ($this->is_logged_in() == FALSE)
    {
      redirect('auth');
    }
    else
    {
      $this->load->model('category_model');
    }
  }
  
  /**
   * 
   */
  public function index()
  {
    $rs = $this->category_model->fetch_all();
    $data = array();
    $data['categories'] = $rs;
    $this->load->view('category/index', $data);
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
        $category_id = $this->category_model->create($data);

        $this->session->set_flashdata('flash_message', "Successfully created category.");
        redirect('category');
        return;
      }
    }
    $this->load->helper('form');
    $this->load->view('category/create');
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
        $category_id = $this->category_model->update($data, $category_id);

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
    $this->category_model->delete($category_id);
    redirect('category');
  }
}
