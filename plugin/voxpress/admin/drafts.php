<div class="voxpress wrap">

  <div id="icon-edit-pages" class="icon32"><br></div>

  <h2><?php echo __('Drafts','voxpress') ?>
    <a href="https://<?php echo get_option("uvx_account_url"); ?>/admin/delivery/create/" target="_blank" class="button-primary"><?php echo __("Create new newsletter", "voxpress") ?></a>
  </h2>

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
      <th><?php echo __("Last edited", "voxpress") ?></th>
      <th>&nbsp;</th>
    </thead>

  <?php foreach ($status["drafts"] as $d): ?>

  <tr>
    <td valign="center">
      <div><strong><?php echo esc_html($d["subject"]); ?></strong></div>
      <div><small><a href="<?php echo esc_attr($d["list_url"]); ?>" target="_blank"><?php echo esc_html($d["list_title"]); ?></a></small></div>
    </td>
    <td valign="center">
      <?php echo date("Y-m-d H:i:s", strtotime($d["edit_time"])); ?>
    </td>
    <td class="right" style="white-space: nowrap">
      <a href="<?php echo esc_attr($d["url"]); ?>" target="_blank" class="button-secondary"><?php echo __("Edit newsletter", "voxpress") ?></a>
    </td>
  </tr>

  <?php endforeach; ?>

</table>
