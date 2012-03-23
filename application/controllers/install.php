<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

/**
 * 
 */
class Install extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    if ($this->db->table_exists('user'))
    {
      redirect('auth');
      exit();
    }
  }
  
  public function index()
  {
    if ($this->is_method_post() == TRUE)
    {
      // Validate form.
      $this->load->library('form_validation');
      $this->form_validation->set_error_delimiters('<div class="alert alert-error"><strong>Error: </strong>', '</div>');
      $this->form_validation->set_rules('email_address', 'Email Address', 'trim|required|valid_email|xss_clean');
      $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|matches[password_conf]|sha1');
      if ($this->form_validation->run() == TRUE)
      {
        $this->create_tables();
        
      }
    }
    $this->load->helper('form');
    $this->load->view('category/create');
  }
  
  protected function create_tables()
  {
    // TODO
    exit();
    
    $this->load->dbforge();
    
    $user = array(
        'id'             => array(
            'type'            => 'INT',
            'auto_increment'  => TRUE
        ),
        'uuid'           => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45
        ),
        'email_address'  => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45,
            'null'            => TRUE
        ),
        'password'       => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45,
            'null'            => TRUE
        ),
        'is_active'      => array(
            'type'            => 'TINYINT',
            'constraint'      => 1,
            'default'         => 0
        ),
        'is_admin'       => array(
            'type'            => 'TINYINT',
            'constraint'      => 1,
            'default'         => 0
        ),
        'created_at'     => array(
            'type'            => 'DATETIME',
            'null'            => TRUE
        ),
        'updated_at'     => array(
            'type'            => 'DATETIME',
            'null'            => TRUE
        ),
        'last_logged_in' => array(
            'type'            => 'DATETIME',
            'null'            => TRUE
        ),
        'last_ip'        => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45
        )
    );
    
    $this->dbforge->add_field($user);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('user', TRUE);
    
    $album = array(
        'id'             => array(
            'type'            => 'INT',
            'auto_increment'  => TRUE
        ),
        'name'           => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45
        ),
        'published'      => array(
            'type'            => 'TINYINT',
            'constraint'      => 1,
            'default'         => 1
        ),
        'created_by'     => array(
            'type'            => 'INT',
        ),
        'updated_by'     => array(
            'type'            => 'INT',
        ),
        'created_at'     => array(
            'type'            => 'DATETIME'
        ),
        'updated_at'     => array(
            'type'            => 'DATETIME'
        )
    );
    
    $this->dbforge->add_field($album);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('album', TRUE);
    
    $album_config = array(
        'id'             => array(
            'type'            => 'INT',
            'auto_increment'  => TRUE
        ),
        'album_id'       => array(
            'type'            => 'INT',
            'null'            => TRUE
        ),
        'thumb_width'    => array(
            'type'            => 'INT',
            'default'         => 100
        ),
        'thumb_height'   => array(
            'type'            => 'INT',
            'default'         => 100
        ),
        'crop_thumbnails' => array(
            'type'            => 'TINYINT',
            'constraint'      => 1,
            'default'         => 0
        ),
        'auto_publish'   => array(
            'type'            => 'TINYINT',
            'constraint'      => 1,
            'default'         => 0
        )
    );
    
    $this->dbforge->add_field($album_config);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('album_config', TRUE);
    
    $category = array(
        'id'             => array(
            'type'            => 'INT',
            'auto_increment'  => TRUE
        ),
        'name'           => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45
        ),
        'created_by'     => array(
            'type'            => 'INT'
        )
    );
    
    $this->dbforge->add_field($category);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('category', TRUE);
    
    // Add comment, image, image_category, ticket, user_session, create user account.
  }
  
}
