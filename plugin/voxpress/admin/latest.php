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
        <th><?php echo __("Newsletter", "voxpress") ?></th>
        <th class="center"><?php echo __("Sent", "voxpress") ?></th>
        <th class="right"><?php echo __("Recipients", "voxpress") ?></th>
        <th class="right"><?php echo __("Successfull", "voxpress") ?></th>
        <th class="right"><?php echo __("Views", "voxpress") ?></th>
        <th class="right"><?php echo __("Clicks", "voxpress") ?></th>
        <th class="right">&nbsp;</th>
      </thead>

      <?php foreach ($status["sent"] as $d): ?>

      <tr>

        <td>
          <div><strong><?php echo esc_html($d["subject"]); ?></strong></a></div>
          <div><small><a href="<?php echo esc_attr($d["list_url"]); ?>" target="_blank"><?php echo esc_html($d["list_title"]); ?></a></small></div>
        </td>

        <td class="center"><?php echo date("Y-m-d H:i:s", strtotime($d["send_time"])); ?></td>

        <td class="right">
          <?php echo esc_html($d["recipients"]); ?>
        </td>

        <td class="right">
          <?php echo esc_html($d["delivered"]); ?>
          <div style="font-size: 10px"><?php echo number_format((esc_html($d["delivered"]) / esc_html($d["recipients"]) * 100), 1); ?>%</div>
        </td>

        <td class="right">
          <?php echo esc_html($d["views"]); ?> 
          <div style="font-size: 10px"><?php echo number_format((esc_html($d["views"]) / esc_html($d["delivered"]) * 100), 1); ?>%</div>
        </td>

        <td class="right">
          <?php echo esc_html($d["clicks"]); ?>
          <div style="font-size: 10px"><?php echo number_format((esc_html($d["clicks"]) / esc_html($d["views"]) * 100), 1); ?>%</div>
        </td>

        <td class="right" style="white-space: nowrap">
          <a href="<?php echo esc_attr($d["url"]); ?>" class="button-secondary" target="_blank"><?php echo __("Details", "voxpress") ?></a>
        </td>

      </tr>

      <?php endforeach; ?>


    </table>


