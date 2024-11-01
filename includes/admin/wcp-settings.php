<?php
Class Wcp_Settings{

	public function __construct(){
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 );
		
		add_action( 'woocommerce_settings_tabs_wcp_checkout', array( $this, 'settings_tab' ) );
		add_action( 'woocommerce_update_options_wcp_checkout', array( $this, 'process_admin_options' ) );

	}
	public function add_settings_tab( $tabs ){
		$tabs['wcp_checkout'] = __( 'Checkout Popup', 'wps-wcp' );
		return $tabs;
	}
	public function settings_tab(){
		echo '<div class="wps-wcp-checkout">';
		woocommerce_admin_fields( self::get_settings() );
		echo "</div>";
	}

	public static function get_settings() {
       
		$settings = array(

			array(
				'name' => __( 'General Settings', 'wps-wcp' ),
				'type' => 'title',
				'desc' => __( 'Below options are help you to configure the popup activity throughout the site.', 'wps-wcp' ),
				'id'   => 'wps_wcp_general_settings'
			),

			

			array(
				'name' => __( 'Checkout popup display', 'wps-wcp' ),
				'type' => 'select',
				'desc' => __( 'When you want to show the popup.<p><u>Please Note</u></p><p>If you choose "After click on Cart page link" option, please make sure your Cart Page link element must have woocommerce default <b>"added_to_cart"</b> class, otherwise it will not work and on that situation we suggest you to use "Immediately after product add to cart" option. <p>If you want to open the popup from any custom link, please use the shortcode <b>[WPS_WCP_OPEN]</b> into the "href" link.<p class="wcp-premium">This Feature ( including shortcode ) is Available On Premium Version. [ <a href="https://www.wpsuperiors.com/shop/woocommerce-checkout-on-popup/" target="_blank;">Buy Now</a> ]</p>', 'wps-wcp' ),
				'options' => array( 'immediate' => 'Immediately after product add to cart', 'after_click' => 'After click on Cart page link' )
			),

			array(
				'name' => __( 'Steps on checkout popup', 'wps-wcp' ),
				'type' => 'select',
				'desc' => __( 'Into the popup either skip the cart page for <b>Fast Checkout Process</b> Or maintain default woocommerce behaviour. <p class="wcp-premium">This Feature Is Available On Premium Version.[ <a href="https://www.wpsuperiors.com/shop/woocommerce-checkout-on-popup/" target="_blank;">Buy Now</a> ]</p>', 'wps-wcp' ),
				'options' => array( 'cart_checkout' => 'Cart and then Checkout ( default woocommerce behaviour )' ,'skip_cart' => 'Skip Cart page, direct Checkout page ( fast checkout process )' )
			),

			array( 'type' => 'sectionend', 'id' => 'wps_wcp_general_settings_end' ),

			array(
				'name' => __( 'Global Icon Settings', 'wps-wcp' ),
				'type' => 'title',
				'desc' => __( 'Below options are help you to configure the floating Global Icon throughout the site. Global icon is not available on Cart and Checkout page.', 'wps-wcp' ),
				'id'   => 'wps_wcp_global_icon_settings'
			),

			array(
				'name' => __( 'Global icon', 'wps-wcp' ),
				'type' => 'select',
				'desc' => __( 'A floating cart icon will be visible at the bottom of the page throughout the site.', 'wps-wcp' ),
				'id'   => 'wps_wcp_global_icon',
				'options' => array( 'enable' => 'Enable', 'disable' => 'Disable' )
			),

			array(
				'name' => __( 'Global icon visibility', 'wps-wcp' ),
				'type' => 'select',
				'desc' => __( 'Floating Global icon visible to which pages.<p class="wcp-premium">This Feature Is Available On Premium Version. [ <a href="https://www.wpsuperiors.com/shop/woocommerce-checkout-on-popup/" target="_blank;">Buy Now</a> ]</p>', 'wps-wcp' ),
				'options' => array( 'woocommerce' => 'Only WooCommerce Pages ( Cart, Checkout, and My Account pages are excluded )', 'home' => 'Only Home', 'all' => 'All Pages' )
			),

			

			array(
				'name' => __( 'Global icon background color', 'wps-wcp' ),
				'type' => 'color',
				'desc' => __( 'Floating Global icon background color. Please choose any color except <b>White.</b>', 'wps-wcp' ),
				'id'   => 'wps_wcp_global_icon_bck_color',
				'default'=>'#f91504'
			),

			array(
				'name' => __( 'Global icon hover background color', 'wps-wcp' ),
				'type' => 'color',
				'desc' => __( ' This hover background color will be shown when an user hover / place the curser upon the Floating Global icon . Please choose any color except <b>White.</b>', 'wps-wcp' ),
				'id'   => 'wps_wcp_global_icon_hover_bck_color',
				'default'=>'#e82517'
			),

			
			
			array( 'type' => 'sectionend', 'id' => 'wps_wcp_global_icon_end' ),

			array(
				'name' => __( 'Popup Settings', 'wps-wcp' ),
				'type' => 'title',
				'desc' => __( 'Below options are help you to change style of the popup to make it looks good.', 'wps-wcp' ),
				'id'   => 'wps_wcp_popup_settings'
			),

			array(
				'name' => __( 'Popup header text', 'wps-wcp' ),
				'type' => 'text',
				'desc' => __( 'Text appear at the top of the popup', 'wps-wcp' ),
				'id'   => 'wps_wcp_popup_header_text',
				'default' => 'Your Cart Page Is Here'
			),

			array(
				'name' => __( 'Popup footer text', 'wps-wcp' ),
				'type' => 'text',
				'desc' => __( 'Text appear at the bottom of the popup.', 'wps-wcp' ),
				'id' => 'wps_wcp_popup_footer_text',
				'default' => 'Powered by WooCommerce Checkout On Popup Plugin from WPSuperiors'
			),

			array(
				'name' => __( 'Popup footer button', 'wps-wcp' ),
				'type' => 'select',
				'desc' => __( '3 types of buttons available on footer. Proceed to checkout, Back to cart, and Close. <p class="wcp-premium">This Feature Is Available On Premium Version. [ <a href="https://www.wpsuperiors.com/shop/woocommerce-checkout-on-popup/" target="_blank;">Buy Now</a> ]</p>', 'wps-wcp' ),
				'options' => array( 'disable'=>'Disable', 'enable' => 'Enable')
			),

			array(
				'name' => __( 'Popup background color', 'wps-wcp' ),
				'type' => 'color',
				'desc' => __( 'Please note, within the popup woocommerce ( exists on your theme ) buttons are used, do not select any color which is the same as button background color. You can change the button background color from Theme Customizer. ', 'wps-wcp' ),
				'id'   => 'wps_wcp_popup_bck_color',
				'default'=> '#fff'
			),

			
			array(
				'name' => __( 'Popup custom CSS', 'wps-wcp' ),
				'type' => 'textarea',
				'id'   => 'wps_wcp_popup_css',
				'default'=> ''
			),

			array( 'type' => 'sectionend', 'id' => 'wps_wcp_popup_settings_end' ),
			array(
				'name' => __( 'S U P P O R T from   W P S U P E R I O R S', 'wps-wcp' ),
				'type' => 'title',
				'desc' => __( '<p style="margin-top:30px;">Need more features or Any Query? Feel free to write on us <a style="text-decoration:none;" href="mailto:support@wpsuperiors.com">support@wpsuperiors.com</a> OR visit <a style="text-decoration:none;" href="http://www.wpsuperiors.com/contact-us/" target="_blank">Contact Us</a></p><p style="margin-top:30px;">Popup design do not going well with your store theme ? Don\'t worry, we will fix that for you, please write us at <a style="text-decoration:none;" href="mailto:support@wpsuperiors.com">support@wpsuperiors.com</a> with screenshot and full description what you want.</p>', 'wps-wcp' ),
				'id'   => 'wps_wcp_popup_settings_support'
			),
			array( 'type' => 'sectionend', 'id' => 'wps_wcp_popup_settings_support_end' )
		);
		
        return apply_filters( 'wc_wcp_checkout', $settings );
    }
	public function process_admin_options(){
		
		 woocommerce_update_options( self::get_settings() );
	}
	

}new Wcp_Settings;