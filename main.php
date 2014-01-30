<?php  
/* 
	Plugin Name: Don't Pay
	Plugin URI: http://www.simone-poggi.com/
	Description: WooCommerce plugin to avoid payment procedure for certain orders
	Version: 0.1 
	Author: Simone Poggi
	Author URI: http://www.simone-poggi.com/
	License: MIT
*/  
 
/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
 
	function your_shipping_method_init() {
		if ( ! class_exists( 'WC_Your_Shipping_Method' ) ) {
			class WC_Your_Shipping_Method extends WC_Shipping_Method {
				/**
				 * Constructor for your shipping class
				 *
				 * @access public
				 * @return void
				 */
				public function __construct() {
					$this->id                 = 'your_shipping_method'; // Id for your shipping method. Should be uunique.
					$this->method_title       = __( 'Your Shipping Method' );  // Title shown in admin
					$this->method_description = __( 'Description of your shipping method' ); // Description shown in admin
 
					$this->enabled            = "yes"; // This can be added as an setting but for this example its forced enabled
					$this->title              = "My Shipping Method"; // This can be added as an setting but for this example its forced.
 
					$this->init();
				}
 
				/**
				 * Init your settings
				 *
				 * @access public
				 * @return void
				 */
				function init() {
					// Load the settings API
					$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
					$this->init_settings(); // This is part of the settings API. Loads settings you previously init.
 
					// Save settings in admin if you have any defined
					add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
				}
 
				/**
				 * calculate_shipping function.
				 *
				 * @access public
				 * @param mixed $package
				 * @return void
				 */
				public function calculate_shipping( $package ) {
					$rate = array(
						'id' => $this->id,
						'label' => $this->title,
						'cost' => '10.99',
						'calc_tax' => 'per_item'
					);
 
					// Register the rate
					$this->add_rate( $rate );
				}
			}
		}
	}
 
	add_action( 'woocommerce_shipping_init', 'your_shipping_method_init' );
 
	function add_your_shipping_method( $methods ) {
		$methods[] = 'WC_Your_Shipping_Method';
		return $methods;
	}
 
	add_filter( 'woocommerce_shipping_methods', 'add_your_shipping_method' );
}