<?php
/*
Plugin Name: Voxpress
Plugin URI: http://bitbucket.org/ubivox/voxpress
Description: Integrates your Wordpress with Ubivox
Version: 0.1
Author: Ubivox Developers
Author URI: https://www.ubivox.com
License: GPL2
*/

###############################################################################
# API Client
###############################################################################

class UbivoxAPIException extends Exception { }
class UbivoxAPIUnavailable extends UbivoxAPIException { }
class UbivoxAPIError extends UbivoxAPIException { }
class UbivoxAPIUnauthorized extends UbivoxAPIException { }
class UbivoxAPIConnectionError extends UbivoxAPIException { }
class UbivoxAPINotFound extends UbivoxAPIException { }

class UbivoxAPI {

    function __construct($autoload=false) {

        $this->methods = array();

        if ($autoload) {
            $resp = $this->call("system.listMethods", null);
            $this->methods = $resp;
        }
    }

    public function call($method, $params = null) {

        $auth = get_option("uvx_api_username").":".
            get_option("uvx_api_password");

        $post = xmlrpc_encode_request($method, $params);

        $c = curl_init(get_option("uvx_api_url"));

        curl_setopt($c, CURLOPT_USERAGENT, "Voxpress 0.1");
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, $auth); 
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_POSTFIELDS, $post);
        curl_setopt($c, CURLOPT_HEADER, true);

        $http_response = curl_exec($c);
        
        $info = curl_getinfo($c);

        list($header, $data) = explode("\r\n\r\n", $http_response, 2);

        if ($info["http_code"] == 200) {

            $response = xmlrpc_decode($data);

            if (is_array($response) && xmlrpc_is_fault($response)) {
                throw new UbivoxAPIError($response["faultString"], $response["faultCode"]);
            }
            
            return $response;

        }

        if ($info["http_code"] == 401) {
            throw new UbivoxAPIUnauthorized();
        }

        if ($info["http_code"] == 503) {
            throw new UbivoxAPIUnavailable();
        }

        if ($info["http_code"] == 404) {
            throw new UbivoxAPINotFound();
        }

        if ($info["http_code"] == 0) {
            throw new UbivoxAPIConnectionError();
        }

        throw new UbivoxAPIException($header);

    }
}

###############################################################################
# Settings page
###############################################################################

add_action("admin_menu", "uvx_setup_menu");

function uvx_setup_menu() {
    add_options_page(
        "Ubivox Options", 
        "Ubivox", 
        "manage_options", 
        "voxpress-options", 
        "uvx_options_page");
}

function uvx_options_page() {

    if (!current_user_can("manage_options")) {
        wp_die(__(
            "You do not have sufficient permissions to access this page."
        ));
    }

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

    echo '<div class="wrap">';

    echo '<h2>Ubivox Options</h2>';

    if ($saved) {
        echo '<div class="updated"><p><strong>Options Saved</strong></p></div>';
    }

    echo '<form method="post">';

    echo '<table class="form-table">';

    echo '<tr valign="top"><th><label for="uvx_api_url">Ubivox API URL</label></th><td><input type="text" id="uvx_api_url" name="uvx_api_url" class="regular-text" value="'.esc_attr($opts["uvx_api_url"]).'" size="100"></td></tr>';
    echo '<tr valign="top"><th><label for="uvx_api_username">Ubivox API Username</label></th><td><input type="text" id="uvx_api_username" name="uvx_api_username" class="regular-text" value="'.esc_attr($opts["uvx_api_username"]).'" size="15"></td></tr>';
    echo '<tr valign="top"><th><label for="uvx_api_password">Ubivox API Password</label></th><td><input type="text" id="uvx_api_password" name="uvx_api_password" class="regular-text" value="'.esc_attr($opts["uvx_api_password"]).'" size="30"></td></tr>';

    echo '</table>';

    echo '<p class="submit">';
    echo '<input type="submit" class="button-primary" name="_save" value="Save Changes" />';
    echo '</p>';

    echo '</form>';
    
    if ($opts["uvx_api_url"]) {

        echo '<hr />';

        echo '<h3>Connection test</h3>';

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

        echo '<p>';

        if ($response) {

            echo '<strong style="color: darkgreen;">OK</strong>';

            $start = microtime(true);
            echo ' - '.count($api->call("ubivox.get_maillists")).' list(s) available';
            $delta = microtime(true) - $start;

            printf(' <em>(%.4fs response time)</em>', $delta);

        } else {

            echo '<strong style="color: darkred;">ERROR</strong>';

            if ($error) {
                echo ' - '.$error;
            }

        }

        echo '</p>';

        echo '<form method="post">';
        echo '<input type="submit" name="Submit" class="button-secondary" value="Retry" />';
        echo '</form>';

    }

    echo '</div>';

}

###############################################################################
# Widget
###############################################################################

define(
    UBIVOX_WIDGET_DESCRIPTION, 
    "Get new subscriptions for your lists in Ubivox"
);

class Ubivox_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            "ubivox_widget", // Base ID
            "Ubivox Subscription", // Name
            array("description" => UBIVOX_WIDGET_DESCRIPTION,)
        );
    }

    public function widget( $args, $instance ) {

        echo $args["before_widget"];

        echo '<form method="POST" class="ubivox_subscription">';

        echo '<p>';
        echo '<label for="'.$this->get_field_id("email_address").'">E-mail</label><br>';
        echo '<input type="text" name="email_address" id="'.$this->get_field_id("email_address").'" value="">';
        echo '</p>';

        echo '<p>';
        echo '<button class="ubivox_signup">'.$instance["button_text"].'</button>';
        echo '</p>';

        echo '</form>';

        echo $args["after_widget"];
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance["list_id"] = intval($new_instance["list_id"]);
        $instance["button_text"] = strip_tags($new_instance["button_text"]);
        return $instance;
    }

    public function form( $instance ) {

        $api = new UbivoxAPI();
        $lists = $api->call("ubivox.get_maillists");
        $first_list = $lists[0];
            
        if (isset($instance["list_id"])) {
            $list_id = $instance["list_id"];
        } else {
            $list_id = $first_list["id"];
        }

        if (isset($instance["button_text"])) {
            $button_text = $instance["button_text"];
        } else {
            $title = __("Subscribe", "ubivox");
        }

        echo '<p>';
        echo '<label for="'.$this->get_field_id("list_id").'">List:</label>';
        echo '<select class="widefat" style="width:100%;" id="'.$this->get_field_id('list_id').'" name="'.$this->get_field_name('list_id').'">';
        
        foreach ($lists as $l) {
            echo '<option value="'.esc_attr($l["id"]).'"';
            if ($l["id"] == $list_id) {
                echo ' selected';
            }
            echo '>'.esc_html($l["title"]).'</option>';
        }

        echo '</select></p>';

        echo '<p>';
        echo '<label for="'.$this->get_field_id("button_text").'">Button text:</label>';
        echo '<input type="text" class="widefat" id="'.$this->get_field_id('button_text').'" name="'.$this->get_field_name('button_text').'" value="'.esc_attr($button_text).'">';
        echo '</p>';

    }

}

add_action("widgets_init", create_function("", "register_widget('Ubivox_Widget');"));

?>