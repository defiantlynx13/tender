<?php
/**
 * Sample Page Handler Class File
 *
 * This file contains Second_Page_Handler class which is used to render a page in your project
 * with a specific route or url.
 *
 * @package    Tender_Shop_Dir\Includes\PageHandlers
 * @author     Your_Name <youremail@nomail.com>
 * @license    https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link       https://yoursite.com
 * @since      1.0.0
 */

namespace Tender_Shop_Dir\Includes\PageHandlers;

use Tender_Shop_Dir\Includes\PageHandlers\Contracts\Page_Handler;
use Tender_Shop_Dir\Includes\Functions\Utility;

/**
 * Class Second_Page_Handler.
 * This class  is used to render a page in your project with a specific route or url.
 *
 * @package    \Tender_Shop_Dir\Includes\PageHandlers
 * @author     Your_Name <youremail@nomail.com>
 * @see        \Tender_Shop_Dir\Includes\Functions\Utility
 * @see        \Tender_Shop_Dir\Includes\PageHandlers\Contracts\Page_Handler
 */
class Shop_Managment_Handler implements Page_Handler {

	/**
	 * Method render in First_Page_Handler Class
	 *
	 * It calls when you need to render a view in your website.
	 *
	 * @access  public
	 */
	public function render() {
        $dash_assets_url=trailingslashit( Tender_Shop_URL ) . 'assets/dashboard/';
		Utility::load_template( 'shop-managment.shop-managment', compact( 'dash_assets_url' ), 'front' );
		exit;
	}
}
