<?php $this->load->view('inc/header'); ?>

<?php if (isset($flash)): ?>
<div class="alert alert-success"><a class="close" data-dismiss="alert">x</a><strong><?php echo $flash; ?></strong></div>
<?php endif; ?>

<h1>Albums</h1>
<?php if (isset($albums)): ?>
<table class="table table-striped table-bordered">
  <tr>
    <th>Name</th>
    <th></th>
  </tr>
<?php foreach ($albums->result() as $album): ?>
  <tr>
    <td><?php echo $album->name; ?></td>
    <td><a class="btn btn-small" href="<?php echo site_url("album/edit/$album->id"); ?>">Edit</a>
      <a class="btn btn-small btn-danger" href="<?php echo site_url("album/remove/$album->id"); ?>"><i class="icon-remove icon-white"></i> Delete</a></td>
  </tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

<p><a class="btn btn-primary" href="<?php echo site_url("album/create"); ?>">Create new album</a></p>

<?php $this->load->view('inc/footer'); ?>
