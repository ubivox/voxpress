<?php

$saved = false;

if (isset($_POST["_save"])) {
    update_option("ubivox_ecommerce_maillist_id", intval($_POST["maillist_id"]));
    update_option("ubivox_ecommerce_target_id", intval($_POST["target_id"]));
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

?>

<div class="wrap">

<h2>E-Commerce Integration</h2>

<?php if ($saved) { ?>
<div class="updated"><p><strong>Options Saved</strong></p></div>
<?php } ?>

<form method="post">

<h3>Checkout subscription</h3>

<table class="form-table">

<tr valign="top">
  <th>
    <label for="id_maillist_id">Add checkbox for:</label>
  </th>
  <td>
    <select id="id_maillist_id" name="maillist_id">
    <option value="0"<?php echo $maillist_id == 0 ? " selected" : ""?>>- Do not use -</option>
      <?php foreach ($maillists as $maillist): ?>
      <option value="<?php echo esc_attr($maillist["id"]); ?>"<?php echo $maillist_id == $maillist["id"] ? " selected" : ""?>><?php echo esc_html($maillist["title"]); ?></option>
      <?php endforeach; ?>
      </select>
  </td>
</tr>
<tr valign="top">
  <th>
    <label for="id_target_id">Add sales tracking target:</label>
  </th>
  <td>
    <select id="id_target_id" name="target_id">
    <option value="0"<?php echo $target_id == 0 ? " selected" : ""?>>- Do not use -</option>
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

</div>
