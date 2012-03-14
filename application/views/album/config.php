<?php $this->load->view('inc/header'); ?>

<h1>Album Config</h1>

<div class="well">
  <div class="alert">This will resize all thumbnails in this album.</div>
<?php
echo form_open("album/configure/$album->id");

echo form_fieldset('Settings');

echo form_error('thumb_width');
echo form_label('Thumbnail width', 'thumb_width');
echo form_input(array('name' => 'thumb_width', 'id' => 'thumb_width', 'value' => $config->thumb_width));

echo form_error('thumb_height');
echo form_label('Thumbnail width', 'thumb_height');
echo form_input(array('name' => 'thumb_height', 'id' => 'thumb_height', 'value' => $config->thumb_height));

echo form_label('Crop Thumbnails?', 'crop_thumbnails');
echo form_checkbox('crop_thumbnails', '1', $config->crop_thumbnails);
?>
<div class="alert alert-info">This forces the thumbnail to be crop to fit the exact dimensions.</div>
<?php
echo form_fieldset_close(); 

echo form_button(array('id' => 'submit', 'value' => 'Update', 'name' => 'submit', 'type' => 'submit', 'content' => 'Update','class' => 'btn btn-primary'));
?>
 <a href="<?php echo site_url('album'); ?>" class="btn">Cancel</a>
<?php
echo form_close();
?>
</div>

<?php $this->load->view('inc/footer'); ?>
