<?php $current_user = wp_get_current_user(); ?>
<div class="app-content content container-fluid">
	<div class="content-wrapper">

		<div class="content-body"><!-- stats -->
            <?php
                if (get_user_meta($current_user->ID,'send_files',true)=='true')
                {
                    ?>
                     <div class="row">
                        <div class=" col-xs-12 col-md-6 offset-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-block">
                                        <div class="media">
	                                        <?php
	                                        if (get_user_meta($current_user->ID,'user_status',true) == 'pending')
	                                        {
		                                        ?>
                                                    <div class="media-body text-xs-left">
                                                        <h3 class="info lighten-1">در حال بررسی مدارک توسط کارشناس</h3>
                                                        <span>پس از تایید مدارک توسط کارشناس مربوطه، می توانید در مناقصات شرکت نمایید.</span>
                                                    </div>

                                                    <div class="media-right media-middle">
                                                        <i class="fa fa-clock info lighten-1 font-large-2 float-xs-right"></i>
                                                    </div>
                                                <?php
	                                        }
	                                        else if(get_user_meta($current_user->ID,'user_status',true) == 'verified')
	                                        {
		                                        ?>
                                                <div class="media-body text-xs-left">
                                                    <h3 class="success lighten-1">تایید شده توسط کارشناس</h3>
                                                    <span>مدارک ارسالی شما توسط کارشناس تایید شده است. می توانید در مناقصات شرکت نمایید.</span>
                                                </div>

                                                <div class="media-right media-middle">
                                                    <i class="fa fa-check-circle success lighten-1 font-large-2 float-xs-right"></i>
                                                </div>
		                                        <?php
	                                        }
	                                        ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            ?>


			<div class="row">
                <div class="col-xs-12 <?php echo (get_user_meta($current_user->ID,'send_files',true)=='true')? 'col-md-6':'col-md-4' ;?> ">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <span class="avatar avatar-online">
                                    تصویر پروفایل
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

                                <div class="card-text text-xs-center">
                                    <?php
                                        if(metadata_exists('user', $current_user->ID,'profile_pic'))
                                        {
                                            ?>
                                            <img class="card-img img-fluid mb-1 rounded-circle" src="<?php echo get_user_meta($current_user->ID,'profile_pic',true)?>" style="height: 100px !important;width: 100px !important;">
                                            <?php
                                        }
                                        else
                                        {
	                                        ?>
                                            <img class="card-img img-fluid mb-1 rounded-circle" src="<?php echo get_avatar_url( $current_user->ID);?>" alt="<?php echo $current_user->user_login?>" id="profile_pic_holder" style="height: 65px !important;width: 65px !important;">
	                                        <?php
                                        }
                                    ?>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 text-xs-center" >
                                        <div class="form-group">
                                            <!-- button group -->
                                            <div class="btn-group" role="group" aria-label="Basic example" id="profile_pic_action">
                                                <input type="file" id="profile_pic_upload" accept="image/x-png,image/jpeg,image/jpg" style="display:none"/>
                                                <?php
                                                    if(metadata_exists('user', $current_user->ID,'profile_pic'))
                                                    {
                                                        ?>
                                                        <button type="button" class="btn btn-outline-success btn-sm" id="edit_profile_pic" name="edit_profile_pic" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="ویرایش تصویر"><i class="fa fa-edit"></i></button>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" id="remove_profile_pic" name="remove_profile_pic" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="حذف تصویر"><i class="fa fa-times"></i></button>
	                                                    <?php
                                                    }
                                                    else
                                                    {
	                                                    ?>
                                                        <button type="button" class="btn btn-outline-success btn-sm" id="add_profile_pic" name="add_profile_pic" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="افزودن عکس"><i class="fa fa-plus"></i></button>
	                                                    <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
                    if (get_user_meta($current_user->ID,'send_files',true)=='false')
                    {
                        ?>
                        <div class="col-xs-12 col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                    <span class="avatar avatar-online">
                                         ارسال اطلاعات کارخانه
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
                                        <form class="form" name="update_shop_data" id="update_shop_data">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <label for="shop_name">نام کارخانه</label>
                                                            <input type="text" id="shop_name" name="shop_name" class="form-control tnd_round">
                                                            <div class="shop_name_error danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <label for="shop_manager_name">نام و نام خانوادگی مدیریت</label>
                                                            <input type="text" id="shop_manager_name" name="shop_manager_name" class="form-control tnd_round">
                                                            <div class="shop_manager_name_error danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <label for="shop_address">آدرس</label>
                                                            <input type="text" id="shop_address" name="shop_address" class="form-control tnd_round">
                                                            <div class="shop_address_error danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="form-group">
                                                            <label for="shop_tel">موبایل</label>
                                                            <input type="text" id="shop_tel" name="shop_tel" class="form-control tnd_round">
                                                            <div class="shop_tel_error danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="form-group">
                                                            <label for="shop_code">کد اقتصادی/ کد ملی</label>
                                                            <input type="text" id="shop_code" name="shop_code" class="form-control tnd_round">
                                                            <div class="shop_code_error danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <label for="shop_file">ارسال مدارک فروشگاه (یک فایل zip یا rar) شامل :</label>
                                                            <ul>
                                                                <li class="ml-2 font-small-3">صفحه اصلی اساسنامه (شامل موضوع فعالیت)</li>
                                                                <li class="ml-2 font-small-3">آخرین آگهی تغییرات</li>
                                                                <li class="ml-2 font-small-3">گواهی صلاحیت</li>

                                                            </ul>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="file" id="shop_file" class="form-control round border-primary" name="shop_file">
                                                                <div class="form-control-position" id="pnd_upload_icon">
                                                                    <i class="fa fa-paperclip"></i>
                                                                </div>
                                                            </div>
                                                            <div class="pnd_file_error danger"></div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-actions right">
                                                <button type="button" class="btn btn-outline-green tnd_round" name="update_shop_data_btn" id="update_shop_data_btn">
                                                    <i class="fa fa-check"></i> درخواست بررسی اطلاعات کارخانه
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
				?>
                <div class="col-xs-12 <?php echo (get_user_meta($current_user->ID,'send_files',true)=='true')? 'col-md-6':'col-md-4' ;?>">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <span class="avatar avatar-online">
                                    مدیریت گذرواژه
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


                                <form class="form" name="profile_update_pass" id="profile_update_pass">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="current_pass">گذرواژه فعلی</label>
                                                    <input type="password" id="current_pass" class="form-control tnd_round"  name="current_pass">
                                                    <div class="update_pass_current_pass_error danger"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="new_pass">گذرواژه جدید</label>
                                                    <input type="password" id="new_pass" class="form-control tnd_round"  name="new_pass">
                                                    <div class="update_pass_new_pass_error danger"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-actions right">
                                        <button type="button" class="btn btn-outline-green tnd_round" id="update_pass_btn" name="update_pass_btn">
                                            <i class="fa fa-check"></i> بروز رسانی گذرواژه
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
			</div>

		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="shop_data_update_confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                پس از ارسال اطلاعات کارخانه، کارشناسان ما اقدام به بررسی اطلاعات ارسالی شما می نمایند.
                پس از تایید اطلاعات توسط کارشناس مربوطه ، می توانید در مناقصات شرکت نمایید.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="shop_data_update_confirm_cancel_btn">انصراف</button>
                <button type="button" class="btn btn-primary" id="shop_data_update_confirm_ok_btn">تایید</button>
            </div>
        </div>
    </div>
</div>
