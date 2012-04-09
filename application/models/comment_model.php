<?php

class Comment_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table_name = 'image_comment';
  }
  
  public function get_comments_by_image_id($image_id)
  {
    $this->db->select('comment')
             ->from($this->table_name)
             ->where('published', 1)
             ->where('image_id', $image_id);
    $q = $this->db->get();
    return $q->result();
  }
}
