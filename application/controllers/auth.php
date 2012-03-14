<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Auth extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('user_model');
  }

  public function index()
  {
    $this->load->helper('form');
    $this->load->view('auth/index');
    if ($this->_is_logged_in() == TRUE)
    {
      redirect('user');
    }
  }

  public function authenticate()
  {
    // Authenticate user.
    $this->load->helper('form');
    $userData = array('email_address' => $this->input->post('email_address'), 'password' => $this->input->post('password'));
    $user_id = $this->user_model->authenticate($userData);
    if ($user_id > 0)
    {
      // Create session var
      $user = $this->user_model->find_by_id($user_id);
      $this->create_login_session($user);

      $this->session->set_flashdata('flash_message', 'You are logged in.');

      redirect('album');
    }
    else
    {
      $data['login_error'] = 'Incorrect login';
      $this->load->view('auth/index', $data);
    }
  }

  public function logout()
  {
    $this->session->sess_destroy();
    redirect('auth');
  }

  public function forgotpassword()
  {
    $this->load->helper('form');
    $email_address = $this->input->post('email_address');
    $data['email_address'] = $email_address;
    if (isset($email_address))
    {
      // Validate form
      $this->load->library('form_validation');
      $this->form_validation->set_error_delimiters('<div class="alert alert-error"><strong>Error: </strong>', '</div>');
      $this->form_validation->set_rules('email_address', 'Email Address', 'trim|required|valid_email|xss_clean');
      if ($this->form_validation->run() == TRUE)
      {
        $query = $this->user_model->get_by_email_address($email_address);
        // No user found
        if ($query->num_rows() == 0)
        {
          $data['error'] = 'No user exists with the supplied email address.';
        }
        else
        {
          // Found user, email them with a link to reset password.
          $user = $query->row();
          // Create ticket
          $this->load->model('ticket_model');
          $ticket_id = $this->ticket_model->create(array('user_id' => $user->id, 'uuid' => $this->create_uuid()));
          $ticket = $this->ticket_model->find_by_id($ticket_id);
          // Send email
          // TODO Get this from config
          $subject = 'GalleryCMS - Forgot Password';
          $reset_pw_url = base_url('auth/resetpassword/' . $ticket->uuid);
          $message = "You have requested to reset your password.\r\n Click the following link to reset your password: $reset_pw_url";
          $this->send_mail($user->email_address, $subject, $message);
          // Show to success page
          $this->load->view('auth/forgot_password_success');
          return;
        }
      }
    }
    $this->load->view('auth/forgot_password', $data);
  }

  public function resetpassword($uuid)
  {
    $this->load->model('ticket_model', 'ticket_model');
    // Check for ticket
    $ticket = $this->ticket_model->get_by_uuid($uuid);
    if ($ticket->num_rows() == 0)
    {
      $data['error'] = 'This link has expired.';
    }
    else
    {
      $ticket = $ticket->row();
      $user = $this->user_model->find_by_id($ticket->user_id);
      $data['uuid'] = $uuid;

      $new_password = $this->input->post('password');
      if (isset($new_password))
      {
        // Validate new password
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="alert alert-error"><strong>Error: </strong>', '</div>');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|matches[password_conf]|sha1');
        if ($this->form_validation->run() == TRUE)
        {
          // Save new password
          $this->user_model->update_password($this->input->post('password'), $user->id);
          // Delete ticket
          $this->ticket_model->delete($ticket->id);
          // Authenticate user
          $user_id = $this->user_model->authenticate(array('email_address' => $user->email_address, 'password' => $new_password));
          if ($user_id > 0)
          {
            $this->create_login_session($user);
          }
          // Redirect
          redirect('album');
        }
      }
    }
    $this->load->view('auth/reset_password', $data);
  }

  protected function create_login_session($user)
  {
    $session_data = array(
        'email_address' => $user->email_address,
        'user_id' => $user->id,
        'logged_in' => TRUE,
        'is_admin' => $user->is_admin,
        'ip' => $this->input->ip_address()
    );
    $this->session->set_userdata($session_data);
  }

  private function _is_logged_in()
  {
    $session_data = $this->session->all_userdata();
    return (isset($session_data['user_id']) && $session_data['user_id'] > 0 && $session_data['logged_in'] == TRUE);
  }

}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */
