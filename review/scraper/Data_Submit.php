<?php  
	include_once('../includes/functions.php');   
	echo "<pre>";  
	// print_r($_POST);
	if(isset($_POST['submit'])){ 
		if(isset($_POST['checkbox'])){
			if (is_array($_POST['checkbox'])){
				foreach($_POST['checkbox'] as $value){ 
				  $Slected_Array[] = json_decode(base64_decode($value),true); 
				 //
				}
				$final_array =$Slected_Array[0];
				if($_POST['field-keywords']){
					$final_array['field-keywords']  = $_POST['field-keywords'];
				}elseif($_POST['otpCode']){
					$final_array['otpCode']  = $_POST['otpCode'];
				}
				
				$verifyUrl  = $final_array['loginUrl'];
				unset($final_array['loginUrl']);
				//print_r($Slected_Array[0]);
				//print_r($verifyUrl);
				//print_r($final_array);
				$verifyUrl = 'https://www.amazon.co.uk/errors/validateCaptcha?amzn='.$final_array['amzn'].'&amzn-r=%2F&field-keywords='.$final_array['field-keywords'];
				 $response = getcontent($verifyUrl);   
				//print_r($response); 
				$xpath = convertToXpath($response);
				$verify = $xpath->query("//form[@id='auth-mfa-form']");
				$captchaImg  = $xpath->query("//form[@action='/errors/validateCaptcha']");
				if($verify->length >=1){
					verify($xpath); 
				}elseif($captchaImg->length >=1){
					captcha1($xpath);
				}else{
					echo 'login successfull';
				}
			}else{
				$value = $_POST['checkbox'];
				$Slected_Array = json_decode(base64_decode($value),true);
				 // print_r($Slected_Array); 
			}
			//include_once('downloadDataToFile.php');   
		} 
		echo'<meta http-equiv="refresh" content="1; url=Amazon_Review_Product.php">'; 
	} 