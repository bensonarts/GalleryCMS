<?php $this->load->view('inc/header_guest'); ?>

<div class="well login">
  <div class="page-header">
    <h1>Login</h1>
  </div>
  <?php
  echo form_open('auth/authenticate');

  if (isset($login_error)) {
          echo '<div class="alert alert-error"><strong>' . $login_error . '</strong></div>';
  }
  echo form_label('Email Address', 'email_address');
  echo form_input('email_address', $email);

  echo form_label('Password', 'password');
  echo form_password('password');
  ?>
  <p>
  <?php
  echo form_button(array('id' => 'submit', 'value' => 'Login', 'name' => 'submit', 'type' => 'submit', 'content' => 'Sign In','class' => 'btn btn-primary btn-large'));
  ?>
  </p>
  <?php
  echo form_close();
  ?>
  <p><a href="<?php echo site_url('auth/forgotpassword'); ?>">Forgot Password?</a></p>
</div>

<?php $this->load->view('inc/footer_guest'); ?>
