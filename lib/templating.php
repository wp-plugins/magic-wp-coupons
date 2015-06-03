<?php
//------------------------------  Overriding Single/Store WP Defailt template Pages  -------------------------//
###################################################################################################################################

add_filter( 'template_include', 'override_coupon_page_template', 99 );

function override_coupon_page_template( $template ) {

		if( is_singular( 'coupons' ) ){
			$new_template = PLUGIN_BASE . '/single-coupon.php' ;
			return $new_template;
		}elseif( is_tax( 'stores') ){
			$new_template = PLUGIN_BASE . '/taxonomy-stores.php' ;
			return $new_template;
		}else{
			return $template;
		}
		
}	
//-------------------------------------------------------------------------------------------------------------------------------//


?>