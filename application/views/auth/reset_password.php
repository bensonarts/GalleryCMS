<?php $this->load->view('inc/header_guest'); ?>

<div class="page-header">
  <h1>Reset Password</h1>
</div>

<?php
if (isset($error)) {
  echo '<div class="alert alert-error"><strong>' . $error . '</strong></div>';
} else {
  echo form_open("auth/resetpassword/$uuid");
  
  echo form_fieldset('User Information');
  
  echo form_error('password');
  echo form_label('New Password', 'password');
  echo form_password('password');
  
  echo form_error('password_conf');
  echo form_label('Re-type password', 'password_conf');
  echo form_password('password_conf');
  
  echo form_fieldset_close(); 
  
  echo form_button(array('id' => 'submit', 'value' => 'Save New Password', 'name' => 'submit', 'type' => 'submit', 'content' => 'Save New Password','class' => 'btn btn-primary'));
  
  echo form_close();
}
?>
<p><a href="<?php echo site_url('auth'); ?>">Login</a></p>

<?php $this->load->view('inc/footer_guest'); ?>
