<?php
class Wcp_Class_Cart{
	public function __construct(){
		add_filter( 'woocommerce_cart_item_class', array($this,'filter_woocommerce_cart_item_class'), 10, 3 ); 
		add_action('wp_footer',array($this, 'load_cart_script'));
		add_action('wc_ajax_wcp_update_cart',array($this,'wcp_update_cart'));
		add_action('wc_ajax_wcp_coupon_apply',array($this,'wcp_coupon_apply'));
		add_action('wc_ajax_wcp_coupon_remove',array($this,'wcp_coupon_remove'));
		add_action('wc_ajax_wcp_add_to_cart_generate_modal_content',array($this,'wcp_add_to_cart_generate_modal_content'));
	}
	
	public function filter_woocommerce_cart_item_class($cart_item1, $cart_item, $cart_item_key){
		$cart_item = ' wcp-has-key wcp-data-key=' . $cart_item_key;
		return $cart_item;
	}
	public function load_cart_script(){
		?>
		<script type="text/javascript">
			var $ =jQuery.noConflict();

			/* REMOVE */
			$(document).on('click','.wcp-modal-content .shop_table .remove',function(e){
				$(".wcp-cart-loader").fadeIn('slow');
				$(".wcp-cart-modal-message").removeClass('woocommerce-error');
				$(".wcp-cart-modal-message").removeClass('woocommerce-message');
				$(".wcp-cart-modal-message").html('');
				$(".wcp-cart-modal-message").fadeOut('slow');
				e.preventDefault();
				var cart_key = $(this).parents('tr').attr('wcp-cart-key');
				//console.log(cart_key);
				update_cart(cart_key,0);

				
				
				$(this).parents('tr').fadeOut('slow',function(){
					$(".wcp-cart-modal-message").removeClass('woocommerce-error');
					$(".wcp-cart-modal-message").addClass('woocommerce-message');
					$(".wcp-cart-modal-message").html('Product successfully removed from cart');

					$(".wcp-cart-modal-message").fadeIn('slow',function(){
						$(".wcp-cart-loader").fadeOut('slow');
					});
				});
			});

			/* QTY */
			$(document).on('change','.wcp-modal-content .shop_table .product-quantity .quantity .qty',function(e){
				$(".wcp-cart-loader").fadeIn('slow');
				$(".wcp-cart-modal-message").removeClass('woocommerce-error');
				$(".wcp-cart-modal-message").removeClass('woocommerce-message');
				$(".wcp-cart-modal-message").html('');
				$(".wcp-cart-modal-message").fadeOut('slow');

				e.preventDefault();
				var cart_key = $(this).parents('tr').attr('wcp-cart-key');
				var new_qty = parseFloat($(this).val());
				//var step = parseFloat($(this).attr('step'));
				var min_value = parseFloat($(this).attr('min'));
				var max_value = parseFloat($(this).attr('max'));
				var invalid  = false;

	
				if(isNaN(new_qty)  || new_qty < 0){
					$(this).val(1);
					$(this).focus();
					$(".wcp-cart-modal-message").removeClass('woocommerce-message');
					$(".wcp-cart-modal-message").addClass('woocommerce-error');
					$(".wcp-cart-modal-message").html('Wrong quantity entered.');
					$(".wcp-cart-modal-message").fadeIn('slow',function(){
						$(".wcp-cart-loader").fadeOut('slow');
					});
					return false;
				}

				if(new_qty > max_value && max_value > 0){
					$(this).val(max_value);
					$(this).focus();
					$(".wcp-cart-modal-message").removeClass('woocommerce-message');
					$(".wcp-cart-modal-message").addClass('woocommerce-error');
					$(".wcp-cart-modal-message").html('Wrong quantity entered.');
					$(".wcp-cart-modal-message").fadeIn('slow',function(){
						$(".wcp-cart-loader").fadeOut('slow');
					});
					return false;
				}

				if(new_qty < min_value){
					$(this).val(min_value);
					$(this).focus();
					$(".wcp-cart-modal-message").removeClass('woocommerce-message');
					$(".wcp-cart-modal-message").addClass('woocommerce-error');
					$(".wcp-cart-modal-message").html('Wrong quantity entered.');
					$(".wcp-cart-modal-message").fadeIn('slow',function(){
						$(".wcp-cart-loader").fadeOut('slow');
					});
					return false;
				}

				if(invalid == false){
					e.preventDefault();
					$(".wcp-cart-loader").fadeIn('slow');
					update_cart(cart_key,new_qty);
					$(".wcp-cart-modal-message").removeClass('woocommerce-error');
					$(".wcp-cart-modal-message").addClass('woocommerce-message');
					$(".wcp-cart-modal-message").html('Cart successfully updated');
					$(".wcp-cart-modal-message").fadeIn('slow',function(){
						$(".wcp-cart-loader").fadeOut('slow');
					});
				}
			});

			/* COUPON */
			
			$(document).on('click','.wcp-modal-content .shop_table .actions .coupon .button',function(e){
				var couponcode = $(".wcp-modal-content .shop_table .actions #coupon_code").val();
				$(".wcp-cart-loader").fadeIn('slow');
				$(".wcp-cart-modal-message").removeClass('woocommerce-error');
				$(".wcp-cart-modal-message").removeClass('woocommerce-message');
				$(".wcp-cart-modal-message").html('');
				$(".wcp-cart-modal-message").fadeOut('slow');
				e.preventDefault();
				$.ajax({
					url: "<?php echo site_url();?>/?wc-ajax=wcp_coupon_apply",
					type: 'POST',
					data: {
						couponcode: couponcode,
					},
					success: function(response){
							$.ajax({
								url: "<?php echo site_url();?>/?wc-ajax=wcp_generate_modal_content",
								type: 'POST',
								data: {
									option: 'cart'
								},
								success: function(response){
									$("#wcp_cart").html(response);
									$(".checkout-button").attr("href","javascript:void(0);");
									$(".checkout-button").attr("data-toggle","modal");
									$(".checkout-button").attr("data-target","#checkoutModal");
									$(".checkout-button").attr("data-dismiss","modal");
									$('.wcp-modal-content table tbody tr').each(function(){
									   	if ($(this).hasClass('wcp-has-key')) { 
									   		var all_classes = $(this).attr('class');
									   		var splits = all_classes.split("=");
									   		var key = splits[1];
									   		$(this).attr("wcp-cart-key",key);
									   		$(this).removeClass("wcp-data-key="+key);
									    }
									});
								}
								
							});

						
					}
				});
				$(".wcp-cart-loader").fadeOut('slow');
			});

			$(document).on('click','.wcp-modal-content .woocommerce-remove-coupon',function(e){
				var couponcode = $(this).attr('data-coupon');
				//console.log("Hellow"+couponcode);
				$(".wcp-cart-loader").fadeIn('slow');
				$(".wcp-cart-modal-message").removeClass('woocommerce-error');
				$(".wcp-cart-modal-message").removeClass('woocommerce-message');
				$(".wcp-cart-modal-message").html('');
				$(".wcp-cart-modal-message").fadeOut('slow');
				e.preventDefault();
				$.ajax({
					url: "<?php echo site_url();?>/?wc-ajax=wcp_coupon_remove",
					type: 'POST',
					data: {
						couponcode: couponcode,
					},
					success: function(response){
							$.ajax({
								url: "<?php echo site_url();?>/?wc-ajax=wcp_generate_modal_content",
								type: 'POST',
								data: {
									option: 'cart'
								},
								success: function(response){
									$("#wcp_cart").html(response);
									$(".checkout-button").attr("href","javascript:void(0);");
									$(".checkout-button").attr("data-toggle","modal");
									$(".checkout-button").attr("data-target","#checkoutModal");
									$(".checkout-button").attr("data-dismiss","modal");
									$('.wcp-modal-content table tbody tr').each(function(){
									   	if ($(this).hasClass('wcp-has-key')) { 
									   		var all_classes = $(this).attr('class');
									   		var splits = all_classes.split("=");
									   		var key = splits[1];
									   		$(this).attr("wcp-cart-key",key);
									   		$(this).removeClass("wcp-data-key="+key);
									    }
									});
								}
								
							});

						
					}
				});
				$(".wcp-cart-modal-message").removeClass('woocommerce-error');
					$(".wcp-cart-modal-message").addClass('woocommerce-message');
					$(".wcp-cart-modal-message").html('Coupon successfully removed.');
					$(".wcp-cart-modal-message").fadeIn('slow',function(){
						$(".wcp-cart-loader").fadeOut('slow');
				});
			});



			function update_cart(cart_key,new_qty){
				
				$.ajax({
					url: "<?php echo site_url();?>/?wc-ajax=wcp_update_cart",
					type: 'POST',
					data: {
						cart_key: cart_key,
						new_qty: new_qty
					},
					success: function(response){
						if(response.fragments){
							//console.log("Success-"+response);
							var fragments = response.fragments,
								cart_hash =  response.cart_hash;

							//Set fragments
					   		$.each( response.fragments, function( key, value ) {
					   			console.log(response);
								$( key ).replaceWith( value );
								console.log(response);
								$( key ).stop( true ).css( 'opacity', '1' ).unblock();
							});

					   		if(wc_cart_fragments_params){
						   		var cart_hash_key = wc_cart_fragments_params.ajax_url.toString() + '-wc_cart_hash';
								//Set cart hash
								sessionStorage.setItem( wc_cart_fragments_params.fragment_name, JSON.stringify( fragments ) );
								localStorage.setItem( cart_hash_key, cart_hash );
								sessionStorage.setItem( cart_hash_key, cart_hash );
							}
							$.ajax({
								url: "<?php echo site_url();?>/?wc-ajax=wcp_generate_modal_content",
								type: 'POST',
								data: {
									option: 'cart'
								},
								success: function(response){

									$("#wcp_cart").html(response);
									$(".checkout-button").attr("href","javascript:void(0);");
									$(".checkout-button").attr("data-toggle","modal");
									$(".checkout-button").attr("data-target","#checkoutModal");
									$(".checkout-button").addClass("wcp-proceed-to-checkout");
									$(".checkout-button").attr("data-dismiss","modal");
									$('.wcp-modal-content table tbody tr').each(function(){
									   	if ($(this).hasClass('wcp-has-key')) { 
									   		var all_classes = $(this).attr('class');
									   		var splits = all_classes.split("=");
									   		var key = splits[1];
									   		$(this).attr("wcp-cart-key",key);
									   		$(this).removeClass("wcp-data-key="+key);
									    }
									});
								}
								
							});

							$(document.body).trigger('wc_fragments_loaded');
						}
						else{
							console.log(response);
						}

					}
				});
			}
		</script>
		<?php
	}

	public function wcp_update_cart(){
		
		//Form Input Values
		$cart_key = sanitize_text_field($_POST['cart_key']);
		$new_qty = (float) $_POST['new_qty'];

		if(!is_numeric($new_qty) || $new_qty < 0 || !$cart_key){
			wp_send_json(array('error' => __('Something went wrong','woocommerce')));
		}
		

		$cart_success = $new_qty == 0 ? WC()->cart->remove_cart_item($cart_key) : WC()->cart->set_quantity($cart_key,$new_qty);
		
		if($cart_success){
			$this->action = $new_qty == 0 ? 'remove' : 'update';
			WC_AJAX::get_refreshed_fragments();
		}
		else{
			if(wc_notice_count('error') > 0){
	    		echo wc_print_notices();
			}
		}
		die();
	}

	public function wcp_coupon_apply()
	{
		if(isset($_POST['couponcode']) && !$_POST['couponcode'])
			return;
		global $woocommerce; 
		WC()->cart->remove_coupons();
    	$ret = WC()->cart->add_discount( $_POST['couponcode'] ); 
    	if(!$ret){
    		echo _e('Coupon Code not found.');
    	}
    	else{
    		print_r($ret);
    	}
    	die;
	}
	
	public function wcp_coupon_remove()
	{
		if(isset($_POST['couponcode']) && !$_POST['couponcode'])
			return;
		global $woocommerce; 
		$coupon = isset( $_POST['couponcode'] ) ? wc_clean( $_POST['couponcode'] ) : false;
		if ( empty( $coupon ) ) {
	      wc_add_notice( __( 'Sorry there was a problem removing this coupon.', 'woocommerce' ), 'error' );
	    } else {
	      WC()->cart->remove_coupon( $coupon );
	      wc_add_notice( __( 'Coupon has been removed.', 'woocommerce' ) );
	    }

	    wc_print_notices();
	    wp_die();
	}

	public function wcp_add_to_cart_generate_modal_content(){
		echo do_shortcode('[woocommerce_cart]');die;
	}

}new Wcp_Class_Cart;


?>