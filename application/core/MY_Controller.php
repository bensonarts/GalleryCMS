<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function get_user_data()
  {
    return $this->session->all_userdata();
  }
  
  public function get_user_id()
  {
    $session_data = $this->session->all_userdata();
    return $session_data['user_id'];
  }
  
  public function is_logged_in()
  {
    $session_data = $this->session->all_userdata();
    return (isset($session_data['user_id']) && $session_data['user_id'] > 0 && isset($session_data['logged_in']) && $session_data['logged_in'] === TRUE);
  }
  
  public function is_admin()
  {
    $session_data = $this->session->all_userdata();
    return 1;
    // TODO FIXME
    return (isset($session_data['is_admin']) && $session_data['is_admin'] === 1);
  }
  
}