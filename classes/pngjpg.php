<?php
	Class PngJpg {
		public $image;
		public function __construct($format_res, $flname) {
			$this->image = imagecreatefrompng('Original/'.$format_res);
			$this->imagealphaColor($this->image, imagecolorallocatealpha($this->image, 255, 255, 255, 0));
			imagesavealpha($this->image, false);
			imagejpeg($this->image, 'Recode/'.$flname.'.jpg', 100);
			imagedestroy($this->image);
		}

		public function imagealphaColor($image, $_transparent_color) {
			set_time_limit(0);
			if(empty($_transparent_color))
				$_transparent_color = imagecolorallocatealpha( $image , 255, 255, 255, 0 );
			$_tc = imagecolorsforindex($image, $_transparent_color);
			imagealphablending($image, false);
			for($x = 0; $x < imagesx($image); $x++)
				for($y = 0; $y < imagesy($image); $y++){
					$color = imagecolorat($image, $x, $y);
					if($color != $_transparent_color){
						$c = imagecolorsforindex($image, $color);
						foreach($c as $ci => $v)
							$$ci = & $c[$ci];
						if(!isset($alpha));
						elseif($alpha == 127){
							imagefill($image, $x, $y, $_transparent_color);
						}elseif($alpha > 0){
							foreach($c as $ci => $v)
								$$ci = round(
									(
										$_tc[$ci] * $alpha + (
											($ci == 'alpha')? 
												0 : 
												$$ci * (127 - $alpha)
											)
									) / 127
								);
							$new_color = imagecolorallocatealpha( $image , $red , $green , $blue, $alpha );
							imagefill($image, $x, $y, $new_color);
						}
					}
				}
			imagesavealpha($image, true);
		}

		public function __destruct() {
			unset($this);
		}
	}
?>