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
class User_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table_name = 'user';
  }
  
  /**
   * Get all users.
   * 
   * @return type 
   */
  public function fetch_all()
  {
    $this->db->select('user.id, user.email_address, user.is_admin, user.is_active, user.created_at, user.last_logged_in, user.last_ip, IFNULL(COUNT(`album`.`id`), 0) as `total_albums`', FALSE)
            ->from($this->table_name)
            ->join('album', 'album.created_by = user.id', 'left')
            ->group_by('user.id');
    $q = $this->db->get();
    
    return $q->result();
  }
  
  /**
   * Authenticate user.
   * 
   * @param array $data
   * @return type 
   */
  public function authenticate(array $data)
  {
    $query = $this->db->get_where($this->table_name, array('email_address' => $data['email_address'], 'password' => sha1($data['password'])));
    $user_id = 0;
    $is_valid = ($query->num_rows() > 0);
    if ($is_valid == TRUE)
    {
        $user_id = $query->row()->id;
        $this->update_last_logged_in($user_id);
        $this->update_last_ip($user_id);
    }
    
    return $user_id;
  }
  
  /**
   * Find user by email address.
   * 
   * @param type $email_address
   * @return type 
   */
  public function get_by_email_address($email_address)
  {
    $q = $this->db->get_where($this->table_name, array('email_address' => $email_address));
    
    return $q->row();
  }
  
  /**
   * Update last_ip column for user.
   * 
   * @param type $user_id
   * @return type 
   */
  public function update_last_ip($user_id)
  {
    $this->db->update($this->table_name, array('last_ip' => $this->input->ip_address()), array('id' => $user_id));
    
    return $user_id;
  }
  
  /**
   * Update last_logged_in column for user.
   * 
   * @param type $user_id
   * @return type 
   */
  public function update_last_logged_in($user_id)
  {
    $now = date('Y-m-d H:i:s');
    $this->db->update($this->table_name, array('last_logged_in' => $now), array('id' => $user_id));
    
    return $user_id;
  }
  
  /**
   * Update user's password.
   * 
   * @param type $password
   * @param type $user_id
   * @return type 
   */
  public function update_password($password, $user_id)
  {
    $this->db->update($this->table_name, array('password' => $password), array('id' => $user_id));
    
    return $user_id;
  }

}