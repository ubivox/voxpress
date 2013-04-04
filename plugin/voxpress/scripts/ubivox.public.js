
// Namespace
var uvx = {

    // Initialize plugin
    init: function(){

        // Hook subscription form to Ajax
        jQuery("form.uvx_subscription").submit(uvx.subscribe);

        // Hook unsubscription form to Ajax
        jQuery("form.uvx_unsubscription").submit(uvx.unsubscribe);

        // Hook inline links
        uvx.inline_links.init();

        // Make archive links popup inline
        jQuery('a.uvx_archive_link').click(function(event) {
            var $me = jQuery(this);

            uvx.modal.open({
                url: this.href,
                width: 800,
                height: jQuery(window).height() - 50
            });

            event.preventDefault();

        });

        // setup subscription widgets
        jQuery('.widget_ubivox_subscription_widget').each(function(){
            uvx.widget.subscription.init(this);
        });

    },

    widget: {

        subscription: {

            init: function(widget){

                var $widget = jQuery(widget);
                var settings = $widget.find('form.ubivox_subscription').data('ubivox');

                // Set classes
                $widget.addClass('uvx_placement_' + settings.placement);
                $widget.addClass('uvx_effect_' + settings.effect);
                $widget.wrapInner('<div class="uvx_padding"></div>');

                // Set styles

                    // Border width & Color
                    if (settings.border_size != 0) {
                        $widget.css('border', settings.border_size + "px solid " + settings.border_color);
                        $widget.addClass('uvx_border');                        
                    };

                    // Border radius
                    if (settings.border_radius != 0) {
                        $widget.css('border-radius', settings.border_radius);
                        $widget.addClass('uvx_border_rounded');
                    };

                    // Shadow class
                    if (settings.shadow == "yes" ) {                        
                        $widget.addClass('uvx_shadow');                        
                    };

                    // Background Color
                    $widget.css('background', settings.background_color);

                // Handle placement position if not inline
                if (settings.placement != "inline") {                

                    // Set position
                    switch(true)
                    {
                        case (settings.placement == 'top' ):
                            var my = "center top";
                            var at = "center top";
                            var direction = "up";
                        break;

                        case (settings.placement == 'right' ):
                            var my = "right center";
                            var at = "right center";
                            var direction = "right";
                        break;

                        case (settings.placement == 'bottom' ):
                            var my = "center bottom";
                            var at = "center bottom";
                            var direction = "down";
                        break;

                        case (settings.placement == 'left' ):
                            var my = "left center";
                            var at = "left center";
                            var direction = "left";
                        break;

                        case (settings.placement == 'center' ):
                            var my = "center center";
                            var at = "center center";
                            var direction = "up";
                        break;

                    }

                    $widget.data('ubivox_placement', {my: my, at: at, direction: direction});
                
                    // Do initial placement
                    jQuery(window).resize();
                
                };

                if (settings.block_text != "") {
                    // Attach close button
                    var $btn_close = jQuery('<a href="#" class="uvx_dont_show">'+ settings.block_text +'</a>');
                    $widget.find('.ubivox_signup_button').after($btn_close);

                    $btn_close.click(function(event) {
                        uvx.widget.subscription.close(widget);
                        event.preventDefault();
                    });

                };

                // Show widget after selected delay
                setTimeout(function() {
                    uvx.widget.subscription.show(widget);
                }, settings.delay * 1000);


            },

            show: function(widget){

                var $widget = jQuery(widget);
                var settings = $widget.find('form.ubivox_subscription').data('ubivox');
                var placement = $widget.data('ubivox_placement');

                // Attach and activate overlay
                if (settings.overlay_opacity != 0 && settings.placement != 'inline') {

                    var $overlay = jQuery('<div class="uvx_overlay"></div>');

                    $overlay.css({
                        background: settings.overlay_color
                    });

                    jQuery('body').append($overlay);

                    jQuery(window).bind('resize', function(){
                        $overlay.css({
                            height: jQuery(document).outerHeight()
                        })
                    });

                    jQuery(window).resize();
                };


                if (settings.placement != "inline") {
                    // Activate position on scroll and resize
                    jQuery(window).bind('resize', function(){
                        $widget.position({
                            my: placement.my,
                            at: placement.at,
                            of: jQuery(window)
                        })
                    });

                    jQuery(window).resize();                    
                };


                // Choose effect
                switch(settings.effect)
                {
                    case("fade"):

                        // Overlay
                        if ($overlay != null) {
                            $overlay.fadeTo(800, settings.overlay_opacity);
                        };

                        // Show widget
                        $widget.fadeTo(800, 1);

                    break;

                    case("slide"):

                        if (typeof(placement) == 'undefined') {
                            var placement = {
                                direction : "up"
                            };
                        };

                        // Overlay
                        if ($overlay != null) {
                            $overlay.fadeTo(800, settings.overlay_opacity);                            
                        };

                        $widget.css({
                            opacity: 1,
                            display: "block",
                            visibility: "hidden"
                        });

                        jQuery(window).resize();

                        $widget.css({
                            display: "none",
                            visibility: "visible"
                        });

                        // Show widget
                        $widget.show('drop',{
                            direction: placement.direction,
                            distance: 60
                        }, 800);

                    break;

                    default:

                        // Overlay
                        if ($overlay != null) {
                            $overlay.css('opacity', settings.overlay_opacity);
                        };

                        // Show widget
                        $widget.show();
                    

                }


            },

            close: function(widget){

                var $widget = jQuery(widget);

                $widget.fadeTo(800, 0);

                // Remove overlay
                jQuery('.uvx_overlay').fadeTo(800, 0, function(){
                    jQuery(this).remove();
                });



            }
        }


    },

    // INLINE SIGNUP LINKS
    inline_links: {

        init: function(){

            jQuery('a[href*="'+ uvx_settings.account_url +'"]').each(function(){
                var $me = jQuery(this)

                $me.click(function(event) {
                    uvx.modal.open({
                        url: $me.attr('href'),
                        width: 600,
                        height: 400
                    });
                    event.preventDefault();
                });
            });

        }

    },

    
    popup: {

        init: function(){

            // Get popups
            uvx.popup.$widgets = jQuery('.widget_uvx_popup_widget');
            var $widgets = uvx.popup.$widgets;

            $widgets.each(uvx.popup.show);
            
        },

        show: function(i, element){

            var $element = jQuery(element);

            $element.effect({
                effect: 'slide',
                direction: 'up'
            }, 2000);

        },

        hide: function(){

        }

    },


    // POPUP HELPER: show content in lightbox
    modal: {

        open: function(options){

            var defaults = {
                url: "about:blank",
                height: 500,
                width: 700,
                $overlay: jQuery('<div id="uvx_overlay"></div>'),
                $popup: jQuery('<div id="uvx_popup"><iframe src="about:blank" frameborder="0" scrolling="auto" allowtransparency="true" width="100%" height="100%"></iframe></div>'),
                $btn_close: jQuery('<div id="uvx_popup_close">x</div>'),
                $window: jQuery(window),
                window_padding: 50
            }            
            var options = jQuery.extend(defaults, options);
            
            // Set url
            options.$popup.find('iframe').attr('src', options.url);

            // Append elements
            jQuery('body').append(options.$overlay).append(options.$popup);

            options.$btn_close.click(uvx.modal.close).appendTo(options.$popup);
        
            // Attached resize and scroll position
            options.$window.bind('resize scroll', function(){
                uvx.modal.position(options);
            })

            // Trigger initial position
            jQuery(window).resize();

            options.$popup.add(options.$overlay).fadeTo(800, 1)

        },

        close: function(event){
            var $popup = jQuery('#uvx_popup');
            var $overlay = jQuery('#uvx_overlay');

            $popup.add($overlay).fadeOut(800, function(){
                $popup.add($overlay).remove();
                jQuery(window).unbind('resize, scroll');
            });
        },

        position: function(options){

            options.$overlay.height(jQuery(document).height());

            if (options.$window.width() < options.width) {
                options.$popup.width(options.$window.width() - options.window_padding);
            } else {
                options.$popup.width(options.width);
            };

            if (options.$window.height() < options.height) {
                options.$popup.height(options.$window.height() - options.window_padding);
            } else {
                options.$popup.height(options.height);
            };

            options.$popup.position({
                my: "center",
                at: "center",
                of: window
            });
        }

    },

    // Handle ajax subscription
    subscribe: function(){

        event.preventDefault();

        var $form = jQuery(this);

        if ($form.find(".uvx_signup_button").attr("disabled")) {
            return;
        }

        $form.find(".uvx_signup_button").attr("disabled", true);

        var params = {
            action: "uvx_subscribe",
            email_address: $form.find("input[name=email_address]").val(),
            list_id: $form.find("input[name=list_id]").val(),
        };

        var settings = {};

        $form.find(".uvx_input").each(function () {

            var $p = jQuery(this);
            var $input = $p.find("input");

            if ($p.hasClass("uvx_text")) {
                settings[$input.attr("name")] = $input.val();
                return;
            }

            if ($p.hasClass("uvx_textarea")) {
                var $textarea = $p.find("textarea");
                settings[$textarea.attr("name")] = $textarea.val();
                return;
            }

            if ($p.hasClass("uvx_checkbox") && $p.find("input:checked").length > 0) {
                settings[$input.attr("name")] = "True";
                return;
            }

            if ($p.hasClass("uvx_select")) {
                var $select = $p.find("select");
                settings[$select.attr("name")] = $select.val();
                return;
            }

            if ($p.hasClass("uvx_select_multiple")) {
                var $select = $p.find("select");
                settings[$select.attr("name")] = $select.val().join(",");
                return;
            }

            if ($p.hasClass("uvx_select_radio")) {
                var $input = $p.find("input:checked");
                settings[$input.attr("name")] = $input.val();
                return;
            }

            if ($p.hasClass("uvx_select_checkbox")) {

                var checked = []

                $p.find("input:checked").each(function (i, e) {
                    checked.push(jQuery(e).val());
                });

                settings[$input.attr("name")] = checked.join(",");

                return;
            }

        });

        params["settings"] = JSON.stringify(settings);

        jQuery.post(uvx_settings.ajaxurl, params, function(response) {
            if (response["status"] == "ok") {
                var $success_text = $form.next();
                $form.hide();
                $success_text.show();
            } else {
                alert(response["message"]);
            }
        });

    },

    // Handle ajax unsubscription
    ubsubscribe: function(){

        event.preventDefault();

        var $form = jQuery(this);

        if ($form.find(".uvx_signout_button").attr("disabled")) {
            return;
        }

        $form.find(".uvx_signout_button").attr("disabled", true);

        var params = {
            action: "uvx_unsubscribe",
            email_address: $form.find("input[name=email_address]").val(),
            list_id: $form.find("input[name=list_id]").val(),
        };

        jQuery.post(uvx_settings.ajaxurl, params, function(response) {
            if (response["status"] == "ok") {
                var $success_text = $form.next();
                $form.hide();
                $success_text.show();
            } else {
                alert(response["message"]);
            }
        });

    }


}

// Initialize plugin
jQuery(function() {
    uvx.init();
});
