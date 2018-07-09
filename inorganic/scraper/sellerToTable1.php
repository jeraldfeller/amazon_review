<?php
	ini_set('display_startup_errors', 1);
	 ini_set('display_errors', 1);
	error_reporting(0);
	ini_set('post_max_size', '64M');
	ini_set('upload_max_filesize', '64M');
	echo "<pre>";
	require_once('../includes/functions.php');  
	if(isset($_POST["marketplace"])){
		if($_POST["marketplace"] == 'it'){
		$table = 'inorganic'; 
		}elseif($_POST["marketplace"] == 'co.uk'){
		$table = 'inorganic_uk'; 
		}elseif($_POST["marketplace"] == 'de'){
		$table = 'inorganic_de'; 
		}elseif($_POST["marketplace"] == 'fr'){
		$table = 'inorganic_fr'; 
		}
		 
		
	}	 //print_r($_FILES); exit;
	// print_r($table);exit;
	if(isset($_FILES["file"])){
		if(isset($_FILES["file"])) $fileName = $_FILES["file"]; 
		$target_dir = "uploads/";
		$target_file = $target_dir . basename($fileName["name"]);
		
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION); 
		 
		// Check file size
		if ($fileName["size"] > 5000000) {
		   // echo "Sorry, your file is too large.";
			$uploadOk = 1;
		}
		$allowedFileTypes = array("csv","CSV",'xlsx','XLSX',"txt");
		// Allow certain file formats
		if(!in_array($imageFileType, $allowedFileTypes) ) {
			echo "Sorry, only CSV or xlsx files are allowed.";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			//echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($fileName["tmp_name"], $target_file)) {
				echo "The file ". basename( $fileName["name"]). " has been uploaded.";
			} else {
				//echo "Sorry, there was an error uploading your file.";
			}
		}
	} 
	//print_r($fileName); 
	//$target_file = $fileName["tmp_name"];
	if($uploadOk != 0){
		if($imageFileType =='xlsx' or $imageFileType =='XLSX'){
			if ( $xlsx = SimpleXLSX::parse($target_file) ) {
				$results = $xlsx->rows();
				if($results){
					$count=0;
					foreach($results as $result){ 
						if($count > 0){
							if($result[0]){
							//$OrderArray["sku"] = $result[0];
							$OrderArray["keyword"] = $result[0];
							  //print_r($OrderArray);
							//echo "data uploaded upto UPC =".$single[3];
							  insertDBField($table,$OrderArray);
							}
						}
						$count++;
					} 
				}
					
			} else {
				echo SimpleXLSX::parse_error();
			} 
			
		}else{
			$file = fopen($target_file,'r');
			$count=0;
			while(! feof($file)) {
				$single = fgetcsv($file);
				$single[1] = iconv( "Windows-1252", "UTF-8", $single[1]);
				$single[2] = iconv( "Windows-1252", "UTF-8", $single[2]);
				$single[4] = iconv( "Windows-1252", "UTF-8", $single[4]);
				$single[5] = iconv( "Windows-1252", "UTF-8", $single[5]);
				//print_r($single);
				if($count >= 0 && !empty($single)){  
					//$OrderArray["sku"] = $single[0];
					$OrderArray["time"] = $single[0]; 
					$OrderArray["keyword"] = $single[1]; 
					$OrderArray["message"] = $single[2]; 
					$OrderArray["asin"] = $single[3]; 
					$OrderArray["brand"] = $single[4]; 
					$OrderArray["title"] = $single[5]; 
					$OrderArray["position"] = $single[6]; 
					//$OrderArray["locale"] = $_POST["marketplace"]; 
					 insertDBField($table,$OrderArray); 
				} 
				$count++;
			}  
			fclose($file);
		}
		echo "  data updation to DataBase is completed successfully";
		// echo'<meta http-equiv="refresh" content="1; url=Amazon_sponsored_Add.php">'; 
	} 
?> 
			 
		