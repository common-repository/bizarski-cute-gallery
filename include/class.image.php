<?php

class Image {
	private $width;
	private $height; 
	private $r_width;
	private $r_height; 
	private $image; 
	private $file;
	private $resource; 
	private $r=0;
	private $g=0;
	private $b=0;
	
	public static function new_from_post($file, $width=0, $height=0, $num=false) { 
		$object = new self();
		if($width && $height) {
			$object->width = $width;
			$object->height = $height;
			$object->image = imagecreatetruecolor($width, $height);
		}
		if(is_int($num)) {
			list($r_width, $r_height) = getimagesize($_FILES[$file]['tmp_name'][$num]);
			$object->r_width = $r_width;
			$object->r_height = $r_height;
			$file_type = $_FILES[$file]['type'][$num];
			if($file_type == "image/pjpeg" || $file_type == "image/jpeg"){
				$object->resource = imagecreatefromjpeg($_FILES[$file]['tmp_name'][$num]);
			} else if($file_type == "image/x-png" || $file_type == "image/png"){
				$object->resource = imagecreatefrompng($_FILES[$file]['tmp_name'][$num]);
			} else if($file_type == "image/gif"){
				$object->resource = imagecreatefromgif($_FILES[$file]['tmp_name'][$num]);
			}
		} else {
			list($r_width, $r_height) = getimagesize($_FILES[$file]['tmp_name']);
			$object->r_width = $r_width;
			$object->r_height = $r_height;
			$file_type = $_FILES[$file]['type'];
			if($file_type == "image/pjpeg" || $file_type == "image/jpeg"){
				$object->resource = imagecreatefromjpeg($_FILES[$file]['tmp_name']);
			} else if($file_type == "image/x-png" || $file_type == "image/png"){
				$object->resource = imagecreatefrompng($_FILES[$file]['tmp_name']);
			} else if($file_type == "image/gif"){
				$object->resource = imagecreatefromgif($_FILES[$file]['tmp_name']);
			}
		}
		return $object;
	}
	
	public static function new_from_file($resource, $ext, $width=0, $height=0) {
		$object = new self(); 
		if($width && $height) {
			$object->width = $width;
			$object->height = $height;
			$object->image = imagecreatetruecolor($width, $height);
		}
		list($r_width, $r_height) = getimagesize($resource);
		$object->r_width = $r_width;
		$object->r_height = $r_height;
		if($ext == "jpg" || $ext == "jpeg"){
			$object->resource = imagecreatefromjpeg($resource);
		} else if($ext == "png"){
			$object->resource = imagecreatefrompng($resource);
		} else if($ext == "gif"){
			$object->resource = imagecreatefromgif($resource);
		}
		return $object;
	}
	
	public function save_as($path, $type="png", $quality=9) {
		if(!$this->width && !$this->height) {
			$this->image = imagecreatetruecolor($this->r_width, $this->r_height);
			imagecopyresampled($this->image, $this->resource, 0, 0, 0, 0, $this->r_width, $this->r_height, $this->r_width, $this->r_height);
		}
		if($type == "png") {
			ImagePng($this->image, $path, $quality);
		} else if($type == "jpg") {
			if($quality < 10) { 
				$quality = $quality*10;
			}
			ImageJpeg($this->image, $path, $quality);
		} else if($type == "gif") {
			if($quality < 10) { 
				$quality = $quality*10;
			}
			ImageGif($this->image, $path, $quality);
		}
	}
	
	public function resize_fill() {
		$imgratio = $this->r_width / $this->r_height;

		if($this->r_width > $this->width || $this->r_height > $this->height) {
			$correct = $this->width/$imgratio;
			if($correct > $this->height) {
				$thumbwidth = $this->height*$imgratio;
				$thumbheight = $this->height;
			} else {
				$thumbwidth = $this->width;
				$thumbheight = $correct;
			}
		} else {
			$thumbwidth = $this->r_width; $thumbheight = $this->r_height;
		}

		$thumb_img = imagecreatetruecolor($this->width,$this->height);
		if(isset($this->r) && isset($this->g) && isset($this->b)) {
			$background = imagecolorallocate($this->resource, $this->r, $this->g, $this->b);
			imagefill($thumb_img, 0, 0, $background);
		} else {
			imagealphablending($thumb_img, false);
			imagesavealpha($thumb_img, true);
			$background = imagecolorallocatealpha($thumb_img, 255, 255, 255, 127);
			imagefilledrectangle($thumb_img, 0,  0, $this->width, $this->height,  $background);
		}

		if($thumbheight < $this->height) {
			$top = ($this->height-$thumbheight)/2;
		} else {
			$top = 0;
		}
		if($thumbwidth < $this->width) {
			$left = ($this->width-$thumbwidth)/2;
		} else {
			$left = 0;
		}
		imagecopyresampled($thumb_img, $this->resource, $left, $top, 0, 0, $thumbwidth, $thumbheight, $this->r_width, $this->r_height);
		$this->image = $thumb_img;
	}
	
	private function resize_aspect($overflow=0) {
		$imgratio = $this->r_width / $this->r_height;
		if($imgratio >= 1) {
			if($this->r_width > $this->width || $this->r_height > $this->height) {
				$correct = $this->width/$imgratio;
				if(!$overflow && $correct > $this->height) {
					$newwidth = $this->height*$imgratio;
					$newheight = $this->height;
				} else if($overflow && $correct < $this->height) {
					$newheight = $this->height; 
					$newwidth = $newheight*$imgratio;
				} else {
					$newwidth = $this->width;
					$newheight = $correct;
				}
			} else {
				$newwidth = $this->r_width; $newheight = $this->r_height;
			}
		} else {
			if($this->r_width > $this->width || $this->r_height > $this->height) {
				$correct = $this->height*$imgratio;
				if(!$overflow && $correct > $this->width) {
					$newheight = $this->width;
					$newwidth = $this->width*$imgratio;
				} else if($overflow && $correct < $this->width) {
					$newwidth = $this->width; 
					$newheight = $newwidth/$imgratio;
				} else {
					$newheight = $this->height;
					$newwidth = $correct;
				}
			} else {
				$newwidth = $this->r_width; $newheight = $this->r_height;
			}
		}
		return array($newwidth, $newheight);
	}
	
	public function resize_crop() {
		$imgratio = $this->r_width / $this->r_height;
		list($newwidth, $newheight) = $this->resize_aspect(1);
		$resized_img = imagecreatetruecolor($newwidth,$newheight);
		$background = imagecolorallocatealpha($resized_img, 255, 255, 255, 127);
		imagefilledrectangle($resized_img, 0,  0, $newwidth, $newheight,  $background);
		imagealphablending($resized_img, false);
		imagesavealpha($resized_img, true);
		
		imagecopyresampled($resized_img, $this->resource, 0, 0, 0, 0, $newwidth, $newheight, $this->r_width, $this->r_height);
		
		if($newheight > $this->height) {
			$top = ($this->height-$newheight)/2;
		} else {
			$top = 0;
		}
		if($newwidth > $this->width) {
			$left = ($this->width-$newwidth)/2;
		} else {
			$left = 0;
		}
		
		imagecopy($this->image, $resized_img, $left, $top, 0, 0, $newwidth, $newheight);
		ImageDestroy($resized_img);
	}
	
	public function resize($keep_aspect=1, $overflow=1) { 
		if($keep_aspect) {
			list($newwidth, $newheight) = $this->resize_aspect($overflow);
		} else {
			$newwidth = $this->width; 
			$newheight = $this->height; 
		}
		$resized_img = imagecreatetruecolor($newwidth,$newheight);
		$background = imagecolorallocate($resized_img, $this->r, $this->g, $this->b);
		imagecolortransparent($resized_img, $background);
		imagealphablending($resized_img, false);
		imagesavealpha($resized_img, true);
		
		imagecopyresampled($resized_img, $this->resource, 0, 0, 0, 0, $newwidth, $newheight, $this->r_width, $this->r_height);
		$this->image = $resized_img;
	}
	
	public function watermark($mark_path, $color="255,255,255") {
		$c = explode(",",$color);
		$bgimg = imagecreatetruecolor($this->width, $this->height); 
		$background = imagecolorallocate($bgimg, $c[0], $c[1], $c[2]);
		imagefill($bgimg, 0, 0, $background);
		imagecopyresampled($bgimg, $this->image, 0, 0, 0, 0, $this->width, $this->height, $this->width, $this->height);  
		$this->image = $bgimg;
		$watermark = imagecreatefrompng($mark_path);  
		$background = imagecolorallocate($watermark, 0, 0, 0);
		imagecolortransparent($watermark, $background);
		imagealphablending($watermark, false);
		imagesavealpha($watermark, true);
		$watermark_width = imagesx($watermark);  
		$watermark_height = imagesy($watermark);  
		$dest_x = ($this->width - $watermark_width)/2;  
		$dest_y = ($this->height - $watermark_height)/2;  
		imagecopyresampled($this->image, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, $watermark_width, $watermark_height);  
		imagedestroy($watermark); 
	}
	
	public function add_text($text) { 
		global $user;
		$font = 'inc/arial.ttf';
		$white = ImageColorAllocate($this->image, 255, 255, 255); 
		$grey = ImageColorAllocate($this->image, 130, 130, 130); 
		// Add some shadow to the text
		imagettftext($this->image, 10, 0, 11, 271, $grey, $font, $text);
		// Add the text
		imagettftext($this->image, 10, 0, 10, 270, $white, $font, $text);
	}
	
	public function fill($r, $g, $b) {
		$this->r = $r;
		$this->g = $g;
		$this->b = $b;
	}

}

?>
