<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL; ?>
<album>
  <?php if ( ! empty($album)): ?>
  <title><![CDATA[<?php echo $album->name; ?>]]></title>
  <images>
    <?php foreach ($album->images as $image): ?>
    <image>
      <title><![CDATA[<?php echo $image->title; ?>]]></title>
      <caption><![CDATA[<?php echo $image->caption; ?>]]></caption>
      <filename><?php echo $image->file_name; ?></filename>
      <url><?php echo $image->url; ?></url>
      <thumb><?php echo $image->thumb; ?></thumb>
    </image>
    <? endforeach; ?>
  </images>
  <?php endif; ?>
</album>