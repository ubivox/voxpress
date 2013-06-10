<?php

$opts_keys = array("uvx_account_url","uvx_api_url", "uvx_api_username", "uvx_api_password");
$opts = array();

$saved = false;

foreach ($opts_keys as $key) {
    if (isset($_POST["_save"]) && isset($_POST[$key])) {
        $value = $_POST[$key];
        update_option($key, $value);
        $saved = true;
    } else {
        $value = get_option($key);
    }
    $opts[$key] = $value;
}

$bools_keys = array("uvx_modal_links");
$bools = array();

foreach ($bools_keys as $key) {
    if (isset($_POST["_save"])) {
        $value = isset($_POST[$key]);
        update_option($key, $value);
        $saved = true;
    } else {
        $value = get_option($key);
    }
    $bools[$key] = $value ? " checked" : "";
}

?>

<div class="wrap">

<h2><?php echo __("Voxpress Settings", "voxpress") ?></h2>

<?php if ($saved) { ?>
<div class="updated"><p><strong><?php echo __("Options Saved", "voxpress") ?></strong></p></div>
<?php } ?>
 
<form method="post">

<h3><?php echo __("Ubivox", "voxpress") ?></h3>

<table class="form-table">

<tr valign="top"><th style="width:300px"><label for="uvx_api_url"><?php echo __("Ubivox Account URL", "voxpress") ?></label></th><td><input type="text" id="uvx_account_url" name="uvx_account_url" class="regular-text" value="<?php echo esc_attr($opts["uvx_account_url"]); ?>" size="100"></td></tr>
<tr valign="top"><th><label for="uvx_api_url"><?php echo __("Ubivox API URL", "voxpress") ?></label></th><td><input type="text" id="uvx_api_url" name="uvx_api_url" class="regular-text" value="<?php echo esc_attr($opts["uvx_api_url"]); ?>" size="100"></td></tr>
<tr valign="top"><th><label for="uvx_api_username"><?php echo __("Ubivox API Username", "voxpress") ?></label></th><td><input type="text" id="uvx_api_username" name="uvx_api_username" class="regular-text" value="<?php echo esc_attr($opts["uvx_api_username"]); ?>" size="15"></td></tr>
<tr valign="top"><th><label for="uvx_api_password"><?php echo __("Ubivox API Password", "voxpress") ?></label></th><td><input type="text" id="uvx_api_password" name="uvx_api_password" class="regular-text" value="<?php echo esc_attr($opts["uvx_api_password"]); ?>" size="30"></td></tr>

<tr valign="top">
<th>&nbsp;</th>
<td><a href="https://docs.ubivox.com/guide/finding-your-api-info/" target="_blank"><?php echo __("View guide to find your API credentials", "voxpress") ?></a></td>
</tr>

</table>

<br><br>

<p class="submit" style="padding-left:310px">
<input type="submit" class="button-primary" name="_save" value="<?php echo __("Save Changes", "voxpress") ?>" />
</p>

</form>

<?php

if ($opts["uvx_api_url"]) {

    $api = new UbivoxAPI();

    try {
        $response = $api->call("ubivox.ping");
    } catch (UbivoxAPIUnauthorized $e) {
        $error = "Unauthorized";
    } catch (UbivoxAPIConnectionError $e) {
        $error = "Connection error";
    } catch (UbivoxAPINotFound $e) {
        $error = "Unknown Ubivox account or wrong API URL";
    } catch (UbivoxAPIError $e) {
        $error = $e->getMessage();
    } catch (UbivoxAPIException $e) {
        $error = $e->getMessage();
    }

?>

<hr />

<h3><?php echo __("Connection test", "voxpress") ?></h3>

<p>

<?php if ($response) { ?>

<strong style="color: darkgreen;">OK</strong>

<?php

$start = microtime(true);
echo " - ".count($api->call("ubivox.get_maillists"))." list(s) available";
$delta = microtime(true) - $start;

printf(" <em>(%.4fs response time)</em>", $delta);

} else {

?>

<strong style="color: darkred;"><?php echo __("Connection test", "voxpress") ?><?php echo __("ERROR", "voxpress") ?></strong>

<?php
if ($error) {
    echo " - ".esc_html($error);
}

}

?>

</p>

<form method="post">
<input type="submit" name="Submit" class="button-secondary" value="Retry" />
</form>

<?php } ?>

</div>
