<?php
	class txtcsv {
		public function __construct($format_res, $flname) {
			$this->Convert('Original/'.$format_res, 'Recode/'.$flname.'.csv');
		}
		
		public function __destruct() {
			unset($this);
		}

		public function Convert($firstpath, $secondpath) {
			chmod($firstpath, 0777);
			$text = file_get_contents($firstpath);
			file_put_contents($secondpath, $text);
		}
	}
?>