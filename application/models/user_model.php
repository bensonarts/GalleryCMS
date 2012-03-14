<?php

class User_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table_name = 'user';
  }
  
  public function fetch_all()
  {
    $this->db->select('user.id, user.email_address, user.is_admin, user.is_active, user.created_at, user.last_logged_in, user.last_ip, IFNULL(COUNT(`album`.`id`), 0) as `total_albums`', FALSE)
            ->from($this->table_name)
            ->join('album', 'album.created_by = user.id', 'left')
            ->group_by('user.id');
    $q = $this->db->get();
    
    return $q->result();
  }

  public function authenticate(array $data)
  {
    $query = $this->db->get_where($this->table_name, array('email_address' => $data['email_address'], 'password' => sha1($data['password'])));
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
    $q = $this->db->get_where($this->table_name, array('email_address' => $email_address));
    
    return $q->result();
  }

  public function update_last_ip($user_id)
  {
    $this->db->update($this->table_name, array('last_ip' => $this->input->ip_address()), array('id' => $user_id));
    
    return $user_id;
  }

  public function update_last_logged_in($user_id)
  {
    $now = date('Y-m-d H:i:s');
    $this->db->update($this->table_name, array('last_logged_in' => $now), array('id' => $user_id));
    
    return $user_id;
  }
  
  public function update_password($password, $user_id)
  {
    $this->db->update($this->table_name, array('password' => $password), array('id' => $user_id));
    
    return $user_id;
  }

}