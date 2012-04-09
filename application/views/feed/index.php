<?php $this->load->view('inc/header'); ?>

<?php if (isset($flash)): ?>
<div class="alert alert-success"><a class="close" data-dismiss="alert">x</a><strong><?php echo $flash; ?></strong></div>
<?php endif; ?>

<div class="page-header">
  <h1>My Feeds</h1>
</div>

<?php if (isset($feeds)): ?>
<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>My Custom Feeds</th>
      <th width="160">
        <a class="btn btn-primary" href="<?php echo site_url("feed/create"); ?>">Create new feed</a>
      </th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($feeds as $feed): ?>
    <tr>
      <td><?php echo $feed->name; ?></td>
      <td>
        <div class="btn-group">
          <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
            Action
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo site_url("feed/edit/$feed->id"); ?>"><i class="icon-pencil"></i> Rename</a></li>
            <li><a class="user-delete-btn" href="<?php echo site_url("feed/remove/$feed->id"); ?>"><i class="icon-trash"></i> Delete</a></li>
          </ul>
        </div>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<?php if (isset($albums)): ?>
<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>Album Feeds</th>
      <th width="160">
        <a class="btn btn-primary" href="<?php echo site_url("album/create"); ?>">Create new album</a>
      </th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($albums as $album): ?>
    <tr>
      <td><a href="<?php echo site_url("album/images/$album->id"); ?>"><?php echo $album->name; ?></a></td>
      <td>
        <div class="btn-group">
          <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
            View
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo site_url("api/feed/json/$album->id"); ?>" target="_blank"><i class="icon-book"></i> JSON</a></li>
            <li><a href="<?php echo site_url("api/feed/xml/$album->id"); ?>" target="_blank"><i class="icon-book"></i> XML</a></li>
          </ul>
        </div>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<?php $this->load->view('inc/footer'); ?>
