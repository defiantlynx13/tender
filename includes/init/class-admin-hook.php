<?php
/**
 * Admin_Hook Class File
 *
 * This file contains hooks that you need in admin panel of WordPress
 * (like enqueue styles or scripts in admin panel)
 *
 * @package    Tender_Shop_Dir\Includes\Init
 * @author     Your_Name <youremail@nomail.com>
 * @license    https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link       https://yoursite.com
 * @since      1.0.0
 */

namespace Tender_Shop_Dir\Includes\Init;

use Tender_Shop_Dir\Includes\Functions\Date;
use Tender_Shop_Dir\Includes\Functions\File_Uploader;
use Tender_Shop_Dir\Includes\Functions\SmsIr;
use Tender_Shop_Dir\Includes\Functions\Utility;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tender_Shop_Dir\Includes\Init
 * @author     Your_Name <youremail@nomail.com>
 */
class Admin_Hook {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $Tender_Shop The ID of this plugin.
	 */
	private $Tender_Shop;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param      string $Tender_Shop The name of this plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $Tender_Shop, $version ) {

		$this->Tender_Shop = $Tender_Shop;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tender_Shop_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tender_Shop_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style(
			$this->Tender_Shop . '_admin_style',
			Tender_Shop_ADMIN_CSS . 'plugin-name-admin.css',
			array(),
			$this->version,
			'all'
		);

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tender_Shop_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tender_Shop_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script(
			$this->Tender_Shop . '_admin_script',
			Tender_Shop_ADMIN_JS . 'plugin-name-admin.js',
			array( 'jquery' ),
			$this->version,
			false
		);
	}

    function fetch_shop_data_callback()
    {
        header("Content-Type: application/json");
        $request= $_POST;
        $column = array(
            'user_status',
            'last_name',
            'corporation_name',
            'corporation_grade',
        );

        $args=array(
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'meta'     => 'tnd_user_type',
                    'value'   => 'shop'
                ),
                array(
                    'meta'     => 'tnd_user_type',
                    'value'   => 'factory'
                )
            )
        );

        if (isset($request["search"]["value"]) )
        {
            if ($request["search"]["value"] !=null)
            {
                $args['meta_query'][]=array(
                    'meta'     => 'shop_name',
                    'value'   => $request["search"]["value"],
                    'compare' => 'LIKE'
                );
            }
        }

        if(isset($request['order']))
        {
            $args['orderby']=$column[$request['order']['0']['column']];
            $args['order']=$request['order']['0']['dir'];
        }

        $args['limit']=intval($request['length']);
        $args['offset']=intval($request['start']);

        $customers = get_users($args);
        $count=intval($request['start'])+1;
        foreach($customers as $customer) {
            $send_files = get_user_meta($customer->ID, 'send_files', true);
            $user_status = get_user_meta($customer->ID, 'user_status', true);
            $sub_array = array();
            $sub_array[] = $count;
            $temp = get_userdata($customer->ID);
            $sub_array[]=($temp->tnd_user_type == 'shop')? $temp->shop_name.'(فروشگاه)':$temp->shop_name.'(کارخانه)';

            $sub_array[] =$temp->user_login;
            if (metadata_exists( 'user', $customer->ID, 'shop_manager_name'))
            {
                $sub_array[] = $temp->shop_manager_name;
            }
            else
            {
                $sub_array[] = '-';
            }



            if (metadata_exists( 'user', $customer->ID, 'shop_code'))
            {
                $sub_array[] = $temp->shop_code;
            }
            else
            {
                $sub_array[] = '-';
            }

            if (metadata_exists( 'user', $customer->ID, 'shop_file'))
            {
                $sub_array[] = '<a href="'.$temp->shop_file.'" class="btn btn-warning" data-toggle="tooltip" data-original-title="دانلود مستندات فروشگاه"><i class="fa fa-download"></i> </a>';
            }
            else
            {
                $sub_array[] = '-';
            }

            if (metadata_exists( 'user', $customer->ID, 'shop_address'))
            {
                $sub_array[] = $temp->shop_address;
            }
            else
            {
                $sub_array[] = '-';
            }

            $operations="";
            if ($send_files == "true")
            {
                if ($user_status == 'pending')
                {
                    $operations.='<button type="button" class="btn btn-success" id="shop_verification_btn" name="shop_verification_btn" data-toggle="tooltip" data-original-title="تایید مدارک فروشگاه" data-user_id="'.$customer->ID.'"><i class="fa fa-check"></i> </button>'.' | ';
                }
                else
                {
                    $operations.='<button type="button" class="btn btn-info" id="resend_files_btn" name="resend_files_btn" data-user_id="'.$customer->ID.'" data-toggle="tooltip" data-original-title="ارسال مجدد مدارک توسط فروشگاه/کارخانه"><i class="fa fa-redo"></i> </button>'.' | ';
                }
            }
            $operations.='<button type="button" class="btn btn-pink.accent-1" id="edit_user_and_grade_corp_name" name="edit_user_and_grade_corp_name" data-toggle="tooltip" data-original-title="ویرایش اطلاعات فروشگاه/کارخانه " data-user_id="'.$customer->ID.'" data-shop_type="'.get_user_meta($customer->ID,'tnd_user_type',true).'"  data-shop_name="'.get_user_meta($customer->ID,'shop_name',true).'" data-shop_manager_name="'.get_user_meta($customer->ID,'shop_manager_name',true).'"  data-shop_address="'.get_user_meta($customer->ID,'shop_address',true).'" data-shop_tel="'.get_user_meta($customer->ID,'shop_tel',true).'"  data-shop_code="'.get_user_meta($customer->ID,'shop_code',true).'" ><i class="fa fa-edit"></i> </button>'.' | ';
            $operations.='<button type="button" class="btn btn-warning" id="change_pass_btn" name="change_pass_btn" data-toggle="tooltip" data-original-title="تغییر گذره واژه کاربر" data-user_id="'.$customer->ID.'"><i class="fa fa-key"></i> </button>'.' | ';
//            $operations.='<button type="button" class="btn btn-primary" id="show_user_email_tel_btn" name="show_user_email_tel_btn" data-toggle="tooltip" data-original-title="مشاهده سایر اطلاعات کاربر" data-user_id="'.$customer->ID.'" data-email="'.get_user_meta($customer->ID,'profile_email',true).'" data-tel="'.get_user_meta($customer->ID,'profile_tel',true).'" data-user_name="'.$temp->user_login.'" data-fname_lname="'.$temp->first_name .  " " .$temp->last_name.'"><i class="fa fa-eye"></i> </button>'.' | ';
            if ($user_status == 'enable' || $user_status == 'pending' || $user_status == 'verified')
            {
                $operations.='<button type="button" class="btn btn-danger" id="lock_user_btn" name="lock_user_btn" data-user_id="'.$customer->ID.'" data-toggle="tooltip" data-original-title="غیر فعال کردن کاربر"><i class="fa fa-lock"></i> </button>';
            }
            elseif ($user_status == 'disable')
            {
                $operations.='<button type="button" class="btn btn-success " id="unlock_user_btn" name="unlock_user_btn" data-user_id="'.$customer->ID.'" data-toggle="tooltip" data-original-title="فعال کردن کاربر"><i class="fa fa-unlock"></i> </button>';
            }

            $sub_array[] = $operations;
            $sub_array[] = $send_files;
            $sub_array[] = $user_status;
            $data[] = $sub_array;
            $count++;
        }

        $output = array(
            "draw"    => intval($_POST["draw"]),
            "recordsTotal"  =>  intval($count),
            "recordsFiltered" => intval($count),
            "data" => $data
        );


        echo json_encode($output);

        wp_die();
    }

    function tnd_new_shop_callback()
    {
        header("Content-Type: application/json");
        $request= $_POST;
        if (isset($request['user_id']) && $request['user_id'] != "")
        {
            if (current_user_can('administrator'))
            {
                $user_id = username_exists( sanitize_text_field($request['user_name']) );

                if ( ! $user_id)
                {
                    $user_id = wp_create_user( sanitize_text_field($request['user_name']), sanitize_text_field($request['password']) );
                    update_user_meta($user_id,'shop_name',sanitize_text_field($request['shop_name']));
                    update_user_meta($user_id,'user_status','enable');
                    update_user_meta($user_id,'tnd_user_type',sanitize_text_field($request['shop_type']));
                    update_user_meta($user_id,'send_files','false');
                    $output=array(
                        'success' => 'true',
                        'error' => 'فروشگاه/کارخانه با موفقیت ثبت شد.',
                    );
                }
                else
                {
                    $output=array(
                        'success' => 'false',
                        'error' => 'نام کاربری از قبل ثبت شده است.',
                    );
                }

            }
            else
            {
                $output=array(
                    'success' => 'false',
                    'error' =>'خطای امنیتی رخ داده است',
                );
            }

        }
        else
        {
            $output=array(
                'success' => false,
                'error' =>'خطای امنیتی رخ داده است',
            );
        }



        echo json_encode($output);

        wp_die();
    }
    function tnd_shop_verification_callback()
    {
        header("Content-Type: application/json");
        $request= $_POST;
        if (isset($request['user_id']) && $request['user_id'] != "")
        {
            if (current_user_can('administrator'))
            {
                update_user_meta(intval($request['user_id']),'send_files','true');
                update_user_meta(intval($request['user_id']),'user_status','verified');
                $output=array(
                    'success' => true,
                    'error' => 'کاربر مدنظر با موفقیت تایید گردید.',
                );
            }
            else
            {
                $output=array(
                    'success' => false,
                    'error' =>'خطای امنیتی رخ داده است',
                );
            }

        }
        else
        {
            $output=array(
                'success' => false,
                'error' =>'خطای امنیتی رخ داده است',
            );
        }



        echo json_encode($output);

        wp_die();
    }

    function edit_shop_data_callback()
    {
        header("Content-Type: application/json");
        $request= $_POST;
        if (isset($request['user_id']) && $request['user_id'] != "")
        {
            if (current_user_can('administrator'))
            {
                update_user_meta(intval($request['shop_id']),'tnd_user_type',sanitize_text_field($request['shop_type']));
                update_user_meta(intval($request['shop_id']),'shop_name',sanitize_text_field($request['shop_name']));
                update_user_meta(intval($request['shop_id']),'shop_manager_name',sanitize_text_field($request['shop_manager_name']));
                update_user_meta(intval($request['shop_id']),'shop_address',sanitize_text_field($request['shop_address']));
                update_user_meta(intval($request['shop_id']),'shop_tel',sanitize_text_field($request['shop_tel']));
                update_user_meta(intval($request['shop_id']),'shop_code',sanitize_text_field($request['shop_code']));
                $output=array(
                    'success' => true,
                    'error' => 'اطلاعات فروشگاه/کارخانه با موفقیت به روزرسانی گردید.',
                );
            }
            else
            {
                $output=array(
                    'success' => false,
                    'error' =>'خطای امنیتی رخ داده است',
                );
            }

        }
        else
        {
            $output=array(
                'success' => false,
                'error' =>'خطای امنیتی رخ داده است',
            );
        }



        echo json_encode($output);

        wp_die();
    }



    function tnd_shop_profile_pic_add_callback()
    {
//        require_once( ABSPATH . 'wp-content/plugins/tender/inc/ajax/fancy_file_uploader_helper.php' );
        header("Content-Type: application/json");
        $allowedexts = array(
            "png" => true,
            "jpg" => true,
            "jpeg" => true,
        );

        $files = File_Uploader::NormalizeFiles("profile_pic");
        if (!isset($files[0]))
        {
            $result = array(
                "success" => false,
                "error" => "File data was submitted but is missing.",
                "errorcode" => "bad_input"
            );
        }
        else if (!$files[0]["success"])
        {
            $result = $files[0];
        }
        else if (!isset($allowedexts[strtolower($files[0]["ext"])]))
        {
            $result = array(
                "success" => false,
                "error" => "نوع فایل انتخاب شده معتبر نیست.. ",
                "errorcode" => "invalid_file_ext"
            );
        }
        else
        {
            // For chunked file uploads, get the current filename and starting position from the incoming headers.
            $name = File_Uploader::GetChunkFilename();
            if ($name !== false)
            {
                $startpos = File_Uploader::GetFileStartPosition();

                $result = array(
                    "success" => false,
                    "error" => ". نام فایل مدنظر معتبر نمی باشد",
                    "errorcode" => $startpos
                );
            }
            else
            {
                // [Do stuff with the file here.]
                // copy($files[0]["file"], __DIR__ . "/images/" . $id . "." . strtolower($files[0]["ext"]));
                $upload_dir= wp_upload_dir();
                $target_dir = trailingslashit( $upload_dir['basedir'].'/tender_uploads/'.$_REQUEST['user_id']);
                $target_file = $target_dir .basename($_FILES["profile_pic"]["name"]);
                if (!file_exists($target_dir )) {
                    wp_mkdir_p( $target_dir );
                }

                if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file))
                {
                    $upload_dir_link= wp_upload_dir();
                    $target_dir_link = trailingslashit( $upload_dir_link['baseurl'].'/tender_uploads/'.$_REQUEST['user_id']);
                    $target_file_link = $target_dir_link .basename($_FILES["profile_pic"]["name"]);

                    $user_id=intval($_REQUEST['user_id']);
                    update_user_meta($user_id,'profile_pic',$target_file_link);
                    $result = array(
                        "success" => true,
                        "error" => "تصویر شما با موفقیت اپلود گردید.",
                        "src" => $target_file_link,
                    );
                }
                else
                {
                    $result = array(
                        "success" => false,
                        "error" => "هنگام اپلود فایل خطایی رخ داد. لطفا مجدد تلاش نمایید.",
                    );
                }


            }


        }


        echo json_encode($result);
        wp_die();
    }

    function tnd_shop_profile_pic_remove_callback()
    {
        header("Content-Type: application/json");
        $request= $_POST;
        $output=array();
        if ( isset($request['user_id']) && $request['user_id']!="")
        {
            $upload_dir= wp_upload_dir();
            $target_dir = trailingslashit( $upload_dir['basedir'].'/tender_uploads/'.$_REQUEST['user_id']);
            $target_file = $target_dir .end(explode('/',get_user_meta($request['user_id'],'profile_pic',true)));
            unlink($target_file);
            delete_user_meta($request['user_id'],'profile_pic');
            $output=array(
                'success' => true,
                'error' => 'تصویر پروفایل با موفقیت حذف گردید.'
            );
        }
        else
        {
            $output=array(
                'success' => false,
                'error' => 'خطای امنیتی رخ داده است',
            );
        }

        echo json_encode($output);
        wp_die();
    }

    function tnd_shop_profile_update_pass_callback()
    {
        header("Content-Type: application/json");
        $request= $_POST;
        $output=array();
        if (isset($request['current_pass']) && $request['current_pass']!="" && isset($request['new_pass']) && $request['new_pass']!="")
        {
            $c_user_id=get_current_user_id();

            if ($c_user_id == intval($request['user_id']))
            {
                if (sanitize_text_field($request['current_pass']) != sanitize_text_field($request['new_pass']))
                {
                    $userData = get_user_by('ID', $c_user_id);
                    if (wp_check_password($request['current_pass'], $userData->user_pass, $c_user_id))
                    {
                        wp_set_password(sanitize_text_field($request['new_pass']),$c_user_id);
                        $output=array(
                            'status' => 'password_set'
                        );
                    }
                    else
                    {
                        $output=array(
                            'status' => 'current_pass_false'
                        );
                    }
                }
                else
                {
                    $output=array(
                        'status' => 'passwords_are_same'
                    );
                }

            }
            else
            {
                $output=array(
                    'status' => 'not_safe'
                );
            }
        }
        else
        {
            $output=array(
                'status' => 'empty_fildes'
            );
        }

        echo json_encode($output);
        wp_die();
    }

    function tnd_shop_profile_shop_files_data_callback()
    {
        header("Content-Type: application/json");
        $allowedexts = array(
            "zip" => true,
            "rar" => true,
        );

        $files = File_Uploader::NormalizeFiles("shop_file");

        if (!isset($files[0]))
        {
            $result = array(
                "success" => false,
                "error" => "File data was submitted but is missing.",
                "errorcode" => "bad_input"
            );
        }
        else if (!$files[0]["success"])
        {
            $result = $files[0];
        }
        else if (!isset($allowedexts[strtolower($files[0]["ext"])]))
        {
            $result = array(
                "success" => false,
                "error" => "نوع فایل انتخاب شده معتبر نیست.. ",
                "errorcode" => "invalid_file_ext"
            );
        }
        else
        {
            // For chunked file uploads, get the current filename and starting position from the incoming headers.
            $name = File_Uploader::GetChunkFilename();
            if ($name !== false)
            {
                $startpos = File_Uploader::GetFileStartPosition();

                $result = array(
                    "success" => false,
                    "error" => ". نام فایل مدنظر معتبر نمی باشد",
                    "errorcode" => $startpos
                );
            }
            else
            {
                // [Do stuff with the file here.]
                // copy($files[0]["file"], __DIR__ . "/images/" . $id . "." . strtolower($files[0]["ext"]));
                $upload_dir= wp_upload_dir();
                $target_dir = trailingslashit( $upload_dir['basedir'].'/tender_uploads/'.$_REQUEST['user_id']);
                $target_file = $target_dir .basename($_FILES["shop_file"]["name"]);
                if (!file_exists($target_dir )) {
                    wp_mkdir_p( $target_dir );
                }

                if (move_uploaded_file($_FILES["shop_file"]["tmp_name"], $target_file))
                {
                    $upload_dir_link= wp_upload_dir();
                    $target_dir_link = trailingslashit( $upload_dir_link['baseurl'].'/tender_uploads/'.$_REQUEST['user_id']);
                    $target_file_link = $target_dir_link .basename($_FILES["shop_file"]["name"]);

                    $user_id=intval($_REQUEST['user_id']);
                    update_user_meta($user_id,'shop_file',$target_file_link);
                    $result = array(
                        "success" => true,
                        "error" => "اطلاعات فروشگاه/کارخانه برای کارشناس مربوطه ارسال گردید.پس از تایید اطلاعات میتوانید در مناقصات شرکت نمایید",
                    );
                }
                else
                {
                    $result = array(
                        "success" => false,
                        "error" => "هنگام اپلود فایل خطایی رخ داد. لطفا مجدد تلاش نمایید.",
                    );
                }


            }


        }


        echo json_encode($result);
        wp_die();
    }

    function tnd_shop_profile_data_callback()
    {
        header("Content-Type: application/json");
        $request= $_POST;
        $output=array();
        if ( isset($request['user_id']) && $request['user_id']!="")
        {
            $c_uid=get_current_user_id();
            if ($c_uid == intval($request['user_id']))
            {
                update_user_meta(intval($request['user_id']),'shop_name',sanitize_text_field($request['shop_name']));
                update_user_meta(intval($request['user_id']),'shop_manager_name',sanitize_text_field($request['shop_manager_name']));
                update_user_meta(intval($request['user_id']),'shop_address',sanitize_text_field($request['shop_address']));
                update_user_meta(intval($request['user_id']),'shop_tel',sanitize_text_field($request['shop_tel']));
                update_user_meta(intval($request['user_id']),'shop_code',sanitize_text_field($request['shop_code']));
                update_user_meta(intval($request['user_id']),'user_status','pending');
                update_user_meta(intval($request['user_id']),'send_files','true');
                $output=array(
                    'status' => 'true'
                );
            }
            else
            {
                $output=array(
                    'status' => 'not_secure'
                );
            }

        }
        else
        {
            $output=array(
                'status' => 'false'
            );
        }

        echo json_encode($output);
        wp_die();
    }




    function get_contractors_executed_tenders_callback()
    {
        header("Content-Type: application/json");
        $request= $_POST;
        $output=array();
        if ( isset($request['user_id']) && $request['user_id']!="")
        {
            $c_uid=get_current_user_id();
            if ($c_uid == intval($request['user_id']) and user_can( intval($request['user_id']), 'manage_options' ))
            {
                global $post;
                $args = array(
                    'post_type' => 'jahadTender',
                    'posts_per_page' => -1,
                    'meta_key'       => 'tender_status',
                    'meta_value'     => 'executed'
                );
                $query = new \WP_Query( $args );
                $result=array();
                if ( $query->have_posts() )
                {
                    $count=1;
                    while ( $query->have_posts() )
                    {
                        $query->the_post();
                        if (get_post_meta($post->ID, 'shop_tender_status',true) !='created')
                        {
                            $result[]=array(
                                'id' =>$post->ID,
                                'name' =>get_post_meta($post->ID,'tender_name',true),
                            );
                        }
                    }
                }
                wp_reset_query();
                $output=array(
                    'status' => 'true',
                    'result' =>$result
                );
            }
            else
            {
                $output=array(
                    'status' => 'not_secure'
                );
            }

        }
        else
        {
            $output=array(
                'status' => 'not_secure'
            );
        }

        echo json_encode($output);
        exit;
    }
    function edit_get_contractors_executed_tenders_callback()
    {
        header("Content-Type: application/json");
        $request= $_POST;
        $output=array();
        if ( isset($request['user_id']) && $request['user_id']!="")
        {
            $c_uid=get_current_user_id();
            if ($c_uid == intval($request['user_id']) and user_can( intval($request['user_id']), 'manage_options' ))
            {
                global $post;
                $args = array(
                    'post_type' => 'jahadTender',
                    'posts_per_page' => -1,
                    'meta_key'       => 'tender_status',
                    'meta_value'     => 'executed'
                );
                $query = new \WP_Query( $args );
                $result=array();
                if ( $query->have_posts() )
                {
                    $count=1;
                    while ( $query->have_posts() )
                    {
                        $query->the_post();
                        if ($post->ID ==get_post_meta(intval($request['tender_id']),'parent_tender_id',true))
                        {
                            $result[]=array(
                                'id' =>$post->ID,
                                'name' =>get_post_meta($post->ID,'tender_name',true),
                            );
                        }
                        else if (get_post_meta($post->ID, 'shop_tender_status',true) !='created')
                        {
                            $result[]=array(
                                'id' =>$post->ID,
                                'name' =>get_post_meta($post->ID,'tender_name',true),
                            );
                        }
                    }
                }
                wp_reset_query();
                $output=array(
                    'status' => 'true',
                    'result' =>$result
                );
            }
            else
            {
                $output=array(
                    'status' => 'not_secure'
                );
            }

        }
        else
        {
            $output=array(
                'status' => 'not_secure'
            );
        }

        echo json_encode($output);
        exit;
    }
    function new_shop_tender_upload_files_callback()
    {
//        require_once( ABSPATH . 'wp-content/plugins/tender/inc/ajax/fancy_file_uploader_helper.php' );
        header("Content-Type: application/json");
        $allowedexts = array(
            "zip" => true,
            "rar" => true,
        );

        $files = File_Uploader::NormalizeFiles("new_tender_files");

        if (!isset($files[0]))
        {
            $result = array(
                "success" => false,
                "error" => "File data was submitted but is missing.",
                "errorcode" => "bad_input"
            );
        }
        else if (!$files[0]["success"])
        {
            $result = $files[0];
        }
        else if (!isset($allowedexts[strtolower($files[0]["ext"])]))
        {
            $result = array(
                "success" => false,
                "error" => "نوع فایل انتخاب شده معتبر نیست.. ",
                "errorcode" => "invalid_file_ext"
            );
        }
        else
        {
            // For chunked file uploads, get the current filename and starting position from the incoming headers.
            $name = File_Uploader::GetChunkFilename();
            if ($name !== false)
            {
                $startpos = File_Uploader::GetFileStartPosition();

                $result = array(
                    "success" => false,
                    "error" => ". نام فایل مدنظر معتبر نمی باشد",
                    "errorcode" => $startpos
                );
            }
            else
            {

                if (user_can( $_REQUEST['user_id'], 'manage_options' ))
                {
                    $new_post = array(
                        'post_content' => '',
                        'post_status' => 'publish',
                        'post_date' => date('Y-m-d H:i:s'),
                        'post_author' => $_REQUEST['user_id'],
                        'post_type' => 'shopJahadTender'
                    );

                    $post_id = wp_insert_post( $new_post );
                    $upload_dir= wp_upload_dir();
                    $target_dir = trailingslashit( $upload_dir['basedir'].'/tender_uploads/tenders/'.$post_id);
                    $target_file = $target_dir .basename($_FILES["new_tender_files"]["name"]);
                    if (!file_exists($target_dir )) {
                        wp_mkdir_p( $target_dir );
                    }

                    if (move_uploaded_file($_FILES["new_tender_files"]["tmp_name"], $target_file))
                    {
                        $upload_dir_link= wp_upload_dir();
                        $target_dir_link = trailingslashit( $upload_dir_link['baseurl'].'/tender_uploads/tenders/'.$post_id);
                        $target_file_link = $target_dir_link .basename($_FILES["new_tender_files"]["name"]);

                        update_post_meta($post_id,'tender_files',$target_file_link);
                        $result = array(
                            "success" => true,
                            "error" =>$post_id,
                        );
                    }
                    else
                    {
                        $result = array(
                            "success" => false,
                            "error" => "هنگام اپلود فایل خطایی رخ داد. لطفا مجدد تلاش نمایید.",
                        );
                    }
                }
                else
                {
                    $result = array(
                        "success" => false,
                        "error" => "خطای امنیتی رخ داده است",
                    );
                }



            }


        }


        echo json_encode($result);
        wp_die();
    }
    function add_info_to_new_shop_tender_callback()
    {
        header("Content-Type: application/json");
        $request= $_POST;
        if (isset($request['user_id']) && $request['user_id'] != "")
        {
            if (user_can( $_REQUEST['user_id'], 'manage_options' ))
            {

                update_post_meta(intval($request['tender_id']),'parent_tender_id',intval($request['new_parent_tender_id']));
                update_post_meta(intval($request['tender_id']),'tender_date',sanitize_text_field($request['new_tender_date']));
                update_post_meta(intval($request['tender_id']),'tender_end_date',sanitize_text_field($request['new_tender_end_date']));

                if(sanitize_text_field($request['new_tender_only_one']) == "yes")
                {
                    update_post_meta(intval($request['tender_id']),'tender_only_one',sanitize_text_field($request['new_tender_only_one']));
                }


                update_post_meta(intval($request['tender_id']),'tender_status','not_executed');
                update_post_meta(intval($request['new_parent_tender_id']),'shop_tender_status','created');

                $output=array(
                    'success' => 'true',
                    'error' => 'مناقصه با موفقیت ثبت شد.',
                );


            }
            else
            {
                $output=array(
                    'success' => false,
                    'error' =>'خطای امنیتی رخ داده است',
                );
            }


        }



        echo json_encode($output);

        wp_die();
    }
    function get_all_shop_tenders_data_callback()
    {
        include_once(TND_INC_DIR.DIRECTORY_SEPARATOR.'vendors'.DIRECTORY_SEPARATOR.'jdf.php');
        header("Content-Type: application/json");
        $request = $_REQUEST;
        $column = array(
            'tnd_name',
            'tnd_area',
            'tnd_price',
            'tnd_date',
            'tnd_winner',
        );

//	$args=array(
//		'meta_query' => array(
//			array(
//				'meta'     => 'tnd_user_type',
//				'value'   => 'customer'
//			)
//		)
//	);
//
//	if (isset($request["search"]["value"]) )
//	{
//		if ($request["search"]["value"] !=null)
//		{
//			$args['meta_query'][]=array(
//				'meta'     => 'last_name',
//				'value'   => $request["search"]["value"],
//				'compare' => 'LIKE'
//			);
//			$args['meta_query'][]=array(
//				'meta'     => 'first_name',
//				'value'   => $request["search"]["value"],
//				'compare' => 'LIKE'
//			);
//		}
//
//	}
//
//	if(isset($request['order']))
//	{
//		$args['orderby']=$column[$request['order']['0']['column']];
//		$args['order']=$request['order']['0']['dir'];
//	}


        $args_all = array(
            'post_type' => 'shopJahadTender',
            'limit' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
        );
        $query_all = new \WP_Query( $args_all );
        $count_all = $query_all->found_posts;

        global $post;
        $args = array(
            'post_type' => 'shopJahadTender',
            'posts_per_page' => intval($request['length']),
            'limit' => intval($request['length']),
            'offset' => intval($request['start']),
            'orderby' => 'date',
            'order' => 'DESC',
        );
        $query = new \WP_Query( $args );
        if ( $query->have_posts() )
        {
            while ( $query->have_posts() )
            {
                $query->the_post();
                if (get_post_meta($post->ID,'tender_status',true) != 'archived')
                {
                    $sub_array = array();
                    $parent_tender_id=intval(get_post_meta($post->ID,'parent_tender_id',true));
                    $sub_array[] = '<a href="#" id="tender_info_btn" data-toggle="tooltip"
                     data-tender_id="'.$post->ID.'" 
                     data-tender_start_date="'.jdate('d F y',get_post_meta($post->ID,'tender_date',true)).'" 
                     data-tender_end_date="'.jdate('d F y',get_post_meta($post->ID,'tender_end_date',true)).'" 
                     data-tender_area="'.get_post_meta($parent_tender_id,'tender_area',true).'" 
                     data-tender_price="'.number_format(get_post_meta($parent_tender_id,'tender_proposed_price',true)).'" 
                     data-tender_system_type="'.get_post_meta($parent_tender_id,'tender_system_type',true).'"
                     data-tender_advisor="'.get_post_meta($parent_tender_id,'tender_advisor',true).'" 
                     data-tender_city="'.get_post_meta($parent_tender_id,'tender_city',true).'"  
                     data-tender_district="'.get_post_meta($parent_tender_id,'tender_district',true).'" 
                     data-tender_files="'.get_post_meta($parent_tender_id,'tender_files',true).'" 
                     data-tender_only_one="'.get_post_meta($parent_tender_id,'tender_only_one',true).'" 
                     data-original-title="مشاهده سایر اطلاعات  مناقصه اصلی (پیمانکار)">
                     <i class="fa fa-eye"></i>
                     '.get_post_meta($parent_tender_id,'tender_name',true).'
                      </a>';
//                    $sub_array[]='<button type="button" class="btn btn-warning bg-accent-4" id="tender_contributors_btn" name="tender_contributors_btn" data-parent_tender_id="'.get_post_meta($post->ID,'parent_tender_id',true).'" data-toggle="tooltip" data-placement="top" title="سایر اطلاعات مناقصه اصلی"><i class="fa fa-eye"></i> </button>';
                    if (metadata_exists('post',$post->ID,'tender_contributors'))
                    {
                        $temp_contr=get_post_meta($post->ID,'tender_contributors',true);
                        if(!($temp_contr == null || $temp_contr == ''))
                        {

                            $sub_array[]='<button type="button" class="btn btn-warning bg-accent-4" id="tender_contributors_btn" name="tender_contributors_btn" data-tender_id="'.$post->ID.'" data-toggle="tooltip" data-placement="top" title="لیست شرکت کنندگان در مناقصه"><i class="fa fa-users"></i> </button>';
                        }
                        else
                        {
                            $sub_array[]='-';

                        }

                    }
                    else
                    {
                        $sub_array[]='-';

                    }
                    $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_date',true));
                    $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_end_date',true));
                    $sub_array[] = '<a href="'.get_post_meta($post->ID,'tender_files',true).'" class="btn btn-warning" data-toggle="tooltip" data-original-title="دانلود مستندات مناقصه فروشگاه"><i class="fa fa-download"></i> </a>';

                    $operations="";
                    date_default_timezone_set('Asia/Tehran');
                    $end_date = date('Y-m-d',get_post_meta($post->ID,'tender_end_date',true));
                    $today = date('Y-m-d');
                    $tender_status=get_post_meta($post->ID,'tender_status',true);
                    $tender_expired=($today <= $end_date )?'false':'true';
                    json_encode($tender_expired);


	                $tender_only_one=(get_post_meta($post->ID,'tender_only_one',true) == 'yes')?'yes':'no';
	                $operations.='<button type="button" class="btn btn-blue-grey bg-darken-2" 
                        id="tender_edit_btn" name="tender_edit_btn" 
                        data-tender_id="'.$post->ID.'"  
                        data-parent_tender_id="'.get_post_meta($post->ID,'parent_tender_id',true).'"  
                        data-tender_date="'.get_post_meta($post->ID,'tender_date',true).'" 
                        data-tender_end_date="'.get_post_meta($post->ID,'tender_end_date',true).'" 
                        data-tender_date_persian="'.jdate('d F y',get_post_meta($post->ID,'tender_date',true)).'" 
                        data-tender_end_date_persian="'.jdate('d F y',get_post_meta($post->ID,'tender_end_date',true)).'" 
                        data-tender_files="'.get_post_meta($post->ID,'tender_files',true).'"
                        data-tender_only_one="'.$tender_only_one.'"
                        >
                        <i class="fa fa-edit"></i> 
                        ویرایش مناقصه</button>';

                    if ($tender_expired == 'true')
                    {
	                    if ($tender_status == 'not_executed')
	                    {
		                    $operations.='<button type="button" class="btn btn-success" id="run_tender_btn" name="run_tender_btn" data-tender_id="'.$post->ID.'"><i class="fa fa-hammer"></i> برگزاری مناقصه </button>';
	                    }
	                    else if($tender_status == 'executed')
	                    {
		                    $winner_id=get_post_meta($post->ID,'tender_winner',true);

		                    $temp_contrib=get_post_meta($post->ID,'tender_contributors',true);

		                    foreach ( $temp_contrib as $key => $value)
		                    {
			                    if ($value['customer_id'] == $winner_id)
			                    {
				                    $winner_file=$value['proposed_file'];
				                    break;
			                    }
		                    }
		                    $operations.='<button type="button" class="btn btn-deep-purple bg-darken-4" id="show_winner_btn" name="show_winner_btn" 
                            data-tender_id="'.$post->ID.'"  
                            data-winner_shop_name="'.get_user_meta($winner_id,'shop_name',true).'"  
                            data-winner_shop_manager_name="'.get_user_meta($winner_id,'shop_manager_name',true).'"
                            data-winner_shop_tel="'.get_user_meta($winner_id,'shop_tel',true).'"  
                            data-winner_shop_proposed_file="'.$winner_file.'" 
                            data-winner_confirm_file_link="'.get_post_meta($post->ID,'win_confirm_file_link',true).'"  
                            data-toggle="tooltip" data-placement="top" title="مشاهده برنده"><i class="fa fa-trophy"></i></button>'.'|';
//					$operations.='<button type="button" class="btn btn-info" id="edit_winner_btn" name="edit_winner_btn" data-tender_id="'.$post->ID.'"><i class="icon-edit"></i>ویرایش برنده</i></button>'.'|';
		                    $operations.='<button type="button" class="btn btn-success" id="finish_tender_btn" name="finish_tender_btn" data-tender_id="'.$post->ID.'" data-toggle="tooltip" data-placement="top" title="اتمام پروژه"><i class="fas fa-flag-checkered"></i></button>';
	                    }
	                    else if($tender_status == 'finished')
	                    {
		                    $operations.='<button type="button" class="btn btn-danger" id="archive_tender_btn" name="archive_tender_btn" data-tender_id="'.$post->ID.'" data-toggle="tooltip" data-placement="top" title="ارشیو کردن پروژه"><i class="fa fa-archive"></i> </button>';
	                    }
                    }

	                $operations.='<button type="button" class="btn btn-danger" id="delete_shop_tender_btn" name="delete_shop_tender_btn" data-tender_id="'.$post->ID.'" data-toggle="tooltip" data-placement="top" title="حذف "><i class="fas fa-trash"></i></button>';

	                $sub_array[] = $operations;


                    if ($tender_expired == 'true')
                    {
                        $sub_array[] = 'true';
                        if ($tender_status == 'not_executed')
                        {
                            $sub_array[]='not_executed';
                        }
                        else if($tender_status == 'executed')
                        {
                            $sub_array[]='executed';
                        }
                        else
                        {
                            $sub_array[]='finished';
                        }

                    }
                    else
                    {
                        $sub_array[] = 'false';
                        $sub_array[] = 'not_executed';
                    }
                    $data[] = $sub_array;

                }
            }
        }
        wp_reset_query();

        $output = array(
            "draw"    => intval($_GET["draw"]),
            "recordsTotal"  =>  intval($count_all),
            "recordsFiltered" => intval($count_all),
            "data" => $data
        );


        echo json_encode($output);

        wp_die();
    }
    function get_all_shop_archived_tenders_data_callback()
    {
        include_once(TND_INC_DIR.DIRECTORY_SEPARATOR.'vendors'.DIRECTORY_SEPARATOR.'jdf.php');
        header("Content-Type: application/json");

        $column = array(
            'tnd_name',
            'tnd_area',
            'tnd_price',
            'tnd_date',
            'tnd_winner',
        );

//	$args=array(
//		'meta_query' => array(
//			array(
//				'meta'     => 'tnd_user_type',
//				'value'   => 'customer'
//			)
//		)
//	);
//
//	if (isset($request["search"]["value"]) )
//	{
//		if ($request["search"]["value"] !=null)
//		{
//			$args['meta_query'][]=array(
//				'meta'     => 'last_name',
//				'value'   => $request["search"]["value"],
//				'compare' => 'LIKE'
//			);
//			$args['meta_query'][]=array(
//				'meta'     => 'first_name',
//				'value'   => $request["search"]["value"],
//				'compare' => 'LIKE'
//			);
//		}
//
//	}
//
//	if(isset($request['order']))
//	{
//		$args['orderby']=$column[$request['order']['0']['column']];
//		$args['order']=$request['order']['0']['dir'];
//	}



        global $post;
        $args = array(
            'post_type' => 'shopjahadTender',
            'posts_per_page' => -1,
        );
        $query = new \WP_Query( $args );
        if ( $query->have_posts() )
        {
            $count=1;
            while ( $query->have_posts() )
            {
                $query->the_post();
                if (get_post_meta($post->ID,'tender_status',true) == 'archived')
                {
                    $sub_array = array();
                    $parent_tender_id=intval(get_post_meta($post->ID,'parent_tender_id',true));
                    $sub_array[] = '<a href="#" id="tender_info_btn" data-toggle="tooltip"
                     data-tender_id="'.$post->ID.'" 
                     data-tender_start_date="'.jdate('d F y',get_post_meta($post->ID,'tender_date',true)).'" 
                     data-tender_end_date="'.jdate('d F y',get_post_meta($post->ID,'tender_end_date',true)).'" 
                     data-tender_area="'.get_post_meta($parent_tender_id,'tender_area',true).'" 
                     data-tender_price="'.number_format(get_post_meta($parent_tender_id,'tender_proposed_price',true)).'" 
                     data-tender_system_type="'.get_post_meta($parent_tender_id,'tender_system_type',true).'"
                     data-tender_advisor="'.get_post_meta($parent_tender_id,'tender_advisor',true).'" 
                     data-tender_city="'.get_post_meta($parent_tender_id,'tender_city',true).'"  
                     data-tender_district="'.get_post_meta($parent_tender_id,'tender_district',true).'" 
                     data-tender_files="'.get_post_meta($parent_tender_id,'tender_files',true).'" 
                     data-tender_only_one="'.get_post_meta($parent_tender_id,'tender_only_one',true).'" 
                     data-original-title="مشاهده سایر اطلاعات  مناقصه اصلی (پیمانکار)">
                     <i class="fa fa-eye"></i>
                     '.get_post_meta($parent_tender_id,'tender_name',true).'
                      </a>';
//                    $sub_array[]='<button type="button" class="btn btn-warning bg-accent-4" id="tender_contributors_btn" name="tender_contributors_btn" data-parent_tender_id="'.get_post_meta($post->ID,'parent_tender_id',true).'" data-toggle="tooltip" data-placement="top" title="سایر اطلاعات مناقصه اصلی"><i class="fa fa-eye"></i> </button>';
                    if (metadata_exists('post',$post->ID,'tender_contributors'))
                    {
                        $temp_contr=get_post_meta($post->ID,'tender_contributors',true);
                        if(!($temp_contr == null || $temp_contr == ''))
                        {

                            $sub_array[]='<button type="button" class="btn btn-warning bg-accent-4" id="tender_contributors_btn" name="tender_contributors_btn" data-tender_id="'.$post->ID.'" data-toggle="tooltip" data-placement="top" title="لیست شرکت کنندگان در مناقصه"><i class="fa fa-users"></i> </button>';
                        }
                        else
                        {
                            $sub_array[]='-';

                        }

                    }
                    else
                    {
                        $sub_array[]='-';

                    }
                    $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_date',true));
                    $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_end_date',true));
                    $sub_array[] = '<a href="'.get_post_meta($post->ID,'tender_files',true).'" class="btn btn-warning" data-toggle="tooltip" data-original-title="دانلود مستندات مناقصه فروشگاه"><i class="fa fa-download"></i> </a>';

                    $operations="";
                    date_default_timezone_set('Asia/Tehran');
                    $date = date('Y-m-d',get_post_meta($post->ID,'tender_date',true));
                    $today = date('Y-m-d');
                    $tender_status=get_post_meta($post->ID,'tender_status',true);
                    $tender_expired=($today > $date )?'true':'false';

                    $winner_id=get_post_meta($post->ID,'tender_winner',true);

                    $temp_contrib=get_post_meta($post->ID,'tender_contributors',true);

                    foreach ( $temp_contrib as $key => $value)
                    {
                        if ($value['customer_id'] == $winner_id)
                        {
                            $winner_file=$value['proposed_file'];
                            break;
                        }
                    }
                    $operations.='<button type="button" class="btn btn-deep-purple bg-darken-4" id="show_winner_btn" name="show_winner_btn" 
                            data-tender_id="'.$post->ID.'"  
                            data-winner_shop_name="'.get_user_meta($winner_id,'shop_name',true).'"  
                            data-winner_shop_manager_name="'.get_user_meta($winner_id,'shop_manager_name',true).'"
                            data-winner_shop_tel="'.get_user_meta($winner_id,'shop_tel',true).'"  
                            data-winner_shop_proposed_file="'.$winner_file.'" 
                            data-winner_confirm_file_link="'.get_post_meta($post->ID,'win_confirm_file_link',true).'"  
                            data-toggle="tooltip" data-placement="top" title="مشاهده برنده"><i class="fa fa-trophy"></i></button>';
                    $sub_array[] = $operations;


                    if ($tender_expired == 'true')
                    {
                        $sub_array[] = 'true';
                        if ($tender_status == 'not_executed')
                        {
                            $sub_array[]='not_executed';
                        }
                        else if($tender_status == 'executed')
                        {
                            $sub_array[]='executed';
                        }
                        else
                        {
                            $sub_array[]='finished';
                        }
                    }
                    else
                    {
                        $sub_array[] = 'false';
                        $sub_array[] = 'not_executed';
                    }
                    $data[] = $sub_array;
                    $count++;
                }
            }
        }
        wp_reset_query();

        $output = array(
            "draw"    => intval($_GET["draw"]),
            "recordsTotal"  =>  intval($count),
            "recordsFiltered" => intval($count),
            "data" => $data
        );


        echo json_encode($output);

        wp_die();
    }
    function edit_shop_tender_callback()
    {
        header("Content-Type: application/json");
        $request= $_POST;
        if (isset($request['user_id']) && $request['user_id'] != "")
        {
            if (user_can( $_REQUEST['user_id'], 'manage_options' ))
            {

                update_post_meta(intval($request['tender_id']),'tender_date',sanitize_text_field($request['edit_tender_date']));
                update_post_meta(intval($request['tender_id']),'tender_end_date',sanitize_text_field($request['edit_tender_end_date']));

                if(sanitize_text_field($request['edit_tender_only_one']) == "yes")
                {
                    update_post_meta(intval($request['tender_id']),'tender_only_one','yes');
                }
                else
                {
                    update_post_meta(intval($request['tender_id']),'tender_only_one','no');
                }


                update_post_meta(intval($request['tender_id']),'tender_status','not_executed');


                delete_post_meta(get_post_meta(intval($request['tender_id']),'parent_tender_id',true), 'shop_tender_status' );

                update_post_meta(intval($request['tender_id']),'parent_tender_id',intval($request['edit_parent_tender_id']));
                update_post_meta(intval($request['edit_parent_tender_id']),'shop_tender_status','created');

                $output=array(
                    'success' => 'true',
                    'error' => 'مناقصه با موفقیت ویرایش شد.',
                );


            }
            else
            {
                $output=array(
                    'success' => false,
                    'error' =>'خطای امنیتی رخ داده است',
                );
            }


        }



        echo json_encode($output);

        wp_die();
    }
    function delete_shop_tender_callback()
    {
        header("Content-Type: application/json");
        $request= $_POST;
        if (isset($request['user_id']) && $request['user_id'] != "")
        {
            if (user_can( $_REQUEST['user_id'], 'manage_options' ))
            {

              $parent_tender_id=get_post_meta(intval($request['tender_id']),'parent_tender_id',true);

              delete_post_meta($parent_tender_id,'shop_tender_status');
              wp_delete_post(intval($request['tender_id']),true);
                $output=array(
                    'success' => 'true',
                    'error' => 'مناقصه با موفقیت حذف شد.',
                    'tender_id' => $parent_tender_id,
                );
            }
            else
            {
                $output=array(
                    'success' => false,
                    'error' =>'خطای امنیتی رخ داده است',
                );
            }


        }



        echo json_encode($output);

        wp_die();
    }
    function get_shop_tender_contributors_callback()
    {
        header("Content-Type: application/json");
        $request=$_POST;
        $output=array();
        if (isset($request['user_id']) && $request['user_id'] != "")
        {
            if (user_can( $_REQUEST['user_id'], 'manage_options' ))
            {
                date_default_timezone_set('Asia/Tehran');
                $end_date = date('Y-m-d',get_post_meta(intval($request['tender_id']),'tender_end_date',true));
                $today = date('Y-m-d');
                $tender_expired=($today <= $end_date )?'false':'true';
                json_encode($tender_expired);

                if ($tender_expired == 'true')
                {
                    foreach (get_post_meta(intval($request['tender_id']),'tender_contributors',true) as $key => $value)
                    {
                        $temp_shop_name=get_user_meta( intval($value['customer_id']), 'shop_name', true );
                        $temp_manager_name=get_user_meta( intval($value['customer_id']), 'shop_manager_name', true );
                        $temp_manager_tel=get_user_meta( intval($value['customer_id']), 'shop_tel', true );
                        $temp_shop_proppsed_file= $value['proposed_file'];

                        $output['contributors'][]=array(
                            $temp_shop_name,
                            $temp_manager_name,
                            $temp_manager_tel,
                            $temp_shop_proppsed_file,
                        );
                    }
                    $output['success']= 'true';
                }
                else
                {
                    foreach (get_post_meta(intval($request['tender_id']),'tender_contributors',true) as $key => $value)
                    {
                        $temp_shop_name=get_user_meta( intval($value['customer_id']), 'shop_name', true );
                        $temp_manager_name=get_user_meta( intval($value['customer_id']), 'shop_manager_name', true );
                        $temp_manager_tel=get_user_meta( intval($value['customer_id']), 'shop_tel', true );
                        $proposed_file='-';

                        $output['contributors'][]=array(
                            $temp_shop_name,
                            $temp_manager_name,
                            $temp_manager_tel,
                            $proposed_file,
                        );
                    }
                    $output['success']= 'true';
                }

            }
            else
            {
                $output=array(
                    'success' => 'false',
                    'error' =>'خطای امنیتی رخ داده است',
                );
            }


        }

	    $parent_tender_id=intval(get_post_meta($request['tender_id'],'parent_tender_id',true));
	    $output['tender_title']=get_post_meta($parent_tender_id,'tender_name',true);
        echo json_encode($output);

        wp_die();
    }

    function get_shop_tender_contributors_and_files_callback()
    {
        header("Content-Type: application/json");
        $request=$_POST;
        $output=array();
        if (isset($request['user_id']) && $request['user_id'] != "")
        {
            if (user_can( $_REQUEST['user_id'], 'manage_options' ))
            {
                foreach (get_post_meta(intval($request['tender_id']),'tender_contributors',true) as $key => $value)
                {
                    $temp_shop_name=get_user_meta( intval($value['customer_id']), 'shop_name', true );
                    $temp_manager_name=get_user_meta( intval($value['customer_id']), 'shop_manager_name', true );
                    $temp_manager_tel=get_user_meta( intval($value['customer_id']), 'shop_tel', true );
                    $temp_shop_proppsed_file= '<a href="'.$value['proposed_file'].'" class="btn btn-warning"><i class="icon-cloud-download3"></i> </a>';
                    $opr='<button type="button" class="btn btn-success bg-accent-4" id="tender_winer_btn" name="tender_winer_btn" data-customer_id="'.intval($value['customer_id']).'"  data-tender_id="'.intval($request['tender_id']).'" data-toggle="tooltip" data-original-title="تایید به عنوان برنده مناقصه"><i class="icon-user-check"></i> تایید برنده</button>';


                    $output['contributors'][]=array(
                        $opr,
                        $temp_shop_name,
                        $temp_manager_name,
                        $temp_manager_tel,
                        $temp_shop_proppsed_file,
                    );
                }
                $output['success']= 'true';
            }
            else
            {
                $output=array(
                    'success' => 'false',
                    'error' =>'خطای امنیتی رخ داده است',
                );
            }


        }
        echo json_encode($output);

        wp_die();
    }




    function get_contractors_executed_tenders_for_factory_callback()
    {
        header("Content-Type: application/json");
        $request= $_POST;
        $output=array();
        if ( isset($request['user_id']) && $request['user_id']!="")
        {
            $c_uid=get_current_user_id();
            if ($c_uid == intval($request['user_id']) and user_can( intval($request['user_id']), 'manage_options' ))
            {
                global $post;
                $args = array(
                    'post_type' => 'jahadTender',
                    'posts_per_page' => -1,
                    'meta_key'       => 'tender_status',
                    'meta_value'     => 'executed'
                );
                $query = new \WP_Query( $args );
                $result=array();
                if ( $query->have_posts() )
                {
                    $count=1;
                    while ( $query->have_posts() )
                    {
                        $query->the_post();
                        if (get_post_meta($post->ID, 'factory_tender_status',true) !='created')
                        {
                            $result[]=array(
                                'id' =>$post->ID,
                                'name' =>get_post_meta($post->ID,'tender_name',true),
                            );
                        }
                    }
                }
                wp_reset_query();
                $output=array(
                    'status' => 'true',
                    'result' =>$result
                );
            }
            else
            {
                $output=array(
                    'status' => 'not_secure'
                );
            }

        }
        else
        {
            $output=array(
                'status' => 'not_secure'
            );
        }

        echo json_encode($output);
        exit;
    }
    function edit_get_contractors_executed_tenders_for_factory_callback()
    {
        header("Content-Type: application/json");
        $request= $_POST;
        $output=array();
        if ( isset($request['user_id']) && $request['user_id']!="")
        {
            $c_uid=get_current_user_id();
            if ($c_uid == intval($request['user_id']) and user_can( intval($request['user_id']), 'manage_options' ))
            {
                global $post;
                $args = array(
                    'post_type' => 'jahadTender',
                    'posts_per_page' => -1,
                    'meta_key'       => 'tender_status',
                    'meta_value'     => 'executed'
                );
                $query = new \WP_Query( $args );
                $result=array();
                if ( $query->have_posts() )
                {
                    $count=1;
                    while ( $query->have_posts() )
                    {
                        $query->the_post();
                        if ($post->ID ==get_post_meta(intval($request['tender_id']),'parent_tender_id',true))
                        {
                            $result[]=array(
                                'id' =>$post->ID,
                                'name' =>get_post_meta($post->ID,'tender_name',true),
                            );
                        }
                        else if (get_post_meta($post->ID, 'factory_tender_status',true) !='created')
                        {
                            $result[]=array(
                                'id' =>$post->ID,
                                'name' =>get_post_meta($post->ID,'tender_name',true),
                            );
                        }
                    }
                }
                wp_reset_query();
                $output=array(
                    'status' => 'true',
                    'result' =>$result
                );
            }
            else
            {
                $output=array(
                    'status' => 'not_secure'
                );
            }

        }
        else
        {
            $output=array(
                'status' => 'not_secure'
            );
        }

        echo json_encode($output);
        exit;
    }
    function new_factory_tender_upload_files_callback()
    {
//        require_once( ABSPATH . 'wp-content/plugins/tender/inc/ajax/fancy_file_uploader_helper.php' );
        header("Content-Type: application/json");
        $allowedexts = array(
            "zip" => true,
            "rar" => true,
        );

        $files = File_Uploader::NormalizeFiles("new_tender_files");

        if (!isset($files[0]))
        {
            $result = array(
                "success" => false,
                "error" => "File data was submitted but is missing.",
                "errorcode" => "bad_input"
            );
        }
        else if (!$files[0]["success"])
        {
            $result = $files[0];
        }
        else if (!isset($allowedexts[strtolower($files[0]["ext"])]))
        {
            $result = array(
                "success" => false,
                "error" => "نوع فایل انتخاب شده معتبر نیست.. ",
                "errorcode" => "invalid_file_ext"
            );
        }
        else
        {
            // For chunked file uploads, get the current filename and starting position from the incoming headers.
            $name = File_Uploader::GetChunkFilename();
            if ($name !== false)
            {
                $startpos = File_Uploader::GetFileStartPosition();

                $result = array(
                    "success" => false,
                    "error" => ". نام فایل مدنظر معتبر نمی باشد",
                    "errorcode" => $startpos
                );
            }
            else
            {

                if (user_can( $_REQUEST['user_id'], 'manage_options' ))
                {
                    $new_post = array(
                        'post_content' => '',
                        'post_status' => 'publish',
                        'post_date' => date('Y-m-d H:i:s'),
                        'post_author' => $_REQUEST['user_id'],
                        'post_type' => 'factoryJahadTender'
                    );

                    $post_id = wp_insert_post( $new_post );
                    $upload_dir= wp_upload_dir();
                    $target_dir = trailingslashit( $upload_dir['basedir'].'/tender_uploads/tenders/'.$post_id);
                    $target_file = $target_dir .basename($_FILES["new_tender_files"]["name"]);
                    if (!file_exists($target_dir )) {
                        wp_mkdir_p( $target_dir );
                    }

                    if (move_uploaded_file($_FILES["new_tender_files"]["tmp_name"], $target_file))
                    {
                        $upload_dir_link= wp_upload_dir();
                        $target_dir_link = trailingslashit( $upload_dir_link['baseurl'].'/tender_uploads/tenders/'.$post_id);
                        $target_file_link = $target_dir_link .basename($_FILES["new_tender_files"]["name"]);

                        update_post_meta($post_id,'tender_files',$target_file_link);
                        $result = array(
                            "success" => true,
                            "error" =>$post_id,
                        );
                    }
                    else
                    {
                        $result = array(
                            "success" => false,
                            "error" => "هنگام اپلود فایل خطایی رخ داد. لطفا مجدد تلاش نمایید.",
                        );
                    }
                }
                else
                {
                    $result = array(
                        "success" => false,
                        "error" => "خطای امنیتی رخ داده است",
                    );
                }



            }


        }


        echo json_encode($result);
        wp_die();
    }
    function add_info_to_new_factory_tender_callback()
    {
        header("Content-Type: application/json");
        $request= $_POST;
        if (isset($request['user_id']) && $request['user_id'] != "")
        {
            if (user_can( $_REQUEST['user_id'], 'manage_options' ))
            {

                update_post_meta(intval($request['tender_id']),'parent_tender_id',intval($request['new_parent_tender_id']));
                update_post_meta(intval($request['tender_id']),'tender_date',sanitize_text_field($request['new_tender_date']));
                update_post_meta(intval($request['tender_id']),'tender_end_date',sanitize_text_field($request['new_tender_end_date']));

                if(sanitize_text_field($request['new_tender_only_one']) == "yes")
                {
                    update_post_meta(intval($request['tender_id']),'tender_only_one',sanitize_text_field($request['new_tender_only_one']));
                }


                update_post_meta(intval($request['tender_id']),'tender_status','not_executed');
                update_post_meta(intval($request['new_parent_tender_id']),'factory_tender_status','created');

                $output=array(
                    'success' => 'true',
                    'error' => 'مناقصه با موفقیت ثبت شد.',
                );


            }
            else
            {
                $output=array(
                    'success' => false,
                    'error' =>'خطای امنیتی رخ داده است',
                );
            }


        }



        echo json_encode($output);

        wp_die();
    }
    function get_all_factory_tenders_data_callback()
    {
        include_once(TND_INC_DIR.DIRECTORY_SEPARATOR.'vendors'.DIRECTORY_SEPARATOR.'jdf.php');
        header("Content-Type: application/json");
        $request = $_REQUEST;
        $column = array(
            'tnd_name',
            'tnd_area',
            'tnd_price',
            'tnd_date',
            'tnd_winner',
        );

//	$args=array(
//		'meta_query' => array(
//			array(
//				'meta'     => 'tnd_user_type',
//				'value'   => 'customer'
//			)
//		)
//	);
//
//	if (isset($request["search"]["value"]) )
//	{
//		if ($request["search"]["value"] !=null)
//		{
//			$args['meta_query'][]=array(
//				'meta'     => 'last_name',
//				'value'   => $request["search"]["value"],
//				'compare' => 'LIKE'
//			);
//			$args['meta_query'][]=array(
//				'meta'     => 'first_name',
//				'value'   => $request["search"]["value"],
//				'compare' => 'LIKE'
//			);
//		}
//
//	}
//
//	if(isset($request['order']))
//	{
//		$args['orderby']=$column[$request['order']['0']['column']];
//		$args['order']=$request['order']['0']['dir'];
//	}


        $args_all = array(
            'post_type' => 'factoryJahadTender',
            'limit' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
        );
        $query_all = new \WP_Query( $args_all );
        $count_all = $query_all->found_posts;

        global $post;
        $args = array(
            'post_type' => 'factoryJahadTender',
            'posts_per_page' => intval($request['length']),
            'limit' => intval($request['length']),
            'offset' => intval($request['start']),
            'orderby' => 'date',
            'order' => 'DESC',
        );
        $query = new \WP_Query( $args );
        if ( $query->have_posts() )
        {
            while ( $query->have_posts() )
            {
                $query->the_post();
                if (get_post_meta($post->ID,'tender_status',true) != 'archived')
                {
                    $sub_array = array();
                    $parent_tender_id=intval(get_post_meta($post->ID,'parent_tender_id',true));
                    $sub_array[] = '<a href="#" id="tender_info_btn" data-toggle="tooltip"
                     data-tender_id="'.$post->ID.'" 
                     data-tender_start_date="'.jdate('d F y',get_post_meta($post->ID,'tender_date',true)).'" 
                     data-tender_end_date="'.jdate('d F y',get_post_meta($post->ID,'tender_end_date',true)).'" 
                     data-tender_area="'.get_post_meta($parent_tender_id,'tender_area',true).'" 
                     data-tender_price="'.number_format(get_post_meta($parent_tender_id,'tender_proposed_price',true)).'" 
                     data-tender_system_type="'.get_post_meta($parent_tender_id,'tender_system_type',true).'"
                     data-tender_advisor="'.get_post_meta($parent_tender_id,'tender_advisor',true).'" 
                     data-tender_city="'.get_post_meta($parent_tender_id,'tender_city',true).'"  
                     data-tender_district="'.get_post_meta($parent_tender_id,'tender_district',true).'" 
                     data-tender_files="'.get_post_meta($parent_tender_id,'tender_files',true).'" 
                     data-tender_only_one="'.get_post_meta($parent_tender_id,'tender_only_one',true).'" 
                     data-original-title="مشاهده سایر اطلاعات  مناقصه اصلی (پیمانکار)">
                     <i class="fa fa-eye"></i>
                     '.get_post_meta($parent_tender_id,'tender_name',true).'
                      </a>';
//                    $sub_array[]='<button type="button" class="btn btn-warning bg-accent-4" id="tender_contributors_btn" name="tender_contributors_btn" data-parent_tender_id="'.get_post_meta($post->ID,'parent_tender_id',true).'" data-toggle="tooltip" data-placement="top" title="سایر اطلاعات مناقصه اصلی"><i class="fa fa-eye"></i> </button>';
                    if (metadata_exists('post',$post->ID,'tender_contributors'))
                    {
                        $temp_contr=get_post_meta($post->ID,'tender_contributors',true);
                        if(!($temp_contr == null || $temp_contr == ''))
                        {

                            $sub_array[]='<button type="button" class="btn btn-warning bg-accent-4" id="tender_contributors_btn" name="tender_contributors_btn" data-tender_id="'.$post->ID.'" data-toggle="tooltip" data-placement="top" title="لیست شرکت کنندگان در مناقصه"><i class="fa fa-users"></i> </button>';
                        }
                        else
                        {
                            $sub_array[]='-';

                        }

                    }
                    else
                    {
                        $sub_array[]='-';

                    }
                    $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_date',true));
                    $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_end_date',true));
                    $sub_array[] = '<a href="'.get_post_meta($post->ID,'tender_files',true).'" class="btn btn-warning" data-toggle="tooltip" data-original-title="دانلود مستندات مناقصه فروشگاه"><i class="fa fa-download"></i> </a>';

                    $operations="";
                    date_default_timezone_set('Asia/Tehran');
                    $end_date = date('Y-m-d',get_post_meta($post->ID,'tender_end_date',true));
                    $today = date('Y-m-d');
                    $tender_status=get_post_meta($post->ID,'tender_status',true);
                    $tender_expired=($today <= $end_date )?'false':'true';
                    json_encode($tender_expired);

	                $tender_only_one=(get_post_meta($post->ID,'tender_only_one',true) == 'yes')?'yes':'no';
	                $operations.='<button type="button" class="btn btn-blue-grey bg-darken-2" 
                        id="tender_edit_btn" name="tender_edit_btn" 
                        data-tender_id="'.$post->ID.'"  
                        data-parent_tender_id="'.get_post_meta($post->ID,'parent_tender_id',true).'"  
                        data-tender_date="'.get_post_meta($post->ID,'tender_date',true).'" 
                        data-tender_end_date="'.get_post_meta($post->ID,'tender_end_date',true).'" 
                        data-tender_date_persian="'.jdate('d F y',get_post_meta($post->ID,'tender_date',true)).'" 
                        data-tender_end_date_persian="'.jdate('d F y',get_post_meta($post->ID,'tender_end_date',true)).'" 
                        data-tender_files="'.get_post_meta($post->ID,'tender_files',true).'"
                        data-tender_only_one="'.$tender_only_one.'"
                        >
                        <i class="fa fa-edit"></i> 
                        ویرایش مناقصه</button>';

                    if ($tender_expired == 'true')
                    {
	                    if ($tender_status == 'not_executed')
	                    {
		                    $operations.='<button type="button" class="btn btn-success" id="run_tender_btn" name="run_tender_btn" data-tender_id="'.$post->ID.'"><i class="fa fa-hammer"></i> برگزاری مناقصه </button>';
	                    }
	                    else if($tender_status == 'executed')
	                    {
		                    $winner_id=get_post_meta($post->ID,'tender_winner',true);

		                    $temp_contrib=get_post_meta($post->ID,'tender_contributors',true);

		                    foreach ( $temp_contrib as $key => $value)
		                    {
			                    if ($value['customer_id'] == $winner_id)
			                    {
				                    $winner_file=$value['proposed_file'];
				                    break;
			                    }
		                    }
		                    $operations.='<button type="button" class="btn btn-deep-purple bg-darken-4" id="show_winner_btn" name="show_winner_btn" 
                            data-tender_id="'.$post->ID.'"  
                            data-winner_shop_name="'.get_user_meta($winner_id,'shop_name',true).'"  
                            data-winner_shop_manager_name="'.get_user_meta($winner_id,'shop_manager_name',true).'"
                            data-winner_shop_tel="'.get_user_meta($winner_id,'shop_tel',true).'"  
                            data-winner_shop_proposed_file="'.$winner_file.'" 
                            data-winner_confirm_file_link="'.get_post_meta($post->ID,'win_confirm_file_link',true).'"  
                            data-toggle="tooltip" data-placement="top" title="مشاهده برنده"><i class="fa fa-trophy"></i></button>'.'|';
//					$operations.='<button type="button" class="btn btn-info" id="edit_winner_btn" name="edit_winner_btn" data-tender_id="'.$post->ID.'"><i class="icon-edit"></i>ویرایش برنده</i></button>'.'|';
		                    $operations.='<button type="button" class="btn btn-success" id="finish_tender_btn" name="finish_tender_btn" data-tender_id="'.$post->ID.'" data-toggle="tooltip" data-placement="top" title="اتمام پروژه"><i class="fas fa-flag-checkered"></i></button>';
	                    }
	                    else if($tender_status == 'finished')
	                    {
		                    $operations.='<button type="button" class="btn btn-danger" id="archive_tender_btn" name="archive_tender_btn" data-tender_id="'.$post->ID.'" data-toggle="tooltip" data-placement="top" title="ارشیو کردن پروژه"><i class="fa fa-archive"></i> </button>';
	                    }
                    }

	                $operations.='<button type="button" class="btn btn-danger" id="delete_factory_tender_btn" name="delete_factory_tender_btn" data-tender_id="'.$post->ID.'" data-toggle="tooltip" data-placement="top" title="حذف "><i class="fas fa-trash"></i></button>';

	                $sub_array[] = $operations;


                    if ($tender_expired == 'true')
                    {
                        $sub_array[] = 'true';
                        if ($tender_status == 'not_executed')
                        {
                            $sub_array[]='not_executed';
                        }
                        else if($tender_status == 'executed')
                        {
                            $sub_array[]='executed';
                        }
                        else
                        {
                            $sub_array[]='finished';
                        }

                    }
                    else
                    {
                        $sub_array[] = 'false';
                        $sub_array[] = 'not_executed';
                    }
                    $data[] = $sub_array;

                }
            }
        }
        wp_reset_query();

        $output = array(
            "draw"    => intval($_GET["draw"]),
            "recordsTotal"  =>  intval($count_all),
            "recordsFiltered" => intval($count_all),
            "data" => $data
        );


        echo json_encode($output);

        wp_die();
    }
    function get_all_factory_archived_tenders_data_callback()
    {
        include_once(TND_INC_DIR.DIRECTORY_SEPARATOR.'vendors'.DIRECTORY_SEPARATOR.'jdf.php');
        header("Content-Type: application/json");

        $column = array(
            'tnd_name',
            'tnd_area',
            'tnd_price',
            'tnd_date',
            'tnd_winner',
        );

//	$args=array(
//		'meta_query' => array(
//			array(
//				'meta'     => 'tnd_user_type',
//				'value'   => 'customer'
//			)
//		)
//	);
//
//	if (isset($request["search"]["value"]) )
//	{
//		if ($request["search"]["value"] !=null)
//		{
//			$args['meta_query'][]=array(
//				'meta'     => 'last_name',
//				'value'   => $request["search"]["value"],
//				'compare' => 'LIKE'
//			);
//			$args['meta_query'][]=array(
//				'meta'     => 'first_name',
//				'value'   => $request["search"]["value"],
//				'compare' => 'LIKE'
//			);
//		}
//
//	}
//
//	if(isset($request['order']))
//	{
//		$args['orderby']=$column[$request['order']['0']['column']];
//		$args['order']=$request['order']['0']['dir'];
//	}



        global $post;
        $args = array(
            'post_type' => 'factoryjahadTender',
            'posts_per_page' => -1,
        );
        $query = new \WP_Query( $args );
        if ( $query->have_posts() )
        {
            $count=1;
            while ( $query->have_posts() )
            {
                $query->the_post();
                if (get_post_meta($post->ID,'tender_status',true) == 'archived')
                {
                    $sub_array = array();
                    $parent_tender_id=intval(get_post_meta($post->ID,'parent_tender_id',true));
                    $sub_array[] = '<a href="#" id="tender_info_btn" data-toggle="tooltip"
                     data-tender_id="'.$post->ID.'" 
                     data-tender_start_date="'.jdate('d F y',get_post_meta($post->ID,'tender_date',true)).'" 
                     data-tender_end_date="'.jdate('d F y',get_post_meta($post->ID,'tender_end_date',true)).'" 
                     data-tender_area="'.get_post_meta($parent_tender_id,'tender_area',true).'" 
                     data-tender_price="'.number_format(get_post_meta($parent_tender_id,'tender_proposed_price',true)).'" 
                     data-tender_system_type="'.get_post_meta($parent_tender_id,'tender_system_type',true).'"
                     data-tender_advisor="'.get_post_meta($parent_tender_id,'tender_advisor',true).'" 
                     data-tender_city="'.get_post_meta($parent_tender_id,'tender_city',true).'"  
                     data-tender_district="'.get_post_meta($parent_tender_id,'tender_district',true).'" 
                     data-tender_files="'.get_post_meta($parent_tender_id,'tender_files',true).'" 
                     data-tender_only_one="'.get_post_meta($parent_tender_id,'tender_only_one',true).'" 
                     data-original-title="مشاهده سایر اطلاعات  مناقصه اصلی (پیمانکار)">
                     <i class="fa fa-eye"></i>
                     '.get_post_meta($parent_tender_id,'tender_name',true).'
                      </a>';
//                    $sub_array[]='<button type="button" class="btn btn-warning bg-accent-4" id="tender_contributors_btn" name="tender_contributors_btn" data-parent_tender_id="'.get_post_meta($post->ID,'parent_tender_id',true).'" data-toggle="tooltip" data-placement="top" title="سایر اطلاعات مناقصه اصلی"><i class="fa fa-eye"></i> </button>';
                    if (metadata_exists('post',$post->ID,'tender_contributors'))
                    {
                        $temp_contr=get_post_meta($post->ID,'tender_contributors',true);
                        if(!($temp_contr == null || $temp_contr == ''))
                        {

                            $sub_array[]='<button type="button" class="btn btn-warning bg-accent-4" id="tender_contributors_btn" name="tender_contributors_btn" data-tender_id="'.$post->ID.'" data-toggle="tooltip" data-placement="top" title="لیست شرکت کنندگان در مناقصه"><i class="fa fa-users"></i> </button>';
                        }
                        else
                        {
                            $sub_array[]='-';

                        }

                    }
                    else
                    {
                        $sub_array[]='-';

                    }
                    $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_date',true));
                    $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_end_date',true));
                    $sub_array[] = '<a href="'.get_post_meta($post->ID,'tender_files',true).'" class="btn btn-warning" data-toggle="tooltip" data-original-title="دانلود مستندات مناقصه فروشگاه"><i class="fa fa-download"></i> </a>';

                    $operations="";
                    date_default_timezone_set('Asia/Tehran');
                    $date = date('Y-m-d',get_post_meta($post->ID,'tender_date',true));
                    $today = date('Y-m-d');
                    $tender_status=get_post_meta($post->ID,'tender_status',true);
                    $tender_expired=($today > $date )?'true':'false';

                    $winner_id=get_post_meta($post->ID,'tender_winner',true);

                    $temp_contrib=get_post_meta($post->ID,'tender_contributors',true);

                    foreach ( $temp_contrib as $key => $value)
                    {
                        if ($value['customer_id'] == $winner_id)
                        {
                            $winner_file=$value['proposed_file'];
                            break;
                        }
                    }
                    $operations.='<button type="button" class="btn btn-deep-purple bg-darken-4" id="show_winner_btn" name="show_winner_btn" 
                            data-tender_id="'.$post->ID.'"  
                            data-winner_shop_name="'.get_user_meta($winner_id,'shop_name',true).'"  
                            data-winner_shop_manager_name="'.get_user_meta($winner_id,'shop_manager_name',true).'"
                            data-winner_shop_tel="'.get_user_meta($winner_id,'shop_tel',true).'"  
                            data-winner_shop_proposed_file="'.$winner_file.'" 
                            data-winner_confirm_file_link="'.get_post_meta($post->ID,'win_confirm_file_link',true).'"  
                            data-toggle="tooltip" data-placement="top" title="مشاهده برنده"><i class="fa fa-trophy"></i></button>';
                    $sub_array[] = $operations;


                    if ($tender_expired == 'true')
                    {
                        $sub_array[] = 'true';
                        if ($tender_status == 'not_executed')
                        {
                            $sub_array[]='not_executed';
                        }
                        else if($tender_status == 'executed')
                        {
                            $sub_array[]='executed';
                        }
                        else
                        {
                            $sub_array[]='finished';
                        }
                    }
                    else
                    {
                        $sub_array[] = 'false';
                        $sub_array[] = 'not_executed';
                    }
                    $data[] = $sub_array;
                    $count++;
                }
            }
        }
        wp_reset_query();

        $output = array(
            "draw"    => intval($_GET["draw"]),
            "recordsTotal"  =>  intval($count),
            "recordsFiltered" => intval($count),
            "data" => $data
        );


        echo json_encode($output);

        wp_die();
    }
    function edit_factory_tender_callback()
    {
        header("Content-Type: application/json");
        $request= $_POST;
        if (isset($request['user_id']) && $request['user_id'] != "")
        {
            if (user_can( $_REQUEST['user_id'], 'manage_options' ))
            {

                update_post_meta(intval($request['tender_id']),'tender_date',sanitize_text_field($request['edit_tender_date']));
                update_post_meta(intval($request['tender_id']),'tender_end_date',sanitize_text_field($request['edit_tender_end_date']));

                if(sanitize_text_field($request['edit_tender_only_one']) == "yes")
                {
                    update_post_meta(intval($request['tender_id']),'tender_only_one','yes');
                }
                else
                {
                    update_post_meta(intval($request['tender_id']),'tender_only_one','no');
                }


                update_post_meta(intval($request['tender_id']),'tender_status','not_executed');


                delete_post_meta(get_post_meta(intval($request['tender_id']),'parent_tender_id',true), 'factory_tender_status' );

                update_post_meta(intval($request['tender_id']),'parent_tender_id',intval($request['edit_parent_tender_id']));
                update_post_meta(intval($request['edit_parent_tender_id']),'factory_tender_status','created');

                $output=array(
                    'success' => 'true',
                    'error' => 'مناقصه با موفقیت ویرایش شد.',
                );


            }
            else
            {
                $output=array(
                    'success' => false,
                    'error' =>'خطای امنیتی رخ داده است',
                );
            }


        }



        echo json_encode($output);

        wp_die();
    }
	function delete_factory_tender_callback()
	{
		header("Content-Type: application/json");
		$request= $_POST;
		if (isset($request['user_id']) && $request['user_id'] != "")
		{
			if (user_can( $_REQUEST['user_id'], 'manage_options' ))
			{

				$parent_tender_id=get_post_meta(intval($request['tender_id']),'parent_tender_id',true);

				delete_post_meta($parent_tender_id,'factory_tender_status');

				wp_delete_post(intval($request['tender_id']),true);

				$output=array(
					'success' => 'true',
					'error' => 'مناقصه با موفقیت حذف شد.',
				);


			}
			else
			{
				$output=array(
					'success' => false,
					'error' =>'خطای امنیتی رخ داده است',
				);
			}


		}



		echo json_encode($output);

		wp_die();
	}
	function get_factory_tender_contributors_callback()
    {
        header("Content-Type: application/json");
        $request=$_POST;
        $output=array();
        if (isset($request['user_id']) && $request['user_id'] != "")
        {
            if (user_can( $_REQUEST['user_id'], 'manage_options' ))
            {
                date_default_timezone_set('Asia/Tehran');
                $end_date = date('Y-m-d',get_post_meta(intval($request['tender_id']),'tender_end_date',true));
                $today = date('Y-m-d');
                $tender_expired=($today <= $end_date )?'false':'true';
                json_encode($tender_expired);

                if ($tender_expired == 'true')
                {
                    foreach (get_post_meta(intval($request['tender_id']),'tender_contributors',true) as $key => $value)
                    {
                        $temp_shop_name=get_user_meta( intval($value['customer_id']), 'shop_name', true );
                        $temp_manager_name=get_user_meta( intval($value['customer_id']), 'shop_manager_name', true );
                        $temp_manager_tel=get_user_meta( intval($value['customer_id']), 'shop_tel', true );
                        $temp_shop_proppsed_file= $value['proposed_file'];

                        $output['contributors'][]=array(
                            $temp_shop_name,
                            $temp_manager_name,
                            $temp_manager_tel,
                            $temp_shop_proppsed_file,
                        );
                    }
                    $output['success']= 'true';
                }
                else
                {
                    foreach (get_post_meta(intval($request['tender_id']),'tender_contributors',true) as $key => $value)
                    {
                        $temp_shop_name=get_user_meta( intval($value['customer_id']), 'shop_name', true );
                        $temp_manager_name=get_user_meta( intval($value['customer_id']), 'shop_manager_name', true );
                        $temp_manager_tel=get_user_meta( intval($value['customer_id']), 'shop_tel', true );
                        $proposed_file='-';

                        $output['contributors'][]=array(
                            $temp_shop_name,
                            $temp_manager_name,
                            $temp_manager_tel,
                            $proposed_file,
                        );
                    }
                    $output['success']= 'true';
                }

            }
            else
            {
                $output=array(
                    'success' => 'false',
                    'error' =>'خطای امنیتی رخ داده است',
                );
            }


        }


	    $parent_tender_id=intval(get_post_meta($request['tender_id'],'parent_tender_id',true));
	    $output['tender_title']=get_post_meta($parent_tender_id,'tender_name',true);
        echo json_encode($output);

        wp_die();
    }

    function get_factory_tender_contributors_and_files_callback()
    {
        header("Content-Type: application/json");
        $request=$_POST;
        $output=array();
        if (isset($request['user_id']) && $request['user_id'] != "")
        {
            if (user_can( $_REQUEST['user_id'], 'manage_options' ))
            {
                foreach (get_post_meta(intval($request['tender_id']),'tender_contributors',true) as $key => $value)
                {
                    $temp_shop_name=get_user_meta( intval($value['customer_id']), 'shop_name', true );
                    $temp_manager_name=get_user_meta( intval($value['customer_id']), 'shop_manager_name', true );
                    $temp_manager_tel=get_user_meta( intval($value['customer_id']), 'shop_tel', true );
                    $temp_shop_proppsed_file= '<a href="'.$value['proposed_file'].'" class="btn btn-warning"><i class="icon-cloud-download3"></i> </a>';
                    $opr='<button type="button" class="btn btn-success bg-accent-4" id="tender_winer_btn" name="tender_winer_btn" data-customer_id="'.intval($value['customer_id']).'"  data-tender_id="'.intval($request['tender_id']).'" data-toggle="tooltip" data-original-title="تایید به عنوان برنده مناقصه"><i class="icon-user-check"></i> تایید برنده</button>';


                    $output['contributors'][]=array(
                        $opr,
                        $temp_shop_name,
                        $temp_manager_name,
                        $temp_manager_tel,
                        $temp_shop_proppsed_file,
                    );
                }
                $output['success']= 'true';
            }
            else
            {
                $output=array(
                    'success' => 'false',
                    'error' =>'خطای امنیتی رخ داده است',
                );
            }


        }
        echo json_encode($output);

        wp_die();
    }


    function get_all_current_shop_tenders_data_callback()
    {
        include_once(TND_INC_DIR.DIRECTORY_SEPARATOR.'vendors'.DIRECTORY_SEPARATOR.'jdf.php');
        header("Content-Type: application/json");

        $current_user=wp_get_current_user();
        if ($current_user->ID == intval($_REQUEST['user_id']) )
        {
            global $post;
            $args_all = array(
                'post_type' => 'shopjahadtender',
                'limit' => -1,
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order' => 'DESC',
            );
            $query_all = new \WP_Query( $args_all );
            if ( $query_all->have_posts() )
            {
                $count_all = 1;
                while ( $query_all->have_posts() )
                {
                    $query_all->the_post();
                    date_default_timezone_set('Asia/Tehran');
                    $s_date = date('Y-m-d',get_post_meta($post->ID,'tender_date',true));
                    $e_date = date('Y-m-d',get_post_meta($post->ID,'tender_end_date',true));
                    $today = date('Y-m-d');
                    $temp_tender_contributors=get_post_meta($post->ID,'tender_contributors',true);
                    if ($today >= $s_date AND $today <=$e_date)
                    {
                        if (!Utility::check_customer_send_tender_request($_REQUEST['user_id'],$temp_tender_contributors))
                        {
                            $count_all++;
                        }
                    }
                }
            }
            wp_reset_query();


            $args = array(
                'post_type' => 'shopjahadtender',
                'posts_per_page' => intval($_REQUEST['length']),
                'limit' => intval($_REQUEST['length']),
                'offset' => intval($_REQUEST['start']),
                'orderby' => 'date',
                'order' => 'DESC',
            );


            $query = new \WP_Query( $args );
            if ( $query->have_posts() )
            {
                $today = date('Y-m-d');
                switch (get_user_meta(intval($_REQUEST['user_id']),'corporation_grade',true))
                {
                    case 'A':
                        $max_area=50;
                        break;
                    case 'B':
                        $max_area=100;
                        break;
                    case 'C':
                        $max_area=200;
                        break;
                    case 'D':
                        $max_area=10000;
                        break;
                }

                $count1= intval($_REQUEST['start']);
                while ( $query->have_posts() )
                {

                    $query->the_post();
                    date_default_timezone_set('Asia/Tehran');
                    $s_date = date('Y-m-d',get_post_meta($post->ID,'tender_date',true));
                    $e_date = date('Y-m-d',get_post_meta($post->ID,'tender_end_date',true));

                    $current_user=wp_get_current_user();
                    $temp_tender_contributors=get_post_meta($post->ID,'tender_contributors',true);

                    if ($today >= $s_date AND $today <=$e_date)
                    {
                        if (!Utility::check_customer_send_tender_request($_REQUEST['user_id'],$temp_tender_contributors))
                        {
                            $sub_array = array();
                            $parent_tender_id=intval(get_post_meta($post->ID,'parent_tender_id',true));
                            $sub_array[] = '<a href="#" id="tender_info_btn" data-toggle="tooltip"
                     data-tender_id="'.$post->ID.'" 
                     data-tender_start_date="'.jdate('d F y',get_post_meta($post->ID,'tender_date',true)).'" 
                     data-tender_end_date="'.jdate('d F y',get_post_meta($post->ID,'tender_end_date',true)).'" 
                     data-tender_area="'.get_post_meta($parent_tender_id,'tender_area',true).'" 
                     data-tender_price="'.number_format(get_post_meta($parent_tender_id,'tender_proposed_price',true)).'" 
                     data-tender_system_type="'.get_post_meta($parent_tender_id,'tender_system_type',true).'"
                     data-tender_advisor="'.get_post_meta($parent_tender_id,'tender_advisor',true).'" 
                     data-tender_city="'.get_post_meta($parent_tender_id,'tender_city',true).'"  
                     data-tender_district="'.get_post_meta($parent_tender_id,'tender_district',true).'" 
                     data-tender_files="'.get_post_meta($parent_tender_id,'tender_files',true).'" 
                     data-tender_only_one="'.get_post_meta($parent_tender_id,'tender_only_one',true).'" 
                     data-original-title="مشاهده سایر اطلاعات  مناقصه اصلی (پیمانکار)">
                     <i class="fa fa-eye"></i>
                     '.get_post_meta($parent_tender_id,'tender_name',true).'
                      </a>';
                            $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_date',true));
                            $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_end_date',true));
                            $sub_array[] = '<a href="'.get_post_meta($post->ID,'tender_files',true).'" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="دانلود اسناد مناقصه"><i class="icon-cloud-download3"></i> </a>';
                            $sub_array[] = '<button type="button" class="btn btn-success" id="request_tender_btn" name="request_tender_btn" data-tender_id="'.$post->ID.'" data-tender_price="'.get_post_meta($post->ID,'tender_proposed_price',true).'"><i class="icon-android-done"></i> شرکت در مناقصه</button>';

                            $data[] = $sub_array;
                        }

                    }
                }
            }
            wp_reset_query();

            $output = array(
                "draw"    => intval($_GET["draw"]),
                "recordsTotal"  =>  intval($count_all),
                "recordsFiltered" => intval($count_all),
                "data" => $data,
            );


            echo json_encode($output);

            wp_die();
        }

    }
    function tnd_shop_tender_request_tool_file_callback()
    {
        require_once( ABSPATH . 'wp-content/plugins/tender/inc/ajax/fancy_file_uploader_helper.php' );
        header("Content-Type: application/json");
        $allowedexts = array(
            "zip" => true,
            "rar" => true,
        );

        $files = File_Uploader::NormalizeFiles("tender_tools_advisor");

        if (!isset($files[0]))
        {
            $result = array(
                "success" => false,
                "error" => "File data was submitted but is missing.",
                "errorcode" => "bad_input"
            );
        }
        else if (!$files[0]["success"])
        {
            $result = $files[0];
        }
        else if (!isset($allowedexts[strtolower($files[0]["ext"])]))
        {
            $result = array(
                "success" => false,
                "error" => "نوع فایل انتخاب شده معتبر نیست.. ",
                "errorcode" => "invalid_file_ext"
            );
        }
        else
        {
            // For chunked file uploads, get the current filename and starting position from the incoming headers.
            $name = File_Uploader::GetChunkFilename();
            if ($name !== false)
            {
                $startpos = File_Uploader::GetFileStartPosition();

                $result = array(
                    "success" => false,
                    "error" => ". نام فایل مدنظر معتبر نمی باشد",
                    "errorcode" => $startpos
                );
            }
            else
            {
                $current_user_id=get_current_user_id();
                if ($current_user_id == intval($_REQUEST['user_id']))
                {
                    $post_id = intval($_REQUEST['tender_id']);
                    $upload_dir= wp_upload_dir();
                    $target_dir = trailingslashit( $upload_dir['basedir'].'/tender_uploads/tenders/'.$post_id.'/proposed_files/'.intval($_REQUEST['user_id']));
                    $target_file = $target_dir .basename($_FILES["tender_tools_advisor"]["name"]);
                    if (!file_exists($target_dir )) {
                        wp_mkdir_p( $target_dir );
                    }

                    if (move_uploaded_file($_FILES["tender_tools_advisor"]["tmp_name"], $target_file))
                    {
                        $upload_dir_link= wp_upload_dir();
                        $target_dir_link = trailingslashit( $upload_dir_link['baseurl'].'/tender_uploads/tenders/'.$post_id.'/proposed_files/'.intval($_REQUEST['user_id']));
                        $target_file_link = $target_dir_link .basename($_FILES["tender_tools_advisor"]["name"]);
                        if(metadata_exists('post',$_REQUEST['tender_id'],'tender_contributors'))
                        {
                            $tender_contributors=get_post_meta($_REQUEST['tender_id'],'tender_contributors',true);
                            $tender_contributors[]=array(
                                'customer_id' => $_REQUEST['user_id'],
                                'proposed_file' => $target_file_link
                            );
                            update_post_meta(intval($_REQUEST['tender_id']),'tender_contributors',$tender_contributors);
                        }
                        else
                        {
                            $tender_contributors=array();
                            $tender_contributors[]=array(
                                'customer_id' => $_REQUEST['user_id'],
                                'proposed_file' => $target_file_link
                            );
                            update_post_meta(intval($_REQUEST['tender_id']),'tender_contributors',$tender_contributors);
                        }

                        $result = array(
                            "success" => true,
                            "error" =>$post_id,
                        );
                    }
                    else
                    {
                        $result = array(
                            "success" => false,
                            "error" => "هنگام اپلود فایل خطایی رخ داد. لطفا مجدد تلاش نمایید.",
                        );
                    }
                }
                else
                {
                    $result = array(
                        "success" => false,
                        "error" => "خطای امنیتی رخ داده است",
                    );
                }



            }


        }


        echo json_encode($result);
        wp_die();
    }
    function get_all_requested_shop_tenders_data_callback()
    {
        include_once(TND_INC_DIR.DIRECTORY_SEPARATOR.'vendors'.DIRECTORY_SEPARATOR.'jdf.php');
        header("Content-Type: application/json");

        $column = array(
            'tnd_name',
            'tnd_area',
            'tnd_price',
            'tnd_date',
            'tnd_winner',
        );

//	$args=array(
//		'meta_query' => array(
//			array(
//				'meta'     => 'tnd_user_type',
//				'value'   => 'customer'
//			)
//		)
//	);
//
//	if (isset($request["search"]["value"]) )
//	{
//		if ($request["search"]["value"] !=null)
//		{
//			$args['meta_query'][]=array(
//				'meta'     => 'last_name',
//				'value'   => $request["search"]["value"],
//				'compare' => 'LIKE'
//			);
//			$args['meta_query'][]=array(
//				'meta'     => 'first_name',
//				'value'   => $request["search"]["value"],
//				'compare' => 'LIKE'
//			);
//		}
//
//	}
//
//	if(isset($request['order']))
//	{
//		$args['orderby']=$column[$request['order']['0']['column']];
//		$args['order']=$request['order']['0']['dir'];
//	}


        $current_user=wp_get_current_user();
        if ($current_user->ID == intval($_REQUEST['user_id']) )
        {
            global $post;
            $args = array(
                'post_type' => 'shopjahadtender',
                'posts_per_page' => -1,
            );
            $query = new \WP_Query( $args );
            if ( $query->have_posts() )
            {
                $today = date('Y-m-d');
                $count=1;
                while ( $query->have_posts() )
                {
                    $query->the_post();
                    date_default_timezone_set('Asia/Tehran');
                    $date = date('Y-m-d',get_post_meta($post->ID,'tender_end_date',true));

                    $current_user=wp_get_current_user();
                    $temp_tender_contributors=get_post_meta($post->ID,'tender_contributors',true);

                    if (Utility::check_customer_send_tender_request($_REQUEST['user_id'],$temp_tender_contributors))
                    {
                        $sub_array = array();
                        $sub_array[]=$count;
                        $parent_tender_id=intval(get_post_meta($post->ID,'parent_tender_id',true));
                        $sub_array[] = '<a href="#" id="tender_info_btn" data-toggle="tooltip"
                     data-tender_id="'.$post->ID.'" 
                     data-tender_start_date="'.jdate('d F y',get_post_meta($post->ID,'tender_date',true)).'" 
                     data-tender_end_date="'.jdate('d F y',get_post_meta($post->ID,'tender_end_date',true)).'" 
                     data-tender_area="'.get_post_meta($parent_tender_id,'tender_area',true).'" 
                     data-tender_price="'.number_format(get_post_meta($parent_tender_id,'tender_proposed_price',true)).'" 
                     data-tender_system_type="'.get_post_meta($parent_tender_id,'tender_system_type',true).'"
                     data-tender_advisor="'.get_post_meta($parent_tender_id,'tender_advisor',true).'" 
                     data-tender_city="'.get_post_meta($parent_tender_id,'tender_city',true).'"  
                     data-tender_district="'.get_post_meta($parent_tender_id,'tender_district',true).'" 
                     data-tender_files="'.get_post_meta($parent_tender_id,'tender_files',true).'" 
                     data-tender_only_one="'.get_post_meta($parent_tender_id,'tender_only_one',true).'" 
                     data-original-title="مشاهده سایر اطلاعات  مناقصه اصلی (پیمانکار)">
                     <i class="fa fa-eye"></i>
                     '.get_post_meta($parent_tender_id,'tender_name',true).'
                      </a>';
                        $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_date',true));
                        $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_end_date',true));
                        $sub_array[] = '<a href="'.get_post_meta($post->ID,'tender_files',true).'" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="دانلود اسناد مناقصه"><i class="icon-cloud-download3"></i> </a>';

                        $temp_contrib=get_post_meta($post->ID,'tender_contributors',true);

                        foreach ( $temp_contrib as $key => $value)
                        {
                            if ($value['customer_id'] == intval($_REQUEST['user_id']))
                            {
                                $shop_req_file=$value['proposed_file'];
                                break;
                            }
                        }
                        $sub_array[] = '<a href="'.$shop_req_file.'" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="دانلود فایل پیشنهادی شما"><i class="icon-cloud-download3"></i> </a>';
                        $operations="";
                        if ($date >= $today)
                        {
                            $operations.='<button type="button" class="btn btn-danger" id="tender_cancel_btn" name="tender_cancel_btn" data-toggle="tooltip" data-placement="top" title="انصراف از مناقصه" data-tender_id="'.$post->ID.'"><i class="icon-cross2"></i> </button>';
                        }
                        $sub_array[]=$operations;
                        $data[] = $sub_array;
                        $count++;
                    }


                }
            }
            wp_reset_query();

            $output = array(
                "draw"    => intval($_GET["draw"]),
                "recordsTotal"  =>  intval($count),
//        "recordsFiltered" => intval($count),
                "data" => $data
            );


            echo json_encode($output);

            wp_die();
        }

    }
    function get_all_winned_shop_tenders_data_callback()
    {
        include_once(TND_INC_DIR.DIRECTORY_SEPARATOR.'vendors'.DIRECTORY_SEPARATOR.'jdf.php');
        header("Content-Type: application/json");

        $column = array(
            'tnd_name',
            'tnd_area',
            'tnd_price',
            'tnd_date',
            'tnd_winner',
        );

//	$args=array(
//		'meta_query' => array(
//			array(
//				'meta'     => 'tnd_user_type',
//				'value'   => 'customer'
//			)
//		)
//	);
//
//	if (isset($request["search"]["value"]) )
//	{
//		if ($request["search"]["value"] !=null)
//		{
//			$args['meta_query'][]=array(
//				'meta'     => 'last_name',
//				'value'   => $request["search"]["value"],
//				'compare' => 'LIKE'
//			);
//			$args['meta_query'][]=array(
//				'meta'     => 'first_name',
//				'value'   => $request["search"]["value"],
//				'compare' => 'LIKE'
//			);
//		}
//
//	}
//
//	if(isset($request['order']))
//	{
//		$args['orderby']=$column[$request['order']['0']['column']];
//		$args['order']=$request['order']['0']['dir'];
//	}


        $current_user=wp_get_current_user();
        if ($current_user->ID == intval($_REQUEST['user_id']) )
        {
            global $post;
            $args = array(
                'post_type' => 'shopjahadtender',
                'posts_per_page' => -1,
            );
            $query = new \WP_Query( $args );
            if ( $query->have_posts() )
            {
                $today = date('Y-m-d');
                $count=1;
                while ( $query->have_posts() )
                {
                    $query->the_post();
                    date_default_timezone_set('Asia/Tehran');
                    $date = date('Y-m-d',get_post_meta($post->ID,'tender_date',true));

                    $temp_tender_contributors=get_post_meta($post->ID,'tender_contributors',true);


                    if (Utility::check_customer_send_tender_request($_REQUEST['user_id'],$temp_tender_contributors) and get_current_user_id() == get_post_meta($post->ID,'tender_winner',true))
                    {
                        $sub_array = array();
                        $sub_array[]=$count;
                        $parent_tender_id=intval(get_post_meta($post->ID,'parent_tender_id',true));
                        $sub_array[] = '<a href="#" id="tender_info_btn" data-toggle="tooltip"
                     data-tender_id="'.$post->ID.'" 
                     data-tender_start_date="'.jdate('d F y',get_post_meta($post->ID,'tender_date',true)).'" 
                     data-tender_end_date="'.jdate('d F y',get_post_meta($post->ID,'tender_end_date',true)).'" 
                     data-tender_area="'.get_post_meta($parent_tender_id,'tender_area',true).'" 
                     data-tender_price="'.number_format(get_post_meta($parent_tender_id,'tender_proposed_price',true)).'" 
                     data-tender_system_type="'.get_post_meta($parent_tender_id,'tender_system_type',true).'"
                     data-tender_advisor="'.get_post_meta($parent_tender_id,'tender_advisor',true).'" 
                     data-tender_city="'.get_post_meta($parent_tender_id,'tender_city',true).'"  
                     data-tender_district="'.get_post_meta($parent_tender_id,'tender_district',true).'" 
                     data-tender_files="'.get_post_meta($parent_tender_id,'tender_files',true).'" 
                     data-tender_only_one="'.get_post_meta($parent_tender_id,'tender_only_one',true).'" 
                     data-original-title="مشاهده سایر اطلاعات  مناقصه اصلی (پیمانکار)">
                     <i class="fa fa-eye"></i>
                     '.get_post_meta($parent_tender_id,'tender_name',true).'
                      </a>';
                        $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_date',true));
                        $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_end_date',true));
                        $sub_array[] = '<a href="'.get_post_meta($post->ID,'tender_files',true).'" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="دانلود اسناد مناقصه"><i class="icon-cloud-download3"></i> </a>';

                        $temp_contrib=get_post_meta($post->ID,'tender_contributors',true);

                        foreach ( $temp_contrib as $key => $value)
                        {
                            if ($value['customer_id'] == intval($_REQUEST['user_id']))
                            {
                                $shop_req_file=$value['proposed_file'];
                                break;
                            }
                        }

                        $sub_array[] = '<a href="'.$shop_req_file.'" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="دانلود اسناد مناقصه"><i class="icon-cloud-download3"></i> </a>';

                        $tender_status=get_post_meta($post->ID,'tender_status',true);
                        if($tender_status == 'executed')
                        {
                            $sub_array[] = '<div class="tag tag-warning">در حال اجرا</div>';
                        }
                        else if($tender_status == 'finished')
                        {
                            $sub_array[] = '<div class="tag tag-success">پایان یافته</div>';
                        }
                        else if($tender_status == 'archived')
                        {
                            $sub_array[] = '<div class="tag tag-success">پایان یافته</div>';
                        }

//                        $operations="";
//                        $operations.='<button type="button" class="btn btn-warning bg-accent-4" id="tender_info_btn" name="tender_info_btn" data-toggle="tooltip" data-placement="top" title="سایر جزئیات مناقصه" data-tender_id="'.$post->ID.'"  data-tender_area="'.get_post_meta($post->ID,'tender_area',true).'"  data-tender_price="'.get_post_meta($post->ID,'tender_proposed_price',true).'"  data-tender_system_type="'.get_post_meta($post->ID,'tender_system_type',true).'"  data-tender_advisor="'.get_post_meta($post->ID,'tender_advisor',true).'"  data-tender_city="'.get_post_meta($post->ID,'tender_city',true).'"  data-tender_district="'.get_post_meta($post->ID,'tender_district',true).'"  data-tender_files="'.get_post_meta($post->ID,'tender_files',true).'"><i class="icon-eye6"></i> </button>';
//                        $operations.=' '.'|'.' '.'<button type="button" class="btn btn-danger bg-accent-4" id="tender_info_btn" name="tender_info_btn" data-toggle="tooltip" data-placement="top" title="ارسال در خواست انصراف از انجام پروژه" data-tender_id="'.$post->ID.'"  data-tender_area="'.get_post_meta($post->ID,'tender_area',true).'"  data-tender_price="'.get_post_meta($post->ID,'tender_proposed_price',true).'"  data-tender_system_type="'.get_post_meta($post->ID,'tender_system_type',true).'"  data-tender_advisor="'.get_post_meta($post->ID,'tender_advisor',true).'"  data-tender_city="'.get_post_meta($post->ID,'tender_city',true).'"  data-tender_district="'.get_post_meta($post->ID,'tender_district',true).'"  data-tender_files="'.get_post_meta($post->ID,'tender_files',true).'"><span class="fa-stack" style="vertical-align: top;height: 18px;line-height: 16px;width: 16px;"> <i class="fas fa-trophy fa-stack-1x"></i><i class="fas fa-slash fa-stack-1x" style="color:red"></i></span> </button>';
//                        $sub_array[] = $operations;
                        $data[] = $sub_array;
                        $count++;
                    }


                }
            }
            wp_reset_query();

            $output = array(
                "draw"    => intval($_GET["draw"]),
                "recordsTotal"  =>  intval($count),
//        "recordsFiltered" => intval($count),
                "data" => $data
            );
        }


        echo json_encode($output);

        wp_die();
    }



    function get_all_current_factory_tenders_data_callback()
    {
        include_once(TND_INC_DIR.DIRECTORY_SEPARATOR.'vendors'.DIRECTORY_SEPARATOR.'jdf.php');
        header("Content-Type: application/json");

        $current_user=wp_get_current_user();
        if ($current_user->ID == intval($_REQUEST['user_id']) )
        {
            global $post;
            $args_all = array(
                'post_type' => 'factoryjahadtender',
                'limit' => -1,
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order' => 'DESC',
            );
            $query_all = new \WP_Query( $args_all );

            if ( $query_all->have_posts() )
            {
                $count_all = 1;
                while ( $query_all->have_posts() )
                {
                    $query_all->the_post();
                    date_default_timezone_set('Asia/Tehran');
                    $s_date = date('Y-m-d',get_post_meta($post->ID,'tender_date',true));
                    $e_date = date('Y-m-d',get_post_meta($post->ID,'tender_end_date',true));
                    $today = date('Y-m-d');
                    $temp_tender_contributors=get_post_meta($post->ID,'tender_contributors',true);
                    if ($today >= $s_date AND $today <=$e_date)
                    {
                        if (!Utility::check_customer_send_tender_request($_REQUEST['user_id'],$temp_tender_contributors))
                        {
                            $count_all++;
                        }
                    }
                }
            }
            wp_reset_query();


            $args = array(
                'post_type' => 'factoryjahadtender',
                'limit' => -1,
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order' => 'DESC',
            );


            $query = new \WP_Query( $args );

            if ( $query->have_posts() )
            {
                $today = date('Y-m-d');
                switch (get_user_meta(intval($_REQUEST['user_id']),'corporation_grade',true))
                {
                    case 'A':
                        $max_area=50;
                        break;
                    case 'B':
                        $max_area=100;
                        break;
                    case 'C':
                        $max_area=200;
                        break;
                    case 'D':
                        $max_area=10000;
                        break;
                }

                $count1= intval($_REQUEST['start']);
                while ( $query->have_posts() )
                {

                    $query->the_post();
                    date_default_timezone_set('Asia/Tehran');
                    $s_date = date('Y-m-d',get_post_meta($post->ID,'tender_date',true));
                    $e_date = date('Y-m-d',get_post_meta($post->ID,'tender_end_date',true));

                    $current_user=wp_get_current_user();
                    $temp_tender_contributors=get_post_meta($post->ID,'tender_contributors',true);

                    if ($today >= $s_date AND $today <=$e_date)
                    {
                        if (!Utility::check_customer_send_tender_request($_REQUEST['user_id'],$temp_tender_contributors))
                        {
                            $sub_array = array();
                            $parent_tender_id=intval(get_post_meta($post->ID,'parent_tender_id',true));
                            $sub_array[] = '<a href="#" id="tender_info_btn" data-toggle="tooltip"
                     data-tender_id="'.$post->ID.'" 
                     data-tender_start_date="'.jdate('d F y',get_post_meta($post->ID,'tender_date',true)).'" 
                     data-tender_end_date="'.jdate('d F y',get_post_meta($post->ID,'tender_end_date',true)).'" 
                     data-tender_area="'.get_post_meta($parent_tender_id,'tender_area',true).'" 
                     data-tender_price="'.number_format(get_post_meta($parent_tender_id,'tender_proposed_price',true)).'" 
                     data-tender_system_type="'.get_post_meta($parent_tender_id,'tender_system_type',true).'"
                     data-tender_advisor="'.get_post_meta($parent_tender_id,'tender_advisor',true).'" 
                     data-tender_city="'.get_post_meta($parent_tender_id,'tender_city',true).'"  
                     data-tender_district="'.get_post_meta($parent_tender_id,'tender_district',true).'" 
                     data-tender_files="'.get_post_meta($parent_tender_id,'tender_files',true).'" 
                     data-tender_only_one="'.get_post_meta($parent_tender_id,'tender_only_one',true).'" 
                     data-original-title="مشاهده سایر اطلاعات  مناقصه اصلی (پیمانکار)">
                     <i class="fa fa-eye"></i>
                     '.get_post_meta($parent_tender_id,'tender_name',true).'
                      </a>';
                            $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_date',true));
                            $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_end_date',true));
                            $sub_array[] = '<a href="'.get_post_meta($post->ID,'tender_files',true).'" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="دانلود اسناد مناقصه"><i class="icon-cloud-download3"></i> </a>';
                            $sub_array[] = '<button type="button" class="btn btn-success" id="request_tender_btn" name="request_tender_btn" data-tender_id="'.$post->ID.'" data-tender_price="'.get_post_meta($post->ID,'tender_proposed_price',true).'"><i class="icon-android-done"></i> شرکت در مناقصه</button>';

                            $data[] = $sub_array;
                        }

                    }
                }
            }
            wp_reset_query();

            $output = array(
                "draw"    => intval($_GET["draw"]),
                "recordsTotal"  =>  intval($count_all),
                "recordsFiltered" => intval($count_all),
                "data" => $data,
            );


            echo json_encode($output);

            wp_die();
        }

    }
    function tnd_factory_tender_request_tool_file_callback()
    {
        require_once( ABSPATH . 'wp-content/plugins/tender/inc/ajax/fancy_file_uploader_helper.php' );
        header("Content-Type: application/json");
        $allowedexts = array(
            "zip" => true,
            "rar" => true,
        );

        $files = File_Uploader::NormalizeFiles("tender_tools_advisor");

        if (!isset($files[0]))
        {
            $result = array(
                "success" => false,
                "error" => "File data was submitted but is missing.",
                "errorcode" => "bad_input"
            );
        }
        else if (!$files[0]["success"])
        {
            $result = $files[0];
        }
        else if (!isset($allowedexts[strtolower($files[0]["ext"])]))
        {
            $result = array(
                "success" => false,
                "error" => "نوع فایل انتخاب شده معتبر نیست.. ",
                "errorcode" => "invalid_file_ext"
            );
        }
        else
        {
            // For chunked file uploads, get the current filename and starting position from the incoming headers.
            $name = File_Uploader::GetChunkFilename();
            if ($name !== false)
            {
                $startpos = File_Uploader::GetFileStartPosition();

                $result = array(
                    "success" => false,
                    "error" => ". نام فایل مدنظر معتبر نمی باشد",
                    "errorcode" => $startpos
                );
            }
            else
            {
                $current_user_id=get_current_user_id();
                if ($current_user_id == intval($_REQUEST['user_id']))
                {
                    $post_id = intval($_REQUEST['tender_id']);
                    $upload_dir= wp_upload_dir();
                    $target_dir = trailingslashit( $upload_dir['basedir'].'/tender_uploads/tenders/'.$post_id.'/proposed_files/'.intval($_REQUEST['user_id']));
                    $target_file = $target_dir .basename($_FILES["tender_tools_advisor"]["name"]);
                    if (!file_exists($target_dir )) {
                        wp_mkdir_p( $target_dir );
                    }

                    if (move_uploaded_file($_FILES["tender_tools_advisor"]["tmp_name"], $target_file))
                    {
                        $upload_dir_link= wp_upload_dir();
                        $target_dir_link = trailingslashit( $upload_dir_link['baseurl'].'/tender_uploads/tenders/'.$post_id.'/proposed_files/'.intval($_REQUEST['user_id']));
                        $target_file_link = $target_dir_link .basename($_FILES["tender_tools_advisor"]["name"]);
                        if(metadata_exists('post',$_REQUEST['tender_id'],'tender_contributors'))
                        {
                            $tender_contributors=get_post_meta($_REQUEST['tender_id'],'tender_contributors',true);
                            $tender_contributors[]=array(
                                'customer_id' => $_REQUEST['user_id'],
                                'proposed_file' => $target_file_link
                            );
                            update_post_meta(intval($_REQUEST['tender_id']),'tender_contributors',$tender_contributors);
                        }
                        else
                        {
                            $tender_contributors=array();
                            $tender_contributors[]=array(
                                'customer_id' => $_REQUEST['user_id'],
                                'proposed_file' => $target_file_link
                            );
                            update_post_meta(intval($_REQUEST['tender_id']),'tender_contributors',$tender_contributors);
                        }

                        $result = array(
                            "success" => true,
                            "error" =>$post_id,
                        );
                    }
                    else
                    {
                        $result = array(
                            "success" => false,
                            "error" => "هنگام اپلود فایل خطایی رخ داد. لطفا مجدد تلاش نمایید.",
                        );
                    }
                }
                else
                {
                    $result = array(
                        "success" => false,
                        "error" => "خطای امنیتی رخ داده است",
                    );
                }



            }


        }


        echo json_encode($result);
        wp_die();
    }
    function get_all_requested_factory_tenders_data_callback()
    {
        include_once(TND_INC_DIR.DIRECTORY_SEPARATOR.'vendors'.DIRECTORY_SEPARATOR.'jdf.php');
        header("Content-Type: application/json");

        $column = array(
            'tnd_name',
            'tnd_area',
            'tnd_price',
            'tnd_date',
            'tnd_winner',
        );

//	$args=array(
//		'meta_query' => array(
//			array(
//				'meta'     => 'tnd_user_type',
//				'value'   => 'customer'
//			)
//		)
//	);
//
//	if (isset($request["search"]["value"]) )
//	{
//		if ($request["search"]["value"] !=null)
//		{
//			$args['meta_query'][]=array(
//				'meta'     => 'last_name',
//				'value'   => $request["search"]["value"],
//				'compare' => 'LIKE'
//			);
//			$args['meta_query'][]=array(
//				'meta'     => 'first_name',
//				'value'   => $request["search"]["value"],
//				'compare' => 'LIKE'
//			);
//		}
//
//	}
//
//	if(isset($request['order']))
//	{
//		$args['orderby']=$column[$request['order']['0']['column']];
//		$args['order']=$request['order']['0']['dir'];
//	}


        $current_user=wp_get_current_user();
        if ($current_user->ID == intval($_REQUEST['user_id']) )
        {
            global $post;
            $args = array(
                'post_type' => 'factoryjahadtender',
                'posts_per_page' => -1,
            );
            $query = new \WP_Query( $args );
            if ( $query->have_posts() )
            {
                $today = date('Y-m-d');
                $count=1;
                while ( $query->have_posts() )
                {
                    $query->the_post();
                    date_default_timezone_set('Asia/Tehran');
                    $date = date('Y-m-d',get_post_meta($post->ID,'tender_end_date',true));

                    $current_user=wp_get_current_user();
                    $temp_tender_contributors=get_post_meta($post->ID,'tender_contributors',true);

                    if (Utility::check_customer_send_tender_request($_REQUEST['user_id'],$temp_tender_contributors))
                    {
                        $sub_array = array();
                        $sub_array[]=$count;
                        $parent_tender_id=intval(get_post_meta($post->ID,'parent_tender_id',true));
                        $sub_array[] = '<a href="#" id="tender_info_btn" data-toggle="tooltip"
                     data-tender_id="'.$post->ID.'" 
                     data-tender_start_date="'.jdate('d F y',get_post_meta($post->ID,'tender_date',true)).'" 
                     data-tender_end_date="'.jdate('d F y',get_post_meta($post->ID,'tender_end_date',true)).'" 
                     data-tender_area="'.get_post_meta($parent_tender_id,'tender_area',true).'" 
                     data-tender_price="'.number_format(get_post_meta($parent_tender_id,'tender_proposed_price',true)).'" 
                     data-tender_system_type="'.get_post_meta($parent_tender_id,'tender_system_type',true).'"
                     data-tender_advisor="'.get_post_meta($parent_tender_id,'tender_advisor',true).'" 
                     data-tender_city="'.get_post_meta($parent_tender_id,'tender_city',true).'"  
                     data-tender_district="'.get_post_meta($parent_tender_id,'tender_district',true).'" 
                     data-tender_files="'.get_post_meta($parent_tender_id,'tender_files',true).'" 
                     data-tender_only_one="'.get_post_meta($parent_tender_id,'tender_only_one',true).'" 
                     data-original-title="مشاهده سایر اطلاعات  مناقصه اصلی (پیمانکار)">
                     <i class="fa fa-eye"></i>
                     '.get_post_meta($parent_tender_id,'tender_name',true).'
                      </a>';
                        $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_date',true));
                        $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_end_date',true));
                        $sub_array[] = '<a href="'.get_post_meta($post->ID,'tender_files',true).'" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="دانلود اسناد مناقصه"><i class="icon-cloud-download3"></i> </a>';

                        $temp_contrib=get_post_meta($post->ID,'tender_contributors',true);

                        foreach ( $temp_contrib as $key => $value)
                        {
                            if ($value['customer_id'] == intval($_REQUEST['user_id']))
                            {
                                $shop_req_file=$value['proposed_file'];
                                break;
                            }
                        }
                        $sub_array[] = '<a href="'.$shop_req_file.'" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="دانلود فایل پیشنهادی شما"><i class="icon-cloud-download3"></i> </a>';
                        $operations="";
                        if ($date >= $today)
                        {
                            $operations.='<button type="button" class="btn btn-danger" id="tender_cancel_btn" name="tender_cancel_btn" data-toggle="tooltip" data-placement="top" title="انصراف از مناقصه" data-tender_id="'.$post->ID.'"><i class="icon-cross2"></i> </button>';
                        }
                        $sub_array[]=$operations;
                        $data[] = $sub_array;
                        $count++;
                    }


                }
            }
            wp_reset_query();

            $output = array(
                "draw"    => intval($_GET["draw"]),
                "recordsTotal"  =>  intval($count),
//        "recordsFiltered" => intval($count),
                "data" => $data
            );


            echo json_encode($output);

            wp_die();
        }

    }
    function get_all_winned_factory_tenders_data_callback()
    {
        include_once(TND_INC_DIR.DIRECTORY_SEPARATOR.'vendors'.DIRECTORY_SEPARATOR.'jdf.php');
        header("Content-Type: application/json");

        $column = array(
            'tnd_name',
            'tnd_area',
            'tnd_price',
            'tnd_date',
            'tnd_winner',
        );

//	$args=array(
//		'meta_query' => array(
//			array(
//				'meta'     => 'tnd_user_type',
//				'value'   => 'customer'
//			)
//		)
//	);
//
//	if (isset($request["search"]["value"]) )
//	{
//		if ($request["search"]["value"] !=null)
//		{
//			$args['meta_query'][]=array(
//				'meta'     => 'last_name',
//				'value'   => $request["search"]["value"],
//				'compare' => 'LIKE'
//			);
//			$args['meta_query'][]=array(
//				'meta'     => 'first_name',
//				'value'   => $request["search"]["value"],
//				'compare' => 'LIKE'
//			);
//		}
//
//	}
//
//	if(isset($request['order']))
//	{
//		$args['orderby']=$column[$request['order']['0']['column']];
//		$args['order']=$request['order']['0']['dir'];
//	}


        $current_user=wp_get_current_user();
        if ($current_user->ID == intval($_REQUEST['user_id']) )
        {
            global $post;
            $args = array(
                'post_type' => 'factoryjahadtender',
                'posts_per_page' => -1,
            );
            $query = new \WP_Query( $args );
            if ( $query->have_posts() )
            {
                $today = date('Y-m-d');
                $count=1;
                while ( $query->have_posts() )
                {
                    $query->the_post();
                    date_default_timezone_set('Asia/Tehran');
                    $date = date('Y-m-d',get_post_meta($post->ID,'tender_date',true));

                    $temp_tender_contributors=get_post_meta($post->ID,'tender_contributors',true);


                    if (Utility::check_customer_send_tender_request($_REQUEST['user_id'],$temp_tender_contributors) and get_current_user_id() == get_post_meta($post->ID,'tender_winner',true))
                    {
                        $sub_array = array();
                        $sub_array[]=$count;
                        $parent_tender_id=intval(get_post_meta($post->ID,'parent_tender_id',true));
                        $sub_array[] = '<a href="#" id="tender_info_btn" data-toggle="tooltip"
                     data-tender_id="'.$post->ID.'" 
                     data-tender_start_date="'.jdate('d F y',get_post_meta($post->ID,'tender_date',true)).'" 
                     data-tender_end_date="'.jdate('d F y',get_post_meta($post->ID,'tender_end_date',true)).'" 
                     data-tender_area="'.get_post_meta($parent_tender_id,'tender_area',true).'" 
                     data-tender_price="'.number_format(get_post_meta($parent_tender_id,'tender_proposed_price',true)).'" 
                     data-tender_system_type="'.get_post_meta($parent_tender_id,'tender_system_type',true).'"
                     data-tender_advisor="'.get_post_meta($parent_tender_id,'tender_advisor',true).'" 
                     data-tender_city="'.get_post_meta($parent_tender_id,'tender_city',true).'"  
                     data-tender_district="'.get_post_meta($parent_tender_id,'tender_district',true).'" 
                     data-tender_files="'.get_post_meta($parent_tender_id,'tender_files',true).'" 
                     data-tender_only_one="'.get_post_meta($parent_tender_id,'tender_only_one',true).'" 
                     data-original-title="مشاهده سایر اطلاعات  مناقصه اصلی (پیمانکار)">
                     <i class="fa fa-eye"></i>
                     '.get_post_meta($parent_tender_id,'tender_name',true).'
                      </a>';
                        $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_date',true));
                        $sub_array[] = jdate('d F y',get_post_meta($post->ID,'tender_end_date',true));
                        $sub_array[] = '<a href="'.get_post_meta($post->ID,'tender_files',true).'" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="دانلود اسناد مناقصه"><i class="icon-cloud-download3"></i> </a>';

                        $temp_contrib=get_post_meta($post->ID,'tender_contributors',true);

                        foreach ( $temp_contrib as $key => $value)
                        {
                            if ($value['customer_id'] == intval($_REQUEST['user_id']))
                            {
                                $shop_req_file=$value['proposed_file'];
                                break;
                            }
                        }

                        $sub_array[] = '<a href="'.$shop_req_file.'" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="دانلود اسناد مناقصه"><i class="icon-cloud-download3"></i> </a>';

                        $tender_status=get_post_meta($post->ID,'tender_status',true);
                        if($tender_status == 'executed')
                        {
                            $sub_array[] = '<div class="tag tag-warning">در حال اجرا</div>';
                        }
                        else if($tender_status == 'finished')
                        {
                            $sub_array[] = '<div class="tag tag-success">پایان یافته</div>';
                        }
                        else if($tender_status == 'archived')
                        {
                            $sub_array[] = '<div class="tag tag-success">پایان یافته</div>';
                        }

//                        $operations="";
//                        $operations.='<button type="button" class="btn btn-warning bg-accent-4" id="tender_info_btn" name="tender_info_btn" data-toggle="tooltip" data-placement="top" title="سایر جزئیات مناقصه" data-tender_id="'.$post->ID.'"  data-tender_area="'.get_post_meta($post->ID,'tender_area',true).'"  data-tender_price="'.get_post_meta($post->ID,'tender_proposed_price',true).'"  data-tender_system_type="'.get_post_meta($post->ID,'tender_system_type',true).'"  data-tender_advisor="'.get_post_meta($post->ID,'tender_advisor',true).'"  data-tender_city="'.get_post_meta($post->ID,'tender_city',true).'"  data-tender_district="'.get_post_meta($post->ID,'tender_district',true).'"  data-tender_files="'.get_post_meta($post->ID,'tender_files',true).'"><i class="icon-eye6"></i> </button>';
//                        $operations.=' '.'|'.' '.'<button type="button" class="btn btn-danger bg-accent-4" id="tender_info_btn" name="tender_info_btn" data-toggle="tooltip" data-placement="top" title="ارسال در خواست انصراف از انجام پروژه" data-tender_id="'.$post->ID.'"  data-tender_area="'.get_post_meta($post->ID,'tender_area',true).'"  data-tender_price="'.get_post_meta($post->ID,'tender_proposed_price',true).'"  data-tender_system_type="'.get_post_meta($post->ID,'tender_system_type',true).'"  data-tender_advisor="'.get_post_meta($post->ID,'tender_advisor',true).'"  data-tender_city="'.get_post_meta($post->ID,'tender_city',true).'"  data-tender_district="'.get_post_meta($post->ID,'tender_district',true).'"  data-tender_files="'.get_post_meta($post->ID,'tender_files',true).'"><span class="fa-stack" style="vertical-align: top;height: 18px;line-height: 16px;width: 16px;"> <i class="fas fa-trophy fa-stack-1x"></i><i class="fas fa-slash fa-stack-1x" style="color:red"></i></span> </button>';
//                        $sub_array[] = $operations;
                        $data[] = $sub_array;
                        $count++;
                    }


                }
            }
            wp_reset_query();

            $output = array(
                "draw"    => intval($_GET["draw"]),
                "recordsTotal"  =>  intval($count),
//        "recordsFiltered" => intval($count),
                "data" => $data
            );
        }


        echo json_encode($output);

        wp_die();
    }

}

