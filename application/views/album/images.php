<?php
$includes = array(
    'js' => array('jquery-ui-1.8.18.custom.min.js', 'swfobject.js', 'jquery.uploadify.v2.1.4.min.js', 'fancybox/jquery.fancybox-1.3.4.pack.js'), 
    'css' => array('uploadify.css', 'fancybox/jquery.fancybox-1.3.4.css'));
?>
<?php $this->load->view('inc/header', $includes); ?>

<?php if (isset($flash)): ?>
<div class="alert alert-success"><a class="close" data-dismiss="alert">x</a><strong><?php echo $flash; ?></strong></div>
<?php endif; ?>

<div class="w100" style="margin-bottom: 10px;">
  <div class="page-header">
    <h1><?php echo $album->name; ?></h1>
  </div>
  <div class="well">
    <h2 style="margin-bottom: 10px;">Upload</h2>
    <input id="file_upload" type="file" name="file_upload" />
    <p id="upload-btn" style="margin:10px 0;">
      <a href="javascript:$('#file_upload').uploadifyUpload()" class="btn btn-primary btn-large">Upload Files</a>
    </p>
    <div id="new-images">
      <h4>Uploaded Images</h4>
      <p><a class="btn" href="<?php echo site_url("album/images/$album->id"); ?>" style="margin: 10px 0;"><i class="icon-refresh"></i> Refresh</a></p>
      <ul id="new-image-list"></ul>
      <div class="clear"></div>
    </div>
  </div>
  <h3>JSON feed: <pre style="font-weight:normal;"><a href="<?php echo site_url("api/feed/json/$album->id"); ?>"><?php echo site_url("api/feed/json/$album->id"); ?></a></pre></h3>
  <h3>XML feed: <pre style="font-weight:normal;"><a href="<?php echo site_url("api/feed/xml/$album->id"); ?>"><?php echo site_url("api/feed/xml/$album->id"); ?></a></pre></h3>
</div>

<div id="reorder-feedback" class="alert alert-success" style="display: none;"></div>

<span class="left w75">
  <?php 
  $total_file_size = 0;
  $total_images = 0;
  $img_url = '';
  ?>
  <?php if (isset($images)): ?>
  <ul id="sortable">
    <?php foreach ($images as $image): ?>
    <?php 
    $total_file_size += $image->file_size; 
    $total_images++;
    $img_url = base_url() . 'uploads/' . $image->name;
    ?>
    <li id="image_<?php echo $image->id; ?>" class="ui-state-default">
      <div class="drag-handle"></div>
      <div class="image-container">
        <a class="album-images" ref="group" href="<?php echo $img_url; ?>" title="<?php echo $image->caption; ?>">
          <img src="<?php echo base_url() . 'uploads/' . $image->raw_name . '_thumb' . $image->file_ext; ?>" alt="<?php echo $image->caption; ?>" />
        </a>
      </div>
      <div class="info">
        File name: <?php echo $image->name; ?><br />
        Caption: <?php echo $image->caption; ?><br />
        Comments: <?php echo 0; /** @todo */ ?><br />
        File size: <?php echo $image->file_size; ?> KB
      </div>
      <div class="btn-group">
        <a href="<?php echo site_url("image/view/$image->id"); ?>" class="btn" title="View"><i class="icon-zoom-in"></i></a>
        <a href="<?php echo site_url("image/download/$image->id"); ?>" class="btn" title="Download"><i class="icon-download"></i></a>
        <a href="<?php echo site_url("image/edit/$image->id"); ?>" class="btn" title="Edit"><i class="icon-pencil"></i></a>
        <a href="<?php echo site_url("image/tags/$image->id"); ?>" class="btn" title="Tags"><i class="icon-tags"></i></a>
        <a href="<?php echo site_url("image/comments/$image->id"); ?>" class="btn" title="Comments"><i class="icon-comment"></i></a>
        <a href="<?php echo site_url("image/remove/$album->id/$image->id"); ?>" class="btn btn-danger" title="Delete"
           onclick="confirm('Are you sure you wish to delete this image?')"><i class="icon-remove icon-white"></i></a>
      </div>
    </li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
</span>
<span class="right w20">
  <div class="well sidebar-nav">
    <ul class="nav nav-list">
      <li class="nav-header"><?php echo $album->name; ?></li>
      <li><a href="<?php echo site_url("album/edit/$album->id"); ?>"><i class="icon-pencil"></i>Rename</a></li>
      <li><a href="<?php echo site_url("album/unpublish/$album->id"); ?>"><i class="icon-ban-circle"></i>Unpublish</a></li>
      <li class="nav-header">Info</li>
      <li>Images: <?php echo $total_images; ?></li>
      <li>Album file size: <?php echo round($total_file_size / 1024, 2); ?> MB</li>
    </ul>
  </div>
</span>
<div class="clear"></div>

<script type="text/javascript">
$(document).ready(function() {
  $('#upload-btn').hide();
  $('#new-images').hide();
  
  $('a.album-images').fancybox();
  
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
    'wmode'          : 'opaque',
    'onSelect'       : function(event, ID, fileObj) {
      $('#upload-btn').show();
    },
    'onCancel'       : function(event, ID, fileObj) {
      $('#upload-btn').hide();
    },
    'onComplete'     : function(event, ID, fileObj, response, data) {
      $('#upload-btn').hide();
      $('#new-images').show();
      $.ajax({
        url          : '<?php echo base_url(); ?>index.php/api/resize/' + response,
        type         : 'POST',
        cache        : false,
        success      : function(response) {
          if (response === 'success') {
            var file_name = fileObj.name.substr(0, fileObj.name.lastIndexOf('.'));
            var file_ext = fileObj.name.split('.').pop();
            var new_image = '<li><img src="<?php echo base_url(); ?>uploads/' + file_name + '_thumb.' + file_ext + '" /><br />' + fileObj.name + '</li>';
            $('#new-image-list').append(new_image);
          } else {
            var fail_message = '<li>Thumbnail creation failed for: ' + fileObj.name + '</li>';
            $('#new-image-list').append(fail_message);
          }
        },
        error        : function(jqXHR, textStatus, errorThrown) {
          alert('Error occurred when generating thumbnails.');
        }
      });
    }
  });
});
</script>

<?php $this->load->view('inc/footer'); ?>
