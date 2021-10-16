<?php $current_user = wp_get_current_user(); ?>
<nav class="header-navbar navbar navbar-with-menu navbar-fixed-top navbar-semi-dark navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav">
                <li class="nav-item mobile-menu hidden-md-up float-xs-left"><a class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="icon-menu5 font-large-1"></i></a></li>
                <li class="nav-item"><a href="<?php echo home_url('panel')?>" class="navbar-brand nav-link"><img alt="branding logo" src="<?php echo Assets::images('logo/robust-logo-light2.png'); ?>" data-expand="<?php echo Assets::images('logo/robust-logo-light2.png'); ?>" data-collapse="<?php echo Assets::images('logo/robust-80x80.png'); ?>" class="brand-logo"></a></li>
                <li class="nav-item hidden-md-up float-xs-right"><a data-toggle="collapse" data-target="#navbar-mobile" class="nav-link open-navbar-container"><i class="icon-ellipsis pe-2x icon-icon-rotate-right-right"></i></a></li>
            </ul>
        </div>
        <div class="navbar-container content container-fluid">
            <div id="navbar-mobile" class="collapse navbar-toggleable-sm">
                <ul class="nav navbar-nav">
                    <li class="nav-item hidden-sm-down"><a class="nav-link nav-menu-main menu-toggle hidden-xs is-active"><i class="icon-menu5">         </i></a></li>
                </ul>
                <ul class="nav navbar-nav float-xs-right">
                    <li class="dropdown dropdown-user nav-item">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link">
                            <span class="avatar avatar-online">
                                <?php
                                if(metadata_exists('user', $current_user->ID,'profile_pic'))
                                {
	                                ?>
                                    <img alt="" src="<?php echo get_user_meta($current_user->ID,'profile_pic',true)?>"  class="avatar avatar-64 photo" height="64" width="64"><i></i>
	                                <?php
                                }
                                else
                                {
	                                echo get_avatar( $current_user->ID, 64 ).'<i></i>';
                                }
                                ?>

                            </span>
                            <span class="user-name"><?php echo $current_user->user_firstname ."  " . $current_user->user_lastname;?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="<?php echo home_url('profile');?>" class="dropdown-item"><i class="icon-head"></i>  پروفایل</a>
                            <!--                            <a href="#" class="dropdown-item"><i class="icon-key4"></i> تغییر رمز عبور</a>-->
                            <!--                            <a href="#" class="dropdown-item"><i class="icon-mail6"></i> صندوق ورودی من</a>-->
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo wp_logout_url( home_url('login') ); ?>" class="dropdown-item"><i class="icon-power3"></i> خروج</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>


