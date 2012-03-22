<?php $this->load->view('inc/header'); ?>

<h1>Create Album</h1>

<div class="well">
<?php
echo form_open('album/add');

echo form_fieldset('Album Information');

echo form_error('album_name');
echo form_label('Album Name', 'album_name');
echo form_input('album_name');

echo form_fieldset_close(); 

echo form_button(array('id' => 'submit', 'value' => 'Add', 'name' => 'submit', 'type' => 'submit', 'content' => 'Add','class' => 'btn btn-primary'));
?>
 <a href="<?php echo site_url('album'); ?>" class="btn">Cancel</a>
<?php
echo form_close();
?>
</div>

<script type="text/javascript">
$(document).ready(function() {
  $('form:not(.filter) :input:visible:first').focus();
});
</script>

<?php $this->load->view('inc/footer'); ?>
