<?php
	 
function RobotTest1($xpath){
	global $proxy; 
		$recpatcha = new stdClass; 
		$recpatcha->{"guess"} = "";
		$recpatcha->imagesrc = $xpath->query("//img[@id='auth-captcha-image']/@src")->item(0)->nodeValue;		 
		$recpatcha->proxy = $proxy; 
		return $recpatcha; 
}

function solveAmazonRecaptcha($recpatcha){ 
	copy($recpatcha->imagesrc, "captcha.jpg");
	unset($recpatcha->imagesrc);
	$recpatchaID = submitCaptchaImage(); 
	if($recpatchaID){
		sleep(5);
		$SolvedCaptcha = getSolvedCaptcha($recpatchaID);
		if($SolvedCaptcha){
			$recpatcha->{"guess"} = $SolvedCaptcha;
			//echo $recpatcha->{"guess"};
			return (array) $recpatcha;			
		}		
	}
	else {
		echo "error in uploading image";
	}
	return false;  
}
function submitCaptchaImage($filePathName = "captcha.jpg"){ 
	$file = new CURLFile(realpath('captcha.jpg'),'image/jpeg','captcha');
	$target_url = 'http://2captcha.com/in.php';
	$post["file"] =$file;
	$post["method"] = "post"; 
	$post["regsense"] = 1; 
	$post["key"] = CAPTCHAKEY; 
	$response =  getcontent($target_url, $post);
		//print_r($response);
	return processCaptchaResponse($response);
}
function processCaptchaResponse($response){
	$reponseArray = explode("|", $response);
	if($reponseArray[0] == "OK") return $reponseArray[1];
	else if($reponseArray[0] == "CAPCHA_NOT_READY") return "CAPCHA_NOT_READY";
	else return false;
}
function getSolvedCaptcha($id_captcha){
	global $captchaRetry;
	$url = "http://2captcha.com/res.php?key=".CAPTCHAKEY."&action=get&id=".$id_captcha;
	$response =  getcontent($url,false, false, false);
	//print_r($response);
	$CaptchaResponse = processCaptchaResponse($response);
	if($CaptchaResponse == "CAPCHA_NOT_READY"){
		sleep(5);
		$captchaRetry++;
		if($captchaRetry > 5) return false;
		else return getSolvedCaptcha($id_captcha);
	}
	else return $CaptchaResponse;	

}
function processRecpatcha(){
	if(!empty($_GET["guess"])){
		$proxy = $_GET["proxy"];
		echo $proxy;
		unset($_GET["proxy"]);
		//$query = http_build_query($_GET);
		$recpatchaUrl = "https://www.amazon.com/ap/signin";
		echo $recpatchaUrl;
		$content = getcontent($recpatchaUrl,$_GET,"POST");
		$xpath = convertToXpath($content);	
		return $content;	
		$recpatcha = RobotTest($xpath);
		
		if($recpatcha) {
			echo "Wrong image entered";
			exit;
		}
		else echo "Recpatcha worked !success!";
		return $xpath;		
	}
}
?>