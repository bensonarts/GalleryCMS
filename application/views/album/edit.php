<?php $this->load->view('inc/header'); ?>

<h1>Edit Album</h1>

<div class="well">
<?php
echo form_open("album/update/$album->id");

echo form_fieldset('Album Information');

echo form_error('album_name');
echo form_label('Album Name', 'album_name');
echo form_input(array('name' => 'album_name', 'id' => 'album_name', 'value' => $album->name));

echo form_fieldset_close(); 

echo form_button(array('id' => 'submit', 'value' => 'Update', 'name' => 'submit', 'type' => 'submit', 'content' => 'Update','class' => 'btn btn-primary'));
?>
 <a href="<?php echo site_url('album'); ?>" class="btn">Cancel</a>
<?php
echo form_close();
?>
</div>

<?php $this->load->view('inc/footer'); ?>
