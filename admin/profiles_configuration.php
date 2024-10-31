<?php // The Profiles Main Page



// Get Profiles Options
global $pr_options;

$update_message['error'] = "";
$update_message['info'] = "";

// Get Includes
require_once(PROFILES_PATH."/wp-data/wp-data.php");
require_once(PROFILES_PATH."/library/admin_functions.php");

if(isset($_POST['action'])) {
	switch ($_POST['action']) {
		case 'profiles_newcat':
			$ret_message = profiles_newcat();
			$ret_message = $ret_message['message'];
			$update_message['info'] = $update_message['info'].$ret_message['info'];
			$update_message['error'] = $update_message['error'].$ret_message['error'];
			break;
		case 'profiles_deletecat':
			$ret_message = profiles_deletecat();
			$ret_message = $ret_message['message'];
			$update_message['info'] = $update_message['info'].$ret_message['info'];
			$update_message['error'] = $update_message['error'].$ret_message['error'];
			break;
	}
}

?>
<style type="text/css">
	#icon-profiles {
		background: transparent url(<?php echo profiles_url("/images/icon32.png"); ?>) no-repeat;
	}
	.profiles_message {
		font-weight: 700;
		color: #001A33;
		padding: 5px;
	}
	#profiles_info {
		background: #B3FF66;
		border-color: #00B800;

		}
	#profiles_error {
		background: #FF8080;
		border-color: #CC0000;
		}
	#submit-link {
		background: transparent;
		border: 0;
		margin: 0;
		padding: 0;
		color: #21759B;
		font-family: "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
		font-size: 11px;
		display: inline;
		}
	#submit-link.hover {
		color: #D54E21;
		}
	.invisible {
		display: none;
		}
</style>
<div id="profiles_info" class="updated profiles_message <?php if($update_message['info'] == "") echo "invisible"; ?>">
	<?php echo str_replace("\n","<br />",$update_message['info']); ?>
</div>
<div id="profiles_error" class="error profiles_message <?php if($update_message['error'] == "") echo "invisible"; ?>">
	<?php echo str_replace("\n","<br />",$update_message['error']); ?>
</div>

<div class="wrap">
	<div class="icon32" id="icon-profiles"><br/></div>
	<h2 id="profiles-title">Profiles Configuration <span class="invisible" style="color: #767646;"><em>Updating <img style="vertical-align: middle" src="<?php echo profiles_url('images/admin-loader.gif'); ?>" /></em></span></h2>
	<div id="profiles-content-holder">
	<?php 
	
	// View Select
	if(isset($_GET['newcat'])) {
		require_once(PROFILES_PATH."/admin/views/configuration_newcat.php");
	} else {
		require_once(PROFILES_PATH."/admin/views/configuration_default.php");
	}
	
	?>
	</div>
</div>
<!-- Invisible "AJAX" sections -->
<div id="profiles-deletecat-confirm" class="invisible">
	<p>Are you sure you want to delete $? You will not be able to undo this action!</p>
</div>

<div id="profiles-newcat-dialog" title="Add a new category" class="invisible">
	<p>Please enter the name for your new category</p>
	<form action="tools.php?page=admin/profiles_configuration.php" method="post" id="profiles-newcat-form">
		<input type="text" name="category_name" maxlength="24" /><br />
		<input type="hidden" name="action" value="profiles_newcat" />
		<?php wp_nonce_field('profiles_configuration_newcat'); ?>
	</form>
</div>

<div id="profiles-content-reload" class="invisible">
	<form id="profiles-content-reload-form">
		<input type=hidden" name="action" value="profiles_content_reload">
		<?php wp_nonce_field('profiles_content_reload'); ?>		
	</form>
</div>


<script type="text/javascript" src="<?php echo profiles_url("admin/scripts/jquery-ui-1.7.2.profiles.min.js");?>"></script>
<script type="text/javascript">
var profiles_siteurl = "<?php echo get_option('siteurl'); ?>";
</script>
<script type="text/javascript" src="<?php echo profiles_url("admin/scripts/configuration.js"); ?>"></script>
<link type="text/css" href="<?php echo profiles_url("admin/scripts/jquery-ui-1.7.2.custom.css"); ?>"  rel="stylesheet"/>