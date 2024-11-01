<?php
Class Class_Wcp_Modal{
	public function __construct(){
		add_action('wp_footer',array($this, 'all_modals'));
		add_action('wc_ajax_wcp_generate_modal_content',array($this,'wcp_generate_modal_content'));
	}
	
	public function all_modals(){
		$wps_wcp_popup_header_text = (get_option('wps_wcp_popup_header_text')) ? get_option('wps_wcp_popup_header_text') : 'Your Cart Page Is Here';

		$wps_wcp_popup_footer_text = (get_option('wps_wcp_popup_footer_text')) ? get_option('wps_wcp_popup_footer_text') : 'Powered by WooCommerce Checkout On Popup Plugin from WPSuperiors';
		?>
    		<div class="modal fade" id="waitModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:10%; overflow-y:visible;">
			    <div class="modal-dialog" style="margin:30px 38%">
			      <div class="modal-content">
			      	<div class="modal-header" style="border:none;">
			      		<p id="wcp_loading_header_message"></p>
			      	</div>
			      	<div class="modal-body">
			      		<p style="margin-left:40%;"><img src="<?php echo WCP__IMG ?>front_loader.gif"></p>
			      	</div>
			      	<div class="modal-footer" style="border:none; text-align:center;">
			      		<p id="wcp_loading_footer_message"></p>
			        </div>
			      </div>
			    </div>
		  	</div>

		  	<div class="modal fade" id="emptyCartModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-left:25%; padding-top:10%;overflow-y:visible;">
			    <div class="modal-dialog">
			      <div class="modal-content wcp-modal-content">
			        <div class="modal-header" style="text-align: center;">
			          <button type="button" class="close" data-dismiss="modal">&times;</button>
			          <h4 class="modal-title">No Product Found On Your Cart</h4>
			        </div>
				    <div class="modal-body">
			        	For checkout please add minimum one product on your cart.
				    </div>
			        <div class="modal-footer" style="text-align: center;">
			        	<?php 
			        		$shop_page_id = wc_get_page_id( 'shop' );
							$shop_page_url = $shop_page_id ? get_permalink( $shop_page_id ) : '';
			        	?>
			        	<a href="<?php echo $shop_page_url; ?>" class="btn btn-primary">Continue To Shop</a>
			        </div>
			      </div>
			      
			    </div>
		  	</div>

		  	<div class="modal fade" id="cartModal" role="dialog">
			    <div class="modal-dialog" style="width: 80%;">
			      <!-- Modal content-->
			      <div class="modal-content wcp-modal-content" style="width: 100%;">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal">&times;</button>
			          <h3 class="modal-title"><?php echo $wps_wcp_popup_header_text; ?></h3>
			          <div class="wcp-cart-modal-message" role="alert" style="display:none;"></div>
			          <!-- <div class="woocommerce-error" role="alert">Cart Not updated.</div> -->
			        </div>
				    <div class="modal-body mCustomScrollbar" data-mcs-theme="dark">
			        	<div class="wcp-cart-loader">
			        		<img src="<?php echo WCP__IMG ?>front_loader.gif">
			        	</div>
			        	<p style="margin-left:40%;"></p>
			        	<p id="wcp_message"><?php wc_print_notices(); ?></p>
			          	<p id="wcp_cart"></p>
				    </div>
			        <div class="modal-footer">
			          	<p><?php echo $wps_wcp_popup_footer_text; ?></p>
			        </div>
			      </div>
			      
			    </div>
		  	</div>

		  	<div class="modal fade" id="checkoutModal" role="dialog">
			    <div class="modal-dialog" style="width: 80%;">
			      <!-- Modal content-->
			      <div class="modal-content wcp-modal-content" style="width: 100%;">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal">&times;</button>
			          <h4 class="modal-title"><?php echo $wps_wcp_popup_header_text; ?></h4>
			           <div class="wcp-checkout-modal-message" role="alert" style="display:none;"></div>
			        </div>
			        <div class="modal-body mCustomScrollbar" data-mcs-theme="dark">
			        	<div class="wcp-cart-loader">
			        		<img src="<?php echo WCP__IMG ?>front_loader.gif">
			        	</div>
			        	<p id="wcp_message"><?php wc_print_notices(); ?></p>
			          	<p id="wcp_checkout"></p>
			        </div>
			        <div class="modal-footer">
			          	<p><?php echo $wps_wcp_popup_footer_text; ?></p>
			        </div>
			      </div>
			      
			    </div>
		  	</div>
	<?php
	}

	public function wcp_generate_modal_content(){
		if(isset($_POST['option']) && !$_POST['option'])
		{
			echo "Sorry! Problem occured. Please refresh the page and try again.";
			exit;
		}
		
		switch($_POST['option']){
			case 'cart':
				echo do_shortcode('[woocommerce_cart]');
				break;
			case 'checkout':
				echo '<iframe src="'.site_url().'/checkout/?wcp_modal=true" scrolling="no" style="overflow: hidden; width: 100%; height: 1790px;" name="wcp-checkout-iframe" class="wcp-iframe" id="wcp-checkout-iframe" frameborder="0"></iframe>';
				break;

		}
	}

}new Class_Wcp_Modal;