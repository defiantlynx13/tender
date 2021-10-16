jQuery(document).ready(function ($) {
    Validator.useLang('fa');
    Validator.register('mobile', function (val) {
        return val.match(/^09[0-9]{9}$/g);
    }, 'شماره موبایل اشتباه است');
    Validator.register('sheba', function (val) {
        return val.match(/^(?:IR)(?=.{24}$)[0-9]*$/);
    }, 'شماره شبا اشتباه است');

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });



    var allFormsData={};


    var update_shop_data_form=$('#update_shop_data');
    var shop_name = $('#shop_name');
    var shop_manager_name = $('#shop_manager_name');
    var shop_address = $('#shop_address');
    var shop_tel = $('#shop_tel');
    var shop_code = $('#shop_code');
    var update_shop_data_btn = $('#update_shop_data_btn');
    var shop_files_count=0;

    $('#shop_file').FancyFileUpload({
        url : myData.ajax_url,
        params :
            {
                action :'tnd_shop_profile_shop_files_data' ,
                user_id : myData.u_id ,
            },
        // accept :["png", "jpg", "jpeg", "pdf","doc","docx"],
        maxfilesize : 20000000,
        added : function(e, data)
        {
            shop_files_count++;
        },
        uploadcompleted :function(e, data)
        {
            update_shop_data();
        },
        uploadcancelled:function(e, data)
        {
            $('.ff_fileupload_wrap table.ff_fileupload_uploads td.ff_fileupload_summary .ff_fileupload_errors').hide();
            $.notify(
                {
                    message: data.result.error
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
            update_shop_data_btn.find("i").removeClass('fa fa-circle-o-notch fa-spin');
            update_shop_data_btn.prop('disabled', false);
        }
    });

    shop_code.keydown(function (e) {
        if (e.keyCode == 13) {
            update_shop_data_btn.trigger('click');
            e.stopPropagation();
            return false;
        }
    });

    update_shop_data_btn.on('click',function (event) {
        update_shop_data_form.find(".shop_name_error").html("").addClass("hidden");
        update_shop_data_form.find(".shop_manager_name_error").html("").addClass("hidden");
        update_shop_data_form.find(".shop_address_error").html("").addClass("hidden");
        update_shop_data_form.find(".shop_tel_error").html("").addClass("hidden");
        update_shop_data_form.find(".shop_code_error").html("").addClass("hidden");

        allFormsData.shop_name = shop_name.val();
        allFormsData.shop_manager_name = shop_manager_name.val();
        allFormsData.shop_address =shop_address.val();
        allFormsData.shop_tel = shop_tel.val();
        allFormsData.shop_code = shop_code.val();

        let shop_Data = {
            shop_name: allFormsData.shop_name,
            shop_manager_name: allFormsData.shop_manager_name,
            shop_address: allFormsData.shop_address,
            shop_tel: allFormsData.shop_tel,
            shop_code: allFormsData.shop_code,
        };

        let rules = {
            shop_name: 'required',
            shop_manager_name: 'required',
            shop_address: 'required',
            shop_tel: 'required|mobile',
            shop_code: 'required',
        };

        let validation = new Validator(shop_Data, rules);


        if (validation.fails())
        {
            var shop_name_validation_errors = validation.errors.get('shop_name');
            if (shop_name_validation_errors && shop_name_validation_errors.length > 0)
            {
                update_shop_data_form.find(".shop_name_error").html(validation.errors.errors['shop_name'][0]).removeClass("hidden");
                traverse(document.body);
            }

            var shop_manager_name_validation_errors = validation.errors.get('shop_manager_name');
            if (shop_manager_name_validation_errors && shop_manager_name_validation_errors.length > 0)
            {
                update_shop_data_form.find(".shop_manager_name_error").html(validation.errors.errors['shop_manager_name'][0]).removeClass("hidden");
                traverse(document.body);
            }

            var shop_address_validation_errors = validation.errors.get('shop_address');
            if (shop_address_validation_errors && shop_address_validation_errors.length > 0)
            {
                update_shop_data_form.find(".shop_address_error").html(validation.errors.errors['shop_address'][0]).removeClass("hidden");
                traverse(document.body);
            }

            var shop_tel_validation_errors = validation.errors.get('shop_tel');
            if (shop_tel_validation_errors && shop_tel_validation_errors.length > 0)
            {
                update_shop_data_form.find(".shop_tel_error").html(validation.errors.errors['shop_tel'][0]).removeClass("hidden");
                traverse(document.body);
            }

            var shop_code_validation_errors = validation.errors.get('shop_code');
            if (shop_code_validation_errors && shop_code_validation_errors.length > 0)
            {
                update_shop_data_form.find(".shop_code_error").html(validation.errors.errors['shop_code'][0]).removeClass("hidden");
                traverse(document.body);
            }


        }
        else
        {
            if (shop_files_count == 0)
            {
                $.notify(
                    {
                        message: 'حداقل یک مدرک از شرکت خود انتخاب نمایید.'
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
                return;
            }

            update_shop_data_form.find(".update_pass_current_pass_error").html("").addClass("hidden");
            update_shop_data_btn.find("i").addClass(' icon-check2 fa fa-circle-o-notch fa-spin');
            update_shop_data_btn.prop('disabled', true);

            $('#shop_data_update_confirm').modal('show');

        }

    });

    $('#shop_data_update_confirm_ok_btn').on('click',function (event) {
        $('#shop_data_update_confirm').modal('hide');
        $(".ff_fileupload_actions button.ff_fileupload_start_upload ").click();
    });

    $('#shop_data_update_confirm_cancel_btn').on('click',function (event) {
        var update_shop_data_btn = $('#update_shop_data_btn');
        update_shop_data_btn.find("i").removeClass('fa fa-circle-o-notch fa-spin');
        update_shop_data_btn.prop('disabled', false);
    });

    $(document).on('click', '.ff_fileupload_actions button.ff_fileupload_remove_file', function(){
        shop_files_count--;
    });



    function update_shop_data()
    {
        $.post(
            myData.ajax_url,
            {
                action : 'tnd_shop_profile_data',
                shop_name: shop_name.val(),
                shop_manager_name: shop_manager_name.val(),
                shop_address: shop_address.val(),
                shop_tel: shop_tel.val(),
                shop_code: shop_code.val(),
                user_id:  myData.u_id
            },
            function (response)
            {
                if (response.status == 'true')
                {
                    $.notify(
                        {
                            message: 'اطلاعات فروشگاه/کارخانه شما برای کارشناس مربوطه ارسال گردید! پس از تایید مدارک ، می توانید در مناقصات شرکت نمایید!'
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
                else if(response.status == 'not_secure')
                {
                    update_shop_data_btn.find("i").removeClass('fa fa-circle-o-notch fa-spin');
                    update_shop_data_btn.prop('disabled', false);
                    $.notify(
                        {
                            message: 'خطای امنیتی رخ داده است!'
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
                else if(response.status == 'false')
                {
                    update_shop_data_btn.find("i").removeClass('fa fa-circle-o-notch fa-spin');
                    update_shop_data_btn.prop('disabled', false);
                    $.notify(
                        {
                            message: 'یکی از اطلاعات فروشگاه/کارخانه وارد نشده است.'
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
                    action : 'tnd_shop_profile_update_pass',
                    current_pass: allFormsData.current_pass,
                    new_pass: allFormsData.new_pass,
                    user_id:  myData.u_id
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
        fd.append('user_id',myData.u_id);
        fd.append('action','tnd_shop_profile_pic_add');

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
        data.append('user_id',myData.u_id);
        data.append('action','tnd_shop_profile_pic_remove');
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