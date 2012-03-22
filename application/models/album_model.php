<?php

class Album_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table_name = 'album';
  }
  
  /**
   *
   * @param type $user_id
   * @return type 
   */
  public function fetch_by_user_id($user_id)
  {
    $this->db->select('album.*, COUNT(image.id) as total_images');
    $this->db->from($this->table_name); 
    $this->db->join('image', 'image.album_id = album.id', 'left');
    $this->db->where('album.created_by', $user_id);
    $this->db->group_by('album.id');
    $q = $this->db->get();
    
    return $q->result();
  }
  
  /**
   *
   * @return type 
   */
  public function fetch_all()
  {
    $this->db->select('album.*, COUNT(image.id) as total_images');
    $this->db->from($this->table_name); 
    $this->db->join('image', 'image.album_id = album.id', 'left');
    $this->db->group_by('album.id');
    $q = $this->db->get();
    
    return $q->result();
  }
  
  /**
   *
   * @param type $offset
   * @param type $limit
   * @return type 
   */
  public function paginate($limit = 10, $offset = 0)
  {
    $data = array();
    
    $this->db->select('album.*, COUNT(image.id) as total_images');
    $this->db->from($this->table_name); 
    $this->db->join('image', 'image.album_id = album.id', 'left');
    $this->db->group_by('album.id');
    $this->db->limit($limit, $offset);
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
   *
   * @param type $user_id
   * @param type $limit
   * @param type $offset
   * @return type 
   */
  public function paginate_by_user_id($user_id, $limit = 10, $offset = 0)
  {
    $data = array();
    
    $this->db->select('album.*, COUNT(image.id) as total_images');
    $this->db->from($this->table_name); 
    $this->db->join('image', 'image.album_id = album.id', 'left');
    $this->db->group_by('album.id');
    $this->db->where('album.created_by', $user_id);
    $this->db->limit($limit, $offset);
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
