<?php
/*
Plugin Name: Simple Retail Menus
Plugin URI: http://whatwouldjessedo.com/simple-retail-menus/
Description: Create and manage salon, restaurant, or retail store menu lists of services, food items, retail items, or other data. Multisite capable.
Author: Jesse Cortez
Author URI: http://whatwouldjessedo.com/
Version: 4.2.1

Copyright 2014  Jesse Cortez  (email : whatwouldjessedo at gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110, USA
*/

defined('WP_PLUGIN_URL') or die('Restricted access');
define('JSRM_USER_CAPABILITY',"publish_pages");
define('JSRM_SELF', $_SERVER["PHP_SELF"]."?page=jsrm-retail-menus");
define ('JSRM_DB_VERSION', 4);
define ('JSRM_DISPLAY_VERSION', 4);
define ('JSRM_VALUE_COLS', get_option('jsrm_val_cols','2'));
define ('JSRM_SHOW_DONATION', true);
define ('JSRM_ADMIN_BAR_NODE', true);
global $wpdb;
$jsrm_menu_table = $wpdb->prefix . "jsrm_menus";
$jsrm_item_table = $wpdb->prefix . "jsrm_items";


// DEACTIVATION

function jsrm_deactivate(){
	remove_shortcode( 'simple-retail-menu' ); 
}

function jsrm_deactivate_loop() {
	global $wpdb;
	if (function_exists('is_multisite') && is_multisite()) {
		// check if it is a network activation - if so, run the activation function for each blog id
		if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)) {
			$old_blog = $wpdb->blogid;
			// Get all blog ids
			$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				jsrm_deactivate();
			}
			switch_to_blog($old_blog);
			return;
		}	
	} 
	jsrm_deactivate();		
}

register_deactivation_hook( __FILE__, 'jsrm_deactivate_loop' );


// ACTIVATE PLUGIN
register_activation_hook( __FILE__, 'jsrm_activate_loop' ); 

function jsrm_activate_loop(){
	global $wpdb;
	if (function_exists('is_multisite') && is_multisite()) {
		// check if it is a network activation - if so, run the activation function for each blog id
		if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)) {
			$old_blog = $wpdb->blogid;
			// Get all blog ids
			$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				jsrm_activate();
			}
			switch_to_blog($old_blog);
			return;
		}	
	} 
	jsrm_activate();		
}

// ACTIVATE WHEN NEW BLOG IS CREATED
add_action( 'wpmu_new_blog', 'jsrm_new_blog', 10, 6 ); 		
 
function jsrm_new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta){
	global $wpdb;
	if (is_plugin_active_for_network('simple-retail-menus/simple-retail-menus.php')) {
		$old_blog = $wpdb->blogid;
		switch_to_blog($blog_id);
		jsrm_activate();
		switch_to_blog($old_blog);
	}
}

function jsrm_activate() {
	add_option('jsrm_val_cols', '2');
	
	global $wpdb;
	$jsrm_menu_table = $wpdb->prefix . "jsrm_menus";
	$jsrm_item_table = $wpdb->prefix . "jsrm_items";
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	if (!empty ($wpdb->charset))
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
	if (!empty ($wpdb->collate))
		$charset_collate .= " COLLATE $wpdb->collate";
		
	if ($wpdb->get_var( "SHOW TABLES LIKE $jsrm_menu_table") != $jsrm_menu_table){

		$sql1 = "CREATE TABLE IF NOT EXISTS $jsrm_menu_table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				menuorder mediumint(9) NOT NULL,
				name tinytext NOT NULL,
				description text,
				label tinytext NOT NULL,
				itemheader tinytext NOT NULL,
				valueheader tinytext NOT NULL,";
		
		for ($v=2;$v<=JSRM_VALUE_COLS;$v++){
			$sql1 .= "valueheader" . $v . " tinytext NOT NULL,";
		};		
		$sql1 .= "UNIQUE KEY id (id)
				) $charset_collate;";
				
		dbDelta($sql1);
	}

	if ($wpdb->get_var( "SHOW TABLES LIKE $jsrm_item_table") != $jsrm_item_table){
			
			$sql2 = "CREATE TABLE IF NOT EXISTS $jsrm_item_table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				menu mediumint(9) NOT NULL,
				itemorder mediumint(9) NOT NULL,
				item tinytext NOT NULL,
				description text,
				image tinytext,
				linked tinyint(1),
				linkurl tinytext,
				itemhidden tinyint(1),
				value tinytext,";
				
			for ($v=2;$v<=JSRM_VALUE_COLS;$v++){
				$sql2 .= "value" . $v . " tinytext,";
			};	
			$sql2 .= "UNIQUE KEY id (id)
				) $charset_collate;";
		dbDelta($sql2);
	}
	jsrm_update_db_tables();
}


// ADD DATABASE TABLES
function jsrm_update_db_tables(){
	global $wpdb;
	$jsrm_menu_table = $wpdb->prefix . "jsrm_menus";
	$jsrm_item_table = $wpdb->prefix . "jsrm_items";

	// Update tables with new columns used in this version
	$checkmenus = $wpdb->query( $wpdb->prepare( "SELECT * FROM $jsrm_menu_table LIMIT 1" ) );
	$menucols = mysql_fetch_array($checkmenus);
	
	if(!isset($menucols['menuorder'])){
		$wpdb->query( $wpdb->prepare("ALTER TABLE $jsrm_menu_table ADD menuorder mediumint(9) NOT NULL") );
	};
	if(!isset($menucols['label'])){
		$wpdb->query( $wpdb->prepare("ALTER TABLE $jsrm_menu_table ADD label tinytext NOT NULL") );
	};
	if(!isset($menucols['itemheader'])){
		$wpdb->query( $wpdb->prepare("ALTER TABLE $jsrm_menu_table ADD itemheader tinytext NOT NULL") );
	};
	if(!isset($menucols['valueheader'])){
		$wpdb->query( $wpdb->prepare("ALTER TABLE $jsrm_menu_table ADD valueheader tinytext NOT NULL") );
	};
	
	//loop to update the menu table value headers columns according to number of values.
	for ($v=2;$v<=JSRM_VALUE_COLS;$v++){
		$valh = "valueheader".$v;
		if(!isset($menucols[$valh])){
			$wpdb->query( $wpdb->prepare("ALTER TABLE $jsrm_menu_table ADD $valh tinytext NOT NULL") );
		};
	};
	
	$checkitems = $wpdb->query( $wpdb->prepare( "SELECT * FROM $jsrm_item_table LIMIT 1" ) );
	$itemscols = mysql_fetch_array($checkitems);
	
	if(!isset($itemscols['image'])){
		$wpdb->query( $wpdb->prepare("ALTER TABLE $jsrm_item_table ADD image tinytext") );
	};
	if(!isset($itemscols['linked'])){
		$wpdb->query( $wpdb->prepare("ALTER TABLE $jsrm_item_table ADD linked tinyint(1) NOT NULL") );
	};
	if(!isset($itemscols['linkurl'])){
		$wpdb->query( $wpdb->prepare("ALTER TABLE $jsrm_item_table ADD linkurl tinytext") );
	};
	if(!isset($itemscols['itemhidden'])){
		$wpdb->query( $wpdb->prepare("ALTER TABLE $jsrm_item_table ADD itemhidden tinyint(1) NOT NULL") );
	};
	
	//loop to update the menu items value columns according to number of values.
	for ($v=2;$v<=JSRM_VALUE_COLS;$v++){
		$val = "value".$v;
		if(!isset($menucols[$vh])){
			$wpdb->query( $wpdb->prepare("ALTER TABLE $jsrm_item_table ADD $val tinytext NOT NULL") );
		};
	};
	
	update_option( 'jsrm_db_version', JSRM_DB_VERSION );
}


// CHECK DATABASE TABLES ON INIT AND UPDATE IF NECESSARY

function jsrm_check_database_version(){
	$dbversion = get_option( 'jsrm_db_version', '1' );
	if ($dbversion != JSRM_DB_VERSION){
		jsrm_activate_loop();
	}
}
add_action('init', 'jsrm_check_database_version');


// ADD THE PLUGIN MENU TO THE ADMIN PAGE

function jsrm_add_menu_page() {
	$jsrmpluginpage = add_object_page( 'Retail Menus', 'Retail Menus', JSRM_USER_CAPABILITY, 'jsrm-retail-menus', 'jsrm_display_page');	
}
add_action('admin_menu', 'jsrm_add_menu_page');


// Add Plugin to the Admin Bar

function jsrm_admin_bar_render() {
	global $wp_admin_bar, $wpdb, $jsrm_menu_table, $jsrm_item_table;
	
	$wp_admin_bar->add_menu( array(
		'parent' => false,
		'id' => 'jsrm-toolbar-simple-retail-menus',
		'title' => __('Retail Menus'),
		'href' => admin_url("?page=jsrm-retail-menus"),
		'meta' => false
	));	
       	 
	$wp_admin_bar->add_menu( array(
		'parent'=> 'jsrm-toolbar-simple-retail-menus',
    	'id'    => 'jsrm-toolbar-menu-new',
    	'title' => __('Create New Menu'),
    	'href'  => admin_url("?page=jsrm-retail-menus&mode=new"),
    	'meta' => false
       	));
	
	$wp_admin_bar->add_group( array(
		'parent'=> 'jsrm-toolbar-simple-retail-menus',
    	'id'    => 'jsrm-toolbar-menus',
    	'meta' => false
       	));
       	
	$y = "SELECT * FROM $jsrm_menu_table ORDER by id ASC";
	$result = $wpdb->get_results($y);
	if ($result){
		foreach ($result as $r) {
			$menuid= $r->id;
			$name = esc_html(stripslashes($r->name));
			$label = ($r->label) ? esc_html(stripslashes($r->label)) : $name;
			$editlink = "?page=jsrm-retail-menus&mode=edit&targetmenu=".$menuid;
			
			$wp_admin_bar->add_menu( array(
            	'parent'=> 'jsrm-toolbar-menus',
            	'id'    => 'jsrm-toolbar-menu-'.$menuid,
            	'title' => __($label),
            	'href'  => admin_url($editlink),
            	'meta' => false
       		));
		}
	}
}

//Add SRM to the Admin Bar if option set to true.
if(JSRM_ADMIN_BAR_NODE){
	add_action( 'wp_before_admin_bar_render', 'jsrm_admin_bar_render' );
}


// ADD SCRIPTS & STYLES TO PLUGIN PAGE

function jsrm_admin_scripts(){
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
   	wp_enqueue_script('tabledragdrop', plugins_url('js/jquery.tablednd.0.7.min.js', __FILE__),array('jquery'));
    wp_enqueue_script('jsrm-admin-js', plugins_url('js/jsrm-admin-script.js', __FILE__),array('jquery','media-upload','thickbox','tabledragdrop'));
	wp_enqueue_style('thickbox');
	wp_enqueue_style('jsrm-admin-styles', plugins_url('css/admin.css', __FILE__));
}

if (isset($_GET['page']) && ($_GET['page'] == 'jsrm-retail-menus')){ 
	add_action('admin_enqueue_scripts', 'jsrm_admin_scripts');
}

//ADD SCRIPTS & STYLES TO POSTS PAGES

function jsrm_display_scripts() {
	wp_enqueue_script( 'jsrm-display-js', plugins_url('js/jsrm-page-script.js', __FILE__),array('jquery'));
	wp_enqueue_style('jsrm-display-styles', plugins_url("css/display.css", __FILE__));
}

add_action('wp_enqueue_scripts', 'jsrm_display_scripts');


// REGISTER SHORTCODE and RENDER OUTPUT

add_shortcode( 'simple-retail-menu', 'jsrm_shortcode' );

function jsrm_shortcode($atts){
	global $wpdb, $jsrm_menu_table, $jsrm_item_table;
	
	extract( shortcode_atts( array(
		'id' => false,
		'name' => false,
		'class' => 'jsrm-menu',
		'header'=>'h2',
		'desc' => 'p',
		'display' => 'table',
		'valuecols' => JSRM_VALUE_COLS
		), $atts ) );
	
	//Legacy for using NAME instead of ID in shortcode
	if($id){
		$menudata = $wpdb->get_row("SELECT * FROM $jsrm_menu_table WHERE id = '$id'");	
	}
	elseif($name){
		$menudata = $wpdb->get_row("SELECT * FROM $jsrm_menu_table WHERE name = '$name'");		
	}
	
	$id = $menudata->id;
	$menuname = html_entity_decode(stripslashes($menudata->name));
	$menudescription = html_entity_decode(stripslashes($menudata->description));
	$itemheader = html_entity_decode(stripslashes($menudata->itemheader));
	$valueheader1 = html_entity_decode(stripslashes($menudata->valueheader));
	$hasheaders = ($itemheader || $valueheader1) ? true : false;
	
	for ($v=2;$v<=JSRM_VALUE_COLS;$v++){
		$valueheader = "valueheader".$v;
		${$valueheader} = html_entity_decode(stripslashes($menudata->$valueheader));
		$hasheaders = (${$valueheader}) ? true : $hasheaders;
	}
	
	$q = "SELECT * FROM $jsrm_item_table WHERE menu = '$id' ORDER by itemorder ASC";
	$result = $wpdb->get_results($q);
	
	if ($result) { 
		ob_start();
		include( "includes/page-display-".JSRM_DISPLAY_VERSION.".php" );
		$menudump = ob_get_clean();
		return $menudump;
	}
}

// Database Actions

if( isset($_POST['dbtouch']) ){
	include( 'includes/actions.php' );
	}

// Admin Page Display

function jsrm_display_page() {
	ob_start();
	//Check User Credentials
	if (!current_user_can(JSRM_USER_CAPABILITY))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	global $wpdb, $jsrm_menu_table, $jsrm_item_table;
	$mode = (isset($_GET['mode'])) ? $_GET['mode'] : "home";
	?>
	<div id="jsrm" class="wrap">
	<h2>Simple Retail Menus 4</h2>
	<?php include( 'includes/mode-'.$mode.'.php' ); ?>
	</div>
	<?php 
	ob_end_flush();
}
?>