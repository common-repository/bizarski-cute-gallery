<?php 

require_once("header.php"); 
$section_url = get_admin_url(false, "admin.php?page=cutegallery-admin"); 

if($_POST['submit_cuteblog'] == "Y") { 

	$data = array(
		'name' => $_POST['cutegallery_name'],
		'description' => $_POST['cutegallery_description'],
		'date' => $_POST['cutegallery_date'],
	);

	if($action == "edit") { 
		global $wpdb; 
		$wpdb->update($wpdb->prefix."cutegallery", $data, array("id"=>$id)); ?>
		<div class="updated"><p><strong><?php _e('Successfully updated gallery.'); ?></strong></p></div>
		<?php
	} else { 
		global $wpdb; 
		$wpdb->insert($wpdb->prefix."cutegallery", $data); 
		$action = "";  ?>
		<div class="updated"><p><strong><?php _e('Successfully created new gallery.'); ?></strong></p></div>
		<?php
	}

}

if($action == "trash") { 
	global $wpdb; 
	
	$uploads = wp_upload_dir();
	$baseurl = $uploads['baseurl'];
	$upload_base = $uploads['basedir'];
	
	
	$images = $wpdb->get_results("SELECT image FROM ".$wpdb->prefix."cutegallery_images WHERE gallery_id='".$id."'");
	if(count($images) > 0) { 
		foreach($images as $img) { 
			$path = $upload_base.cutegallery_DIRS."images".cutegallery_DIRS.$img->image;
			if(file_exists($path)){ 
				unlink($path);
			}
			$path = $upload_base.cutegallery_DIRS."images".cutegallery_DIRS."thumbs".cutegallery_DIRS.$img->image;
			if(file_exists($path)){ 
				unlink($path);
			}
			$path = $upload_base.cutegallery_DIRS."images".cutegallery_DIRS."side".cutegallery_DIRS.$img->image;
			if(file_exists($path)){ 
				unlink($path);
			}
		}
	}
	
	$wpdb->query("DELETE FROM ".$wpdb->prefix."cutegallery_images WHERE gallery_id='".$id."'"); 
	$wpdb->query("DELETE FROM ".$wpdb->prefix."cutegallery WHERE id='".$id."'"); 
	
	?>
	<div class="updated"><p><strong><?php _e('Successfully deleted gallery.'); ?></strong></p></div>
	<?php
}


?>

<div class="wrap">

<div id="icon-link-manager" class="icon32"><br></div>

<?php 

switch($action) { 

	case("edit") : ?>
		<h2><?php echo  __( 'Cute Gallery'); ?> - 
		<?php 
		
		global $wpdb;
		$row = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."cutegallery WHERE id='".$id."'");
		echo  __( 'Edit Gallery'); 
		 ?>
		</h2>
		<form name="cutegallery_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">	

			<input type="hidden" name="submit_cuteblog" value="Y">
			
			<table class="form-table" cellpadding="0">
				<tbody>
				<tr>
					<th scope="row"><label for="cutegallery_name">* <?php echo __('Name'); ?></label></th>
					<td><input name="cutegallery_name" id="cutegallery_name" size="100" value="<?php echo $row ? esc_attr(stripslashes($row->name)) : '' ?>" type="text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="cutegallery_date">* <?php echo __('Date'); ?></label></th>
					<td><input name="cutegallery_date" id="cutegallery_date" class="datepicker" size="100" value="<?php echo $row ? esc_attr(stripslashes($row->date)) : '' ?>" type="text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="cutegallery_description"><?php echo __('Description'); ?></label></th>
					<td><textarea cols="40" rows="5" name="cutegallery_description" id="cutegallery_description"><?php echo $row ? stripslashes($row->description) : '' ?></textarea></td>
				</tr>
				</tbody>
			</table>

			<p class="submit"><input name="submit" id="submit" class="button-primary" value="<?php echo $action == "edit" ? __('Update') : __('Submit'); ?>" type="submit"></p>

		</form>
		<?php
		break; 
	default : ?>
	
		<h2>
		<?php echo  __( 'Cute Gallery'); ?> - <?php echo  __( 'All Galleries'); ?>
		</h2>
		
		<br class="clear">
		<div id="col-container">
		
			<div id="col-right">
			
				<div class="col-wrap">
				
					<h3><?php echo __('Manage Galleries');  ?></h3>
					<br class="clear">
	
					<table class="wp-list-table widefat fixed posts" cellspacing="0">
						<thead>
						<tr>
							<th><?php echo __('Name'); ?></th>
							<th><?php echo __('Date'); ?></th>
						</tr>
						</thead>
						<tbody>
						<?php 
						global $wpdb;
						$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."cutegallery ORDER BY date DESC");
						foreach($res as $row) { 
							$edit_params = array('action' => "edit", 'id' => $row->id);
							$delete_params = array('action' => "trash", 'id' => $row->id);
							?>
							<tr>
								<td>
									<strong><?php echo $row->name; ?></strong>
									<div class="row-actions">
										<span class="edit"><a href="<?php echo add_query_arg($edit_params, $section_url); ?>">Edit</a> | </span>
										<span class="trash"><a class="submitdelete" href="<?php echo add_query_arg($delete_params, $section_url); ?>">Trash</a></span>
									</div>
								</td>
								<td>
									<?php echo date("d F Y", strtotime($row->date)); ?>
								</td>
							</tr>
							<?php
						}
						?>
						</tbody>
					</table>
				
				</div>
			
			</div>
			
			<div id="col-left">
			
				<div class="col-wrap">
				
					<div class="form-wrap">
					
						<h3><?php echo __('Add New Gallery');  ?></h3>
				
						<form name="cutegallery_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">	
							
							<input type="hidden" name="submit_cuteblog" value="Y">
							
							<div class="form-field">
								<label for="cutegallery_name">* <?php echo __('Name'); ?></label>
								<input name="cutegallery_name" id="cutegallery_name" size="100" type="text">
							</div>
							<div class="form-field">
								<label for="cutegallery_date">* <?php echo __('Date'); ?></label>
								<input name="cutegallery_date" id="cutegallery_date" class="datepicker" size="100" type="text">
							</div>
							<div class="form-field">
								<label for="cutegallery_description"><?php echo __('Description'); ?></label>
								<textarea cols="40" rows="5" name="cutegallery_description" id="cutegallery_description"></textarea>
							</div>

							<p class="submit"><input name="submit" id="submit" class="button" value="<?php echo __('Add New Gallery'); ?>" type="submit"></p>


						</form>
				
					</div>
				
				</div>
			
			</div>
		
		</div>
	
		<?php 
		break; 
}

?>

</div>