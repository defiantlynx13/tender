jQuery(document).ready(function($){
    Validator.useLang('fa');
    Validator.register('mobile', function (val) {
        return val.match(/^09[0-9]{9}$/g);
    }, 'شماره موبایل اشتباه است');
    Validator.register('phone', function (val) {
        return (val.match(/^[1-9]{1}[0-9-]{6,7}$/g) || val.match(/^0[0-9-]{7,14}$/g) || val.match(/^[^0]{1}[0-9]{3}$/g) );
    }, 'شماره تلفن اشتباه است');
    var allFormsData={};

    var allFormsData={};
    var profile_update_pass_form=$('#profile_update_pass');
    var current_pass = $('#current_pass');
    var new_pass = $('#new_pass');
    var update_pass_btn = $('#update_pass_btn');


    new_pass.keydown(function (e) {
        if (e.keyCode == 13) {
            update_pass_btn.trigger('click');
            e.stopPropagation();
            return false;
        }
    });

    update_pass_btn.on('click',function (event) {
        profile_update_pass_form.find(".update_pass_current_pass_error").html("").addClass("hidden");
        profile_update_pass_form.find(".update_pass_new_pass_error").html("").addClass("hidden");

        allFormsData.current_pass = current_pass.attr('value');
        if (undefined === allFormsData.current_pass)
        {
            allFormsData.current_pass = current_pass.val();
        }

        allFormsData.new_pass = new_pass.attr('value');
        if (undefined === allFormsData.new_pass)
        {
            allFormsData.new_pass = new_pass.val();
        }

        let loginData = {
            current_pass: allFormsData.current_pass,
            new_pass: allFormsData.new_pass
        };

        let rules = {
            current_pass: 'required',
            new_pass: 'required|min:5'
        };

        let validation = new Validator(loginData, rules);


        if (validation.fails())
        {
            var current_pass_validation_errors = validation.errors.get('current_pass');
            if (current_pass_validation_errors && current_pass_validation_errors.length > 0)
            {
                profile_update_pass_form.find(".update_pass_current_pass_error").html(validation.errors.errors['current_pass'][0]).removeClass("hidden");
                traverse(document.body);
            }

            var new_pass_validation_errors = validation.errors.get('new_pass');
            if (new_pass_validation_errors && new_pass_validation_errors.length > 0)
            {
                profile_update_pass_form.find(".update_pass_new_pass_error").html(validation.errors.errors['new_pass'][0]).removeClass("hidden");
                traverse(document.body);
            }

        }
        else
        {
            profile_update_pass_form.find(".update_pass_current_pass_error").html("").addClass("hidden");
            profile_update_pass_form.find(".update_pass_new_pass_error").html("").addClass("hidden");
            update_pass_btn.find("i").addClass(' icon-check2 fa fa-circle-o-notch fa-spin');
            update_pass_btn.prop('disabled', true);


            $.post(
                myData.ajax_url,
                {
                    action : 'tnd_admin_profile_update_pass',
                    current_pass: allFormsData.current_pass,
                    new_pass: allFormsData.new_pass,
                    user_id:  myData.user_id
                },
                function (response)
                {
                    update_pass_btn.find("i").removeClass('fa fa-circle-o-notch fa-spin');
                    update_pass_btn.prop('disabled', false);

                    switch (response.status)
                    {
                        case 'password_set':
                            $.notify(
                                {
                                    message: 'گذرواژه شما با موفقیت بروزرسانی گردید!'
                                },
                                {
                                    type: 'success',
                                    placement: {
                                        from: "top",
                                        align: "left"
                                    },
                                    showProgressbar: true,
                                    mouse_over: "pause",
                                    allow_dismiss: false,
                                    animate: {
                                        enter: 'animated slideInDown',
                                        exit: 'animated slideOutUp'

                                    }
                                }
                            );

                            $.notify(
                                {
                                    message: 'تا چند ثانیه دیگر به صفحه ورود منتقل می شوید!'
                                },
                                {
                                    type: 'success',
                                    placement: {
                                        from: "top",
                                        align: "left"
                                    },
                                    showProgressbar: true,
                                    mouse_over: "pause",
                                    allow_dismiss: false,
                                    animate: {
                                        enter: 'animated slideInDown',
                                        exit: 'animated slideOutUp'

                                    }
                                }
                            );

                            setTimeout(
                                function()
                                {
                                    window.location.href=myData.home_url+'/login';
                                }, 5000);

                            break;

                        case 'passwords_are_same':
                            $.notify(
                                {
                                    message: 'پسورد های وارد شده یکسان هستند. پسورد متفاوت با پسورد فعلی وارد نمایید!'
                                },
                                {
                                    type: 'danger',
                                    placement: {
                                        from: "top",
                                        align: "left"
                                    },
                                    showProgressbar: true,
                                    mouse_over: "pause",
                                    allow_dismiss: false,
                                    animate: {
                                        enter: 'animated slideInDown',
                                        exit: 'animated slideOutUp'

                                    }
                                }
                            );
                            break;

                        case 'current_pass_false':
                            $.notify(
                                {
                                    message: 'گذرواژه فعلی وارد شده، نادرست است!'
                                },
                                {
                                    type: 'danger',
                                    placement: {
                                        from: "top",
                                        align: "left"
                                    },
                                    showProgressbar: true,
                                    mouse_over: "pause",
                                    allow_dismiss: false,
                                    animate: {
                                        enter: 'animated slideInDown',
                                        exit: 'animated slideOutUp'

                                    }
                                }
                            );
                            break;

                        case 'not_safe':
                            $.notify(
                                {
                                    message: 'سیستم شما را به عنوان اخلالگر امنیتی تشخیص داد است!'
                                },
                                {
                                    type: 'danger',
                                    placement: {
                                        from: "top",
                                        align: "left"
                                    },
                                    showProgressbar: true,
                                    mouse_over: "pause",
                                    allow_dismiss: false,
                                    animate: {
                                        enter: 'animated slideInDown',
                                        exit: 'animated slideOutUp'
                                    }
                                }
                            );
                            break;
                    }
                });
        }

    });



    //add profile  pic
    $('#add_profile_pic,#edit_profile_pic').on('click',function (event) {
        $('#profile_pic_upload').trigger('click');
    });


    $("#profile_pic_upload").change(function(e){
        var fd = new FormData();
        fd.append("profile_pic", $("#profile_pic_upload")[0].files[0]);
        fd.append('user_id',myData.user_id);
        fd.append('action','tnd_profile_pic_add');
        $.ajax({
            url : myData.ajax_url,
            type : "post",
            data : fd,
            cache: false,
            contentType: false,
            processData: false,
            success : function(data){
                if (data.success == true)
                {
                    $.notify(
                        {
                            message: data.error
                        },
                        {
                            type: 'success',
                            placement: {
                                from: "top",
                                align: "left"
                            },
                            showProgressbar: true,
                            mouse_over: "pause",
                            allow_dismiss: false,
                            animate: {
                                enter: 'animated slideInDown',
                                exit: 'animated slideOutUp'

                            }
                        }
                    );

                    setTimeout(function() {
                        window.location.href="";
                    }, 2000);
                }
                else
                {
                    $.notify(
                        {
                            message: data.error
                        },
                        {
                            type: 'danger',
                            placement: {
                                from: "top",
                                align: "left"
                            },
                            showProgressbar: true,
                            mouse_over: "pause",
                            allow_dismiss: false,
                            animate: {
                                enter: 'animated slideInDown',
                                exit: 'animated slideOutUp'

                            }
                        }
                    );
                }


            }
        });
    });

    //remove profile  pic
    $('#remove_profile_pic').on('click',function (event) {
        var data = new FormData();
        data.append('user_id',myData.user_id);
        data.append('action','tnd_profile_pic_remove');
        $.ajax({
            url : myData.ajax_url,
            type : "post",
            data : data,
            cache: false,
            contentType: false,
            processData: false,
            success : function(data){
                if (data.success == true)
                {
                    $.notify(
                        {
                            message: data.error
                        },
                        {
                            type: 'success',
                            placement: {
                                from: "top",
                                align: "left"
                            },
                            showProgressbar: true,
                            mouse_over: "pause",
                            allow_dismiss: false,
                            animate: {
                                enter: 'animated slideInDown',
                                exit: 'animated slideOutUp'

                            }
                        }
                    );

                    setTimeout(function() {
                        window.location.href="";
                    }, 2000);
                }
                else
                {
                    $.notify(
                        {
                            message: data.error
                        },
                        {
                            type: 'danger',
                            placement: {
                                from: "top",
                                align: "left"
                            },
                            showProgressbar: true,
                            mouse_over: "pause",
                            allow_dismiss: false,
                            animate: {
                                enter: 'animated slideInDown',
                                exit: 'animated slideOutUp'

                            }
                        }
                    );
                }
            }
        });
    });


    //update user info

    $('#update_profile_info_btn').on('click',function (event) {
        var update_profile_info_form=$('#update_profile_info_form');
        var update_profile_info_form_btn=$('#update_profile_info_btn');
        var fname = $('#fname');
        var lname = $('#lname ');
        var email = $('#email ');
        var tel = $('#tel ');
        var bio = $('#bio ');

        update_profile_info_form.find(".fname_error").html("").addClass("hidden");
        update_profile_info_form.find(".lname_error").html("").addClass("hidden");
        update_profile_info_form.find(".email_error").html("").addClass("hidden");
        update_profile_info_form.find(".tel_error").html("").addClass("hidden");


        allFormsData.fname = fname.val();
        allFormsData.lname = lname.val();
        allFormsData.email = email.val();
        allFormsData.tel = tel.val();
        allFormsData.bio = bio.val();

        let corp_Data = {
            email: allFormsData.email,
            tel: allFormsData.tel,
        };

        let rules = {
            email: 'email',
            tel: 'mobile',
        };

        let validation = new Validator(corp_Data, rules);


        if (validation.fails())
        {
            var email_validation_errors = validation.errors.get('email');
            if (email_validation_errors && email_validation_errors.length > 0)
            {
                update_profile_info_form.find(".email_error").html(validation.errors.errors['email'][0]).removeClass("hidden");

            }

            var tel_validation_errors = validation.errors.get('tel');
            if (tel_validation_errors && tel_validation_errors.length > 0)
            {
                update_profile_info_form.find(".tel_error").html(validation.errors.errors['tel'][0]).removeClass("hidden");

            }
            traverse(document.body);

        }
        else
        {
            update_profile_info_form.find(".fname_error").html("").addClass("hidden");
            update_profile_info_form.find(".lname_error").html("").addClass("hidden");
            update_profile_info_form.find(".email_error").html("").addClass("hidden");
            update_profile_info_form.find(".tel_error").html("").addClass("hidden");
            update_profile_info_form_btn.find("i").addClass(' icon-check2 fa fa-circle-o-notch fa-spin');
            update_profile_info_form_btn.prop('disabled', true);

            $.post(
                myData.ajax_url,
                {
                    action : 'tnd_customer_profile_info_update',
                    fname: allFormsData.fname,
                    lname: allFormsData.lname,
                    email: allFormsData.email,
                    tel: allFormsData.tel,
                    bio: allFormsData.bio,
                    user_id:  myData.user_id
                },
                function (response)
                {
                    if (response.success == 'true')
                    {
                        $.notify(
                            {
                                message: response.error
                            },
                            {
                                type: 'success',
                                placement: {
                                    from: "top",
                                    align: "left"
                                },
                                showProgressbar : true,
                                mouse_over: "pause",
                                allow_dismiss : false
                            }
                        );
                        setTimeout(function() {
                            window.location.href="";
                        }, 2000);
                    }
                    else
                    {
                        update_profile_info_form_btn.find("i").removeClass('fa fa-circle-o-notch fa-spin');
                        update_profile_info_form_btn.prop('disabled', false);
                        $.notify(
                            {
                                message:response.error
                            },
                            {
                                type: 'danger',
                                placement: {
                                    from: "top",
                                    align: "left"
                                },
                                showProgressbar : true,
                                mouse_over: "pause",
                                allow_dismiss : false
                            }
                        );
                    }

                });

        }
    });
    persian={0:'۰',1:'۱',2:'۲',3:'۳',4:'۴',5:'۵',6:'۶',7:'۷',8:'۸',9:'۹'};
    function traverse(el){
        if(el.nodeType==3){
            var list=el.data.match(/[0-9]/g);
            if(list!=null && list.length!=0){
                for(var i=0;i<list.length;i++)
                    el.data=el.data.replace(list[i],persian[list[i]]);
            }
        }
        for(var i=0;i<el.childNodes.length;i++){
            traverse(el.childNodes[i]);
        }
    }

});