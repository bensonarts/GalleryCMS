<?php

class MY_Model extends CI_Model
{
  public $tableName;

  public function __construct()
  {
    parent::__construct();
  }
  
  public function fetch_all()
  {
    return $this->db->get($this->tableName);
  }
  
  public function find_by_id($id)
  {
    $q = $this->db->get_where($this->tableName, array('id' => $id));
    return $q->row();
  }
  
  public function create(array $data)
  {
    $this->db->insert($this->tableName, $data);
    return $this->db->insert_id();
  }
  
  public function update(array $data, $id)
  {
    $this->db->update($this->tableName, $data, array('id' => $id));
  }
  
  public function delete($id)
  {
    $this->db->delete($this->tableName, array('id' => $id)); 
  }
  
  protected function create_uuid()
  {
    $uuid_query = $this->db->query('SELECT UUID()');
    $uuid_rs = $uuid_query->result_array();
    return $uuid_rs[0]['UUID()'];
  }

}