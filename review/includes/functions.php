<?php

require_once 'class.database.php';
require_once 'configuration.php'; 
require_once 'getcontent.php';
require_once 'functions1.php';
$db = MysqliDatabaseconnect();

ini_set('max_execution_time', 9000000); //300 seconds = 5 minutes
function rspecial($string,$rp = '') {
    $string = str_replace(' ', '-', $string);
    return preg_replace('/[^A-Za-z0-9\-]/', $rp, $string);
  }
function selectDBField($table, $field = false, $value = false, $columns = false,$limit = false){
	global $db; 
	//$db = MysqliDatabaseconnect();
	if($limit) $db->limit($limit);
	if($field) $db->where($field, $value);
	if($columns) $results = $db->select($columns)->from($table)->fetch();
	else $results = $db->select()->from($table)->fetch();
	//print_r($results);
	//echo $db->last_query().NL;
	//$db->close();
	if(!empty($results)) return $results;
	else return false;
}

 
function updateDBField($field, $value, $table, $data, $autoCreateColumn = false){
	if(empty($value)) return false;
	if(!empty($data)){
		if (is_object($data)) $data = (array) $data;
		global $db; 
		//$db = MysqliDatabaseconnect();
		$db->where($field, $value);
		if ($db->update ($table, $data)) {
			//echo $db->last_query().NL;
			$affectedRows = $db->affected_rows;
			//$db->close();
			if($affectedRows >= 0) {
				//echo $affectedRows . " records were updated in $table \n";
				return $affectedRows;
			}
			else {
				echo "Error occurred while updating ";
				if($autoCreateColumn){
					createDBColumn($data, $table);					
					return updateDBField($field, $value, $table, $data);
				}				
				return false;
			}
		}
		else {
			//$db->close();
			return false;
		}
	}
	else return false;
}
   
function insertDBField($table, $data, $autoCreateColumn = false){
	 
	if(!functionallyEmpty($data)){
		if (is_object($data)) $data = (array) $data;
		global $db; 
		//mysql_set_charset('utf8',$db); 
		//$db = MysqliDatabaseconnect();
		$id = $db->insert($table, (array) $data) ;
		//$db->close();
		//echo $db->last_query();
		if ($id >= 0) {
			//echo "Record was created in $table Id=" . $id."\n";
			return true;
		}
		else {
			echo "Error occurred while inserting \n";
			if($autoCreateColumn) {
				createDBColumn($data, $table);
				return insertDBField($table, $data, false);
			}
			return false;
		}
	}
	else return false;
}


function deleteDBField($table, $field, $value){
	if(!is_array($field) && empty($value)) return false;
	$db = MysqliDatabaseconnect();
	$db->where($field, $value);
	if ($db->delete()->from($table)->execute()) {
		echo $db->affected_rows . " records were deleted from $table \n";
		return true;
	}
	else return false;
}

  
function MysqliDatabaseconnect(){
	return new Database(SERVER , USER, PWD, DB);
}   
    
function convertToXpath($content){
	$doc = new DOMDocument();
	libxml_use_internal_errors(true);
	$doc->loadHTML($content);
	$xpath = new DOMXpath($doc);
	return $xpath;
}

function convertXmlToXpath($content){
	$doc = new DOMDocument();
	libxml_use_internal_errors(true);
	$doc->loadXML($content);
	$xpath = new DOMXpath($doc);
	return $xpath;
}

function appendcsv($completedata){
	global $file;
	global $count;
	if(is_object($completedata)) $completedata = (array) $completedata;
	if($count == "0") fputcsv($file,array_keys($completedata));
	$count++;
	
	$content = array_values($completedata);
	fputcsv($file,$content);	
	return true;
}
 
   
function downloadFile($file){ 
	if (file_exists($file)){
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.basename($file));
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		flush();
		readfile($file);
		
    }
}

function functionallyEmpty($o){
  if (empty($o)) return true;
  else if (is_numeric($o)) return false;
  else if (is_string($o)) return !strlen(trim($o)); 
  else if (is_object($o)) return functionallyEmpty((array)$o);
 
  // It's an array!
  foreach($o as $element) 
    if (functionallyEmpty($element)) continue; // so far so good.
    else return false; 
    
  // all good.
  return true;
} 
function login(){
	$html ="https://www.amazon.com/ap/signin?_encoding=UTF8&openid.assoc_handle=usflex&openid.claimed_id=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0%2Fidentifier_select&openid.identity=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0%2Fidentifier_select&openid.mode=checkid_setup&openid.ns=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0&openid.ns.pape=http%3A%2F%2Fspecs.openid.net%2Fextensions%2Fpape%2F1.0&openid.pape.max_auth_age=0&openid.return_to=https%3A%2F%2Fwww.amazon.com%2Fgp%2Fyourstore%2Fhome%3Fie%3DUTF8%26action%3Dsign-out%26path%3D%252Fgp%252Fyourstore%252Fhome%26ref_%3Dnav_youraccount_signout%26signIn%3D1%26useRedirectOnSuccess%3D1";
	$html ="https://www.amazon.it/ap/signin?_encoding=UTF8&ignoreAuthState=1&openid.assoc_handle=itflex&openid.claimed_id=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0%2Fidentifier_select&openid.identity=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0%2Fidentifier_select&openid.mode=checkid_setup&openid.ns=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0&openid.ns.pape=http%3A%2F%2Fspecs.openid.net%2Fextensions%2Fpape%2F1.0&openid.pape.max_auth_age=0&openid.return_to=https%3A%2F%2Fwww.amazon.it%2F%3F_encoding%3DUTF8%26ref_%3Dnav_ya_signin&switch_account=";
	$response = getcontent($html); 
	//  print_r($response);
  	$xpath = convertToXpath($response);  
	$checkLogin = $xpath->query("//form[@name='signIn']");
	 //print_r($checkLogin);
	if($checkLogin->length==1){
		$items = $xpath->query("//form//input"); 
		$loginUrl = $xpath->query("//form/@action")->item(0)->nodeValue; 
		//print_r($loginUrl);
		foreach($items as $item){
				$name= $xpath->query(".//@name",$item)->item(0)->nodeValue;
				$loginDetails[$name] = $xpath->query(".//@value",$item)->item(0)->nodeValue;
		}	 
		$loginDetails["email"] =EMAIL;
		$loginDetails["password"]	=PASSWORD;
		$response = getcontent($loginUrl,$loginDetails);   
		print_r($response); 
		$xpath = convertToXpath($response);
		$verify = $xpath->query("//form[@id='auth-mfa-form']");
		$captchaImg  = $xpath->query("//img[@id='auth-captcha-image']");
		//print_r($verify); 
		if($verify->length >=1){
			verify($xpath); 
		}elseif($captchaImg->length >=1){
			captcha($xpath);
		} 
		$checkLogin = $xpath->query("//form[@name='signIn']");
		 
		if($checkLogin->length==0){
			echo "login Successful, now you can submit the file"; 
		}else{
			echo "Error during Log IN AMAZON account";
		}
	}else{ 
			echo "thanks for login ,now you can submit the file";  
	}
	
}

function verify($xpath){
	$verify = $xpath->query("//form[@id='auth-mfa-form']");
	// print_r($verify);
	if($verify->length >=1){
		$items = $xpath->query("//form//input"); 
		$verifyUrl = $xpath->query("//form[@id='auth-mfa-form']/@action")->item(0)->nodeValue; 
		foreach($items as $item){
				$name= $xpath->query(".//@name",$item)->item(0)->nodeValue;
				$verifyDetails[$name] = $xpath->query(".//@value",$item)->item(0)->nodeValue;
		}	
		$verifyDetails["loginUrl"]	=$verifyUrl; 	
		//$verifyDetails["rememberDevice"]	='true';  
		// print_r($verifyDetails); 
		select_checkBox($verifyDetails,'otpCode'); 
		submit_checkBox(); 
		 sleep(43600);
	} 
}
	
	
	
function captcha1($xpath){
	$captchaImg  = $xpath->query("//form[@action='/errors/validateCaptcha']");
	   //sleep(43600);
	   echo "amazon Ban your IP  ";
	if($captchaImg->length >=1){
		echo "amazon Ban your IP  ";  
		$Img  = $xpath->query("//form[@action='/errors/validateCaptcha']//img/@src")->item(0)->nodeValue;
		echo '<img src="'.$Img.'" />';  
		$items = $xpath->query("//form[@action='/errors/validateCaptcha']//input");
		$loginUrl = $xpath->query("//form/@action")->item(0)->nodeValue; 
		//echo $loginUrl;
		foreach($items as $item){
			$name= $xpath->query(".//@name",$item)->item(0)->nodeValue;
			$loginDetails1[$name] = $xpath->query(".//@value",$item)->item(0)->nodeValue;
		} 
		//$loginDetails1["email"] =EMAIL;
		//$loginDetails1["password"]	=PASSWORD;
		$loginDetails1["loginUrl"]	='https://www.amazon.com/errors/validateCaptcha'; 
		//print_r($loginDetails1); 
		/*$recpatcha = RobotTest1($xpath); 
		if($recpatcha){
			$recpatchaSolved = solveAmazonRecaptcha($recpatcha); 
			$loginDetails1["guess"] = $recpatchaSolved["guess"];
			print_r($loginDetails1);  
			$response = getcontent($loginUrl,$loginDetails1);
			//$xpath = convertToXpath($response);  
		}  
		print_r($response); */
		//$xpath = convertToXpath($response);
		$verify = $xpath->query("//form[@id='auth-mfa-form']");
		$captchaImg  = $xpath->query("//form[@action='/errors/validateCaptcha']");
	   	
		if($verify->length >=1){
			verify($xpath); 
		}elseif($captchaImg->length >=1){
			select_checkBox($loginDetails1,'field-keywords');
			// sleep(43600);
			submit_checkBox(); 
		} 
		
	}else{
		echo 'no captcha found';
	}	
}

	
function captcha($xpath){
	$captchaImg  = $xpath->query("//img[@id='auth-captcha-image']");
	   //sleep(43600);
	if($captchaImg->length >=1){
		echo "amazon Ban your IP  ";  
		$Img  = $xpath->query("//img[@id='auth-captcha-image']/@src")->item(0)->nodeValue;
		echo '<img src="'.$Img.'" />';  
		$items = $xpath->query("//form//input");
		$loginUrl = $xpath->query("//form/@action")->item(0)->nodeValue; 
		//echo $loginUrl;
		foreach($items as $item){
			$name= $xpath->query(".//@name",$item)->item(0)->nodeValue;
			$loginDetails1[$name] = $xpath->query(".//@value",$item)->item(0)->nodeValue;
		} 
		$loginDetails1["email"] =EMAIL;
		$loginDetails1["password"]	=PASSWORD;
		$loginDetails1["loginUrl"]	=$loginUrl; 
		//print_r($loginDetails1); 
		/*$recpatcha = RobotTest1($xpath); 
		if($recpatcha){
			$recpatchaSolved = solveAmazonRecaptcha($recpatcha); 
			$loginDetails1["guess"] = $recpatchaSolved["guess"];
			print_r($loginDetails1);  
			$response = getcontent($loginUrl,$loginDetails1);
			//$xpath = convertToXpath($response);  
		}  
		print_r($response); */
		//$xpath = convertToXpath($response);
		$verify = $xpath->query("//form[@id='auth-mfa-form']");
		$captchaImg  = $xpath->query("//img[@id='auth-captcha-image']");	
		if($verify->length >=1){
			verify($xpath); 
		}elseif($captchaImg->length >=1){
			select_checkBox($loginDetails1,'guess');
			submit_checkBox(); 
		} 
		
	}else{
		echo 'no captcha found';
	}	
}


function loginDetails1($ASIN_token){
			$loginDetails1 = "asin=".$ASIN_token['asin']."&sku=singla".$ASIN_token['asin']."&itemPrice=".$ASIN_token["itemprice"]."&minPrice=".$ASIN_token["minPrice"]."&maxPrice=".$ASIN_token['maxPrice']."&ruleId=".RULEID."&glType=gl_book&shippingPrice=5.49&localeString=".localeString."&token=".$ASIN_token['token']; 
			return $loginDetails1;
		}
		function remove_ASIN_Account($tokens,$xpath,$rule_Name =rule_Name){
			if($tokens->length>=1){ 
				$x = 0;
				foreach($tokens as $token){
					$ruleName= $xpath->query(".//*[@class='ruleName']/@title",$token)->item(0)->nodeValue;
					 
					if($ruleName != $rule_Name){
						$asin_notification = 'Total NEW ASIN found :';
						$ASIN_token['asin']=  $xpath->query(".//*[@class='asin']/@value",$token)->item(0)->nodeValue;  	
						insertDBField('inactive',$ASIN_token);	
					}
				} 
			}
		}
		
	function update_price($tokens,$xpath,$rule_Name =rule_Name,$data_type = false){
		if($tokens->length>=1){ 
			$x = 0;
			foreach($tokens as $token){
				$automate_data = 0;
				if($data_type =='notification'){
					$data_error_type= $xpath->query(".//@data-listing-paused",$token)->item(0)->nodeValue;
					if($data_error_type == 'false'){
						$automate_data = 1;
					}elseif($data_error_type == 'true'){
						$automate_data = 0;
					}
				}
				
				$ruleName= $xpath->query(".//*[@class='ruleName']/@title",$token)->item(0)->nodeValue;
				if($ruleName != $rule_Name ){
					
					$asin_notification = 'Total NEW ASIN found :';
					$ASIN_token['token']= $xpath->query(".//input[@class='token']/@value",$token)->item(0)->nodeValue;  
					$ASIN_token['itemprice']=  $xpath->query(".//*[@class='itemprice']/@value",$token)->item(0)->nodeValue; 
					$ASIN_token['asin']=  $xpath->query(".//*[@class='asin']/@value",$token)->item(0)->nodeValue; 
					$ASIN_token["minPrice"] =10;
					$ASIN_token["maxPrice"] =1000.00;
					  
					 //  print_r($ASIN_token);

					if($automate_data == 0){		  
						$loginUrl = "https://sellercentral.amazon.com/automatepricing/ajax/rules/associate/";
						 //	$loginUrl = "https://sellercentral.amazon.com/automatepricing/ajax/listings/updatelisting"; 
						$loginDetails = loginDetails1($ASIN_token);
						$response = getcontent($loginUrl,$loginDetails);
						$xpath_L = convertToXpath($response);
						$checkLogin = $xpath_L->query("//form[@name='signIn']"); 
						if($checkLogin->length == 0){
							$price_json_data = json_decode($response,true);
							//print_r($loginDetails);    
							//   print_r($price_json_data);    
							if (array_key_exists("successMessage",$price_json_data)){
								if($price_json_data['successful']==1){
									$final_Max_price['status']=1;
									 // print_r($price_json_data['successMessage']); 
								}else{
									//if($price_json_data['fieldErrors']['maxPrice']){
										$error = rtrim($price_json_data['fieldErrors']['maxPrice'][0],".");
										$maxprice=explode('$',$error);
										//print_r($maxprice);
										$final_Max_price["maxPrice"] = substr($maxprice[1],0,-2); 
										//print_r($final_Max_price);
										$final_Max_price["maxPrice"] =$final_Max_price["maxPrice"] -.01; 
										$error_data['asin']=$ASIN_token['asin'];
										$error_data['fieldErrors']=$price_json_data["fieldErrors"];
										//$final_Max_price['status']=2;  
										$ASIN_token["maxPrice"] =$final_Max_price["maxPrice"];
									
									//}
									if($price_json_data["fieldErrors"]["minPrice"][0] == 'Minimum Price is higher than Your Price. Please try a lower Minimum Price.'){
										$delete['asin']=$ASIN_token['asin'];
										$delete['error']='Minimum';
										insertDBField('inactive',$delete);
									} 
									
									$loginDetails2 = loginDetails1($ASIN_token);
									$response1 = getcontent($loginUrl,$loginDetails2);
									$xpath_L1 = convertToXpath($response1);
									$checkLogin = $xpath_L1->query("//form[@name='signIn']"); 
									if($checkLogin->length == 0){
									
										$price_json_data1 = json_decode($response1,true);
										if($price_json_data1["fieldErrors"]["maxPrice"][0] == 'Maximum Price is lower than Your Price. Please try a higher Maximum Price.' or $price_json_data1["fieldErrors"]["maxPrice"][0] == 'Please enter a valid price.'){
											//echo 'do'.$price_json_data1["fieldErrors"]["maxPrice"][0].'1';
											$delete['asin']=$ASIN_token['asin'];
											$delete['error']='Maximum';
										//	print_r($delete);
											insertDBField('inactive',$delete);
										} 
										 
										 //print_r($ASIN_token); 
										  // print_r($price_json_data1); 
									}else{
										echo "please login";
										
										login();
									}
									
									 
								}
							}									
						}else{
							echo "please login";
							
							login();
						}
						$x++;
					}else{
					 
						$delete['asin']=$ASIN_token['asin'];
						$delete['error']='error';
						insertDBField('inactive',$delete);
						//unset($delete);
						
					}
					/*if($rule_Name =='MAX_REACHED'){
						$delete['asin']=$ASIN_token['asin'];
						$delete['error']='Maximum';
						insertDBField('inactive',$delete);
					}*/
					unset($ASIN_token);
					unset($price_json_data);
					unset($final_Max_price);
					unset($delete);
					unset($price_json_data1);
				}
			}
			echo $asin_notification.$x;
		}
		//return $x;
	}	
		




 
	 
	function select_checkBox($ASIN,$code){
	?>
	<form method="post" action ="Data_Submit.php">
		<?php 
			//print_r($ASIN);
			$postvalue = base64_encode(json_encode($ASIN));  
			echo '<input type="checkbox" value="'.$postvalue.'" name="checkbox[]"</input>';
			echo '<input type="text" name="'.$code.'" id="'.$code.'"</input>';
			//$ASIN['smallimage'] = '<img src="'.$ASIN['smallimage'].'"/>';
			print_r($ASIN);
			return $ASIN;
			
		?> 
	<?PHP 
}
 
	function submit_checkBox(){
		?>
		<form method="post"> 
			<input type="submit" name="submit" class="btn btn-success" value="Submit">  
		</form> 
		
		<?PHP 
	}

?>