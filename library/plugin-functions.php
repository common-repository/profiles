<?php
/*
Functions required for the proper internal operation of the plugin
*/

// Init
function profiles_init() {
	
	$admin = get_role('administrator');
	$admin->add_cap('profiles_menu');
	
}

// Add the admin section
function profiles_add_admin() {
	//add_menu_page("Profiles", "Profiles", 'profiles_menu', 'admin/profiles_main.php', 'profiles_menu', profiles_url('my-plugin/images/logo.png'));
	add_menu_page('Profiles', 'Profiles', 8, 'admin/profiles_main.php', 'profiles_menu', profiles_url('/images/menu.png'));
	add_submenu_page('admin/profiles_main.php', 'Edit', 'Edit Profiles', 8, 'admin/profiles_main.php', 'profiles_menu');
	add_submenu_page('tools.php','Profiles Configuration', 'Profiles Configuration', 8, 'admin/profiles_configuration.php','profiles_configuration');
}

function profiles_admin_scripts() {
	wp_enqueue_script('jquery');
//	wp_enqueue_script('jquery-ui-profiles',WP_PLUGIN_DIRECTORY."/profiles/admin/scripts/jquery-ui-1.7.2.profiles.min.js",false,'1.7.2',true);
//	wp_enqueue_script('profiles-configuration',WP_PLUGIN_DIRECTORY."/profiles/admin/scripts/configuration.js",false,'0.1',true);
}

function profiles_menu() {
	require_once(PROFILES_PATH."/admin/profiles_main.php");
}

function profiles_configuration() {
	require_once(PROFILES_PATH."/admin/profiles_configuration.php");
}

// Some useful functions
function profiles_url($location) {
	return plugins_url('profiles/'.$location);
}