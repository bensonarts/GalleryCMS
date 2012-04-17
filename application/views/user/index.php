<?php $this->load->view('inc/header'); ?>

<?php if (isset($flash)): ?>
<div class="alert alert-success"><a class="close" data-dismiss="alert">x</a><strong><?php echo $flash; ?></strong></div>
<?php endif; ?>

<div class="page-header">
  <h1>Users</h1>
</div>

<?php if (isset($users)): ?>
<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>Email address</th>
      <th>Is admin</th>
      <th>Is activated</th>
      <th>Created</th>
      <th>Last logged in</th>
      <th># of albums</th>
      <th>Last IP</th>
      <th>
        <?php if ($user_data['is_admin'] == 1): ?>
        <a class="btn btn-primary" href="<?php echo site_url("user/create"); ?>">Create new user</a>
        <?php endif; ?>
      </th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($users as $user): ?>
    <tr>
      <td><?php echo $user->email_address; ?></td>
      <td><?php echo (($user->is_admin == 1) ? 'Yes' : 'No'); ?></td>
      <td><?php echo (($user->is_active == 1) ? 'Yes' : 'No'); ?></td>
      <td><?php echo date('M j, Y', strtotime($user->created_at)); ?></td>
      <td><?php
      if (isset($user->last_logged_in)):
        echo date('M j, Y', strtotime($user->last_logged_in));
      endif;
      ?></td>
      <td><?php echo $user->total_albums; ?></td>
      <td><?php echo $user->last_ip; ?></td>
      <td>
        <div class="btn-group">
          <a class="btn dropdown-toggle<?php if ($user_data['is_admin'] != 1 || $user_data['user_id'] == $user->id): ?> disabled<?php endif; ?>" data-toggle="dropdown" href="#">
            Action
            <span class="caret"></span>
          </a>
          <?php if ($user_data['is_admin'] == 1 && $user_data['user_id'] != $user->id): ?>
          <ul class="dropdown-menu">
            <li><a href="<?php echo site_url("user/edit/$user->id"); ?>"><i class="icon-pencil"></i> Edit</a></li>
            <?php if ($user_data['is_admin'] == 1): ?>
            <li><a href="<?php echo site_url("user/deactivate/$user->id"); ?>"><i class="icon-ban-circle"></i> Deactivate</a></li>
            <li><a class="user-delete-btn" href="#user-modal" data-toggle="modal" rel="<?php echo site_url("user/remove/$user->id"); ?>">
                <i class="icon-trash"></i> Delete</a></li>
            <?php endif; ?>
          </ul>
          <?php endif; ?>
        </div>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<div class="modal hide fade" id="user-modal">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>Delete User</h3>
  </div>
  <div class="modal-body">
    <p><strong>Are you sure you want to delete this user?</strong></p>
    <p>This will permanently delete all photos and albums belonging to this user.</p>
  </div>
  <div class="modal-footer">
    <a id="user-modal-delete-btn" href="#" class="btn btn-danger">Delete</a>
    <a href="#" class="btn" data-dismiss="modal">Cancel</a>
  </div>
</div>

<script type="text/javascript">
var deleteUrl;
$(document).ready(function() {
  $('.user-delete-btn').click(function() {
    deleteUrl = $(this).attr('rel');
  });
  
  $('#user-modal').on('show', function() {
    $('#user-modal-delete-btn').attr('href', deleteUrl);
  });
});
</script>

<?php $this->load->view('inc/footer'); ?>
