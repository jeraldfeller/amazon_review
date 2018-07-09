<?php  
	//echo "<pre>";
	//ini_set('display_startup_errors', 1);
	//ini_set('display_errors', -1);
	error_reporting(0);
	ini_set("memory_limit", "-1");
	require_once('../includes/functions.php');
	function selectDBFiel_dorder_by1($table, $field = false, $value = false,$order_field, $columns = false,$limit = false,$order = 'DESC',$between = false){

		$db = MysqliDatabaseconnect();
		if($limit) $db->limit($limit);
		if($field) $db->where($field, $value);
		if($between) $db->between($between['field'], $between['value1'], $between['value2'],$between['type'] = 'AND');
		if($order_field) $db->order_by($order_field,$order);  //DESC  //ASC
		if($columns) $results = $db->select($columns)->from($table)->fetch();
		else $results = $db->select()->from($table)->fetch();
		//print_r($results);
		//echo $db->last_query().NL;
		$db->close();
		if(!empty($results)) return $results;
		else return false;
	}
	if($_GET['ID1'] && $_GET['ID2'] && $_GET['ID2']>$_GET['ID1']){
		$between['field'] ='id';
		$between['value1'] =$_GET['ID1'];
		$between['value2'] = $_GET['ID2']; 
	
		$values = selectDBFiel_dorder_by1("amazonReview","status",0,'id',false,false,'ASC',$between); 
		//$values = selectDBField("amazonReview");
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
					 unset($value["pageNo"]);
					$value['amazonLink'] ='https://www.amazon.it/dp/'.$value["asin"];   
					$value['amazonLink'] = '=HYPERLINK("'.$value['amazonLink'].'")';
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
				}
				fclose($fp);
				downloadFile($file); 
				 
			}else{
				echo "process of downloading data to file is done";
				 
			} 
		}else{
			echo "No More ASIN Data please submit the csv Data file";
		}
	}else{
		echo 'Please enter valid ID values';
	}
	 
?>