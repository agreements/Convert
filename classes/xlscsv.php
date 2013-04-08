<?php
	class xlscsv {
		public function __construct($format_res, $flname) {
			/** PHPExcel_IOFactory */
			include 'classes/PHPExcel/IOFactory.php';

			$this->inputFileType = 'Excel5';
			$this->inputFileName = 'Original/'.$format_res;

			$this->objReader = PHPExcel_IOFactory::createReader($this->inputFileType);
			$this->objPHPExcelReader = $this->objReader->load($this->inputFileName);

			$this->loadedSheetNames = $this->objPHPExcelReader->getSheetNames();

			$this->objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcelReader, 'CSV');

			foreach($this->loadedSheetNames as $this->sheetIndex => $this->loadedSheetName) {
				$this->objWriter->setSheetIndex($this->sheetIndex);
				$this->objWriter->save('Recode/'.$flname.'.csv');

			}
		}
	}
?>