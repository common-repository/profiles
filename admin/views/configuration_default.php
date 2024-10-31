<?php $cat_options = $pr_options->categories; ?>
<div class="metabox-holder has-right-sidebar">
	<h3>Categories</h3>
	<div id="side-info-column" class="inner-sidebar">
		<?php profiles_create_infobox(); ?>
		<p>Lorem Ipsum Dolor Sit Amet . . .</p>
		<?php profiles_end_infobox(); ?>
	</div>
	<div id="post-body">
		<div id="post-body-content">
			<p>The following categories contain profiles information</p>
			<div class="tablenav">
				<div class="align-left action">
					<form action="tools.php" method="get">
						<input type="hidden" name="page" value="admin/profiles_configuration.php" />
						<input type="hidden" name="newcat" value="1" />
						<input type="submit" value="Add New" class="button-secondary" id="add_newcat" />
					</form>
				</div>
			</div>
			<div class="clear"></div>
			<table class="widefat fixed">
				<thead style="text-align: center">
					<tr>
						<th scope="col">Category</th>
						<th scope="col">Data Template</th>
						<th scope="colgroup" colspan="3" align="center">Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php if (is_array($cat_options)) :?>
				<?php foreach($cat_options as $category) : ?>
					<tr>
						<td><?php echo $category->name; ?></td>
						<td><?php if ($category->template == "") { echo "No Template Assigned (assign)"; } else { echo $category->template; } ?></td>
						<td>Edit/Assign</td>
						<td><form method="post"><input type="hidden" name="action" value="profiles_deletecat" /><input type="hidden" name="catname" value="<?php echo $category->name; ?>" /><?php wp_nonce_field('profiles_configuration_deletecat'); ?><input id="submit-link" class="submit-link-button profiles-deletecat-button" type="submit" value="Delete" /></form></td>
						<td>Another Option</td>
					</tr>
				<?php endforeach;?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>	
</div>