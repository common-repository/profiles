<div class="metabox-holder has-right-sidebar">
	<h3>Categories</h3>
	<div id="side-info-column" class="inner-sidebar">
		<?php profiles_create_infobox(); ?>
		<p>"Please enter a name for your new category. Making this name distinctive will help prevent colisions with other applications.</p>
		<?php profiles_end_infobox(); ?>
	</div>
	<div id="post-body">
		<div id="post-body-content">
			<form action="tools.php?page=admin/profiles_configuration.php" method="post" >
				<input type="text" name="category_name" maxlength="24" /><br />
				<input type="submit" value="Create Category >" />
				<input type="hidden" name="action" value="profiles_newcat" />
				<?php wp_nonce_field('profiles_configuration_newcat'); ?>
			</form>
		</div>
	</div>	
</div>