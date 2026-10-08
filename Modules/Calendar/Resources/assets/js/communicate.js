 $(document).ready(function() {
        $("#CommunicationStaffsByRole").on("change", function() {
            $("#checkbox").prop("checked", false);
            var url = $("#url").val();
            console.log($(this).val());

            var formData = {
                id: $(this).val(),
            };
            // console.log(formData);
            // $("#staticPagesInput").select2("val", "");
            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "communicate/studStaffByRole",
                success: function(data) {
                    // console.log(data);
                    var a = "";

                        $.each(data, function(i, item) {
                            if (item.length) {
                                $("#staticPagesInput").find("option").remove();
                                $("#selectStaffsDiv ul").find("li").not(":first").remove();

                                $.each(item, function(i, staffs) {
                                    // console.log(staffs);
                                    $("#staticPagesInput").append(
                                        $("<option>", {
                                            value: staffs.name +
                                                "-" +
                                                staffs.email +
                                                "-" +
                                                staffs.phone,
                                            text: staffs.name,
                                        })
                                    );
                                });
                            } else {
                                $("#selectStaffsDiv .current").html("SELECT *");
                                $("#staticPagesInput").find("option").not(":first").remove();
                                $("#selectStaffsDiv ul").find("li").not(":first").remove();
                            }
                        });

                    console.log(a);
                },
                error: function(data) {
                    console.log("Error:", data);
                },
            });
        });


   // sms gateway submit form twilio
    $('form[id="twilio_form"]').validate({
        rules: {
            twilio_account_sid: "required",
            twilio_authentication_token: "required",
            twilio_registered_no: "required",
        },
        messages: {
            twilio_account_sid: "This field is required",
            twilio_authentication_token: "This field is required",
            twilio_registered_no: "This field is required",
        },
        submitHandler: function(form) {
            // form.submit(event);
            //event.preventDefault();
            form_data = $("#twilio_form").serialize();
            updateTwilioData = $("#twilio_form_url").val();
            url = $("#url").val();
            var twilio_account_sid = $("#twilio_account_sid").val();
            $(".invalid-feedback").remove();
            if (twilio_account_sid.length < 1) {
                alert(twilio_account_sid);
                $("#twilio_account_sid").after(
                    '<span class="invalid-feedback" role="alert"><strong>This field is Required</strong></span>'
                );
            }
            $.ajax({
                type: "POST",
                data: form_data,
                url: url + "/communicate/" + updateTwilioData,
                success: function(data) {
                    if (data == 1) {
                        toastr.success(
                            "Twilio Data has been updated successfully",
                            "Successful", {
                                timeOut: 5000,
                            }
                        );
                    } else {
                        toastr.error("You Got Error", "Inconceivable!", {
                            timeOut: 5000,
                        });
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {},
            });
        },
    });

    // sms gateway submit form msg91
    $('form[id="msg91_form"]').validate({
        rules: {
            msg91_authentication_key_sid: "required",
            msg91_sender_id: "required",
            msg91_route: "required",
            msg91_country_code: "required",
        },
        messages: {
            msg91_authentication_key_sid: "This field is required",
            msg91_sender_id: "This field is required",
            msg91_route: "This field is required",
            msg91_country_code: "This field is required",
        },
        submitHandler: function(form) {
            // form.submit(event);
            //event.preventDefault();
            form_data = $("#msg91_form").serialize();
            updateMsg91Data = $("#msg91_form_url").val();
            url = $("#url").val();
            $.ajax({
                type: "POST",
                data: form_data,
                url: url + "/communicate/" + updateMsg91Data,
                success: function(data) {
                    console.log(data);
                    if (data == "success") {
                        toastr.success(
                            "Msg91 Data has been updated successfully",
                            "Successful", {
                                timeOut: 5000,
                            }
                        );
                    } else {
                        toastr.error("You Got Error", "Inconceivable!", {
                            timeOut: 5000,
                        });
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {},
            });
        },
    });

    // select a service
    $("#sms_service").on("change", function(e) {
        e.preventDefault();
        sms_service = $("#sms_service").val();
        url = $("#url").val();
        // console.log(sms_service);
        $.ajax({
            type: "get",
            data: {
                sms_service: sms_service,
            },
            url: url + "/communicate/activeSmsService",
            success: function(data) {
                // console.log('This is response : '+data);
                if (data == "success") {
                    toastr.success("This Service is Active Now", "Successful", {
                        timeOut: 5000,
                    });
                } else {
                    toastr.error("You Got Error", "Inconceivable!", {
                        timeOut: 5000,
                    });
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {},
        });
    });



    });