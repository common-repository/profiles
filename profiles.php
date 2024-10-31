<?php
/*
Plugin Name: Profile Viewer
Plugin URI: http://compu.terlicio.us/code/plugins/profiles
Description: Profile Viewer / Biography Engine
Version: trunk
Author: Christopher O'Connell
Author URI: http://compu.terlicio.us/
*/
?>
<?php

$profiles_revision = "3.0 Alpha 1";

// Initialize Options
require_once('library/options.php');
$pr_options = profilesOptions::getInstance();

// Do some immediate stuff
profiles_startup();


// Setup includes
require_once('library/compat.php');
require_once('library/config.php');
require_once('admin/install.php');
require_once('library/plugin-functions.php');
require_once('library/admin_functions.php');

// Add activation script
register_activation_hook(__FILE__,'profiles_install');

// Hook to rewrite rules
add_filter('page_rewrite_rules','profiles_rewrite_rules');

// Hook to the head
//add_action('wp_head','profiles_add_head');

// Hooks for the admin pages
add_action('admin_menu','profiles_add_admin');

// Hook for the admin js
add_action('admin_head','profiles_admin_scripts');

// Hooks for the admin JS
add_action('wp_ajax_profiles_deletecat','profiles_deletecat');
add_action('wp_ajax_profiles_newcat','profiles_newcat');
add_action('wp_ajax_profiles_content_reload','profiles_content_reload');

// Check if the current version of the plugin is installed
if (get_option('profiles_revision') != $profiles_revision) {
	profiles_install();
}

function profiles_startup() {
	define(PROFILES_PATH,dirname(__FILE__));
	register_taxonomy('profiles','profiles',array( 'hierarchical' => true, 'label' => 'People', 'query_var' => true, 'rewrite' => true ));\
}