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
class MY_Controller extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
  }
  
  /**
   * Get user session data.
   * 
   * @return type 
   */
  protected function get_user_data()
  {
    return $this->session->all_userdata();
  }
  
  /**
   * Get logged in user id.
   * 
   * @return type 
   */
  protected function get_user_id()
  {
    $session_data = $this->session->all_userdata();
    return $session_data['user_id'];
  }
  
  /**
   * Determine if user is authenticated.
   * 
   * @return type 
   */
  protected function is_logged_in()
  {
    $session_data = $this->session->all_userdata();
    return (isset($session_data['user_id']) && $session_data['user_id'] > 0 && isset($session_data['logged_in']) && $session_data['logged_in'] == TRUE);
  }
  
  /**
   * Determine if logged in user is admin.
   * 
   * @return type 
   */
  protected function is_admin()
  {
    $session_data = $this->session->all_userdata();
    return (isset($session_data['is_admin']) && $session_data['is_admin'] == 1);
  }
  
  /**
   * Utility method for creating UUIDs.
   * 
   * @return type 
   */
  protected function create_uuid()
  {
    $uuid_query = $this->db->query('SELECT UUID()');
    $uuid_rs = $uuid_query->result_array();
    return $uuid_rs[0]['UUID()'];
  }
  
  /**
   * Utility method for sending emails.
   * 
   * @param type $to
   * @param type $subject
   * @param type $message 
   */
  protected function send_mail($to, $subject, $message)
  {
    $this->load->library('email');
    $this->email->from($this->config->item('from_email_address'));
    $this->email->to($to);
    $this->email->subject($subject);
    $this->email->message($message);
    $this->email->send();
  }
  
  /**
   * Utility method for determining if the request is a POST.
   * 
   * @return type 
   */
  protected function is_method_post()
  {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
  }
  
}