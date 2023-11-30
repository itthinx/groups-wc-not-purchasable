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
 *
 * @since 1.0.0
 *
 * Plugin Name: Groups WooCommerce Not Purchasable - Example Plugin
 * Plugin URI: http://www.itthinx.com/plugins/groups
 * Description: An example of using the Groups plugin with WooCommerce to have products that can not be purchased by group members.
 * Version: 1.2.0
 * Author: itthinx
 * WC requires at least: 7.9
 * WC tested up to: 8.3
 * Author URI: https://www.itthinx.com
 * Donate-Link: https://www.itthinx.com
 * License: GPLv3
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Restrict products that belong to a certain category to be unavailable for group members.
 */
class Groups_WC_Not_Purchasable {

	/**
	 * Maps product categories to groups:
	 *
	 * For example, products in the "Standard" product category
	 * are not available to members of the "Premium" group - use the
	 * groups_wc_not_purchasable_category_to_group filter to return
	 * and array holding:
	 *
	 * <code>array( 'Standard' => 'Premium' )</code>
	 * 
	 * Alternatively, you could fork this plugin and directly define it here.
	 */
	private static $category_to_group = array();

	/**
	 * Registers the restrictions filter.
	 */
	public static function init() {
		add_filter( 'woocommerce_is_purchasable', array( __CLASS__, 'woocommerce_is_purchasable' ), 10, 2 );
		add_filter( 'woocommerce_is_visible', array( __CLASS__, 'woocommerce_product_is_visible' ), 10, 2 );
		add_action( 'before_woocommerce_init', array( __CLASS__, 'before_woocommerce_init' ) );
	}

	/**
	 * Declare HPOS compatibility
	 *
	 * @since 1.2.0
	 */
	public static function before_woocommerce_init() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}

	/**
	 * If the current user belongs to any of the groups in self::$category_to_group and the
	 * product to the corresponding category, then the product will not be purchasable by
	 * the user.
	 * 
	 * Otherwise it will return the unmodified value of $purchasable.
	 * 
	 * @param boolean $purchasable
	 * @param WC_Product|int $product
	 * 
	 * @return boolean
	 */
	public static function woocommerce_is_purchasable( $purchasable, $product ) {
		$result = $purchasable;
		if ( is_object( $product ) ) {
			$product_id = method_exists( $product, 'get_id' ) ? $product->get_id() : $product->id;
		} else {
			$product_id = intval( $product );
		}
		if ( class_exists( 'Groups_User' ) && class_exists( 'Groups_Group' ) ) {
			if ( is_user_logged_in() ) {
				$user_id = get_current_user_id();
				$user = new Groups_User( $user_id );
				$category_to_group = apply_filters(
					'groups_wc_not_purchasable_category_to_group',
					self::$category_to_group
				);
				if ( is_array( $category_to_group ) ) {
					foreach( $category_to_group as $category => $group ) {
						if ( $group = Groups_Group::read_by_name( $group ) ) {
							if ( $user->is_member( $group->group_id ) ) {
								if ( has_term( $category, 'product_cat', $product_id ) ) {
									$result = false;
									break;
								}
							}
						}
					}
				}
			}
		}
		return $result;
	}

	/**
	 * Filters the product visibility.
	 *
	 * @param boolean $visible
	 * @param int $product_id
	 *
	 * @return boolean true if product is visible
	 */
	public static function woocommerce_product_is_visible( $visible, $product_id ) {
		if ( apply_filters( 'groups_wc_not_purchasable_filter_visibility', true, $visible, $product_id ) ) {
			$visible = self::woocommerce_is_purchasable( $visible, $product_id );
		}
		return $visible;
	}
}
Groups_WC_Not_Purchasable::init();
