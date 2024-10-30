<?php 

require_once("header.php"); 

$section_url = get_admin_url(false, "admin.php?page=cutegallery-settings"); 

if($_POST['submit_cutegig'] == "Y") { 
	$cutegallery_thumb_w = $_POST['cutegallery_thumb_w'];
	update_option('cutegallery_thumb_w', $cutegallery_thumb_w);
	
	$cutegallery_thumb_h = $_POST['cutegallery_thumb_h'];
	update_option('cutegallery_thumb_h', $cutegallery_thumb_h);
	
	$cutegallery_side_w = $_POST['cutegallery_side_w'];
	update_option('cutegallery_side_w', $cutegallery_side_w);
	
	$cutegallery_side_h = $_POST['cutegallery_side_h'];
	update_option('cutegallery_side_h', $cutegallery_side_h);
	
	$cutegallery_cont_css = $_POST['cutegallery_cont_css'];
	update_option('cutegallery_cont_css', $cutegallery_cont_css);
} else { 
	$cutegallery_thumb_w = cutegallery_THUMB_WIDTH;

	$cutegallery_thumb_h = cutegallery_THUMB_HEIGHT;

	$cutegallery_side_w = cutegallery_SIDE_WIDTH;

	$cutegallery_side_h = cutegallery_SIDE_HEIGHT;

	$cutegallery_cont_css = cutegallery_CONT_CSS;
}

?>

<div class="wrap">

<div id="icon-options-general" class="icon32"><br></div>

		<h2><?php echo  __( 'Cute Gallery' ); ?> - 
		<?php echo __( 'Settings' ); ?>
		</h2>
		<form name="cutegallery_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">	
		<input type="hidden" name="submit_cutegig" value="Y">
		
			<h3><?php echo __('Image sizes'); ?></h3>

			<table class="form-table">
				<tbody>
				<tr>
					<th scope="row"><?php echo __('Thumbnail size'); ?></th>
					<td>
					<label for="cutegallery_thumb_w"><?php echo __('Width'); ?></label>
					<input name="cutegallery_thumb_w" id="cutegallery_thumb_w" class="small-text" value="<?php echo esc_attr($cutegallery_thumb_w); ?>" type="text">
					<label for="cutegallery_thumb_h"><?php echo __('Height'); ?></label>
					<input name="cutegallery_thumb_h" id="cutegallery_thumb_h" class="small-text" value="<?php echo esc_attr($cutegallery_thumb_h); ?>" type="text">
					</td>
				</tr>
				<tr>
					<th scope="row"><?php echo __('Widget image size'); ?></th>
					<td>
					<label for="cutegallery_side_w"><?php echo __('Width'); ?></label>
					<input name="cutegallery_side_w" id="cutegallery_side_w" class="small-text" value="<?php echo esc_attr($cutegallery_side_w); ?>" type="text">
					<label for="cutegallery_side_h"><?php echo __('Height'); ?></label>
					<input name="cutegallery_side_h" id="cutegallery_side_h" class="small-text" value="<?php echo esc_attr($cutegallery_side_h); ?>" type="text">
					</td>
				</tr>
				</tbody>
			</table>
			
			<h3><?php echo __('Style settings'); ?></h3>
			
			<table class="form-table">
				<tbody>
				<tr>
					<th scope="row"><label for="cutegallery_cont_css"><?php echo __('Container style'); ?></label></th>
					<td><input name="cutegallery_cont_css" id="cutegallery_cont_css" size="100" value="<?php echo esc_attr($cutegallery_cont_css); ?>" type="text"><p class="description"><?php echo __('In CSS format. Example: width: 400px; background: #000; color: #fff;'); ?></p></td>
				</tr>
				</tbody>
			</table>

			<p class="submit">
			<input name="save" class="button-primary" id="publish" accesskey="p" value="<?php echo __('Save Changes'); ?>" type="submit">
			</p>
			
		</form>

</div>