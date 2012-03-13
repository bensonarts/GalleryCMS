<?php $this->load->view('inc/header_guest'); ?>

<div class="page-header">
  <h1>Login</h1>
</div>

<?php
echo form_open('auth/authenticate');

echo form_fieldset('User Information');

if (isset($login_error)) {
	echo '<div class="alert alert-error"><strong>' . $login_error . '</strong></div>';
}
echo form_label('Email Address', 'email_address');
echo form_input('email_address');

echo form_label('Password', 'password');
echo form_password('password');

echo form_fieldset_close(); 

echo form_button(array('id' => 'submit', 'value' => 'Login', 'name' => 'submit', 'type' => 'submit', 'content' => 'Sign In','class' => 'btn btn-primary'));

echo form_close();
?>
<p><a href="<?php echo site_url('auth/forgotpassword'); ?>">Forgot Password?</a></p>

<?php $this->load->view('inc/footer'); ?>
