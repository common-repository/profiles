<?php
/*

Install Script for the profile viewer

*/

// Hook into DB setup
function profiles_install() {

	// Set globals
	global $profiles_revision;
	global $pr_options;

	// Setup options object
	$options = array(
		"load_jquery" => true,
		"use_permalinks" => false,
		"locations" => array(),
		"setup_complete" => false,
		"image_width" => '200',
		"image_height" => '300',
		"watermark" => false,
		"watermark_text" => 'Profiles',
		"watermark_color_fore" => '#990100',
		"watermakr_color_shadow" => '#123456',
		"watermark_font" => 'trumania',
		"watermark_size" => '18',
	);
	
	foreach($options as $opt => $opt_val) {
		$pr_options->$opt = $opt_val;
	}
	
	// Update revision.
	update_option('profiles_revision',$profiles_revision);
	
	// Do extended install
	profiles_install_database();
	
	
/*	if (get_option('template') != "k2") {
		update_option('profiles_load_jquery','true');
	} else {
		update_option('profiles_load_jquery','false');
	}
	
	update_option('profiles_use_permalinks','true');
	update_option('profiles_default_text','is a ignominiously listed on '.get_option('blogname'));
	update_option('profiles_location','people');
	update_option('profiles_revision',$profiles_revision);
	update_option('profiles_setup_complete','false');
	update_option('profiles_user_level','1');
	update_option('profiles_image_width','200');
	update_option('profiles_image_height','300');
	update_option('profiles_image_watermark_add','false');
	update_option('profiles_image_watermark_text','Profiles');
	update_option('profiles_image_watermark_color','990100#123456');
	update_option('profiles_image_watermark_font','trumania');
	update_option('profiles_image_watermark_size','18');*/
}

function profiles_install_database() {

	require_once(dirname(__FILE__). '/../../../../wp-admin/upgrade-functions.php');

	global $wpdb;
	global $pr_options;
	
	$database_revision = array(
							"DB" => "3.0", 
							"Release" => "trunk", 
							"Dataformat" => "5.1", 
							"Options" => array(
										"-custom-tables",
										"-facebook",
										"-shows",
										"-udbs",
										"+meta-uid-link",
										"-global-meta",
										"+post-meta",
										),
							"uData" => "0.1",
							);
	
	if ($pr_options->database_revision != $database_revision) {
/*		$sql = "CREATE TABLE `".$people_table."` (
			`id` int(11) NOT NULL auto_increment,
			`first_name` varchar(30) default NULL,
			`last_name` varchar(30) default NULL,
			`slug` varchar(60) NOT NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=MyISAM ;";

		dbDelta($sql);

		$sql2 = "CREATE TABLE `".$bios_table."` (
			  `id` int(11) NOT NULL auto_increment,
			  `uid` int(11) NOT NULL default '0',
			  `text` longtext NOT NULL,
			  `photo` blob,
			 PRIMARY KEY  (`id`)
			) ENGINE=MyISAM ;";
      
		dbDelta($sql2);*/
	
		$pr_options->database_revision = $database_revision;
	}

}