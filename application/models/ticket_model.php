<?php

class Ticket_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->tableName = 'ticket';
  }
  
  public function get_by_uuid($uuid)
  {
    return $this->db->get_where($this->tableName, array('uuid' => $uuid));
  }

}