<?php  
/* 
	Plugin Name: Don't Pay
	Plugin URI: http://www.simone-poggi.com/
	Description: WooCommerce plugin to avoid payment procedure
	Version: 0.1 
	Author: Simone Poggi
	Author URI: http://www.simone-poggi.com/
	License: MIT
*/  
 
/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
 
	function my_plugin_init() {
		if ( ! class_exists( 'My_Cheque_Order' ) ) {
			class My_Cheque_Order extends WC_Gateway_Cheque {
				/**
				 * Constructor for your shipping class
				 *
				 * @access public
				 * @return void
				 */
				public function __construct() {
					$this->id                 = 'My_Cheque_Order'; // Id for your shipping method. Should be uunique.
					$this->method_title       = __( "Inoltra l'Ordine" );  // Title shown in admin
					$this->method_description = __( 'Inoltra la richiesta direttamente ai nostri uffici. Verrete contattati in seguito per ricevere tutti i dettagli su come procedere all\'acquisto' ); // Description shown in admin
					$this->enabled            = "yes"; // This can be added as an setting but for this example its forced enabled
 					$this->hasFields          = false;

					$this->init_form_fields();
					$this->init_settings();

					add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
				}
				
				public function init_form_fields() {
					$this->form_fields = array(
						'enabled' => array(
							'title' => __( 'Enable/Disable', 'woocommerce' ),
							'type' => 'checkbox',
							'label' => __( 'Enable Cheque Payment', 'woocommerce' ),
							'default' => 'yes'
						),
						'title' => array(
							'title' => __( 'Title', 'woocommerce' ),
							'type' => 'text',
							'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
							'default' => __( 'Cheque Payment', 'woocommerce' ),
							'desc_tip'      => true,
						),
						'description' => array(
							'title' => __( 'Customer Message', 'woocommerce' ),
							'type' => 'textarea',
							'default' => ''
						)
					);
				}
			}
		}
	}

	add_action( 'plugins_loaded', 'my_plugin_init' );
 
	function add_This( $methods ) {
		$methods[] = 'My_Cheque_Order';
		return $methods;
	}
 
	add_filter( 'woocommerce_payments_gateways', 'add_This' );
}