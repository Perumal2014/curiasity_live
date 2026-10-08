let lang = window._locale;
let baseURL = $("#url").val();
$(document).ready(function(){
    if ($("#category_id").val() != "") {
        $("#category_id").trigger("change");
    }
    if ($("#primary_skill_id").val() != "") {
        $("#primary_skill_id").trigger("change");
    }
    // if($('#business_unit').val() != '') {
    //     alert(this.val());
    //     $('#business_unit').trigger('change');
    //     $("#subDepartmentDiv").removeClass('d-none');
    // }

});


$(document).ready(function () {
    $("#category_id").on("change", function () {
        var url = $("#ajaxSubCategoryUrl").val();
       
        // console.log(url);
       
        var formData = {
            id: $(this).val(),
        };
        console.log(formData);
        // get section for student
        $.ajax({
            type: "GET",
            data: formData,
            dataType: "json",
             url: url,
            success: function (data) {
                // console.log(data);
                var a = "";
                // $.loading.onAjax({img:'loading.gif'});
                var editSubCategory = $("#edit_subcategory_course_id").val(); 
                $.each(data, function (i, item) {
                    if (item.length) {
                        $("#subcategory_id").find("option").not(":first").remove();
                        $("#subCategoryDiv ul").find("li").not(":first").remove();
                       
                        $.each(item, function (i, section) {
                             var selected = section.id == editSubCategory ? true : false;   
                            console.log(section.id);

                            $("#subcategory_id").append(
                                $("<option>", {
                                    value: section.id,
                                    text: section.name[lang],
                                    selected: selected
                                })
                            );

                            $("#subCategoryDiv ul").append(
                                "<li data-value='" +
                                section.id +
                                "' class='option'>" +
                                section.name[lang] +
                                "</li>"
                            );
                        });
                    } else {
                        $("#subCategoryDiv .current").html("Select Sub Category");
                        $("#subcategory_id").find("option").not(":first").remove();
                        $("#subCategoryDiv ul").find("li").not(":first").remove();
                    }
                });
                $('#subcategory_id').niceSelect('update');
                // console.log(a);
            },
            error: function (data) {
                console.log("Error:", data);
            },
        });
    });

    $("#subcategory_id").on("change", function () {
        var url = $("#url").val();

        var formData = {
            category_id: $('#category_id').val(),
            subcategory_id: $(this).val(),
        };

        $.ajax({
            type: "GET",
            data: formData,
            dataType: "json",
            url: url + "/" + "ajaxGetCourseList",
            success: function (data) {
                $.each(data, function (i, item) {
                    if (item.length) {
                         if(editSubCategory == section.id){
                            selected = "selected";
                        }
                        console.log(item.length);
                        $("#course_id").find("option").not(":first").remove();
                        $("#CourseDiv ul").find("li").not(":first").remove();

                        $.each(item, function (i, course) {
                            $("#course_id").append(
                                $("<option>", {
                                    value: course.id,
                                    text: course.name,
                                })
                            );
                            $("#CourseDiv ul").append("<li data-value='" + course.id + "' class='option'>" + course.title2 + "</li>");
                        });
                    } else {
                        $("#CourseDiv .current").html("Select A Course *");
                        $("#course_id").find("option").not(":first").remove();
                        $("#CourseDiv ul").find("li").not(":first").remove();
                    }
                });
                // console.log(a);
            },
            error: function (data) {
                console.log("Error:", data);
            },
        });
    });
});

$(document).ready(function () {
    $(".edit_category_id").on("change", function () {
        var url = $("#url").val();

        var course_id = $(this).closest('#course').data('course_id');

        var formData = {
            id: $(this).val(),
        };


        $.ajax({
            type: "GET",
            data: formData,
            dataType: "json",
            url: url + "/" + "admin/course/ajaxGetCourseSubCategory",
            success: function (data) {
                // console.log("#edit_subcategory_id"+course_id);
                // console.log("#edit_subCategoryDiv"+course_id+" ul");
                var a = "";
                // $.loading.onAjax({img:'loading.gif'});
                $.each(data, function (i, item) {
                    if (item.length) {
                        $("#edit_subcategory_id" + course_id).find("option").not(":first").remove();
                        $("#edit_subCategoryDiv" + course_id + " ul").find("li").not(":first").remove();

                        $.each(item, function (i, section) {
                            $("#edit_subcategory_id" + course_id).append(
                                $("<option>", {
                                    value: section.id,
                                    text: section.name[lang],
                                })
                            );

                            $("#edit_subCategoryDiv" + course_id + " ul").append(
                                "<li data-value='" +
                                section.id +
                                "' class='option'>" +
                                section.name[lang] +
                                "</li>"
                            );
                        });
                    } else {
                        $("#edit_subCategoryDiv" + course_id + ".current").html("SECTION *");
                        $("#edit_subcategory_id" + course_id).find("option").not(":first").remove();
                        $("#edit_subCategoryDiv" + course_id + " ul").find("li").not(":first").remove();
                    }
                });
                // console.log(a);
            },
            error: function (data) {
                console.log("Error:", data);
            },
        });

    });
});

$(document).ready(function () {
    let discount = $('#addDiscount');
    let discountDiv = $('#discountDiv');
    $('#course_2').change(function () {
        if (this.checked) {
            $('#price_div').fadeOut('slow');
            discountDiv.fadeOut('slow');
            discount.val('');
            $('.booking_amount_field').val(0);
        } else {
            discountDiv.fadeIn('slow');
            discount.val('');
            $('#price_div').fadeIn('slow');
        }
    });

});
$(document).ready(function () {
    $('#course_3').change(function () {
        if (this.checked)
            $('#discount_price_div').fadeIn('slow');
        else
            $('#discount_price_div').fadeOut('slow');
    });
});


$(document).ready(function () {
    let discount = $('.editDiscount');
    let discountDiv = $('.editDiscountDiv');
    $('.edit_course_2').change(function () {
        var course_id = $(this).val();

        if (this.checked) {
            $('#edit_price_div').fadeOut('slow');
            discountDiv.fadeOut('slow');
            // discount.val();
        } else {
            $('#edit_price_div').fadeIn('slow');
            discountDiv.fadeIn('slow');
            // discount.val('');
        }
    });

    $('.edit_course_2').trigger('change');

});
$(document).ready(function () {
    $('.edit_course_3').change(function () {
        if (this.checked)
            $('#edit_discount_price_div').fadeIn('slow');
        else
            $('#edit_discount_price_div').fadeOut('slow');

    });
});
$(document).ready(function () {
    $("#type1").on("click", function () {
        if ($('#type1').is(':checked')) {
            $(".courseBox").show();
            $(".quizBox").hide();
            $(".videoOption").show();
            $("#quiz_id").val('');
            $("#dripCheck").show();
            $("#course_requirements").show();
            $("#course_description").show();    
            $("#access_limit_disp").show();
            $("#primary_skill").show();
            $("#has_recommended_disp").show();
            $("#has_external_disp").show();
            $("#course_type_disp").show();
            $("#feedback_available_disp").show();
            $("#manager_feedback_available_disp").show();
            // $(".makeResize").addClass("col-xl-4");
            // $(".makeResize").removeClass("col-xl-6");
        }
    });

    $("#type2").on("click", function () {
        if ($('#type2').is(':checked')) {
            console.log('checked ty2');
            $(".courseBox").hide();
            $(".quizBox").show();
            $(".videoOption").hide();
            $("#dripCheck").hide();
            $("#course_requirements").show();
            $("#course_description").show();    
            $("#access_limit_disp").hide();
            $("#primary_skill").hide();
            $("#has_recommended_disp").hide();
            $("#has_external_disp").hide();
            $("#course_type_disp").hide();
            $("#feedback_available_disp").hide();
            $("#manager_feedback_available_disp").hide();            
            // $(".makeResize").addClass("col-xl-6");
            // $(".makeResize").removeClass("col-xl-4");
        }
    });

    $(".type1").on("click", function () {
        if ($('.type1').is(':checked')) {
            $(".courseBox").show();
            $(".quizBox").hide();
            $("#quiz_id").val('');
            $(".videoOption").show();
            $(".dripCheck").show();
            $("#course_outcome").show();
            $("#is_free_course").show();
            $("#price_div").show();
            $("#price_text_div").show();
            $("#complete_course").show();
            $("#comp_cour_no").show();
            $("#comp_cour_yes").show();
            $("#discount_price").show();
            $("#app_purchase").show();
            $("#overview_video").show();
            $("#meta_keywords").show();
            $("#meta_desc").show();
            $("#discountDiv").show(); 
            $("#access_limit_disp").show();
            $("#primary_skill").show();
            $("#has_recommended_disp").show();
            $("#has_external_disp").show();
            $("#course_type_disp").show();
            $("#feedback_available_disp").show();
            $("#manager_feedback_available_disp").show();   
            // comp_cour_yes    
            // $(".makeResize").addClass("col-xl-4");
            // $(".makeResize").removeClass("col-xl-6");
        }
    });

    $(".type2").on("click", function () {

        if ($('.type2').is(':checked')) {
            $(".courseBox").hide();
            $(".videoOption").hide();
            $(".quizBox").show();
            $(".dripCheck").hide();
            $("#course_outcome").show();
            $("#is_free_course").show();
            $("#price_div").show();
            $("#price_text_div").show();
            $("#complete_course").show();
            $("#comp_cour_no").show();
            $("#comp_cour_yes").show();
            $("#discount_price").show();
            $("#app_purchase").show();
            $("#overview_video").show();
            $("#meta_keywords").show();
            $("#meta_desc").show();
             $("#discountDiv").show(); 
            /*        $(".makeResize").addClass("col-xl-6");
                    $(".makeResize").removeClass("col-xl-4");*/
        }
    });

     $("#type3").on("click", function () {

        if ($('#type3').is(':checked')) {
            // alert('test');
            $("#dripCheck").hide();
            $("#course_requirements").hide();
            $("#course_description").hide();
            $("#course_outcome").hide();
            $("#is_free_course").hide();    
            $("#price_div").hide();
            $("#price_text_div").hide();
            $("#complete_course").hide();
            $("#comp_cour_no").hide();
            $("#comp_cour_yes").hide();
            $("#discount_price").hide();
            $("#app_purchase").hide();
            $("#overview_video").hide();
            $("#meta_keywords").hide();
            $("#meta_desc").hide();
            $("#discountDiv").hide(); 
            // $(".videoOption").hide();
            // $(".quizBox").show();
            // $(".dripCheck").hide();
            /*        $(".makeResize").addClass("col-xl-6");
                    $(".makeResize").removeClass("col-xl-4");*/
        }
    });

    // $(document).on('change', '.department_div', function () {
    //     let value = $(this).find(":selected").val();
    //     alert(value);
    //     if (value === '0') {
    //         $("#department_div").show();
    //     } else {
    //         $("#department_div").hide();
    //     }
    // });

    $(document).on('change', '#scope', function () {
        let value = $(this).val();
        //alert(value);
        if (value === '0') {
            $("#group_div").removeClass('d-none');
        } else {
            $("#group_div").addClass('d-none');
        }
    });

    $(document).on('change', '#business_unit', function () {
        let value = $(this).val();
        
        if (value != '') {
            $("#subDepartmentDiv").removeClass('d-none');
        } else {
            $("#subDepartmentDiv").addClass('d-none');
        }
    });



    $(document).on('change', '.category_id', function () {
        let category_id = $(this).find(":selected").val();
        if (category_id === 'Youtube' || category_id === 'URL'|| category_id === 'm3u8'|| category_id === 'Custom') {
            $(this).closest('.videoOption').find('.VdoCipherUrl').hide();
            $(this).closest('.videoOption').find('.videoUrl').show();
            $(this).closest('.videoOption').find('.vimeoUrl').hide();
            $(this).closest('.videoOption').find('.videofileupload').hide();
            $(this).closest('.videoOption').find('.vimeoVideo');
            $(this).closest('.videoOption').find('.youtubeVideo');
            $(this).closest('.videoOption').find('.videofileupload');

        } else if (category_id === 'Self' || (category_id === 'AmazonS3')) {
            $(this).closest('.videoOption').find('.VdoCipherUrl').hide();
            $(this).closest('.videoOption').find('.videofileupload').show();
            $(this).closest('.videoOption').find('.videoUrl').hide();
            $(this).closest('.videoOption').find('.vimeoUrl').hide();
            $(this).closest('.videoOption').find('.vimeoVideo');
            $(this).closest('.videoOption').find('.youtubeVideo');
            $(this).closest('.videoOption').find('.videofileupload');

        } else if (category_id === 'Vimeo') {
            $(this).closest('.videoOption').find('.VdoCipherUrl').hide();
            $(this).closest('.videoOption').find('.videofileupload').hide();
            $(this).closest('.videoOption').find('.videoUrl').hide();
            $(this).closest('.videoOption').find('.vimeoUrl').show();
            $(this).closest('.videoOption').find('.vimeoVideo');
            $(this).closest('.videoOption').find('.youtubeVideo');
            $(this).closest('.videoOption').find('.videofileupload');
        } else if (category_id === 'VdoCipher') {
            $(this).closest('.videoOption').find('.videofileupload').hide();
            $(this).closest('.videoOption').find('.videoUrl').hide();
            $(this).closest('.videoOption').find('.vimeoUrl').hide();
            $(this).closest('.videoOption').find('.VdoCipherUrl').show();
            $(this).closest('.videoOption').find('.vimeoVideo');
            $(this).closest('.videoOption').find('.youtubeVideo');
            $(this).closest('.videoOption').find('.videofileupload');
        } else if (category_id === 'BunnyStorage') {
            $(this).closest('.videoOption').find('.VdoCipherUrl').hide();
            $(this).closest('.videoOption').find('.videofileupload').hide();
            $(this).closest('.videoOption').find('.videoUrl').hide();
            $(this).closest('.videoOption').find('.vimeoUrl').hide();
            $(this).closest('.videoOption').find('.bunnyStreamUrl').show();
            $(this).closest('.videoOption').find('.vimeoVideo');
            $(this).closest('.videoOption').find('.youtubeVideo');
            $(this).closest('.videoOption').find('.videofileupload');
        } else {
            $(this).closest('.videoOption').find('.VdoCipherUrl').hide();
            $(this).closest('.videoOption').find('.videofileupload').hide();
            $(this).closest('.videoOption').find('.videoUrl').hide();
            $(this).closest('.videoOption').find('.vimeoUrl').hide();
            $(this).closest('.videoOption').find('.vimeoVideo');
            $(this).closest('.videoOption').find('.youtubeVideo');
            $(this).closest('.videoOption').find('.videofileupload');
        }
    });

     function toggleDeliveryFields(value) {

        $('.showteams').hide();
        $('.showmeet').hide();
        $('.showzoom').hide();

        if (value === 'Teams') {
            $('.showteams').show();
        } 
        else if (value === 'Gmeet') {
            $('.showmeet').show();
        } 
        else if (value === 'Zoom') {
            $('.showzoom').show();
        }
    }

    // On change
    $(document).on('change', '.mode_id', function () {
        toggleDeliveryFields($(this).val());
    });

    // On page load (Edit case)
    toggleDeliveryFields($('.mode_id').val());

    $(document).on('change', '.type', function () {
        let type = $(this).val();
        if (type == 0) {
            $('.single_class').show();
            $('.continuous_class').hide();
        } else {
            $('.single_class').hide();
            $('.continuous_class').show();
        }
    })
    $(document).on('change', '.free_class', function () {
        if ($(this).is(':checked')) {
            $('.fees').hide();
        } else {
            $('.fees').show();
        }
    })

});

function changeType(el) {
    var validity = document.getElementById('show_validity');
    var course = document.getElementById('show_course');
    if (el.value == 1) {
        validity.style.display = 'block';
        course.style.display = 'none';
    } else {
        validity.style.display = 'none';
        course.style.display = 'block';
    }
}


$(document).on('click', '#add_course_btn', function () {
    $('#addTitle').val('');
    $('#addDuration').val('');
    $('#addPrice').val('');
    $('#addMeta').val('');
});

$('input[type=radio][name=host]').change(function () {
    let host = this.value;
    if (host == "Zoom") {
        $('.zoomSetting').show();
        $('.bbbSetting').hide();
        $('.InAppLiveClassSetting').hide();
        $('.jitsiSetting').hide();
    } else if (host == "BBB") {
        $('.zoomSetting').hide();
        $('.bbbSetting').show();
        $('.InAppLiveClassSetting').hide();
        $('.jitsiSetting').hide();
    } else if (host == "Jitsi") {
        $('.zoomSetting').hide();
        $('.bbbSetting').hide();
        $('.InAppLiveClassSetting').hide();
        $('.jitsiSetting').show();
    } else if (host == "InAppLiveClass") {
        $('.zoomSetting').hide();
        $('.bbbSetting').hide();
        $('.jitsiSetting').hide();
        $('.InAppLiveClassSetting').show();
    } else {
        $('.zoomSetting').hide();
        $('.bbbSetting').hide();
        $('.jitsiSetting').hide();
        $('.InAppLiveClassSetting').hide();
    }

    allowCalendarDivToggle(host);
});

allowCalendarDivToggle();

function allowCalendarDivToggle(host = '') {
    let div = $('.allow_google_calendar_div');
    if (host == 'GoogleMeet') {
        div.hide();
    } else {
        div.show()
    }
}

function getVimeoList(obj) {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: baseURL + "/" + "admin/course/get-vimeo-list",
        success: function (data) {


            $.each(data, function (index, value) {
                obj.html('<option value=' + value.uri + '>' + value.name + '</option>');
            });
            
        },
        error: function (data) {
            console.log("Error:", data);
        },
    });
}

$(document).ready(function () {
    $('.vimeo_video_list').each(function (i, obj) {
        getVimeoList(obj);

    });

    $('#course_start_date').datepicker({
        minDate: 0,   // disables all past dates
        dateFormat: 'yy-mm-dd'
    });
});

$(document).ready(function(){

    // hide all
    $(".tab-content-box").hide();

    // default show
    $("#ongoingCoursesContent").show();

    $("#ongoingCoursesTab").click(function(){
        $(".tab-content-box").hide();
        $("#ongoingCoursesContent").show();

        $(".nav-link").removeClass("active");
        $(this).find("a").addClass("active");
    });

    $("#upcomingCoursesTab").click(function(){
        $(".tab-content-box").hide();
        $("#upcomingCoursesContent").show();

        $(".nav-link").removeClass("active");
        $(this).find("a").addClass("active");
    });

    $("#pastCoursesTab").click(function(){
        $(".tab-content-box").hide();
        $("#pastCoursesContent").show();

        $(".nav-link").removeClass("active");
        $(this).find("a").addClass("active");
    });

});

$("#primary_skill_id").on("change", function () {
    var url = $("#ajaxGetSecondarySkillList").val();
        // console.log(url);

    var formData = {
        id: $(this).val(),
    };
    console.log(formData);
    // get section for student
    $.ajax({
        type: "GET",
        data: formData,
        dataType: "json",
        url: url,
        beforeSend: function () {
            $("#secondarySkillLoader").show(); // show loader
        },
        success: function (data) {
            var edit_sec_skill_course_id = $("#edit_sec_skill_course_id").val();

            // convert "python,php,java" → ["python","php","java"]
            var selectedSkills = edit_sec_skill_course_id ? edit_sec_skill_course_id.split(',') : [];

            $("#secondary_skill_id").html('<option value="">Select Secondary Skill</option>');

            $.each(data, function (i, item) {

                $.each(item, function (i, section) {

                    var selected = selectedSkills.includes(section.id.toString()) ? 'selected' : '';

                    $("#secondary_skill_id").append(
                        '<option value="'+section.id+'" '+selected+'>'+section.name+'</option>'
                    );

                });

            });
            // refresh nice select UI
            $('#secondary_skill_id').niceSelect('update');

        },
         complete: function () {
            $("#secondarySkillLoader").hide(); // hide loader
        },

        error: function (data) {
            console.log("Error:", data);
        },
    });
});




$("#business_unit").on("change", function () {

    var url = $("#ajaxGetDepartmentList").val();
    // get saved department ids
    var selectedDepartments = $("#selected_departments").val();
    selectedDepartments = selectedDepartments ? selectedDepartments.split(',') : [];

    $.ajax({
        type: "GET",
        url: url,
        data: {
            id: $(this).val()
        },
        dataType: "json",

        beforeSend: function () {
            $("#secondaryDepartmentLoader").show();
        },

        success: function (data) {

            $("#department_id").empty();

            if(data.length > 0){

                $.each(data,function(i,section){
                    
                    var selected = selectedDepartments.includes(section.id.toString()) ? 'selected' : '';

                    $("#department_id").append(
                        '<option value="'+section.id+'" '+selected+'>'+section.dept_name+'</option>'
                    );

                });

            }

            $('#department_id').niceSelect('update');

        },

        complete:function(){
            $("#secondaryDepartmentLoader").hide();
        }

    });

});


$("#bu_multiple").on("change", function () {

    var url = $("#ajaxGetDepartmentList").val();
    // get saved department ids
    var selectedDepartments = $("#selected_departments").val();
    selectedDepartments = selectedDepartments ? selectedDepartments.split(',') : [];

    $.ajax({
        type: "GET",
        url: url,
        data: {
            id: $(this).val()
        },
        dataType: "json",

        beforeSend: function () {
            $("#secondaryDepartmentLoader").show();
        },

        success: function (data) {

            $("#department_id").empty();

            if(data.length > 0){

                $.each(data,function(i,section){
                    
                    var selected = selectedDepartments.includes(section.id.toString()) ? 'selected' : '';

                    $("#department_id").append(
                        '<option value="'+section.id+'" '+selected+'>'+section.dept_name+'</option>'
                    );

                });

            }

            $('#department_id').niceSelect('update');

        },

        complete:function(){
            $("#secondaryDepartmentLoader").hide();
        }

    });

});

// $("#recommend_for").on("change", function () {

//     var url = $("#ajaxGetBuList").val();
//     // get saved department ids
//     var selectedDepartments = $("#recommend_for").val();
//     selectedDepartments = selectedDepartments ? selectedDepartments.split(',') : [];
//     $("#subRecommendDiv").removeClass('d-none');
//     $.ajax({
//         type: "GET",
//         url: url,
//         data: {
//             id: $(this).val()
//         },
//         dataType: "json",

//         beforeSend: function () {
//             $("#secondaryRecDepartmentLoader").show();
//         },

//         success: function (data) {
//             //    
            
//             $("#recbusiness_id").empty();

//             if(data.length > 0){

//                 $.each(data,function(i,section){
                    
//                     var selected = selectedDepartments.includes(section.id.toString()) ? 'selected' : '';

//                     $("#recbusiness_id").append(
//                         '<option value="'+section.id+'" '+selected+'>'+section.bu_name+'</option>'
//                     );

//                 });

//             }

//             $('#recbusiness_id').niceSelect('update');

//         },

//         complete:function(){
//             $("#secondaryRecDepartmentLoader").hide();
//         }

//     });

// });

// $("#recbusiness_id").on("change", function () {

//     var url = $("#ajaxGetDepartmentList").val();
//     // get saved department ids
//     var selectedDepartments = $("#recommend_for").val();
//     selectedDepartments = selectedDepartments ? selectedDepartments.split(',') : [];
//     $("#subRecommendDeptDiv").removeClass('d-none');
//     $.ajax({
//         type: "GET",
//         url: url,
//         data: {
//             id: $(this).val()
//         },
//         dataType: "json",

//         beforeSend: function () {
//             $("#DepartmentLoader").show();
//         },

//         success: function (data) {
//             //    
            
//             $("#recdepartment_id").empty();

//             if(data.length > 0){

//                 $.each(data,function(i,section){
                    
//                     var selected = selectedDepartments.includes(section.id.toString()) ? 'selected' : '';

//                     $("#recdepartment_id").append(
//                         '<option value="'+section.id+'" '+selected+'>'+section.dept_name+'</option>'
//                     );

//                 });

//             }

//             $('#recdepartment_id').niceSelect('update');

//         },

//         complete:function(){
//             $("#DepartmentLoader").hide();
//         }

//     });

// });


// $("#bu_multiple").on("change", function () {

//     var selectedValues = $(this).val(); // array of selected BU ids

//     // 👉 CONDITION: multiple BU selected → hide department
//     if (selectedValues && selectedValues.length > 1) {
//         $("#basedDepartmentDivView").addClass('d-none');
//         return; // stop further execution
//     } else {
//         $("#basedDepartmentDivView").removeClass('d-none');
//     }

//     var url = $("#ajaxGetDepartmentList").val();
//     var grpType = $("#grp_type_hidden").val();

//     var selectedDepartments = $("#selected_departments").val();
//     selectedDepartments = selectedDepartments ? selectedDepartments.split(',') : [];

//     $.ajax({
//         type: "GET",
//         url: url,
//         data: {
//             id: selectedValues,
//             grp_type: grpType
//         },
//         dataType: "json",

//         beforeSend: function () {
//             $("#DeptLoader").show();
//         },

//         success: function (data) {

//             $("#base_department").empty();

//             if (data.length > 0) {
//                 $.each(data, function (i, section) {

//                     var selected = selectedDepartments.includes(section.id.toString()) ? 'selected' : '';

//                     $("#base_department").append(
//                         '<option value="'+section.id+'" '+selected+'>'+section.dept_name+'</option>'
//                     );

//                 });
//             }

//             $('#base_department').niceSelect('update');
//         },

//         complete:function(){
//             $("#DeptLoader").hide();
//         }
//     });

// });


$("#state_multiple").on("change", function () {

    var selectedValues = $(this).val(); // array of selected BU ids
    
    // 👉 CONDITION: multiple BU selected → hide department
    if (selectedValues && selectedValues.length > 1) {
        
        $("#basedCityDivView").addClass('d-none');
        return; // stop further execution
    } else {
        $("#basedCityDivView").removeClass('d-none');

        var url = $("#ajaxGetOverAllList").val();
        var grpType = 5;

        var selectedDepartments = $("#selected_departments").val();
        selectedDepartments = selectedDepartments ? selectedDepartments.split(',') : [];

        $.ajax({
            type: "GET",
            url: url,
            data: {
                id: selectedValues,
                grp_type: grpType
            },
            dataType: "json",

            beforeSend: function () {
                $("#CityLoader").show();
            },

            success: function (data) {

                $("#city_multiple").empty();

                if (data.length > 0) {
                    $.each(data, function (i, section) {

                        var selected = selectedDepartments.includes(section.id.toString()) ? 'selected' : '';

                        $("#city_multiple").append(
                            '<option value="'+section.id+'" '+selected+'>'+section.city_name+'</option>'
                        );

                    });
                }

                $('#city_multiple').niceSelect('update');
            },

            complete:function(){
                $("#CityLoader").hide();
            }
        });
    }



    //  var selectedOption = $(this).find(':selected');

    // var grpType = selectedOption.data('type');   // 👈 get type

    // alert(grpType); 
    // // store in hidden field
    // $("#grp_type_hidden").val(grpType);

    // handleGroupChange(grpType, selectedId);

});


$("#recgrpList_id").on("change", function () {

    var selectedOption = $(this).find(':selected');

    var grpType = selectedOption.data('type');   // 👈 get type
    var selectedId = $(this).val();

    // store in hidden field
    $("#grp_type_hidden").val(grpType);

    handleGroupChange(grpType, selectedId);

    // Show based on type
    // if (grpType == 1) {
    //     $("#basedBuDivView").removeClass('d-none');   // show BU
    //     $("#basedDepartmentDivView").addClass('d-none');
    //     $("#basedStateDivView").addClass('d-none');
    //     $("#basedCityDivView").addClass('d-none');
    //     var url = $("#ajaxGetOverAllList").val();

    //     $.ajax({
    //         type: "GET",
    //         url: url,
    //         data: {
    //             id: selectedId,
    //             grp_type: grpType   // 👈 pass type also to backend
    //         },
    //         dataType: "json",

    //         beforeSend: function () {
    //             $("#BuLoader").show();
    //         },

    //         success: function (data) {

    //             $("#bu_multiple").empty();

    //             if (data.length > 0) {
    //                 $.each(data, function (i, section) {

    //                     $("#bu_multiple").append(
    //                         '<option value="'+section.id+'" data-type="'+section.grp_type+'">'+section.bu_name+'</option>'
    //                     );

    //                 });
    //             }

    //             $('#bu_multiple').niceSelect('update');
    //         },

    //         complete: function () {
    //             $("#BuLoader").hide();
    //         }
    //     });
    // } else if (grpType == 2) {
    //     $("#basedBuDivView").addClass('d-none');   // show BU
    //     $("#basedDepartmentDivView").removeClass('d-none');
    //     $("#basedStateDivView").addClass('d-none');
    //     $("#basedCityDivView").addClass('d-none');
    //     var url = $("#ajaxGetOverAllList").val();

    //     $.ajax({
    //         type: "GET",
    //         url: url,
    //         data: {
    //             id: selectedId,
    //             grp_type: grpType   // 👈 pass type also to backend
    //         },
    //         dataType: "json",

    //         beforeSend: function () {
    //             $("#BuLoader").show();
    //         },

    //         success: function (data) {

    //             $("#bu_multiple").empty();

    //             if (data.length > 0) {
    //                 $.each(data, function (i, section) {

    //                     $("#dept_multiple").append(
    //                         '<option value="'+section.id+'" data-type="'+section.grp_type+'">'+section.dept_name+'</option>'
    //                     );

    //                 });
    //             }

    //             $('#bu_multiple').niceSelect('update');
    //         },
    //         complete: function () {
    //             $("#BuLoader").hide();
    //         }
    //     });
    // } else if (grpType == 3) {
    //     $("#basedBuDivView").addClass('d-none');   // show BU
    //     $("#basedDepartmentDivView").addClass('d-none');
    //     $("#basedStateDivView").addClass('d-none');
    //     $("#basedCityDivView").addClass('d-none');
    // } else if (grpType == 4) {
    //     $("#basedStateDivView").removeClass('d-none');
    //     $("#basedBuDivView").addClass('d-none');   // show BU
    //     $("#basedDepartmentDivView").addClass('d-none');
    //     $("#basedCityDivView").addClass('d-none');
    //     var url = $("#ajaxGetOverAllList").val();
    //     $.ajax({
    //         type: "GET",
    //         url: url,
    //         data: {
    //             id: selectedId,
    //             grp_type: grpType   // 👈 pass type also to backend
    //         },
    //         dataType: "json",

    //         beforeSend: function () {
    //             $("#CityLoader").show();
    //         },

    //         success: function (data) {

    //             $("#state_multiple").empty();

    //             if (data.length > 0) {
    //                 $.each(data, function (i, section) {

    //                     $("#state_multiple").append(
    //                         '<option value="'+section.id+'" data-type="'+section.grp_type+'">'+section.state_name+'</option>'
    //                     );

    //                 });
    //             }

    //             $('#state_multiple').niceSelect('update');
    //         },

    //         complete: function () {
    //             $("#CityLoader").hide();
    //         }
    //     });
    // } else if (grpType == 5) {
    //     $("#basedStateDivView").addClass('d-none');
    //     $("#basedBuDivView").addClass('d-none');   // show BU
    //     $("#basedDepartmentDivView").addClass('d-none');
    //     $("#basedCityDivView").removeClass('d-none');
        
    //     var url = $("#ajaxGetOverAllList").val();
    //     $.ajax({
    //         type: "GET",
    //         url: url,
    //         data: {
    //             id: selectedId,
    //             grp_type: grpType   // 👈 pass type also to backend
    //         },
    //         dataType: "json",

    //         beforeSend: function () {
    //             $("#CityLoader").show();
    //         },

    //         success: function (data) {

    //             $("#city_multiple").empty();

    //             if (data.length > 0) {
    //                 $.each(data, function (i, section) {

    //                     $("#city_multiple").append(
    //                         '<option value="'+section.id+'" data-type="'+section.grp_type+'">'+section.city_name+'</option>'
    //                     );

    //                 });
    //             }

    //             $('#city_multiple').niceSelect('update');
    //         },

    //         complete: function () {
    //             $("#CityLoader").hide();
    //         }
    //     });
    // } else if (grpType == 6) {
    //     $("#basedStateDivView").addClass('d-none');
    //     $("#basedBuDivView").addClass('d-none');   // show BU
    //     $("#basedDepartmentDivView").addClass('d-none');
    //     $("#basedCityDivView").addClass('d-none');
    // } else if (grpType == 7) {
    //     $("#basedStateDivView").addClass('d-none');
    //     $("#basedBuDivView").addClass('d-none');   // show BU
    //     $("#basedDepartmentDivView").addClass('d-none');
    //     $("#basedCityDivView").addClass('d-none');
    // } else if (grpType == 8) {
    //     $("#basedStateDivView").addClass('d-none');
    //     $("#basedBuDivView").addClass('d-none');   // show BU
    //     $("#basedDepartmentDivView").addClass('d-none');
    //     $("#basedCityDivView").addClass('d-none');
    // } else if (grpType == 0) {
    //     $("#basedStateDivView").addClass('d-none');
    //     $("#basedBuDivView").addClass('d-none');   // show BU
    //     $("#basedDepartmentDivView").addClass('d-none');
    //     $("#basedCityDivView").addClass('d-none');
    // }

    // AJAX call
    

});


function handleGroupChange(grpType, selectedId) {
    $("#grp_type_hidden").val(grpType);

    // Hide all first
    // $("#basedBuDivView, #basedDepartmentDivView, #basedStateDivView, #basedCityDivView").addClass('d-none');

    if (grpType == 1) {
        $("#basedBuDivView").removeClass('d-none');   // show BU
        $("#basedDepartmentDivView").addClass('d-none');
        $("#basedStateDivView").addClass('d-none');
        $("#basedCityDivView").addClass('d-none');
        var url = $("#ajaxGetOverAllList").val();

        $.ajax({
            type: "GET",
            url: url,
            data: {
                id: selectedId,
                grp_type: grpType   // 👈 pass type also to backend
            },
            dataType: "json",

            beforeSend: function () {
                $("#BuLoader").show();
            },

            success: function (data) {

                $("#bu_multiple").empty();

                if (data.length > 0) {
                    $.each(data, function (i, section) {

                        $("#bu_multiple").append(
                            '<option value="'+section.id+'" data-type="'+section.grp_type+'">'+section.bu_name+'</option>'
                        );

                    });
                }

                $('#bu_multiple').niceSelect('update');
            },

            complete: function () {
                $("#BuLoader").hide();
            }
        });
        // your BU ajax...
    } 
    else if (grpType == 2) {
        
        $("#basedBuDivView").addClass('d-none');   // show BU
        $("#basedDepartmentDivView").removeClass('d-none');
        $("#basedStateDivView").addClass('d-none');
        $("#basedCityDivView").addClass('d-none');
        var url = $("#ajaxGetOverAllList").val();

        $.ajax({
            type: "GET",
            url: url,
            data: {
                id: selectedId,
                grp_type: grpType   // 👈 pass type also to backend
            },
            dataType: "json",

            beforeSend: function () {
                $("#BuLoader").show();
            },

            success: function (data) {

                $("#bu_multiple").empty();

                if (data.length > 0) {
                    $.each(data, function (i, section) {

                        $("#dept_multiple").append(
                            '<option value="'+section.id+'" data-type="'+section.grp_type+'">'+section.dept_name+'</option>'
                        );

                    });
                }

                $('#bu_multiple').niceSelect('update');
            },
            complete: function () {
                $("#BuLoader").hide();
            }
        });
        // your dept ajax...
    } 
    else if (grpType == 3) {
        // 👉 your custom logic for type 3
        $("#basedBuDivView").addClass('d-none');   // show BU
        $("#basedDepartmentDivView").addClass('d-none');
        $("#basedStateDivView").addClass('d-none');
        $("#basedCityDivView").addClass('d-none');
    } 
    else if (grpType == 4) {
        // state ajax...
        $("#basedStateDivView").removeClass('d-none');
        $("#basedBuDivView").addClass('d-none');   // show BU
        $("#basedDepartmentDivView").addClass('d-none');
        $("#basedCityDivView").addClass('d-none');
        var url = $("#ajaxGetOverAllList").val();
        $.ajax({
            type: "GET",
            url: url,
            data: {
                id: selectedId,
                grp_type: grpType   // 👈 pass type also to backend
            },
            dataType: "json",

            beforeSend: function () {
                $("#CityLoader").show();
            },

            success: function (data) {

                $("#state_multiple").empty();

                if (data.length > 0) {
                    $.each(data, function (i, section) {

                        $("#state_multiple").append(
                            '<option value="'+section.id+'" data-type="'+section.grp_type+'">'+section.state_name+'</option>'
                        );

                    });
                }

                $('#state_multiple').niceSelect('update');
            },

            complete: function () {
                $("#CityLoader").hide();
            }
        });
    } 
    else if (grpType == 5) {
         $("#basedStateDivView").addClass('d-none');
        $("#basedBuDivView").addClass('d-none');   // show BU
        $("#basedDepartmentDivView").addClass('d-none');
        $("#basedCityDivView").removeClass('d-none');
        
        var url = $("#ajaxGetOverAllList").val();
        $.ajax({
            type: "GET",
            url: url,
            data: {
                id: selectedId,
                grp_type: grpType   // 👈 pass type also to backend
            },
            dataType: "json",

            beforeSend: function () {
                $("#CityLoader").show();
            },

            success: function (data) {

                $("#city_multiple").empty();

                if (data.length > 0) {
                    $.each(data, function (i, section) {

                        $("#city_multiple").append(
                            '<option value="'+section.id+'" data-type="'+section.grp_type+'">'+section.city_name+'</option>'
                        );

                    });
                }

                $('#city_multiple').niceSelect('update');
            },

            complete: function () {
                $("#CityLoader").hide();
            }
        });
        // city ajax...
    } else if (grpType == 6) {
        $("#basedStateDivView").addClass('d-none');
        $("#basedBuDivView").addClass('d-none');   // show BU
        $("#basedDepartmentDivView").addClass('d-none');
        $("#basedCityDivView").addClass('d-none');
    } else if (grpType == 7) {
        $("#basedStateDivView").addClass('d-none');
        $("#basedBuDivView").addClass('d-none');   // show BU
        $("#basedDepartmentDivView").addClass('d-none');
        $("#basedCityDivView").addClass('d-none');
    } else if (grpType == 8) {
        $("#basedStateDivView").addClass('d-none');
        $("#basedBuDivView").addClass('d-none');   // show BU
        $("#basedDepartmentDivView").addClass('d-none');
        $("#basedCityDivView").addClass('d-none');
    } else if (grpType == 0) {
        $("#basedStateDivView").addClass('d-none');
        $("#basedBuDivView").addClass('d-none');   // show BU
        $("#basedDepartmentDivView").addClass('d-none');
        $("#basedCityDivView").addClass('d-none');
    }
}


$(document).on("keyup change", "#access_limit", function () {

    var enrolledCount = parseInt($("#enrolled_count").val());
    var already_allocated = parseInt($("#already_allocated").val());
    
    var newLimit = parseInt($(this).val());

    if(newLimit < enrolledCount){
        toastr.warning(
            "You cannot reduce the seat limit below already enrolled users (" + enrolledCount + ").",
            "Seat Limit Error"
        );


        $(this).val(already_allocated);
    }

});


// console.log('course.js loaded');

$(document).off('click', '.openEnrollModal');

function openEnrollModal(courseId)
{
    // console.log(courseId);

    $('#popup_course_id').val(courseId);

    // $('#confirm_cancel_delete_bulk').modal({
    //     backdrop: 'static',
    //     keyboard: false
    // });
    $('#confirm_cancel_delete_bulk').modal('show');
}

$(document).off('click', '.openQuizEnrollModal');

function openQuizEnrollModal(courseId)
{
    // console.log(courseId);

    $('#quiz_course_id').val(courseId);

    // $('#confirm_cancel_delete_bulk').modal({
    //     backdrop: 'static',
    //     keyboard: false
    // });
    $('#confirm_cancel_delete_bulk2').modal('show');
}


   