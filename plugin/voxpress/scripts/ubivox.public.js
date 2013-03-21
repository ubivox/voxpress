
// Namespace
var ubivox = {

    // Initialize plugin
    init: function(){

        // Hook subscription form to Ajax
        jQuery("form.ubivox_subscription").submit(ubivox.subscribe);

        // Hook unsubscription form to Ajax
        jQuery("form.ubivox_unsubscription").submit(ubivox.unsubscribe);

        // Hook inline links
        ubivox.inline_links.init();

        // Make archive links popup inline
        jQuery('a.ubivox_archive_link').click(function(event) {
            var $me = jQuery(this);

            ubivox.modal.open({
                url: this.href,
                width: 800,
                height: jQuery(window).height() - 50
            });

            event.preventDefault();

        });

        // setup subscription widgets
        jQuery('.widget_ubivox_subscription_widget').each(function(){
            ubivox.widget.subscription.init(this);
        });

    },

    widget: {

        subscription: {

            init: function(widget){

                var $widget = jQuery(widget);
                var data = $widget.find('form.ubivox_subscription').data('ubivox');

                $widget.css({
                    background: data.bg,
                    opacity: data.bg_opacity
                })

                switch(true)
                {
                    // Create blocker effect
                    case (data.effect == "blocker"):

                    break

                    // Create slide effect
                    case (data.effect.indexOf('slide') != -1):

                    var direction = data.effect.substring(6, data.effect.length);
                    
                    $widget.addClass('slide ' + direction);


                    switch(direction)
                    {
                        case ("top"):
                        jQuery(window).bind('resize scroll', function(){
                            $widget.position({
                                my: "center top",
                                at: "center top",
                                of: jQuery(window)
                            })
                        });
                        break

                        case ("right"):
                        jQuery(window).bind('resize scroll', function(){
                            $widget.position({
                                my: "right center",
                                at: "right center",
                                of: jQuery(window)
                            })
                        });
                        break

                        case ("bottom"):
                        jQuery(window).bind('resize scroll', function(){
                            $widget.position({
                                my: "center bottom",
                                at: "center bottom",
                                of: jQuery(window)
                            })
                        });
                        break

                        case ("left"):
                        jQuery(window).bind('resize scroll', function(){
                            $widget.position({
                                my: "left center",
                                at: "left center",
                                of: jQuery(window)
                            })
                        });
                        break

                    }


                    jQuery(window).resize();


                    break

                    // Present inline if no effext
                    default: 

                    $widget.show();

                }

            }            
        }


    },

    // INLINE SIGNUP LINKS
    inline_links: {

        init: function(){

            jQuery('a[href*="'+ ubivox_settings.account_url +'"]').each(function(){
                var $me = jQuery(this)

                $me.click(function(event) {
                    ubivox.modal.open({
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
            ubivox.popup.$widgets = jQuery('.widget_ubivox_popup_widget');
            var $widgets = ubivox.popup.$widgets;

            $widgets.each(ubivox.popup.show);
            
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
                $overlay: jQuery('<div id="ubivox_overlay"></div>'),
                $popup: jQuery('<div id="ubivox_popup"><iframe src="about:blank" frameborder="0" scrolling="auto" allowtransparency="true" width="100%" height="100%"></iframe></div>'),
                $btn_close: jQuery('<div id="ubivox_popup_close">x</div>'),
                $window: jQuery(window),
                window_padding: 50
            }            
            var options = jQuery.extend(defaults, options);
            
            // Set url
            options.$popup.find('iframe').attr('src', options.url);

            // Append elements
            jQuery('body').append(options.$overlay).append(options.$popup);

            options.$btn_close.click(ubivox.modal.close).appendTo(options.$popup);
        
            // Attached resize and scroll position
            options.$window.bind('resize scroll', function(){
                ubivox.modal.position(options);
            })

            // Trigger initial position
            jQuery(window).resize();

            options.$popup.add(options.$overlay).fadeTo(800, 1)

        },

        close: function(event){
            var $popup = jQuery('#ubivox_popup');
            var $overlay = jQuery('#ubivox_overlay');

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

        if ($form.find(".ubivox_signup_button").attr("disabled")) {
            return;
        }

        $form.find(".ubivox_signup_button").attr("disabled", true);

        var params = {
            action: "ubivox_subscribe",
            email_address: $form.find("input[name=email_address]").val(),
            list_id: $form.find("input[name=list_id]").val(),
        };

        var data = {};

        $form.find(".ubivox_input").each(function () {

            var $p = jQuery(this);
            var $input = $p.find("input");

            if ($p.hasClass("ubivox_text")) {
                data[$input.attr("name")] = $input.val();
                return;
            }

            if ($p.hasClass("ubivox_textarea")) {
                var $textarea = $p.find("textarea");
                data[$textarea.attr("name")] = $textarea.val();
                return;
            }

            if ($p.hasClass("ubivox_checkbox") && $p.find("input:checked").length > 0) {
                data[$input.attr("name")] = "True";
                return;
            }

            if ($p.hasClass("ubivox_select")) {
                var $select = $p.find("select");
                data[$select.attr("name")] = $select.val();
                return;
            }

            if ($p.hasClass("ubivox_select_multiple")) {
                var $select = $p.find("select");
                data[$select.attr("name")] = $select.val().join(",");
                return;
            }

            if ($p.hasClass("ubivox_select_radio")) {
                var $input = $p.find("input:checked");
                data[$input.attr("name")] = $input.val();
                return;
            }

            if ($p.hasClass("ubivox_select_checkbox")) {

                var checked = []

                $p.find("input:checked").each(function (i, e) {
                    checked.push(jQuery(e).val());
                });

                data[$input.attr("name")] = checked.join(",");

                return;
            }

        });

        params["data"] = JSON.stringify(data);

        jQuery.post(ubivox_settings.ajaxurl, params, function(response) {
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

        if ($form.find(".ubivox_signout_button").attr("disabled")) {
            return;
        }

        $form.find(".ubivox_signout_button").attr("disabled", true);

        var params = {
            action: "ubivox_unsubscribe",
            email_address: $form.find("input[name=email_address]").val(),
            list_id: $form.find("input[name=list_id]").val(),
        };

        jQuery.post(ubivox_settings.ajaxurl, params, function(response) {
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
    ubivox.init();
});
