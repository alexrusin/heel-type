<?php
/*
Plugin Name: Heel Post
Plugin URI: http://alexrusin.com/
Description: This plugin allows to create heel post
Version: 1.0.0
Author: Alex Rusin
Author URI: http://alexrusin.com
License: GPLv2
*/
//Exit if accessed directly
if(!defined('ABSPATH')){
	exit;
}
//flush rewrite rules
function ht_activate(){
 	flush_rewrite_rules();
 }
register_activation_hook( __FILE__ , 'ht_activate');

require_once (plugin_dir_path(__FILE__).'inc/classCustomPost.php');
require_once (plugin_dir_path(__FILE__).'inc/classCustomTaxonomy.php');
require_once (plugin_dir_path(__FILE__).'inc/classCustomMetabox.php');
require_once (plugin_dir_path(__FILE__).'inc/classCustomDisplay.php');
require_once (plugin_dir_path(__FILE__).'inc/classSubMenuPage.php');

function ht_front_end_enqueue_scripts(){
	
	wp_enqueue_style( 'ht-front-style', plugins_url( 'css/front-style.css', __FILE__ ) );
}

add_action('wp_enqueue_scripts','ht_front_end_enqueue_scripts' );


function ht_admin_enqueue_scripts(){
	global $typenow, $pagenow;
	if ( $typenow == 'heel') {
		wp_enqueue_style( 'ht-admin-css', plugins_url( 'css/admin-style.css', __FILE__ ) );
	}
	wp_enqueue_media();
	wp_enqueue_script( 'ht-img-upload', plugin_dir_url( __FILE__ ) . 'js/image-upload.js', array('jquery'), '0.0.2', true );

	if($pagenow =='edit.php' && $typenow =='heel' ){
		wp_enqueue_script( 'ht-heel-reorder', plugin_dir_url( __FILE__ ) . 'js/reorder.js', array('jquery', 'jquery-ui-sortable'), '0.0.1', true );
		wp_localize_script('ht-heel-reorder', 'HEEL_REORDER', array(
			'security' => wp_create_nonce('reorder-heel-types'),

			));

	}
}

add_action('admin_enqueue_scripts', 'ht_admin_enqueue_scripts');


$ht_post = new CustomPost('Heel', 'Heels', array('thumbnail'));
$ht_custom_tax = new CustomTaxonomy('Heel Type', 'Heel Types', $ht_post->get_slug());
$ht_heel_description = new CustomMetabox('heel_description', 'Heel Description');
$ht_custom_tax->add_upload_img();

$ht_heel_type_list = new CustomDisplay($ht_custom_tax->get_slug());
$ht_heel_type_list->display_heel_type();
$ht_heel_type_list->cust_taxonomy_display();

$args = array(
	'parent_slug' => $ht_post->get_slug(),
	'tax_slug'    => $ht_custom_tax->get_slug(),
	'page_title'  => 'Reorder Heels',
	'menu_title'  => 'Reorder Heels', 
	'capability'  => 'manage_options',
	'menu_slug'   => 'reorder_heels',
	'cb_function' => 'reorder_heels_page'
	);

$ht_heel_reorder = new SubMenuPage($args);
