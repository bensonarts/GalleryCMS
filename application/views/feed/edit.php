<?php
$includes = array(
    'js' => array('jquery-ui-1.8.18.custom.min.js')
);
?>
<?php $this->load->view('inc/header', $includes); ?>

<?php if (isset($flash)): ?>
<div class="alert alert-success"><a class="close" data-dismiss="alert">x</a><strong><?php echo $flash; ?></strong></div>
<?php endif; ?>

<ul class="pager">
  <li class="previous">
    <a href="<?php echo site_url('feed'); ?>">&larr; Back to feeds</a>
  </li>
</ul>

<h1><?php echo $feed->name; ?></h1>

<span>JSON feed</span>
<pre><a href="<?php echo site_url("api/myfeed/json/$feed->uuid"); ?>" target="_blank"><?php echo site_url("api/myfeed/json/$feed->uuid"); ?></a></pre>
<span>XML feed</span>
<pre><a href="<?php echo site_url("api/myfeed/xml/$feed->uuid"); ?>" target="_blank"><?php echo site_url("api/myfeed/xml/$feed->uuid"); ?></a></pre>

<div id="reorder-feedback" class="alert alert-success" style="display: none;"></div>

<div class="alert alert-info">To create a custom feed, drag albums from the right into 'My Feed'.</div>

<div style="float:left; margin-right: 20px;">
  <h4>My Feed (drop)</h4>
  <ul id="feeds">
    <?php foreach ($feed_albums as $feed_album): ?>
    <li id="album_<?php echo $feed_album->album_id; ?>" class="ui-state-default"><?php echo $feed_album->name; ?></li>
    <?php endforeach; ?>
  </ul>
</div>

<div style="float:left;">
  <h4>My Albums (drag)</h4>
  <ul id="takeable">
    <?php foreach ($albums as $album): ?>
    <li id="album_<?php echo $album->id; ?>" class="ui-state-default"><?php echo $album->name; ?></li>
    <?php endforeach; ?>
  </ul>
</div>

<div class="clear"></div>


<script type="text/javascript">
$(document).ready(function() {
  $('#takeable').sortable({
    connectWith: '#feeds'
  });
  
  $('#feeds').sortable({
    connectWith: '#takeable',
    update : function () {
      var order = $('#feeds').sortable('serialize', { key : 'order_num[]' }); 
      $.ajax({
        url          : '<?php echo base_url(); ?>index.php/feed/reorder/<?php echo $feed->id; ?>?' + order,
        type         : 'GET',
        cache        : false,
        success      : function(response) {
          $('#reorder-feedback').show();
          $('#reorder-feedback').html('<a class="close" data-dismiss="alert">x</a><strong>Feed saved successfully.</strong>');
        },
        error        : function(jqXHR, textStatus, errorThrown) {
          alert('An error occured saving the feed.');
        }
      });
    }
  });
  
  $('ul, li').disableSelection();
});
</script>

<?php $this->load->view('inc/footer'); ?>
