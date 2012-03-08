<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Test</title>
  <link rel="stylesheet" href="/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/css/bootstrap-responsive.min.css">
  <?php if (isset($css)): ?>
    <?php foreach ($css as $stylesheet): ?>
  <link rel="stylesheet" href="/css/<?php echo $stylesheet; ?>">
    <?php endforeach; ?>
  <?php endif; ?>
  <link rel="stylesheet" href="/css/main.css">
  <link rel="shortcut icon" href="/images/favicon.ico">
  
  <script type="text/javascript" src="/js/jquery-1.7.1.min.js"></script>
  <script type="text/javascript" src="/js/bootstrap.min.js"></script>
  <?php if (isset($js)): ?>
    <?php foreach ($js as $script): ?>
  <script type="text/javascript" src="/js/<?php echo $script; ?>"></script>
    <?php endforeach; ?>
  <?php endif; ?>
</head>
<body>
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand" href="/">Project name</a>
      <div class="nav-collapse">
        <ul class="nav">
          <li class="active"><a href="<?php echo site_url("album"); ?>">Albums</a></li>
          <li><a href="<?php echo site_url("user"); ?>">Users</a></li>
        </ul>
        <p class="navbar-text pull-right"><a href="<?php echo site_url("auth/logout"); ?>">Logout</a></p>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>

<div class="container-fluid">
