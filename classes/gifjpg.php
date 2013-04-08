<?php
	Class GifJpg {
		var $image;
		public function __construct($format_res, $flname) {
			$this->image = imagecreatefromgif('Original/'.$format_res);
			imagejpeg($this->image, 'Recode/'.$flname.'.jpg', 100);
			imagedestroy($this->image);
		}
		public function __destruct() {
			unset($this);
		}
	}
?>