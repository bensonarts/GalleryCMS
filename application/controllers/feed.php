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
class Feed extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->is_logged_in() == FALSE)
    {
      redirect('auth');
      exit();
    }
    else
    {
      $this->load->model('feed_model');
      $this->load->model('album_model');
    }
  }

  /**
   * Displays a list of feeds.
   */
  public function index()
  {
    $rs = $this->feed_model->fetch_by_user_id($this->get_user_id());
    $albums = $this->album_model->fetch_by_user_id($this->get_user_id());
    $data = array();
    $data['feeds'] = $rs;
    $data['albums'] = $albums;
    
    $flash_login_success = $this->session->flashdata('flash_message'); 
    if (isset($flash_login_success) && ! empty($flash_login_success))
    {
      $data['flash'] = $flash_login_success;
    }
    
    $this->load->view('feed/index', $data);
  }

  /**
   * Display feed creation view, handles form submission.
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
      $this->form_validation->set_rules('name', 'Feed Name', 'trim|required|max_length[45]|xss_clean');
      if ($this->form_validation->run() == TRUE)
      {
        // Success, create album & redirect
        $session_data = $this->get_user_data();
        $now = date('Y-m-d H:i:s');
        $data = array(
            'name' => $this->input->post('name'), 
            'created_by' => $session_data['user_id'], 
            'uuid' => $this->create_uuid(),
            'created_at' => $now);
        
        $feed_id = $this->feed_model->create($data);

        $this->session->set_flashdata('flash_message', "Successfully created feed.");
        redirect('feed/edit/' . $feed_id);
        return;
      }
    }

    $this->load->helper('form');
    $this->load->view('feed/create');
  }

  /**
   * Displays feed edit mode.
   *
   * @param type $category_id
   * @return type 
   */
  public function edit($feed_id)
  {
    $albums = $this->album_model->fetch_by_user_id($this->get_user_id());
    $feed = $this->feed_model->find_by_id($feed_id);
    $feed_albums = $this->feed_model->get_feed_albums($feed_id);
    
    $feed_album_ids = array();
    foreach ($feed_albums as $feed_album)
    {
      array_push($feed_album_ids, $feed_album->album_id);
    }
    // Filter out selected albums.
    $albums_filtered = array();
    foreach ($albums as $album)
    {
      if ( ! in_array($album->id, $feed_album_ids))
      {
        array_push($albums_filtered, $album);
      }
    }
    
    $data = array();
    $data['albums'] = $albums_filtered;
    $data['feed'] = $feed;
    $data['feed_albums'] = $feed_albums;
    
    $flash_login_success = $this->session->flashdata('flash_message'); 
    if (isset($flash_login_success) && ! empty($flash_login_success))
    {
      $data['flash'] = $flash_login_success;
    }

    $this->load->view('feed/edit', $data);
  }
  
  /**
   * Display rename feed view. Process form submission.
   *
   * @param type $feed_id
   * @return type 
   */
  public function rename($feed_id)
  {
    if ($this->is_method_post() == TRUE)
    {
      // Validate form.
      $this->load->library('form_validation');
      $this->form_validation->set_error_delimiters('<div class="alert alert-error"><strong>Error: </strong>', '</div>');
      $this->form_validation->set_rules('name', 'Feed Name', 'trim|required|max_length[45]|xss_clean');
      if ($this->form_validation->run() == TRUE)
      {
        // Success, create album & redirect
        $data = array('name' => $this->input->post('name'));
        
        $this->feed_model->update($data, $feed_id);

        $this->session->set_flashdata('flash_message', "Successfully renamed feed.");
        redirect('feed');
        return;
      }
    }
    
    $data = array();
    $data['feed'] = $this->feed_model->find_by_id($feed_id);

    $this->load->helper('form');
    $this->load->view('feed/rename', $data);
  }

  /**
   * Deletes a feed.
   *
   * @param type $category_id 
   */
  public function remove($feed_id)
  {
    $this->feed_model->delete($feed_id);
    $this->feed_model->delete_albums_by_feed_id($feed_id);
    $this->session->set_flashdata('flash_message', "Successfully deleted feed.");
    redirect('feed');
  }
  
  /**
   * Re-orders albums in a feed.
   *
   * @param type $feed_id 
   */
  public function reorder($feed_id)
  {
    if( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
    {
      $this->feed_model->delete_albums_by_feed_id($feed_id);
      foreach ($this->input->get('order_num', TRUE) as $position => $album_id)
      {
        if ($album_id > 0)
        {
          $feed_album_id = $this->feed_model->create_feed_album(array('feed_id' => $feed_id, 'album_id' => $album_id, 'order_num' => $position + 1));
        }
      }
      echo 'success';
    }
  }
  

}
