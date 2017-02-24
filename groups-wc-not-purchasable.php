<?php
/**
 * groups-wc-not-purchasable.php
*
* Copyright (c) www.itthinx.com
*
* This code is released under the GNU General Public License.
* See COPYRIGHT.txt and LICENSE.txt.
*
* This code is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* This header and all notices must be kept intact.
*
* @author itthinx
* @package groups
* @since groups 1.0.0
*
* Plugin Name: Groups WooCommerce Not Purchasable - Example Plugin
* Plugin URI: http://www.itthinx.com/plugins/groups
* Description: An example of using the Groups plugin with WooCommerce to have products that can not be purchased by group members.
* Version: 1.0.0
* Author: itthinx
* Author URI: http://www.itthinx.com
* Donate-Link: http://www.itthinx.com
* License: GPLv3
*/

/**
 * Restrict products that belong to a certain category to be unavailable for group members.
 */
class Groups_WC_Not_Purchasable {

	/**
	 * Maps product categories to groups:
	 * 
	 * For example, products in the "Standard" product category
	 * are not available to members of the "Premium" group ...
	 * 
	 * private static $category_to_group = array(
	 *     'Standard' => 'Premium'
	 * );
	 */
	private static $category_to_group = array(
		'Standard' => 'Premium',
	);

	/**
	 * Registers the restrictions filter.
	 */
	public static function init() {
		add_filter( 'woocommerce_is_purchasable', array( __CLASS__, 'woocommerce_is_purchasable' ), 10, 2 );
	}

	/**
	 * If the current user belongs to any of the groups in self::$category_to_group and the
	 * product to the corresponding category, then the product will not be purchasable by
	 * the user.
	 * 
	 * Otherwise it will return the unmodified value of $purchasable.
	 * 
	 * @param boolean $purchasable
	 * @param WC_Product $product
	 * 
	 * @return boolean
	 */
	public static function woocommerce_is_purchasable( $purchasable, $product ) {
		$result = $purchasable;
		if ( class_exists( 'Groups_User' ) && class_exists( 'Groups_Group' ) ) {
			if ( is_user_logged_in() ) {
				$user_id = get_current_user_id();
				$user = new Groups_User( $user_id );
				foreach( self::$category_to_group as $category => $group ) {
					if ( $group = Groups_Group::read_by_name( $group ) ) {
						if ( $user->is_member( $group->group_id ) ) {
							if ( has_term( $category, 'product_cat', $product->id ) ) {
								$result = false;
								break;
							}
						}
					}
				}
			}
		}
		return $result;
	}

}
Groups_WC_Not_Purchasable::init();
