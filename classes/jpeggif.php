<?php
	Class Jpeggif {
		var $image;
		public function __construct($format_res, $flname) {
			$this->image = imagecreatefromjpeg('Original/'.$format_res);
			imagegif($this->image, 'Recode/'.$flname.'.gif', 100);
			imagedestroy($this->image);
		}
		public function __destruct() {
			unset($this);
		}
	}
?>