<?php

class Feed_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table_name = 'feed';
    $this->join_table_name = 'feed_album';
  }
  
  /**
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
                  ->order_by('order_num', 'desc')
                  ->get();
    return $q->result();
  }
  
  /**
   *
   * @param type $feed_id 
   */
  public function delete_albums_by_feed_id($feed_id)
  {
    $this->db->delete($this->join_table_name, array('feed_id' => $feed_id));
  }
  
  /**
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
