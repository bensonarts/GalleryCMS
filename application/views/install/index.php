<?php $this->load->view('inc/header_guest'); ?>

<div class="well login">
  <div class="page-header">
    <h1>Register</h1>
  </div>
  <?php
  echo form_open('install/index');

  if (isset($install_error)) {
    echo '<div class="alert alert-error"><strong>' . $install_error . '</strong></div>';
  }
  echo form_error('email_address');
  echo form_label('Email Address', 'email_address');
  echo form_input('email_address', $email);

  echo form_error('password');
  echo form_label('Password', 'password');
  echo form_password('password');
  
  echo form_error('password_conf');
  echo form_label('Re-type password', 'password_conf');
  echo form_password('password_conf');
  ?>
  <p>
  <?php
  echo form_button(array('id' => 'submit', 'value' => 'Register', 'name' => 'submit', 'type' => 'submit', 'content' => 'Register','class' => 'btn btn-primary btn-large'));
  ?>
  </p>
  <?php
  echo form_close();
  ?>
</div>

<?php $this->load->view('inc/footer_guest'); ?>
