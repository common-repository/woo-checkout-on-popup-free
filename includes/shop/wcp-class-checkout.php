<?php
/**
* 
*/
class Wcp_Class_Checkout
{
	
	public function __construct()
	{
		add_action('wp_footer',array($this,'wcp_checkout_script'));
		//add_action('wc_ajax_wcp_checkout_login',array($this,'wcp_checkout_login'));

		add_action( 'template_redirect', array( $this, 'wcp_template_redirect' ), 1 );

		add_action( 'get_header', array( $this, 'remove_admin_login_header' ) );
	}
	public function remove_admin_login_header() {
		if ( isset( $_GET['wcp_modal'] ) ) {
			remove_action( 'wp_head', '_admin_bar_bump_cb' );
		}
	}
	public function wcp_template_redirect(){
		if ( isset( $_GET['wcp_modal'] ) ) {
			add_filter( 'show_admin_bar', '__return_false' );

			add_filter( 'body_class', array( 'Wcp_Class_Checkout', 'add_body_class' ) );
			add_action( 'wp_head', array( 'Wcp_Class_Checkout', 'add_frame_head' ) );
		}
	}
	public static function add_body_class($classes){
		$classes[] = 'wcp-modal-checkout';
        return $classes;
	}
	public static function add_frame_head(){
		$wps_wcp_popup_bck_color = (get_option('wps_wcp_popup_bck_color')) ? get_option('wps_wcp_popup_bck_color') : '#fff';
		$output = '<base target="_parent" />';
		$output .= '<style>body,table tbody td,table th,#payment .payment_methods > li:not(.woocommerce-notice),#order_review,#payment .payment_methods > li .payment_box, #payment .place-order{background-color:'.$wps_wcp_popup_bck_color.' !important;}</style>';
		echo $output;
	}
	public function wcp_checkout_script(){
		?>
		<script type="text/javascript">
			var $ =jQuery.noConflict();
			$(document).on('click',"#wcp_place_order", function(e) {
				
				var iframe = $('#wcp-checkout-iframe').contents();
				
				iframe.find("#place_order").click();
				iframe.find(".woocommerce-error").focus();
				
			});
		</script>
		<?php
	}
	
}new Wcp_Class_Checkout;

?>