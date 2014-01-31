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
 

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
 
	// WooCommerce is active
	
	function init_MC() {
		if ( ! class_exists( 'Gateway_MC' ) ) {
			class Gateway_MC extends WC_Gateway_Cheque {
				/**
				 * Constructor for your shipping class
				 *
				 * @access public
				 * @return void
				 */
				public function __construct() {
					$this->id                 = 'Gateway_MC';
					$this->title              = __( "Inoltra l'Ordine" );
					$this->method_title       = __( "Inoltra l'Ordine" );
					$this->description        = __( 'Inoltra la richiesta direttamente ai nostri uffici. Verrete contattati in seguito per ricevere tutti i dettagli su come procedere all\'acquisto' ); 
					$this->enabled            = "yes";
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

	add_action( 'plugins_loaded', 'init_MC' );
 
	function add_Gateway_MC_Class( $methods ) {
		$methods[] = 'Gateway_MC';
		return $methods;
	}
 
	add_filter( 'woocommerce_payment_gateways', 'add_Gateway_MC_Class' );
}