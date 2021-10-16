jQuery(document).ready(function($){
    Validator.useLang('fa');
    Validator.register('only_en', function (val) {
        return val.match(/^[a-z][a-z0-9]*$/i);
    }, 'تنها از حروف و اعداد انگلیسی استفاده نمایید.');

    fetch_data();
    function fetch_data()
    {
        $('#user_managment_tbl').DataTable({
            "columns": [
                { "width": "5%" },
                { "width": "30%" },
                { "width": "25%" },
                { "width": "25%" },
                { "width": "25%" },
                { "width": "25%" },
            ],
            language: {
                processing:     "در حال پردازش",
                search:         "",
                searchPlaceholder: "جستجو کاربران",
                lengthMenu:    " _MENU_",
                emptyTable:     "کاربری وجود ندارد",
                zeroRecords:   "کاربری با مشخصات مدنظر شما یافت نشد.",
                "info": "",
                "infoEmpty": "",
                "infoFiltered": "",
                "search": "_INPUT_",
                "searchPlaceholder": "جستجو ... ",
                "paginate": {
                    "previous": "بعدی",
                    "next": "قبلی",
                    "last" : "»",
                    "first" : "«"
                }
            },
            "bPaginate": false,
            "pageLength":20,
            "dom": "Bfrtip",
            buttons: [
                {
                    text: '<i class="icon-plus3 fa-lg "></i> &nbsp;افزودن پیمانکار ',
                    className: 'btn btn-outline-success btn-min-width mr-1 mb-1',
                    attr:  {
                        id: 'add_row_customer'
                    }
                }
            ],
            "serverSide": true,
            "ajax": {
                "url"  : myData.ajax_url+'?action=get_all_users_data&user_id='+myData.user_id
            },
            "rowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                if ( aData[6] == "true" )
                {
                    if (aData[7] == 'pending')
                    {
                        $('td:eq(0)', nRow).addClass('bg-warning');
                    }
                    else if(aData[7] == 'verified')
                    {
                        $('td:eq(0)', nRow).addClass('bg-success');
                    }

                }
                return nRow;

            },
            "fnDrawCallback" : function() {
                traverse(document.body);
            }
        } );
    }


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
    traverse(document.body);


    //show user details
$('body').on('click','#show_user_email_tel_btn',function () {
    var tel=$(this).data('tel');
    var email=$(this).data('email');
    var user_name=$(this).data('user_name');
    var fname_lname=$(this).data('fname_lname');
    $('#user_other_data_modal_name').html(fname_lname);
    $('#user_other_data_modal_username').html(user_name);
    $('#user_other_data_modal_tel').html(tel);
    $('#user_other_data_modal_email').html(email);
    $('#user_other_data_modal').modal('toggle');
});

    //edit corp name anf grade
$('body').on('click','#edit_user_and_grade_corp_name',function () {

    switch ($(this).data('corp_grade'))
    {
        case 'A':
            $("#corporation_grade_A").prop("checked", true);
            break;
        case 'B':
            $("#corporation_grade_B").prop("checked", true);
            break;
        case 'C':
            $("#corporation_grade_C").prop("checked", true);
            break;
        case 'D':
            $("#corporation_grade_D").prop("checked", true);
            break;
    }

    $('#user_edit_corp_name').val($(this).data('corp_name'));
    $('#edit_user_corp_and_name_confirm').attr('data-user_id',$(this).data('user_id'));
    $('#edit_user_and_grade_corp_name_modal').modal('toggle');
});


    //edit corp name anf grade confirm
$('body').on('click','#edit_user_corp_and_name_confirm',function () {
    $(".user_edit_corp_name_error").html("").addClass("hidden");
    let corp_Data = {
        corp_name:$('#user_edit_corp_name').val(),
    };

    let rules = {
        corp_name: 'required',
    };

    let validation = new Validator(corp_Data, rules);
    if (validation.fails())
    {

        var corp_name_validation_errors = validation.errors.get('corp_name');
        if (corp_name_validation_errors && corp_name_validation_errors.length > 0)
        {
            $(".user_edit_corp_name_error").html(validation.errors.errors['corp_name'][0]).removeClass("hidden");
            traverse(document.body);
        }
    }
    else
    {
        $('#edit_user_corp_and_name_confirm').find("i").addClass(' icon-check2 fa fa-circle-o-notch fa-spin');
        $('#edit_user_corp_and_name_confirm').prop('disabled', true);
        $.post(
            myData.ajax_url,
            {
                action : 'edit_user_corp_name_and_grade',
                user_id: myData.user_id,
                corp_id: $(this).data('user_id'),
                corp_name: $('#user_edit_corp_name').val(),
                corp_grade: $("input[name='corporation_grade']:checked").val(),
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
                    $('#user_managment_tbl').DataTable().destroy();
                    fetch_data();
                    $('#edit_user_and_grade_corp_name_modal').modal('hide');
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

                $('#edit_user_corp_and_name_confirm').find("i").removeClass(' icon-check2 fa fa-circle-o-notch fa-spin');
                $('#edit_user_corp_and_name_confirm').prop('disabled', false);

            });

    }

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
                    user_id: myData.user_id,
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
                    $('#user_managment_tbl').DataTable().destroy();
                    fetch_data();
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
                    $('#user_managment_tbl').DataTable().destroy();
                    fetch_data();
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

    //user verification
    $('body').on('click','#user_verification_btn',function () {

        var user_id=$(this).data('user_id');
        $.post(
            myData.ajax_url,
            {
                action : 'tnd_user_verification',
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
                    $('#user_managment_tbl').DataTable().destroy();
                    fetch_data();
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
                    $('#user_managment_tbl').DataTable().destroy();
                    fetch_data();
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


    //add customer
    $('body').on('click','#add_row_customer',function () {
        $('#add_customer_modal').modal('toggle');
    });

    $('body').on('click','#add_new_user_btn',function () {
        $(".new_user_fname_error").html("").addClass("hidden");
        $(".new_user_lname_error").html("").addClass("hidden");
        $(".new_user_name_error").html("").addClass("hidden");
        $(".new_password_error").html("").addClass("hidden");

        var user_fname=$('#new_user_fname').val();
        var user_lname=$('#new_user_lname').val();
        var user_name=$('#new_user_name').val();
        var password=$('#new_password').val();


        let corp_Data = {
            user_fname: user_fname,
            user_lname: user_lname,
            user_name: user_name,
            password: password,
        };

        let rules = {
            user_fname: 'required',
            user_lname: 'required',
            user_name: 'required|only_en|min:5',
            password: 'required|min:5',
        };

        let validation = new Validator(corp_Data, rules);


        if (validation.fails())
        {

            var user_fname_validation_errors = validation.errors.get('user_fname');
            if (user_fname_validation_errors && user_fname_validation_errors.length > 0)
            {
                $(".new_user_fname_error").html(validation.errors.errors['user_fname'][0]).removeClass("hidden");
                traverse(document.body);
            }

            var user_lname_validation_errors = validation.errors.get('user_lname');
            if (user_lname_validation_errors && user_lname_validation_errors.length > 0)
            {
                $(".new_user_lname_error").html(validation.errors.errors['user_lname'][0]).removeClass("hidden");
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
            $('#add_new_user_btn').find("i").addClass(' icon-check2 fa fa-circle-o-notch fa-spin');
            $('#add_new_user_btn').prop('disabled', true);

            $.post(
                myData.ajax_url,
                {
                    action : 'tnd_new_customer',
                    user_id: myData.user_id,
                    user_fname: user_fname,
                    user_lname: user_lname,
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
                        $('#user_managment_tbl').DataTable().destroy();
                        fetch_data();
                        $('#add_new_user_btn').find("i").removeClass('fa fa-circle-o-notch fa-spin');
                        $('#add_new_user_btn').prop('disabled', false);
                        $('#add_customer_modal').modal('hide');
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

                        $('#add_new_user_btn').find("i").removeClass('fa fa-circle-o-notch fa-spin');
                        $('#add_new_user_btn').prop('disabled', false);
                    }

                });
        }

    });

});