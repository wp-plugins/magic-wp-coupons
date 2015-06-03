<?php
/*
Plugin Name: Magic WP Coupons - Lite
Plugin URI: http://designsvalley.com
Description: A coupons site plugin, which generally has a shortcode to put coupon type posts where you want.
Author: Shahzad Ahmad Mirza
Author URI: http://shahzadmirza.com
Version: 2.2.1
License: LGPLv2
*/


define("PLUGIN_NAME","DV Coupons");
define("PLUGIN_POST_TYPE","coupons");
define("PLUGIN_URL",__file__);
define("PLUGIN_BASE",dirname(__file__));
define("PLUGIN_DIR",plugin_dir_url(PLUGIN_URL));
define("PLUGIN_JS_DIR",PLUGIN_DIR."js/");
define("PLUGIN_CSS_DIR",PLUGIN_DIR."css/");
define("PLUGIN_MENU_TITLE",'DV Coupon Options');
$themename = PLUGIN_NAME;
$shortname = "dv";
$active_template_url	=	PLUGIN_DIR.'/templates/'.(get_option("dv_coupon_template")!='' ? get_option("dv_coupon_template") : 'Mycoupons').'/';

include("admin/custom_post_types_with_taxonomies.php");
include("lib/wp_utitlities.php");
include("lib/utilities.class.php");
include("admin/meta_box.php");
include("lib/dv_ajax.php");
include("lib/html.php");
include("admin/cloak-manager.php");
include("editors_button/index.php");

include("admin/widgets/latest_coupons.php");
include("admin/widgets/popular_coupons.php");
include("admin/widgets/coupon_stores.php");
include("admin/includes/template_installer.php");

include("admin/admin-panel.php");

$wp_util	=	new	wp_util();
$util		=	new	utilities();
$html		=	new	Html();
include("lib/functions.php");
include("lib/shortcodes.php");

require_once("lib/templating.php");				# Templating single/archive coupon pages

//----------------------------------------------  Construct & Display  Side Bars ------------------------------------------------//
###################################################################################################################################
$wp_util->add_sidebar(array(
		'name'=>'Blog Sidebar',
		/*'id'=>	'right_sidebar',*/
		'description'=>'Sidebar to place widgets on blog page',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="blog_sidebar_widget_title">',
        'after_title' => '</h3>',
));
$wp_util->build_side_bars();
//-------------------------------------------------------------------------------------------------------------------------------//

//------------------------------------------------  Add JS & CSS to head tags ---------------------------------------------------//
###################################################################################################################################
function	add_to_head_tag(){
	global $util;
	echo '<script src="'.PLUGIN_JS_DIR.'script.js" type="text/javascript" charset="utf-8"></script>'."\n";
	echo '<link type="text/css" href="'.PLUGIN_CSS_DIR.'admin_style.css" rel="stylesheet" media="screen" />'."\n";
}
add_action('wp_head', 'add_to_head_tag');
//-------------------------------------------------------------------------------------------------------------------------------//





//---------------------------------------------------  Enqueueing Javascripts ---------------------------------------------------//
###################################################################################################################################
function modify_jquery() {
	if (!is_admin()) {
		// comment out the next two lines to load the local copy of jQuery
		wp_deregister_script('jquery');
		wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js', false, '1.8.1');
		wp_enqueue_script('jquery');
		
		wp_register_script('jqueryClipBoard', PLUGIN_DIR.'js/jquery.zclip.js', false, '1.1.1');
		wp_enqueue_script('jqueryClipBoard');
				
//		wp_register_script('magicWpCouponsCustom', PLUGIN_DIR.'templates/Mycoupons/custom.js', false);
		wp_enqueue_script( 'magicWpCouponsCustom', PLUGIN_DIR.'templates/'.(get_option("dv_coupon_template")!='' ? get_option("dv_coupon_template") : 'Mycoupons').'/custom.js', array(), '1.0.0' );
		
		wp_enqueue_style( 'dv_default_template', PLUGIN_DIR.'templates/'.(get_option("dv_coupon_template")!='' ? get_option("dv_coupon_template") : 'Mycoupons').'/style.css' );
		
//		wp_register_script('myajax', PLUGIN_DIR.'js/dv_coupons.js', false);
//		wp_enqueue_script('myajax');
		
	}
}
add_action('init', 'modify_jquery', 50);
//-------------------------------------------------------------------------------------------------------------------------------//




//------------------------------  Controlling Like/Dislike/etc Appearance Modified by Team Member of DV  -------------------------//
###################################################################################################################################

function check_and_control_options(){
	
	if(!get_option("dv_display_likes")){
		echo '<style type="text/css"> a.like{display:none !important;} </style>';
	}
	if(!get_option("dv_display_dislikes")){
		echo '<style type="text/css"> a.dislike{display:none !important;} </style>';
	}
	if(!get_option("dv_display_clicks")){
		echo '<style type="text/css"> .coupon_views{display:none !important;} </style>';
	}
	
}

add_action("wp_head", "check_and_control_options");

//-------------------------------------------------------------------------------------------------------------------------------//



//------------------------------  Creating New Table in DB when plugin is installed - For Url Cloaking  -------------------------//
###################################################################################################################################


function install_table_for_dvc() {
   	global $wpdb;
  	$rablename = 'dv_cloaked_urls';
 
	// create the ECPT metabox database table
	if($wpdb->get_var("show tables like '$rablename'") != $rablename) 
	{

		$sql = "CREATE TABLE " . $rablename . " (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`cloaked_url` varchar (255) NULL,
		`actaul_url` longtext NULL,
		`hits` varchar (7) NULL,
		UNIQUE KEY id (id)
		);";
 
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	 
}

register_activation_hook(__FILE__ , 'install_table_for_dvc');

//-------------------------------------------------------------------------------------------------------------------------------//

?>