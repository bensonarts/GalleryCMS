<?php $this->load->view('inc/header_guest'); ?>

<div class="page-header">
  <h1>Email Sent</h1>
</div>

<p>An email has been sent to your email address with a link to reset your password.</p>

<p><a href="<?php echo site_url('auth'); ?>">Login</a></p>

<?php $this->load->view('inc/footer_guest'); ?>
