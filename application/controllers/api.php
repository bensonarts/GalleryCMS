<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Api extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('album_model');
    $this->load->model('image_model');
  }
  
  /**
   *
   * @param type $album_id 
   */
  public function upload($album_id)
  {
    $config = array();
    $config['upload_path']    = './uploads/';
    $config['allowed_types']  = 'gif|jpg|png';
    $config['max_size']       = '2048'; // 2MB
    $config['overwrite']      = TRUE;
    $config['remove_spaces']  = TRUE;
    $config['encrypt_name']   = FALSE;
    $config['overwrite']      = FALSE;
    
    $this->load->library('upload', $config);
    
    if (!$this->upload->do_upload('Filedata'))
    {
      echo $this->upload->display_errors();
    }
    else
    {
      $upload_info = $this->upload->data();
      
      $this->load->model('config_model');
      $album_config = $this->config_model->get_by_album_id($album_id);

      // Insert file information into database
      $now = date('Y-m-d H:i:s');
      $order_num = $this->image_model->get_last_order_num($album_id);
      if (empty($order_num))
      {
        $order_num = 0;
      }
      $order_num++;
      $image_data = array(
      'album_id'       => $album_id,
      'uuid'           => $this->create_uuid(),
      'name'           => $upload_info['file_name'],
      'order_num'      => $order_num,
      'caption'        => '',
      'raw_name'       => $upload_info['raw_name'],
      'file_type'      => $upload_info['file_type'],
      'file_name'      => $upload_info['file_name'],
      'file_ext'       => $upload_info['file_ext'],
      'file_size'      => $upload_info['file_size'],
      'path'           => $config['upload_path'],
      'full_path'      => $upload_info['full_path'],
      'published'      => $album_config->auto_publish,
      'created_at'     => $now,
      'updated_at'     => $now,
      'created_by'     => $this->input->post('user_id')
      );

      $this->image_model->create($image_data);

      $this->album_model->update(array('updated_at' => $now), $album_id);
    }
    
    echo $upload_info['file_name'];
  }
  
  /**
   *
   * @param type $filename 
   */
  public function resize($album_id, $filename)
  {
    $this->load->model('config_model');
    $album_config = $this->config_model->get_by_album_id($album_id);
    
    $config = array();
    $config['image_library']   = 'gd2';
    $config['source_image']    = './uploads/' . $filename;
    $config['create_thumb']    = TRUE;
    $config['maintain_ratio']  = TRUE;
    $config['width']           = $album_config->thumb_width;
    $config['height']          = $album_config->thumb_height;
    // TODO Handle cropping.
    
    $this->load->library('image_lib', $config); 
    
    $success = $this->image_lib->resize();
    $this->image_lib->clear();
    
    if ($success == TRUE)
    {
      echo 'success';
    } else {
      echo 'failure';
    }
  }
  
  /**
   * 
   */
  public function reorder()
  {
    // Reorder images with incoming AJAX request
    foreach ($this->input->get('order_num', TRUE) as $position => $image_id)
    {
      $this->image_model->reorder($image_id, $position + 1);
    }
    echo 'success';
  }
  
  /**
   *
   * @param type $type
   * @param type $album_id
   * @throws Exception 
   */
  public function feed($type, $album_id)
  {
    switch (strtolower($type))
    {
      case 'json':
        $this->output_json_feed($album_id);
        break;
      case 'xml':
        $this->output_xml_feed($album_id);
        break;
      default:
        throw new Exception('This option is not supported.');
        break;
    }
  }
  
  /**
   *
   * @param type $type
   * @param type $user_uuid
   * @throws Exception 
   */
  public function my_feed($type, $user_uuid)
  {
    // TODO Recursivley display user's albums
    switch (strtolower($type))
    {
      case 'json':
        $this->output_json_feed($user_uuid);
        break;
      case 'xml':
        $this->output_xml_feed($user_uuid);
        break;
      default:
        throw new Exception('This option is not supported.');
        break;
    }
  }
  
  /**
   *
   * @param type $album_id
   * @return type 
   */
  protected function get_feed($album_id)
  {
    $this->load->model('comment_model');
    $album = $this->album_model->find_by_id($album_id);
    $image_data = $this->image_model->get_feed($album_id);
    
    foreach ($image_data as $image)
    {
      $image->comments = $this->comment_model->get_comments_by_image_id($image->id);
      $image->url = base_url() . 'uploads/' . $image->file_name;
    }
    $album->images = $image_data;
    
    return $album;
  }
  
  /**
   *
   * @param type $album_id 
   */
  protected function output_json_feed($album_id)
  {
    header('Content-Type: text/javascript; charset=utf8');
    
    echo json_encode($this->get_feed($album_id));
  }
  
  /**
   *
   * @param type $album_id 
   */
  protected function output_xml_feed($album_id)
  {
    header("Content-Type: application/xhtml+xml; charset=utf-8");
    $data = array();
    $data['album'] = $this->get_feed($album_id);
    
    $this->load->view('feed/xml', $data);
  }
  
}
  