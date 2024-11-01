<?php

if( !defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') ) {
	exit();
	}
	
function jsrm_uninstall(){
	global $wpdb;
	$jsrm_menu_table = $wpdb->prefix . "jsrm_menus";
	$jsrm_item_table = $wpdb->prefix . "jsrm_items";
	$wpdb->query( $wpdb->prepare("DROP TABLE IF EXISTS $jsrm_menu_table,$jsrm_item_table") );
	delete_option('jsrm_db_version');
	delete_option('jsrm_val_cols');	
}

function jsrm_uninstall_loop(){
	global $wpdb;
	if (function_exists('is_multisite') && is_multisite()) {
		$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
		foreach ($blogids as $blog_id) {
			switch_to_blog($blog_id);
			jsrm_uninstall();
		}
		restore_current_blog();
		return;
	}
	jsrm_uninstall();
}

jsrm_uninstall_loop();

?>