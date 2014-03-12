<?php
	//for converting tga to png or jpg
	ini_set("memory_limit", "150M"); 
?>

<?php
	/*
		@name: Convert
		@author: Rune Boege
		@discribe: converter for multiple formats
		@version: 1.4.4
	*/
	
	//file downloading after saving in "Convert" folder
	function download($file) {
		if(file_exists($file)) {
		    if(ob_get_level()) {
		      ob_end_clean();
		    }
		    
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename=' . basename($file));
		    header('Content-Transfer-Encoding: binary');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($file));
		
		    readfile($file); 
		    
	 	}
	}

	/*
		MAIN
	*/ 	

	if(count($_POST) && isset($_POST['submit'])) {	
		
		if(!empty($_POST['type'])) {

			//upload file to the server
			if(!empty($_FILES['files']['tmp_name'])) {
				$dir = 'Original/';
				$upload_dir = $dir . basename($_FILES['files']['name']);
				$upload_dir = str_replace(" ", "", $upload_dir);
				if(copy($_FILES['files']['tmp_name'], $upload_dir)) {
					$flag = true;
				} else {
					$flag = false;
				}
			} 

			//get file format
			$str[] = $_FILES['files']['name'];
			$str = htmlspecialchars(implode('', $str));
			$str_arr = explode('.', $str);
			$result[] = $str_arr[count($str_arr)-1];
			$result = implode('', $result);
			$result = strtolower($result);
			$result = str_replace(" ", "", $result);
			//$result = trim($result);

			//get file name with dots
			$st[] = $_FILES['files']['name'];
			$st = htmlspecialchars(implode('', $st));
			$str_arr = explode('.', $st);
			$i = 0;
			$flname = '';
			while($i < count($str_arr)-1) {
				if($i == count($str_arr)-2) {
					$flname .= $str_arr[$i];
				} else {
					$flname .= $str_arr[$i].'.';
				}
				$i++;
			}

			$flname = str_replace(" ", "", $flname);
			//array with extensions
			$arr = array();
			$arr['doc'] = array('txt', 'docx', 'rtf', 'pdf', 'html');
			$arr['docx'] = array('txt', 'doc', 'rtf', 'pdf', 'html');
			$arr['rtf'] = array('doc', 'docx', 'txt', 'html', 'pdf');
			$arr['xlsx'] = array('html', 'csv');
			$arr['xls'] = array('html', 'csv');
			$arr['gif'] = array('jpg', 'png');
			$arr['jpg'] = array('gif', 'png');
			$arr['jpeg'] = array('gif', 'png');
			$arr['png'] = array('gif', 'jpg');
			$arr['tga'] = array('jpg', 'png');
			$arr['txt'] = array('csv');
			
			//format check
			if($flag && array_key_exists($result, $arr)) {
				$from = trim($result);
				$to = $_POST['type'];
				$format_res = $flname . '.' . $from;
				$class = $from . $to;

				//add classes from "classes" folder 
				if(require_once('classes/' . $class . '.php')) {
					$fileclass = new $class($format_res, $flname);

					//use our function to save the file
					download('Recode/' . $flname . '.' . $to);

					//delete Original folder
					if(file_exists('Original/' . $format_res)) 
						unlink('Original/' . $format_res);

					//clear Recode folder
					if(file_exists('Recode/'.$flname . '.' . $to)) 
						unlink('Recode/'.$flname . '.' . $to);
					exit();
				}	
			}
		}
	}	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Convert</title>
	<link href="style/style.css" rel="stylesheet">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<!--works faster becouse file is cached-->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery-1.8.1.js"></script>
	<script type="text/javascript">
		//extensions select
		$('.activated').live('click', function() {
			$('.type_selected').removeClass('type_selected');
		 	$('.input_type[value="' + $(this).val() + '"]').prop('checked',true);
			$(this).addClass('type_selected');
		});

	</script>
	<script type="text/javascript">

		$(document).ready(function() {
			var filename;
			var extensions = [];
			var extension;
			//array with our extensions
			extensions['doc'] = ['txt', 'docx', 'rtf', 'pdf', 'html'];
			extensions['docx'] = ['txt', 'doc', 'rtf', 'pdf', 'html'];
			extensions['rtf'] = ['doc', 'docx', 'txt', 'html', 'pdf'];
			extensions['xlsx'] = ['html', 'csv'];
			extensions['xls'] = ['html', 'csv'];
			extensions['gif'] = ['jpg', 'png'];
			extensions['jpg'] = ['gif', 'png'];
			extensions['jpeg'] = ['gif', 'png'];
			extensions['png'] = ['jpg', 'gif'];
			extensions['tga'] = ['jpg', 'png'];
			extensions['txt'] = ['csv'];
			$('input[type="file"]').change(function(e) {
				// Deselect the error message \ successful recoding
				$('.type_selected').removeClass('type_selected');
				$("#message").removeClass("visible").addClass("hidden");
				//get file name and extention
				var filepath = e.target.value.split('\\');
				filename = filepath[filepath.length-1].split('.');
				extension = filename[filename.length-1];
				$('.file_type').not('.deactivated').removeClass('activated').addClass('deactivated');
				//show possible extensions
				if(extensions[extension.toLowerCase()] !== undefined) {
					$.each(extensions[extension.toLowerCase()], function(k,v) {
						$('.' + v).removeClass('deactivated').addClass('activated');
					});
				}
				//show tick 
				$("#validation").css({
					"background": "url('img/true.png') no-repeat"
				});
				//show cross
				if(filename.length == 1) {
					$("#validation").css({
						"background-image": "url('img/false.png')"
					});
				}
			});
			//submit event
			$('.submit').click(function() {
				var text;
				//message about the wrong extension
				if(filename === undefined) {
					$("#message").removeClass("hidden").addClass("visible");
					$("#message").css({
						"border": "2px solid #9c3232",
						"background-color": "#d59e9e"
					});
					text = "<center>Your file is not loaded!</center>";
					$("#message").html(text);
					$('.file_type').not('.deactivated').removeClass('activated').addClass('deactivated');
					return false;
				}
				//message that there is no extension 
				if($('input[type="radio"]:checked').length==0) {
					$("#message").removeClass("hidden").addClass("visible");
					$("#message").css({
						"border": "2px solid #9c3232",
						"background-color": "#d59e9e"
					});
					text = "<center>You have to select the extension!</center>";
					$("#message").html(text);
					return false;	
				}
				//message about successful conversion
				if(filename !== undefined && $('input[type="radio"]:checked').length>0) {
					$("#message").css({
						"border": "2px solid #2e8856",
						"background-color": "#5abd68"
					})
					$("#message").removeClass("hidden").addClass("visible");
					text = "<center>This is demo version! Click <a href='http://codecanyon.net/item/Convert-converter/4235718?sso?WT.ac=category_item&WT.seg_1=category_item&WT.z_author=Polyakov_I'>here</a> to purchase</center>";
					$("#message").html(text);
					$('.type_selected').removeClass('type_selected');
					$.each(extensions[extension.toLowerCase()], function(k,v) {
						$('.' + v).removeClass('activated').addClass('deactivated');
					});
					$("#validation").css({
						"background-image": "url('img/false.png')"
					});

					setTimeout(function(){
						$('input[type="file"]').val('');
					}, 3000);
					
					return true;
				}
			});

		});	
	</script>
</head>
<body>
	<div class="hole_wrap">
		<!--WRAP CONTENT-->
		<div id="content_wrap">

			<!--BEGIN CONTENT-->
			<div id="content">

				<!--HEADER-->
				<div id="header">
						<div id="block" class="f_l">
							<div id="head_text"><h1>Convert</h1></div>
							<div id="text"><p>convert files in three clicks!</p></div>
						</div>
					<div id="logo" class="f_r"></div>
					<div class="clearfix"></div>
				</div>
				<!--END HEADER-->
				<!--FORM BEGIN-->	
				<form class="form" name="f" method="POST" enctype="multipart/form-data" target="_blank">
					<div id="loading_block" class="f_l">
					
						<div id="head_text"><h1><center>1. Load file to convert:</center></h1></div>
						<!-- file load -->
						<div class="upload_b f_l">
							<input id="loading_f" class="files_load" type="file" name="files" size="10">
						</div>
						<div id="validation" class="f_r"></div>
						<div class="clearfix"></div>
						<div class="gr_line"></div>
						<div id="head_text"><h1><center>2. Select an extension you need:</center></h1></div>
						<!-- radios -->
						<div class="formats f_l">
							<input class="input_type" type="radio" name="type" value="doc">
							<input class="input_type" type="radio" name="type" value="docx">
							<input class="input_type" type="radio" name="type" value="rtf">
							<input class="input_type" type="radio" name="type" value="xls">
							<input class="input_type" type="radio" name="type" value="xlsx">
							<input class="input_type" type="radio" name="type" value="csv">	
							<input class="input_type" type="radio" name="type" value="pdf">	
							<input class="input_type" type="radio" name="type" value="txt">	
							<input class="input_type" type="radio" name="type" value="html">
							<input class="input_type" type="radio" name="type" value="gif">
							<input class="input_type" type="radio" name="type" value="jpg">
							<input class="input_type" type="radio" name="type" value="png">
							<!-- buttons -->
							<button class="file_type doc deactivated" type="button" value="doc" ></button>
							<button class="file_type docx deactivated" type="button" value="docx"></button>
							<button class="file_type rtf deactivated" type="button" value="rtf"></button>
							<button class="file_type xls deactivated" type="button" value="xls"></button>
							<button class="file_type xlsx deactivated" type="button" value="xlsx"></button>
							<button class="file_type csv deactivated" type="button" value="csv"></button>
							<button class="file_type pdf deactivated" type="button" value="pdf"></button>
							<button class="file_type txt deactivated" type="button" value="txt"></button>
							<button class="file_type html deactivated" type="button" value="html"></button>
							<button class="file_type gif deactivated" type="button" value="gif"></button>
							<button class="file_type jpg deactivated" type="button" value="jpg"></button>
							<button class="file_type png deactivated" type="button" value="png"></button>
						</div>
						<div class="clearfix"></div>
						<!-- exec -->
						<div class="gr_line"></div>
						<div id="head_text"><h1><center>3. Convert !</center></h1></div>
						<input class="submit f_l" id="loader" type="submit" name="submit" value="Convert">
						<div id="message" class="hidden f_l"><center></center></div>
					</div>
					<div class="clearfix"></div>
				</form>
				<!--END OF FORM-->
			</div>
			<!--END OF CONTENT-->
		</div>
		<!--END OF CONTENT WRAP-->
	</div>
</body>
</html>