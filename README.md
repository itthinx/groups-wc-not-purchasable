# groups-wc-not-purchasable
An example of using the Groups plugin with WooCommerce to have products that can not be purchased by group members.

This plugin can be used to have products that can not be purchased by group members.

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
