<?php

class Ubivox_Popup_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            "ubivox_popup_widget", // Base ID
            "Ubivox Popup", // Name
            array("description" => "Show a promotion for your newsletter on a page.")
        );
    }

    public function widget( $args, $instance ) {

        echo $args["before_widget"];

        $title = apply_filters("widget_title", $instance["title"]);

        if (!empty($title)) {
            echo $args["before_title"].$title.$args["after_title"];
        }

        echo '<a href="'. get_option("uvx_account_url") .'/forms/subscribe/list/69'.intval($instance["list_id"]).'/">';

        echo '<button class="ubivox_view_signup_button">'.$instance["button_text"].'</button>';

        echo $args["after_widget"];
    }

    public function update( $new_instance, $old_instance ) {

        $instance = array();

        $instance["list_id"] = intval($new_instance["list_id"]);
        $instance["button_text"] = strip_tags($new_instance["button_text"]);
        $instance["title"] = strip_tags($new_instance["title"]);
        $instance["text"] = $new_instance["success_text"];
        $instance["position"] = $new_instance["position"];
        $instance["background"] = $new_instance["background"];
        $instance["background_opacity"] = $new_instance["background_opacity"];

        return $instance;
    }

    public function form( $instance ) {

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

        if (isset($instance["button_text"])) {
            $button_text = $instance["button_text"];
        } else {
            $button_text = __("Subscribe", "ubivox");
        }

        if (isset($instance["title"])) {
            $title = $instance["title"];
        } else {
            $title = __("Newsletter", "ubivox");
        }

        if (isset($instance["position"])) {
            $position = $instance["position"];
        } else {
            $position = "bottom";
        }

        if (isset($instance["background"])) {
            $background = $instance["background"];
        } else {
            $background = "#000000";
        }

        if (isset($instance["background_opacity"])) {
            $background_opacity = $instance["background_opacity"];
        } else {
            $background_opacity = "30";
        }

        # Title
        echo '<p>';
        echo '<label for="'.$this->get_field_id("title").'">Title:</label>';
        echo '<input type="text" class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.esc_attr($title).'">';
        echo '</p>';

        # List to show signup for
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

        # Text / HTML
        echo '<p>';
        echo '<label for="'.$this->get_field_id("text").'">Text / HTML:</label>';
        echo '<textarea class="widefat" id="'.$this->get_field_id('text').'" name="'.$this->get_field_name('text').'">'.esc_html($success_text).'</textarea>';
        echo '</p>';

        # Action button
        echo '<p>';
        echo '<label for="'.$this->get_field_id("button_text").'">Button text:</label>';
        echo '<input type="text" class="widefat" id="'.$this->get_field_id('button_text').'" name="'.$this->get_field_name('button_text').'" value="'.esc_attr($button_text).'">';
        echo '</p>';

        ## Settings

        echo '<h3 style="margin-top: 30px">'. __("Settings") .'</h3>';

        # Position
        echo '<p>';
        echo '<label for="'.$this->get_field_id("position").'">Position:</label>';
        echo '<select class="widefat" style="width:100%;" id="'.$this->get_field_id('position').'" name="'.$this->get_field_name('position').'">';
        
        foreach ($lists as $l) {
            echo '<option value="'.esc_attr($l["id"]).'"';
            if ($l["id"] == $list_id) {
                echo ' selected';
            }
            echo '>'.esc_html($l["title"]).'</option>';
        }
        
        echo '<option value="top">'. __('Top')  .'</option>';
        echo '<option value="right">'. __('Right')  .'</option>';
        echo '<option value="bottom">'. __('Bottom')  .'</option>';
        echo '<option value="left">'. __('Left')  .'</option>';

        echo '</select></p>';

        ?>

        <script type="text/javascript">
            //<![CDATA[
                jQuery(document).ready(function()
                {
                    // colorpicker field
                    jQuery('.cw-color-picker').each(function(){
                        var $this = jQuery(this),
                            id = $this.attr('rel');
                        $this.farbtastic('#' + id);
                    });
        
                    jQuery('#widgets-right .ubivox-background-opacity-slider').each(function(){
                        
                        var $me = jQuery(this);
                        var $input = jQuery('#' + $me.attr('rel'));
                        var $counter = jQuery('#' + $me.attr('rel') + '_counter');

                        console.log($input)

                        $me.slider({
                            min: 1,
                            max: 100,
                            value: $input.val(),
                            slide: function( event, ui ) {
                                $input.val(ui.value);
                                $counter.html(ui.value);
                            }
                        });

                    });

                });
            //]]>   
          </script>

        <?php 

        # Background color
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("background").'">Background Color:</label>';
        echo '<div class="cw-color-picker center" rel="' .$this->get_field_id('background') .'"></div>';
        echo '<input type="text" class="widefat" id="'.$this->get_field_id('background').'" name="'.$this->get_field_name('background').'" value="'.esc_attr($background).'">';
        echo '</div>';

        # Background opacity
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("background_opacity").'">Background Opacity:</label>';
        echo '<input type="hidden" class="widefat" id="'.$this->get_field_id('background_opacity').'" name="'.$this->get_field_name('background_opacity').'" value="'.esc_attr($background_opacity).'">';
        echo '<div class="ubivox-background-opacity-slider-container">';
        echo '<div id="'.$this->get_field_id('background_opacity').'_counter" class="ubivox-background-opacity-counter">'.esc_attr($background_opacity).'</div>';
        echo '<div id="'.$this->get_field_id('background_opacity').'_slider" class="ubivox-background-opacity-slider" rel="'.$this->get_field_id('background_opacity').'"></div>';
        echo '</div>';
        echo '</div>';


    }

}

function widget_scripts() {
    wp_enqueue_script('farbtastic');
    wp_enqueue_script('jquery-ui-slider');
}
function widget_styles() {
    wp_enqueue_style('farbtastic'); 
    wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
}

add_action('admin_print_scripts-widgets.php', 'widget_scripts');
add_action('admin_print_styles-widgets.php', 'widget_styles');

