<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Image extends MY_Controller
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
      $this->load->model('image_model', 'Image_Model');
    }
  }
  
  public function remove($album_id, $image_id)
  {
    // Delete all photos with this album id
    $image = $this->Image_Model->find_by_id($image_id);
    if (isset($image))
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
    $this->Image_Model->delete($image_id);
    // Delete album record
    $this->session->set_flashdata('flash_message', "Successfully deleted image.");
    redirect("album/images/$album_id");
  }
  
}