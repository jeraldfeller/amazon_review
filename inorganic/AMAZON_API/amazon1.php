<?php 
	ini_set('display_startup_errors', 1);
	ini_set('display_errors', 1);
	error_reporting(0);
	include_once('includes/functions.php');  
	echo "<pre>";
	$table ='inorganic';
	$table1 ='inorganic1';
	//$table1 ='UPCTOASIN3';
	$results = selectDBField($table,"status",'0',false,10); 
	print_r($results);
	if($results){
		foreach($results as $result){
			$id=$result["id"]; 
			if($result["asin"]){
				$UPCvalue[] =  sprintf("%010s",$result["asin"]);
			}else{
				$asin_Final['status'] = '3';
			  updateDBField("id",$result["id"],$table,$asin_Final);
			}			
			//$UPCvalue[10] =$result["SP_asin5"];
			 
		} 
		 echo 'ASIN updation done upto id  :'.$result["id"];
		$asinlist = implode(",",$UPCvalue);  
		//$asinlist = '0915811812';  
		if(!empty($asinlist)){ 
			 
				$response = apisearchLookup($asinlist);
				$xpath = convertToXpath($response);
				$items = $xpath->query("//item");
				$error = $xpath->query("//errors/error/code");
				if($error->length>=1){
					foreach($error as $erro){ 
						$error1 =$erro->nodeValue; 	
					}
					if($error1 =='AWS.InvalidParameterValue' ){
						$status["status"] = 14;  
					}else{
					$status["status"] = 29;  
					}
					updateDBField("id",$id,$table,$status);
				}
				  PRINT_r($response);
				 PRINT_r($items);
				if($items->length>=1){  
					$x =0;
					foreach($items as $item){ 
						$asin = $xpath->query(".//asin",$item)->item(0)->nodeValue; 
						$itemdetail['brand'] = $xpath->query(".//brand",$item)->item(0)->nodeValue;
						//$itemdetail['title'] = $xpath->query(".//title",$item)->item(0)->nodeValue;
						$itemdetail["status"] =3;
						updateDBField("asin",$asin,$table,$itemdetail);
							 
						unset($asin); 
						unset($itemdetail); 
					} 
				}  
		}    
		     echo'<meta http-equiv="refresh" content="1;">';
	}else{
		  function delete_column_data($field, $value, $table){  
			$db = MysqliDatabaseconnect();
			$db->query("UPDATE ".$table." SET ".$field." = ".$value);
			$db->execute();
			$db->close();
			return true; 
		}
		 
		delete_column_data('status',0,$table1);  
		echo 'data updation done successfully';
		 
	}