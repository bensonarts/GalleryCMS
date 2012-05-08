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
class Image extends MY_Controller
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
      $this->load->model('image_model');
      $this->load->model('album_model');
    }
  }
  
  /**
   * Displays image edit form, processes form submission.
   *
   * @param type $album_id
   * @param type $image_id
   * @return type 
   */
  public function edit($album_id, $image_id)
  {
    $this->load->helper('form');
    $this->load->model('config_model');
    $album = $this->album_model->find_by_id($album_id);
    $album_config = $this->config_model->get_by_album_id($album_id);
    $image = $this->image_model->find_by_id($image_id);
    
    $data = array();
    $data['image'] = $image;
    $data['album'] = $album;
    
    if ($this->is_method_post() == TRUE)
    {
      if ( ! empty($_FILES['file']['tmp_name']))
      {
        // Upload file if image has been selected.
        $config = array();
        $config['upload_path']    = './uploads/';
        $config['allowed_types']  = 'gif|jpg|png';
        $config['max_size']       = '2048'; // 2MB
        $config['overwrite']      = TRUE;
        $config['remove_spaces']  = TRUE;
        $config['encrypt_name']   = FALSE;
        $config['overwrite']      = FALSE;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('file'))
        {
          $error = $this->upload->display_errors();
          $data['error'] = $error;
          $this->load->view('image/edit', $data);
          return;
        }
        else
        {
          // Delete old image
          $old_file = $image->path . $image->file_name;
          $thumbnail_name = $image->path . $image->raw_name . '_thumb' . $image->file_ext;
          if (file_exists($old_file))
          {
            unlink($old_file);
          }
          if (file_exists($thumbnail_name))
          {
            unlink($thumbnail_name);
          }
          
          $upload_info = $this->upload->data();
          
          // Create thumbnail
          $config['image_library']   = 'gd2';
          $config['source_image']    = './uploads/' . $upload_info['file_name'];
          $config['create_thumb']    = TRUE;
          $config['maintain_ratio']  = TRUE;
          $config['width']           = $album_config->thumb_width;
          $config['height']          = $album_config->thumb_height;
          // TODO Handle cropping.

          $this->load->library('image_lib', $config); 

          $this->image_lib->resize();
          $this->image_lib->clear();
          
          // Update record
          $now = date('Y-m-d H:i:s');
          $image_data = array(
            'name'           => $this->input->post('name'),
            'caption'        => $this->input->post('caption'),
            'raw_name'       => $upload_info['raw_name'],
            'file_type'      => $upload_info['file_type'],
            'file_name'      => $upload_info['file_name'],
            'file_ext'       => $upload_info['file_ext'],
            'file_size'      => $upload_info['file_size'],
            'path'           => $config['upload_path'],
            'full_path'      => $upload_info['full_path'],
            'published'      => $this->input->post('published'),
            'updated_at'     => $now,
            'updated_by'     => $this->get_user_id()
          );
          
          $this->image_model->update($image_data, $image_id);
          
          $this->album_model->update(array('updated_at' => $now), $album_id);
          
          $this->session->set_flashdata('flash_message', "Successfully updated image.");
          
          redirect('album/images/' . $album->id);
          return;
        }
      }
      else
      {
        // Update record
        $now = date('Y-m-d H:i:s');
        $image_data = array(
            'name'           => $this->input->post('name'),
            'caption'        => $this->input->post('caption'),
            'published'      => $this->input->post('published'),
            'updated_at'     => $now,
            'updated_by'     => $this->input->post('user_id')
          );
        
        $this->image_model->update($image_data, $image_id);
        
        $this->album_model->update(array('updated_at' => $now), $album_id);
        
        $this->session->set_flashdata('flash_message', "Successfully updated image.");
        
        redirect('album/images/' . $album->id);
        return;
      }
    }
    
    $this->load->view('image/edit', $data);
  }
  
  /**
   * Downloads selected image.
   *
   * @param type $image_id 
   */
  public function download($image_id)
  {
    $image = $this->image_model->find_by_id($image_id);
    if ( ! empty($image))
    {
      header('Content-Type: ' . $image->file_type);
      header('Content-Length: ' . ($image->file_size * 1024)); // KB -> B
      header('Content-Disposition: attachment; filename="' . $image->file_name . '"');
      $open = fopen($image->path . $image->file_name, 'r');
      fpassthru($open);
      fclose($open);
    }
    echo 'image not found';
  }
  
  /**
   * Deletes image file and record.
   *
   * @param type $album_id
   * @param type $image_id 
   */
  public function remove($album_id, $image_id)
  {
    // Delete all photos with this album id
    $image = $this->image_model->find_by_id($image_id);
    if ( ! empty($image))
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
    // Delete image records
    $this->image_model->delete($image_id);
    
    $now = date('Y-m-d H:i:s');
    $this->album_model->update(array('updated_at' => $now), $album_id);
    // Delete album record
    $this->session->set_flashdata('flash_message', "Successfully deleted image.");
    redirect("album/images/$album_id");
  }
  
  /**
   * Publishes an image.
   *
   * @param type $album_id
   * @param type $image_id 
   */
  public function publish($album_id, $image_id)
  {
    $this->image_model->update(array('published' => 1), $image_id);
    $this->session->set_flashdata('flash_message', "Successfully published image.");
    redirect("album/images/$album_id");
  }
  
  /**
   * Un-publishes an image.
   *
   * @param type $album_id
   * @param type $image_id 
   */
  public function unpublish($album_id, $image_id)
  {
    $this->image_model->update(array('published' => 0), $image_id);
    $this->session->set_flashdata('flash_message', "Successfully unpublished image.");
    redirect("album/images/$album_id");
  }
  
  /**
   * TODO
   *
   * @param type $album_id
   * @param type $image_id 
   */
  public function comments($album_id, $image_id)
  {
    // TODO
    $this->load->view('image/comments');
  }
  
}