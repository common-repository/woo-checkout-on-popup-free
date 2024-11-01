<?php
/*
Plugin Name: WooCommerce Checkout On Popup (FREE)
Plugin URI: https://www.wpsuperiors.com/woocommerce-checkout-on-popup/
Description: Boost you store sell by made the checkout process easy and fast using Popup.
Version: 1.2.0
Author: WPSuperiors
Author URI: https://wpsuperiors.com/
Text Domain: wcp-lan
Domain Path: /languages/
* WC requires at least: 3.4.0
* WC tested up to: 8.3.1
*/
if ( ! defined( 'ABSPATH' ) ) {
	wp_die('Please Go Back');
	exit;
}

add_action( 'plugins_loaded', 'WPS_WCP_language' );

function WPS_WCP_language() {
  load_plugin_textdomain( 'wcp-lan', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
add_action( 'admin_init', 'wcp_woo_active_check' );
function wcp_woo_active_check() {
    if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
        add_action( 'admin_notices', 'wcp_woo_active_failed_notice' );
        deactivate_plugins( plugin_basename( __FILE__ ) ); 
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
}

function wcp_woo_active_failed_notice(){
    ?><div class="error"><p>Please Activate <b>WooCommerce</b> Plugin, Before You Proceed To Activate <b>WooCommerce Checkout On Popup (Free/Premium)</b> Plugin.</p></div><?php
}


define( 'WCP__BASE', plugin_basename( __FILE__ ) );
define( 'WCP__DIR', plugin_dir_path( __FILE__ ) );
define( 'WCP__URL', plugin_dir_url( __FILE__ ) );
define( 'WCP__AST', plugin_dir_url( __FILE__ ).'includes/assets/' );
define( 'WCP__JS', plugin_dir_url( __FILE__ ).'includes/assets/js/' );
define( 'WCP__CSS', plugin_dir_url( __FILE__ ).'includes/assets/css/' );
define( 'WCP__IMG', plugin_dir_url( __FILE__ ).'includes/assets/images/' );


require 'includes/class-wcp-main.php';

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wcp_add_action_links' );

function wcp_add_action_links ( $links ) {
 $wcp_action_links = array(
 '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=wcp_checkout' ) . '">Settings</a>',
 '<a href="https://www.wpsuperiors.com/woocommerce-checkout-on-popup-details/" target="_blank;">Documentation</a>',
 '<a href="https://www.wpsuperiors.com/woocommerce-checkout-on-popup/" target="_blank;">Get The Premium</a>'
 );
  
return array_merge( $links, $wcp_action_links );
}