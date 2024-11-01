<?php
Class Wcp_Custom_Woocommerce{

	public function __construct(){
		add_action( 'admin_init',array($this,'update_ajax_add_to_cart') );
		add_action( 'woocommerce_init',array($this,'update_ajax_add_to_cart') );
		add_action( 'init',array($this,'update_ajax_add_to_cart') );
		add_filter( 'woocommerce_product_settings', array($this,'hide_the_woocommerce_settings'), 10, 1 );
		add_filter( 'woocommerce_shipping_settings', array($this,'hide_the_woocommerce_shipping_settings'), 10, 1 );  
	}
	public function update_ajax_add_to_cart(){
		update_option( 'woocommerce_enable_ajax_add_to_cart','yes' );
		update_option( 'woocommerce_cart_redirect_after_add','no' );
		update_option( 'woocommerce_enable_shipping_calc','no' );
	}
	public function hide_the_woocommerce_shipping_settings($array)
	{
		//echo "<pre>"; print_r($array); echo "</pre>";
		unset( $array[1] );
		return $array;
	}
	public function hide_the_woocommerce_settings($array){
		
		unset( $array[2] );
		unset( $array[3] );
		return $array;
	}

}new Wcp_Custom_Woocommerce;