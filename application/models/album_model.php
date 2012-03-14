<?php

class Album_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table_name = 'album';
  }
  
  public function fetch_by_user_id($user_id)
  {
    $this->db->select('album.*, COUNT(image.id) as total_images');
    $this->db->from($this->table_name); 
    $this->db->join('image', 'image.album_id = album.id', 'left');
    $this->db->where('created_by', $user_id);
    $this->db->group_by('album.id');
    $q = $this->db->get();
    return $q;
  }
  
  public function fetch_all()
  {
    $this->db->select('album.*, COUNT(image.id) as total_images');
    $this->db->from($this->table_name); 
    $this->db->join('image', 'image.album_id = album.id', 'left');
    $this->db->group_by('album.id');
    $q = $this->db->get();
    return $q;
  }

}
