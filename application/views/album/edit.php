<?php $this->load->view('inc/header'); ?>

<h1>Edit Album</h1>
<?php
echo form_open("album/update/$album->id");

echo form_fieldset('Album Information');

echo form_error('album_name');
echo form_label('Album Name', 'album_name');
echo form_input(array('name' => 'album_name', 'id' => 'album_name', 'value' => $album->name));

echo form_fieldset_close(); 

echo form_button(array('id' => 'submit', 'value' => 'Update', 'name' => 'submit', 'type' => 'submit', 'content' => 'Update','class' => 'btn btn-primary'));

echo form_close();
?>

<?php $this->load->view('inc/footer'); ?>
