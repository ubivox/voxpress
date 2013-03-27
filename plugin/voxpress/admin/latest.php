<div class="voxpress wrap">

  <div id="icon-edit-pages" class="icon32"><br></div>

  <h2><?php echo __('Latest Published Newsletters','voxpress') ?></h2>

  <br>


<?php

$api = new UbivoxAPI();

try {
    $status = $api->call("ubivox.account_status");
} catch (UbivoxAPIException $e) {
    echo "Could not contact API: ". $e->getMessage();
    return;
}

?>


    <table class="widefat">
      <thead>
        <th>Newsletter</th>
        <th>Sent</th>
        <th class="right">Views</th>
        <th class="right">Clicks</th>
        <th class="right">&nbsp;</th>
      </thead>

      <?php foreach ($status["sent"] as $d): ?>

      <tr>

        <td>
          <div><strong><?php echo esc_html($d["subject"]); ?></strong></a></div>
          <div><small><a href="<?php echo esc_attr($d["list_url"]); ?>" target="_blank"><?php echo esc_html($d["list_title"]); ?></a></small></div>
        </td>

        <td><?php echo date("Y-m-d H:i:s", strtotime($d["send_time"])); ?></td>

        <td class="right">
          <?php echo esc_html($d["views"]); ?>
        </td>

        <td class="right"><?php echo esc_html($d["clicks"]); ?></td>

        <td class="right" style="white-space: nowrap">
          <a href="<?php echo esc_attr($d["url"]); ?>" class="button-secondary" target="_blank">Details</a>
          <a href="<?php echo esc_attr($d["url"]); ?>" class="button-secondary" target="_blank">Preview</a>
        </td>

      </tr>

      <?php endforeach; ?>


    </table>


