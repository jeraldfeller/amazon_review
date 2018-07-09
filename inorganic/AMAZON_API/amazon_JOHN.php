<?php 
	ini_set('display_startup_errors', 1);
	//ini_set('display_errors', 1);
	error_reporting(0);
	$table ='john2';
	$table1 ='john3';
	include_once('includes/functions.php');  
	echo "<pre>";      
	$results = selectDBField($table,"status",29,false,1); 
	PRINT_r($results);
	if($results){
		foreach($results as $result){
			$id=$result["id"];
			if($result["asin"] == 'NA'){
				$itemdetail["status"] = 15;  	 
				updateDBField("id",$id,$table,$itemdetail);
				 echo'<meta http-equiv="refresh" content="0;">';
				 
			}else{
				$asinvalue[] =  sprintf("%010s",$result["asin"]);			
			}
		} 
		$asinlist = implode(",",$asinvalue);  
		//$asinlist = '0000050694938';  
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
				//PRINT_r($error);
				PRINT_r($items);
				if($items->length>=1){  
					$x1 =0;
					foreach($items as $item){ 
							$itemdetail["ean"] =$result['ean'];
							$itemdetail["asin"] = $xpath->query(".//asin",$item)->item(0)->nodeValue;
							$itemdetail["brand"] = $xpath->query(".//brand",$item)->item(0)->nodeValue;
							$itemdetail["Weblink"] = 'https://www.amazon.co.uk/dp/'.$itemdetail["asin"];
							$itemdetail["product_description"] = $xpath->query(".//editorialreview/content",$item)->item(0)->nodeValue;
							$itemdetail['product_description'] =trim(preg_replace('/\s+/',' ', $itemdetail['product_description']));
							$itemdetail["image_location"] = $xpath->query(".//largeimage/url",$item)->item(0)->nodeValue;
							$itemdetail["Amazon_Item_model_number"] = $xpath->query(".//model",$item)->item(0)->nodeValue;
							$itemdetail["Amazon_Bestsellers_Rank"] = $xpath->query(".//salesrank",$item)->item(0)->nodeValue;
							$Bullets= $xpath->query(".//feature",$item);
							if($Bullets->length>=1){  
								foreach($Bullets as $Bullet){  
									$Bullet_Descriptions[] = $xpath->query(".//text()",$Bullet)->item(0)->nodeValue; 
								}
							}
							$itemdetail["Bullet_Description"] = implode(',',$Bullet_Descriptions);   
							$lowestnewprice = $xpath->query(".//lowestnewprice/formattedprice",$item)->item(0)->nodeValue;
							$itemdetail["Amazon_Landed_Price"]  = preg_replace('/[^0-9.]/','',$lowestnewprice);  
							$browsenodes= $xpath->query(".//browsenodes/browsenode",$item);
							if($browsenodes->length>=1){  
							$x=1;
								foreach($browsenodes as $browsenode){
									$itemdetail["IPG_Cat_Desc_1_name"] = $xpath->query(".//name",$browsenode)->item(0)->nodeValue;
									$top_browsenodes= $xpath->query(".//ancestors/browsenode",$browsenode);
									if($top_browsenodes->length>=1){  
										foreach($top_browsenodes as $top_browsenode){ 
											if($x==1){
												$itemdetail["IPG_Cat_Desc_1_P"] = $xpath->query(".//name",$top_browsenode)->item(0)->nodeValue; 
											}
										$x++;
										} 
										 
									}
									
								}
							} 
							$itemdetail["packagedimensions_height"] = $xpath->query(".//packagedimensions/height",$item)->item(0)->nodeValue;
							$itemdetail["packagedimensions_length"] = $xpath->query(".//packagedimensions/length",$item)->item(0)->nodeValue;
							$itemdetail["packagedimensions_width"] = $xpath->query(".//packagedimensions/width",$item)->item(0)->nodeValue;
							$itemdetail["packagedimensions_weight"] = $xpath->query(".//packagedimensions/weight",$item)->item(0)->nodeValue;
							$itemdetail["itemdimensions_height"] = $xpath->query(".//itemdimensions/height",$item)->item(0)->nodeValue;
							$itemdetail["itemdimensions_length"] = $xpath->query(".//itemdimensions/length",$item)->item(0)->nodeValue;
							$itemdetail["itemdimensions_width"] = $xpath->query(".//itemdimensions/width",$item)->item(0)->nodeValue;
							$itemdetail["itemdimensions_weight"] = $xpath->query(".//itemdimensions/weight",$item)->item(0)->nodeValue;
							
							$itemdetail["status"] = 1; 
						if($x1==0){
							updateDBField("id",$id,$table,$itemdetail); 
							$Found_asins[$asin] = $asin;
						}
						 insertDBField($table1,$itemdetail); 
							PRINT_r($itemdetail);
							unset($itemdetail);
						 
						$x1++;
					} 
				}else{ 
					echo "Sorry no item, with this keyword, PLease search another keyword"; 
				} 
			foreach($results as $result){
				$status["status"] =9;  
				$serch = array_search($result['asin'],$Found_asins);
				//print_r($serch);
				//print_r($Found_asins);
				//print_r($result);
				if($serch == false){ 
					//deleteDBField('amazon_Inventory','asin',$result['asin']);
					//updateDBField("asin",$result['asin'],$table,$status);
				} 	
			}  
		}  echo'<meta http-equiv="refresh" content="0;">';
	}else{
		echo 'all done';
		
		
	}
	   