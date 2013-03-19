<?php

global $wpdb;

$dbkeys = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT(meta_key) FROM $wpdb->postmeta"));
$keys = array();

foreach ($dbkeys as $key) {
    array_push($keys, $key);
}

?>

<div class="wrap">

<h2>WooCommerce Integration</h2>

<form method="post">

<?php var_dump($keys) ?>

</form>

</div>
