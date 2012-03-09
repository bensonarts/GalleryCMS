<?php

class User_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->tableName = 'user';
  }

  public function authenticate(array $data)
  {
    $query = $this->db->get_where($this->tableName, array('email_address' => $data['email_address'], 'password' => sha1($data['password'])));
    $user_id = 0;
    $is_valid = ($query->num_rows() > 0);
    if ($is_valid == TRUE)
    {
        $user_id = $query->row()->id;
        $this->update_last_logged_in($user_id);
        $this->update_last_ip($user_id);
    }
    return $user_id;
  }
  
  public function get_by_email_address($email_address)
  {
    return $this->db->get_where($this->tableName, array('email_address' => $email_address));
  }

  public function update_last_ip($user_id)
  {
    $this->db->update($this->tableName, array('last_ip' => $this->input->ip_address()), array('id' => $user_id));
  }

  public function update_last_logged_in($user_id)
  {
    $now = date('Y-m-d H:i:s');
    $this->db->update($this->tableName, array('last_logged_in' => $now), array('id' => $user_id));
  }
  
  public function update_password($password, $user_id)
  {
    $this->db->update($this->tableName, array('password' => $password), array('id' => $user_id));
  }

}