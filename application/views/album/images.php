<?php
$includes = array('js' => array('jquery-ui-1.8.18.custom.min.js', 'swfobject.js', 'jquery.uploadify.v2.1.4.min.js'), 'css' => array('jquery.ui.all.css', 'uploadify.css'));
?>
<?php $this->load->view('inc/header', $includes); ?>

<?php if (isset($flash)): ?>
<div class="alert alert-success"><a class="close" data-dismiss="alert">x</a><strong><?php echo $flash; ?></strong></div>
<?php endif; ?>

<h2><?php echo $album->name; ?></h2>
<input id="file_upload" type="file" name="file_upload" />
<p><a href="javascript:$('#file_upload').uploadifyUpload()">Upload Files</a></p>

<div id="new-images">
  <ul id="new-image-list"></ul>
</div>

<?php if (isset($images)): ?>
<ul id="sortable">
  <?php $count = 0; ?>
  <?php foreach ($images->result() as $image): ?>
    <?php $count++; ?>
  <li id="image_<?php echo $count; ?>" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
    <img src="<?php echo base_url() . 'uploads/' . $image->raw_name . '_thumb' . $image->file_ext; ?>" alt="<?php echo $image->caption; ?>" /><br />
    <span><?php echo $image->name; ?></span><br />
    <span>ID: <?php echo $image->id; ?> ORDER_NUM: <?php echo $count; ?></span>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>
<div id="info"></div>

<script type="text/javascript">
$(document).ready(function() {
  $("#sortable").sortable({
    update : function () { 
      var order = $('#sortable').sortable('serialize', { key : 'order_num[]' }); 
      $('#info').html(order);
      $.ajax({
        url          : '<?php echo base_url(); ?>index.php/api/reorder?' + order,
        type         : 'GET',
        cache        : false,
        success      : function(reponse) {
          alert(response);
        },
        error        : function(jqXHR, textStatus, errorThrown) {
          alert(textStatus);
        }
      });
    }
  });
  $( "#sortable" ).disableSelection();
  
  $('#file_upload').uploadify({
    'uploader'       : '<?php echo base_url(); ?>flash/uploadify.swf',
    'script'         : '<?php echo base_url(); ?>index.php/api/upload/<?php echo $album->id; ?>',
    'cancelImg'      : '<?php echo base_url(); ?>images/cancel.png',
    'folder'         : '/uploads',
    'auto'           : false,
    'multi'          : true,
    'scriptData'     : { 'user_id' : '<?php echo $user_id; ?>' },
    'fileExt'        : '*.jpg;*.jpeg;*.gif;*.png',
    'fileDesc'       : 'Image files',
    'sizeLimit'      : 2097152, // 2MB
    'onComplete'     : function(event, ID, fileObj, response, data) {
      $.ajax({
        url          : '<?php echo base_url(); ?>index.php/api/resize/' + response,
        type         : 'POST',
        cache        : false,
        success      : function(response) {
          if (response === 'success') {
            var file_name = fileObj.name.substr(0, fileObj.name.lastIndexOf('.'));
            var file_ext = fileObj.name.split('.').pop();
            var new_image = '<li><a href="#"><img src="<?php echo base_url(); ?>uploads/' + file_name + '_thumb.' + file_ext + '" /><br />' + fileObj.name + '</a></li>';
            $('#new-image-list').append(new_image);
          } else {
            var fail_message = '<li>Thumbnail creation failed for: ' + fileObj.name + '</li>';
            $('#new-image-list').append(fail_message);
          }
        },
        error        : function(jqXHR, textStatus, errorThrown) {
          alert(textStatus);
        }
      });
    }
  });
});
</script>

<?php $this->load->view('inc/footer'); ?>
