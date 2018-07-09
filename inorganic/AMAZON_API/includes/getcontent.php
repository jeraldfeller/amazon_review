<?php

$uagentList = array(
        "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11",
        "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:16.0) Gecko/20100101 Firefox/16.0",
        "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.94 Safari/537.4",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/536.26.17 (KHTML, like Gecko) Version/6.0.2 Safari/536.26.17",
        "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)",
        "Mozilla/5.0 (Windows NT 5.1; rv:16.0) Gecko/20100101 Firefox/16.0",
        "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11",
        "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11",
        "Mozilla/5.0 (Windows NT 6.1; rv:16.0) Gecko/20100101 Firefox/16.0",
        "Mozilla/5.0 (Windows NT 5.1; rv:13.0) Gecko/20100101 Firefox/13.0.1",
        "Opera/9.80 (Windows NT 5.1; U; en) Presto/2.10.289 Version/12.01",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; FunWebProducts; .NET CLR 1.1.4322; PeoplePal 6.2)",
        "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.112 Safari/535.1",
        "Mozilla/5.0 (Windows NT 6.1; rv:2.0b7pre) Gecko/20100921 Firefox/4.0b7pre",
        "Mozilla/5.0 (Windows NT 5.1; rv:5.0.1) Gecko/20100101 Firefox/5.0.1",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MRA 5.8 (build 4157); .NET CLR 2.0.50727; AskTbPTV/5.11.3.15590)",
        "Mozilla/4.0 (compatible; MSIE 6.0; MSIE 5.5; Windows NT 5.0) Opera 7.02 Bork-edition [en]",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)",
        "Mozilla/5.0 (Windows NT 6.1; rv:5.0) Gecko/20100101 Firefox/5.02",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; .NET CLR 1.1.4322)",
        "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:5.0) Gecko/20100101 Firefox/5.0",
        "Mozilla/5.0 (Windows NT 6.0) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.112 Safari/535.1",
        "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; .NET CLR 3.5.30729)",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.94 Safari/537.4",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/536.26.14 (KHTML, like Gecko) Version/6.0.1 Safari/536.26.14",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:16.0) Gecko/20100101 Firefox/16.0",
        "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)",
        "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.94 Safari/537.4",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/534.57.2 (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) )",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_5) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11"
    );
shuffle($uagentList);
$useragent = $uagentList[0];

$proxies = array(
"173.234.227.57:8800",
"23.19.34.182:8800",
"23.19.34.13:8800",
"173.208.39.88:8800",
"23.19.34.17:8800",
"173.234.249.126:8800",
"173.234.227.63:8800",
"173.234.249.142:8800",
"23.19.34.243:8800",
"173.208.39.103:8800"
);
shuffle($proxies);
$proxy = $proxies[0]; 

function getcontent($curl_url, $post_fields = false, $header = false, $useProxy = false, $referral = false, $definedProxy = false)
{
	global $useragent;
	//global $proxies;
	//global $proxy;
	//shuffle($proxies);
	//if(!$definedProxy)	$proxy = TRIM($proxies[0]);
	//else $proxy = $definedProxy;
	//sleep(5);
	$cookiefile = "cookie.txt";
	touch($cookiefile);
	//echo $cookiefile;
	if(!$referral) $referral = $curl_url;

	$ch2 = curl_init();
   	curl_setopt($ch2,CURLOPT_URL,$curl_url);
   	//curl_setopt($ch2,CURLOPT_URL,REMOTE."?crunchurl=".$curl_url);
   	if($useProxy){
		curl_setopt($ch2, CURLOPT_HTTPPROXYTUNNEL, false);
		curl_setopt($ch2, CURLOPT_PROXY, $proxy);
	}
   	curl_setopt($ch2,CURLOPT_RETURNTRANSFER, true);
   	curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, 1);
   	curl_setopt($ch2, CURLOPT_AUTOREFERER, TRUE);
   	curl_setopt($ch2, CURLOPT_REFERER, $referral);
   	curl_setopt($ch2,CURLOPT_CONNECTTIMEOUT,"1000");
   	curl_setopt($ch2,CURLOPT_TIMEOUT,"1000");
   	curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
    	if($post_fields){
			curl_setopt($ch2, CURLOPT_POST, 1);
			curl_setopt($ch2, CURLOPT_POSTFIELDS, $post_fields);
		}
	if($header) curl_setopt ($ch2, CURLOPT_HEADER, $header );
	curl_setopt($ch2,CURLOPT_USERAGENT,$useragent);
	curl_setopt($ch2,CURLOPT_COOKIEFILE,$cookiefile);
	curl_setopt($ch2, CURLOPT_COOKIEJAR, $cookiefile);
	//if($header) $result = curl_getinfo($ch2);
	$result = curl_exec($ch2);	
	$error=curl_error($ch2);
	if($error) {
		file_put_contents("./../mike/badproxy/".$proxy.".txt",date("h:i:sa")." ".$proxy." ".$error.NL,FILE_APPEND);
		echo $proxy." ".$error.NL;
	}
    curl_close($ch2);
    //echo $result;
    return $result;
}

?>