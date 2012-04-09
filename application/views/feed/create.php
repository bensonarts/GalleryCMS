<?php
$includes = array(
    'js' => array('jquery-ui-1.8.18.custom.min.js')
);
?>
<?php $this->load->view('inc/header', $includes); ?>

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

<div style="float:left; margin-right: 20px;">
  <h4>Feed albums (drop)</h4>
  <ul id="feeds">
  </ul>
</div>

<div style="float:left;">
  <h4>Albums (drag)</h4>
  <?php if (isset($albums)): ?>
  <ul id="takeable">
    <?php foreach ($albums as $album): ?>
    <li id="album_<?php echo $album->id; ?>" class="ui-state-default"><?php echo $album->name; ?></li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
</div>

<div class="clear"></div>


<script type="text/javascript">
$(document).ready(function() {
  $('form:not(.filter) :input:visible:first').focus();
  
  $('#feeds').sortable({
    revert: true
  });
  
  $('#feeds').droppable({
    accept: '#takeable li',
    activeClass: 'takeable-active',
    drop: function(event, ui) {
      hideAlbum(ui.draggable);
    }
  });
  
  $('#takeable li').draggable({
    connectToSortable: '#feeds',
    helper: 'clone',
    revert: 'invalid'
  });
  
  function hideAlbum($item) {
    //$item.hide();
  }
    /*{
    handle : '.drag-handle',
    update : function () { 
      var order = $('#sortable').sortable('serialize', { key : 'order_num[]' }); 
      $.ajax({
        url          : 'index.php/api/reorder?' + order,
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
  });*/
  $('ul, li').disableSelection();
});
</script>

<?php $this->load->view('inc/footer'); ?>
