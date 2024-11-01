<?php
Class Class_Style_Script{
	public function __construct(){
		add_action('wp_footer',array($this, 'load_stlye'));
		add_action('wp_footer',array($this, 'load_script'));
	}
	public function load_stlye()
	{
		?>
			<style type="text/css">
				.modal-content{
					position: absolute !important;
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
				 		$('#cartModal').modal('show');
				 	}
				});
			</script>
		<?php
	}
	

}new Class_Style_Script;