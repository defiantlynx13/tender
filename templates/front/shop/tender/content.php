<?php $current_user = wp_get_current_user(); ?>
<div class="app-content content container-fluid">
	<div class="content-wrapper">

		<div class="content-body">
			<div class="row">
                <div class="col-xs-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <span class="avatar avatar-online">
                                   مناقصات در حال برگزاری
                                </span>
                            </h4>
                            <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="expand"><i class="fa fa-expand"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body collapse in">
                            <div class="card-block">
                                <table class="table mb-0 text-xs-center" id="current_tenders_tbl" name="current_tenders_tbl">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">اطلاعات مناقصه اصلی(پیمانکار)</th>
                                            <th colspan="3">اطلاعات مناقصه(لوازم و اتصالات)</th>
                                            <th rowspan="2">عملیات</th>
                                        </tr>
                                        <tr>
                                            <th>تاریخ شروع</th>
                                            <th>تاریخ پایان</td>
                                            <th>اسناد</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <span class="avatar avatar-online">
                                    مناقصاتی که شرکت کرده اید.
                                </span>
                            </h4>
                            <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="expand"><i class="fa fa-expand"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body collapse in">
                            <div class="card-block">
                                <table class="table mb-0 text-xs-center" id="requested_tenders_tbl" name="requested_tenders_tbl">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">#</th>
                                            <th rowspan="2">اطلاعات مناقصه اصلی(پیمانکار)</th>
                                            <th colspan="3">اطلاعات مناقصه(لوازم و اتصالات)</th>
                                            <th rowspan="2">فایل پیشنهادی شما</th>
                                            <th rowspan="2">عملیات</th>
                                        </tr>
                                        <tr>
                                            <th>تاریخ شروع</th>
                                            <th>تاریخ پایان</td>
                                            <th>اسناد</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <span class="avatar avatar-online">
                                    مناقصاتی که برنده شده اید.
                                </span>
                            </h4>
                            <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="expand"><i class="fa fa-expand"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body collapse in">
                            <div class="card-block">
                                <table class="table mb-0 text-xs-center" id="winned_tenders_tbl" name="winned_tenders_tbl">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">#</th>
                                            <th rowspan="2">اطلاعات مناقصه اصلی(پیمانکار)</th>
                                            <th colspan="3">اطلاعات مناقصه(لوازم و اتصالات)</th>
                                            <th rowspan="2">فایل پیشنهادی شما</th>
                                            <th rowspan="2">وضعیت پروژه</th>
                                        </tr>
                                        <tr>
                                            <th>تاریخ شروع</th>
                                            <th>تاریخ پایان</td>
                                            <th>اسناد</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="corp_data_update_confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                پس از ارسال اطلاعات شرکت، کارشناسان ما اقدام به بررسی اطلاعات ارسالی شما می نمایند.
                پس از تایید اطلاعات توسط کارشناس مربوطه ، می توانید در مناقصات شرکت نمایید.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="corp_data_update_confirm_cancel_btn">انصراف</button>
                <button type="button" class="btn btn-primary" id="corp_data_update_confirm_ok_btn">تایید</button>
            </div>
        </div>
    </div>
</div>


<!-- show tender info -->
<div class="modal fade" id="show_tender_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="z-index: 9999999999">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="user_other_data_modal_name"> جزئیات مناقصه</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <tbody>
                        <tr>
                            <td class="text-truncate">مساحت</td>
                            <td class="text-truncate" id="tender_info_area"></td>
                        </tr>
                        <tr>
                            <td class="text-truncate">هزینه برآورد</td>
                            <td class="text-truncate" id="tender_info_price"></td>
                        </tr>
                        <tr>
                            <td class="text-truncate">نوع سیستم</td>
                            <td class="text-truncate" id="tender_info_system_type"></td>
                        </tr>
                        <tr>
                            <td class="text-truncate">مشاور طرح</td>
                            <td class="text-truncate" id="tender_info_advisor"></td>
                        </tr>
                        <tr>
                            <td class="text-truncate">شهرستان</td>
                            <td class="text-truncate" id="tender_info_city"></td>
                        </tr>
                        <tr>
                            <td class="text-truncate">محل اجرا</td>
                            <td class="text-truncate" id="tender_info_district"></td>
                        </tr>
                        <tr>
                            <td class="text-truncate">فایل های مناقصه</td>
                            <td class="text-truncate" id="tender_info_files"></td>
                        </tr>
                        </tbody>
                        <tfoot id="tender_only_one" class="hidden">
                            <tr>
                                <td colspan="2" class="text-xs-center" style="color: white;background-color: red">این مناقصه با شرکت حتی یک پیمانکار برگزار خواهد شد!</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <br>
<!--                <b> پیشنهاد شما</b>-->
<!--                <br>-->
<!--                <div class="table-responsive">-->
<!--                    <table class="table table-hover mb-0">-->
<!--                        <tbody>-->
<!--                        <tr>-->
<!--                            <td class="text-truncate">قیمت پیشنهادی شما</td>-->
<!--                            <td class="text-truncate" id="customer_proposed_price"></td>-->
<!--                        </tr>-->
<!---->
<!--                        <tr>-->
<!--                            <td class="text-truncate">فایل تجهیزات پیشنهادی شما</td>-->
<!--                            <td class="text-truncate" id="customer_proposed_files"></td>-->
<!--                        </tr>-->
<!--                        </tbody>-->
<!--                    </table>-->
<!--                </div>-->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>


<!-- user propose price modal -->
<div class="modal fade" id="user_propose_price_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="z-index: 9999999999">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="user_other_data_modal_name"> شرکت در مناقصه</h4>
            </div>
            <div class="modal-body">
                <form class="form" name="customer_propose_form" id="customer_propose_form">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="tender_tools_advisor">فایل تجهیزات و لوازم </label>
                                    <input type="file" id="tender_tools_advisor" name="tender_tools_advisor"/>
                                    <div class="tender_tools_advisor_msg"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions right">
                        <button type="button" class="btn btn-outline-green tnd_round" name="user_propose_price_confirm_btn" id="user_propose_price_confirm_btn">
                            <i class="icon-check2"></i>شرکت در مناقصه
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
