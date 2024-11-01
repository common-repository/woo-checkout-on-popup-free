<?php
Class Class_Wcp_Style_Script{
	public function __construct(){
		add_action('wp_footer',array($this, 'load_stlye'));
		add_action('wp_footer',array($this, 'load_script'));
		
	}
	
	public function load_stlye()
	{
		global $woocommerce;
		$wps_wcp_global_icon = (get_option('wps_wcp_global_icon')) ? get_option('wps_wcp_global_icon') : 'enable';
		if( $wps_wcp_global_icon == 'enable' )
		{
			if(!is_checkout() && !is_cart() && !is_page( 'cart' ) && !is_page( 'checkout' ) )
			{

			?>
			    <div <?php if($woocommerce->cart->cart_contents_count != 0){echo 'id="wcp-mini-cart-item" data-toggle="" data-target=""'; }else{ echo 'id="wcp-mini-cart" data-toggle="modal" data-target="#emptyCartModal"';}?> class="wcp-floating-cart">
			        <p class="icon"><img src="<?php echo WCP__IMG.'mini_cart.png' ?>"></p>
			    </div>
	    <?php
			}
		}
		$wps_wcp_global_icon_visible =  'woocommerce';

		if( $wps_wcp_global_icon_visible == 'woocommerce' && !is_woocommerce()){
			echo "<style>#wcp-mini-cart-item{display:none !important;}</style>";
		}
	?>

	    
		<style type="text/css">
			.modal-content{
				position: absolute !important;
			}
			.wcp_checkout{
				display: none;
			}
			.wcp-modal-content .shop_table .actions button{
				display: none;
			}
			#cartModal .wcp-cart-modal-message{
				margin-bottom: 2px;
				margin-top: 2px;
			}
			.wcp-modal-checkout header,.wcp-modal-checkout .woocommerce-breadcrumb, .wcp-modal-checkout footer{
				display: none;
			}

		</style>
		<?php
	}

	public function load_script()
	{
		?>
			<script type="text/javascript">
				var $ =jQuery.noConflict();
				$( document ).ajaxComplete(function(event, XMLHttpRequest, ajaxOptions) {
				 	var actual_url = ajaxOptions.url
				 	var url_arr = actual_url.split("?");
				 	if(url_arr[1] == 'wc-ajax=add_to_cart')
					{
				 		
				 		$(".added_to_cart").attr("href","javascript:void(0);");
						$(".added_to_cart").attr("data-toggle","modal");
						$(".added_to_cart").attr("data-target","#cartModal");

				 		$("#wcp_loading_header_message").html('<span class="wcp-load-header">Your cart is preparing...</span>');

				 		$("#wcp_loading_footer_message").html('<span class="wcp-load-footer">Please wait for a while</span>');
				 		$('#waitModal').modal('show');
				 		$(".wcp-cart-modal-message").removeClass('woocommerce-error');
						$(".wcp-cart-modal-message").removeClass('woocommerce-message');
						$(".wcp-cart-modal-message").html('');
						$(".wcp-cart-modal-message").fadeOut('slow',function(){
							$(".wcp-cart-modal-message").addClass('woocommerce-message');
							$(".wcp-cart-modal-message").html('Product successfully added to cart.');
							$(".wcp-cart-modal-message").fadeIn('slow');
						});

						$(".wcp-floating-cart").attr("id","wcp-mini-cart-item");
						$(".wcp-floating-cart").attr("data-toggle","");
						$(".wcp-floating-cart").attr("data-target","");

				 		$.ajax({
							url: "<?php echo site_url();?>/?wc-ajax=wcp_generate_modal_content",
							type: 'POST',
							data: {
								option: 'cart'
							},
							success: function(response){
								$("#wcp_cart").html(response);

								$(".checkout-button").attr("href","javascript:void(0);");
								$(".checkout-button").attr("data-dismiss","modal");
								$(".checkout-button").addClass("wcp-proceed-to-checkout");
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

						setTimeout(function(){
							$('#waitModal').modal('hide');
							$('#cartModal').modal('show');
						}, 2000);
					}
						
				});
			</script>
		
			<script type="text/javascript">
				var $ =jQuery.noConflict();
				$(document).on('submit','form.cart',function(e){
					e.preventDefault();
					var form = $(this);
					var wcp_submit_btn  = form.find( 'button[type="submit"]');
					$("#wcp_loading_header_message").html('<span class="wcp-load-header">Your cart is preparing...</span>');
					
				 	$("#wcp_loading_footer_message").html('<span class="wcp-load-footer">Please wait for a while</span>');
				 	$('#waitModal').modal('show');

				 	$(".wcp-floating-cart").attr("id","wcp-mini-cart-item");
					$(".wcp-floating-cart").attr("data-toggle","");
					$(".wcp-floating-cart").attr("data-target","");

					var form_data = form.serializeArray();

					// if button as name add-to-cart get it and add to form
			        if( wcp_submit_btn.attr('name') && wcp_submit_btn.attr('name') == 'add-to-cart' && wcp_submit_btn.attr('value') ){
			            form_data.push({ name: 'add-to-cart', value: wcp_submit_btn.attr('value') });
			        }

			        form_data.push({name: 'action', value: 'wcp_add_to_cart_generate_modal_content'});

					//$( document.body ).trigger( 'adding_to_cart', [ wcp_submit_btn, form_data ] );

					$(".wcp-cart-modal-message").removeClass('woocommerce-error');
					$(".wcp-cart-modal-message").removeClass('woocommerce-message');
					$(".wcp-cart-modal-message").html('');
					$(".wcp-cart-modal-message").fadeOut('slow',function(){
						$(".wcp-cart-modal-message").addClass('woocommerce-message');
						$(".wcp-cart-modal-message").html('Product successfully added to cart.');
						$(".wcp-cart-modal-message").fadeIn('slow');
					});

					$.ajax({
						url: "<?php echo site_url();?>/?wc-ajax=wcp_add_to_cart_generate_modal_content",
						type: 'POST',
						data: $.param(form_data),
					    success: function(response){
					    	//$( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, atc_btn ] );
					    	$("#wcp_cart").html(response);
					    	$("#wcp_cart .woocommerce .woocommerce-message").hide();
							$(".checkout-button").attr("href","javascript:void(0);");
							// $(".checkout-button").attr("data-toggle","modal");
							// $(".checkout-button").attr("data-target","#checkoutModal");
							$(".checkout-button").attr("data-dismiss","modal");
							$(".checkout-button").addClass("wcp-proceed-to-checkout");
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
					setTimeout(function(){
						$('#waitModal').modal('hide');
						$('#cartModal').modal('show');
					}, 2000);
				});



				$(document).on('click',"#cartModal .wcp-proceed-to-checkout", function(e){

					$("#wcp_loading_header_message").html('<span class="wcp-load-header">Your checkout is preparing...</span>');
				 	$("#wcp_loading_footer_message").html('<span class="wcp-load-footer">Please wait for a while</span>');
				 	$('#waitModal').modal('show');

				 	$(".wcp-checkout-modal-message").removeClass('woocommerce-error');
					$(".wcp-checkout-modal-message").removeClass('woocommerce-message');
					$(".wcp-checkout-modal-message").html('');
					$(".wcp-checkout-modal-message").fadeOut('slow');
				 	
			 		$.ajax({
						url: "<?php echo site_url();?>/?wc-ajax=wcp_generate_modal_content",
						type: 'POST',
						data: {
							option: 'checkout'
						},
						success: function(response){
							$("#wcp_checkout").html(response);
						}
						
					});

					setTimeout(function(){
						$('#waitModal').modal('hide');
						$('#checkoutModal').modal('show');
					}, 2000);
				});

				$(document).on('click',".wcp-back-to-cart", function(e){

					$("#wcp_loading_header_message").html('<span class="wcp-load-header">Your cart is preparing...</span>');
				 		$("#wcp_loading_footer_message").html('<span class="wcp-load-footer">Please wait for a while</span>');
				 		$('#waitModal').modal('show');
				 		$(".wcp-cart-modal-message").removeClass('woocommerce-error');
						$(".wcp-cart-modal-message").removeClass('woocommerce-message');
						$(".wcp-cart-modal-message").html('');
						
				 		$.ajax({
							url: "<?php echo site_url();?>/?wc-ajax=wcp_generate_modal_content",
							type: 'POST',
							data: {
								option: 'cart'
							},
							success: function(response){
								$("#wcp_cart").html(response);

								$(".checkout-button").attr("href","javascript:void(0);");
								$(".checkout-button").attr("data-dismiss","modal");
								$(".checkout-button").addClass("wcp-proceed-to-checkout");
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

						setTimeout(function(){
							$('#waitModal').modal('hide');
							$('#cartModal').modal('show');
						}, 2000);

				});


				$(document).on('click','#wcp-mini-cart-item',function(e){
					$("#wcp_loading_header_message").html('<span class="wcp-load-header">Your cart is preparing...</span>');

				 		$("#wcp_loading_footer_message").html('<span class="wcp-load-footer">Please wait for a while</span>');
				 		$('#waitModal').modal('show');
				 		$(".wcp-cart-modal-message").removeClass('woocommerce-error');
						$(".wcp-cart-modal-message").removeClass('woocommerce-message');
						$(".wcp-cart-modal-message").html('');
				 		$.ajax({
							url: "<?php echo site_url();?>/?wc-ajax=wcp_generate_modal_content",
							type: 'POST',
							data: {
								option: 'cart'
							},
							success: function(response){
								$("#wcp_cart").html(response);

								$(".checkout-button").attr("href","javascript:void(0);");
								$(".checkout-button").attr("data-dismiss","modal");
								$(".checkout-button").addClass("wcp-proceed-to-checkout");
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

						setTimeout(function(){
							$('#waitModal').modal('hide');
							$('#cartModal').modal('show');
						}, 2000);
				});
			</script>
		<?php
	}
	

}new Class_Wcp_Style_Script;