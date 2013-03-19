<?php

global $wpdb;

$dbkeys = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT(meta_key) FROM $wpdb->usermeta"));
$keys = array();

foreach ($dbkeys as $key) {
    if ($key[0] == "_") continue;
    array_push($keys, $key);
}

try {
    $api = new UbivoxAPI();
    $data_fields = $api->call("ubivox.list_data_fields_details");
} catch (UbivoxAPIException $e) {
    echo "Missing API settings.";
    return;
}

$mapping = get_option("ubivox_data_field_mapping");

if ($mapping === false) {
    $mapping = array();
}

if (isset($_POST["_save"])) {

    $mapping = array();

    foreach ($data_fields as $field) {
        $key = "data_field_".$field["id"];
        if (isset($_POST[$key]) and !empty($_POST[$key])) {
            if (in_array($_POST[$key], $keys)) {
                $mapping[$field["key"]] = $_POST[$key];
            }
        }
    }

    update_option("ubivox_data_field_mapping", $mapping);

}

?>

<div class="wrap">

<h2>WooCommerce Integration</h2>

<form method="post">

<h3>Ubivox data field / Wordpress user profile mapping</h3>

<table class="form-table">

<?php foreach ($data_fields as $field): ?>

<tr valign="top">
  <th>
    <label for="id_data_field_<?php echo $field["id"]; ?>">Map "<?php echo esc_html($field["title"]); ?>" (<?php echo esc_html($field["key"]); ?>) to</label>
  </th>
  <td>
    <select id="id_data_field_<?php echo $field["id"]; ?>" name="data_field_<?php echo $field["id"]; ?>">
    <option value="">- Do not set -</option>
      <?php foreach ($keys as $key): ?>
      <option value="<?php echo esc_attr($key); ?>"<?php echo isset($mapping[$field["key"]]) && $mapping[$field["key"]] == $key ? " selected" : ""?>><?php echo esc_html($key); ?></option>
      <?php endforeach; ?>
      </select>
  </td>
</tr>

<?php endforeach; ?>

</table>

<p class="submit">
<input type="submit" class="button-primary" name="_save" value="Save Changes" />
</p>

</form>

</div>
