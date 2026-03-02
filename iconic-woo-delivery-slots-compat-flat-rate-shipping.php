<?php
/**
 * Plugin Name:     WooCommerce Delivery Slots by Kadence [Flat Rate Shipping Plugin For WooCommerce]
 * Plugin URI:      https://iconicwp.com/products/woocommerce-delivery-slots/
 * Description:     Compatibility between WooCommerce Delivery Slots by Kadence and Flat Rate Shipping Plugin For WooCommerce by theDotstore.
 * Author:          Kadence WP
 * Author URI:      https://www.kadencewp.com/
 * Text Domain:     iconic-woo-delivery-slots-compat-flat-rate-shipping
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Iconic_Woo_Delivery_Slots_Compat_Flat_rate_shipping
 */

/**
 * Is Flat Rate Shipping Plugin For WooCommerce active?
 *
 * @return bool
 */
function iconic_compat_frs_is_active() {
	return defined( 'AFRSM_PRO_PLUGIN_VERSION' ) || defined( 'AFRSM_PRO_PREMIUM_VERSION' ) || defined( 'AFRSM_PLUGIN_VERSION' );
}

/**
 * Remove default options.
 *
 * @return array
 */
function iconic_compat_frs_remove_default_shipping_method_options( $shipping_method_options ) {
	if ( ! iconic_compat_frs_is_active() ) {
		return $shipping_method_options;
	}

	$sm_args = array(
		'post_type'        => 'wc_afrsm',
		'posts_per_page'   => -1,
		'orderby'          => 'menu_order',
		'order'            => 'ASC',
		'suppress_filters' => false,
	);

	$shipping_methods = get_posts( $sm_args );

	foreach ( $shipping_methods as $shipping_method ) {
		$id    = sprintf( 'advanced_flat_rate_shipping:%d', $shipping_method->ID );
		$title = sprintf( 'Advanced Flat Rate Shipping: %s', get_the_title( $shipping_method->ID ) );

		$shipping_method_options[ $id ] = $title;
	}

	return $shipping_method_options;
}

add_filter( 'iconic_wds_shipping_method_options', 'iconic_compat_frs_remove_default_shipping_method_options', 10 );

/**
 * Activate the plugin.
 */
function iconic_compat_frs_activate() {
	delete_transient( 'iconic-wds-shipping-methods' );
}
register_activation_hook( __FILE__, 'iconic_compat_frs_activate' );
