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
