<?php
	//ini_set('display_startup_errors', 1);
	//  ini_set('display_errors', 1);
	//error_reporting(0);
	require_once('includes/functions.php');  
	echo "<pre>";  
	$table = 'john2';  
	$results = selectDBField($table,"status1",'0',false,1);  
		print_r($results);
	if($results){ 
		foreach($results as $result){  
			if($result['Item_Weight']){
				$items_Details['we']=$result['Item_Weight'];
			}elseif($result['shipping_weight']){
				$items_Details['we']=$result['shipping_weight'];
				
			}
			if($result['ProductDimensions']){
				$items_Details['dim']=$result['ProductDimensions'];
			}elseif($result['Dimensions']){
				$items_Details['dim']=$result['Dimensions'];
				
			}   
			 
			//$items_Details['dim'] = $result['dim'];

			if($items_Details['dim']){
				$final_a = explode(' x ',$items_Details['dim']);
				$items_Details['le']=$final_a[0];
				$items_Details['wi']=$final_a[1];
				$items_Details['hi']=$final_a[2];
				
				
			}  
			if($items_Details['hi']){
				$final_a2 = explode(' cm ; ',$items_Details['hi']);
				$items_Details['hi']=$final_a2[0];
				if($final_a2[1]){
					$items_Details['we']=$final_a2[1];  
				}else{
					$final_a1 = explode(' cm',$items_Details['hi']);
					$items_Details['hi']=$final_a1[0];
					
				}
				
			}  
			
			$items_Details['status1'] = 2;
			print_r($items_Details);
			updateDBField("id",$result['id'],$table,$items_Details);
			









/*
		
			 
			
			 /*
			 
			 
			 
			 
			if($result['le'] == 'A' or $result['le'] == 'NA' or $result['le'] == 'NA1'){
				$items_Details['dim']=$result['ProductDimensions'];
				$final_a = explode(' x ',$items_Details['dim']);
				$items_Details['le']=$final_a[0];
				$items_Details['wi']=$final_a[1];
				$items_Details['hi']=$final_a[2];
			}   
			$items_Details['status1'] = 11;
			print_r($items_Details);
			updateDBField("id",$result['id'],$table,$items_Details);
			 */
			 
			
			
			
			
			
			
			 
			/*$new = explode('on',$result['first_review_date1']);
			print_r($result);
			$result['first_review_date1'] =$new[1];
			print_r($result);
			/*$path =  realpath(dirname(__FILE__));
			$file = $path.'/overstock/'.$result['sku'].'.jpg';
			$image_content = file_get_contents($result['image']);
			file_put_contents($file,$image_content); */ 
			 
			//$items_Details['status'] =14; 	
			//$items_Details['first_review_date'] =date('m/d/Y', strtotime($result['first_review_date1']));; 	
			/*$date1=date_create("02/06/2018"); 
			$date2=date_create($result['first_review_date1']); 
			$diff=date_diff($date2,$date1);
			$items_Details['total_days'] = $diff->format("%R%a days");
			//print_r($items_Details);
			// updateDBField("id",$result['id'],$table,$items_Details);
			//echo'<meta http-equiv="refresh" content="1;">';
			//echo'<meta http-equiv="refresh" content="1;">';
			 */
		}
		             echo'<meta http-equiv="refresh" content="0;">';
	}else{
		echo 'no more data';
		
	}
	   