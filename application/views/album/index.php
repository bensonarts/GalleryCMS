<?php $this->load->view('inc/header'); ?>

<?php if (isset($flash)): ?>
<div class="alert alert-success"><a class="close" data-dismiss="alert">x</a><strong><?php echo $flash; ?></strong></div>
<?php endif; ?>

<h2>Albums</h2>
<?php if (isset($albums)): ?>
<table class="table table-striped table-bordered">
  <tr>
    <th width="80%">Name</th>
    <th width="10%">Photos</th>
    <th width="10%"><a class="btn btn-primary" href="<?php echo site_url("album/create"); ?>">Create new album</a></th>
  </tr>
<?php foreach ($albums->result() as $album): ?>
  <tr>
    <td><?php echo $album->name; ?></td>
    <td>999</td>
    <td>
      <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
          Action
          <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li><a href="<?php echo site_url("album/edit/$album->id"); ?>"><i class="icon-pencil"></i> Rename</a></li>
          <li><a href="<?php echo site_url("album/images/$album->id"); ?>"><i class="icon-picture"></i> Images</a></li>
          <li><a href="<?php echo site_url("album/configure/$album->id"); ?>"><i class="icon-cog"></i> Configure</a></li>
          <li><a class="album-delete-btn" href="#album-modal" data-toggle="modal" rel="<?php echo site_url("album/remove/$album->id"); ?>"><i class="icon-trash"></i> Delete</a></li>
        </ul>
      </div>
    </td>
  </tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

<div class="modal hide fade" id="album-modal">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>Delete Album</h3>
  </div>
  <div class="modal-body">
    <p><strong>Are you sure you want to delete this album?</strong></p>
    <p>This will permanently delete all photos in this album.</p>
  </div>
  <div class="modal-footer">
    <a id="album-modal-delete-btn" href="#" class="btn btn-danger">Delete</a>
    <a href="#" class="btn" data-dismiss="modal">Cancel</a>
  </div>
</div>

<script type="text/javascript">
var deleteUrl;
$(document).ready(function() {
  $('.album-delete-btn').click(function() {
    deleteUrl = $(this).attr('rel');
  });
  
  $('#album-modal').on('show', function() {
    $('#album-modal-delete-btn').attr('href', deleteUrl);
  });
});
</script>

<?php $this->load->view('inc/footer'); ?>
