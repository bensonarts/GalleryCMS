<?php

class Feed_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table_name = 'feed';
  }
  
  /**
   *
   * @param type $user_id
   * @return type 
   */
  public function fetch_by_user_id($user_id)
  {
    $q = $this->db->get($this->table_name)
                  ->where('user_id', $user_id);
    return $q->result();
  }
  
  /**
   *
   * @param array $data 
   * @return type
   */
  public function create(array $data)
  {
    $this->db->insert($this->table_name, $data);
    // TODO add join table `feed_album`
    return $this->db->insert_id();
  }
  
  /**
   *
   * @param array $data
   * @param type $feed_id
   * @return type 
   */
  public function update(array $data, $feed_id)
  {
    $this->db->update($this->table_name, $data, array('id' => $feed_id));
    // TODO update join table `feed_album`
    return $feed_id;
  }
  
  /**
   *
   * @param type $id 
   * @return type
   */
  public function delete($feed_id)
  {
    $this->db->delete($this->table_name, array('id' => $feed_id));
    // TODO update join table `feed_album`
    return $feed_id;
  }
}
