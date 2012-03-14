<?php $this->load->view('inc/header'); ?>

<h1>Create User</h1>

<div class="well">
<?php
echo form_open('user/add');

echo form_fieldset('User Information');

echo form_error('email_address');
echo form_label('Email Address', 'email_address');
echo form_input('email_address');

echo form_error('password');
echo form_label('Password', 'password');
echo form_password('password');

echo form_error('password_conf');
echo form_label('Re-type password', 'password_conf');
echo form_password('password_conf');

echo form_label('Is active?', 'is_active');
echo form_checkbox('is_active', '1', TRUE);

echo form_label('Is admin?', 'is_admin');
echo form_checkbox('is_admin', '1', FALSE);

echo form_fieldset_close();

echo form_button(array('id' => 'submit', 'value' => 'Add', 'name' => 'submit', 'type' => 'submit', 'content' => 'Add','class' => 'btn btn-primary'));
?>
 <a href="<?php echo site_url('user'); ?>" class="btn">Cancel</a>
<?php
echo form_close();
?>
</div>

<?php $this->load->view('inc/footer'); ?>
