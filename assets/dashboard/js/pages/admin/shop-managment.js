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


    fetch_all_shop_data();
    function fetch_all_shop_data()
    {
        $('#shop_managment_tbl').addClass('loadingbar gray');
        $('#shop_managment_tbl').DataTable({
            language: {
                zeroRecords:    "تا به حال فروشگاهی ثبت نشده است!",
                info:           "نمایش _START_ تا _END_ از _TOTAL_ فروشگاه",
                processing:     "در حال پردازش",
                searchPlaceholder: "جستجو ...",
                lengthMenu:    " _MENU_",
                emptyTable:     "تا به حال فروشگاهی ثبت نشده است!",
                infoEmpty:      "نمایش 0 تا 0 از 0 فروشگاه",
                infoFiltered:   "(جستجو در مجموع _MAX_  فروشگاه)",
                "paginate": {
                    "first":      "اولین",
                    "last":       "آخرین",
                    "next":       "بعدی",
                    "previous":   "قبلی"
                },
            },
            pageLength:8,
            dom: "Bfrtp",
            buttons: [
                {
                    text: '<i class="fa fa-plus "></i> &nbsp;افزودن فروشگاه/کارخانه ',
                    className: 'btn btn-outline-success btn-min-width mr-1 mb-1',
                    attr:  {
                        id: 'add_row_shop'
                    }
                }
            ],
            columnDefs: [
                { className: "text-center", targets: "_all" },
            ],
            bFilter :false,
            // responsive : true,
            serverSide: true,
            ajax: {
                url: myData.ajax_url,
                data: {
                    action: 'fetch_shop_data',
                    user_id: myData.u_id,
                },
                type: 'post',
            },
            "fnDrawCallback" : function() {
                traverse(document.body);
                $('#shop_managment_tbl').removeClass('loadingbar gray');
            },
            "rowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

                if ( aData[8] == "true" )
                {
                    if (aData[9] == 'pending')
                    {
                        $('td:eq(0)', nRow).addClass('bg-warning');
                    }
                    else if(aData[9] == 'verified')
                    {
                        $('td:eq(0)', nRow).addClass('bg-success');
                    }

                }
                else
                {
                    $('td:eq(0)', nRow).addClass('bg-danger');
                }
                return nRow;
            }
        } );
    }


    //add customer
    $('body').on('click','#add_row_shop',function () {
        $('#add_shop_modal').modal('toggle');
    });

    $('body').on('click','#add_new_shop_btn',function () {
        $(".new_shop_name_error").html("").addClass("hidden");
        $(".new_user_name_error").html("").addClass("hidden");
        $(".new_password_error").html("").addClass("hidden");

        var shop_type=$('input[name="new_shop_type"]:checked').val();
        var shop_name=$('#new_shop_name').val();
        var user_name=$('#new_user_name').val();
        var password=$('#new_password').val();


        let corp_Data = {
            shop_type: shop_type,
            shop_name: shop_name,
            user_name: user_name,
            password: password,
        };

        let rules = {
            shop_name: 'required',
            user_name: 'required|min:5',
            password: 'required|min:5',
        };

        let validation = new Validator(corp_Data, rules);


        if (validation.fails())
        {

            var shop_name_validation_errors = validation.errors.get('shop_name');
            if (shop_name_validation_errors && shop_name_validation_errors.length > 0)
            {
                $(".new_shop_name_error").html(validation.errors.errors['shop_name'][0]).removeClass("hidden");
                traverse(document.body);
            }

            var user_name_validation_errors = validation.errors.get('user_name');
            if (user_name_validation_errors && user_name_validation_errors.length > 0)
            {
                $(".new_user_name_error").html(validation.errors.errors['user_name'][0]).removeClass("hidden");
                traverse(document.body);
            }
            var password_validation_errors = validation.errors.get('password');
            if (password_validation_errors && password_validation_errors.length > 0)
            {
                $(".new_password_error").html(validation.errors.errors['password'][0]).removeClass("hidden");
                traverse(document.body);
            }
        }
        else
        {
            $('#add_new_shop_btn').find("i").removeClass('icon-check2').addClass('  fa fa-circle-o-notch fa-spin');
            $('#add_new_shop_btn').prop('disabled', true);

            $.post(
                myData.ajax_url,
                {
                    action : 'tnd_new_shop',
                    user_id: myData.u_id,
                    shop_type: shop_type,
                    shop_name: shop_name,
                    user_name: user_name,
                    password: password,
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
                        $('#shop_managment_tbl').DataTable().destroy();
                        fetch_all_shop_data();
                        $('#add_new_shop_btn').find("i").removeClass('fa fa-circle-o-notch fa-spin').addClass('icon-check2');
                        $('#add_new_shop_btn').prop('disabled', false);
                        $('#add_shop_modal').modal('hide');
                    }
                    else
                    {
                        $.notify(
                            {
                                message: response.error
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

                        $('#add_new_shop_btn').find("i").removeClass('fa fa-circle-o-notch fa-spin').addClass('icon-check2');
                        $('#add_new_shop_btn').prop('disabled', false);
                    }

                });
        }

    });

    //user verification
    $('body').on('click','#shop_verification_btn',function () {

        var user_id=$(this).data('user_id');
        $.post(
            myData.ajax_url,
            {
                action : 'tnd_shop_verification',
                user_id: user_id,
            },
            function (response)
            {
                if (response.success == true)
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
                    $('#shop_managment_tbl').DataTable().destroy();
                    fetch_all_shop_data();
                }
                else
                {
                    $.notify(
                        {
                            message: response.error
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
    });


    //user resend files
    $('body').on('click','#resend_files_btn',function () {
        $('#resend_files_modal_confirm').modal('toggle');
        $('#resend_files_ok_btn').data('user_id',$(this).data('user_id'));
    });
    $('#resend_files_ok_btn').on('click',function (event) {
        $('#resend_files_modal_confirm').modal('hide');
        var user_id=$(this).data('user_id');
        $.post(
            myData.ajax_url,
            {
                action : 'tnd_resend_files',
                user_id: user_id,
            },
            function (response)
            {
                if (response.success == true)
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
                    $('#shop_managment_tbl').DataTable().destroy();
                    fetch_all_shop_data();
                }
                else
                {
                    $.notify(
                        {
                            message: response.error
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
    });

    //change user password
    $('body').on('click','#change_pass_btn',function ()
    {

        $('#change_user_pass_btn_confirm').attr('data-user_id',$(this).data('user_id'));
        $('#change_user_pass_modal').modal('toggle');
    });

    //change user password confirm
    $('body').on('click','#change_user_pass_btn_confirm',function () {
        $(".new_pass_error").html("").addClass("hidden");
        let corp_Data = {
            new_pass:$('#new_pass').val(),
        };

        let rules = {
            new_pass: 'required|min:6',
        };

        let validation = new Validator(corp_Data, rules);
        if (validation.fails())
        {

            var new_pass_validation_errors = validation.errors.get('new_pass');
            if (new_pass_validation_errors && new_pass_validation_errors.length > 0)
            {
                $(".new_pass_error").html(validation.errors.errors['new_pass'][0]).removeClass("hidden");
                traverse(document.body);
            }
        }
        else
        {
            $('#change_user_pass_btn_confirm').find("i").addClass(' icon-check2 fa fa-circle-o-notch fa-spin');
            $('#change_user_pass_btn_confirm').prop('disabled', true);
            $.post(
                myData.ajax_url,
                {
                    action : 'update_corp_pass_admin',
                    user_id: myData.u_id,
                    corp_id: $(this).data('user_id'),
                    new_pass: $('#new_pass').val(),
                },
                function (response)
                {
                    if (response.success == true)
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
                        $('#change_user_pass_modal').modal('hide');
                    }
                    else
                    {
                        $.notify(
                            {
                                message: response.error
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

                    $('#change_user_pass_btn_confirm').find("i").removeClass(' icon-check2 fa fa-circle-o-notch fa-spin');
                    $('#change_user_pass_btn_confirm').prop('disabled', false);

                });

        }

    });


    //lock user
    $('body').on('click','#lock_user_btn',function () {

        $('#lock_user_modal_confirm').modal('toggle');
        $('#lock_user_confirm_ok_btn').data('user_id',$(this).data('user_id'));
    });


    $('#lock_user_confirm_ok_btn').on('click',function (event) {
        $('#lock_user_modal_confirm').modal('hide');
        var user_id=$(this).data('user_id');
        $.post(
            myData.ajax_url,
            {
                action : 'tnd_lock_user',
                user_id: user_id,
            },
            function (response)
            {
                if (response.success == true)
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
                    $('#shop_managment_tbl').DataTable().destroy();
                    fetch_all_shop_data();
                }
                else
                {
                    $.notify(
                        {
                            message: response.error
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
    });

    //unlock user
    $('body').on('click','#unlock_user_btn',function () {

        var user_id=$(this).data('user_id');
        $.post(
            myData.ajax_url,
            {
                action : 'tnd_unlock_user',
                user_id: user_id,
            },
            function (response)
            {
                if (response.success == true)
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
                    $('#shop_managment_tbl').DataTable().destroy();
                    fetch_all_shop_data();
                }
                else
                {
                    $.notify(
                        {
                            message: response.error
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
    });


    //edit corp name anf grade
    $('body').on('click','#edit_user_and_grade_corp_name',function () {

        $(".user_edit_shop_name_error").html("").addClass("hidden");
        $(".user_edit_shop_manager_name_error").html("").addClass("hidden");
        $(".user_edit_shop_address_error").html("").addClass("hidden");
        $(".user_edit_shop_tel_error").html("").addClass("hidden");
        $(".user_edit_shop_code_error").html("").addClass("hidden");

        ($(this).data('shop_type') == 'shop')? $('#user_edit_type_shop').prop("checked", true): $('#user_edit_type_factory').prop("checked", true);

        $('#user_edit_shop_name').val($(this).data('shop_name'));
        $('#user_edit_shop_manager_name').val($(this).data('shop_manager_name'));
        $('#user_edit_shop_address').val($(this).data('shop_address'));
        $('#user_edit_shop_tel').val($(this).data('shop_tel'));
        $('#user_edit_shop_code').val($(this).data('shop_code'));
        $('#edit_user_shop_data_confirm').attr('data-user_id',$(this).data('user_id'));
        $('#edit_shop_data_modal').modal('toggle');
    });

    //edit corp name anf grade confirm
    $('body').on('click','#edit_user_shop_data_confirm',function () {
        $(".user_edit_shop_name_error").html("").addClass("hidden");
        $(".user_edit_shop_manager_name_error").html("").addClass("hidden");
        $(".user_edit_shop_address_error").html("").addClass("hidden");
        $(".user_edit_shop_tel_error").html("").addClass("hidden");
        $(".user_edit_shop_code_error").html("").addClass("hidden");

        let corp_Data = {
            shop_type:$('input[name="user_edit_shop_type"]:checked').val(),
            shop_name:$('#user_edit_shop_name').val(),
            shop_manager_name:$('#user_edit_shop_manager_name').val(),
            shop_address:$('#user_edit_shop_address').val(),
            shop_tel:$('#user_edit_shop_tel').val(),
            shop_code:$('#user_edit_shop_code').val(),
        };

        let rules = {
            shop_name: 'required',
            shop_manager_name: 'required',
            shop_address: 'required',
            shop_tel: 'required|mobile',
            shop_code: 'required',
        };

        let validation = new Validator(corp_Data, rules);
        if (validation.fails())
        {
            var shop_name_validation_errors = validation.errors.get('shop_name');
            if (shop_name_validation_errors && shop_name_validation_errors.length > 0)
            {
                $(".user_edit_shop_name_error").html(validation.errors.errors['shop_name'][0]).removeClass("hidden");
                traverse(document.body);
            }

            var shop_manager_name_validation_errors = validation.errors.get('shop_manager_name');
            if (shop_manager_name_validation_errors && shop_manager_name_validation_errors.length > 0)
            {
                $(".user_edit_shop_manager_name_error").html(validation.errors.errors['shop_manager_name'][0]).removeClass("hidden");
                traverse(document.body);
            }

            var shop_address_validation_errors = validation.errors.get('shop_address');
            if (shop_address_validation_errors && shop_address_validation_errors.length > 0)
            {
                $(".user_edit_shop_address_error").html(validation.errors.errors['shop_address'][0]).removeClass("hidden");
                traverse(document.body);
            }

            var shop_tel_validation_errors = validation.errors.get('shop_tel');
            if (shop_tel_validation_errors && shop_tel_validation_errors.length > 0)
            {
                $(".user_edit_shop_tel_error").html(validation.errors.errors['shop_tel'][0]).removeClass("hidden");
                traverse(document.body);
            }

            var shop_code_validation_errors = validation.errors.get('shop_code');
            if (shop_code_validation_errors && shop_code_validation_errors.length > 0)
            {
                $(".user_edit_shop_code_error").html(validation.errors.errors['shop_code'][0]).removeClass("hidden");
                traverse(document.body);
            }


        }
        else
        {
            $('#edit_user_shop_data_confirm').find("i").addClass(' icon-check2 fa fa-circle-o-notch fa-spin');
            $('#edit_user_shop_data_confirm').prop('disabled', true);
            $.post(
                myData.ajax_url,
                {
                    action : 'edit_shop_data',
                    user_id: myData.u_id,
                    shop_id: $(this).data('user_id'),
                    shop_name:$('#user_edit_shop_name').val(),
                    shop_type:$('input[name="user_edit_shop_type"]:checked').val(),
                    shop_manager_name:$('#user_edit_shop_manager_name').val(),
                    shop_address:$('#user_edit_shop_address').val(),
                    shop_tel:$('#user_edit_shop_tel').val(),
                    shop_code:$('#user_edit_shop_code').val(),
                },
                function (response)
                {
                    if (response.success == true)
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
                        $('#shop_managment_tbl').DataTable().destroy();
                        fetch_all_shop_data();
                        $('#edit_shop_data_modal').modal('hide');
                    }
                    else
                    {
                        $.notify(
                            {
                                message: response.error
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

                    $('#edit_user_shop_data_confirm').find("i").removeClass(' icon-check2 fa fa-circle-o-notch fa-spin');
                    $('#edit_user_shop_data_confirm').prop('disabled', false);

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