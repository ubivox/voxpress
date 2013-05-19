<div class="voxpress wrap">

  <div id="icon-edit-pages" class="icon32"><br></div><h2><?php echo __('Lists','voxpress') ?></h2>


<?php

$api = new UbivoxAPI();

try {
    $status = $api->call("ubivox.account_status");
} catch (UbivoxAPIException $e) {
    echo "Could not contact API: ". $e->getMessage();
    return;
}

?>


          <?php foreach ($status["lists"] as $ml): ?>

            <h2><?php echo esc_html($ml["title"]); ?> <a href="<?php echo esc_attr($ml["url"]); ?>" class="button-primary" target="_blank"><?php echo __("Go to list in Ubivox", "voxpress") ?></a></h2>
            
            <table class="widefat">
              <thead>
                <th><?php echo __("Date") ?></th>
                <th class="right"><?php echo __("Subscribed", "voxpress") ?></th>
                <th class="right"><?php echo __("Unsubscribed", "voxpress") ?></th>
                <th class="right"><?php echo __("Suspended", "voxpress") ?></th>
                <th class="right"><?php echo __("Growth", "voxpress") ?></th>
                <th class="right"><?php echo __("Total on list", "voxpress") ?></th>
              </thead>

              <?php foreach ($ml["stats"] as $day): ?>
              <tr>
                <td><?php echo date("Y-m-d", strtotime($day["date"])); ?></td>
                <td class="right"><?php echo intval($day["new_subscriptions"]); ?></td>
                <td class="right"><?php echo intval($day["unsubscribed_removed"]); ?></td>
                <td class="right"><?php echo intval($day["suspended"]); ?></td>
                <td class="right"><?php echo intval($day["growth"]); ?></td>
                <td class="right"><?php echo intval($day["active_total"]); ?></td>
              </tr>
              <?php endforeach; ?>
            </table>

            <br>

          <?php endforeach; ?>
  