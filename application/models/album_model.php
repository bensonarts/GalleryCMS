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
class Album_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table_name = 'album';
  }
  
  /**
   * Get album by user id.
   * 
   * @param type $user_id
   * @return type 
   */
  public function fetch_by_user_id($user_id)
  {
    $this->db->select('album.*, COUNT(image.id) as total_images, user.email_address as user, user.id as user_id')
             ->from($this->table_name)
             ->join('image', 'image.album_id = album.id', 'left')
             ->join('user', 'user.id = album.created_by', 'left')
             ->where('album.created_by', $user_id)
             ->group_by('album.id')
             ->order_by('updated_at', 'desc'); 
    $q = $this->db->get();
    
    return $q->result();
  }
  
  /**
   * Get album by uuid.
   * 
   * @param type $uuid
   * @return type 
   */
  public function find_by_uuid($uuid)
  {
    $q = $this->db->get_where($this->table_name, array('uuid' => $uuid));
    return $q->row();
  }
  
  /**
   * Get all albums.
   * 
   * @return type 
   */
  public function fetch_all()
  {
    $this->db->select('album.*, COUNT(image.id) as total_images, user.email_address as user, user.id as user_id')
             ->from($this->table_name)
             ->join('image', 'image.album_id = album.id', 'left')
             ->join('user', 'user.id = album.created_by', 'left')
             ->group_by('album.id')
             ->order_by('updated_at', 'desc'); 
    $q = $this->db->get();
    
    return $q->result();
  }
  
  /**
   * Paginate albums.
   * 
   * @param type $offset
   * @param type $limit
   * @return type 
   */
  public function paginate($limit = 10, $offset = 0)
  {
    $data = array();
    
    $this->db->select('album.*, COUNT(image.id) as total_images, user.email_address as user, user.id as user_id')
             ->from($this->table_name)
             ->join('image', 'image.album_id = album.id', 'left')
             ->join('user', 'user.id = album.created_by', 'left')
             ->group_by('album.id')
             ->limit($limit, $offset)
             ->order_by('updated_at', 'desc'); 
    $q = $this->db->get();
    
    if ($q->num_rows() > 0)
    {
      foreach ($q->result_array() as $row)
      {
        $data[] = $row;
      }
    }
    
    return $data;
  }
  
  /**
   * Paginate albums by user id.
   * 
   * @param type $user_id
   * @param type $limit
   * @param type $offset
   * @return type 
   */
  public function paginate_by_user_id($user_id, $limit = 10, $offset = 0)
  {
    $data = array();
    
    $this->db->select('album.*, COUNT(image.id) as total_images, user.email_address as user, user.id as user_id')
             ->from($this->table_name)
             ->join('image', 'image.album_id = album.id', 'left')
             ->join('user', 'user.id = album.created_by', 'left')
             ->group_by('album.id')
             ->where('album.created_by', $user_id)
             ->limit($limit, $offset)
             ->order_by('updated_at', 'desc'); 
    $q = $this->db->get();
    
    if ($q->num_rows() > 0)
    {
      foreach ($q->result_array() as $row)
      {
        $data[] = $row;
      }
    }
    
    return $data;
  }
}
