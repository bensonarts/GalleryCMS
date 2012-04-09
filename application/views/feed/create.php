<?php $this->load->view('inc/header'); ?>

<h1>Create Custom Feed</h1>

<div class="well">
<?php
echo form_open('feed/create');

echo form_fieldset('Feed Information');

echo form_error('name');
echo form_label('Feed Name', 'name');
echo form_input('name');

echo form_fieldset_close(); 

echo form_button(array('id' => 'submit', 'value' => 'Add', 'name' => 'submit', 'type' => 'submit', 'content' => 'Add','class' => 'btn btn-primary'));
?>
 <a href="<?php echo site_url('feed'); ?>" class="btn">Cancel</a>
<?php
echo form_close();
?>
</div>

<?php if (isset($albums)): ?>
<ul id="sortable">
  <?php foreach ($albums as $album): ?>
  <li id="album_<?php echo $album->id; ?>" class="ui-state-default">
    <div class="drag-handle" style="height: 30px"></div>
    <div class="album-container">
      <?php echo $album->name; ?>
    </div>
    <div class="btn-group">
      <a href="#" class="btn btn-danger" title="Delete"><i class="icon-remove icon-white"></i></a>
    </div>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>


<script type="text/javascript">
$(document).ready(function() {
  $('form:not(.filter) :input:visible:first').focus();
});
$("#sortable").sortable({
  handle : '.drag-handle',
  update : function () { 
    var order = $('#sortable').sortable('serialize', { key : 'order_num[]' }); 
    $.ajax({
      url          : '<?php echo base_url(); ?>index.php/api/reorder?' + order,
      type         : 'GET',
      cache        : false,
      success      : function(response) {
        $('#reorder-feedback').show();
        $('#reorder-feedback').html('<a class="close" data-dismiss="alert">x</a><strong>Changed image order successfully.</strong>');
      },
      error        : function(jqXHR, textStatus, errorThrown) {
        alert('An error occured when ordering the images.');
      }
    });
  }
});
$("#sortable").disableSelection();
</script>

<?php $this->load->view('inc/footer'); ?>
