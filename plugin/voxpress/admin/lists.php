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

            <h2><?php echo esc_html($ml["title"]); ?> <a href="<?php echo esc_attr($ml["url"]); ?>" class="button-primary" target="_blank">Go to list</a></h2>
            
            <table class="widefat">
              <thead>
                <th>Date</th>
                <th class="right">Subscribed</th>
                <th class="right">Unsubscribed</th>
                <th class="right">Suspended</th>
                <th class="right">Growth</th>
                <th class="right">Total on list</th>
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
  