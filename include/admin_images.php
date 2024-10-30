<?php 

require_once("header.php"); 

global $wpdb;
$galleries = $wpdb->get_results("SELECT id, name FROM ".$wpdb->prefix."cutegallery ORDER BY date DESC");
$has_records = count($galleries);

if(!$has_records) { 
	echo '<div class="error"><p>Please, add a gallery first.</p></div>';
}

$gid = $gid ? $gid : $galleries[0]->id;
$section_url = get_admin_url(false, "admin.php?page=cutegallery-images&gid=".$gid); 

$uploads = wp_upload_dir();
$baseurl = $uploads['baseurl'];

if($_POST['submit_cuteblog'] == "Y") { 
	global $wpdb;
	$oldfile = $wpdb->get_var("SELECT image FROM ".$wpdb->prefix."cutegallery_images WHERE id='".$id."'");
	$filename = $oldfile ? $oldfile : '';
	if($_FILES['cutegallery_image']['size']) { 
		require_once("class.image.php");
		require_once("upload_image.php");
		$success = upload_image("cutegallery_image");
		if(is_array($success)) { 
			$filename = $success[0];
			if($oldfile && $oldfile != "na.jpg") { 
			
				$upload_base = $uploads['basedir'];
			
				$path = $upload_base.cutegallery_DIRS."images".cutegallery_DIRS.$oldfile;
				if(file_exists($path)){ 
					unlink($path);
				}
				$path = $upload_base.cutegallery_DIRS."images".cutegallery_DIRS."thumbs".cutegallery_DIRS.$oldfile;
				if(file_exists($path)){ 
					unlink($path);
				}
				$path = $upload_base.cutegallery_DIRS."images".cutegallery_DIRS."side".cutegallery_DIRS.$oldfile;
				if(file_exists($path)){ 
					unlink($path);
				}
			}
		}
	}

	$data = array(
		'gallery_id' => $_POST['cutegallery_gallery_id'],
		'caption' => $_POST['cutegallery_caption'],
		'image' => $filename
	);

	if($action == "edit") { 
		global $wpdb; 
		$wpdb->update($wpdb->prefix."cutegallery_images", $data, array("id"=>$id)); ?>
		<div class="updated"><p><strong><?php _e('Successfully updated image.'); ?></strong></p></div>
		<?php
	} else { 
		global $wpdb; 
		$wpdb->insert($wpdb->prefix."cutegallery_images", $data); 
		$action = "";  ?>
		<div class="updated"><p><strong><?php _e('Successfully added new image.'); ?></strong></p></div>
		<?php
	}

}

if($action == "trash") { 
	global $wpdb; 
	$oldfile = $wpdb->get_var("SELECT image FROM ".$wpdb->prefix."cutegallery_images WHERE id='".$id."'");
	if($oldfile) { 
	
		$upload_base = $uploads['basedir'];
	
		$path = $upload_base.cutegallery_DIRS."images".cutegallery_DIRS.$oldfile;
		if(file_exists($path)){ 
			unlink($path);
		}
		$path = $upload_base.cutegallery_DIRS."images".cutegallery_DIRS."thumbs".cutegallery_DIRS.$oldfile;
		if(file_exists($path)){ 
			unlink($path);
		}
		$path = $upload_base.cutegallery_DIRS."images".cutegallery_DIRS."side".cutegallery_DIRS.$oldfile;
		if(file_exists($path)){ 
			unlink($path);
		}
	}
	
	$wpdb->query("DELETE FROM ".$wpdb->prefix."cutegallery_images WHERE id='".$id."'"); 
	
	?>
	<div class="updated"><p><strong><?php _e('Successfully deleted image.'); ?></strong></p></div>
	<?php
}


?>

<div class="wrap">

<div id="icon-link-manager" class="icon32"><br></div>

<?php 

switch($action) { 

	case("new") : 
	case("edit") : ?>
		<h2><?php echo  __( 'Cute Gallery' ); ?> - 
		<?php 
		
		global $wpdb;
		$row = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."cutegallery_images WHERE id='".$id."'");
		echo  __( 'Edit Image' ); 
		 ?>
		</h2>
		<form name="cutegallery_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">	

			<input type="hidden" name="submit_cuteblog" value="Y">
			
			<table class="form-table" cellpadding="0">
				<tbody>

				<tr>
					<th scope="row"><label for="cutegallery_gallery_id">* <?php echo __('Gallery'); ?></label></th>
					<td>
						<select name="cutegallery_gallery_id" id="cutegallery_gallery_id">
						<?php 
						foreach($galleries as $res) { 
							echo '<option value="'.$res->id.'"';
							if($row) { 
								echo $res->id == $row->gallery_id ? ' selected="selected"' : '';
							} else { 
								echo $gid == $res->id ? ' selected="selected"' : '';
							}
							echo '>'.stripslashes($res->name).'</option>';
						}

						?>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="cutegallery_caption"><?php echo __('Caption'); ?></label></th>
					<td><input name="cutegallery_caption" id="cutegallery_caption" value="<?php echo $row ? esc_attr(stripslashes($row->caption)) : ''; ?>" size="100" type="text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="cutegallery_image"><?php echo __('Image'); ?></label></th>
					<td>
					<input id="cutegallery_image" type="file" name="cutegallery_image"><br>
					<?php if($row) { if($row->image) { ?><img alt="" style="float: left" src="<?php echo $baseurl.'/images/thumbs/'.$row->image; ?>"><?php } } ?>
					</td>
				</tr>
				</tbody>
			</table>

			<p class="submit"><input name="submit" id="submit" class="button-primary" value="<?php echo $action == "edit" ? __('Update') : __('Submit'); ?>" type="submit"></p>



		</form>
		<?php
		break; 
	default : 

		?>
	
		<h2>
		<?php echo  __( 'Cute Gallery' ); ?> - <?php echo  __( 'Manage Images' ); ?>
		<?php if($has_records) { ?>
		<a class="add-new-h2" href="<?php echo add_query_arg($new_params, $section_url) ?>"><?php echo __('New Image'); ?></a> 
		<?php } ?>
		</h2>
		
		<br class="clear">
		
		<div class="tablenav top">

			<div class="alignleft actions">
				<div style="float: left; margin-right: 5px; line-height: 25px;"><?php _e('Select gallery: '); ?></div>
				<form style="display: inline" name="cutegallery_filter_form" method="GET" action="<?php echo $section_url; ?>">
				<input type="hidden" name="page" value="cutegallery-images">
				<select name="gid">
					<?php 
					foreach($galleries as $res) { 
						echo '<option value="'.$res->id.'"'.($res->id == $gid ? ' selected="selected"' : '').'>'.stripslashes($res->name).'</option>';
					}
					?>
				</select>
				<input name="" id="doaction" class="button-secondary action" value="<?php _e('Filter'); ?>" type="submit">
				</form>
				<br class="clear">
			</div>

			<br class="clear">
		</div>
		
		<table class="wp-list-table widefat fixed posts" cellspacing="0">
			<thead>
			<tr>
				<th><?php echo __('Image'); ?></th>
				<th><?php echo __('Gallery'); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php 
			global $wpdb;
			$res = $wpdb->get_results("SELECT imgs.id, imgs.image, gal.name AS albumname 
			FROM ".$wpdb->prefix."cutegallery_images AS imgs JOIN ".$wpdb->prefix."cutegallery AS gal 
			ON imgs.gallery_id = gal.id WHERE imgs.gallery_id = '".$gid."' 
			ORDER BY gallery_id DESC, gal.date DESC, id DESC");
			foreach($res as $row) { 
				$edit_params = array('action' => "edit", 'id' => $row->id);
				$delete_params = array('action' => "trash", 'id' => $row->id);
				?>
				<tr>
					<td>
						<strong><?php echo stripslashes($row->image); ?></strong>
						<div class="row-actions">
							<span class="edit"><a href="<?php echo add_query_arg($edit_params, $section_url); ?>">Edit</a> | </span>
							<span class="trash"><a class="submitdelete" href="<?php echo add_query_arg($delete_params, $section_url); ?>">Trash</a></span>
						</div>
					</td>
					<td>
						<?php echo stripslashes($row->albumname); ?>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
	
		<?php 
		break; 
}

?>

</div>