# groups-wc-not-purchasable
An example of using the Groups plugin with WooCommerce to have products that can not be purchased by group members.

This plugin can be used to have products that can not be purchased by group members.

## Using a filter hook to set up which product categories cannot be seen by which groups.
Use the filter `groups_wc_not_purchasable_category_to_group` to change this.
Let's have a look at an example. In this example, we have three product categories that will be hidden from members of certain groups.

The product categories are:

- Basic Membership
- Premium Membership
- Diamond Membership

The groups are:

- Premium
- Platinum
- Diamond

We don't want to let members of the Premium, Platinum or Diamond group purchase products from the "Basic Membership" product category.
Likewise, members of the Platinum or Diamond group shouldn't
purchase products from the "Premium Membership" or "Platinum Membership" product categories.
Our Diamond members shouldn't be able to purchase products from the basic, premium or platinum product categories.
This is how we set up our filter based on these requirements. You can place this in your theme's `functions.php` file.

```
add_filter( 'groups_wc_not_purchasable_category_to_group', 'my_category_to_group_filter' );
function my_category_to_group_filter( $category_to_group ) {
	return array(
		'Basic Membership'    => 'Premium',
		'Premium Membership'  => 'Platinum',
		'Platinum Membership' => 'Diamond'
	);
}
```

## Alternative to using the filter (not recommended).

In the plugin's main file, an array $category_to_group determines which product categories are unavailable for which groups. By default, it is declared like this:

```
private static $category_to_group = array(
	'Standard' => 'Premium',
);
```

This means that any product that belongs to the "Standard" product category cannot be purchased by members of the "Premium" group.

You could change this and add as many entries as needed, for example:

```
private static $category_to_group = array(
	'Standard' => 'Premium',
	'Wholesale' => 'Distributor'
);
```

... would also exclude members of the "Distributor" group from purchasing products in the "Wholesale" product category.

## Usage

- Install and activate this plugin.
- Create a group named "Premium".
- Assign a user to the "Premium" group.
- Assign one or more products to a category named "Standard".
- Users in the "Premium" group can not purchase the products in the "Standard" category.
- Any visitor is allowed to see those products, but members of the excluded groups cannot purchase them.

## Requirements
- [Groups](http://wordpress.org/plugins/groups/)
- [WooCommerce](http://wordpress.org/plugins/woocommerce/)
