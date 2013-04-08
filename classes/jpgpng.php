<?php
	Class JpgPng {
		var $image;
		public function __construct($format_res, $flname) {
			$this->image = imagecreatefromjpeg('Original/'.$format_res);
			imagepng($this->image, 'Recode/'.$flname.'.png', 9);
			imagedestroy($this->image);
		}
		public function __destruct() {
			unset($this);
		}
	}
?>