
<div class="app-content content container-fluid">
    <div class="content-wrapper">

        <div class="content-body"><!-- stats -->
            <!-- Recent invoice with Statistics -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">مدیریت فروشگاه ها و کارخانجات</h4>
                            <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
                                    <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
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
                                    <table class="table mb-0 text-xs-center" id="shop_managment_tbl">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">#</th>
                                                <th rowspan="2">نام فروشگاه/کارخانه</th>
                                                <th colspan="5">اطلاعات فروشگاه/کارخانه</th>
                                                <th rowspan="2">عملیات</th>
                                            </tr>
                                            <tr>
                                                <th>نام کاربری</th>
                                                <th>مدیر</th>
                                                <th>شماره اقتصادی/ کدملی</th>
                                                <th>اسناد</th>
                                                <th>آدرس</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
<!--                            <div class="card-block card-dashboard left">-->
<!--                                راهنمای وضعیت کاربران :-->
<!--                                <br>-->
<!--                                <div class="tag tag-warning">منتظر بررسی مدارک</div>-->
<!--                                <div class="tag tag-success">مدارک تایید شده</div>-->
<!--                            </div>-->
                        </div>
                    </div>

                </div>
            </div>

            <!--/ stats -->
        </div>
    </div>
</div>

<!-- Modal user other data -->
<div class="modal fade text-xs-left in" id="user_other_data_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel19">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="user_other_data_modal_name"></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12"><h5>نام کاربری</h5></div>
                    <div class="col-xs-12"><b id="user_other_data_modal_username" ></b></div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-xs-12"><h5>شماره تماس</h5></div>
                    <div class="col-xs-12 float-xs-right"><b id="user_other_data_modal_tel" ></b></div>

                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-12"><h5>ایمیل</h5></div>
                    <div class="col-xs-12 float-xs-left"><b id="user_other_data_modal_email" ></b></div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal lock user confirm -->
<div class="modal fade" id="lock_user_modal_confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                در صورتی که کاربر را غیر فعال نمایید، کاربر دسترسی به سایت نخواد داشت.
                همچنین پس از فعال کردن مجدد کاربر، مدارک شرکت باید مجددا توسط کاربر ارسال شود.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="shop_data_update_confirm_cancel_btn">انصراف</button>
                <button type="button" class="btn btn-primary" id="lock_user_confirm_ok_btn">تایید</button>
            </div>
        </div>
    </div>
</div>



<!-- Modal resend user data confirm -->
<div class="modal fade" id="resend_files_modal_confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                در صورتی که درخواست مجدد مدارک را تایید کنید،
                فروشگاه باید مجددا مدارک را ارسال نماید و تا زمان تایید مجدد مدارک توسط کارشناس ،نمی تواند در مناقصات شرکت نماید.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="shop_data_update_confirm_cancel_btn">انصراف</button>
                <button type="button" class="btn btn-primary" id="resend_files_ok_btn">تایید</button>
            </div>
        </div>
    </div>
</div>



<!-- Modal add shop -->
<div class="modal fade " id="add_shop_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="user_other_data_modal_name">افزودن فروشگاه/کارخانه</h4>
            </div>
            <div class="modal-body">
                <form class="form" name="update_shoporation_data" id="update_shoporation_data">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="new_shop_type" id="new_shop_type_shop" value="shop" checked>
                                        <label class="form-check-label" for="new_shop_type_shop">فروشگاه</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="new_shop_type" id="new_shop_type_factory" value="factory">
                                        <label class="form-check-label" for="new_shop_type_factory">کارخانه</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="new_shop_name">نام فروشگاه/کارخانه</label>
                                    <input type="text" id="new_shop_name" name="new_shop_name" class="form-control tnd_round">
                                    <div class="new_shop_name_error danger"></div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="new_user_name">نام کاربری</label>
                                    <input type="text" id="new_user_name" name="new_user_name" class="form-control tnd_round">
                                    <div class="new_user_name_error danger"></div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="new_password">گذرواژه</label>
                                    <input type="text" id="new_password" name="new_password" class="form-control tnd_round">
                                    <div class="new_password_error danger"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions right">
                        <button type="button" class="btn btn-outline-green tnd_round" name="add_new_shop_btn" id="add_new_shop_btn">
                            <i class="icon-check2"></i>افزودن فروشگاه/کارخانه
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>


<!-- Modal edit user shop name and grade -->
<div class="modal fade " id="edit_shop_data_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="user_other_data_modal_name">ویرایش اطلاعات فروشگاه/کارخانه</h4>
            </div>
            <div class="modal-body">
                <form class="form" name="update_shop_data" id="update_shop_data">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="user_edit_shop_type" id="user_edit_type_shop" value="shop" checked>
                                        <label class="form-check-label" for="user_edit_type_shop">فروشگاه</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="user_edit_shop_type" id="user_edit_type_factory" value="factory">
                                        <label class="form-check-label" for="user_edit_type_factory">کارخانه</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="user_edit_shop_name">نام فروشگاه/کارخانه</label>
                                    <input type="text" id="user_edit_shop_name" name="user_edit_shop_name" class="form-control tnd_round">
                                    <div class="user_edit_shop_name_error danger"></div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="user_edit_shop_manager_name">نام و نام خانوادگی مدیریت</label>
                                    <input type="text" id="user_edit_shop_manager_name" name="user_edit_shop_manager_name" class="form-control tnd_round">
                                    <div class="user_edit_shop_manager_name_error danger"></div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="user_edit_shop_address">آدرس</label>
                                    <input type="text" id="user_edit_shop_address" name="user_edit_shop_address" class="form-control tnd_round">
                                    <div class="user_edit_shop_address_error danger"></div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="user_edit_shop_tel">موبایل</label>
                                    <input type="text" id="user_edit_shop_tel" name="user_edit_shop_tel" class="form-control tnd_round">
                                    <div class="user_edit_shop_tel_error danger"></div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="user_edit_shop_code">کد اقتصادی</label>
                                    <input type="text" id="user_edit_shop_code" name="user_edit_shop_code" class="form-control tnd_round">
                                    <div class="user_edit_shop_code_error danger"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions right">
                        <button type="button" class="btn btn-outline-green tnd_round" name="edit_user_shop_data_confirm" id="edit_user_shop_data_confirm">
                            <i class="icon-check2"></i>تایید
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- Modal change user password -->
<div class="modal fade " id="change_user_pass_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="user_other_data_modal_name">تغییر گذرواژه کاربر</h4>
            </div>
            <div class="modal-body">
                <form class="form" name="update_shoporation_data" id="update_shoporation_data">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="new_pass">گذرواژه جدید</label>
                                    <input type="text" id="new_pass" name="new_pass" class="form-control tnd_round">
                                    <div class="new_pass_error danger"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions right">
                        <button type="button" class="btn btn-outline-green tnd_round" name="change_user_pass_btn_confirm" id="change_user_pass_btn_confirm">
                            <i class="icon-check2"></i>تایید
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>