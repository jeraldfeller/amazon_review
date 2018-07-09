<?php 
	ini_set('display_startup_errors', 1);
	//ini_set('display_errors', 1);
	error_reporting(-1);
	include_once('./includes/functions.php');   
	//echo "<pre>";  
	
	if(isset($_POST['submit'])){ 
		if(isset($_POST['checkbox'])){
			if (is_array($_POST['checkbox'])) {
				foreach($_POST['checkbox'] as $value){
				 
				  $Slected_Array[] = json_decode(base64_decode($value),true);
				 // print_r($Slected_Array);
				}
			}else{
				$value = $_POST['checkbox'];
				$Slected_Array = json_decode(base64_decode($value),true);
				 // print_r($Slected_Array);
				
				 
			}
			include_once('downloadDataToFile.php');  
			 
		} 
	} 