<?php $this->load->view('inc/header'); ?>

<h1>Users</h1>
<?php
if (isset($users)) {
?>
<table class="table table-striped table-bordered">
  <tr>
    <th>Email address</th>
    <th>Is admin</th>
    <th>Created</th>
    <th>Last logged in</th>
    <th># of albums</th>
    <th colspan="2">Last IP</th>
  </tr>
<?php
  foreach ($users->result() as $user) {
    echo '<pre>' . print_r($user, true) . '</pre>';
?>
  <tr>
    <td><?php echo $user->email_address; ?></td>
    <td><?php echo $user->is_admin; ?></td>
    <td><?php echo $user->created_at; ?></td>
    <td><?php echo $user->last_logged_in; ?></td>
    <td>??</td>
    <td><?php echo $user->last_ip; ?></td>
    <td><a class="btn btn-small" href="<?php echo site_url("user/edit/$user->id"); ?>">Edit</a> 
      <a class="btn btn-small btn-danger" href="#"><i class="icon-remove icon-white"></i> Delete</a></td>
  </tr>
<?php
  }
?>
</table>
<?php
}
?>

<p><a class="btn btn-primary" href="<?php echo site_url("user/create"); ?>">Create new user</a></p>

<?php $this->load->view('inc/footer'); ?>
