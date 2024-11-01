<?php
/*
Plugin Name: w2pe Beaver Slider
Description: w2pe Beaver Slider perhaps the best image slider for Wordpress based on jQuery. The image gallery for your web site.
Version: 1.0.1
Plugin URI: http://www.webworksbd.com
Author: WebworksBD
Author URI: http://www.webworksbd.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


// general info
if ( function_exists('plugins_url') ){
	$url = plugins_url(plugin_basename(dirname(__FILE__)));
}
else{
	$url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));
}

$plugindir = ABSPATH.'wp-content/plugins/w2pe-beaverslider/';


// create db table 
function w2pe_bslider_table(){
	
	global $wpdb;

	$image_table = $wpdb->prefix . 'bslider_images';
    
	$sql = "CREATE TABLE IF NOT EXISTS `" . $image_table . "` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `caption` VARCHAR(250) DEFAULT NULL,
	  `ftype` VARCHAR(5) NOT NULL,
	  PRIMARY KEY (`id`)
      );";

   
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
	
	$col = mysql_query("SELECT count(*) as row FROM information_schema.columns WHERE TABLE_SCHEMA=DATABASE() AND table_name = '$image_table'");
	$res = mysql_fetch_object($col);
	if($res->row==3){
		mysql_query("ALTER TABLE `".$image_table."` ADD `position` TINYINT NOT NULL DEFAULT '1' AFTER `ftype`");
	}
	
	// common settings
	add_option(' bslider_width', 720);
	add_option(' bslider_height', 420);
	add_option(' bslider_caption_visible', TRUE);
	add_option(' bslider_caption_left', '');
	add_option(' bslider_caption_right', 15);
	add_option(' bslider_caption_top', '');
	add_option(' bslider_caption_bottom', 15);
	add_option(' bslider_paginate', TRUE);
	add_option(' bslider_pause_time', 2000);
	add_option(' bslider_interval', 2000);
	add_option(' bslider_supdate', 0);
	add_option(' bslider_effects', 'effects:"chessBoardRightDown:800:10:10"');
}

register_activation_hook(__FILE__, 'w2pe_bslider_table');


// admin notice
function w2pe_bslider_admin_notice() {
	$cstatus = get_option('bslider_supdate');
	if(empty($cstatus)){
		printf(__('<div class="updated error" id="msg"><p>Please update w2pe Beaver Slider settings before upload images. To update settings <a href="%1$s">click here</a></p></div>'), '?page=w2pe_bslider_menu');
	}
}
add_action( 'admin_notices', 'w2pe_bslider_admin_notice' );


// admin menu for w2pe_bslider
add_action('admin_menu', 'w2pe_bslider_menu');

function w2pe_bslider_menu(){	
	
	add_menu_page( 'w2pe Beaver Slider','w2pe Beaver Slider', 'add_users', 'w2pe_bslider_menu', 'w2pe_bslider_setting',get_option('siteurl').'/wp-content/plugins/w2pe-beaverslider/images/menu.png');
	
	add_submenu_page( 'w2pe_bslider_menu', __('Images - Beaver Slider'), 'Images', 'add_users', 'w2pe_bslider_images', 'w2pe_bslider_images' );
	
	add_submenu_page( 'w2pe_bslider_menu', __('Effects - Beaver Slider'), 'Effects', 'add_users', 'w2pe_bslider_effect', 'w2pe_bslider_effect' );
	
	add_submenu_page( 'w2pe_bslider_menu', __('Support - Beaver Slider'), 'Support', 'add_users', 'w2pe_bslider_support', 'w2pe_bslider_support' );
	
	// make upload dir
	$upload_dir = ABSPATH."wp-content/uploads/w2pe-beaverslider";
	if (!file_exists($upload_dir)) {
		umask(0); 
		mkdir($upload_dir, 0777, true) or die("error creating the folder" . $upload_dir . "check folder permissions");
	}
	
	
}

function w2pe_bslider_setting(){
	require_once 'bslider_setting.php';
}

function w2pe_bslider_images(){
	require_once 'bslider_images.php';
}

function w2pe_bslider_effect(){
	require_once 'bslider_effect.php';
}

function w2pe_bslider(){
	require_once 'slider.php';
}

function w2pe_bslider_support(){
	require_once 'support.php';
}

//short code
add_shortcode( 'w2pe_bSlider', 'w2pe_bslider' );


// ajax
add_action( 'wp_ajax_bslider_effect', 'bslider_effect_callback' );

function bslider_effect_callback() {
	global $wpdb;

	
	parse_str($_POST['sdata'], $bslider_data);
	
	$effects = 'effects:"';
	for($a=0; $a < count($bslider_data['effect']);$a++){
		$duration = empty($bslider_data['duration'][$a]) ? 800 : $bslider_data['duration'][$a];
		$size = empty($bslider_data['size'][$a]) ? 10 : $bslider_data['size'][$a];
		$steps = empty($bslider_data['steps'][$a]) ? 10 : $bslider_data['steps'][$a];
		
		$effects.= $bslider_data['effect'][$a].':';
		$effects.= $duration.':';
		$effects.= $size.':';
		$effects.= $steps;
	}
	$effects.= '"';
	
	$all_effects = get_option( 'bslider_effects' );
	if(empty($all_effects)){
		add_option(' bslider_effects', $effects);
		echo 'added';
		
	}else{
		update_option(' bslider_effects', $effects);
		echo 'updated';
	}
	
	die();
}


// uninstall plugin
if ( function_exists('register_uninstall_hook') )
	register_uninstall_hook(__FILE__, 'uninstall_bslider');

function uninstall_bslider() {

	global $wpdb;
	$image_table = $wpdb->prefix . 'bslider_images';
	
	$wpdb->query( "DROP TABLE IF EXISTS $image_table" );
	
	// common settings
	delete_option(' bslider_width');
	delete_option(' bslider_height');
	delete_option(' bslider_caption_visible');
	delete_option(' bslider_caption_left');
	delete_option(' bslider_caption_right');
	delete_option(' bslider_caption_top');
	delete_option(' bslider_caption_bottom');
	delete_option(' bslider_paginate');
	delete_option(' bslider_pause_time');
	delete_option(' bslider_interval');
	delete_option(' bslider_supdate');
	delete_option(' bslider_effects');
	
}




//add script
if (!is_admin()) {
	//load script on frontend
	wp_register_style( 'w2pe_bslider_css', $url.'/css/bslider_css.css');
	wp_enqueue_style( 'w2pe_bslider_css');
	wp_register_script('jquery', 'http://code.jquery.com/jquery-1.10.2.min.js', false, '1.10.2');
	wp_enqueue_script('jquery');
	wp_register_script('w2pe_beaverslider', 'http://beaverslider.com/code/current/beaverslider.js', false, '1.00');
	wp_enqueue_script('w2pe_beaverslider');
	wp_register_script('w2pe_beaverslider_effect', 'http://beaverslider.com/code/current/beaverslider-effects.js', false, '1.00');
	wp_enqueue_script('w2pe_beaverslider-effects.js');
	
}else{
	//load script on backend
	wp_register_style( 'w2pe_bslider_admin_css', $url.'/css/admin_css.css');
	wp_enqueue_style( 'w2pe_bslider_admin_css');
}
