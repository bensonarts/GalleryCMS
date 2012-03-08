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
  
}