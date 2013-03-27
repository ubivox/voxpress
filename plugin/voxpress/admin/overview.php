<div class="wrap">

  <h2>Ubivox</h2>

<?php

$api = new UbivoxAPI();

try {
    $status = $api->call("ubivox.account_status");
} catch (UbivoxAPIException $e) {
    echo "Could not contact API: ". $e->getMessage();
    return;
}

?>

  <h3>Lists</h3>

  <p>
    <ul>

      <?php foreach ($status["lists"] as $ml): ?>

      <li>
        <a href="<?php echo esc_attr($ml["url"]); ?>" target="_blank"><strong><?php echo esc_html($ml["title"]); ?></strong></a>
        
        <table>
          <tr>
            <th>Date</th>
            <th>New subscriptions</th>
            <th>Unsubscribed</th>
            <th>Suspended</th>
            <th>Growth</th>
            <th>Total active</th>
          </tr>

          <?php foreach ($ml["stats"] as $day): ?>
          <tr>
            <td><?php echo date("Y-m-d", strtotime($day["date"])); ?></td>
            <td><?php echo intval($day["new_subscriptions"]); ?></td>
            <td><?php echo intval($day["unsubscribed_removed"]); ?></td>
            <td><?php echo intval($day["suspended"]); ?></td>
            <td><?php echo intval($day["growth"]); ?></td>
            <td><?php echo intval($day["active_total"]); ?></td>
          </tr>
          <?php endforeach; ?>
        </table>

      </li>

      <?php endforeach; ?>

    </ul>
  </p>

  <hr>

  <h3>Drafts</h3>

  <p>
    <ul>

      <?php foreach ($status["drafts"] as $d): ?>

      <li>
        <a href="<?php echo esc_attr($d["url"]); ?>" target="_blank"><strong><?php echo esc_html($d["subject"]); ?></strong></a><br>
        On <a href="<?php echo esc_attr($d["list_url"]); ?>" target="_blank"><?php echo esc_html($d["list_title"]); ?></a>, 
        updated: <?php echo date("Y-m-d H:i:s", strtotime($d["edit_time"])); ?>
      </li>

      <?php endforeach; ?>

    </ul>
  </p>

  <hr>

  <h3>Sent</h3>

  <p>
    <ul>

      <?php foreach ($status["sent"] as $d): ?>

      <li>
        <a href="<?php echo esc_attr($d["url"]); ?>" target="_blank"><strong><?php echo esc_html($d["subject"]); ?></strong></a><br>
        On <a href="<?php echo esc_attr($d["list_url"]); ?>" target="_blank"><?php echo esc_html($d["list_title"]); ?></a>, 
        sent: <?php echo date("Y-m-d H:i:s", strtotime($d["send_time"])); ?><br>
        Views: <?php echo esc_html($d["views"]); ?><br>
        Clicks: <?php echo esc_html($d["clicks"]); ?>
      </li>

      <?php endforeach; ?>

    </ul>
  </p>

</div>
