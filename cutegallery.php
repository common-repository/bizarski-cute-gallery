<?php
/**
 * @package Bizarski Cute Gallery
 */
/*
Plugin Name: Bizarski Cute Gallery
Description: Simple Fancybox gallery with customizable thumbnails. For documentation, visit my website. 
Plugin URI: http://cuteplugins.com/wordpress-cute-gallery/
Version: 1.3.0
Author: Bizarski
License: GPLv2 or later
*/

$cutegallery_thumb_w = get_option('cutegallery_thumb_w');
$cutegallery_thumb_w = $cutegallery_thumb_w ? $cutegallery_thumb_w : 110;
$cutegallery_thumb_h = get_option('cutegallery_thumb_h');
$cutegallery_thumb_h = $cutegallery_thumb_h ? $cutegallery_thumb_h : 75;

$cutegallery_side_w = get_option('cutegallery_side_w');
$cutegallery_side_w = $cutegallery_side_w ? $cutegallery_side_w : 200;
$cutegallery_side_h = get_option('cutegallery_side_h');
$cutegallery_side_h = $cutegallery_side_h ? $cutegallery_side_h : 300;

$cutegallery_cont_css = get_option('cutegallery_cont_css');
$cutegallery_cont_css = $cutegallery_cont_css ? $cutegallery_cont_css : 'margin: 8px 8px 0 0; border: 2px solid #000;';

define('cutegallery_THUMB_WIDTH', $cutegallery_thumb_w);
define('cutegallery_THUMB_HEIGHT', $cutegallery_thumb_h);

define('cutegallery_SIDE_WIDTH', $cutegallery_side_w);
define('cutegallery_SIDE_HEIGHT', $cutegallery_side_h);

define('cutegallery_CONT_CSS', $cutegallery_cont_css);

define('cutegallery_VER', '1.3.0');
define('cutegallery_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('cutegallery_PLUGIN_PATH', dirname(__FILE__));

//define('cutegallery_DIRS', '\\'); //localhost
define('cutegallery_DIRS', '/');

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}

include_once dirname( __FILE__ ) . '/widget.php';

class BizarskiCuteGallery { 

	function init() { 
		if(!is_admin()) { 
			wp_register_script('fancybox', cutegallery_PLUGIN_URL . 'script/fancybox/jquery.fancybox-1.3.4.js', array('jquery'));
			wp_enqueue_script('fancybox');	
			wp_register_script('cutegallery-front', cutegallery_PLUGIN_URL . 'cutegallery.front.js.php', array('jquery', 'fancybox'));
			wp_enqueue_script('cutegallery-front');
			
			wp_register_style('cutegallery.css', cutegallery_PLUGIN_URL . 'cutegallery.css');
			wp_enqueue_style('cutegallery.css');
			wp_register_style('fancybox', cutegallery_PLUGIN_URL . 'script/fancybox/jquery.fancybox-1.3.4.css');
			wp_enqueue_style('fancybox');
		} else { 
			wp_register_script('jquery-ui-core', cutegallery_PLUGIN_URL . 'script/jquery.ui.core.js', array('jquery'));
			wp_enqueue_script('jquery-ui-core');
			wp_register_script('jquery-ui-widget', cutegallery_PLUGIN_URL . 'script/jquery.ui.widget.js', array('jquery', 'jquery-ui-core'));
			wp_enqueue_script('jquery-ui-widget');
			wp_register_script('datepicker', cutegallery_PLUGIN_URL . 'script/datepicker.js', array('jquery', 'jquery-ui-core', 'jquery-ui-widget'));
			wp_enqueue_script('datepicker');
			wp_register_script('cutegallery-back', cutegallery_PLUGIN_URL . 'cutegallery.js.php', array('jquery', 'jquery-ui-core', 'jquery-ui-widget', 'datepicker'));
			wp_enqueue_script('cutegallery-back');
			
			wp_register_style('jquery.ui.all.css', cutegallery_PLUGIN_URL . 'script/base/jquery.ui.all.css');
			wp_enqueue_style('jquery.ui.all.css');
			wp_register_style('datePicker.css', cutegallery_PLUGIN_URL . 'script/datePicker.css');
			wp_enqueue_style('datePicker.css');
		}
		if (!session_id()) {
			session_start();
		}
	}
	
	function admin() {
		if(is_super_admin()) { 
			require_once dirname( __FILE__ ) . '/include/admin.php';
		}
	}
	
	function admin_images() {
		if(is_super_admin()) { 
			require_once dirname( __FILE__ ) . '/include/admin_images.php';
		}
	}
	
	function admin_settings() {
		if(is_super_admin()) { 
			require_once dirname( __FILE__ ) . '/include/admin_settings.php';
		}
	}
	
	function admin_actions() {
		if(is_super_admin()) { 
			add_menu_page("Cute Gallery", "Cute Gallery", 'add_users', "cutegallery-admin", array("Bizarskicutegallery", "admin"), false);
			add_submenu_page("cutegallery-admin", "Manage Galleries", "Manage Galleries", 'add_users', 'cutegallery-admin', array("Bizarskicutegallery", "admin"));
			add_submenu_page("cutegallery-admin", "Manage Images", "Manage Images", 'add_users', 'cutegallery-images', array("Bizarskicutegallery", "admin_images"));
			add_submenu_page("cutegallery-admin", "Settings", "Settings", 'add_users', 'cutegallery-settings', array("Bizarskicutegallery", "admin_settings"));
		}
	}
	
	function install() { 
		global $wpdb;
		$cutegallery_ver = get_option("cutegallery_ver");

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
		$table = "CREATE TABLE ".$wpdb->prefix."cutegallery (
		id int(11) NOT NULL AUTO_INCREMENT,
		name VARCHAR(128) DEFAULT NULL,
		description VARCHAR(256) DEFAULT NULL,
		date DATE NOT NULL DEFAULT '0000-00-00', 
		PRIMARY KEY  (id) 
		);";

		dbDelta($table);

		$table = "CREATE TABLE ".$wpdb->prefix."cutegallery_images (
		id int(11) NOT NULL AUTO_INCREMENT,
		gallery_id int(11) NOT NULL DEFAULT '0', 
		caption VARCHAR(256) DEFAULT NULL,
		image VARCHAR(128) DEFAULT NULL,
		PRIMARY KEY  (id), 
		KEY gallery_id (gallery_id)
		);";

		dbDelta($table);
		
		if(!$cutegallery_ver) { 
			add_option("cutegallery_ver", cutegallery_VER);
		}
	}
	
	function check_db_version() { 
		global $wpdb;
		$cutegallery_ver = get_option("cutegallery_ver");
		if($cutegallery_ver != cutegallery_VER) { 
			self::install();
			update_option("cutegallery_ver", cutegallery_VER);
		}
	}
	
	function display_gallery($args) { 
		global $wpdb; 
		$uploads = wp_upload_dir();
		$baseurl = $uploads['baseurl'];
		
		$per_row = $args['per_row'] ? $args['per_row'] : 0;
		$limit = $args['limit'] ? " LIMIT ".$args['limit']." " : '';
		$offset = $args['offset'] ? " OFFSET ".$args['offset']." " : '';
		$content = '';
		$gallery = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."cutegallery WHERE id='".$args['id']."'");
		if($gallery) { 
			$images = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."cutegallery_images WHERE gallery_id='".$args['id']."' ORDER BY id DESC".$limit.$offset);
			$num = 0;
			$content .= '<div style="clear: both"></div>';
			foreach($images as $img) { 
				$num++;
				$content .= '<div class="cutegallery_container'.($num == $per_row ? ' last' : '').'" style="'.cutegallery_CONT_CSS.'">';
				$content .= '<a href="'.$baseurl.'/images/'.$img->image.'" rel="'.esc_attr($gallery->name).'" title="'.esc_attr($img->caption).'" class="cutegallery-fancybox">';
				$content .= '<img alt="" src="'.$baseurl.'/images/thumbs/'.$img->image.'" width="'.cutegallery_THUMB_WIDTH.'" height="'.cutegallery_THUMB_HEIGHT.'"></a>';
				$content .= '</div>';
				$num = $num == $per_row ? 0 : $num;
			}
			$content .= '<div style="clear: both"></div>';
		} 
		return $content;
	}
	
}

register_activation_hook(__FILE__,array('BizarskiCuteGallery', 'install'));
add_action('init', array('BizarskiCuteGallery', 'init'));
add_action('plugins_loaded', array('BizarskiCuteGallery', 'check_db_version'));
add_action('admin_menu', array('BizarskiCuteGallery', 'admin_actions') );
add_shortcode('cutegallery_show', array('BizarskiCuteGallery', 'display_gallery'));