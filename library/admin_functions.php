<?php

// Administration Functions

// View Functions

function profiles_create_infobox($title="Info") { ?>
			<div id="side-sortables" class="meta-box-sortables ui-sortable">
				<div id="infodiv" class="postbox">	
					<div class="handlediv" title="Click to toggle"><br></div>
					<h3 class="hndle"><span><?php echo $title; ?></span></h3>
					<div class="inside">
<?php }

function profiles_end_infobox() { ?>
					</div>
				</div>
			</div>
<?php }

// SACK Functions

function profiles_deletecat() {
	
	$catname = $_POST['catname'];
	
	$message['error'] = "";
	$message['info'] = "";
	
	$message['info'] = "Tried to delete category $catname.\n";
	$message['error'] = "Unfortunately, deleteCategory() is not yet implemented.\n";
	
	
	// Cleanup
	$data['message'] = $message;
	
	$is_ajax = isset($_POST['ajax']);
	
	if($is_ajax) {
		
		die(json_encode($data));
	}
	
	return $data;
}

function profiles_newcat() {
	
	// Setup
	global $pr_options;
	$cat_options = $pr_options->categories;
	$message = "";

	// Logic
	profiles_verify_request('profiles_configuration_newcat');
	$name = $_POST['category_name'];
	if(!get_magic_quotes_gpc()) {
		$name = addslashes($name);
	}
	$already_set = false;
	if(is_array($cat_options)) {
		foreach($cat_options as $cat_option) {
			if (strtolower($cat_option->name) == strtolower($name)) {
				$message['error'] = $message['error']."A category with the name $cat_option->name already exists!\n";
				$already_set = true;
				break;
			}
		}
	}
	if(!$already_set) {
		$cat_options[] = new profilesCategory($name);
		$pr_options->categories = $cat_options;
		$message['info'] = $message['info']."Category '$name' successfully added.";
	}
	
	// Cleanup
	$data['message'] = $message;
	
	$is_ajax = isset($_POST['ajax']);
	
	if($is_ajax) {
		
		die(json_encode($data));
	}
	
	return $data;
}

function profiles_content_reload() {
	
	profiles_verify_request('profiles_content_reload');
	
	global $pr_options;
	
	die(require_once(PROFILES_PATH."/admin/views/configuration_default.php"));
}


function profiles_verify_request($namespace) {

	$error = "";
	
	if (!isset($_POST['_wpnonce']))	$error = "There was a problem authenticating. Please log out and log back in.\n";
	if (!check_admin_referer($namespace)) $error = "There was a problem authenticating. Please log out and log back in.\n";
	
	// Die if neccessary
	if ($error != "") {
		
		$is_ajax = isset($_POST['ajax']);
		
		if($is_ajax) {
			$data['message']['error'] = $error;		
			die(json_encode($data));
		}
		
		die($error);
	}
	
}