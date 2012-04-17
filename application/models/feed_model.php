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
class Feed_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table_name = 'feed';
    $this->join_table_name = 'feed_album';
  }
  
  /**
   * Get feeds by user id.
   * 
   * @param type $user_id
   * @return type 
   */
  public function fetch_by_user_id($user_id)
  {
    $q = $this->db->from($this->table_name)
                  ->where('created_by', $user_id)
                  ->get();
    return $q->result();
  }
  
  /**
   * Get feed by uuid.
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
   * Get feed albums by feed id.
   * 
   * @param type $feed_id
   * @return type 
   */
  public function get_feed_albums($feed_id)
  {
    $q = $this->db->select('feed_album.*, album.name, album.id as album_id')
                  ->from($this->join_table_name)
                  ->where('feed_id', $feed_id)
                  ->join('album', 'album.id = feed_album.album_id', 'left')
                  ->order_by('order_num', 'asc')
                  ->get();
    return $q->result();
  }
  
  /**
   * Delete feed_album join table records by feed id.
   * 
   * @param type $feed_id 
   */
  public function delete_albums_by_feed_id($feed_id)
  {
    $this->db->delete($this->join_table_name, array('feed_id' => $feed_id));
  }
  
  /**
   * Delete feed_album join tables records by album id.
   * 
   * @param type $album_id 
   */
  public function delete_albums_by_album_id($album_id)
  {
    $this->db->delete($this->join_table_name, array('album_id' => $album_id));
  }
  
  /**
   * Create feed_album join table record.
   * 
   * @param array $data
   * @return type 
   */
  public function create_feed_album(array $data)
  {
    $this->db->insert($this->join_table_name, $data);
    return $this->db->insert_id();
  }
}
