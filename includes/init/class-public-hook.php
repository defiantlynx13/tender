<?php
/**
 * Public_Hook Class File
 *
 * This file contains hooks that you need in public
 * (like enqueue styles or scripts in front end)
 *
 * @package    Tender_Shop_Dir\Includes\Init
 * @author     Your_Name <youremail@nomail.com>
 * @license    https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link       https://yoursite.com
 * @since      1.0.0
 */

namespace Tender_Shop_Dir\Includes\Init;

use Tender_Shop_Dir\Includes\Functions\Utility;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Tender_Shop_Dir\Includes\Init
 * @author     Your_Name <youremail@nomail.com>
 */
class Public_Hook {

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
	 * @param      string $Tender_Shop The name of the plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $Tender_Shop, $version ) {

		$this->Tender_Shop = $Tender_Shop;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
			$this->Tender_Shop . '_public_style',
			Tender_Shop_CSS . 'plugin-name-public.css',
			array(),
			$this->version,
			'all'
		);

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
//        $current_route=Utility::get_current_route();
//
//        switch ($current_route)
//        {
//            case '/mezrab-dashboard':
//                wp_enqueue_script(
//                    $this->Tender_Shop . '_mezrab-dashboard_script',
//                    Tender_Shop_URL . 'assets/dashboard/assets/js/mezrab-dashboard.js',
//                    array( 'jquery' ),
//                    $this->version,
//                    true
//                );
//                break;
//        }

	}

//    public function handle_custom_query_var_callback( $query, $query_vars ) {
//        if ( ! empty( $query_vars['intro_teacher'] ) ) {
//            $query['meta_query'][] = array(
//                'key' => 'intro_teacher',
//                'value' => esc_attr( $query_vars['intro_teacher'] ),
//            );
//        }
//
//        return $query;
//    }

}

