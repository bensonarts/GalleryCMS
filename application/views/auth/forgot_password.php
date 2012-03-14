<?php $this->load->view('inc/header_guest'); ?>

<div class="well login">
  <div class="page-header">
    <h1>Forgot Password?</h1>
  </div>

  <?php
  echo form_open('auth/forgotpassword');

  if (isset($error)) {
    echo '<div class="alert alert-error"><strong>' . $error . '</strong></div>';
  }
  echo form_error('email_address');
  echo form_label('Email Address', 'email_address');
  echo form_input('email_address', $email_address);
  ?>
  <p>
  <?php
  echo form_button(array('id' => 'submit', 'value' => 'Reset Password', 'name' => 'submit', 'type' => 'submit', 'content' => 'Reset Password','class' => 'btn btn-primary btn-large'));
  ?>
  </p>
  <?php
  echo form_close();
  ?>
  <p><a href="<?php echo site_url('auth'); ?>">Login</a></p>
</div>

<?php $this->load->view('inc/footer_guest'); ?>
