<?php

class Image_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table_name = 'image';
  }
  
  public function get_images_by_album_id($album_id)
  {
    $this->db->order_by('order_num', 'asc');
    return $this->db->get_where($this->table_name, array('album_id' => $album_id));
  }
  
  public function delete_by_album_id($album_id)
  {
    $this->db->delete($this->table_name, array('album_id' => $album_id)); 
  }
  
  public function find_by_uuid($uuid)
  {
    $q = $this->db->get_where($this->table_name, array('id' => $uuid));
    return $q->row();
  }
  
  public function reorder($image_id, $position)
  {
    $this->db->update($this->table_name, array('order_num' => $position), array('id' => $image_id));
  }
  
  public function get_last_order_num($album_id)
  {
    $this->db->order_by('order_num', 'desc');
    $query = $this->db->get_where($this->table_name, array('album_id' => $album_id), 1);
    $result = $query->row();
    if (!empty($result))
    {
      return $result->order_num;
    }
    return 0;
  }

}
