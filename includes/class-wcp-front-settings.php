<?php
class Class_Wcp_Front_Settings{

	public function __construct(){
		add_action( 'wp_footer', array( $this, 'add_front_css' ) );
		
	}
	
	public function add_front_css(){
		
		$wps_wcp_global_icon_bck_color = (get_option('wps_wcp_global_icon_bck_color')) ? get_option('wps_wcp_global_icon_bck_color') : '#f91504';

		$wps_wcp_global_icon_hover_bck_color = (get_option('wps_wcp_global_icon_hover_bck_color')) ? get_option('wps_wcp_global_icon_hover_bck_color') : '#e82517';


		$wps_wcp_popup_footer_button = (get_option('wps_wcp_popup_footer_button')) ? get_option('wps_wcp_popup_footer_button') : 'enable';

		$wps_wcp_popup_bck_color = (get_option('wps_wcp_popup_bck_color')) ? get_option('wps_wcp_popup_bck_color') : '#fff';

		$wps_wcp_popup_css = (get_option('wps_wcp_popup_css')) ? get_option('wps_wcp_popup_css') : '';

		

		echo '<style type="text/css">'.$wps_wcp_popup_css.'</style>';
		?>

		<style type="text/css">
			.wcp-floating-cart{
				background: <?php echo $wps_wcp_global_icon_bck_color; ?>;
			}
			.wcp-floating-cart:hover{
			    background: <?php echo $wps_wcp_global_icon_hover_bck_color; ?>;
			}
			#emptyCartModal .modal-content, #waitModal .modal-content, .wcp-modal-content, .wcp-modal-content .modal-body table th, .wcp-modal-content .modal-body table tr td {
				background-color: <?php echo $wps_wcp_popup_bck_color; ?>!important;
			}
			.wcp-modal-content table.cart td.product-remove, .wcp-modal-content table.cart td.actions{
				border-top-color: <?php echo $wps_wcp_popup_bck_color; ?>!important;;
			}
		</style>
		
		<?php
	}
}new Class_Wcp_Front_Settings;

?>