<div data-scroll-to-active="true" class="main-menu menu-fixed menu-dark menu-accordion menu-shadow">
    <!-- main menu header-->
        <div class="main-menu-header">
            <!--            <input type="text" placeholder="Search" class="menu-search form-control round"/>-->
        </div>
    <!-- / main menu header-->
    <!-- main menu content-->
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item">
                    <a>
                        <i class="fa fa-hammer"></i>
                        <span data-i18n="nav.form_layouts.form_layout_basic" class="menu-title"> مناقصات </span>
                    </a>
                    <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                        <li class=" nav-item"><a href="<?php echo home_url('tender_managment');?>"><i class="fa fa-balance-scale"></i><span data-i18n="nav.advance_cards.main" class="menu-title">مناقصات پیمانکاران</span></a></li>
                        <li class=" nav-item"><a href="<?php echo home_url('shop_tender_managment');?>"><i class="fa fa-store"></i><span data-i18n="nav.advance_cards.main" class="menu-title">مناقصات فروشگاه ها</span></a></li>
                        <li class=" nav-item"><a href="<?php echo home_url('factory_tender_managment');?>"><i class="fa fa-industry"></i><span data-i18n="nav.advance_cards.main" class="menu-title">مناقصات کارخانجات</span></a></li>
                    </ul>
                </li>
                <li class=" nav-item">
                    <a><i class="fa fa-users"></i>
                        <span data-i18n="nav.form_layouts.form_layout_basic" class="menu-title"> کاربران</span>
                    </a>
                    <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                        <li class=" nav-item"><a href="<?php echo home_url('users_managment');?>"><i class="fa fa-drafting-compass"></i><span data-i18n="nav.advance_cards.main" class="menu-title">پیمانکاران</span></a></li>
                        <li class=" nav-item"><a href="<?php echo home_url('shop_managment');?>"><i class="fa fa-industry"></i><span data-i18n="nav.advance_cards.main" class="menu-title">فروشگاه ها/ کارخانه ها</span></a></li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="<?php echo home_url('profile');?>"><i class="fa fa-id-card"></i><span data-i18n="nav.advance_cards.main" class="menu-title"> پروفایل</span></a></li>
                <li class=" nav-item"><a href="<?php echo wp_logout_url( home_url('login') ); ?>"><i class="fa fa-power-off"></i><span data-i18n="nav.form_layouts.form_layout_basic" class="menu-title"> خروج</span></a></li>
            </ul>
        </div>
    <!-- /main menu content-->
</div>

