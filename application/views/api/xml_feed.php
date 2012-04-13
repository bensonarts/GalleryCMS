<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL; ?>
<feed>
  <?php if ( ! empty($feed)): ?>
  <title><![CDATA[<?php echo $feed->name; ?>]]></title>
  <albums>
    <?php foreach ($feed->albums as $album): ?>
    <album>
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
    </album>
    <?php endforeach; ?>
  </albums>
  <?php endif; ?>
</feed>