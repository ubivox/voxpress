jQuery(function() {

    jQuery("form.ubivox_subscription").submit(function (event) {

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

        jQuery.post(UbivoxAjax.ajaxurl, params, function(response) {
            if (response["status"] == "ok") {
                var $success_text = $form.next();
                $form.hide();
                $success_text.show();
            } else {
                alert(response["message"]);
            }
        });

    });

    return;


});
