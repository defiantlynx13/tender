<?php
/**
 * De-activator Class File
 *
 * This class defines tasks that must be run when plugin is deactivated.
 *
 * @category   Uninstall
 * @package    Tender_Shop_Dir\Includes\Uninstall
 * @author     Your_Name <youremail@nomail.com>
 * @license    https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link       https://yoursite.com
 * @since      1.0.0
 */

namespace Tender_Shop_Dir\Includes\Uninstall;

/**
 * Class Deactivator.
 * You can run desire tasks with this class when your plugin is de-activated.
 *
 * @package    Tender_Shop_Dir\Includes\Uninstall
 * @author     Your_Name <youremail@nomail.com>
 */
class Deactivator {

	/**
	 * Run related tasks when plugin is deactivated
	 *
	 * @access public
	 * @since  1.0.0
	 * @static
	 */
	public static function deactivate() {

		if ( get_option( 'Tender_Shop_prefix_plugin_setting_option1' ) ) {
			update_option(
				'Tender_Shop_prefix_plugin_setting_option1',
				'After de-activation'
			);
		}

		if ( get_option( 'Tender_Shop_prefix_plugin_setting_option4' ) ) {
			delete_option( 'Tender_Shop_prefix_plugin_setting_option4' );
		}
	}

}


