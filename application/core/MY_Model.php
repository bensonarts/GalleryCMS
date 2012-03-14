<?php

class MY_Model extends CI_Model
{
  public $table_name;

  public function __construct()
  {
    parent::__construct();
  }
  
  /**
   *
   * @return type 
   */
  public function fetch_all()
  {
    $q = $this->db->get($this->table_name);
    return $q->result();
  }
  
  /**
   *
   * @param type $id
   * @return type 
   */
  public function find_by_id($id)
  {
    $q = $this->db->get_where($this->table_name, array('id' => $id));
    return $q->row();
  }
  
  /**
   *
   * @param array $data
   * @return type 
   */
  public function create(array $data)
  {
    $this->db->insert($this->table_name, $data);
    return $this->db->insert_id();
  }
  
  /**
   *
   * @param array $data
   * @param type $id 
   */
  public function update(array $data, $id)
  {
    $this->db->update($this->table_name, $data, array('id' => $id));
  }
  
  /**
   *
   * @param type $id 
   */
  public function delete($id)
  {
    $this->db->delete($this->table_name, array('id' => $id)); 
  }
  
  /**
   *
   * @return type 
   */
  protected function create_uuid()
  {
    $uuid_query = $this->db->query('SELECT UUID()');
    $uuid_rs = $uuid_query->result_array();
    return $uuid_rs[0]['UUID()'];
  }

}