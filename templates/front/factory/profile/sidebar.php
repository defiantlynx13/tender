<?php
$current_user=wp_get_current_user();
$current_user_status=get_user_meta($current_user->ID,'user_status',true);
?>
<div data-scroll-to-active="true" class="main-menu menu-fixed menu-dark menu-accordion menu-shadow">
    <!-- main menu header-->
        <div class="main-menu-header">
            <!--            <input type="text" placeholder="Search" class="menu-search form-control round"/>-->
        </div>
    <!-- / main menu header-->
    <!-- main menu content-->
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                    <?php
                    if ($current_user_status=='verified')
                    {
                        ?>
                        <li class=" nav-item"><a href="<?php echo home_url('factory_tenders');?>"><i class="fa fa-balance-scale"></i><span data-i18n="nav.dash.main" class="menu-title">مناقصات</span></a></li>
                        <?php
                    }
                    ?>
                    <li class=" nav-item"><a href="<?php echo home_url('profile');?>"><i class="fa fa-id-card"></i><span data-i18n="nav.advance_cards.main" class="menu-title"> پروفایل</span></a></li>
                    <li class=" nav-item"><a href="<?php echo wp_logout_url( home_url('login') ); ?>"><i class="fa fa-power-off"></i><span data-i18n="nav.form_layouts.form_layout_basic" class="menu-title"> خروج</span></a></li>
                </ul>
   </ul>
        </div>
    <!-- /main menu content-->
</div>

