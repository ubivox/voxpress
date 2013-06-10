<?php

class Ubivox_Subscription_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            "ubivox_subscription_widget", // Base ID
            "Ubivox Subscription", // Name
            array("description" => "Get new subscriptions for your lists in Ubivox")
        );
    }

    public function widget( $args, $instance ) {

        echo $args["before_widget"];

        $title = apply_filters("widget_title", $instance["title"]);

        if (!empty($title)) {
            echo $args["before_title"].$title.$args["after_title"];
        }

        echo '<div class="description">'.esc_html($instance["description"]).'</div>';

        echo '<p class="uvx_success_text" style="display: none;">';
        echo esc_html($instance["success_text"]);
        echo '</p>';

        echo '<form method="POST" class="ubivox_subscription" data-ubivox=\'{        
            "placement":"'.$instance["placement"].'",
            "effect":"'.$instance["effect"].'",
            "background_color":"'.$instance["background_color"].'",
            "text_color":"'.$instance["text_color"].'",
            "border_color":"'.$instance["border_color"].'",
            "border_size":"'.$instance["border_size"].'",
            "border_radius": '.$instance["border_radius"].',
            "width": "'.$instance["width"].'",
            "padding": "'.$instance["padding"].'",
            "shadow": "'.$instance["shadow"].'",
            "overlay_color":"'.$instance["overlay_color"].'",
            "overlay_opacity":"'. (intval($instance["overlay_opacity"]) / 100) .'",
            "cookie_life":"'.$instance["cookie_life"].'",
            "delay":"'.$instance["delay"].'"
        }\'>';

        echo '<input type="hidden" name="list_id" class="uvx_list_id" value="'.intval($instance["list_id"]).'">';

        echo '<div class="uvx_form_fields">';

        echo '<p>';
        echo '<label for="'.$this->get_field_id("email_address").'">E-mail:</label><br>';
        echo '<input type="text" name="email_address" id="'.$this->get_field_id("email_address").'" value="">';
        echo '</p>';

        foreach ($instance["data_meta"] as $field) {

            if (!in_array($field["key"], $instance["data"])) {
                continue;
            }

            echo '<p class="uvx_input uvx_'.$field["datatype"].'">';

            switch ($field["datatype"]) {

            case "textarea":

                echo '<label for="'.$this->get_field_id("data_".$field["key"]).'">'.esc_html($field["title"]).':</label><br>';
                echo '<textarea name="'.esc_attr($field["key"]).'" id="'.$this->get_field_id("data_".$field["key"]).'" style="width: 100%; height: 100px;"></textarea>';

                break;

            case "checkbox":

                echo '<label for="'.$this->get_field_id("data_".$field["key"]).'">'.esc_html($field["title"]).':</label><br>';
                echo '<input type="checkbox" name="'.esc_attr($field["key"]).'" id="'.$this->get_field_id("data_".$field["key"]).'" value="1">';

                break;

            case "select_multiple":
            case "select":

                if ($field["datatype"] == "select_multiple") {
                    $multiple = " multiple";
                } else {
                    $multiple = "";
                }

                echo '<label for="'.$this->get_field_id("data_".$field["key"]).'">'.esc_html($field["title"]).':</label><br>';
                echo '<select name="'.esc_attr($field["key"]).'" id="'.$this->get_field_id("data_".$field["key"]).'" style="width: 100%;"'.$multiple.'>';

                foreach ($field["choices"] as $choice) {
                    list($key, $value) = $choice;
                    echo '<option value="'.esc_attr($key).'">'.esc_html($value).'</option>';
                }

                echo '</select>';

                break;

            case "select_radio":

                echo '<label>'.esc_html($field["title"]).':</label><br>';

                foreach ($field["choices"] as $choice) {

                    list($key, $value) = $choice;
                    echo '<input type="radio" name="'.esc_attr($field["key"]).'" id="'.$this->get_field_id("data_".$field["key"]."_".$key).'" value="'.esc_attr($key).'">&nbsp;';
                    echo '<label for="'.$this->get_field_id("data_".$field["key"]."_".$key).'">'.esc_html($value).'</label><br>';
                }

                break;

            case "select_checkbox":

                echo '<label>'.esc_html($field["title"]).':</label><br>';

                foreach ($field["choices"] as $choice) {

                    list($key, $value) = $choice;
                    echo '<input type="checkbox" name="'.esc_attr($field["key"]).'" id="'.$this->get_field_id("data_".$field["key"]."_".$key).'" value="'.esc_attr($key).'">&nbsp;';
                    echo '<label for="'.$this->get_field_id("data_".$field["key"]."_".$key).'">'.esc_html($value).'</label><br>';
                }

                break;

            default:

                echo '<label for="'.$this->get_field_id("data_".$field["key"]).'">'.esc_html($field["title"]).':</label><br>';
                echo '<input type="text" name="'.esc_attr($field["key"]).'" id="'.$this->get_field_id("data_".$field["key"]).'" value="">';

            }

            echo '</p>';

        }

        echo '</div>';

        echo '<div class="uvx_button_row">';
        echo '<button class="uvx_signup_button">'.$instance["button_text"].'</button>';

        if ($instance["block_text"]) {
            echo '<a href="#" class="uvx_link_block">'. $instance["block_text"] .'</a>';
        };

        echo '</div>';

        echo '</form>';

        echo $args["after_widget"];
    }

    public function update( $new_instance, $old_instance ) {

        $instance = array();

        $instance["description"] = strip_tags($new_instance["description"]);
        $instance["list_id"] = intval($new_instance["list_id"]);
        $instance["title"] = strip_tags($new_instance["title"]);
        $instance["block_text"] = strip_tags($new_instance["block_text"]);
        $instance["button_text"] = strip_tags($new_instance["button_text"]);
        $instance["success_text"] = strip_tags($new_instance["success_text"]);
        $instance["placement"] = $new_instance["placement"];
        $instance["effect"] = $new_instance["effect"];
        $instance["width"] = $new_instance["width"];
        $instance["padding"] = $new_instance["padding"];
        $instance["border_radius"] = $new_instance["border_radius"];
        $instance["background_color"] = $new_instance["background_color"];        
        $instance["text_color"] = $new_instance["text_color"];
        $instance["border_color"] = $new_instance["border_color"];
        $instance["border_size"] = intval($new_instance["border_size"]);
        $instance["shadow"] = $new_instance["shadow"];
        $instance["overlay_color"] = $new_instance["overlay_color"];
        $instance["overlay_opacity"] = intval($new_instance["overlay_opacity"]);
        $instance["delay"] = intval($new_instance["delay"]);
        $instance["cookie_life"] = intval($new_instance["cookie_life"]);


        try {
            $api = new UbivoxAPI();
            $data_fields = $api->call("ubivox.list_data_fields_details");
        } catch (UbivoxAPIException $e) {
            return $old_instance;
        }

        $data = Array();

        foreach ($data_fields as $field) {
            if (isset($new_instance["data_".$field["id"]])) {
                $data[] = $field["key"];
            }
        }

        $instance["data"] = $data;
        $instance["data_meta"] = $data_fields;

        return $instance;
    }

    public function form( $instance ) {

        if (isset($instance["description"])) {
            $description = $instance["description"];
        } else {
            $description = __("Join our mailing list to keep up with news and interesting stories", "voxpress");
        }

        try {

            $api = new UbivoxAPI();

            $lists = $api->call("ubivox.get_maillists");
            $data_fields = $api->call("ubivox.list_data_fields_details");

        } catch (UbivoxAPIException $e) {
            echo "<p>Connection problems. See settings for details.</p>";
            return;
        }

        $first_list = $lists[0];
            
        if (isset($instance["list_id"])) {
            $list_id = $instance["list_id"];
        } else {
            $list_id = $first_list["id"];
        }

        if (isset($instance["block_text"])) {
            $block_text = $instance["block_text"];
        } else {
            $block_text = __("Hide this", "voxpress");
        }

        if (isset($instance["button_text"])) {
            $button_text = $instance["button_text"];
        } else {
            $button_text = __("Subscribe", "voxpress");
        }

        if (isset($instance["title"])) {
            $title = $instance["title"];
        } else {
            $title = __("Newsletter", "voxpress");
        }

        if (isset($instance["data"])) {
            $data = $instance["data"];
        } else {
            $data = array();
        }

        if (isset($instance["success_text"])) {
            $success_text = $instance["success_text"];
        } else {
            $success_text = __("Thank you for your subscription. We've send you an e-mail with a confirmation link.", "voxpress");
        }

        if (isset($instance["placement"])) {
            $placement = $instance["placement"];
        } else {
            $placement = "inline";
        }

        if (isset($instance["effect"])) {
            $effect = $instance["effect"];
        } else {
            $effect = "slide";
        }        

        if (isset($instance["border_radius"])) {
            $border_radius = $instance["border_radius"];
        } else {
            $border_radius = "3";
        }        

        if (isset($instance["width"])) {
            $width = $instance["width"];
        } else {
            $width = "400px";
        }        

        if (isset($instance["padding"])) {
            $padding = $instance["padding"];
        } else {
            $padding = "0";
        }        

        if (isset($instance["background_color"])) {
            $background_color = $instance["background_color"];
        } else {
            $background_color = "transparent";
        }

        if (isset($instance["text_color"])) {
            $text_color = $instance["text_color"];
        } else {
            $text_color = "#333333";
        }

        if (isset($instance["border_color"])) {
            $border_color = $instance["border_color"];
        } else {
            $border_color = "#FFFFFF";
        }

        if (isset($instance["border_size"])) {
            $border_size = $instance["border_size"];
        } else {
            $border_size = "0";
        }

        if (isset($instance["shadow"])) {
            $shadow = $instance["shadow"];
        } else {
            $shadow = "no";
        }

        if (isset($instance["overlay_color"])) {
            $overlay_color = $instance["overlay_color"];
        } else {
            $overlay_color = "#000000";
        }

        if (isset($instance["overlay_opacity"])) {
            $overlay_opacity = $instance["overlay_opacity"];
        } else {
            $overlay_opacity = "40";
        }

        if (isset($instance["delay"])) {
            $delay = $instance["delay"];
        } else {
            $delay = "0";
        }

        if (isset($instance["cookie_life"])) {
            $cookie_life = $instance["cookie_life"];
        } else {
            $cookie_life = 30;
        }


        // Title
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("title").'">'. __("Title", "voxpress") .':</label>';
        echo '<input type="text" class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.esc_attr($title).'">';
        echo '</div>';

        // Description
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("description").'">'. __("Description", "voxpress") .':</label>';
        echo '<textarea class="widefat" style="height:100px" id="'.$this->get_field_id('description').'" name="'.$this->get_field_name('description').'">'.esc_html($description).'</textarea>';
        echo '</div>';

        // Select list
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("list_id").'">'. __("List", "voxpress") .':';
        echo '<select class="widefat" style="width:150px;float:right" id="'.$this->get_field_id('list_id').'" name="'.$this->get_field_name('list_id').'">';
        
        foreach ($lists as $l) {
            echo '<option value="'.esc_attr($l["id"]).'"';
            if ($l["id"] == $list_id) {
                echo ' selected';
            }
            echo '>'.esc_html($l["title"]).'</option>';
        }

        echo '</select></label></div>';

        echo '<div class="ubivox-widget-field">';
        echo '<label>Data fields:</label><br>';

        foreach ($data_fields as $field) {

            if (in_array($field["key"], $data)) {
                $checked = " checked";
            } else {
                $checked = "";
            }

            echo '<input class="checkbox" type="checkbox" id="'.$this->get_field_id('data_'.$field["id"]).'" name="'.$this->get_field_name('data_'.$field["id"]).'"'.$checked.'>&nbsp;';
            echo '<label for="'.$this->get_field_id('data_'.$field["id"]).'">'.esc_html($field["title"]).' <em>('.esc_html($field["key"]).')</em></label><br>';
        }

        echo '</div>';

        // Button text
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("button_text").'">'. __("Subscribe button text", "voxpress") .':</label>';
        echo '<input type="text" class="widefat" id="'.$this->get_field_id('button_text').'" name="'.$this->get_field_name('button_text').'" value="'.esc_attr($button_text).'">';
        echo '</div>';

        // Block text
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("block_text").'">'. __("Close button text", "voxpress") .':<div style="font-size:10px">'. __("Hide form until cookie expires (based on list ID).", "voxpress") .'</div></label>';
        echo '<input type="text" class="widefat" id="'.$this->get_field_id('block_text').'" name="'.$this->get_field_name('block_text').'" value="'.esc_attr($block_text).'">';
        echo '<div style="font-size:10px;color:#888">'. __("Doesn't show if text is empty.", "voxpress") .'</div>';
        echo '</div>';

        // Success text
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("success_text").'">'. __("Success text", "voxpress") .':</label>';
        echo '<textarea class="widefat" style="height:100px" id="'.$this->get_field_id('success_text').'" name="'.$this->get_field_name('success_text').'">'.esc_html($success_text).'</textarea>';
        echo '<div style="font-size:10px;color:#888">'. __("Shown when a subscriber has successfully subscribed to the list.", "voxpress") .'</div>';
        echo '</div>';


        ## Memory Settings

        echo '<hr />';
        echo '<h3>'. __("Memory", "voxpress") .'</h3>';

        # Cookie lifetime
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("cookie_life").'">'. __("Cookie lifetime", "voxpress") .': <div style="float:right;margin-left:5px;margin-top:5px">days</div><input type="text" style="width: 45px; float:right; text-align:center" id="'.$this->get_field_id('cookie_life').'" name="'.$this->get_field_name('cookie_life').'" value="'.esc_attr($cookie_life).'"></label>';
        echo '<div style="font-size:10px;color:#888;clear:both;margin-top:10px">'. __("The cookie decides how many days should expire before the same visitor sees the form again, after it has been closed", "voxpress") .'</div>';
        echo '<div style="font-size:10px;color:#888;clear:both;margin-top:10px">'. __("Use 0 to make it a session cookie and show the form again on the next visit after the browser has been closed.", "voxpress") .'</div>';
        echo '<div style="font-size:10px;color:#888;clear:both;margin-top:10px">'. __("Use -1 or lower to ignore any cookies and always show the form, even if previous cookies has been set for the user.", "voxpress") .'</div>';
        echo '</div>';


        ## Design Settings

        echo '<hr />';
        echo '<h3>'. __("Design Settings", "voxpress") .'</h3>';

        echo '<div class="ubivox_design_settings">';

        # Placement
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("placement").'">'. __("Placement", "voxpress") .':';
        echo '<select style="width:90px; float:right" id="'.$this->get_field_id('placement').'" name="'.$this->get_field_name('placement').'">';
        
        $placement_options = array(
            "inline" => __("Inline", "voxpress"),
            "top" => __("Top", "voxpress"),
            "bottom" => __("Bottom", "voxpress"),
            "right" => __("Right", "voxpress"),
            "left" => __("Left", "voxpress"),
            "center" => __("Center", "voxpress")
        );

        foreach ($placement_options as $key => $label) {
            if ($key == $placement) {
                $default = ' selected="selected"';
            } else {
                $default = "";
            }
            echo '<option value="'. $key .'"'.$default.'>'. $label  .'</option>';
        }

        echo '</select></label>';
        echo '</div>';


        # Effect
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("effect").'">'. __("Effect", "voxpress") .':';
        echo '<select style="width:90px; float:right" id="'.$this->get_field_id('effect').'" name="'.$this->get_field_name('effect').'">';
        
        $effect_options = array(
            "slide" => __("Slide", "voxpress"),
            "fade" => __("Fade", "voxpress"),
            "none" => __("None", "voxpress")
        );

        foreach ($effect_options as $key => $label) {
            if ($key == $effect) {
                $default = ' selected="selected"';
            } else {
                $default = "";
            }
            echo '<option value="'. $key .'"'.$default.'>'. $label  .'</option>';
        }

        echo '</select></label>';
        echo '</div>';


        # Delay
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("delay").'">'. __("Delay", "voxpress") .': <div style="float:right;margin-left:5px;margin-top:5px">sek</div><input type="text" style="width: 45px; float:right; text-align:center" id="'.$this->get_field_id('delay').'" name="'.$this->get_field_name('delay').'" value="'.esc_attr($delay).'"></label>';
        echo '</div>';

        # Background color
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("background_color").'">'. __("Background color", "voxpress") .': <input type="text" style="width: 75px; float:right; text-align:center" id="'.$this->get_field_id('background_color').'" name="'.$this->get_field_name('background_color').'" value="'.esc_attr($background_color).'"></label>';
        echo '</div>';

        # Text color
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("text_color").'">'. __("Text color", "voxpress") .': <input type="text" style="width: 75px; float:right; text-align:center" id="'.$this->get_field_id('text_color').'" name="'.$this->get_field_name('text_color').'" value="'.esc_attr($text_color).'"></label>';
        echo '</div>';

        # Border color
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("border_color").'">'. __("Border color", "voxpress") .': <input type="text" style="width: 75px; float:right; text-align:center" id="'.$this->get_field_id('border_color').'" name="'.$this->get_field_name('border_color').'" value="'.esc_attr($border_color).'"></label>';
        echo '</div>';

        # Border size
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("border_size").'">'. __("Border size", "voxpress") .': <div style="float:right;margin-left:5px;margin-top:5px">px</div><input type="text" style="width: 45px; float:right; text-align:center" id="'.$this->get_field_id('border_size').'" name="'.$this->get_field_name('border_size').'" value="'.esc_attr($border_size).'"></label>';
        echo '</div>';

        # Border Radius
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("border_radius").'">'. __("Border radius", "voxpress") .': <div style="float:right;margin-left:5px;margin-top:5px">px</div><input type="text" style="width: 45px; float:right; text-align:center" id="'.$this->get_field_id('border_radius').'" name="'.$this->get_field_name('border_radius').'" value="'.esc_attr($border_radius).'"></label>';
        echo '</div>';

        # Width
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("width").'">'. __("Width (when not inline)", "voxpress") .': <div style="float:right;margin-left:5px;margin-top:5px"></div><input type="text" style="width: 45px; float:right; text-align:center" id="'.$this->get_field_id('width').'" name="'.$this->get_field_name('width').'" value="'.esc_attr($width).'"></label>';
        echo '</div>';

        # Padding
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("padding").'">'. __("Padding", "voxpress") .': <div style="float:right;margin-left:5px;margin-top:5px">px</div><input type="text" style="width: 45px; float:right; text-align:center" id="'.$this->get_field_id('padding').'" name="'.$this->get_field_name('padding').'" value="'.esc_attr($padding).'"></label>';
        echo '</div>';

        # shadow
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("shadow").'">'. __("Use shadow", "voxpress") .':';
        echo '<select style="width:90px; float:right" id="'.$this->get_field_id('shadow').'" name="'.$this->get_field_name('shadow').'">';
        
        $shadow_options = array(
            "yes" => __("Yes", "voxpress"),
            "no" => __("No", "voxpress"),
        );

        foreach ($shadow_options as $key => $label) {
            if ($key == $shadow) {
                $default = ' selected="selected"';
            } else {
                $default = "";
            }
            echo '<option value="'. $key .'"'.$default.'>'. $label  .'</option>';
        }

        echo '</select></label>';
        echo '</div>';

        # Overlay color
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("overlay_color").'">'. __("Overlay color", "voxpress") .': <input type="text" style="width: 75px; float:right; text-align:center" id="'.$this->get_field_id('overlay_color').'" name="'.$this->get_field_name('overlay_color').'" value="'.esc_attr($overlay_color).'"></label>';
        echo '</div>';

        # Overlay opacity        
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("overlay_opacity").'">'. __("Overlay opacity", "voxpress") .': <div style="float:right;margin-left:5px;margin-top:5px">%</div><input type="text" style="width: 45px; float:right; text-align:center" id="'.$this->get_field_id('overlay_opacity').'" name="'.$this->get_field_name('overlay_opacity').'" value="'.esc_attr($overlay_opacity).'"></label>';
        echo '</div>';


        echo '</div>';
        echo '<br />';

    }

}


function widget_scripts() {
    
}
function widget_styles() {
    
}

add_action('admin_print_scripts-widgets.php', 'widget_scripts');
add_action('admin_print_styles-widgets.php', 'widget_styles');

