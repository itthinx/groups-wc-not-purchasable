# groups-wc-not-purchasable
This plugin is an extension to Groups and WooCommerce to have products that can not be purchased by group members.

This plugin can be used to have products that can not be purchased by group members.

## Requirements
- [Groups](http://wordpress.org/plugins/groups/)
- [WooCommerce](http://wordpress.org/plugins/woocommerce/)

## Usage

Usage example based on the first filter example as shown below.

- You must have [Groups](http://wordpress.org/plugins/groups/) and [WooCommerce](http://wordpress.org/plugins/woocommerce/) activated.
- Install and activate this plugin.
- Create a group named "Premium".
- Assign a user to the "Premium" group.
- Assign one or more products to a category named "Standard".
- Add the filter hook to your `functions.php` as shown below.
- Users in the "Premium" group can not purchase the products in the "Standard" category.
- Any visitor is allowed to see those products, but members of the excluded groups cannot purchase them.

## Using a filter hook to set up which product categories cannot be purchased by which groups.

We will use the filter `groups_wc_not_purchasable_category_to_group` to determine relationships between product categories and groups - group members will not be able to purchase products from related product categories.

### Simple Example

In this example we will not allow members of the "Premium" group to purchase products that are in the "Standard" product category.

This is how we set up our filter based on these requirements. You can place this in your theme's `functions.php` file.

```
add_filter( 'groups_wc_not_purchasable_category_to_group', 'my_simple_category_to_group_filter' );
function my_simple_category_to_group_filter( $category_to_group ) {
	return array(
		'Standard' => 'Premium'
	);
}
```

### Added more entries based on the Simple Example

You could change the above and add as many entries as needed. For example, to also exclude members of the "Distributor" group from purchasing products in the "Wholesale" product category:

```
add_filter( 'groups_wc_not_purchasable_category_to_group', 'my_simple_category_to_group_filter' );
function my_simple_category_to_group_filter( $category_to_group ) {
	return array(
		'Standard'  => 'Premium',
		'Wholesale' => 'Distributor'
	);
}
```

### Advanced Example

In this example, we have three product categories that will be hidden from members of certain groups.

The product categories are:

- Basic Membership
- Premium Membership
- Diamond Membership

The groups are:

- Premium
- Platinum
- Diamond

We don't want to let members of the Basic, Premium, Platinum or Diamond group purchase products from product categories that belong to products that grant access to the same or inferior membership.

Members of the Platinum or Diamond group shouldn't purchase products from the inferior "Premium Membership" or "Platinum Membership" product categories.

Our Diamond members shouldn't be able to purchase products from the basic, premium, platinum or diamond product categories.

This is how we set up our filter based on these requirements. You can place this in your theme's `functions.php` file.

```
add_filter( 'groups_wc_not_purchasable_category_to_group', 'my_advanced_category_to_group_filter' );
function my_advanced_category_to_group_filter( $category_to_group ) {
	return array(
		'Basic Membership'    => 'Basic',
		'Basic Membership'    => 'Premium',
		'Basic Membership'    => 'Platinum',
		'Basic Membership'    => 'Diamond',
		'Premium Membership'  => 'Premium',
		'Premium Membership'  => 'Platinum',
		'Premium Membership'  => 'Diamond',
		'Platinum Membership' => 'Platinum'
		'Platinum Membership' => 'Diamond',
		'Diamond Membership'  => 'Diamond'
	);
}
```
