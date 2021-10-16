jQuery(document).ready(function($){
    Validator.useLang('fa');
    var allFormsData={};
    var tendder_request_tool_files_count=0;

    $('body').tooltip({
        selector : '[data-toggle="tooltip"]'
    });

    fetch_data_current_tenders();
    function fetch_data_current_tenders()
    {
        $('#current_tenders_tbl').DataTable({
            "pageLength":10,
            "lengthMenu": [[5,10, 25, -1], [5,10, 25, "همه"]],
            bFilter :false,
            language: {
                processing:     "در حال پردازش",
                search:         "",
                searchPlaceholder: "جستجو مناقصات",
                lengthMenu:    " _MENU_",
                emptyTable:     "مناقصه ای وجود ندارد",
                zeroRecords:   "مناقصه ای با مشخصات مدنظر شما یافت نشد.",
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
            "serverSide": true,
            "ajax": {
                "url"  : myData.ajax_url+'?action=get_all_current_factory_tenders_data&user_id='+myData.user_id
            },
            "fnDrawCallback" : function() {
                traverse(document.body);
            }
        } );
    }





    $(document).on('click','#tender_info_btn',function () {

        $('#tender_only_one').addClass('hidden');

        $('#tender_info_area').html($(this).data('tender_area')+' هکتار ');
        $('#tender_info_price').html($(this).data('tender_price')+' ریال ');
        $('#tender_info_system_type').html($(this).data('tender_system_type'));
        $('#tender_info_advisor').html($(this).data('tender_advisor'));
        $('#tender_info_city').html($(this).data('tender_city'));
        $('#tender_info_district').html($(this).data('tender_district'));
        $('#tender_info_files').html('<a href="'+$(this).data('tender_files')+'">دانلود</a>');

        if ($(this).data('tender_only_one') == 'yes')
        {
            $('#tender_only_one').removeClass('hidden');
        }

        // $('#customer_proposed_price').html($(this).data('customer_proposed_price'));
        // $('#customer_proposed_files').html('<a href="'+$(this).data('customer_proposed_files')+'">دانلود</a>');
        traverse(document.body);
        $('#show_tender_info').modal('toggle');
    });


    $(document).on('click','#user_propose_price_confirm_btn',function () {

        if (tendder_request_tool_files_count == 0)
        {
            $.notify(
                {
                    message: 'حداقل یک فایل برای تجهیزات مورد استفاده در پروژه انتخاب نمایید.'
                },
                {
                    type: 'danger',
                    placement: {
                        from: "top",
                        align: "left"
                    },
                    showProgressbar : true,
                    mouse_over: "pause",
                    allow_dismiss : false,
                    z_index: 999999999999999,
                }
            );
            return;
        }

        $('#user_propose_price_confirm_btn').find("i").addClass(' icon-check2 fa fa-circle-o-notch fa-spin');
        $('#user_propose_price_confirm_btn').prop('disabled', true);


        $(".ff_fileupload_actions button.ff_fileupload_start_upload ").click();
    });


    $(document).on('click','#request_tender_btn',function () {
        $('#user_propose_price_confirm_btn').attr('data-tender_id',$(this).data('tender_id'));
        $('#tender_tools_advisor').FancyFileUpload({
            url : myData.ajax_url,
            params :
                {
                    action :'tnd_factory_tender_request_tool_file' ,
                    user_id : myData.user_id ,
                    tender_id : $(this).data('tender_id')
                },
            // accept :["png", "jpg", "jpeg", "pdf","doc","docx"],
            maxfilesize : 20000000,
            added : function(e, data)
            {
                tendder_request_tool_files_count++;
                if (tendder_request_tool_files_count > 0)
                {
                    $('.ff_fileupload_dropzone_wrap').hide();
                }
            },
            uploadcompleted :function(e, data)
            {
                if (data.result.success == true)
                {
                    $.notify(
                        {
                            message: 'درخواست شما با موفقیت ثبت گردید!'
                        },
                        {
                            type: 'success',
                            placement: {
                                from: "top",
                                align: "left"
                            },
                            showProgressbar : true,
                            mouse_over: "pause",
                            allow_dismiss : false,
                            z_index: 99999999999999999999,
                        }
                    );

                    $('#user_propose_price_modal').modal('hide');
                    $('#current_tenders_tbl').DataTable().clear().destroy();
                    fetch_data_current_tenders();

                    $('#requested_tenders_tbl').DataTable().clear().destroy();
                    fetch_data_requested_tenders();

                    traverse(document.body);
                    $('#user_propose_price_confirm_btn').find("i").removeClass('fa fa-circle-o-notch fa-spin');
                    $('#user_propose_price_confirm_btn').prop('disabled', false);


                }
                else
                {
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


                    $('#user_propose_price_confirm_btn').find("i").removeClass('fa fa-circle-o-notch fa-spin');
                    $('#user_propose_price_confirm_btn').prop('disabled', false);
                }

            },
            uploadcancelled:function(e, data)
            {
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
                        allow_dismiss : false,
                        z_index: 999999999999999999
                    }
                );

                $('#user_propose_price_confirm_btn').find("i").removeClass('fa fa-circle-o-notch fa-spin');
                $('#user_propose_price_confirm_btn').prop('disabled', false);
            }
        });

        traverse(document.body);
        $('#user_propose_price_modal').modal('toggle');
    });



    $(document).on('click', '.ff_fileupload_actions button.ff_fileupload_remove_file', function(){
        tendder_request_tool_files_count--;
        if (tendder_request_tool_files_count==0)
        {
            $('.ff_fileupload_dropzone_wrap').show();
        }
    });





    $('.tender_price_customer input[type=text]').bind('keyup', function () {

        var value=this.value.replace(/[,]*/g, '');
        var message = $('.tender_price_customer_msg');
        message.text('');
        if (value > 99) {
            message.text(Num2persian(value)+"(ریال)");
        }
    });



    fetch_data_requested_tenders();
    function fetch_data_requested_tenders()
    {
        $('#requested_tenders_tbl').DataTable({
            language: {
                processing:     "در حال پردازش",
                search:         "",
                searchPlaceholder: "جستجو مناقصات",
                lengthMenu:    " _MENU_",
                emptyTable:     "مناقصه ای وجود ندارد",
                zeroRecords:   "مناقصه ای با مشخصات مدنظر شما یافت نشد.",
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
            "serverSide": true,
            "ajax": {
                "url"  : myData.ajax_url+'?action=get_all_requested_factory_tenders_data&user_id='+myData.user_id
            },
            "fnDrawCallback" : function() {
                traverse(document.body);
            }
        } );
    }


    $(document).on('click','#tender_cancel_btn',function () {

        $.post(
            myData.ajax_url,
            {
                action : 'customer_tender_cancel',
                user_id :myData.user_id,
                tender_id:  $(this).data('tender_id'),
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

                    // $('#corp_request_area_tips').html(response.customer_total_request_area+' '+' هکتار');
                    $('#current_tenders_tbl').DataTable().clear().destroy();
                    fetch_data_current_tenders();

                    $('#requested_tenders_tbl').DataTable().clear().destroy();
                    fetch_data_requested_tenders();

                    traverse(document.body);



                }
                else
                {
                    $.notify(
                        {
                            message: 'خطایی رخ داده است'
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




    fetch_data_winned_tenders();
    function fetch_data_winned_tenders()
    {
        $('#winned_tenders_tbl').DataTable({
            language: {
                processing:     "در حال پردازش",
                search:         "",
                searchPlaceholder: "جستجو مناقصات",
                lengthMenu:    " _MENU_",
                emptyTable:     "مناقصه ای وجود ندارد",
                zeroRecords:   "مناقصه ای با مشخصات مدنظر شما یافت نشد.",
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
            "serverSide": true,
            "ajax": {
                "url"  : myData.ajax_url+'?action=get_all_winned_factory_tenders_data&user_id='+myData.user_id
            },
            "fnDrawCallback" : function() {
                traverse(document.body);
            }
        } );
    }




});

