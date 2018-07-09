<?php  
	//echo "<pre>";
	require_once('../includes/functions.php');  
	$values = selectDBField("inorganic");
	//print_r($values);
	if($values){
		$path =  realpath(dirname(__FILE__)); 
		//print_r($path);
		$filefolder = "/downloads/";
		if (!file_exists($path.$filefolder)) { 
		 	 mkdir($path.$filefolder);
		 
		}
		$date =date('m-d-Y_hia').'.csv'; 
		//$file = $path.$filefolder.$date;
		$file = $path.$filefolder.'inorganic.csv';
		if($values){
			$fp = fopen($file,'w');
			$count= 0;
			foreach($values as $value){
				$newid =$value["id"];
				 unset($value["id"]); 
				 unset($value["status"]);
				  
				  
				//$value['amazonLink'] = '=HYPERLINK("'.$value['amazonLink'].'")';
				 //unset($value["link"]); 
				if($fp){
					if($count==0){ 
						$heading = array_keys($value);
						fputcsv($fp, $heading);
						fputcsv($fp, $value);
						$download["status"] = 2;
						//updateDBField("id",$newid,"testOrderAmazon",$download);
					}else{
						fputcsv($fp, $value);
						$download["status"] = 2;
						//updateDBField("id",$newid,"testOrderAmazon",$download);
				
					} 
				}
				$count++;
			}
			fclose($fp);
			downloadFile($file); 
			 
		}else{
			echo "process of downloading data to file is done";
			 
		} 
	}else{
		echo "No More  Data please submit the csv Data file";
	}
	 
?>