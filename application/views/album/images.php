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
  
  <ul class="pager">
    <li class="previous">
      <a href="<?php echo site_url('album'); ?>">&larr; Back to albums</a>
    </li>
  </ul>
  
  <div class="well">
    <h4 style="margin-bottom: 10px;">Upload images for album: <?php echo $album->name; ?></h4>
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
    $img_url = base_url() . 'uploads/' . $image->file_name;
    ?>
    <li id="image_<?php echo $image->id; ?>" class="ui-state-default" style="height: <?php echo $config->thumb_height + 10; ?>px">
      <div class="drag-handle" style="height: <?php echo $config->thumb_height + 5; ?>px"></div>
      <div class="image-container">
        <a class="album-images img-fancy thumbnail" href="<?php echo $img_url; ?>" title="<?php echo $image->caption; ?>">
          <img src="<?php echo base_url() . 'uploads/' . $image->raw_name . '_thumb' . $image->file_ext . '?r=' . rand(); ?>" alt="<?php echo $image->caption; ?>" />
        </a>
      </div>
      <div class="info" style="left: <?php echo $config->thumb_width + 50; ?>px">
        File name: <?php echo $image->name; ?><br />
        Caption: 
          <?php if (empty($image->caption)): ?>
            <a href="<?php echo site_url("image/edit/$album->id/$image->id"); ?>">Create one</a>
          <?php else: ?>
            <?php echo $image->caption; ?> 
          <?php endif; ?>
          <br />
        <?php /* Comments: <?php echo $image->comments; ?><br /> */ ?>
        File size: <?php echo $image->file_size; ?> KB<br />
      </div>
      <div class="btn-group">
        <a href="<?php echo $img_url; ?>" class="btn img-fancy" rel="tooltip" data-original-title="Preview"><i class="icon-zoom-in"></i></a>
        <a href="<?php echo site_url("image/download/$image->id"); ?>" class="btn" title="Download" rel="tooltip" data-original-title="Download"><i class="icon-download-alt"></i></a>
        <a href="<?php echo site_url("image/edit/$album->id/$image->id"); ?>" class="btn" title="Edit" rel="tooltip" data-original-title="Edit"><i class="icon-pencil"></i></a>
        <?php /* <a href="<?php echo site_url("image/comments/$album->id/$image->id"); ?>" class="btn" title="Comments" rel="tooltip" data-original-title="Comments"><i class="icon-comment"></i></a> */ ?>
        <?php if ($image->published == 1): ?>
        <a href="<?php echo site_url("image/unpublish/$album->id/$image->id"); ?>" class="btn btn-success" title="Published" rel="tooltip" data-original-title="Published"><i class="icon-ok icon-white"></i></a>
        <?php else: ?>
        <a href="<?php echo site_url("image/publish/$album->id/$image->id"); ?>" class="btn" title="Unpublished" rel="tooltip" data-original-title="Unpublished"><i class="icon-ok"></i></a>
        <?php endif; ?>
        <a href="#image-modal" class="btn btn-danger image-delete-btn" title="Delete" rel="tooltip" action="<?php echo site_url("image/remove/$album->id/$image->id"); ?>" data-toggle="modal" data-original-title="Delete"><i class="icon-remove icon-white"></i></a>
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
      <li><a href="<?php echo site_url("album/configure/$album->id"); ?>"><i class="icon-cog"></i>Configure</a></li>
      <li><a href="<?php echo site_url("api/feed/json/$album->uuid"); ?>" target="_blank"><i class="icon-book"></i>JSON Feed</a></li>
      <li><a href="<?php echo site_url("api/feed/xml/$album->uuid"); ?>" target="_blank"><i class="icon-book"></i>XML Feed</a></li>
      <li class="nav-header">Info</li>
      <li>Images: <?php echo $total_images; ?></li>
      <li>Album file size: <?php echo round($total_file_size / 1024, 2); ?> MB</li>
    </ul>
  </div>
</span>
<div class="clear"></div>

<div class="modal hide fade" id="image-modal">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>Delete Image</h3>
  </div>
  <div class="modal-body">
    <p><strong>Are you sure you want to delete this image?</strong></p>
  </div>
  <div class="modal-footer">
    <a id="image-modal-delete-btn" href="#" class="btn btn-danger">Delete</a>
    <a href="#" class="btn" data-dismiss="modal">Cancel</a>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  $('.btn-group > a').tooltip();
  $('#upload-btn').hide();
  $('#new-images').hide();
  
  $('a.img-fancy').fancybox();
  
  $('.image-delete-btn').click(function() {
    deleteUrl = $(this).attr('action');
  });
  
  $('#image-modal').on('show', function() {
    $('#image-modal-delete-btn').attr('href', deleteUrl);
  });
  
  $("#sortable").sortable({
    handle : '.drag-handle',
    update : function () { 
      var order = $('#sortable').sortable('serialize', { key : 'order_num[]' }); 
      $.ajax({
        url          : '<?php echo base_url(); ?>index.php/album/reorder?' + order,
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
    'onError'        : function(event, ID, fileObj, errorObj) {
      
    },
    'onComplete'     : function(event, ID, fileObj, response, data) {
      var fileName = response;
      $('#upload-btn').hide();
      $('#new-images').show();
      $.ajax({
        url          : '<?php echo base_url(); ?>index.php/album/resize/<?php echo $album->id; ?>/' + response,
        type         : 'POST',
        cache        : false,
        success      : function(response) {
          if (response !== 'failure') {
            var new_image = '<li><img src="<?php echo base_url(); ?>uploads/' + response + '" /><br />' + response + '</li>';
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
