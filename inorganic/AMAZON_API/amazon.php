<?php 
	ini_set('display_startup_errors', 1);
	ini_set('display_errors', 1);
	error_reporting(0);
	include_once('includes/functions.php');  
	echo "<pre>";
	$table ='inorganic';
	//$table1 ='UPCTOASIN3';
	$results = selectDBField($table,"status",'1',false,1); 
	//print_r($results);
	if($results){
		foreach($results as $result){
			$id=$result["id"];
			//$asin_Final['asin'] =  sprintf("%013s",$result["asin"]);			
			// updateDBField("asin",$result["asin"],$table,$asin_Final);
			$UPCvalue[0] =$result["SP_asin0"];
			$UPCvalue[1] =$result["SP_asin1"];
			$UPCvalue[2] =$result["SP_asin2"];
			$UPCvalue[3] =$result["SP_asin3"];
			$UPCvalue[4] =$result["SP_asin4"];
			$UPCvalue[5] =$result["SP_asin5"];
			$UPCvalue[6] =$result["SP_asin6"];
			$UPCvalue[7] =$result["SP_asin7"];
			$UPCvalue[8] =$result["SP_asin8"];
			$UPCvalue[9] =$result["SP_asin9"];
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
				  //PRINT_r($response);
				 //PRINT_r($items);
				if($items->length>=1){  
					$x =0;
					foreach($items as $item){ 
						$asin = $xpath->query(".//asin",$item)->item(0)->nodeValue;
						//print_r($asin);
						foreach($result as $k=>$v){
							if($v == $asin){
								$br = preg_replace('/[^0-9.]/','',$k);
								$itemdetail['brand'.$br] = $xpath->query(".//brand",$item)->item(0)->nodeValue;
								$itemdetail['SP_title'.$br] = $xpath->query(".//title",$item)->item(0)->nodeValue;
								$itemdetail["status"] =2;
								updateDBField("id",$id,$table,$itemdetail);
							}
							
							
						}
						unset($asin);
						
						/*$itemdetail["isbn"] = $xpath->query(".//isbn",$item)->item(0)->nodeValue;
						$itemdetail["ean"] = $xpath->query(".//ean",$item)->item(0)->nodeValue;
						$itemdetail["title"] = $xpath->query(".//title",$item)->item(0)->nodeValue; 
						$itemdetail["Product_Page_Link"] ="https://www.amazon.com/dp/".$itemdetail["asin"];
						
						$itemdetail["Publisher"] = $xpath->query(".//publisher",$item)->item(0)->nodeValue;
						$itemdetail["manufacturer"] = $xpath->query(".//manufacturer",$item)->item(0)->nodeValue;
						$itemdetail["publicationdate"] = $xpath->query(".//publicationdate",$item)->item(0)->nodeValue;
						$itemdetail["Edition"] = $xpath->query(".//edition",$item)->item(0)->nodeValue;
						//$itemdetail["New_FBA_Price"] = $xpath->query(".//itemdimensions/weight",$item)->item(0)->nodeValue;
						//$itemdetail["total_FBA"] = $xpath->query(".//salesrank",$item)->item(0)->nodeValue;
						//$itemdetail["Avg_Review_Rating"] = $xpath->query(".//salesrank",$item)->item(0)->nodeValue;
						$itemdetail["numberofpages"] = $xpath->query(".//numberofpages",$item)->item(0)->nodeValue;
						$itemdetail["salesrank"] = $xpath->query(".//salesrank",$item)->item(0)->nodeValue;
						$itemdetail["New_MFN_Price"] = $xpath->query(".//lowestnewprice/formattedprice",$item)->item(0)->nodeValue;
						$itemdetail["packagedimensions_height"] = $xpath->query(".//packagedimensions/height",$item)->item(0)->nodeValue;
						$itemdetail["packagedimensions_length"] = $xpath->query(".//packagedimensions/length",$item)->item(0)->nodeValue;
						$itemdetail["packagedimensions_width"] = $xpath->query(".//packagedimensions/width",$item)->item(0)->nodeValue;
						$itemdetail["packagedimensions_weight"] = $xpath->query(".//packagedimensions/weight",$item)->item(0)->nodeValue;
						$itemdetail["itemdimensions_height"] = $xpath->query(".//itemdimensions/height",$item)->item(0)->nodeValue;
						$itemdetail["itemdimensions_length"] = $xpath->query(".//itemdimensions/length",$item)->item(0)->nodeValue;
						$itemdetail["itemdimensions_width"] = $xpath->query(".//itemdimensions/width",$item)->item(0)->nodeValue;
						$itemdetail["itemdimensions_weight"] = $xpath->query(".//itemdimensions/weight",$item)->item(0)->nodeValue;
						
						$itemdetail["label"] = $xpath->query(".//label",$item)->item(0)->nodeValue;
						$itemdetail["buybox_price"] = $xpath->query(".//offers//price/formattedprice",$item)->item(0)->nodeValue;
						$itemdetail["totalnew"] = $xpath->query(".//totalnew",$item)->item(0)->nodeValue;
						$itemdetail["totalused"] = $xpath->query(".//totalused",$item)->item(0)->nodeValue;
						$itemdetail["totalcollectible"] = $xpath->query(".//totalcollectible",$item)->item(0)->nodeValue;
						$itemdetail["totalrefurbished"] = $xpath->query(".//totalrefurbished",$item)->item(0)->nodeValue;*/
						
						//insertDBField($table1,$itemdetail);  
					 	//$Found_asins[$itemdetail["asin"]] = $itemdetail["asin"];
						//PRINT_r($itemdetail);
						unset($itemdetail); 
					} 
				}else{ 
					//echo "Sorry no item, with this UPC, PLease search another UPC";
					$s["status"] =2;
					//updateDBField("id",$id,$table,$s);
				} 
				 
			
			 
		}   
		foreach($results as $result){
				$status["status"] =9; 
				
				//$serch = array_search($result['asin'],$Found_asins);
				//print_r($serch);
				//print_r($Found_asins);
				//print_r($result);
				if($serch == false){ 
					//deleteDBField('amazon_Inventory','asin',$result['asin']);
					//updateDBField("id",$result['id'],$table,$status);
				} 
						
		} 
		     echo'<meta http-equiv="refresh" content="1;">';
	}else{
		 
		echo 'data updation done successfully';
		echo'<meta http-equiv="refresh" content="1; url=http://51.15.193.78/Amazon_review/inorganic/scraper/sellerDataToFile.php">';
	}