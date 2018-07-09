<?php
	//ini_set('display_startup_errors', 1);
	//ini_set('display_errors', 1);
	//error_reporting(0); 
	//ini_set("memory_limit", "-1");
	echo "<pre>";
	require_once('includes/functions.php'); 
	//print_r($_FILES);
	if(isset($_FILES["file"])){
		if(isset($_FILES["file"])) $fileName = $_FILES["file"]; 
		$target_dir = "uploads/";
		$target_file = $target_dir . basename($fileName["name"]);
		//print_r($target_file);
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
				echo "Sorry, there was an error uploading your file.";
			}
		}
	} 
	$target_file = $fileName["tmp_name"];
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
							$OrderArray["ean"] = $result[10];
							   print_r($OrderArray);
							//echo "data uploaded upto UPC =".$single[3];
							  insertDBField("john2",$OrderArray);
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
				
				//print_r($single);
				if($count > 0 && !empty($single)){  
					//$OrderArray["sku"] = $single[0];
					$OrderArray["ean"] = $single[10];
					 print_r($OrderArray);
					//echo "data uploaded upto UPC =".$single[3];
					insertDBField("john2",$OrderArray); 
				} 
				$count++;
			}  
			fclose($file);
		}
		echo "  data updation to DataBase is completed successfully";
		//echo'<meta http-equiv="refresh" content="1; url=Amazon_Review_Product.php">'; 
	} 
?> 
			 
		