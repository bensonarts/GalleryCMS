<?php

class Config_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table_name = 'album_config';
  }
  
  public function get_by_album_id($album_id)
  {
    $this->db->select('*');
    $this->db->from($this->table_name);
    $this->db->where('album_id', $album_id);
    $q = $this->db->get();
    
    return $q->row();
  }
  
  public function update_by_album_id(array $data, $album_id)
  {
    $this->db->update($this->table_name, $data, array('album_id' => $album_id));
    
    return $album_id;
  }
  
  public function delete_by_album_id($album_id)
  {
    $this->db->delete($this->table_name, array('album_id' => $album_id));
    
    return $album_id;
  }
}
