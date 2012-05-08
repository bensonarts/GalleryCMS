<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

/**
 * Copyright (c) 2012, Aaron Benson - GalleryCMS - http://www.gallerycms.com
 * 
 * GalleryCMS is a free software application built on the CodeIgniter framework. 
 * The GalleryCMS application is licensed under the MIT License.
 * The CodeIgniter framework is licensed separately.
 * The CodeIgniter framework license is packaged in this application (license.txt) 
 * or read http://codeigniter.com/user_guide/license.html
 * 
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 * 
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 * 
 */
class Album extends MY_Controller
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
      $this->load->model('album_model');
      $this->load->model('image_model');
    }
  }
  
  /**
   * Displays list of albums for regular users. Admins can see all albums.
   */
  public function index()
  {
    $uri = $this->uri->segment(3);
    $offset = ( ! empty($uri) && is_numeric($uri)) ? $uri : 0;
    $per_page = 10;
    
    if ($this->is_admin() === TRUE)
    {
      $album_data = $this->album_model->paginate($per_page, $offset);
      $total = count($this->album_model->fetch_all());
    }
    else
    {
      $album_data = $this->album_model->paginate_by_user_id($this->get_user_id(), $per_page, $offset);
      $total = count($this->album_model->fetch_by_user_id($this->get_user_id()));
    }
    
    for ($i = 0; $i < count($album_data); $i++)
    {
      $album_data[$i]['images'] = $this->image_model->get_last_ten_by_album_id($album_data[$i]['id']);
    }
    $data = array();
    $data['albums'] = $album_data;
    
    $this->load->library('pagination');
    
    $config = array();
    $config['base_url']         = site_url('album/index');
    $config['total_rows']       = $total;
    $config['per_page']         = $per_page;
    $config['full_tag_open']    = '<div class="pagination"><ul>';
    $config['full_tag_close']   = '</ul></div>';
    $config['first_link']       = '&larr; First';
    $config['last_link']        = 'Last &rarr;';
    $config['first_tag_open']   = '<li>';
    $config['first_tag_close']  = '</li>';
    $config['prev_link']        = 'Previous';
    $config['prev_tag_open']    = '<li class="prev">';
    $config['prev_tag_close']   = '</li>';
    $config['next_link']        = 'Next';
    $config['next_tag_open']    = '<li>';
    $config['next_tag_close']   = '</li>';
    $config['last_tag_open']    = '<li>';
    $config['last_tag_close']   = '</li>';
    $config['cur_tag_open']     =  '<li class="active"><a href="#">';
    $config['cur_tag_close']    = '</a></li>';
    $config['num_tag_open']     = '<li>';
    $config['num_tag_close']    = '</li>';
    $config['num_links']        = 4;
    
    $this->pagination->initialize($config);
    
    $this->load->model('user_model');
    $data['user'] = $this->user_model->find_by_id($this->get_user_id());
    
    $flash_login_success = $this->session->flashdata('flash_message'); 
    
    if (isset($flash_login_success) && ! empty($flash_login_success))
    {
      $data['flash'] = $flash_login_success;
    }
    
    $data['is_admin'] = $this->is_admin();
    $session_data = $this->get_user_data();
    $data['email_address'] = $session_data['email_address'];
    
    $this->load->view('album/index', $data);
  }
  
  /**
   * View form for creation of album.
   */
  public function create()
  {
    $this->load->helper('form');
    $this->load->view('album/create');
  }
  
  /**
   * Process album addition.
   */
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
                   'name'       => $this->input->post('album_name'),
                   'uuid'       => $this->create_uuid(),
                   'created_by' => $user_data['user_id'],
                   'updated_by' => $user_data['user_id'],
                   'created_at' => $now,
                   'updated_at' => $now);
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
  
  /**
   * Display stored album to edit.
   *
   * @param type $album_id 
   */
  public function edit($album_id)
  {
    $this->load->helper('form');
    
    $data = array();
    $data['album'] = $this->album_model->find_by_id($album_id);
    
    $this->load->view('album/edit', $data);
  }
  
  /**
   *
   * @param type $album_id 
   */
  public function update($album_id)
  {
    // Validate form.
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<div class="alert alert-error"><strong>Error: </strong>', '</div>');
    
    $data = array();
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
                   'updated_by' => $user_data['user_id'],
                   'created_at' => $now,
                   'updated_at' => $now);
      $this->album_model->update($data, $album_id);
      $this->session->set_flashdata('flash_message', "Successfully updated album.");
      redirect('album');
    }
  }
  
  /**
   * Deletes album, according image records and files.
   *
   * @param type $album_id 
   */
  public function remove($album_id)
  {
    // Delete all photos with this album id
    $rs = $this->image_model->get_images_by_album_id($album_id);
    if ( ! empty($rs))
    {
      foreach ($rs as $image)
      {
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
    // Delete feeds
    $this->load->model('feed_model');
    $this->feed_model->delete_albums_by_album_id($album_id);
    
    $this->session->set_flashdata('flash_message', "Successfully deleted album.");
    redirect('album');
  }
  
  /**
   * Displays images for selected album. 
   *
   * @param type $album_id 
   */
  public function images($album_id)
  {
    $this->load->model('config_model');
    
    $data = array();
    $data['config']    = $this->config_model->get_by_album_id($album_id);
    $data['album']     = $this->album_model->find_by_id($album_id);
    $data['images']    = $this->image_model->get_images_by_album_id($album_id);
    $data['user_id']   = $this->get_user_id();
    
    $flash_login_success = $this->session->flashdata('flash_message'); 
    if (isset($flash_login_success) && ! empty($flash_login_success))
    {
      $data['flash'] = $flash_login_success;
    }
    
    $this->load->view('album/images', $data);
  }
  
  /**
   * Displays configuration options for album, also processes form.
   *
   * @param type $album_id
   * @return type 
   */
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
            'album_id'        => $album_id,
            'thumb_width'     => $this->input->post('thumb_width'),
            'thumb_height'    => $this->input->post('thumb_height'),
            'crop_thumbnails' => $this->input->post('crop_thumbnails'),
            'auto_publish'    => $this->input->post('auto_publish')
        ), $album_id);
        
        // Update all album's thumbnails
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
            $this->image_lib->resize();
            $this->image_lib->clear();
            $config = array();
          }
        }
        
        $now = date('Y-m-d H:i:s');
        $this->album_model->update(array('updated_at' => $now), $album_id);
        
        redirect("album/images/$album_id");
        return;
      }
    }
    
    $data = array();
    $data['config'] = $this->config_model->get_by_album_id($album_id);
    $data['album'] = $this->album_model->find_by_id($album_id);
    
    $this->load->view('album/config', $data);
  }
  
  /**
   * Handles resizing of images per album.
   *
   * @param type $filename 
   */
  public function resize($album_id, $image_id)
  {
    $this->load->model('config_model');
    $image = $this->image_model->find_by_id($image_id);
    if (isset($image))
    {
      $album_config = $this->config_model->get_by_album_id($album_id);

      $this->load->library('image_lib');
      $config = array();
      $config['image_library']   = 'gd2';
      $config['source_image']    = './uploads/' . $image->file_name;
      $config['create_thumb']    = TRUE;
      $config['maintain_ratio']  = TRUE;
      $config['width']           = $album_config->thumb_width;
      $config['height']          = $album_config->thumb_height;
      $config['thumb_marker']    = '_thumb';
      // TODO Handle cropping
      $this->image_lib->initialize($config);
      $this->image_lib->resize();

      $success = $this->image_lib->resize();
      $this->image_lib->clear();
    }
    
    if ($success == TRUE)
    {
      echo $image->raw_name . '_thumb' . $image->file_ext;
    } else {
      echo 'failure';
    }
  }
  
  /**
   * Handles reordering of images.
   */
  public function reorder()
  {
    // Reorder images with incoming AJAX request
    if( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
    {
      foreach ($this->input->get('order_num', TRUE) as $position => $image_id)
      {
        $this->image_model->reorder($image_id, $position + 1);
      }
      echo 'success';
    }
  }
  
}
