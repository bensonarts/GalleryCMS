<?php $this->load->view('inc/header'); ?>

<?php if (isset($flash)): ?>
<div class="alert alert-success"><a class="close" data-dismiss="alert">x</a><strong><?php echo $flash; ?></strong></div>
<?php endif; ?>

<h1>Users</h1>

<?php if (isset($users)): ?>
<table class="table table-striped table-bordered">
  <tr>
    <th>Email address</th>
    <th>Is admin</th>
    <th>Created</th>
    <th>Last logged in</th>
    <th># of albums</th>
    <th colspan="2">Last IP</th>
  </tr>
<?php foreach ($users->result() as $user): ?>
  <tr>
    <td><?php echo $user->email_address; ?></td>
    <td><?php echo $user->is_admin; ?></td>
    <td><?php echo $user->created_at; ?></td>
    <td><?php echo $user->last_logged_in; ?></td>
    <td>??</td>
    <td><?php echo $user->last_ip; ?></td>
    <td><a class="btn btn-small" href="<?php echo site_url("user/edit/$user->id"); ?>">Edit</a> 
      <a class="btn btn-small btn-danger" href="<?php echo site_url("user/remove/$user->id"); ?>"
        onclick="return confirm('Are you sure you want to delete this user?\r\rThis user\'s albums and images will be permanently deleted.');">
        <i class="icon-remove icon-white"></i> Delete</a></td>
  </tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

<p><a class="btn btn-primary" href="<?php echo site_url("user/create"); ?>">Create new user</a></p>

<?php $this->load->view('inc/footer'); ?>
