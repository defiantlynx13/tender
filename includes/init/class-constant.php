<?php
/**
 * Constant Class File
 *
 * This file contains Constant class which defines needed constants to ease
 * your plugin development processes.
 *
 * @package    Tender_Shop_Dir\Includes\Init
 * @author     Your_Name <youremail@nomail.com>
 * @license    https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link       https://yoursite.com
 * @since      1.0.0
 */

namespace Tender_Shop_Dir\Includes\Init;

/**
 * Class Constant
 *
 * This class defines needed constants that you will use in plugin development.
 *
 * @package    Tender_Shop_Dir\Includes\Init
 * @author     Your_Name <youremail@nomail.com>
 */
class Constant {

	/**
	 * Define define_constant method in Constant class
	 *
	 * It defines all of constants that you need
	 *
	 * @access  public
	 * @static
	 */
	public static function define_constant() {

		/**
		 * Tender_Shop_PATH constant.
		 * It is used to specify plugin path
		 */
		if ( ! defined( 'Tender_Shop_PATH' ) ) {
			define( 'Tender_Shop_PATH', trailingslashit( plugin_dir_path( dirname( dirname( __FILE__ ) ) ) ) );
		}

		/**
		 * Tender_Shop_URL constant.
		 * It is used to specify plugin urls
		 */
		if ( ! defined( 'Tender_Shop_URL' ) ) {
			define( 'Tender_Shop_URL', trailingslashit( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) ) );
		}

		/**
		 * Tender_Shop_CSS constant.
		 * It is used to specify css urls inside assets directory. It's used in front end and
		 * using to  load related CSS files for front end user.
		 */
		if ( ! defined( 'Tender_Shop_CSS' ) ) {
			define( 'Tender_Shop_CSS', trailingslashit( Tender_Shop_URL ) . 'assets/css/' );
		}

		/**
		 * Tender_Shop_JS constant.
		 * It is used to specify JavaScript urls inside assets directory. It's used in front end and
		 * using to load related JS files for front end user.
		 */
		if ( ! defined( 'Tender_Shop_JS' ) ) {
			define( 'Tender_Shop_JS', trailingslashit( Tender_Shop_URL ) . 'assets/js/' );
		}

		/**
		 * Tender_Shop_IMG constant.
		 * It is used to specify image urls inside assets directory. It's used in front end and
		 * using to load related image files for front end user.
		 */
		if ( ! defined( 'Tender_Shop_IMG' ) ) {
			define( 'Tender_Shop_IMG', trailingslashit( Tender_Shop_URL ) . 'assets/images/' );
		}

		/**
		 * Tender_Shop_ADMIN_CSS constant.
		 * It is used to specify css urls inside assets/admin directory. It's used in WordPress
		 *  admin panel and using to  load related CSS files for admin user.
		 */
		if ( ! defined( 'Tender_Shop_ADMIN_CSS' ) ) {
			define( 'Tender_Shop_ADMIN_CSS', trailingslashit( Tender_Shop_URL ) . 'assets/admin/css/' );
		}

		/**
		 * Tender_Shop_ADMIN_JS constant.
		 * It is used to specify JS urls inside assets/admin directory. It's used in WordPress
		 *  admin panel and using to  load related JS files for admin user.
		 */
		if ( ! defined( 'Tender_Shop_ADMIN_JS' ) ) {
			define( 'Tender_Shop_ADMIN_JS', trailingslashit( Tender_Shop_URL ) . 'assets/admin/js/' );
		}

		/**
		 * Tender_Shop_ADMIN_IMG constant.
		 * It is used to specify image urls inside assets/admin directory. It's used in WordPress
		 *  admin panel and using to  load related JS files for admin user.
		 */
		if ( ! defined( 'Tender_Shop_ADMIN_IMG' ) ) {
			define( 'Tender_Shop_ADMIN_IMG', trailingslashit( Tender_Shop_URL ) . 'assets/admin/images/' );
		}

		/**
		 * Tender_Shop_TPL constant.
		 * It is used to specify template urls inside templates directory.
		 */
		if ( ! defined( 'Tender_Shop_TPL' ) ) {
			define( 'Tender_Shop_TPL', trailingslashit( Tender_Shop_PATH . 'templates' ) );
		}

		/**
		 * Tender_Shop_INC constant.
		 * It is used to specify include path inside includes directory.
		 */
		if ( ! defined( 'Tender_Shop_INC' ) ) {
			define( 'Tender_Shop_INC', trailingslashit( Tender_Shop_PATH . 'includes' ) );
		}

		/**
		 * Tender_Shop_LANG constant.
		 * It is used to specify language path inside languages directory.
		 */
		if ( ! defined( 'Tender_Shop_LANG' ) ) {
			define( 'Tender_Shop_LANG', trailingslashit( Tender_Shop_PATH . 'languages' ) );
		}

		/**
		 * Tender_Shop_TPL_ADMIN constant.
		 * It is used to specify template urls inside templates/admin directory. If you want to
		 * create a template for admin panel or administration purpose, you will use from it.
		 */
		if ( ! defined( 'Tender_Shop_TPL_ADMIN' ) ) {
			define( 'Tender_Shop_TPL_ADMIN', trailingslashit( Tender_Shop_TPL . 'admin' ) );
		}

		/**
		 * Tender_Shop_TPL_FRONT constant.
		 * It is used to specify template urls inside templates/front directory. If you want to
		 * create a template for front end or end user purposes, you will use from it.
		 */
		if ( ! defined( 'Tender_Shop_TPL_FRONT' ) ) {
			define( 'Tender_Shop_TPL_FRONT', trailingslashit( Tender_Shop_TPL . 'front' ) );
		}
		/*In future maybe I want to add constants for separated upload directory inside plugin directory*/
	}
}
