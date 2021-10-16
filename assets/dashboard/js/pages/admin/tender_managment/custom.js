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
                    text: '<i class="icon-plus3 fa-lg "></i> &nbsp;افزودن مناقصه ',
                    className: 'btn btn-outline-success btn-min-width mr-1 mb-1',
                    attr:  {
                        id: 'add_row_tender'
                    }
                }
            ],
            "serverSide": true,
            "ajax": {
                "url"  : myData.ajax_url+'?action=get_all_tenders_data&user_id='+myData.user_id
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
                "url"  : myData.ajax_url+'?action=get_all_archived_tenders_data&user_id='+myData.user_id
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

    $('body').on('click','#add_row_tender',function () {
        $('.ff_fileupload_dropzone_wrap').show();
        $('#add_tender_modal').modal('toggle');
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

    $('.new_tender_area input[type=text]').bind('keyup', function () {
        var value=this.value.replace(/[,]*/g, '');
        var message = $('.new_tender_area_msg');
        message.text('');
            message.text(Num2persian(value)+" (هکتار) ");
    });

    var allFormsData={};
    var add_new_tender_form=$('#add_new_tender');
    var new_tender_applicant_name = $('#new_tender_applicant_name');
    var new_tender_applicant_tel = $('#new_tender_applicant_tel');
    var new_tender_name = $('#new_tender_name');
    var new_tender_date = $('#new_tender_date');
    var new_tender_end_date = $('#new_tender_end_date');
    var new_tender_area = $('#new_tender_area');
    var new_tender_proposed_price = $('#new_tender_proposed_price');
    var new_tender_system_type = $('#new_tender_system_type');
    var new_tender_advisor = $('#new_tender_advisor');
    var new_tender_city = $('#new_tender_city');
    var new_tender_district = $('#new_tender_district');
    var new_tender_only_one = $('#new_tender_only_one');
    var add_new_tender_btn = $('#add_new_tender_btn');
    var tender_files_count=0;

    $('#new_tender_files').FancyFileUpload({
        url : myData.ajax_url,
        params :
            {
                action :'new_tender_upload_files' ,
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
                    action : 'add_info_to_new_tender',
                    new_tender_applicant_name: new_tender_applicant_name.val(),
                    new_tender_applicant_tel: new_tender_applicant_tel.val(),

                    new_tender_name: new_tender_name.val(),
                    new_tender_date: new_tender_date.val(),
                    new_tender_end_date: new_tender_end_date.val(),

                    new_tender_area: new_tender_area.val(),
                    new_tender_proposed_price: new_tender_proposed_price.val(),

                    new_tender_system_type: new_tender_system_type.val(),
                    new_tender_advisor: new_tender_advisor.val(),

                    new_tender_city: new_tender_city.val(),
                    new_tender_district: new_tender_district.val(),
                    new_tender_only_one: new_tender_only_one.val(),

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
            update_corporation_data_btn.prop('disabled', false);
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

        add_new_tender_form.find(".new_tender_name_error").html("").addClass("hidden");
        add_new_tender_form.find(".new_tender_area_error").html("").addClass("hidden");
        add_new_tender_form.find(".new_tender_proposed_price_error").html("").addClass("hidden");
        add_new_tender_form.find(".new_tender_applicant_name_error").html("").addClass("hidden");
        add_new_tender_form.find(".new_tender_applicant_tel_error").html("").addClass("hidden");
        add_new_tender_form.find(".new_tender_system_type_error").html("").addClass("hidden");
        add_new_tender_form.find(".new_tender_advisor_error").html("").addClass("hidden");
        add_new_tender_form.find(".new_tender_city_error").html("").addClass("hidden");
        add_new_tender_form.find(".new_tender_district_error").html("").addClass("hidden");

        allFormsData.new_tender_name = new_tender_name.val();
        allFormsData.new_tender_area = new_tender_area.val();
        allFormsData.new_tender_proposed_price = new_tender_proposed_price.val();

        allFormsData.new_tender_applicant_name = new_tender_applicant_name.val();
        allFormsData.new_tender_applicant_tel = new_tender_applicant_tel.val();
        allFormsData.new_tender_system_type = new_tender_system_type.val();
        allFormsData.new_tender_advisor = new_tender_advisor.val();
        allFormsData.new_tender_city = new_tender_city.val();
        allFormsData.new_tender_district = new_tender_district.val();

        let tender_Data = {
            new_tender_name: allFormsData.new_tender_name,
            new_tender_area: allFormsData.new_tender_area,
            new_tender_proposed_price: allFormsData.new_tender_proposed_price,
            new_tender_applicant_name: allFormsData.new_tender_applicant_name,
            new_tender_applicant_tel: allFormsData.new_tender_applicant_tel,
            new_tender_system_type: allFormsData.new_tender_system_type,
            new_tender_advisor: allFormsData.new_tender_advisor,
            new_tender_district: allFormsData.new_tender_district,
        };

        let rules = {
            new_tender_name: 'required',
            new_tender_area: 'required|numeric',
            new_tender_proposed_price: 'required|numeric',
            new_tender_applicant_name: 'required',
            new_tender_applicant_tel: 'required|mobile',
            new_tender_system_type: 'required',
            new_tender_advisor: 'required',
            new_tender_district: 'required',
        };

        let validation = new Validator(tender_Data, rules);


        if (validation.fails())
        {
            var new_tender_name_validation_errors = validation.errors.get('new_tender_name');
            if (new_tender_name_validation_errors && new_tender_name_validation_errors.length > 0)
            {
                add_new_tender_form.find(".new_tender_name_error").html(validation.errors.errors['new_tender_name'][0]).removeClass("hidden");
                traverse(document.body);
            }

            var new_tender_area_validation_errors = validation.errors.get('new_tender_area');
            if (new_tender_area_validation_errors && new_tender_area_validation_errors.length > 0)
            {
                add_new_tender_form.find(".new_tender_area_error").html(validation.errors.errors['new_tender_area'][0]).removeClass("hidden");
                traverse(document.body);
            }

            var new_tender_proposed_price_validation_errors = validation.errors.get('new_tender_proposed_price');
            if (new_tender_proposed_price_validation_errors && new_tender_proposed_price_validation_errors.length > 0)
            {
                add_new_tender_form.find(".new_tender_proposed_price_error").html(validation.errors.errors['new_tender_proposed_price'][0]).removeClass("hidden");
                traverse(document.body);
            }


            var new_tender_applicant_name_validation_errors = validation.errors.get('new_tender_applicant_name');
            if (new_tender_applicant_name_validation_errors && new_tender_applicant_name_validation_errors.length > 0)
            {
                add_new_tender_form.find(".new_tender_applicant_name_error").html(validation.errors.errors['new_tender_applicant_name'][0]).removeClass("hidden");
                traverse(document.body);
            }



            var new_tender_system_type_validation_errors = validation.errors.get('new_tender_system_type');
            if (new_tender_system_type_validation_errors && new_tender_system_type_validation_errors.length > 0)
            {
                add_new_tender_form.find(".new_tender_system_type_error").html(validation.errors.errors['new_tender_system_type'][0]).removeClass("hidden");
                traverse(document.body);
            }



            var new_tender_applicant_tel_validation_errors = validation.errors.get('new_tender_applicant_tel');
            if (new_tender_applicant_tel_validation_errors && new_tender_applicant_tel_validation_errors.length > 0)
            {
                add_new_tender_form.find(".new_tender_applicant_tel_error").html(validation.errors.errors['new_tender_applicant_tel'][0]).removeClass("hidden");
                traverse(document.body);
            }



            var new_tender_advisor_validation_errors = validation.errors.get('new_tender_advisor');
            if (new_tender_advisor_validation_errors && new_tender_advisor_validation_errors.length > 0)
            {
                add_new_tender_form.find(".new_tender_advisor_error").html(validation.errors.errors['new_tender_advisor'][0]).removeClass("hidden");
                traverse(document.body);
            }



            var new_tender_district_validation_errors = validation.errors.get('new_tender_district');
            if (new_tender_district_validation_errors && new_tender_district_validation_errors.length > 0)
            {
                add_new_tender_form.find(".new_tender_district_error").html(validation.errors.errors['new_tender_district'][0]).removeClass("hidden");
                traverse(document.body);
            }
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

        var tender_id=$(this).data('tender_id');
        var tender_name=$(this).data('tender_name');

        var tender_date=$(this).data('tender_date');
        var tender_date_persian=$(this).data('tender_date_persian');

        var tender_end_date=$(this).data('tender_end_date');
        var tender_date_end_persian=$(this).data('tender_end_date_persian');

        var tender_applicant_name=$(this).data('tender_applicant_name');
        var tender_applicant_tel=$(this).data('tender_applicant_tel');
        var tender_area=$(this).data('tender_area');
        var tender_price=$(this).data('tender_price');
        var tender_system_type=$(this).data('tender_system_type');
        var tender_advisor=$(this).data('tender_advisor');
        var tender_city=$(this).data('tender_city');
        var tender_district=$(this).data('tender_district');
        var tender_files=$(this).data('tender_files');

        $('#edit_tender_applicant_name').val(tender_applicant_name);
        $('#edit_tender_applicant_tel').val(tender_applicant_tel);
        $('#edit_tender_name').val(tender_name);

        $("#edit_tender_date2").val(tender_date_persian);
        $("#edit_tender_date").val(tender_date);

        $("#edit_tender_end_date2").val(tender_date_end_persian);
        $("#edit_tender_end_date").val(tender_end_date);


        $("#edit_tender_area").val(tender_area);
        $('.edit_tender_area_msg').text(Num2persian(tender_area)+" (هکتار) ");

        $("#edit_tender_proposed_price").val(tender_price);
        $('.edit_tender_proposed_price_msg').text(Num2persian(tender_price)+"(ریال)");

        $("#edit_tender_system_type").val(tender_system_type);
        $("#edit_tender_advisor").val(tender_advisor);
        $("#edit_tender_city").val(tender_city);
        $("#edit_tender_district").val(tender_district);

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
                        action : 'edit_tender',
                        edit_tender_applicant_name: $('#edit_tender_applicant_name').val(),
                        edit_tender_applicant_tel: $('#edit_tender_applicant_tel').val(),

                        edit_tender_name: $('#edit_tender_name').val(),
                        edit_tender_date: $('#edit_tender_date').val(),
                        edit_tender_end_date: $('#edit_tender_end_date').val(),
                        edit_tender_area: $('#edit_tender_area').val(),
                        edit_tender_proposed_price: $('#edit_tender_proposed_price').val(),

                        edit_tender_system_type: $('#edit_tender_system_type').val(),
                        edit_tender_advisor: $('#edit_tender_advisor').val(),

                        edit_tender_city: $('#edit_tender_city').val(),
                        edit_tender_district: $('#edit_tender_district').val(),

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
                update_corporation_data_btn.prop('disabled', false);
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

        $('#edit_tender').find(".edit_tender_applicant_name_error").html("").addClass("hidden");
        $('#edit_tender').find(".edit_tender_applicant_tel_error").html("").addClass("hidden");
        $('#edit_tender').find(".edit_tender_name_error").html("").addClass("hidden");
        $('#edit_tender').find(".edit_tender_area_error").html("").addClass("hidden");
        $('#edit_tender').find(".edit_tender_proposed_price_error").html("").addClass("hidden");
        $('#edit_tender').find(".edit_tender_system_type_error").html("").addClass("hidden");
        $('#edit_tender').find(".edit_tender_advisor_error").html("").addClass("hidden");
        $('#edit_tender').find(".edit_tender_district_error").html("").addClass("hidden");

        allFormsData.edit_tender_name = $('#edit_tender_name').val();
        allFormsData.edit_tender_area = $('#edit_tender_area').val();
        allFormsData.edit_tender_proposed_price = $('#edit_tender_proposed_price').val();

        allFormsData.edit_tender_applicant_name = $('#edit_tender_applicant_name').val();
        allFormsData.edit_tender_applicant_tel =$('#edit_tender_applicant_tel') .val();
        allFormsData.edit_tender_system_type = $('#edit_tender_system_type').val();
        allFormsData.edit_tender_advisor =$('#edit_tender_advisor') .val();
        allFormsData.edit_tender_city = $('#edit_tender_city').val();
        allFormsData.edit_tender_district =$('#edit_tender_district') .val();

        let tender_Data = {
            edit_tender_name: allFormsData.edit_tender_name,
            edit_tender_area: allFormsData.edit_tender_area,
            edit_tender_proposed_price: allFormsData.edit_tender_proposed_price,
            edit_tender_applicant_name: allFormsData.edit_tender_applicant_name,
            edit_tender_applicant_tel: allFormsData.edit_tender_applicant_tel,
            edit_tender_system_type: allFormsData.edit_tender_system_type,
            edit_tender_advisor: allFormsData.edit_tender_advisor,
            edit_tender_district: allFormsData.edit_tender_district,
        };

        let rules = {
            edit_tender_name: 'required',
            edit_tender_area: 'required|numeric',
            edit_tender_proposed_price: 'required|numeric',
            edit_tender_applicant_name: 'required',
            edit_tender_applicant_tel: 'required|mobile',
            edit_tender_system_type: 'required',
            edit_tender_advisor: 'required',
            edit_tender_district: 'required',
        };

        let validation = new Validator(tender_Data, rules);


        if (validation.fails())
        {
            var edit_tender_name_validation_errors = validation.errors.get('edit_tender_name');
            if (edit_tender_name_validation_errors && edit_tender_name_validation_errors.length > 0)
            {
                $('#edit_tender').find(".edit_tender_name_error").html(validation.errors.errors['edit_tender_name'][0]).removeClass("hidden");
                traverse(document.body);
            }

            var edit_tender_area_validation_errors = validation.errors.get('edit_tender_area');
            if (edit_tender_area_validation_errors && edit_tender_area_validation_errors.length > 0)
            {
                $('#edit_tender').find(".edit_tender_area_error").html(validation.errors.errors['edit_tender_area'][0]).removeClass("hidden");
                traverse(document.body);
            }

            var edit_tender_proposed_price_validation_errors = validation.errors.get('edit_tender_proposed_price');
            if (edit_tender_proposed_price_validation_errors && edit_tender_proposed_price_validation_errors.length > 0)
            {
                $('#edit_tender').find(".edit_tender_proposed_price_error").html(validation.errors.errors['edit_tender_proposed_price'][0]).removeClass("hidden");
                traverse(document.body);
            }


            var edit_tender_applicant_name_validation_errors = validation.errors.get('edit_tender_applicant_name');
            if (edit_tender_applicant_name_validation_errors && edit_tender_applicant_name_validation_errors.length > 0)
            {
                $('#edit_tender').find(".edit_tender_applicant_name_error").html(validation.errors.errors['edit_tender_applicant_name'][0]).removeClass("hidden");
                traverse(document.body);
            }



            var edit_tender_system_type_validation_errors = validation.errors.get('edit_tender_system_type');
            if (edit_tender_system_type_validation_errors && edit_tender_system_type_validation_errors.length > 0)
            {
                $('#edit_tender').find(".edit_tender_system_type_error").html(validation.errors.errors['edit_tender_system_type'][0]).removeClass("hidden");
                traverse(document.body);
            }



            var edit_tender_applicant_tel_validation_errors = validation.errors.get('edit_tender_applicant_tel');
            if (edit_tender_applicant_tel_validation_errors && edit_tender_applicant_tel_validation_errors.length > 0)
            {
                $('#edit_tender').find(".edit_tender_applicant_tel_error").html(validation.errors.errors['edit_tender_applicant_tel'][0]).removeClass("hidden");
                traverse(document.body);
            }



            var edit_tender_advisor_validation_errors = validation.errors.get('edit_tender_advisor');
            if (edit_tender_advisor_validation_errors && edit_tender_advisor_validation_errors.length > 0)
            {
                $('#edit_tender').find(".edit_tender_advisor_error").html(validation.errors.errors['edit_tender_advisor'][0]).removeClass("hidden");
                traverse(document.body);
            }



            var edit_tender_district_validation_errors = validation.errors.get('edit_tender_district');
            if (edit_tender_district_validation_errors && edit_tender_district_validation_errors.length > 0)
            {
                $('#edit_tender').find(".edit_tender_district_error").html(validation.errors.errors['edit_tender_district'][0]).removeClass("hidden");
                traverse(document.body);
            }
        }
        else
        {
            if ( new_file_reqired)
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
                $('#edit_tender_confirm_btn').find("i").addClass(' icon-check2 fa fa-circle-o-notch fa-spin');
                $('#edit_tender_confirm_btn').prop('disabled', true);

                $('#new_tender_confirm').modal('show')
            }
            else
            {
                $('#edit_tender_confirm_btn').find("i").addClass(' icon-check2 fa fa-circle-o-notch fa-spin');
                $('#edit_tender_confirm_btn').prop('disabled', true);

                $('#edit_tender_confirm').modal('show')
            }
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
                    action : 'edit_tender',
                    edit_tender_applicant_name: $('#edit_tender_applicant_name').val(),
                    edit_tender_applicant_tel: $('#edit_tender_applicant_tel').val(),

                    edit_tender_name: $('#edit_tender_name').val(),
                    edit_tender_date: $('#edit_tender_date').val(),
                    edit_tender_end_date: $('#edit_tender_end_date').val(),
                    edit_tender_area: $('#edit_tender_area').val(),
                    edit_tender_proposed_price: $('#edit_tender_proposed_price').val(),

                    edit_tender_system_type: $('#edit_tender_system_type').val(),
                    edit_tender_advisor: $('#edit_tender_advisor').val(),

                    edit_tender_city: $('#edit_tender_city').val(),
                    edit_tender_district: $('#edit_tender_district').val(),

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
                action : 'get_tender_contributors',
                user_id:  myData.user_id,
                tender_id:  $(this).data('tender_id'),
            },
            function (response)
            {
                if (response.success=='true')
                {
                    alert('saeed');
                    var cssclass="";
                    $('#print_tender_contributors').attr('data-tender_title',response.tender_title);
                    $('#tender_contributers_title').html('');
                    $('#tender_contributers_title').append(response.tender_title);
                    $('#tender_contributors_tbl_tbody ').html('');
                    $.each(response.contributors, function( index, value ) {
                        cssclass="";
                        if (value[8]=='true')
                        {
                            cssclass='bg-danger bg-lighten-3';
                        }


                        $('#tender_contributors_tbl_tbody ').append(' <tr class="'+cssclass+'">\n' +
                            '                                <td class="text-truncate">'+value[0]+'</td>\n' +
                            '                                <td class="text-truncate">'+value[1]+'</td>\n' +
                            '                                <td class="text-truncate">'+value[2]+'</td>\n' +
                            '                                <td class="text-truncate">'+value[3]+'</td>\n' +
                            '                                <td class="text-truncate">'+value[4]+'</td>\n' +
                            '                                <td class="text-truncate">'+value[5]+'</td>\n' +
                            '                                <td class="text-truncate">'+value[6]+'</td>\n'+
                                '<td class="text-truncate"><a href="'+value[7]+'" class="btn btn-warning"><i class="icon-cloud-download3"></i> </a></td>\n' +
                                '                            </tr>');

                        // if (value[6] !='-')
                        // {
                        //     $('#tender_contributors_tbl_tbody ').append('<td class="text-truncate"><a href="'+value[7]+'" class="btn btn-warning"><i class="icon-cloud-download3"></i> </a></td>\n' +
                        //     '                            </tr>');
                        // }
                        // else
                        // {
                        //     $('#tender_contributors_tbl_tbody ').append('<td class="text-truncate">-</td>\n' +
                        //         '                            </tr>');
                        // }
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
                action : 'get_tender_contributors_and_files',
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

                            $('#tender_contributors_and_files_tbl_tbody ').append('saeed');
                            $('#tender_contributors_and_files_tbl_tbody ').append(' <tr class="'+cssclass+'">\n' +
                                '                                <td class="text-truncate">'+value[0]+'</td>\n' +
                                '                                <td class="text-truncate">'+value[1]+'</td>\n' +
                                '                                <td class="text-truncate">'+value[2]+'</td>\n' +
                                '                                <td class="text-truncate">'+value[3]+'</td>\n' +
                                '                                <td class="text-truncate">'+value[4]+'</td>\n' +
                                '                                <td class="text-truncate">'+value[5]+'</td>\n' +
                                '                                <td class="text-truncate">'+value[6]+'</td>\n' +
                                '                                <td class="text-truncate">'+value[7]+'</td>\n' +
                                '                                <td class="text-truncate">'+value[8]+'</td>\n' +
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
                '                                <td class="text-truncate">'+$(this).data('winner_name')+'</td>\n' +
                '                                <td class="text-truncate">'+$(this).data('winner_corp')+'</td>\n' +
                '                                <td class="text-truncate">'+$(this).data('winner_price')+'</td>\n' +
                '                                <td class="text-truncate">'+$(this).data('tender_price')+'</td>\n' +
                '                                <td class="text-truncate"><a href="'+$(this).data('winner_proposed_file')+'" class="btn btn-warning"><i class="icon-cloud-download3"></i> </a></td>\n' +
                '                                <td class="text-truncate"><a href="'+$(this).data('win_confirm_file_link')+'" class="btn btn-warning"><i class="icon-cloud-download3"></i> </a></td>\n' +
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

});

