<?php

class Image_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->tableName = 'image';
  }
  
  public function get_images_by_album_id($album_id)
  {
    return $this->db->get_where($this->tableName, array('album_id' => $album_id));
  }
  
  public function delete_by_album_id($album_id)
  {
    $this->db->delete($this->tableName, array('album_id' => $album_id)); 
  }
  
  public function find_by_uuid($uuid)
  {
    $q = $this->db->get_where($this->tableName, array('id' => $uuid));
    return $q->row();
  }

}
