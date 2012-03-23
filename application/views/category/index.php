<?php $this->load->view('inc/header'); ?>

<?php if (isset($flash)): ?>
<div class="alert alert-success"><a class="close" data-dismiss="alert">x</a><strong><?php echo $flash; ?></strong></div>
<?php endif; ?>

<div class="page-header">
  <h1>Categories</h1>
</div>

<?php if (isset($categories)): ?>
<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>Name</th>
      <th width="160">
        <a class="btn btn-primary" href="<?php echo site_url("category/create"); ?>">Create new category</a>
      </th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($categories as $category): ?>
    <tr>
      <td><?php echo $category->name; ?></td>
      <td>
        <div class="btn-group">
          <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
            Action
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo site_url("category/edit/$category->id"); ?>"><i class="icon-pencil"></i> Rename</a></li>
            <li><a class="user-delete-btn" href="<?php echo site_url("category/remove/$category->id"); ?>"><i class="icon-trash"></i> Delete</a></li>
          </ul>
        </div>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<?php $this->load->view('inc/footer'); ?>
