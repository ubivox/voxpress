<?php

$opts_keys = array("uvx_api_url", "uvx_api_username", "uvx_api_password");
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

?>

<div class="wrap">

<h2>Ubivox Options</h2>

<?php if ($saved) { ?>
<div class="updated"><p><strong>Options Saved</strong></p></div>
<?php } ?>
 
<form method="post">

<table class="form-table">

<tr valign="top"><th><label for="uvx_api_url">Ubivox API URL</label></th><td><input type="text" id="uvx_api_url" name="uvx_api_url" class="regular-text" value="<?php echo esc_attr($opts["uvx_api_url"]); ?>" size="100"></td></tr>
<tr valign="top"><th><label for="uvx_api_username">Ubivox API Username</label></th><td><input type="text" id="uvx_api_username" name="uvx_api_username" class="regular-text" value="<?php echo esc_attr($opts["uvx_api_username"]); ?>" size="15"></td></tr>
<tr valign="top"><th><label for="uvx_api_password">Ubivox API Password</label></th><td><input type="text" id="uvx_api_password" name="uvx_api_password" class="regular-text" value="<?php echo esc_attr($opts["uvx_api_password"]); ?>" size="30"></td></tr>

</table>

<p class="submit">
<input type="submit" class="button-primary" name="_save" value="Save Changes" />
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

<h3>Connection test</h3>

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

<strong style="color: darkred;">ERROR</strong>

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
