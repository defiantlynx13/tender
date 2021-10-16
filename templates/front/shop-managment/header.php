<html lang="en" data-textdirection="rtl" class="loading">

<head>
    <?php echo wp_head();?>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">



    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo $dash_assets_url.'css_rtl/bootstrap.css'; ?>">
    <!-- font icons-->

    <!--    data table -->
    <link rel="stylesheet" type="text/css" href="<?php echo $dash_assets_url.'vendors/js/data-table/datatables.min.css'; ?>">
    <!--    end data table -->
    <link rel="stylesheet" type="text/css" href="<?php echo $dash_assets_url.'fonts/icomoon.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $dash_assets_url.'fonts/flag-icon-css/css/flag-icon.min.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $dash_assets_url.'vendors/css/extensions/pace.css'; ?>">
    <!-- END VENDOR CSS-->
    <!-- BEGIN ROBUST CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo $dash_assets_url.'css_rtl/bootstrap-extended.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $dash_assets_url.'css_rtl/app.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $dash_assets_url.'css_rtl/colors.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $dash_assets_url.'css_rtl/custom-rtl.css'; ?>">
    <!-- END ROBUST CSS-->
    <!-- BEGIN Page Level CSS-->

    <link rel="stylesheet" type="text/css" href="<?php echo $dash_assets_url.'css_rtl/core/menu/menu-types/vertical-menu.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $dash_assets_url.'css_rtl/core/menu/menu-types/vertical-overlay-menu.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $dash_assets_url.'css_rtl/pages/login-register.css'; ?>">

    <!-- BEGIN Custom font-->
    <link rel="stylesheet" type="text/css" href="<?php echo $dash_assets_url.'css_rtl/login-style.css'; ?>">
    <!-- END Custom Font-->

    <link rel="stylesheet" type="text/css" href="<?php echo  $dash_assets_url.'css_rtl/animate.min.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $dash_assets_url.'js/fontawesome/css/all.min.css'; ?>">
    <!--    <link rel="stylesheet" type="text/css" href="--><?php //echo Assets::fonts('font-awesome-animated/tender-font-awesome.min.css'); ?><!--">-->

    <link rel="stylesheet" type="text/css" href="<?php echo $dash_assets_url.'css_rtl/bootstrap-select.min.css'; ?>">


    <!-- BEGIN image upload-->
    <link rel="stylesheet" type="text/css" href="<?php echo  $dash_assets_url.'css_rtl/file-upload/fancy_fileupload.css'; ?>">
    <!-- END image upload-->

    <link rel="stylesheet" type="text/css" href="<?php echo $dash_assets_url.'fonts/style-font-login.css'; ?>">

    <style type="text/css">

        [data-notify="progressbar"] {
            margin-bottom: 0px;
            position: absolute;
            bottom: 0px;
            left: 0px;
            width: 100%;
            height: 5px;
        }
        [data-notify="dismiss"] {
            float: right;
        }

        table{
            font-family: IRANSansWeb !important;
        }
        table td{
            font-size: 12px;
        }
        table th{
            font-size: 14px;
        }
        table td, table th{
            border: none;
        }

        .table thead,.table th {text-align: center;}
        .table thead th {
            vertical-align: middle;
        }
        .table th, .table td
        {
            vertical-align: middle;
        }

        #user_managment_tbl_filter,#tender_managment_tbl_filter,#archived_tenders_tbl_filter{
            margin:0px 15px;
            float: left;
            font-size: 25px;
        }
        #user_managment_tbl_length,#tender_managment_tbl_length,#archived_tenders_tbl_filter{
            margin:0px 15px;
            font-size: 25px;
        }


        .dt-buttons{
            margin: 0px 15px;
        }

        .datepicker-plot-area{
            font-family: IRANSansWeb;
        }

        .ff_fileupload_wrap .ff_fileupload_dropzone{
            height: 100px;
        }

        .ff_fileupload_actions button.ff_fileupload_start_upload
        {
            display: none !important;
        }
    </style>

</head>
<body data-open="click" data-menu="vertical-menu" data-col="2-columns" class="vertical-layout vertical-menu 2-columns  fixed-navbar">
