<?php

$saved = false;

if (isset($_POST["_save"])) {
    update_option("ubivox_ecommerce_maillist_id", intval($_POST["maillist_id"]));
    update_option("ubivox_ecommerce_target_id", intval($_POST["target_id"]));
    update_option("ubivox_ecommerce_subscribe_initial", intval($_POST["subscribe_initial"]));
    update_option("ubivox_ecommerce_subscribe_label", $_POST["subscribe_label"]);
    $saved = true;
}

try {
    $api = new UbivoxAPI();
    $maillists = $api->call("ubivox.list_maillists");
    $targets = $api->call("ubivox.list_targets");
} catch (UbivoxAPIException $e) {
    echo "Missing API settings.";
    return;
}

$maillist_id = get_option("ubivox_ecommerce_maillist_id", 0);
$target_id = get_option("ubivox_ecommerce_target_id", 0);
$subscribe_label = get_option("ubivox_ecommerce_subscribe_label", "Subscribe to our newsletter?");
$subscribe_initial = get_option("ubivox_ecommerce_subscribe_initial", 0);

?>

<div class="wrap">

<h2><?php echo __("E-commerce Integration", "voxpress") ?></h2>

<?php if ($saved) { ?>
<div class="updated"><p><strong><?php echo __("Options Saved", "voxpress") ?></strong></p></div>
<?php } ?>

<form method="post">

<h3><?php echo __("Checkout subscription", "voxpress") ?></h3>

<table class="form-table">

<tr valign="top">
  <th>
    <label for="id_maillist_id"><?php echo __("Add checkbox for", "voxpress") ?>:</label>
  </th>
  <td>
    <select id="id_maillist_id" name="maillist_id" style="width: 400px;">
    <option value="0"<?php echo $maillist_id == 0 ? " selected" : ""?>>- <?php echo __("Do not use", "voxpress") ?> -</option>
      <?php foreach ($maillists as $maillist): ?>
      <option value="<?php echo esc_attr($maillist["id"]); ?>"<?php echo $maillist_id == $maillist["id"] ? " selected" : ""?>><?php echo esc_html($maillist["title"]); ?></option>
      <?php endforeach; ?>
      </select>
  </td>
</tr>
<tr valign="top">
  <th>
    <label for="id_subscribe_label"><?php echo __("Checkbox label", "voxpress") ?>:</label>
  </th>
  <td>
    <input type="text" id="id_subscribe_label" name="subscribe_label" value="<?php echo esc_attr($subscribe_label); ?>" style="width: 400px;">
  </td>
</tr>
<tr valign="top">
  <th>
    <label><?php echo __("Initial state", "voxpress") ?>:</label>
  </th>
  <td>
    <input type="radio" id="id_subscribe_initial_checked" name="subscribe_initial" value="0"<?php echo $subscribe_initial == 0 ? " checked" : ""; ?>>&nbsp;<label for="id_subscribe_initial_checked"><?php echo __("Unhecked", "voxpress") ?></label><br>
    <input type="radio" id="id_subscribe_initial_unchecked" name="subscribe_initial" value="1"<?php echo $subscribe_initial == 1 ? " checked" : ""; ?>>&nbsp;<label for="id_subscribe_initial_unchecked"><?php echo __("Checked", "voxpress") ?></label>

  </td>
</tr>
</table>

<h3><?php echo __("Sales tracking", "voxpress") ?></h3>

<table class="form-table">
<tr valign="top">
  <th>
    <label for="id_target_id"><?php echo __("Add sales tracking target", "voxpress") ?>:</label>
  </th>
  <td>
    <select id="id_target_id" name="target_id" style="width: 400px;">
    <option value="0"<?php echo $target_id == 0 ? " selected" : ""?>>- <?php echo __("Do not use", "voxpress") ?> -</option>
      <?php foreach ($targets as $target): ?>
      <option value="<?php echo esc_attr($target["id"]); ?>"<?php echo $target_id == $target["id"] ? " selected" : ""?>><?php echo esc_html($target["title"]); ?></option>
      <?php endforeach; ?>
      </select>
  </td>
</tr>

</table>

<p class="submit">
<input type="submit" class="button-primary" name="_save" value="Save Changes" />
</p>

</form>

<hr />

<h3><?php echo __("Supported e-commerce platforms", "voxpress") ?></h3>

<table style="border: 1px solid #ddd;">
  <tr>
    <th style="border-right: 1px solid #ddd; padding: 10px;"><?php echo __("WooCommerce", "voxpress") ?></th>
    <th style="padding: 10px;"><?php echo UBIVOX_HAS_WOOCOMMERCE ? "<span style='color: darkgreen'>Detected</span>" : "<span style='color: grey'>Not found</span>"; ?></th>
  </tr>
</table>

</div>
