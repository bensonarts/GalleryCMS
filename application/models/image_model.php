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
class Image_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table_name = 'image';
  }
  
  /**
   * Get images by album id.
   * 
   * @param type $album_id
   * @return type 
   */
  public function get_images_by_album_id($album_id)
  {
    $this->db->select('image.*, COUNT(image_comment.id) as comments')
             ->from($this->table_name)
             ->join('image_comment', 'image_comment.image_id = image.id', 'left')
             ->order_by('image.order_num', 'asc')
             ->group_by('image.id')
             ->where('image.album_id', $album_id);
    $q = $this->db->get();
    
    return $q->result();
  }
  
  /**
   * Gets ten images from album
   * 
   * @param type $album_id 
   * @return type
   */
  public function get_last_ten_by_album_id($album_id)
  {
    $this->db->from($this->table_name)
             ->where(array('album_id' => $album_id))
             ->order_by('order_num', 'asc')
             ->limit(10);
    $q = $this->db->get();
    return $q->result();
  }
  
  /**
   * Delete images by album id.
   * 
   * @param type $album_id
   * @return type 
   */
  public function delete_by_album_id($album_id)
  {
    $this->db->delete($this->table_name, array('album_id' => $album_id));
    
    return $album_id;
  }
  
  /**
   * Get image by uud.
   * 
   * @param type $uuid
   * @return type 
   */
  public function find_by_uuid($uuid)
  {
    $q = $this->db->get_where($this->table_name, array('id' => $uuid));
    
    return $q->row();
  }
  
  /**
   * Reorder images.
   * 
   * @param type $image_id
   * @param type $position
   * @return type 
   */
  public function reorder($image_id, $position)
  {
    $this->db->update($this->table_name, array('order_num' => $position), array('id' => $image_id));
    
    return $image_id;
  }
  
  /**
   * Get greatest order num for a given album.
   * 
   * @param type $album_id
   * @return int 
   */
  public function get_last_order_num($album_id)
  {
    $this->db->from($this->table_name)
              ->order_by('order_num', 'desc')
              ->where('album_id', $album_id)
              ->limit(1);
    $q = $this->db->get();
    $result = $q->row();
    if (!empty($result))
    {
      return $result->order_num;
    }
    return 0;
  }
  
  /**
   * Return image set for xml/json output.
   * 
   * @param type $album_id
   * @return type 
   */
  public function get_feed($album_id)
  {
    $this->db->select('id, name as title, caption, file_name, raw_name, file_ext, path, created_at')
             ->from($this->table_name)
             ->where('published', 1)
             ->where('album_id', $album_id)
             ->order_by('order_num', 'asc');
    $q = $this->db->get();
    
    return $q->result();
  }
  
  /**
   * Update image by user id.
   * 
   * @param array $data
   * @param type $id 
   */
  public function update_by_user_id(array $data, $user_id)
  {
    $this->db->update($this->table_name, $data, array('created_by' => $user_id));
  }


}
