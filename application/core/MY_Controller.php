<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
  }
  
  protected function get_user_data()
  {
    return $this->session->all_userdata();
  }
  
  protected function get_user_id()
  {
    $session_data = $this->session->all_userdata();
    return $session_data['user_id'];
  }
  
  protected function is_logged_in()
  {
    $session_data = $this->session->all_userdata();
    return (isset($session_data['user_id']) && $session_data['user_id'] > 0 && isset($session_data['logged_in']) && $session_data['logged_in'] === TRUE);
  }
  
  protected function is_admin()
  {
    $session_data = $this->session->all_userdata();
    return (isset($session_data['is_admin']) && $session_data['is_admin'] == 1);
  }
  
  protected function create_uuid()
  {
    $uuid_query = $this->db->query('SELECT UUID()');
    $uuid_rs = $uuid_query->result_array();
    return $uuid_rs[0]['UUID()'];
  }
  
  protected function send_mail($to, $subject, $message)
  {
    $this->load->library('email');
    $this->email->from($this->config->item('from_email_address'));
    $this->email->to($to);
    $this->email->subject($subject);
    $this->email->message($message);
    $this->email->send();
  }
  
  protected function is_method_post()
  {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
  }
  
}