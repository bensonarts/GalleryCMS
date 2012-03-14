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
      $this->load->model('album_model');
      $this->load->model('image_model');
    }
  }
  
  public function index()
  {
    if ($this->is_admin() === TRUE)
    {
      $data['albums'] = $this->album_model->fetch_all();
    }
    else
    {
      $data['albums'] = $this->album_model->fetch_by_user_id($this->get_user_id());
    }
    $flash_login_success = $this->session->flashdata('flash_message'); 
    if (isset($flash_login_success) && ! empty($flash_login_success))
    {
      $data['flash'] = $flash_login_success;
    }
    // TODO Get image count for each album.
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
      $data = array(
                   'name' => $this->input->post('album_name'), 
                   'created_by' => $user_data['user_id'],
                   'updated_by' => $user_data['user_id']);
      $new_ablum_id = $this->album_model->create($data);
      
      $this->load->model('config_model');
      $this->config_model->create(array(
          'album_id' => $new_ablum_id,
          'thumb_width' => 100,
          'thumb_height' => 100,
          'crop_thumbnails' => 0
      ));
      
      $this->session->set_flashdata('flash_message', "Successfully created album.");
      redirect('album/images/' . $new_ablum_id);
    }
  }
  
  public function edit($album_id)
  {
    $this->load->helper('form');
    $data['album'] = $this->album_model->find_by_id($album_id);
    $this->load->view('album/edit', $data);
  }
  
  public function update($album_id)
  {
    // Validate form.
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<div class="alert alert-error"><strong>Error: </strong>', '</div>');
    $data['album'] = $this->album_model->find_by_id($album_id);
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
      $this->album_model->update($data, $album_id);
      $this->session->set_flashdata('flash_message', "Successfully updated album.");
      redirect('album');
    }
  }
  
  public function remove($album_id)
  {
    // Delete all photos with this album id
    $rs = $this->image_model->get_images_by_album_id($album_id);
    if ( ! empty($rs))
    {
      foreach ($rs as $image) {
        $file_name = $image->path . $image->file_name;
        $thumbnail_name = $image->path . $image->raw_name . '_thumb' . $image->file_ext;
        if (file_exists($file_name))
        {
          unlink($file_name);
        }
        if (file_exists($thumbnail_name))
        {
          unlink($thumbnail_name);
        }
      }
    }
    // Delete image records
    $this->image_model->delete_by_album_id($album_id);
    // Delete album record
    $this->album_model->delete($album_id);
    // Delete album config
    $this->load->model('config_model');
    $this->config_model->delete_by_album_id($album_id);
    
    $this->session->set_flashdata('flash_message', "Successfully deleted album.");
    redirect('album');
  }
  
  public function images($album_id)
  {
    $this->load->model('config_model');
    
    $data['config'] = $this->config_model->get_by_album_id($album_id);
    $data['album'] = $this->album_model->find_by_id($album_id);
    $data['images'] = $this->image_model->get_images_by_album_id($album_id);
    $data['user_id'] = $this->get_user_id();
    $flash_login_success = $this->session->flashdata('flash_message'); 
    if (isset($flash_login_success) && ! empty($flash_login_success))
    {
      $data['flash'] = $flash_login_success;
    }
    $this->load->view('album/images', $data);
  }
  
  public function configure($album_id)
  {
    $this->load->model('config_model');
    $this->load->helper('form');
    
    $thumb_width = $this->input->post('thumb_width');
    
    if (isset($thumb_width) && ! empty($thumb_width))
    {
      $this->load->library('form_validation');
      $this->form_validation->set_error_delimiters('<div class="alert alert-error"><strong>Error: </strong>', '</div>');
      $this->form_validation->set_rules('thumb_width', 'Thumbnail width', 'trim|required|max_length[3]|less_than[300]|greater_than[0]|is_natural|xss_clean');
      $this->form_validation->set_rules('thumb_height', 'Thumbnail height', 'trim|required|max_length[3]|less_than[300]|greater_than[0]|is_natural|xss_clean');

      if ($this->form_validation->run() != FALSE)
      {
        $this->config_model->update_by_album_id(array(
            'album_id' => $album_id,
            'thumb_width' => $this->input->post('thumb_width'),
            'thumb_height' => $this->input->post('thumb_height'),
            'crop_thumbnails' => $this->input->post('crop_thumbnails')
        ), $album_id);
        
        // TODO Update all album's thumbnails
        $images = $this->image_model->get_images_by_album_id($album_id);
        if ( ! empty($images))
        {
          $this->load->library('image_lib');
          $config = array();
          foreach ($images as $image)
          {
            $config['image_library']   = 'gd2';
            $config['source_image']    = './uploads/' . $image->file_name;
            $config['create_thumb']    = TRUE;
            $config['maintain_ratio']  = TRUE;
            $config['width']           = $this->input->post('thumb_width');
            $config['height']          = $this->input->post('thumb_height');
            $config['thumb_marker']    = '_thumb';
            // TODO Handle cropping
            $this->image_lib->initialize($config);
            $success = $this->image_lib->resize();
            $this->image_lib->clear();
            $config = array();
          }
        }
        
        redirect('album');
        return;
      }
    }
    
    $data['config'] = $this->config_model->get_by_album_id($album_id);
    $data['album'] = $this->album_model->find_by_id($album_id);
    
    $this->load->view('album/config', $data);
  }
  
}
