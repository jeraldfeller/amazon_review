<?php  
	//echo "<pre>";
	ini_set("memory_limit", "-1");
	require_once('includes/functions.php');  
	$values = selectDBField("john2");
	//print_r($values);
	if($values){
		$path =  realpath(dirname(__FILE__)); 
		//print_r($path);
		$filefolder = "/downloads/".$_GET["folder"]."/";
		if (!file_exists($path.$filefolder)) { 
		 	 mkdir($path.$filefolder);
		 
		}
		$date =date('m-d-Y_hia').'.csv'; 
		$file = $path.$filefolder.$date;
		if($values){
			$fp = fopen($file,'w');
			$count= 0;
			foreach($values as $value){
				$newid =$value["id"];
				 unset($value["id"]); 
				 unset($value["status"]);
				 unset($value["packagedimensions_height"]);
				 unset($value["packagedimensions_length"]);
				 unset($value["packagedimensions_width"]);
				 unset($value["packagedimensions_weight"]);
				 unset($value["itemdimensions_width"]);
				 unset($value["itemdimensions_length"]);
				 unset($value["itemdimensions_height"]);
				 unset($value["itemdimensions_weight"]);
				 unset($value["Item_Weight"]);
				 unset($value["Dimensions"]);
				 unset($value["shipping_weight"]);
				 unset($value["ProductDimensions"]);
				 unset($value["status1"]);
				$value["Weblink"]='https://www.amazon.co.uk/dp/'.$value["asin"]; 
				if($value["packagedimensions_height"]){
					$value["packagedimensions_height"] =$value["packagedimensions_height"]/100;
				}
				 if($value["packagedimensions_length"] ){
					 $value["packagedimensions_length"]  =$value["packagedimensions_length"] /100;
				 }
				if($value["packagedimensions_width"] ){
					$value["packagedimensions_width"]  =$value["packagedimensions_width"] /100;
				}
				if($value["packagedimensions_weight"] ){
					$value["packagedimensions_weight"]  =$value["packagedimensions_weight"] /100;
				}				
				 if($value["itemdimensions_height"] ){
					 $value["itemdimensions_height"] =$value["itemdimensions_height"] /100;
				 }
				 if($value["itemdimensions_length"] ){
					 $value["itemdimensions_length"] =$value["itemdimensions_length"] /100;
				 }
				if($value["itemdimensions_width"] ){
					
					$value["itemdimensions_width"] =$value["itemdimensions_width"] /100;
				}
				
				if($value["itemdimensions_weight"] ){
					
					$value["itemdimensions_weight"] =$value["itemdimensions_weight"] /100;
				}
							
				
				
				
				
				
				
				
				// unset($value["pageNo"]);
				//$value['amazonLink'] ='https://www.amazon.co.uk/dp/'.$value["asin"];   
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
				unset($value);
			}
			fclose($fp);
			downloadFile($file); 
			 
		}else{
			echo "process of downloading data to file is done";
			 
		} 
	}else{
		echo "No More ASIN Data please submit the csv Data file";
	}
	 
?>