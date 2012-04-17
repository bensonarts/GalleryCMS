<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

/**
 * Copyright (c) 2012, Aaron Benson - GalleryCMS - http://www.gallerycms.com
 * 
 * GalleryCMS is a free software application built on the CodeIgniter framework. 
 * The GalleryCMS application is licensed under the MIT License.
 * The CodeIgniter framework is licensed separately.
 * The CodeIgniter framework license is packaged in this application (license.txt) 
 * or read http://codeigniter.com/user_guide/license.html
 * 
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 * 
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 * 
 */
class MY_Model extends CI_Model
{
  public $table_name;

  public function __construct()
  {
    parent::__construct();
  }
  
  /**
   * Fetch all records.
   * 
   * @return type 
   */
  public function fetch_all()
  {
    $q = $this->db->get($this->table_name);
    return $q->result();
  }
  
  /**
   * Paginate results.
   * 
   * @param type $offset
   * @param type $limit
   * @return type 
   */
  public function paginate($limit = 10, $offset = 0)
  {
    $data = array();
    $this->db->limit($limit, $offset);
    $q = $this->db->get($this->table_name);
    
    if ($q->num_rows() > 0)
    {
      foreach ($q->result_array() as $row)
      {
        $data[] = $row;
      }
    }
    
    return $data;
  }
  
  /**
   * Find record by id.
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
   * Abstract record creation.
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
   * Abstract recort update.
   * 
   * @param array $data
   * @param type $id 
   */
  public function update(array $data, $id)
  {
    $this->db->update($this->table_name, $data, array('id' => $id));
  }
  
  /**
   * Abstract record deletion.
   * 
   * @param type $id 
   */
  public function delete($id)
  {
    $this->db->delete($this->table_name, array('id' => $id));
  }
  
  /**
   * Utiltiy method to create a UUID.
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