jQuery(document).ready(function($){


        $('body').tooltip({
            selector : '[data-toggle="tooltip"]'
        });

    Validator.useLang('fa');
    Validator.register('mobile', function (val) {
        return val.match(/^09[0-9]{9}$/g);
    }, 'شماره موبایل اشتباه است');
    fetch_data();
    function fetch_data()
    {
        $('#tender_managment_tbl').DataTable({
            "pageLength":10,
            "lengthMenu": [[5,10, 25, -1], [5,10, 25, "همه"]],
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
            bFilter :false,
            "dom": "Btp",
            buttons: [
                {
                    text: '<i class="fa fa-plus "></i> &nbsp;افزودن مناقصه ',
                    className: 'btn btn-outline-success btn-min-width mr-1 mb-1',
                    attr:  {
                        id: 'add_row_tender'
                    }
                }
            ],
            "serverSide": true,
            "ajax": {
                "url"  : myData.ajax_url+'?action=get_all_shop_tenders_data&user_id='+myData.user_id
            },
            "rowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                if ( aData[9] == "true" )
                {
                    if (aData[10] == 'not_executed')
                    {
                        $('td:eq(0)', nRow).addClass('bg-warning');
                    }
                    else if(aData[10] == 'executed')
                    {
                        $('td:eq(0)', nRow).addClass('bg-primary');
                    }
                    else
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



    fetch_archived_tenders_data();
    function fetch_archived_tenders_data()
    {
        $('#archived_tenders_tbl').DataTable({
            language: {
                processing:     "در حال پردازش",
                search:         "",
                searchPlaceholder: "جستجو کاربران",
                lengthMenu:    " _MENU_",
                emptyTable:     "کاربری وجود ندارد",
                zeroRecords:   "کاربری با مشخصات مدنظر شما یافت نشد.",
                info: "",
                infoEmpty: "",
                infoFiltered: "",
                search: "_INPUT_",
                searchPlaceholder: "جستجو ... ",
                paginate: {
                    previous: "بعدی",
                    next: "قبلی",
                    last : "»",
                    first : "«"
                }
            },
            bPaginate: false,
            pageLength:20,
            serverSide: true,
            ajax: {
                "url"  : myData.ajax_url+'?action=get_all_shop_archived_tenders_data&user_id='+myData.user_id
            },
            rowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                if ( aData[8] == "true" )
                {
                    if (aData[9] == 'not_executed')
                    {
                        $('td:eq(0)', nRow).addClass('bg-warning');
                    }
                    else if(aData[9] == 'executed')
                    {
                        $('td:eq(0)', nRow).addClass('bg-primary');
                    }
                    else
                    {
                        $('td:eq(0)', nRow).addClass('bg-success');
                    }
                }
                return nRow;

            },
            fnDrawCallback : function() {
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


    var contractors_executed_tenders=tail.select("#new_parent_tender_name", {
        locale: "fa",
        width: 400,
        search: true,
        multiple:false,
        startOpen: true,
    });

    $('body').on('click','#add_row_tender',function () {
        $.post(
            myData.ajax_url,
            {
                action : 'get_contractors_executed_tenders',
                user_id:  myData.user_id,
            },
            function (response)
            {
                if (response.status == 'true')
                {
                    $("#new_parent_tender_name").empty();
                    contractors_executed_tenders.disable(false);
                    $('#new_parent_tender_name').append('<option value="0">انتخاب کنید</option>');
                    for (var index = 0; index < response.result.length; index++)
                    {
                        var excuted_tenders =  response.result[index];
                        $('#new_parent_tender_name').append('<option value="' + excuted_tenders.id + '">' + excuted_tenders.name + '</option>');
                    }

                    contractors_executed_tenders.reload();
                    contractors_executed_tenders.updateLabel('یکی از مناقصات پیمانکاران را انتخاب نمایید');
                    contractors_executed_tenders.options.unselect('0');
                    contractors_executed_tenders.options.disable('0');
                    contractors_executed_tenders.enable(false);

                    $('.ff_fileupload_dropzone_wrap').show();
                    $('#add_tender_modal').modal('toggle');
                }
                else
                {
                    $.notify(
                        {
                            message: 'خطایی امنیتی رخ داده است'
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

    $("#new_tender_date2").pDatepicker({

        "autoClose": true,
        "altField": "#new_tender_date",
        "format" : "DD MMMM YYYY ",
        "altFieldFormatter" :function (unixDate) {
            var self = this;
            var pd = new persianDate(unixDate).toCalendar('gregorian').format('X');
            return pd;
        }
    });

    $("#new_tender_end_date2").pDatepicker({

        "autoClose": true,
        "altField": "#new_tender_end_date",
        "format" : "DD MMMM YYYY ",
        "altFieldFormatter" :function (unixDate) {
            var self = this;
            var pd = new persianDate(unixDate).toCalendar('gregorian').format('X');
            return pd;
        }
    });

    $('.new_tender_proposed_price input[type=text]').bind('keyup', function () {
        var value=this.value.replace(/[,]*/g, '');
        var message = $('.new_tender_proposed_price_msg');
        message.text('');
            message.text(Num2persian(value)+"(ریال)");

    });

    var allFormsData={};
    var add_new_tender_form=$('#add_new_tender');
    var new_parent_tender_id = $('#new_parent_tender_name');
    var new_tender_date = $('#new_tender_date');
    var new_tender_end_date = $('#new_tender_end_date');
    var new_tender_only_one = $('#new_tender_only_one');
    var add_new_tender_btn = $('#add_new_tender_btn');
    var tender_files_count=0;

    $('#new_tender_files').FancyFileUpload({
        url : myData.ajax_url,
        params :
            {
                action :'new_shop_tender_upload_files' ,
                user_id : myData.user_id ,
            },
        // accept :["png", "jpg", "jpeg", "pdf","doc","docx"],
        maxfilesize : 20000000,
        added : function(e, data)
        {
            tender_files_count++;
            if (tender_files_count > 0)
            {
                $('.ff_fileupload_dropzone_wrap').hide();
            }
        },
        uploadcompleted :function(e, data)
        {
            $.post(
                myData.ajax_url,
                {
                    action : 'add_info_to_new_shop_tender',
                    new_parent_tender_id: new_parent_tender_id.val(),
                    new_tender_date: new_tender_date.val(),
                    new_tender_end_date: new_tender_end_date.val(),
                    new_tender_only_one: (new_tender_only_one.prop('checked') === true)?'yes':'no',

                    user_id:  myData.user_id,
                    tender_id:  data.result.error,

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

                        $('#add_tender_modal').modal('hide');

                        $('#tender_managment_tbl').DataTable().clear().destroy();
                        fetch_data();
                        add_new_tender_btn.find("i").removeClass('fa fa-circle-o-notch fa-spin');
                        add_new_tender_btn.prop('disabled', false);

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
            add_new_tender_btn.find("i").removeClass('fa fa-circle-o-notch fa-spin');
        }
    });

    $(document).on('click', '.ff_fileupload_actions button.ff_fileupload_remove_file', function(){
        tender_files_count--;
        if (tender_files_count==0)
        {
            $('.ff_fileupload_dropzone_wrap').show();
        }
    });

    add_new_tender_btn.on('click',function (event) {

        add_new_tender_form.find(".new_parent_tender_name_error").html("").addClass("hidden");

        allFormsData.new_parent_tender_id = new_parent_tender_id.val();
        if ( allFormsData.new_parent_tender_id === "" || allFormsData.new_parent_tender_id == 0)
        {
            add_new_tender_form.find(".new_parent_tender_name_error").html('انتخاب مناقصه پیانکاری برای مناقصه فروشگاه الزامی است!').removeClass("hidden");
            traverse(document.body);
        }
        else
        {
            if (tender_files_count == 0)
            {
                $.notify(
                    {
                        message: 'حداقل یک مدرک انتخاب نمایید.'
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
                        z_index: 9999,
                    }
                );
                return;
            }

            add_new_tender_btn.find("i").addClass(' icon-check2 fa fa-circle-o-notch fa-spin');
            add_new_tender_btn.prop('disabled', true);

            $('#new_tender_confirm').modal('show')


        }

    });

    $('#new_tender_confirm_ok_btn').on('click',function (event) {
        $('#new_tender_confirm').modal('hide');
        $(".ff_fileupload_actions button.ff_fileupload_start_upload ").click();
    });

    $('#new_tender_confirm_cancel_btn').on('click',function (event) {
        var add_new_tender_btn = $('#add_new_tender_btn');
        add_new_tender_btn.find("i").removeClass('fa fa-circle-o-notch fa-spin');
        add_new_tender_btn.prop('disabled', false);
    });



    $("#edit_tender_date2").pDatepicker({
        "autoClose": true,
        "initialValue" : true,
        "initialValueType": 'persian',
        "altField": "#edit_tender_date",
        "format" : "DD MMMM YYYY ",
        "altFieldFormatter" :function (unixDate) {
            var self = this;
            var pd = new persianDate(unixDate).toCalendar('gregorian').format('X');
            return pd;
        }
    });
    $("#edit_tender_end_date2").pDatepicker({
        "autoClose": true,
        "initialValue" : true,
        "initialValueType": 'persian',
        "altField": "#edit_tender_end_date",
        "format" : "DD MMMM YYYY ",
        "altFieldFormatter" :function (unixDate) {
            var self = this;
            var pd = new persianDate(unixDate).toCalendar('gregorian').format('X');
            return pd;
        }
    });

    $('.edit_tender_area input[type=text]').bind('keyup', function () {
        var value=this.value.replace(/[,]*/g, '');
        var message = $('.edit_tender_area_msg');
        message.text('');
        if (value > 99) {
            message.text(Num2persian(value)+" (هکتار) ");
        }
    });


    $('.edit_tender_proposed_price input[type=text]').bind('keyup', function () {
        var value=this.value.replace(/[,]*/g, '');
        var message = $('.edit_tender_proposed_price_msg');
        message.text('');
        if (value > 99) {
            message.text(Num2persian(value)+"(ریال)");
        }
    });

    $(document).on('click','#tender_edit_btn',function (event) {

        $('#edit_tender_confirm_btn').find("i").removeClass('fa fa-circle-o-notch fa-spin');
        $('#edit_tender_confirm_btn').prop('disabled', false);

        var tender_id=$(this).data('tender_id');
        var parent_tender_id=$(this).data('parent_tender_id');


        var contractors_executed_tenders=tail.select("#edit_parent_tender_name", {
            locale: "fa",
            width: 400,
            search: true,
            multiple:false,
            startOpen: true,
        });

        $.post(
            myData.ajax_url,
            {
                action : 'edit_get_contractors_executed_tenders',
                user_id:  myData.user_id,
                tender_id:  tender_id,
            },
            function (response)
            {
                contractors_executed_tenders_lable='یکی از مناقصات پیمانکاران را انتخاب نمایید';
                if (response.status == 'true')
                {
                    $("#edit_parent_tender_name").empty();
                    contractors_executed_tenders.disable(false);
                    for (var index = 0; index < response.result.length; index++)
                    {
                        var excuted_tenders =  response.result[index];
                        $('#edit_parent_tender_name').append('<option value="0">انتخاب کنید</option>');
                        if (excuted_tenders.id == parent_tender_id)
                        {
                            $('#edit_parent_tender_name').append('<option value="' + excuted_tenders.id + '" selected>' + excuted_tenders.name + '</option>');
                            contractors_executed_tenders_lable=excuted_tenders.name;
                        }
                        else
                        {
                            $('#edit_parent_tender_name').append('<option value="' + excuted_tenders.id + '">' + excuted_tenders.name + '</option>');
                        }
                    }

                    contractors_executed_tenders.reload();
                    contractors_executed_tenders.updateLabel(contractors_executed_tenders_lable);
                    contractors_executed_tenders.enable(false);
                }
            });
        var tender_date=$(this).data('tender_date');
        var tender_date_persian=$(this).data('tender_date_persian');

        var tender_end_date=$(this).data('tender_end_date');
        var tender_date_end_persian=$(this).data('tender_end_date_persian');

        var tender_only_one=$(this).data('tender_only_one');

        var tender_files=$(this).data('tender_files');

        $("#edit_tender_date2").val(tender_date_persian);
        $("#edit_tender_date").val(tender_date);

        $("#edit_tender_end_date2").val(tender_date_end_persian);
        $("#edit_tender_end_date").val(tender_end_date);

        (tender_only_one == 'yes')?$('#edit_tender_only_one').prop( "checked", true ):'';

        $('.edit_files_download_link').html('                 <div class="alert alert-grey alert-dismissible fade in mb-2" id="edit_tender_files_alert" role="alert">\n' +
            '                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
            '                                            <span aria-hidden="true">×</span>\n' +
            '                                        </button>\n' +
            '                                        <strong><a href="#" id="edit_tender_files" class="alert-link">دانلود</a> </strong>\n' +
            '                                    </div>');
        $("#edit_tender_files").attr("href", tender_files);

        $("#edit_tender_confirm_btn").val(tender_id);
        $('#edit_tender_confirm_ok_btn').val(tender_id);

        $('#edit_tender_new_file').FancyFileUpload({
            url : myData.ajax_url,
            params :
                {
                    action :'edit_tender_upload_new_files' ,
                    user_id : myData.user_id ,
                    tender_id : tender_id ,
                },
            // accept :["png", "jpg", "jpeg", "pdf","doc","docx"],
            maxfilesize : 20000000,
            added : function(e, data)
            {
                edit_tender_files_count++;
                if (edit_tender_files_count > 0)
                {
                    $('.ff_fileupload_dropzone_wrap').hide();
                }
            },
            uploadcompleted :function(e, data)
            {
                $.post(
                    myData.ajax_url,
                    {
                        action : 'edit_shop_tender',
                        edit_parent_tender_id: $('#edit_parent_tender_name').val(),
                        edit_tender_date: $('#edit_tender_date').val(),
                        edit_tender_end_date: $('#edit_tender_end_date').val(),

                        edit_tender_only_one: ($('#edit_tender_only_one').prop('checked') === true)?'yes':'no',
                        user_id:  myData.user_id,
                        tender_id:  tender_id,
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

                            $('#edit_tender_modal').modal('hide');
                            $('#tender_managment_tbl').DataTable().destroy();
                            fetch_data();
                            $('#edit_tender_confirm_btn').find("i").removeClass('fa fa-circle-o-notch fa-spin');
                            $('#edit_tender_confirm_btn').prop('disabled', false);

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
                add_new_tender_btn.find("i").removeClass('fa fa-circle-o-notch fa-spin');
            }
        });
        $("#edit_tender_new_file_section").hide();
        $('#edit_tender_modal').modal('toggle');

    });


    var show_edit_tender_new_file_section=false;
    var edit_tender_files_count=0;
    var new_file_reqired=false;

    $(document).on('close.bs.alert','#edit_tender_files_alert', function () {
        $("#edit_tender_new_file_section").show();
        show_edit_tender_new_file_section=true;
        var edit_tender_files_count=0;
        new_file_reqired=true;
    });



    $(document).on('click', '.ff_fileupload_actions button.ff_fileupload_remove_file', function(){
        edit_tender_files_count--;
        if (edit_tender_files_count==0)
        {
            $('.ff_fileupload_dropzone_wrap').show();
        }
    });



    $('#edit_tender_confirm_btn').on('click',function (event) {

        $('#edit_tender').find(".edit_parent_tender_name_error").html("").addClass("hidden");

        edit_parent_tender_id = $('#edit_parent_tender_name').val();

        if (edit_parent_tender_id === "" || edit_parent_tender_id == 0)
        {
            $('#edit_tender').find(".edit_parent_tender_name_error").html('انتخاب مناقصه پیانکاری برای مناقصه فروشگاه الزامی است!').removeClass("hidden");
            traverse(document.body);
        }
        else
        {
            if (new_file_reqired)
            {
                if (edit_tender_files_count == 0)
                {
                    $.notify(
                        {
                            message: 'حداقل یک مدرک انتخاب نمایید.'
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
                            z_index: 9999,
                        }
                    );
                    return;
                }
            }
            $('#edit_tender_confirm_btn').find("i").addClass(' icon-check2 fa fa-circle-o-notch fa-spin');
            $('#edit_tender_confirm_btn').prop('disabled', true);

            $('#edit_tender_confirm').modal('show');
        }
    });


    $('#edit_tender_confirm_ok_btn').on('click',function (event) {
        $('#edit_tender_confirm').modal('hide');
        if (new_file_reqired)
        {
            $(".ff_fileupload_actions button.ff_fileupload_start_upload ").click();
        }
        else
        {
            $.post(
                myData.ajax_url,
                {
                    action : 'edit_shop_tender',
                    edit_parent_tender_id: $('#edit_parent_tender_name').val(),
                    edit_tender_date: $('#edit_tender_date').val(),
                    edit_tender_end_date: $('#edit_tender_end_date').val(),

                    edit_tender_only_one: ($('#edit_tender_only_one').prop('checked') === true)?'yes':'no',
                    user_id:  myData.user_id,
                    tender_id:  $(this).val(),
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

                        $('#edit_tender_modal').modal('hide');
                        $('#tender_managment_tbl').DataTable().destroy();
                        fetch_data();
                        $('#edit_tender_confirm_btn').find("i").removeClass('fa fa-circle-o-notch fa-spin');
                        $('#edit_tender_confirm_btn').prop('disabled', false);

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
        }

    });

    $('#edit_tender_confirm_cancel_btn').on('click',function (event) {
        $('#edit_tender_confirm_btn').find("i").removeClass('fa fa-circle-o-notch fa-spin');
        $('#edit_tender_confirm_btn').prop('disabled', false);
    });


    $(document).on('click','#tender_info_btn',function () {
        $('#tender_only_one').addClass('hidden');

        $('#main_tender_start_date').html($(this).data('tender_start_date'));
        $('#main_tender_end_date').html($(this).data('tender_end_date'));
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

        traverse(document.body);
        $('#show_tender_info').modal('toggle');
    });
    
    $(document).on('click','#tender_contributors_btn',function () {

        $.post(
            myData.ajax_url,
            {
                action : 'get_shop_tender_contributors',
                user_id:  myData.user_id,
                tender_id:  $(this).data('tender_id'),
            },
            function (response)
            {
                if (response.success=='true')
                {
                    var cssclass="";
                    $('#print_tender_contributors').attr('data-tender_title',response.tender_title);
                    $('#shop_tender_contributers_title').html('');
                    $('#shop_tender_contributers_title').append(response.tender_title);
                    $('#tender_contributors_tbl_tbody').html('');
                    $.each(response.contributors, function( index, value ) {
                        cssclass="";
                        $('#tender_contributors_tbl_tbody ').append(' <tr class="'+cssclass+'">\n' +
                            '                                <td class="text-truncate">'+value[0]+'</td>\n' +
                            '                                <td class="text-truncate">'+value[1]+'</td>\n' +
                            '                                <td class="text-truncate">'+value[2]+'</td>\n' +
                            '<td class="text-truncate hidden-print"><a href="'+value[3]+'" class="btn btn-warning"><i class="icon-cloud-download3"></i> </a></td>\n' +
                            '                            </tr>');


                    });
                    traverse(document.body);
                    $('#show_tender_contributors').modal('toggle');
                }
            });


    });

    $(document).on('click','#run_tender_btn',function () {

        $.post(
            myData.ajax_url,
            {
                action : 'get_shop_tender_contributors_and_files',
                user_id:  myData.user_id,
                tender_id:  $(this).data('tender_id'),
            },
            function (response)
            {

                if (response.success=='true')
                {

                    if (response.contributors == undefined)
                    {
                            $.notify(
                                {
                                    message: 'شرکتی در این مناقصه شرکت نکرده است!'
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
                    else
                    {
                        $('#tender_contributors_and_files_tbl_tbody ').html('');
                        $.each(response.contributors, function( index, value ) {
                            var cssclass="";
                            if (value[9]=='true')
                            {
                                cssclass='bg-danger bg-lighten-3';

                            }

                            $('#tender_contributors_and_files_tbl_tbody ').append(' <tr class="'+cssclass+'">\n' +
                                '                                <td class="text-truncate">'+value[0]+'</td>\n' +
                                '                                <td class="text-truncate">'+value[1]+'</td>\n' +
                                '                                <td class="text-truncate">'+value[2]+'</td>\n' +
                                '                                <td class="text-truncate">'+value[3]+'</td>\n' +
                                '                                <td class="text-truncate">'+value[4]+'</td>\n' +
                                '                            </tr>');
                        });
                        traverse(document.body);
                        $('#show_tender_contributors_and_files').modal('toggle');
                    }

                }
            });

        // $.post(
        //     myData.ajax_url,
        //     {
        //         action : 'run_tender',
        //         user_id:  myData.user_id,
        //         tender_id: $(this).data('tender_id'),
        //     },
        //     function (response)
        //     {
        //         if (response.status == 'ok')
        //         {
        //             $.notify(
        //                 {
        //                     message: response.msg
        //                 },
        //                 {
        //                     type: 'success',
        //                     placement: {
        //                         from: "top",
        //                         align: "left"
        //                     },
        //                     showProgressbar : true,
        //                     mouse_over: "pause",
        //                     allow_dismiss : false
        //                 }
        //             );
        //
        //             $('#tender_managment_tbl').DataTable().destroy();
        //             fetch_data();
        //         }
        //         else
        //         {
        //             $.notify(
        //                 {
        //                     message: response.msg
        //                 },
        //                 {
        //                     type: 'danger',
        //                     placement: {
        //                         from: "top",
        //                         align: "left"
        //                     },
        //                     showProgressbar : true,
        //                     mouse_over: "pause",
        //                     allow_dismiss : false
        //                 }
        //             );
        //         }
        //     });

    });

    $(document).on('click','#show_winner_btn',function () {
        $('#show_tender_winner_tbl_tbody ').html('');
            $('#show_tender_winner_tbl_tbody ').append(' <tr>\n' +
                '                                <td class="text-truncate">'+$(this).data('winner_shop_name')+'</td>\n' +
                '                                <td class="text-truncate">'+$(this).data('winner_shop_manager_name')+'</td>\n' +
                '                                <td class="text-truncate">'+$(this).data('winner_shop_tel')+'</td>\n' +
                '                                <td class="text-truncate"><a href="'+$(this).data('winner_shop_proposed_file')+'" class="btn btn-warning"><i class="icon-cloud-download3"></i> </a></td>\n' +
                '                                <td class="text-truncate"><a href="'+$(this).data('winner_confirm_file_link')+'" class="btn btn-warning"><i class="icon-cloud-download3"></i> </a></td>\n' +
                '                            </tr>');

        traverse(document.body);
        $('#show_tender_winner').modal('toggle');
    });


    $(document).on('click','#finish_tender_btn',function () {
        $.post(
            myData.ajax_url,
            {
                action : 'finish_tender',
                user_id:  myData.user_id,
                tender_id: $(this).data('tender_id'),
            },
            function (response)
            {
                if (response.status == 'ok')
                {
                    $.notify(
                        {
                            message: response.msg
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

                    $('#tender_managment_tbl').DataTable().destroy();
                    fetch_data();

                }
                else
                {
                    $.notify(
                        {
                            message: response.msg
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



    $(document).on('click','#archive_tender_btn',function () {
        $.post(
            myData.ajax_url,
            {
                action : 'archive_tender',
                user_id:  myData.user_id,
                tender_id: $(this).data('tender_id'),
            },
            function (response)
            {
                if (response.status == 'ok')
                {
                    $.notify(
                        {
                            message: response.msg
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

                    $('#tender_managment_tbl').DataTable().clear().destroy();
                    fetch_data();

                    $('#archived_tenders_tbl').DataTable().clear().destroy();
                    fetch_archived_tenders_data();
                }
                else
                {
                    $.notify(
                        {
                            message: response.msg
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

    
    $(document.body).on('click','#tender_winer_btn',function () {
        $('#tender_winner_confirm_modal_btn').find("i").removeClass('fa fa-circle-o-notch fa-spin');
        $('#tender_winner_confirm_modal_btn').prop('disabled', false);
        $('#show_tender_contributors_and_files').modal('hide');
        $('#tender_winner_confirm_modal_btn').attr('data-customer_id', $(this).data('customer_id'));
        $('#tender_winner_confirm_modal_btn').attr('data-tender_id', $(this).data('tender_id'));
        $('#tender_winner_confirm_file').FancyFileUpload({
            url : myData.ajax_url,
            params :
                {
                    action :'tender_winner_confirm_file' ,
                    user_id : myData.user_id ,
                    customer_id : $(this).data('customer_id'),
                    tender_id : $(this).data('tender_id'),
                },
            // accept :["png", "jpg", "jpeg", "pdf","doc","docx"],
            maxfilesize : 20000000,
            added : function(e, data)
            {
                tender_files_count++;
                if (tender_files_count > 0)
                {
                    $('.ff_fileupload_dropzone_wrap').hide();
                }
            },
            uploadcompleted :function(e, data)
            {
                $('#tender_winner_confirm_modal').modal('hide');
                $('#tender_winner_confirm_modal_btn').find("i").removeClass('fa fa-circle-o-notch fa-spin');
                $('#tender_winner_confirm_modal_btn').prop('disabled', false);
                $.notify(
                    {
                        message: data.result.msg
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
                        z_index:9999999999999
                    }
                );

                $('#tender_managment_tbl').DataTable().clear().destroy();
                fetch_data();
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
                        allow_dismiss : false,
                        z_index:9999999999999
                    }
                );

                $.notify(
                    {
                        message: 'مناقصه برگزار نشد'
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
                        z_index:9999999999999
                    }
                );
                $('#tender_winner_confirm_modal').modal('hide');
                $('#tender_winner_confirm_modal_btn').find("i").removeClass('fa fa-circle-o-notch fa-spin');
                $('#tender_winner_confirm_modal_btn').prop('disabled', false);
            }
        });

        $('#tender_winner_confirm_modal').modal('toggle');
    });

    $(document.body).on('click','#tender_winner_confirm_modal_btn',function ()
    {
        if (tender_files_count == 0)
        {
            $.notify(
                {
                    message: 'حداقل یک مدرک انتخاب نمایید.'
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
                    z_index: 99999999999999999999,
                }
            );
            return;
        }
        $('#tender_winner_confirm_modal_btn').find("i").addClass('fa fa-circle-o-notch fa-spin');
        $('#tender_winner_confirm_modal_btn').prop('disabled', true);
        $(".ff_fileupload_actions button.ff_fileupload_start_upload ").click();
    });


    $('#print_tender_contributors').on('click',function (event) {
        $('#tender_contributors_tbl').printThis({
            header: "<h1>"+$(this).data('tender_title')+"</h1>",
            importStyle: true,
            removeScripts: true,       // remove script tags from print content
        });
    });



    $(document).on('click','#delete_shop_tender_btn',function () {
        $('#delete_tender_confirm_ok_btn').removeData('data-tender_id');
        var tender_id=$(this).data('tender_id');
        $('#delete_tender_confirm_ok_btn').attr('data-tender_id', tender_id);
        $('#delete_tender_confirm').modal('show');
    });

    $('#delete_tender_confirm_ok_btn').on('click',function (event) {
        $('#delete_tender_confirm').modal('hide');
        $.post(
            myData.ajax_url,
            {
                action : 'delete_shop_tender',
                user_id:  myData.user_id,
                tender_id: $(this).data('tender_id'),
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

                    $('#tender_managment_tbl').DataTable().clear().destroy();
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

});

