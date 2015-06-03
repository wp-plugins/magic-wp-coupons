<?php	
	
	function remove_default_template_files(){
		
		echo '<script type="text/javascript">';
			echo	'jQuery(document).ready(function(){
				
						jQuery("#dv_default_template-css").remove();
						jQuery("#dv_default_template-css").remove();
				
					});';
		echo '</script>';
		
	}
	
	function show_coupons($atts){
		
		extract(shortcode_atts(array(
			'store'		=> '',
			'limit'		=> 10,
			'orderby'	=> 'post_date',
			'order'		=> 'DESC'
		), $atts, 'coupons'));
				
		if($limit==''){
			$limit	=	10;
		}
		
		wp_reset_query();
		wp_reset_postdata();
		
		$paged_var		=	(is_front_page() ? 'page' : 'paged');
		
		$myvar	=	'';
		$args = array(
			'posts_per_page' => $limit,
			'post_type' => 'coupons',
			'stores' => $store,
			'post_status' => 'publish',
			'orderby'	=> $orderby,
			'order'		=> $order,
			'paged' => ( get_query_var( $paged_var ) ) ? get_query_var( $paged_var ) : 1
		);
		
		$coupons		=	new WP_Query( $args );
		
	//	var_dump($coupons->query_vars);
		
		if( $coupons->have_posts() ){
			
			while( $coupons->have_posts() ){ $coupons->the_post();
				$myvar	.=	display_coupons(get_the_ID(), $template);
			}
			
		}else{
			$myvar		.=	'Sorry, no coupons found !';
		}
				
		$big = 999999999; // need an unlikely integer
		$myvar	.=	'<div class="dv_pagination">';
		$myvar	.=	paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var($paged_var) ),
			'total' => $coupons->max_num_pages
		) );
		
		$myvar	.=	'</div>';
		wp_reset_postdata();
		wp_reset_query();
		return $myvar;
		
	}
	add_shortcode('coupons', 'show_coupons');
	
	
	function get_cloaked_url_by_actual($actual){
		
		global $wpdb;
		$result		=	$wpdb->get_row("SELECT * FROM `dv_cloaked_urls` WHERE `actaul_url` = '$actual' ");	
		
		if(!empty($result)){
			
			$cloaked_url		=	$result->cloaked_url;
			
			return $cloaked_url;
		}else{
			return '';
		}
		
	}
	
	
	function	display_coupons($post, $template	=	''){
		
		if(is_numeric($post)){
			$post	=	get_post($post);
		}
		
		$curr_views		=	(int)(get_post_meta($post->ID, 'views', true) ? get_post_meta($post->ID, 'views', true) : 0);
		$new_views		=	$curr_views	+ 1;
		
		update_post_meta($post->ID, 'views', $new_views);
				
		$file	=	file_get_contents(PLUGIN_BASE."/templates/". (get_option("dv_coupon_template")!='' ? get_option("dv_coupon_template") : 'Mycoupons') ."/coupon_snippet.html");
	
		$text	=	$post->post_content;
		
		$post_meta		=	get_post_meta($post->ID);
		$likes			=	$post_meta['likes'][0];
		$dislikes		=	$post_meta['dislikes'][0];
		$clicks			=	$post_meta['clicks'][0];
		$verify_class	=	(get_post_meta($post->ID, 'is_verified', true)=='on' ? ' verified ' : '');
		
		$store_data 	=	wp_get_post_terms( $post->ID, 'stores' );
		
		$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
		if(get_option("dv_use_timthumb")=='true'){
			$feat_image	=	PLUGIN_DIR."timthumb.php?h=120&w=120&src=".$feat_image;
		}
		
		$store_url	=	$post_meta['coupon_store_url'][0];
		
		/*
		 * Checking if date has passed or not !
		 */
		if(strcspn($post_meta['coupon_expiry_date'][0], '0123456789') != strlen($post_meta['coupon_expiry_date'][0])){

				list($y,$m,$d)=explode("-",$post_meta['coupon_expiry_date'][0]);
				$date=mktime(0,0,0,$m,$d,$y);
				
				$today=mktime(0,0,0,date("m"),date("d"),date("Y"));
				
				if($date<$today)
				{
					$expiry_msg		=	$post_meta['coupon_expiry_date'][0].' <span class="red">(Expired)</span>';
				}else{
					$expiry_msg		=	$post_meta['coupon_expiry_date'][0];
				}

		}else{
		
					$expiry_msg		=	$post_meta['coupon_expiry_date'][0];
			
		}

		
		$tags	=	array(	
							"coupon_permalink"		=>	get_permalink($post->ID),
							"coupon_code"			=>	$post_meta['coupon_code'][0],
							"coupon_discount"		=>	$post_meta['coupon_discount'][0],
							"coupon_title"			=>	$post->post_title,
							"expiry_date"			=>	$expiry_msg,
							"coupon_text_contents"	=>	$post->post_excerpt,
							"post_id"				=>	$post->ID,
							"featured_image" 		=>  $feat_image,
							"store_url"				=>	(get_option("dv_use_cloaked_url")=='true' ? get_cloaked_url_by_actual($store_url) : $store_url),
							"coupon_views"			=>	(get_option('dv_display_clicks')=='true' ? (empty($clicks))?'0 Clicks':$clicks.' Clicks': ''),
							"likes"					=>	(get_option('dv_display_likes')=='true' ? (empty($likes))?'0':$likes: ''),
							"dislikes"				=>	(get_option('dv_display_dislikes')=='true' ? (empty($dislikes))?'0':$dislikes: ''),
							"class_dislike"			=>	(get_option('dv_display_dislikes')=='true' ? '' : 'hide'),
							"class_like"			=>	(get_option('dv_display_likes')=='true' ? '' : 'hide'),
							"class_clicks"			=>	(get_option('dv_display_clicks')=='true' ? '' : 'hide'),
							"more_from_this_store"	=>	site_url().'?stores='.$store_data->slug,
							"store_name"			=>	$store_data->name,
							"verify_class"			=>	$verify_class
						);
		
		return parse_template($file, $tags);
	}
	
	
	function	show_coupon_stores($atts){
				
			$display_img	=	$atts['img'];
			unset($atts['img']);
			
			$output		=	'<div class="store_coupons dv_wrapper">';
				$output		.=	'<ul>';
					
					$stores = get_terms("stores", $atts);
					
					foreach ( $stores as $store ) :  
                    
						$output		.=	'<li>';
							$output		.=	'<a href="'.get_term_link($store, "stores").'">'.$store->name.' ( '.$store->count.' )</a>';
						$output		.=	'</li>';
					
					endforeach; 					
					
				$output		.=	'</ul>';
		 		
			$output		.=	'</div>';
	
			return $output;			
				
	}
	
	add_shortcode('coupon_stores', 'show_coupon_stores');
	
	
	
?>