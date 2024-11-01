<?php
Class Class_Wcp_Main{
	public function __construct(){
		add_action('wp_head',array($this, 'reg_script_style'));
		add_action('admin_head',array($this, 'reg_script_style_admin'));

		require 'admin/wcp-custom-woocommerce.php';
		require 'admin/wcp-settings.php';

		require 'class-wcp-front-settings.php';
		require 'shop/wcp-class-modal.php';
		require 'shop/wcp-class-style-script.php';
		require 'shop/wcp-class-cart.php';
		require 'shop/wcp-class-checkout.php';

		
	}
	public function reg_script_style_admin(){
		wp_enqueue_style( 'WCP-ADMIN-CSS3', WCP__CSS .'wcp-custom-admin.css',false,'1.1','all'); 
	}
	public function reg_script_style(){
		wp_register_script('WCP-BOOTSTRAP-JS', WCP__JS.'wcp.bootstrap.min.js', array('jquery'),'1.1', true);
        wp_enqueue_script('WCP-BOOTSTRAP-JS');

        wp_register_script('WCP-CUSTOM-JS2', WCP__JS.'wcp.custom.js', array('jquery'),'1.1', true);
        wp_enqueue_script('WCP-CUSTOM-JS2');

        wp_register_script('WCP-CUSTOM-JS3', WCP__JS.'jquery.mCustomScrollbar.concat.min.js', array('jquery'),'1.1', true);
        wp_enqueue_script('WCP-CUSTOM-JS3');



        wp_enqueue_style( 'WCP-BOOTSTRAP-CSS', WCP__CSS .'wcp.bootstrap.min.css',false,'1.1','all'); 

        wp_enqueue_style( 'WCP-BOOTSTRAP-CSS2', WCP__CSS .'wcp-custom.css',false,'1.1','all'); 

        wp_enqueue_style( 'WCP-BOOTSTRAP-CSS3', WCP__CSS .'jquery.mCustomScrollbar.min.css',false,'1.1','all'); 

        
	}
	
	

}new Class_Wcp_Main;