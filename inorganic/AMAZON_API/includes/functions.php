<?php 
 
require_once('./includes/config.php'); 
require_once('./includes/getcontent.php');
 require_once('./includes/aws_signed_request.php'); 
require_once('./includes/class.database.php'); 
require_once('./includes/simplexlsx.class.php'); 
//include('./header.php');    
ini_set('max_execution_time', 900); //300 seconds = 5 minutes 
function selectDBField($table, $field = false, $value = false, $columns = false,$limit = false){

	$db = MysqliDatabaseconnect();
	if($limit) $db->limit($limit);
	if($field) $db->where($field, $value);
	if($columns) $results = $db->select($columns)->from($table)->fetch();
	else $results = $db->select()->from($table)->fetch();
	//print_r($results);
	//echo $db->last_query().NL;
	$db->close();
	if(!empty($results)) return $results;
	else return false;
}

function selectDBFieldWhereIn($table, $field = false, $value = false, $columns = false){

	$db = MysqliDatabaseconnect();
	if($field) $db->where_in($field, $value);
	if($columns) $results = $db->select($columns)->from($table)->fetch();
	else $results = $db->select()->from($table)->fetch();
	//print_r($results);
	//echo $db->last_query().NL;
	$db->close();
	if(!empty($results)) return $results;
	else return false;
}


function updateDBField($field, $value, $table, $data, $autoCreateColumn = false){
	if(empty($value)) return false;
	if(!empty($data)){
		if (is_object($data)) $data = (array) $data;
		$db = MysqliDatabaseconnect();
		$db->where($field, $value);
		if ($db->update ($table, $data)) {
			//echo $db->last_query().NL;
			$affectedRows = $db->affected_rows;
			$db->close();
			if($affectedRows >= 0) {
				//echo $affectedRows . " records were updated in $table \n";
				return $affectedRows;
			}
			else {
				//echo "Error occurred while updating ";
				if($autoCreateColumn){
					createDBColumn($data, $table);					
					return updateDBField($field, $value, $table, $data);
				}				
				return false;
			}
		}
		else {
			$db->close();
			return false;
		}
	}
	else return false;
}

function createDBColumn($data, $table){
	if(!functionallyEmpty($data)){
		if (is_object($data)) $data = (array) $data;
		foreach ($data as $key=>$value){
			$db = MysqliDatabaseconnect();
			$sql= "ALTER TABLE `$table`
					ADD COLUMN `$key` VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL";
			$db->query($sql)->execute();
			$db->close();
		}
		return true;
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

function updateDBFieldWhereIn($field, $value, $table, $data){
	if(functionallyEmpty($value)) return false;
	if(!empty($data)){
		if (is_object($data)) $data = (array) $data;
		$db = MysqliDatabaseconnect();
		$db->where_in($field, $value);
		if ($db->update ($table, $data)) {
			echo $db->affected_rows . " records were updated \n";
			//echo $db->last_query();
			$db->close();
			return true;
		}
		else {
			$db->close();
			return false;
		}
	}
	else return false;
}

function updateDBFieldWhereNotIn($field, $value, $table, $data){
	if(functionallyEmpty($value)) return false;
	if(!empty($data)){
		if (is_object($data)) $data = (array) $data;
		$db = MysqliDatabaseconnect();
		$db->where_not_in($field, $value);
		if ($db->update ($table, $data)) {
			echo $db->affected_rows . " records were updated \n";
			$db->close();
			//echo $db->last_query();
			return true;
		}

		else {
			$db->close();
			return false;
		}
	}
	else return false;
}

function insertDBField($table, $data, $autoCreateColumn = false){
	if(!functionallyEmpty($data)){
		if (is_object($data)) $data = (array) $data;
		$db = MysqliDatabaseconnect();
		$id = $db->insert($table, (array) $data) ;
		$db->close();
		//echo $db->last_query();
		if ($id >= 0) {
			echo "Record was created in $table Id=" . $id."\n";
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

function insertUpdateOnDuplicate($table, $data){
	if(!functionallyEmpty($data)){
		if (is_object($data)) $data = (array) $data;
		$db = MysqliDatabaseconnect();
		$id = $db->insertUpdateOnDuplicate($table, $data) ;	
		$db->close();
		if ($id) {
			echo "Record was updated in $table Id=" . $id."\n";
			//echo $db->last_query()."\n";
			return true;
		}
		
	}
	else return false;
}

function UpdateOnZeroInsert($field, $value, $table, $data, $autoCreateColumn = false ){
	$numberUpdated = updateDBField($field, $value, $table, $data, $autoCreateColumn);
	if($numberUpdated == '0') {
		echo "$numberUpdated updated $value does not exit".NL;
		insertDBField($table, $data, $autoCreateColumn);
	}
}


function MysqliDatabaseconnect(){
	return new Database(SERVER , USER, PWD, DB);
}

function get_main_rank($html){
	//$html = str_get_dom($content);
	$SalesRank_html = $html('*[id="SalesRank"]',0);
	if($SalesRank_html){
		$SalesRank_plain = $SalesRank_html->getPlainText();
		preg_match("/([0-9]{1,3}(,[0-9]{3})*(\.[0-9]+)?|\.[0-9]+)/", $SalesRank_plain, $output_array);
		return (int)preg_replace('/[^\d]/', '', $output_array[1]);
	}
	else return false;

}

function getStars($content){
	$find = array(' out of 5 stars');
	return str_replace($find,'',$content);
}

function getMainStars($html){
	$summaryStars = $html('*[id="summaryStars"]',0);
	if($summaryStars){
		$data = array();
		$numberofstar_a = $summaryStars('a',0);
		$data["numberstar"] = getStars($numberofstar_a->title);
		
		$numberOfReview_html = $summaryStars->getPlainText();
		preg_match("/([0-9]{1,3}(,[0-9]{3})*(\.[0-9]+)?|\.[0-9]+)/", $numberOfReview_html, $output_array);
		$data["numberreviews"] =  (int)preg_replace('/[^\d]/', '', $output_array[1]);
		
		return $data;
	}
	else return false;
}

function getOurPrice($html){
	$priceblock_ourprice = $html('*[id*="priceblock"]',0);
	if($priceblock_ourprice){
		$ourPrice = getPrice($priceblock_ourprice->getPlainText());		
		return $ourPrice;
	}
	else return false;
}

function getNormalPrice($html){
	$priceblock_ourprice = $html('*[class*="a-text-strike"]',0);
	if($priceblock_ourprice){
		$ourPrice = getPrice($priceblock_ourprice->getPlainText());		
		return $ourPrice;
	}
	else return false;
}

function getPrice($content){
	preg_match('/([0-9]{1,3}(,[0-9]{3})*(\.[0-9]+)?|\.[0-9]+)/', $content, $pricematch);
	$dollar_price = TRIM($pricematch[1]);
	return priceCalculation($dollar_price);
}

function getAlibrispriceShippingPrice($content){
	preg_match_all('/([0-9]{1,3}(,[0-9]{3})*(\.[0-9]+)?|\.[0-9]+)/', $content, $pricematch);
	if($pricematch) return $pricematch;
	else return false;
}

function checkInput($input){
	if (!isset($_GET[$input]) || empty($_GET[$input])) {
    return false;
	}
	//else return "<![CDATA[".TRIM($_GET[$input])."]]>";
	else return TRIM($_GET[$input]);
	//else return TRIM($_GET[$input]);
}

function checkPostInput($input){
	if (!isset($_POST[$input]) || empty($_POST[$input])) {
    return false;
	}
	//else return "<![CDATA[".TRIM($_GET[$input])."]]>";
	else return TRIM($_POST[$input]);
	//else return TRIM($_GET[$input]);
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


function priceCalculation($price){
	if(trim($_GET['domain'] == "de")) {
		$find = array(".",",");
		$replace = array("", ".");
		$decimelSeparator = ",";
	}
	else{
		$find = array(",");
		$replace = array("");
		$decimelSeparator = ".";
	}
	$finalPrice =  str_replace($find,$replace,$price);
	if(!empty($finalPrice)) return number_format($finalPrice,2,$decimelSeparator,"");
	else return false;
}



function getOurPriceDOM($xpath){
	$priceblock_ourprice = $xpath->query("//span[contains(@id,'priceblock')]/text()")->item(0)->nodeValue;
	if($priceblock_ourprice){
		$ourPrice = getPrice($priceblock_ourprice);		
		return $ourPrice;
	}
	else return false;
}

function getNormalPriceDOM($xpath){
	$priceblock_ourprice = $xpath->query("//*[contains(@class,'a-text-strike')]/text()")->item(0)->nodeValue;
	if($priceblock_ourprice){
		$ourPrice = getPrice($priceblock_ourprice);		
		return $ourPrice;
	}
	else return false;
}

function getMainStarsDOM($xpath){
	$summaryStars = $xpath->query("//*[@id='summaryStars']")->item(0);
	//print_r($summaryStars);
	if($summaryStars){
		$data = array();
		$numberofstar_a = $xpath->query(".//a/@title", $summaryStars)->item(0)->nodeValue;
		//print_r($numberofstar_a) ;
		$data["numberstar"] = getStars($numberofstar_a);
		
		$numberOfReview_html = $summaryStars->textContent;
		//print_r($numberOfReview_html);
		preg_match("/([0-9]{1,3}(,[0-9]{3})*(\.[0-9]+)?|\.[0-9]+)/", $numberOfReview_html, $output_array);
		$data["numberreviews"] =  (int)preg_replace('/[^\d]/', '', $output_array[1]);
		//print_r($data);
		return $data;
	}
	else return false;
}

function get_main_rankDOM($xpath){
	$SalesRank_plain = $xpath->query("//*[@id='SalesRank']")->item(0)->textContent;
	//print_r($SalesRank_plain);
	if($SalesRank_plain){
		preg_match("/([0-9]{1,3}(,[0-9]{3})*(\.[0-9]+)?|\.[0-9]+)/", $SalesRank_plain, $output_array);
		return (int)preg_replace('/[^\d]/', '', $output_array[1]);
	}
	else return false;

}

function RobotTest($xpath){
	global $proxy;
	$robotTest = $xpath->query("//title[contains(., 'Robot Check')]")->item(0);
	if($robotTest) {
		$recpatcha = new stdClass;
		$recpatcha->amzn = $xpath->query("//*[@name='amzn']/@value")->item(0)->nodeValue;
		$recpatcha->{"amzn-r"} = $xpath->query("//*[@name='amzn-r']/@value")->item(0)->nodeValue;
		$recpatcha->{"amzn-pt"} = $xpath->query("//*[@name='amzn-pt']/@value")->item(0)->nodeValue;
		$recpatcha->{"field-keywords"} = "";
		$recpatcha->imagesrc = $xpath->query("//div[@class='a-row a-text-center']/img/@src")->item(0)->nodeValue;
		$recpatcha->proxy = $proxy;
		//print_r($recpatcha);
		sendEmail($proxy);
		return $recpatcha;
	}
	else return false;
}

function getMainCategory($xpath){
	$mainCategoryUrl = $xpath->query("//a[@class='a-link-normal a-color-tertiary']/@href")->item(0)->nodeValue;
	if($mainCategoryUrl){
		//echo $mainCategoryUrl.NL;
		parse_str($mainCategoryUrl, $mainCategory);
		//print_r($mainCategory);
		if($mainCategory["node"]) return $mainCategory["node"];
		else return false;
	}
	else return false;	
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

function appendcsv($completedata,$file,$count){
	//global $file;
	//global $count;
	if(is_object($completedata)) $completedata = (array) $completedata;
	if($count == "0") fputcsv($file,array_keys($completedata));
	$count++;
	
	$content = array_values($completedata);
	fputcsv($file,$content);	
	return true;
}

function calculateTimeConsumed($end = false){
	global $start;
	if(!$end) $start = microtime(true);
	else {
		$time_elapsed_secs = microtime(true) - $start;
		echo "time Consumed ".$time_elapsed_secs.NL;
	}
	return $time_elapsed_secs;
}

function sendEmail($proxy){
	$to = "dr.rakeshgarg@gmail.com";
	$subject = "IP address Amazon Need Alert";
	$txt = $proxy;
	$headers = "From: info@freeopd.com" . "\r\n";
	mail($to,$subject,$txt,$headers);
}

function getInnerHtml($node){
	if($node) return $node->ownerDocument->saveXML($node);
	else return false;
}

function blockISBN($ISBN, $reason = "Manual Block"){
	$finalProduct = new stdclass;
	$finalProduct->amazonpricestatus = "2222-12-22 22:22:22";
	$finalProduct->abepricestatus = "2222-12-22 22:22:22";	
	$finalProduct->quantity = 0;
	updateDBField("ASIN", $ISBN, "abeStock", $finalProduct);
	//print_r($finalProduct);
	
	unset($finalProduct->quantity);
	$finalProduct->alibrispricestatus = "2222-12-22 22:22:22";	
	updateDBField("ASIN", $ISBN, "product", $finalProduct);
	//print_r($finalProduct);
	
	
	unset($finalProduct->abepricestatus);
	$finalProduct->QuantityforSale = 0;
	updateDBField("ASIN", $ISBN, "alibrisStock", $finalProduct);
	//print_r($finalProduct);
	
	unset($finalProduct);
	$finalProduct = new stdclass;
	$finalProduct->ISBN = $ISBN;
	$finalProduct->reason = $reason;	
	insertDBField("blockedISBN", $finalProduct);
	//deleteDBField("ISBN", $ISBN, "blockedISBN");
	deleteDBField("abeprice", "ASIN", $ISBN);
	deleteDBField("alibrisprice", "ASIN", $ISBN );
	deleteDBField("amazonprice", "ASIN", $ISBN);
}

function unblockISBN($ISBN, $reason = "Manual Block"){
	$finalProduct = new stdclass;
	$finalProduct->amazonpricestatus = "0000-00-00 00:00:00";
	$finalProduct->abepricestatus = "0000-00-00 00:00:00";	
	$finalProduct->quantity = 0;
	updateDBField("ASIN", $ISBN, "abeStock", $finalProduct);
	//print_r($finalProduct);
	
	unset($finalProduct->quantity);
	$finalProduct->alibrispricestatus = "0000-00-00 00:00:00";	
	updateDBField("ASIN", $ISBN, "product", $finalProduct);
	//print_r($finalProduct);
	
	
	unset($finalProduct->abepricestatus);
	$finalProduct->QuantityforSale = 0;
	updateDBField("ASIN", $ISBN, "alibrisStock", $finalProduct);
	//print_r($finalProduct);
	
	unset($finalProduct);
	$finalProduct = new stdclass;
	$finalProduct->ISBN = $ISBN;
	$finalProduct->reason = $reason;	
	//insertDBField("blockedISBN", $finalProduct);
	deleteDBField("blockedISBN", "ISBN", $ISBN);
	//deleteDBField("ASIN", $ISBN, "abeprice");
	//deleteDBField("ASIN", $ISBN, "alibrisprice");
	//deleteDBField("ASIN", $ISBN, "amazonprice");
}

function updateISBN($ASIN, $ISBN){
	$finalProduct = new stdclass;
	//$finalProduct->ASIN = $ASIN;
	$finalProduct->ISBN = $ISBN;	
	updateDBField("ASIN", $ASIN, "product", $finalProduct);
	updateDBField("ASIN", $ASIN, "abeStock", array("quantity" => 0));
	updateDBField("ASIN", $ASIN, "alibrisStock", array("QuantityforSale" => 0));
	print_r($finalProduct);
	deleteDBField( "abeprice", "ASIN", $ASIN);
	deleteDBField("alibrisprice", "ASIN", $ASIN);
	
	//deleteDBField("ISBN", $ISBN, "blockedISBN");
	//deleteDBField("ASIN", $ISBN, "amazonprice");
}

function calculateLastTime($time = 60){
	$currentDate = strtotime("now");
	$futureDate = $currentDate-(60*$time);
	$formatDate = date("Y-m-d H:i:s", $futureDate);
	return $formatDate;
}

function exchangeValues(&$x,&$y) {
    $tmp=$x;
    $x=$y;
    $y=$tmp;
}

function currentTime(){
	$time_now=mktime(date('h')+5,date('i')+30,date('s'));
	$date = date('d-m-Y H:i', $time_now);
	echo $date.NL;
	
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
 

function select_checkBox($ASIN){
	?>
	<form method="post" action ="Data_Submit.php">
		<?php 
			//print_r($ASIN);
			$postvalue = base64_encode(json_encode($ASIN));  
			$ASIN['checkBox'] = '<input type="checkbox" value="'.$postvalue.'" name="checkbox[]"</input>';
			$ASIN['smallimage'] = '<img src="'.$ASIN['smallimage'].'"/>';
			
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
	function data_to_Table($shop){
		?><?php if (count($shop) > 0): ?>
			<table>
			  <thead>
				<tr>
				  <th><?php echo implode('</th><th>', array_keys(current($shop))); ?></th>
				</tr>
			  </thead>
			  <tbody>
			<?php foreach ($shop as $row): array_map('htmlentities', $row); ?>
				<tr>
				  <td><?php echo implode('</td><td>', $row); ?></td>
				</tr>
			<?php endforeach; ?>
			  </tbody>
			</table>
			<?php endif; ?><?php
	} 
	function plain_url_to_link($string) {
  return preg_replace(
  '%(https?|ftp)://([-A-Z0-9./_*?&;=#]+)%i', 
  '<a target="blank" rel="nofollow" href="$0" target="_blank">$0</a>', $string);
}
	
	function AmazonTotalItemsPerPage($TotalItems){

		$TotalItemsArray = preg_split("/[\s]+/",$TotalItems); 

		$ItemsPerPageArray = explode("-",$TotalItemsArray[0]);

		$ItemsPerPageArray = preg_replace("/\,/", "", $ItemsPerPageArray);

		$ItemsPerPage = $ItemsPerPageArray[1]-$ItemsPerPageArray[0]; 

		return $ItemsPerPage;

	}
?>