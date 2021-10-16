
<div class="app-content content container-fluid">
    <div class="content-wrapper">

        <div class="content-body"><!-- stats -->
            <!-- Recent invoice with Statistics -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"> مناقصات جاری فروشگاه ها</h4>
                            <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="fa fa-minus"></i></a></li>
                                    <li><a data-action="expand"><i class="fa fa-expand"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body collapse in">
                            <div class="card-body collapse in">
                                <div class="card-block card-dashboard left">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <div class="table-responsive table-hover table-striped ">
                                    <table class="table mb-0 text-xs-center" id="tender_managment_tbl">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">اطلاعات مناقصه اصلی(پیمانکار)</th>
                                                <th rowspan="2">شرکت کنندگان</th>
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
                </div>

                <div class="col-xs-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">مناقصات ارشیو شده فروشگاه ها</h4>
                            <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="fa fa-minus"></i></a></li>
                                    <li><a data-action="expand"><i class="fa fa-expand"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body collapse in">
                            <div class="card-body collapse in">
                                <div class="card-block card-dashboard left">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <div class="table-responsive table-hover table-striped ">
                                    <table class="table mb-0 text-xs-center" id="archived_tenders_tbl">
                                        <thead>
                                        <tr>
                                            <th rowspan="2">اطلاعات مناقصه اصلی(پیمانکار)</th>
                                            <th rowspan="2">شرکت کنندگان</th>
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
                </div>
            </div>

            <!--/ stats -->
        </div>
    </div>
</div>

<!-- new tender confirm -->
<div class="modal fade" id="new_tender_confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="z-index: 9999999999">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                آیا از صحت اطلاعات وارد شده اطمینان دارید؟
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="new_tender_confirm_cancel_btn">انصراف</button>
                <button type="button" class="btn btn-primary" id="new_tender_confirm_ok_btn">تایید</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal add tender -->
<div class="modal fade " id="add_tender_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="user_other_data_modal_name">افزودن مناقصه</h4>
            </div>
            <div class="modal-body">
                <form class="form" name="add_new_tender" id="add_new_tender">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-xs-8 offset-xs-2">
                                <div class="form-group">
                                    <select id="new_parent_tender_name"  name="new_parent_tender_name" class="form-control">
                                    </select>
                                    <div class="new_parent_tender_name_error danger"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="new_tender_date2">تاریخ شروع مناقصه</label>
                                    <input type="text" id="new_tender_date2" name="new_tender_date2" class="form-control tnd_round">
                                    <input type="hidden" id="new_tender_date" name="new_tender_date">
                                    <div class="new_tender_date2_error danger"></div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="new_tender_end_date2">تاریخ پایان مناقصه</label>
                                    <input type="text" id="new_tender_end_date2" name="new_tender_end_date2" class="form-control tnd_round">
                                    <input type="hidden" id="new_tender_end_date" name="new_tender_end_date">
                                    <div class="new_tender_end_date2_error danger"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="new_tender_only_one" id="new_tender_only_one" value="yes"> این مناقصه حتی با شرکت یک فروشگاه برگزار خواهد شد.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h4 class="form-section">اسناد مناقصه </h4>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="new_tender_files">(یک فایل zip یا rar)</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="file" id="new_tender_files" class="form-control round border-primary" name="new_tender_files">
                                    </div>
                                    <div class="new_tender_files_error danger"></div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions right">
                        <button type="button" class="btn btn-outline-green tnd_round" name="add_new_tender_btn" id="add_new_tender_btn">
                            <i class="fa fa-plus"></i>افزودن مناقصه
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>



<!-- Modal edit tender -->
<div class="modal fade " id="edit_tender_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="user_other_data_modal_name">ویرایش مناقصه</h4>
            </div>
            <div class="modal-body">
                <form class="form" name="edit_tender" id="edit_tender">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-xs-8 offset-xs-2">
                                <div class="form-group">
                                    <select id="edit_parent_tender_name"  name="edit_parent_tender_name" class="form-control">
                                    </select>
                                    <div class="new_parent_tender_name_error danger"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="edit_tender_date2">تاریخ شروع</label>
                                    <input type="text" id="edit_tender_date2" name="edit_tender_date2" class="form-control tnd_round">
                                    <input type="hidden" id="edit_tender_date" name="edit_tender_date">
                                    <div class="edit_tender_date2_error danger"></div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="edit_tender_end_date2">تاریخ پایان</label>
                                    <input type="text" id="edit_tender_end_date2" name="edit_tender_end_date2" class="form-control tnd_round">
                                    <input type="hidden" id="edit_tender_end_date" name="edit_tender_end_date">
                                    <div class="edit_tender_end_date2_error danger"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="edit_tender_only_one" id="edit_tender_only_one" value="yes"> این مناقصه حتی با شرکت یک فروشگاه برگزار خواهد شد.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h4 class="form-section">اسناد مناقصه </h4>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group edit_files_download_link">
                                    <div class="alert alert-grey alert-dismissible fade in mb-2" id="edit_tender_files_alert" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <strong><a href="#" id="edit_tender_files" class="alert-link">دانلود</a> </strong>
                                    </div>
                                </div>

                                <div class="form-group" id="edit_tender_new_file_section">
                                    <label for="edit_tender_new_file">(یک فایل zip یا rar)</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="file" id="edit_tender_new_file" class="form-control round border-primary" name="edit_tender_new_file">
                                    </div>
                                    <div class="edit_tender_new_file_error danger"></div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions right">
                        <button type="button" class="btn btn-outline-green tnd_round" name="edit_tender_confirm_btn" id="edit_tender_confirm_btn">
                            <i class="icon-check2"></i>ویرایش مناقصه
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- edit tender confirm -->
<div class="modal fade" id="edit_tender_confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="z-index: 9999999999">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                آیا از صحت اطلاعات وارد شده اطمینان دارید؟
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="edit_tender_confirm_cancel_btn">انصراف</button>
                <button type="button" class="btn btn-primary" id="edit_tender_confirm_ok_btn">تایید</button>
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
                <h4 class="modal-title" id="user_other_data_modal_name">جزئیات مناقصه</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <tbody>
                        <tr>
                            <td class="text-truncate">تاریخ شروع مناقصه</td>
                            <td class="text-truncate" id="main_tender_start_date"></td>
                        </tr>
                        <tr>
                            <td class="text-truncate">تاریخ پایان مناقصه</td>
                            <td class="text-truncate" id="main_tender_end_date"></td>
                        </tr>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>


<!-- show tender contributors -->
<div class="modal fade " id="show_tender_contributors" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="z-index: 9999999999">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="shop_tender_contributers_title"></h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tender_contributors_tbl">
                        <thead>
                            <tr>
                                <th>نام فروشگاه</th>
                                <th>مدیریت</th>
                                <th>شماره تماس</th>
                                <th class="hidden-print">فایل  پیشنهادی فروشگاه</th>
                            </tr>
                        </thead>

                        <tbody id="tender_contributors_tbl_tbody" style="text-align: center">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="print_tender_contributors" class="btn btn-outline-success" > <i class=" fa fa-print"></i> &nbsp;پرینت  </button>
                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>





<!-- show tender contributors and files -->
<div class="modal fade" id="show_tender_contributors_and_files" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="z-index: 9999999999">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="user_other_data_modal_name">لیست شرکت کنندگان</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tender_contributors_and_files_tbl">
                        <thead>
                        <tr>
                            <th>عملیات</th>
                            <th>نام فروشگاه</th>
                            <th>مدیریت</th>
                            <th>شماره تماس</th>
                            <th class="hidden-print">فایل  پیشنهادی فروشگاه</th>
                        </tr>
                        </thead>

                        <tbody id="tender_contributors_and_files_tbl_tbody" style="text-align: center">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>




<!-- run tender modal -->
<div class="modal fade" id="tender_winner_confirm_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="z-index: 9999999999">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="user_other_data_modal_name">تایید برنده</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-2" role="alert">
                    <strong>توجه :</strong> در صورتی که تاییده از متقاضی مناقصه دارید، تصویر تاییده را اپلود نمایید!
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group tender_winner_confirm_file_container ">
                            <label for="tender_winner_confirm_file">(یک فایل zip یا rar)</label>
                            <div class="position-relative has-icon-left">
                                <input type="file" id="tender_winner_confirm_file" class="form-control round border-primary" name="tender_winner_confirm_file">
                            </div>
                            <div class="tender_winner_confirm_file_error danger"></div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                <button type="button" class="btn btn-primary" id="tender_winner_confirm_modal_btn"> <i class="icon-check2"></i> تایید برنده</button>
            </div>
        </div>
    </div>
</div>


<!-- show_tender_winner -->
<div class="modal fade" id="show_tender_winner" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="z-index: 9999999999">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="user_other_data_modal_name">مشخصات برنده مناقصه</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="show_tender_winner_tbl">
                        <thead>
                        <tr>
                            <th>نام فروشگاه</th>
                            <th>مدیریت</th>
                            <th>شماره تماس</th>
                            <th class="hidden-print">فایل  پیشنهادی فروشگاه</th>
                            <th class="hidden-print">فایل  تاییده برنده</th>

                        </tr>
                        </thead>

                        <tbody id="show_tender_winner_tbl_tbody" style="text-align: center">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="delete_tender_confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="z-index: 9999999999">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                آیا از حذف مناقصه اطمینان دارید؟
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="delete_tender_confirm_cancel_btn">انصراف</button>
                <button type="button" class="btn btn-primary" id="delete_tender_confirm_ok_btn">تایید</button>
            </div>
        </div>
    </div>
</div>
