<?php $this->load->view('inc/header'); ?>

<h1>Albums</h1>
<?php
if (isset($albums)) {
?>
<table class="table table-striped table-bordered">
  <tr>
    <th>Name</th>
    <th></th>
  </tr>
<?php
  foreach ($albums->result() as $album) {
?>
  <tr>
    <td><?php echo $album->name; ?></td>
    <td><a class="btn btn-small" href="#">Edit</a> <a class="btn btn-small btn-danger" href="#"><i class="icon-remove icon-white"></i> Delete</a></td>
  </tr>
<?php
  }
?>
</table>
<?php
}
?>

<p><a class="btn btn-primary" href="<?php echo site_url("album/create"); ?>">Create new album</a></p>

<?php $this->load->view('inc/footer'); ?>
