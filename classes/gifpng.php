<?php
	Class GifPng {
		var $image;
		public function __construct($format_res, $flname) {
			$this->image = imagecreatefromgif('Original/'.$format_res);
			imagepng($this->image, 'Recode/'.$flname.'.png', 9);
			imagedestroy($this->image);
		}
		public function __destruct() {
			unset($this);
		}
	}
?>