<?php  
	//echo "<pre>";
	 
	//ini_set('display_startup_errors', -1);
	  // ini_set('display_errors', -1);
	ini_set("memory_limit", "-1");
	require_once('../includes/functions.php');  
	$values = selectDBField("amazonReview");
	// print_r($values);
	if($values){
		$path =  realpath(dirname(__FILE__)); 
		//print_r($path);
		$filefolder = "/downloads/".$_GET["folder"];
		if (!file_exists($path.$filefolder)) { 
		 	 mkdir($path.$filefolder);
		 
		}
		$date =date('m-d-Y_hia').'.csv'; 
		$file = $path.$filefolder.$date;
		$file = '/var/www/html/Amazon_review/review/scraper/downloads/review.csv';
		if($values){
			$fp = fopen($file,'w');
			//$fp = fopen(,'w');
			$count= 0;
			foreach($values as $value){
				$newid =$value["id"];
				 unset($value["id"]); 
				 unset($value["status"]);
				// unset($value["item_id"]);
				 unset($value["pageNo"]);
				// unset($value["total"]);
				// unset($value["pageNo"]);
				$value['amazonLink'] ='https://www.amazon.it/dp/'.$value["asin"];   
				$value['amazonLink'] = '=HYPERLINK("'.$value['amazonLink'].'")';
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
			//echo '<a href="'.$file.'" target="_blank"></a>';
			downloadFile($file); 
			 
		}else{
			echo "process of downloading data to file is done";
			 
		} 
	}else{
		echo "No More ASIN Data please submit the csv Data file";
	}
	 
?>