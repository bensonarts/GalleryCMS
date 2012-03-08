<?php

class Album_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->tableName = 'album';
  }
  
  public function fetch_by_user_id($user_id)
  {
    return $this->db->get_where($this->tableName, array('created_by' => $user_id));
  }

}
