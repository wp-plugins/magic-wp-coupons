<?php
	
add_action( 'init', 'create_post_type' );
function create_post_type() {
	
	$labels = array(
		'name' => _x('Coupons', 'post type general name', 'your_text_domain'),
		'singular_name' => _x('Coupon', 'post type singular name', 'your_text_domain'),
		'add_new' => _x('Add New', 'Coupon', 'your_text_domain'),
		'add_new_item' => __('Add New Coupon', 'your_text_domain'),
		'edit_item' => __('Edit Coupon', 'your_text_domain'),
		'new_item' => __('New Coupon', 'your_text_domain'),
		'all_items' => __('All Coupons', 'your_text_domain'),
		'view_item' => __('View Coupons', 'your_text_domain'),
		'search_items' => __('Search Members', 'your_text_domain'),
		'not_found' =>  __('No Coupons found', 'your_text_domain'),
		'not_found_in_trash' => __('No Coupons found in Trash', 'your_text_domain'), 
		'parent_item_colon' => '',
		'menu_name' => __('Coupons', 'your_text_domain')
	
	  );
	
	
	register_post_type( 'coupons',
		array(
			'labels' => $labels,
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'coupon'),
			'supports' => array( 'title', /*'editor',*/ 'thumbnail', 'excerpt', 'comments','custom-fields' )
		)
	);
}


function coupon_taxonomy() {  
   register_taxonomy(  
    'stores',  
    'coupons',  
    array(  
        'hierarchical' => true,  
        'label' => 'Coupon Stores',  
        'query_var' => true,  
        'rewrite' => array('slug' => 'stores')  
    )  
);  
}  
add_action( 'init', 'coupon_taxonomy' ); 
 ?>