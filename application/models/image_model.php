<?php

/**
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
   *
   * @param type $album_id
   * @return type 
   */
  public function get_images_by_album_id($album_id)
  {
    $this->db->from($this->table_name)
            ->order_by('order_num', 'asc')
            ->where('album_id', $album_id);
    $q = $this->db->get();
    
    return $q->result();
  }
  
  /**
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
   *
   * @param type $album_id
   * @return type 
   */
  public function get_feed($album_id)
  {
    $this->db->select('id, name as title, caption, file_name, path, created_at')
            ->from($this->table_name)
            ->where('published', 1)
            ->where('album_id', $album_id)
            ->order_by('order_num', 'asc');
    $q = $this->db->get();
    
    return $q->result();
  }
  
  /**
   *
   * @param array $data
   * @param type $id 
   */
  public function update_by_user_id(array $data, $user_id)
  {
    $this->db->update($this->table_name, $data, array('created_by' => $user_id));
  }


}
