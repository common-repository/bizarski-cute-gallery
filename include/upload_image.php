<?PHP

function upload_image($id) {

	global $admin;

	$uploads = wp_upload_dir();
	$upload_base = $uploads['basedir'];

	$storepath = $upload_base.cutegallery_DIRS."images".cutegallery_DIRS;
	if(!file_exists($storepath)) {
		mkdir($storepath, 0777);
	} 
	$thumbpath = $upload_base.cutegallery_DIRS."images".cutegallery_DIRS."thumbs".cutegallery_DIRS;
	if(!file_exists($thumbpath)) {
		mkdir($thumbpath, 0777);
	} 
	$sidepath = $upload_base.cutegallery_DIRS."images".cutegallery_DIRS."side".cutegallery_DIRS;
	if(!file_exists($sidepath)) {
		mkdir($sidepath, 0777);
	} 

	$ext_type = "jpg";
	
	$success = array();
	$errors = "";

	$getExt = explode ('.', $file_name);
	$file_ext = $getExt[count($getExt)-1];

	$file_name = $_FILES[$id]["name"];
	if (strlen($file_name) > 107) {
		$file_name = substr($file_name, 0, 103) . $file_ext; 
	}
	$file_name = str_replace(' ','_',$file_name);
	$file_name =  substr($file_name, 0, strlen($file_name) - 4) .".".$ext_type;

	$file_type = $_FILES[$id]['type'];
	$limited = array("image/gif","image/jpeg","image/pjpeg","image/png", "image/x-png");
	if (in_array($file_type, $limited) && ($_FILES[$id]['size'] < 9242880)) {
		if ($_FILES[$id]['error'] > 0) {
			$errors .= "Return Code: " . $_FILES[$id]['error'] . "<br />";
		} else {

			if (file_exists($storepath . $file_name)) {
				for($i = 0; file_exists($storepath . $file_name); $i++) {
					if(strlen($file_name) < 104) {
						if(is_numeric(substr($file_name, -5, 1))) {
							$newnum = substr($file_name, -5, 1) + 1;
							$file_name = substr($file_name, 0, strlen($file_name) - 5) . $newnum . ".".$ext_type; 
						} else { 
							$file_name = substr($file_name, 0, strlen($file_name) - 4) . $i . ".".$ext_type;
						}
					} else { 
						if(is_numeric(substr($file_name, -5, 1))) {
							$newnum = substr($file_name, -5, 1) + 1;
							$file_name = substr($file_name, 0, strlen($file_name) - 5) . $newnum . ".".$ext_type; 
						} else { 
							$file_name = substr($file_name, 0, strlen($file_name) - 7) . $i . ".".$ext_type;
						}
					}
				}
			}

			$imgname = $storepath . $file_name;
			$image = Image::new_from_post($id, 1024, 768);
			$image->resize(1,0);
			$image->save_as($imgname, $ext_type, 9);
			
			$imgname = $thumbpath . $file_name;
			$image = Image::new_from_post($id, cutegallery_THUMB_WIDTH, cutegallery_THUMB_HEIGHT);
			$image->resize_crop();
			$image->save_as($imgname, $ext_type, 9);
			
			$imgname = $sidepath . $file_name;
			$image = Image::new_from_post($id, cutegallery_SIDE_WIDTH, cutegallery_SIDE_HEIGHT);
			$image->resize_crop();
			$image->save_as($imgname, $ext_type, 9);
			
			unset($image);


			if(file_exists($imgname)) { 
				array_push($success, $file_name);
			}
		}
	} else {
	   $errors .= "Invalid file<br />";
	}
	
	if(count($success) == 1) {
		return $success;
	} else {
		return $errors;
	}
	
}

?>