<?php
# Plugin is loaded before pluggable.php So this is a hack to use WP functions.
if ( !function_exists('wp_get_current_user') ) {
	require_once (ABSPATH . WPINC . '/pluggable.php');
}
	
	global $current_user;
	get_currentuserinfo();
	
	$dv_general_options = array();	
	
	$dv_general_options[] = array(	"name" => "General Settings",
									"icon" => "home",
									"type" => "page");

if( get_option('dv_subscribed')!='yes' ){

	$dv_general_options[] = array(	"name" => "SUBSCRIBE TO OUR MONTHLY NEWSLETTER",
									"type" => "heading");
	
	
	$dv_general_options[] = array(	"name" => "Name",
									"desc" => "Enter your name here to subscribe to our monthly newsletter.",
									"id" => $shortname."_subs_name",
									"std" => $current_user->user_firstname.' '.$current_user->user_lastname,
									"type" => "text");	

	$dv_general_options[] = array(	"name" => "Email",
									"desc" => "Enter your email here to subscribe to our monthly newsletter.",
									"id" => $shortname."_subs_email",
									"std" => $current_user->user_email,
									"type" => "text");	

	$dv_general_options[] = array(	"name" => "Subscribe",
									"desc" => "Subscribe to our montly newsletter.",
									"el_ids" => 'subscribe_bt',
									"onclick" => "subscribe_to_nl()",
									"type" => "button");

	$dv_general_options[] = array(	"type" => "heading_end");


}


	$dv_general_options[] = array(	"name" => "General Settings",
									"type" => "heading");
	
	$dv_general_options[] = array(	"name" => "Display Likes",
									"desc" => "Check this if you want to display likes count for coupons.",
									"id" => $shortname."_display_likes",
									"std" => "",
									"type" => "checkbox");	

	$dv_general_options[] = array(	"name" => "Display Dislikes",
									"desc" => "Check this if you want to display dislikes count for coupons.",
									"id" => $shortname."_display_dislikes",
									"std" => "",
									"type" => "checkbox");

	$dv_general_options[] = array(	"name" => "Display Clicks",
									"desc" => "Check this if you want to display total clicks on coupon codes.",
									"id" => $shortname."_display_clicks",
									"std" => "",
									"type" => "checkbox");	

	$dv_general_options[] = array(	"name" => "Use Timthumb Library",
									"desc" => "This will resize your coupon images using timthumb library.",
									"id" => $shortname."_use_timthumb",
									"std" => "",
									"type" => "checkbox");

	$dv_general_options[] = array(	"name" => "Use Theme's CSS",
									"desc" => "The coupon snippet will use style sheets added to current theme.",
									"id" => $shortname."_use_theme_css",
									"std" => "",
									"type" => "checkbox");


	$dv_general_options[] = array(	"name" => "Use Cloaked Url",
									"desc" => "",
									"id" => $shortname."_use_cloaked_url",
									"std" => "",
									"type" => "checkbox");



	$dv_general_options[] = array(	"type" => "heading_end");
	
	$dv_general_options[] = array(	"type" => "page_end");	


	$dv_general_options[] = array(	"name" => "Templates Settings",
									"type" => "page",
									"icon" => "templates");

	$dv_general_options[] = array(	"name" => "Templates Settings",
									"type" => "heading");

if ($handle = opendir(PLUGIN_BASE.'/templates')) {
	while (false !== ($entry = readdir($handle))) {
		if ($entry != "." && $entry != "..") {
			if(strpos($entry,'.') == false) {
				$rr[]	= "$entry";
			}
		}
	}
	closedir($handle);
}


	$dv_general_options[] = array(	"name" => "Select Template",
									"desc" => "Select a template from bellow",
									"id" => $shortname."_coupon_template",
									"std" => '',
									"options" => $rr,
									"type" => "select");



	$dv_general_options[] = array(	"type" => "heading_end");
	
	
	
	
	
	$dv_general_options[] = array(	"name" => "Install New Template",
									"type" => "heading");

	$dv_general_options[] = array(	"name" => "Upload Template (.zip file)",
									"desc" => "Upload zip file of your template here.",
									"id" => $shortname."_upload_template",
									"std" => '',
									"no_up" => TRUE,
									"type" => "zip");


	$dv_general_options[] = array(	"type" => "heading_end");
	
	
	
	
	
	
	$dv_general_options[] = array(	"type" => "page_end");


/*------------------------------- New Work --------------------------------------------*/

	$dv_general_options[] = array(	"name" => "Get More Templates",
									"icon" => "more",
									"type" => "page");

	$dv_general_options[] = array(	"name" => "Get More Templates",
									"type" => "heading");

$feed	= file_get_contents('http://magic-wp-coupons.designsvalley.com/product-category/templates/feed/');
$feed	= str_replace('<media:', '<', $feed);
$rss	= simplexml_load_string($feed);

$i		=	0;
$titles	=	array();
foreach($rss->channel->item as $item){
	
	$template_img	=	(string)$item->description;
	
	preg_match_all('/<img[^>]+>/i',$template_img, $result); 
		
	
	foreach( $result as $image){			

		preg_match('@<img.+src="(.*)".*>@Uims',$image[0], $img_tag);
		$images[$i]	=	$img_tag[1];
	}
	
	$titles[$i]		=	(string) $item->title;
	$links[$i]		=	(string) $item->link;
	
	$i++;
}
$z	=	0;
$content	=	'';
foreach($titles as $ss){
		
	$e_title	=	$titles[$z];
	$image		=	$images[$z];
	$link		=	$links[$z];
	
	$content	.=	'<div class="dv_tab_temp"><a href="'.$link.'" target="_blank" class="dv_temp_img_a"><img src="'.$image.'" width="200" /></a><a href="'.$link.'" target="_blank"><h3>'.$e_title.'</h3></a></div>';

	$z++;
}

if(count($titles)==0){
	$content	=	'<p>Sorry, there are no templates.</p>';
}

	$dv_general_options[] = array(	"name" => "Text",
									"content" => $content,
									"type" => "htmlcontent");	

	$dv_general_options[] = array(	"type" => "heading_end");
	
	$dv_general_options[] = array(	"type" => "page_end");	


	$dv_general_options[] = array(	"name" => "Buy Premium Version",
									"icon" => "buy",
									"type" => "page");

	$dv_general_options[] = array(	"name" => "Buy Premium Version",
									"type" => "heading");
	
	$dv_general_options[] = array(	"name" => "Text",
									"content" => "<p>We are glad to announce that our premium version of <a href=\"http://magic-wp-coupons.designsvalley.com/\" target=\"_blank\">Magic WP Coupons</a>, is ready and its state of the art plugin which will allow you to create coupon sites with few clicks and generate revenue from affiliate links. Magic WP Coupons premium version is going to do real magic and its set and forget type software which automatically pulls new offers and coupon codes from Prosperent API and deliver fresh offers and coupon codes to users of your site. Here are few features listed below.</p>									

	<h2>Magic WP Coupons Features</h2>
	
	<ul class=\"ul-disc\">
		<li>User friendly admin control panel</li>
		<li>SEO compliant.</li>
		<li>5 different coupon snippet design templates</li>
		<li>Social sharing feature built right into coupon snippet boxes</li>
		<li>Shortcode ready feature allows you to insert shortcode on pages or posts where you want to populate coupons from certain stores.</li>
		<li>Cloaked URLs to save your affiliate links as well as your revenue</li>
		<li>Like/Dislike feature, enabling users to thumb up and down any coupon displayed.</li>
		<li>Coupon click counter, counts each time when user click to use that coupon.</li>
		<li>Coupon teaser, it will only allow people to see coupon codes when they click on teaser, this ensures that, your affiliate link will open automatically with teaser clicks.</li>
		<li>Compatible with 99% available wordpress templates on wordpress template directory and third party templates too.</li>
		<li>Prosperent API integration allow you to use your Prosperent account to pull fresh coupons and offers with couple of clicks and publish those coupons to your site.</li>
		<li>Automatically pull coupon codes and new offers from Prosperent API.</li>
		<li>Detailed reporting feature allows you to analyze and optimize your coupons</li>
	</ul>
	
	<a title=\"Premium Plugin Demo\" href=\"http://snatchbackbucks.com/\" target=\"_blank\"><img class=\"alignleft\" src=\"http://dvcoupons.designsvalley.com/wp-content/themes/magic-wp-coupons/assets/images/live_demo_bt.png\" alt=\"\"></a>
	
	<a href=\"http://magic-wp-coupons.designsvalley.com/product/magic-wp-coupons-premium-version/\"><img class=\"alignright\" src=\"http://dvcoupons.designsvalley.com/wp-content/themes/magic-wp-coupons/assets/images/bt_back_03.png\" alt=\"\"></a>
	
	<span class=\"clear\"></span>
	
	",
									"type" => "htmlcontent");	

	$dv_general_options[] = array(	"type" => "heading_end");
	
	$dv_general_options[] = array(	"type" => "page_end");	



?>