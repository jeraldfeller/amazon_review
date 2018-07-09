<?php 
	//echo "<pre>";
	//include_once('./includes/functions.php');  
	//$result = selectDBFieldWhereIn("upc","status",291,"UPC,ASIN");
	$file = date('m-d-Y_hia').'.csv';
	//print_r($result);
	if($Slected_Array){
		$fp = fopen($file,'w'); 
		if(!empty($Slected_Array))
		{
			$count = 0;
			foreach ( $Slected_Array as $result)
			{
				unset($result["status"]);
				unset($result["checkBox"]);
				//print_r($result);
				appendcsv($result,$fp,$count);	
				$count++;
			}
			 
		}
		fclose($fp);  
		downloadFile($file);
	}else{
		echo "process of downloading data to file is done";
		
	}
	
	
?>