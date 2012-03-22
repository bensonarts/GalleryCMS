<?php $this->load->view('inc/header'); ?>

<h1>Edit Image</h1>

<div class="well">
<?php
echo form_open_multipart("image/edit/$album->id/$image->id");

if (isset($error)) {
  echo '<div class="alert alert-error"><strong>' . $error . '</strong></div>';
}

echo form_fieldset('Image Information');
?>
  <div><img src="<?php echo base_url() . 'uploads/' . $image->file_name; ?>" alt="<?php echo $image->name; ?>" /></div>
  <div class="alert alert-info">Leave this blank to keep the original image. 2MB File size limit.</div>
<?php
echo form_error('file');
echo form_label('File', 'file');
echo form_upload('file');

echo form_error('name');
echo form_label('Title', 'name');
echo form_input(array('name' => 'name', 'id' => 'name', 'value' => $image->name));

echo form_error('caption');
echo form_label('Caption', 'caption');
echo form_textarea(array('name' => 'caption', 'id' => 'caption', 'value' => $image->caption));

echo form_label('Published', 'published');
echo form_checkbox('published', '1', $image->published);

echo form_fieldset_close(); 

echo form_button(array('id' => 'submit', 'value' => 'Update', 'name' => 'submit', 'type' => 'submit', 'content' => 'Update','class' => 'btn btn-primary'));
?>
 <a href="<?php echo site_url('album/images/' . $album->id); ?>" class="btn">Cancel</a>
<?php
echo form_close();
?>
</div>

<?php $this->load->view('inc/footer'); ?>
