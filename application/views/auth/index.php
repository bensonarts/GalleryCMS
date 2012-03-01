<?php $this->load->view('inc/header_guest'); ?>

<h1>Login</h1>
<?php
echo form_open('auth/authenticate');

echo form_fieldset('User Information');

if (isset($login_error)) {
	echo $login_error;
}
echo form_label('Email Address', 'email_address');
echo form_input('email_address');

echo form_label('Password', 'password');
echo form_password('password');

echo form_fieldset_close(); 

echo form_button(array('id' => 'submit', 'value' => 'Login', 'name' => 'submit', 'type' => 'submit', 'content' => 'Sign In','class' => 'btn btn-primary'));

echo form_close();
?>

<?php $this->load->view('inc/footer'); ?>
