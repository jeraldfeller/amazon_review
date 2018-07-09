<?php  
	//echo "<pre>";
	 
	 ini_set('display_startup_errors', -1);
	    
	ini_set("memory_limit", "-1");
	require_once('../includes/functions.php'); 
	 
	if(isset($_GET["marketplace"])){
		if($_GET["marketplace"] == 'it'){
		$table = 'inorganic'; 
		}elseif($_GET["marketplace"] == 'co.uk'){
		$table = 'inorganic_uk'; 
		}elseif($_GET["marketplace"] == 'de'){
		$table = 'inorganic_de'; 
		}elseif($_GET["marketplace"] == 'fr'){
		$table = 'inorganic_fr'; 
		}
		 
		
	} // $table = 'inorganic'; 
	$values = selectDBField($table);
	 // print_r($values);exit;
	if($values){
		$path =  realpath(dirname(__FILE__)); 
		//print_r($path);
		$filefolder = "/downloads/".$_GET["folder"];
		if (!file_exists($path.$filefolder)) { 
		 	 mkdir($path.$filefolder);
		 
		}
		$date =date('m-d-Y_hia').'.csv'; 
		$file = $path.$filefolder.$date;
		$file = '/var/www/html/Amazon_review/inorganic/scraper/downloads/inorganic.csv';
		if($values){
			$fp = fopen($file,'w');
			//$fp = fopen(,'w');
			$count= 0;
			foreach($values as $value){
				$newid =$value["id"];
				 unset($value["id"]); 
				 unset($value["status"]);
				 $value = array_map("utf8_decode", $value);
				// unset($value["pageNo"]); 
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
		echo "No More Data please submit the csv Data file";
	}
	 
?>